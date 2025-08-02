<?php
/**
 * Sistema de Migrations - TypeX Hub
 * 
 * Este script gerencia as migrations do banco de dados, permitindo:
 * - Aplicar migrations pendentes
 * - Verificar status das migrations
 * - Rollback de migrations (opcional)
 * - Criar novas migrations
 * - Deve ser executado dentro do container
 * 
 * Uso:
 * docker exec -it typexhub php database/migrate.php                    # Aplica todas as migrations pendentes
 * docker exec -it typexhub php database/migrate.php status             # Mostra status das migrations
 * php migrate.php rollback           # Desfaz a última migration
 * php migrate.php --create nome      # Cria uma nova migration
 */

// Configurações do banco de dados
$envPath = '.env';
if (file_exists($envPath)) {
    // INI_SCANNER_RAW preserva as aspas, se houver
    $vars = parse_ini_file($envPath, false, INI_SCANNER_RAW);

    foreach ($vars as $key => $value) {
        // opcional: remover aspas simples/duplas
        $value = trim($value, "'\"");

        // coloca no ambiente
        putenv("$key=$value");
        $_ENV[$key]    = $value;
        $_SERVER[$key] = $value;
    }
}

/**
 * Tenta descobrir o Git user.name configurado, seja por comando ou
 * lendo .git/config ou ~/.gitconfig. Se nada for encontrado,
 * retorna o usuário do sistema (get_current_user()).
 *
 * @return string
 */
function getGitUsername(): string
{
    // 1) Tentar via comando shell (se shell_exec estiver habilitado)
    if (function_exists('shell_exec')) {
        $name = trim(@shell_exec('git config --get user.name 2>/dev/null'));
        if ($name !== '') {
            return $name;
        }
    }

    // 2) Procurar em arquivos de config: local e global
    $configFiles = [
        __DIR__ . '/.git/config',           // config do repositório
        getenv('HOME') . '/.gitconfig',     // config global do usuário
    ];
    foreach ($configFiles as $file) {
        if (file_exists($file) && is_readable($file)) {
            $lines     = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $inUserSec = false;
            foreach ($lines as $line) {
                $line = trim($line);
                // Entramos na seção [user]
                if (preg_match('/^\[user\]/i', $line)) {
                    $inUserSec = true;
                    continue;
                }
                // Se chegamos em outra seção, saímos
                if ($inUserSec && preg_match('/^\[.+\]/', $line)) {
                    break;
                }
                // Dentro de [user], buscar “name = ...”
                if ($inUserSec && preg_match('/^name\s*=\s*(.+)$/i', $line, $m)) {
                    return trim($m[1]);
                }
            }
        }
    }

    // 3) Fallback: usuário do sistema de arquivos
    return get_current_user();
}

// em qualquer outro ponto da sua aplicação
$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$dbHost;port=3306;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexao com banco estabelecida com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro na conexao: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Cria a tabela de controle de migrations se não existir
 */
