<?php
/**
 * Sistema de Seeders - TypeX Hub
 * 
 * Este script gerencia os seeders do banco de dados, permitindo:
 * - Aplicar seeders pendentes
 * - Verificar status dos seeders
 * - Rollback de seeders (opcional)
 * - Criar novos seeders
 * 
 * Uso:
 * docker exec -it typexhub php database/seeder.php                    # Aplica todos os seeders pendentes
 * docker exec -it typexhub php database/seeder.php status             # Mostra status dos seeders
 * docker exec -it typexhub php database/seeder.php rollback           # Desfaz o último seeder
 * docker exec -it typexhub php database/seeder.php --create nome      # Cria um novo seeder
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
            echo "Git username: $name\n";
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
 * Cria a tabela de controle de seeders se não existir
 */
function createSeedersTable($pdo) {
    $sql = "
        CREATE TABLE IF NOT EXISTS seeders_controle (
            id INT AUTO_INCREMENT PRIMARY KEY,
            seeder_name VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT NULL,
            batch_number INT NOT NULL,
            executed TINYINT(1) DEFAULT 0,
            INDEX idx_seeder_name (seeder_name),
            INDEX idx_batch (batch_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($sql);
}

/**
 * Obtém todos os seeders executados
 */
function getExecutedSeeders($pdo) {
    $stmt = $pdo->query("SELECT seeder_name FROM seeders_controle WHERE executed = 1 ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Obtém todos os seeders disponíveis para execução
 * Verifica quais seeders do diretório ainda não foram executados no banco
 */
function getAvailableSeeders($pdo) {
    // Garante que a tabela de controle existe
    createSeedersTable($pdo);
    
    // Obtém todos os seeders do diretório
    $seedersDir = __DIR__ . '/seeders/';
    $files = glob($seedersDir . '*.sql');
    $allSeeders = [];
    
    foreach ($files as $file) {
        $allSeeders[] = basename($file, '.sql');
    }
    
    sort($allSeeders);
    
    // Obtém os seeders já executados no banco
    $executedSeeders = getExecutedSeeders($pdo);
    
    // Retorna apenas os seeders que ainda não foram executados
    $availableSeeders = array_diff($allSeeders, $executedSeeders);
    
    return array_values($availableSeeders); // Reindexa o array
}

/**
 * Obtém o próximo número de batch
 */
function getNextBatchNumber($pdo) {
    $stmt = $pdo->query("SELECT COALESCE(MAX(batch_number), 0) + 1 as next_batch FROM seeders_controle");
    return $stmt->fetch(PDO::FETCH_ASSOC)['next_batch'];
}

/**
 * Executa um seeder
 */
function executeSeeder($pdo, $seederName) {
    $seederFile = __DIR__ . '/seeders/' . $seederName . '.sql';
    
    if (!file_exists($seederFile)) {
        throw new Exception("Seeder file not found: $seederFile");
    }
    
    $sql = file_get_contents($seederFile);
    
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
        
        // Registra o seeder como executado
        $batchNumber = getNextBatchNumber($pdo);
        
        // Verifica se já existe um registro para este seeder
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM seeders_controle WHERE seeder_name = ?");
        $stmt->execute([$seederName]);
        $exists = $stmt->fetchColumn() > 0;
        
        if ($exists) {
            // Atualiza o registro existente
            $stmt = $pdo->prepare("UPDATE seeders_controle SET executed = 1, executed_at = CURRENT_TIMESTAMP WHERE seeder_name = ?");
            $stmt->execute([$seederName]);
        } else {
            // Insere novo registro
            $stmt = $pdo->prepare("INSERT INTO seeders_controle (seeder_name, batch_number, executed, executed_at) VALUES (?, ?, 1, CURRENT_TIMESTAMP)");
            $stmt->execute([$seederName, $batchNumber]);
        }
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        throw $e;
    }
}

/**
 * Executa todos os seeders pendentes
 */
function runSeeders($pdo) {
    createSeedersTable($pdo);
    showStatus($pdo);
    
    $available = getAvailableSeeders($pdo);
    $pending = $available; // Agora getAvailableSeeders já retorna apenas os pendentes
    
    echo "Seeders disponíveis: " . count($pending) . "\n";
    if (!empty($pending)) {
        echo "Lista: " . implode(', ', $pending) . "\n";
    }
    
    if (empty($pending)) {
        echo "Todos os seeders ja foram executados!\n";
        return;
    }
    
    echo "Executando " . count($pending) . " seeder(s) pendente(s)...\n\n";
    
    foreach ($pending as $seeder) {
        try {
            echo "Executando: $seeder... ";
            executeSeeder($pdo, $seeder);
            echo "Sucesso!\n";
        } catch (Exception $e) {
            echo "Erro!\n";
            echo "Erro em $seeder: " . $e->getMessage() . "\n";
            echo "Detalhes: " . $e->getTraceAsString() . "\n";
            break;
        }
    }
    
    echo "\nSeeders executados com sucesso!\n";
}

/**
 * Mostra o status dos seeders
 */
function showStatus($pdo) {
    createSeedersTable($pdo);
    
    $executed = getExecutedSeeders($pdo);
    
    // Obtém todos os seeders do diretório
    $seedersDir = __DIR__ . '/seeders/';
    $files = glob($seedersDir . '*.sql');
    $allSeedersFromDir = [];
    
    foreach ($files as $file) {
        $allSeedersFromDir[] = basename($file, '.sql');
    }
    sort($allSeedersFromDir);
    
    // Calcula os seeders pendentes
    $pending = array_diff($allSeedersFromDir, $executed);
    
    // Verifica seeders pendentes sem registro no banco e insere automaticamente
    $insertedSeeders = [];
    if (!empty($pending)) {
        $batchNumber = getNextBatchNumber($pdo);
        
        foreach ($pending as $seeder) {
            // Verifica se o seeder já tem registro no banco
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM seeders_controle WHERE seeder_name = ?");
            $stmt->execute([$seeder]);
            $exists = $stmt->fetchColumn() > 0;
            
            if (!$exists) {
                // Insere o registro do seeder pendente
                try {
                    $stmt = $pdo->prepare("INSERT INTO seeders_controle (seeder_name, batch_number, executed) VALUES (?, ?, 0)");
                    $stmt->execute([$seeder, $batchNumber]);
                    $insertedSeeders[] = $seeder;
                } catch (Exception $e) {
                    echo "⚠️  Aviso: Não foi possível registrar seeder '$seeder': " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "STATUS DOS SEEDERS\n";
    echo "==================\n\n";
    
    // Mostra seeders recém-registrados
    if (!empty($insertedSeeders)) {
        echo "🆕 Seeders registrados automaticamente (" . count($insertedSeeders) . "):\n";
        foreach ($insertedSeeders as $seeder) {
            echo "   - $seeder\n";
        }
        echo "\n";
    }
    
    echo "Executados (" . count($executed) . "):\n";
    foreach ($executed as $seeder) {
        echo "   - $seeder\n";
    }
    
    if (!empty($pending)) {
        echo "\nPendentes (" . count($pending) . "):\n";
        foreach ($pending as $seeder) {
            echo "   - $seeder\n";
        }
    } else {
        echo "\nNenhum seeder pendente!\n";
    }
    
    echo "\n";
}

/**
 * Cria um novo seeder
 */
function createSeeder($pdo, $seederName) {
    // Garante que a tabela de controle existe
    createSeedersTable($pdo);
    
    // Valida o nome do seeder
    if (empty($seederName)) {
        echo "Erro: Nome do seeder é obrigatório!\n";
        echo "Uso: php seeder.php --create nome_do_seeder\n";
        return false;
    }
    
    // Remove caracteres especiais e espaços do nome
    $seederName = preg_replace('/[^a-zA-Z0-9_]/', '_', $seederName);
    $seederName = preg_replace('/_+/', '_', $seederName);
    $seederName = trim($seederName, '_');
    
    // Gera o próximo número de seeder
    $seedersDir = __DIR__ . '/seeders/';
    $files = glob($seedersDir . '*.sql');
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
    $seederFileName = sprintf('%03d_%s.sql', $nextNumber, $seederName);
    $seederFilePath = $seedersDir . $seederFileName;
    
    // Verifica se o arquivo já existe
    if (file_exists($seederFilePath)) {
        echo "Erro: Seeder '$seederFileName' já existe!\n";
        return false;
    }
    
    // Cria o conteúdo do arquivo de seeder
    $seederContent = "-- ==================== SEEDER {$nextNumber}: " . strtoupper($seederName) . " ====================\n";
    $seederContent .= "-- Data: " . date('Y-m-d H:i:s') . "\n";
    $seederContent .= "-- Autor: " . (getGitUsername() ?: 'sistema') . "\n";
    $seederContent .= "-- Descrição: " . ucfirst(str_replace('_', ' ', $seederName)) . "\n\n";
    $seederContent .= "-- Exemplo de INSERT:\n";
    $seederContent .= "-- INSERT INTO tabela (coluna1, coluna2) VALUES ('valor1', 'valor2');\n";
    $seederContent .= "-- INSERT INTO tabela (coluna1, coluna2) VALUES ('valor3', 'valor4');\n\n";
    
    // Cria o arquivo
    if (file_put_contents($seederFilePath, $seederContent)) {
        echo "✅ Seeder criado com sucesso!\n";
        echo "📁 Arquivo: $seederFilePath\n";
        echo "📝 Edite o arquivo e execute 'php seeder.php run' para aplicar\n";
        
        // Registra o seeder no banco como pendente (igual ao showStatus)
        $batchNumber = getNextBatchNumber($pdo);
        try {
            $stmt = $pdo->prepare("INSERT INTO seeders_controle (seeder_name, batch_number, executed) VALUES (?, ?, 0)");
            $stmt->execute([basename($seederFileName, '.sql'), $batchNumber]);
            echo "📊 Seeder registrado no banco como pendente\n";
        } catch (Exception $e) {
            echo "⚠️  Aviso: Não foi possível registrar seeder no banco: " . $e->getMessage() . "\n";
        }

        return true;
    } else {
        echo "❌ Erro ao criar o arquivo de seeder!\n";
        return false;
    }
}

// Processamento dos argumentos da linha de comando
$command = isset($argv[1]) ? $argv[1] : 'run';
$seederName = isset($argv[2]) ? $argv[2] : '';

switch ($command) {
    case 'status':
        showStatus($pdo);
        break;
        
    case 'create':
        createSeeder($pdo, $seederName);
        break;
    case 'run':
    default:
        runSeeders($pdo);
        break;
} 