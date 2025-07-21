<?php
/**
 * Sistema de Migrations - TypeX Hub
 * 
 * Este script gerencia as migrations do banco de dados, permitindo:
 * - Aplicar migrations pendentes
 * - Verificar status das migrations
 * - Rollback de migrations (opcional)
 * 
 * Uso:
 * php migrate.php           # Aplica todas as migrations pendentes
 * php migrate.php status    # Mostra status das migrations
 * php migrate.php rollback  # Desfaz a última migration
 */

// Configurações do banco de dados

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
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
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            batch_number INT NOT NULL,
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
    $stmt = $pdo->query("SELECT migration_name FROM migrations_controle ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Obtém todas as migrations disponíveis no diretório
 */
function getAvailableMigrations() {
    $migrationsDir = __DIR__ . '/migrations/';
    $files = glob($migrationsDir . '*.sql');
    $migrations = [];
    
    foreach ($files as $file) {
        $migrations[] = basename($file, '.sql');
    }
    
    sort($migrations);
    return $migrations;
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
    
    // Remove comentários SQL e linhas vazias para execução limpa
    $statements = explode(';', $sql);
    
    $pdo->beginTransaction();
    
    try {
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                $pdo->exec($statement);
            }
        }
        
        // Registra a migration como executada
        $batchNumber = getNextBatchNumber($pdo);
        $stmt = $pdo->prepare("INSERT INTO migrations_controle (migration_name, batch_number) VALUES (?, ?)");
        $stmt->execute([$migrationName, $batchNumber]);
        
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
    
    $executed = getExecutedMigrations($pdo);
    $available = getAvailableMigrations();
    $pending = array_diff($available, $executed);
    
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
    $available = getAvailableMigrations();
    $pending = array_diff($available, $executed);
    
    echo "STATUS DAS MIGRATIONS\n";
    echo "========================\n\n";
    
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
 * Desfaz a última migration (rollback)
 */
function rollbackLastMigration($pdo) {
    createMigrationsTable($pdo);
    
    $stmt = $pdo->query("
        SELECT migration_name, batch_number 
        FROM migrations_controle 
        ORDER BY id DESC 
        LIMIT 1
    ");
    
    $lastMigration = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lastMigration) {
        echo "Nenhuma migration para desfazer!\n";
        return;
    }
    
    $migrationName = $lastMigration['migration_name'];
    echo "ATENCAO: Rollback automatico nao implementado.\n";
    echo "Ultima migration executada: $migrationName\n";
    echo "Para desfazer, voce deve criar manualmente uma nova migration com as alteracoes reversas.\n";
}

// Processamento dos argumentos da linha de comando
$command = isset($argv[1]) ? $argv[1] : 'run';

switch ($command) {
    case 'status':
        showStatus($pdo);
        break;
        
    case 'rollback':
        rollbackLastMigration($pdo);
        break;
        
    case 'run':
    default:
        runMigrations($pdo);
        break;
}