function createMigrationsTable($pdo) {
    $sql = "
        CREATE TABLE IF NOT EXISTS migrations_controle (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration_name VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT NULL,
            batch_number INT NOT NULL,
            executed TINYINT(1) DEFAULT 0,
            INDEX idx_migration_name (migration_name),
            INDEX idx_batch (batch_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($sql);
}

/**
 * Obtém todas as migrations executadas
 */
function getExecutedMigrations($pdo) {
    $stmt = $pdo->query("SELECT migration_name FROM migrations_controle WHERE executed = 1 ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Obtém todas as migrations disponíveis para execução
 * Verifica quais migrations do diretório ainda não foram executadas no banco
 */
function getAvailableMigrations($pdo) {
    // Garante que a tabela de controle existe
    createMigrationsTable($pdo);
    
    // Obtém todas as migrations do diretório
    $migrationsDir = __DIR__ . '/migrations/';
    $files = glob($migrationsDir . '*.sql');
    $allMigrations = [];
    
    foreach ($files as $file) {
        $allMigrations[] = basename($file, '.sql');
    }
    
    sort($allMigrations);
    
    // Obtém as migrations já executadas no banco
    $executedMigrations = getExecutedMigrations($pdo);
    
    // Retorna apenas as migrations que ainda não foram executadas
    $availableMigrations = array_diff($allMigrations, $executedMigrations);
    
    return array_values($availableMigrations); // Reindexa o array
}

/**
 * Obtém o próximo número de batch
 */
function getNextBatchNumber($pdo) {
    $stmt = $pdo->query("SELECT COALESCE(MAX(batch_number), 0) + 1 as next_batch FROM migrations_controle");
    return $stmt->fetch(PDO::FETCH_ASSOC)['next_batch'];
}

/**
 * Executa uma migration
 */
function executeMigration($pdo, $migrationName) {
    $migrationFile = __DIR__ . '/migrations/' . $migrationName . '.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: $migrationFile");
    }
    
    $sql = file_get_contents($migrationFile);
    
    $pdo->beginTransaction();
    
    try {
        // Divide o SQL em declarações individuais e executa cada uma
        $statements = explode(';', $sql);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            
            // Ignora linhas vazias e comentários
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                $pdo->exec($statement);
            }
        }
        
        // Registra a migration como executada
        $batchNumber = getNextBatchNumber($pdo);
        
        // Verifica se já existe um registro para esta migration
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations_controle WHERE migration_name = ?");
        $stmt->execute([$migrationName]);
        $exists = $stmt->fetchColumn() > 0;
        
        if ($exists) {
            // Atualiza o registro existente
            $stmt = $pdo->prepare("UPDATE migrations_controle SET executed = 1, executed_at = CURRENT_TIMESTAMP WHERE migration_name = ?");
            $stmt->execute([$migrationName]);
        } else {
            // Insere novo registro
            $stmt = $pdo->prepare("INSERT INTO migrations_controle (migration_name, batch_number, executed, executed_at) VALUES (?, ?, 1, CURRENT_TIMESTAMP)");
            $stmt->execute([$migrationName, $batchNumber]);
        }
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        throw $e;
    }
}

/**
 * Executa todas as migrations pendentes
 */
function runMigrations($pdo) {
    createMigrationsTable($pdo);
    showStatus($pdo);
    
    $available = getAvailableMigrations($pdo);
    $pending = $available; // Agora getAvailableMigrations já retorna apenas as pendentes
    
    echo "Migrations disponíveis: " . count($pending) . "\n";
    if (!empty($pending)) {
        echo "Lista: " . implode(', ', $pending) . "\n";
    }
    
    if (empty($pending)) {
        echo "Todas as migrations ja foram executadas!\n";
        return;
    }
    
    echo "Executando " . count($pending) . " migration(s) pendente(s)...\n\n";
    
    foreach ($pending as $migration) {
        try {
            echo "Executando: $migration... ";
            executeMigration($pdo, $migration);
            echo "Sucesso!\n";
        } catch (Exception $e) {
            echo "Erro!\n";
            echo "Erro em $migration: " . $e->getMessage() . "\n";
            echo "Detalhes: " . $e->getTraceAsString() . "\n";
            break;
        }
    }
    
    echo "\nMigrations executadas com sucesso!\n";
}

/**
 * Mostra o status das migrations
 */
function showStatus($pdo) {
    createMigrationsTable($pdo);
    
    $executed = getExecutedMigrations($pdo);
    
    // Obtém todas as migrations do diretório
    $migrationsDir = __DIR__ . '/migrations/';
    $files = glob($migrationsDir . '*.sql');
    $allMigrationsFromDir = [];
    
    foreach ($files as $file) {
        $allMigrationsFromDir[] = basename($file, '.sql');
    }
    sort($allMigrationsFromDir);
    
    // Calcula as migrations pendentes
    $pending = array_diff($allMigrationsFromDir, $executed);
    
    // Verifica migrations pendentes sem registro no banco e insere automaticamente
    $insertedMigrations = [];
    if (!empty($pending)) {
        $batchNumber = getNextBatchNumber($pdo);
        
        foreach ($pending as $migration) {
            // Verifica se a migration já tem registro no banco
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations_controle WHERE migration_name = ?");
            $stmt->execute([$migration]);
            $exists = $stmt->fetchColumn() > 0;
            
            if (!$exists) {
                // Insere o registro da migration pendente
                try {
                    $stmt = $pdo->prepare("INSERT INTO migrations_controle (migration_name, batch_number, executed) VALUES (?, ?, 0)");
                    $stmt->execute([$migration, $batchNumber]);
                    $insertedMigrations[] = $migration;
                } catch (Exception $e) {
                    echo "⚠️  Aviso: Não foi possível registrar migration '$migration': " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "STATUS DAS MIGRATIONS\n";
    echo "========================\n\n";
    
    // Mostra migrations recém-registradas
    if (!empty($insertedMigrations)) {
        echo "🆕 Migrations registradas automaticamente (" . count($insertedMigrations) . "):\n";
        foreach ($insertedMigrations as $migration) {
            echo "   - $migration\n";
        }
        echo "\n";
    }
    
    echo "Executadas (" . count($executed) . "):\n";
    foreach ($executed as $migration) {
        echo "   - $migration\n";
    }
    
    if (!empty($pending)) {
        echo "\nPendentes (" . count($pending) . "):\n";
        foreach ($pending as $migration) {
            echo "   - $migration\n";
        }
    } else {
        echo "\nNenhuma migration pendente!\n";
    }
    
    echo "\n";
}

/**
 * Cria uma nova migration
 */
function createMigration($pdo, $migrationName) {
    // Garante que a tabela de controle existe
    createMigrationsTable($pdo);
    
    // Valida o nome da migration
    if (empty($migrationName)) {
        echo "Erro: Nome da migration é obrigatório!\n";
        echo "Uso: php migrate.php --create nome_da_migration\n";
        return false;
    }
    
    // Remove caracteres especiais e espaços do nome
    $migrationName = preg_replace('/[^a-zA-Z0-9_]/', '_', $migrationName);
    $migrationName = preg_replace('/_+/', '_', $migrationName);
    $migrationName = trim($migrationName, '_');
    
    // Gera o próximo número de migration
    $migrationsDir = __DIR__ . '/migrations/';
    $files = glob($migrationsDir . '*.sql');
    $maxNumber = 0;
    
    foreach ($files as $file) {
        $filename = basename($file, '.sql');
        if (preg_match('/^(\d+)_/', $filename, $matches)) {
            $number = (int)$matches[1];
            if ($number > $maxNumber) {
                $maxNumber = $number;
            }
        }
    }
    
    $nextNumber = $maxNumber + 1;
    $migrationFileName = sprintf('%03d_%s.sql', $nextNumber, $migrationName);
    $migrationFilePath = $migrationsDir . $migrationFileName;
    
    // Verifica se o arquivo já existe
    if (file_exists($migrationFilePath)) {
        echo "Erro: Migration '$migrationFileName' já existe!\n";
        return false;
    }
    
    // Cria o conteúdo do arquivo de migration
    $migrationContent = "-- ==================== MIGRATION {$nextNumber}: " . strtoupper($migrationName) . " ====================\n";
    $migrationContent .= "-- Data: " . date('Y-m-d H:i:s') . "\n";
    $migrationContent .= "-- Autor: " . (getGitUsername() ?: 'sistema') . "\n";
    $migrationContent .= "-- Descrição: " . ucfirst(str_replace('_', ' ', $migrationName)) . "\n\n";
    
    // Cria o arquivo
    if (file_put_contents($migrationFilePath, $migrationContent)) {
        echo "✅ Migration criada com sucesso!\n";
        echo "📁 Arquivo: $migrationFilePath\n";
        echo "📝 Edite o arquivo e execute 'php migrate.php run' para aplicar\n";
        
        // Registra a migration no banco como pendente (igual ao showStatus)
        $batchNumber = getNextBatchNumber($pdo);
        try {
            $stmt = $pdo->prepare("INSERT INTO migrations_controle (migration_name, batch_number, executed) VALUES (?, ?, 0)");
            $stmt->execute([basename($migrationFileName, '.sql'), $batchNumber]);
            echo "📊 Migration registrada no banco como pendente\n";
        } catch (Exception $e) {
            echo "⚠️  Aviso: Não foi possível registrar migration no banco: " . $e->getMessage() . "\n";
        }

        return true;
    } else {
        echo "❌ Erro ao criar o arquivo de migration!\n";
        return false;
    }
}

// Processamento dos argumentos da linha de comando
$command = isset($argv[1]) ? $argv[1] : 'run';
$migrationName = isset($argv[2]) ? $argv[2] : '';

switch ($command) {
    case 'status':
        showStatus($pdo);
        break;
        
    case 'create':
        createMigration($pdo, $migrationName);
        break;
    case 'run':
    default:
        runMigrations($pdo);
        break;
}
