<?php
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath) && is_readable($envPath)) {
    $vars = @parse_ini_file($envPath, false, INI_SCANNER_RAW);
    if ($vars !== false && is_array($vars)) {
        foreach ($vars as $key => $value) {
            $value = trim($value, "'\"");
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

function getGitUsername(): string
{
    if (function_exists('shell_exec')) {
        $name = trim(@shell_exec('git config --get user.name 2>/dev/null'));
        if ($name !== '') {
            return $name;
        }
    }
    
    $configFiles = [
        __DIR__ . '/.git/config',
        getenv('HOME') . '/.gitconfig',
    ];
    foreach ($configFiles as $file) {
        if (file_exists($file) && is_readable($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $inUserSec = false;
            foreach ($lines as $line) {
                $line = trim($line);
                if (preg_match('/^\[user\]/i', $line)) {
                    $inUserSec = true;
                    continue;
                }
                if ($inUserSec && preg_match('/^\[.+\]/', $line)) {
                    break;
                }
                if ($inUserSec && preg_match('/^name\s*=\s*(.+)$/i', $line, $m)) {
                    return trim($m[1]);
                }
            }
        }
    }
    return get_current_user();
}

$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$dbHost;port=3306;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
    echo "Conexao com banco estabelecida com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro na conexao: " . $e->getMessage() . "\n";
    exit(1);
}

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

function getExecutedMigrations($pdo) {
    $stmt = $pdo->query("SELECT migration_name FROM migrations_controle WHERE executed = 1 ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getAvailableMigrations($pdo) {
    createMigrationsTable($pdo);
    
    $migrationsDir = __DIR__ . '/migrations/';
    $files = glob($migrationsDir . '*.sql');
    $allMigrations = [];
    
    foreach ($files as $file) {
        $allMigrations[] = basename($file, '.sql');
    }
    
    sort($allMigrations);
    $executedMigrations = getExecutedMigrations($pdo);
    $availableMigrations = array_diff($allMigrations, $executedMigrations);
    
    return array_values($availableMigrations);
}

function getNextBatchNumber($pdo) {
    $stmt = $pdo->query("SELECT COALESCE(MAX(batch_number), 0) + 1 as next_batch FROM migrations_controle");
    return $stmt->fetch(PDO::FETCH_ASSOC)['next_batch'];
}

function parseSqlStatements($sql) {
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    $statements = [];
    $current = '';
    $inString = false;
    $stringChar = '';
    $escapeNext = false;
    
    for ($i = 0; $i < strlen($sql); $i++) {
        $char = $sql[$i];
        
        if ($escapeNext) {
            $escapeNext = false;
            $current .= $char;
            continue;
        }
        
        if ($char === '\\') {
            $escapeNext = true;
            $current .= $char;
            continue;
        }
        
        if (!$inString && ($char === '"' || $char === "'")) {
            $inString = true;
            $stringChar = $char;
        } elseif ($inString && $char === $stringChar) {
            $inString = false;
        } elseif (!$inString && $char === ';') {
            $current = trim($current);
            if (!empty($current)) {
                $statements[] = $current;
            }
            $current = '';
            continue;
        }
        
        $current .= $char;
    }
    
    $current = trim($current);
    if (!empty($current)) {
        $statements[] = $current;
    }
    
    return $statements;
}

function executeMigration($pdo, $migrationName) {
    $migrationFile = __DIR__ . '/migrations/' . $migrationName . '.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: $migrationFile");
    }
    
    echo "📄 Lendo arquivo: $migrationFile\n";
    $sql = file_get_contents($migrationFile);
    echo "📏 Tamanho do arquivo: " . strlen($sql) . " bytes\n";
    
    if (empty($sql)) {
        throw new Exception("Migration file is empty: $migrationFile");
    }
    
    $statements = parseSqlStatements($sql);
    echo "🔧 Encontradas " . count($statements) . " declarações SQL\n";
    
    if (empty($statements)) {
        throw new Exception("No valid SQL statements found in migration");
    }
    
    $transactionStarted = false;
    
    try {
        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
            $transactionStarted = true;
            echo "🔒 Transação iniciada\n";
        }
        
        $executedStatements = 0;
        
        foreach ($statements as $index => $statement) {
            $statement = trim($statement);
            
            if (empty($statement)) {
                continue;
            }
            
            echo "🔄 Executando declaração " . ($index + 1) . ": " . substr($statement, 0, 50) . "...\n";
            
            try {
                $result = $pdo->exec($statement);
                echo "   ✅ Sucesso (linhas afetadas: " . ($result !== false ? $result : 'N/A') . ")\n";
                $executedStatements++;
            } catch (PDOException $e) {
                echo "   ❌ ERRO: " . $e->getMessage() . "\n";
                echo "   📝 SQL: " . substr($statement, 0, 200) . "...\n";
                throw $e;
            }
        }
        
        if ($executedStatements === 0) {
            throw new Exception("Nenhuma declaração SQL foi executada");
        }
        
        echo "📝 Registrando migration como executada...\n";
        $batchNumber = getNextBatchNumber($pdo);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations_controle WHERE migration_name = ?");
        $stmt->execute([$migrationName]);
        $exists = $stmt->fetchColumn() > 0;
        
        if ($exists) {
            $stmt = $pdo->prepare("UPDATE migrations_controle SET executed = 1, executed_at = CURRENT_TIMESTAMP WHERE migration_name = ?");
            $stmt->execute([$migrationName]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO migrations_controle (migration_name, batch_number, executed, executed_at) VALUES (?, ?, 1, CURRENT_TIMESTAMP)");
            $stmt->execute([$migrationName, $batchNumber]);
        }
        
        if ($transactionStarted && $pdo->inTransaction()) {
            $pdo->commit();
            echo "✅ Transação commitada com sucesso\n";
        }
        
        echo "✅ Migration registrada como executada no banco\n";
        return true;
        
    } catch (Exception $e) {
        if ($transactionStarted && $pdo->inTransaction()) {
            $pdo->rollback();
            echo "❌ ROLLBACK executado - nenhuma alteração foi salva\n";
        }
        throw $e;
    }
}

function verifyMigrationResults($pdo, $migrationName) {
    echo "\n🔍 Verificando resultados da migration...\n";
    
    try {
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "📋 Tabelas encontradas no banco:\n";
        foreach ($tables as $table) {
            echo "   - $table\n";
        }
        
        $stmt = $pdo->prepare("SELECT executed FROM migrations_controle WHERE migration_name = ?");
        $stmt->execute([$migrationName]);
        $executed = $stmt->fetchColumn();
        
        if ($executed == 1) {
            echo "✅ Migration confirmada como executada no registro\n";
            return true;
        } else {
            echo "⚠️  ATENÇÃO: Migration não confirmada no registro\n";
            return false;
        }
        
    } catch (Exception $e) {
        echo "❌ Erro ao verificar tabelas: " . $e->getMessage() . "\n";
        return false;
    }
}

function runMigrations($pdo) {
    createMigrationsTable($pdo);
    showStatus($pdo);
    
    $available = getAvailableMigrations($pdo);
    $pending = $available;
    
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
            echo "🚀 Executando: $migration...\n";
            executeMigration($pdo, $migration);
            
            if (verifyMigrationResults($pdo, $migration)) {
                echo "✅ $migration: SUCESSO CONFIRMADO!\n\n";
            } else {
                echo "⚠️  $migration: Executada mas com resultados duvidosos\n\n";
            }
            
        } catch (Exception $e) {
            echo "❌ ERRO em $migration:\n";
            echo "   Mensagem: " . $e->getMessage() . "\n";
            echo "   Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
            break;
        }
    }
    
    echo "\n🎉 Processo de migrations finalizado!\n";
}

function showStatus($pdo) {
    createMigrationsTable($pdo);
    
    $executed = getExecutedMigrations($pdo);
    
    $migrationsDir = __DIR__ . '/migrations/';
    $files = glob($migrationsDir . '*.sql');
    $allMigrationsFromDir = [];
    
    foreach ($files as $file) {
        $allMigrationsFromDir[] = basename($file, '.sql');
    }
    sort($allMigrationsFromDir);
    
    $pending = array_diff($allMigrationsFromDir, $executed);
    
    $insertedMigrations = [];
    if (!empty($pending)) {
        $batchNumber = getNextBatchNumber($pdo);
        
        foreach ($pending as $migration) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations_controle WHERE migration_name = ?");
            $stmt->execute([$migration]);
            $exists = $stmt->fetchColumn() > 0;
            
            if (!$exists) {
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

function createMigration($pdo, $migrationName) {
    createMigrationsTable($pdo);
    
    if (empty($migrationName)) {
        echo "Erro: Nome da migration é obrigatório!\n";
        echo "Uso: php migrate.php --create nome_da_migration\n";
        return false;
    }
    
    $migrationName = preg_replace('/[^a-zA-Z0-9_]/', '_', $migrationName);
    $migrationName = preg_replace('/_+/', '_', $migrationName);
    $migrationName = trim($migrationName, '_');
    
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
    
    if (file_exists($migrationFilePath)) {
        echo "Erro: Migration '$migrationFileName' já existe!\n";
        return false;
    }
    
    $migrationContent = "-- ==================== MIGRATION {$nextNumber}: " . strtoupper($migrationName) . " ====================\n";
    $migrationContent .= "-- Data: " . date('Y-m-d H:i:s') . "\n";
    $migrationContent .= "-- Autor: " . (getGitUsername() ?: 'sistema') . "\n";
    $migrationContent .= "-- Descrição: " . ucfirst(str_replace('_', ' ', $migrationName)) . "\n\n";
    
    if (file_put_contents($migrationFilePath, $migrationContent)) {
        echo "✅ Migration criada com sucesso!\n";
        echo "📁 Arquivo: $migrationFilePath\n";
        echo "📝 Edite o arquivo e execute 'php migrate.php run' para aplicar\n";
        
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