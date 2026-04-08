<?php

declare(strict_types=1);

/**
 * Seeder idempotente de dados de desenvolvimento.
 * Pré-requisito: migrations aplicadas (inclui 005_departamento_papel_usuario).
 *
 * Credenciais: variáveis de ambiente ou arquivo .env na raiz do projeto (nunca no código).
 */

function loadEnvFromFile(string $path): void
{
    if (!is_readable($path)) {
        return;
    }
    $vars = @parse_ini_file($path, false, INI_SCANNER_RAW);
    if ($vars === false || !is_array($vars)) {
        return;
    }
    foreach ($vars as $key => $value) {
        if (getenv((string) $key) !== false) {
            continue;
        }
        $value = trim((string) $value, "'\"");
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

$rootDir = dirname(__DIR__);
loadEnvFromFile($rootDir . DIRECTORY_SEPARATOR . '.env');
loadEnvFromFile(__DIR__ . DIRECTORY_SEPARATOR . '.env');

$dbHost = getenv('DB_HOST') ?: '';
$dbName = getenv('DB_NAME') ?: '';
$dbUser = getenv('DB_USER') ?: '';
$dbPass = getenv('DB_PASSWORD') ?: '';

if ($dbHost === '' || $dbName === '' || $dbUser === '') {
    fwrite(STDERR, "Erro: defina DB_HOST, DB_NAME, DB_USER e DB_PASSWORD no ambiente ou no .env.\n");
    exit(1);
}

$seedRootPassword = getenv('SEED_ROOT_PASSWORD') ?: '';
$seedDevPassword = getenv('SEED_DEV_PASSWORD') ?: '';

if ($seedRootPassword === '' || $seedDevPassword === '') {
    fwrite(STDERR, "Erro: defina SEED_ROOT_PASSWORD e SEED_DEV_PASSWORD (ambiente ou .env).\n");
    exit(1);
}

$seedRootEmail = getenv('SEED_ROOT_EMAIL') ?: 'presidente@dev.com';
$seedRootRa = getenv('SEED_ROOT_RA') ?: 'presidente.dev';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};port=3306;dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
} catch (PDOException $e) {
    fwrite(STDERR, 'Erro de conexão: ' . $e->getMessage() . "\n");
    exit(1);
}

echo "=== TypeX Hub — seed de desenvolvimento ===\n";
echo "Conexão com o banco estabelecida.\n\n";

$hashRoot = password_hash($seedRootPassword, PASSWORD_DEFAULT);
$hashDev = password_hash($seedDevPassword, PASSWORD_DEFAULT);

/** @return array{0: bool, 1: int} inserted, affected */
function insertIgnore(PDO $pdo, string $sql, array $params = []): array
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $n = $stmt->rowCount();

    return [$n > 0, $n];
}

// --- Departamentos ---
$departamentos = ['Presidência', 'Projetos', 'Finanças', 'RH', 'Eventos'];
echo "--- Departamentos ---\n";
foreach ($departamentos as $nome) {
    [$inserted] = insertIgnore(
        $pdo,
        'INSERT IGNORE INTO departamento (dep_nome) VALUES (?)',
        [$nome]
    );
    echo $inserted ? "[INSERIDO] {$nome}\n" : "[IGNORADO] {$nome} (já existia)\n";
}

// --- Papéis ---
$papeis = ['presidente', 'vice-presidente', 'diretor', 'assessor', 'membro comum'];
echo "\n--- Papéis ---\n";
foreach ($papeis as $nome) {
    [$inserted] = insertIgnore(
        $pdo,
        'INSERT IGNORE INTO papel (pap_nome) VALUES (?)',
        [$nome]
    );
    echo $inserted ? "[INSERIDO] {$nome}\n" : "[IGNORADO] {$nome} (já existia)\n";
}

function papelId(PDO $pdo, string $nome): int
{
    $stmt = $pdo->prepare('SELECT pap_id FROM papel WHERE pap_nome = ?');
    $stmt->execute([$nome]);
    $id = $stmt->fetchColumn();
    if ($id === false) {
        throw new RuntimeException("Papel não encontrado: {$nome}");
    }

    return (int) $id;
}

function departamentoId(PDO $pdo, string $nome): int
{
    $stmt = $pdo->prepare('SELECT dep_id FROM departamento WHERE dep_nome = ?');
    $stmt->execute([$nome]);
    $id = $stmt->fetchColumn();
    if ($id === false) {
        throw new RuntimeException("Departamento não encontrado: {$nome}");
    }

    return (int) $id;
}

/**
 * Usuários de desenvolvimento: email, nome exibido, RA, papel (pap_nome), departamento (dep_nome ou null).
 * Senha: root usa SEED_ROOT_PASSWORD; demais usam SEED_DEV_PASSWORD.
 *
 * @var list<array{email: string, nome: string, ra: string, papel: string, dep: string|null, root: bool}>
 */
$usuarios = [
    [
        'email' => $seedRootEmail,
        'nome' => 'Presidente (dev)',
        'ra' => $seedRootRa,
        'papel' => 'presidente',
        'dep' => 'Presidência',
        'root' => true,
    ],
    [
        'email' => 'vice@dev.com',
        'nome' => 'Vice-presidente (dev)',
        'ra' => 'vice.dev',
        'papel' => 'vice-presidente',
        'dep' => 'Presidência',
        'root' => false,
    ],
    [
        'email' => 'diretor.rh@dev.com',
        'nome' => 'Diretor RH (dev)',
        'ra' => 'dir.rh.dev',
        'papel' => 'diretor',
        'dep' => 'RH',
        'root' => false,
    ],
    [
        'email' => 'diretor.projetos@dev.com',
        'nome' => 'Diretor Projetos (dev)',
        'ra' => 'dir.projetos.dev',
        'papel' => 'diretor',
        'dep' => 'Projetos',
        'root' => false,
    ],
    [
        'email' => 'diretor.financas@dev.com',
        'nome' => 'Diretor Finanças (dev)',
        'ra' => 'dir.financas.dev',
        'papel' => 'diretor',
        'dep' => 'Finanças',
        'root' => false,
    ],
    [
        'email' => 'assessor.rh@dev.com',
        'nome' => 'Assessor RH (dev)',
        'ra' => 'ass.rh.dev',
        'papel' => 'assessor',
        'dep' => 'RH',
        'root' => false,
    ],
    [
        'email' => 'assessor.projetos@dev.com',
        'nome' => 'Assessor Projetos (dev)',
        'ra' => 'ass.projetos.dev',
        'papel' => 'assessor',
        'dep' => 'Projetos',
        'root' => false,
    ],
    [
        'email' => 'membro.rh@dev.com',
        'nome' => 'Membro RH (dev)',
        'ra' => 'membro.rh.dev',
        'papel' => 'membro comum',
        'dep' => 'RH',
        'root' => false,
    ],
    [
        'email' => 'membro@dev.com',
        'nome' => 'Membro Projetos (dev)',
        'ra' => 'membro.projetos.dev',
        'papel' => 'membro comum',
        'dep' => 'Projetos',
        'root' => false,
    ],
    [
        'email' => 'membro.eventos@dev.com',
        'nome' => 'Membro Eventos (dev)',
        'ra' => 'membro.eventos.dev',
        'papel' => 'membro comum',
        'dep' => 'Eventos',
        'root' => false,
    ],
];

echo "\n--- Usuários ---\n";

$sqlInsert = <<<SQL
INSERT IGNORE INTO usuario (
    usr_nome,
    usr_email,
    usr_senha,
    usr_ra,
    usr_ativo,
    dep_id,
    pap_id,
    usr_senha_temporaria
) VALUES (?, ?, ?, ?, 1, ?, ?, 0)
SQL;

foreach ($usuarios as $u) {
    $papId = papelId($pdo, $u['papel']);
    $depId = $u['dep'] !== null ? departamentoId($pdo, $u['dep']) : null;
    $hash = $u['root'] ? $hashRoot : $hashDev;

    $stmt = $pdo->prepare($sqlInsert);
    $stmt->execute([
        $u['nome'],
        $u['email'],
        $hash,
        $u['ra'],
        $depId,
        $papId,
    ]);

    if ($stmt->rowCount() > 0) {
        echo "[INSERIDO] {$u['email']} ({$u['papel']}";
        echo $u['dep'] !== null ? ' + ' . $u['dep'] : '';
        echo ")\n";
    } else {
        echo "[IGNORADO] {$u['email']} (já existia — UNIQUE em e-mail)\n";
    }
}

/**
 * Schema legado (migration 001): tasks → usuarios, diretorias, projetos.
 * Idempotência por nomes prefixados [DEV].
 */
echo "\n--- Tarefas simuladas (schema legado: diretorias, usuarios, projetos, tasks) ---\n";

$legacyHash = password_hash($seedDevPassword, PASSWORD_DEFAULT);

$diretoriasLegado = [
    ['[DEV] Presidência', 'Diretoria legada para seed de tasks.', '#212832', 1],
    ['[DEV] Projetos', 'Diretoria legada — projetos.', '#393d46', 1],
    ['[DEV] Marketing', 'Diretoria legada — marketing.', '#006d71', 1],
];

foreach ($diretoriasLegado as $row) {
    [$inserted] = insertIgnore(
        $pdo,
        'INSERT IGNORE INTO diretorias (diretoria_nome, diretoria_desc, diretoria_cor, diretoria_status) VALUES (?, ?, ?, ?)',
        $row
    );
    echo $inserted ? "[INSERIDO] diretoria: {$row[0]}\n" : "[IGNORADO] diretoria: {$row[0]} (já existia)\n";
}

$getDirId = static function (PDO $pdo, string $nome): int {
    $stmt = $pdo->prepare('SELECT diretoria_id FROM diretorias WHERE diretoria_nome = ?');
    $stmt->execute([$nome]);
    $id = $stmt->fetchColumn();
    if ($id === false) {
        throw new RuntimeException("Diretoria legada não encontrada: {$nome}");
    }

    return (int) $id;
};

$dirPres = $getDirId($pdo, '[DEV] Presidência');
$dirProj = $getDirId($pdo, '[DEV] Projetos');

$legacyUsers = [
    [
        'email' => 'legacy.creator@dev.local',
        'nome' => 'Seed',
        'sobrenome' => 'Criador',
        'cargo' => 'Diretor',
        'diretoria_id' => $dirPres,
    ],
    [
        'email' => 'legacy.assignee@dev.local',
        'nome' => 'Seed',
        'sobrenome' => 'Executor',
        'cargo' => 'Membro',
        'diretoria_id' => $dirProj,
    ],
];

$legacyIds = [];
foreach ($legacyUsers as $lu) {
    $stmt = $pdo->prepare('SELECT usuario_id FROM usuarios WHERE usuario_email = ?');
    $stmt->execute([$lu['email']]);
    $existing = $stmt->fetchColumn();
    if ($existing !== false) {
        $legacyIds[$lu['email']] = (int) $existing;
        echo "[IGNORADO] usuarios (legado): {$lu['email']} (já existia)\n";
        continue;
    }
    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (usuario_nome, usuario_sobrenome, usuario_email, usuario_senha, usuario_rg, usuario_cpf, usuario_telefone, usuario_nascimento, usuario_cargo, diretoria_id)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $lu['nome'],
        $lu['sobrenome'],
        $lu['email'],
        $legacyHash,
        '00.000.000-0',
        '000.000.000-00',
        '(00) 00000-0000',
        '2000-01-01',
        $lu['cargo'],
        $lu['diretoria_id'],
    ]);
    $legacyIds[$lu['email']] = (int) $pdo->lastInsertId();
    echo "[INSERIDO] usuarios (legado): {$lu['email']}\n";
}

$idCreator = $legacyIds['legacy.creator@dev.local'];
$idAssignee = $legacyIds['legacy.assignee@dev.local'];

$projetoNome = '[DEV] Projeto TypeX Hub';
$stmt = $pdo->prepare('SELECT projeto_id FROM projetos WHERE projeto_nome = ?');
$stmt->execute([$projetoNome]);
$projRow = $stmt->fetchColumn();
if ($projRow !== false) {
    $projetoId = (int) $projRow;
    echo "[IGNORADO] projeto: {$projetoNome} (já existia)\n";
} else {
    $stmt = $pdo->prepare(
        'INSERT INTO projetos (projeto_nome, projeto_desc, projeto_diretoria, projeto_responsavel, projeto_data_inicio, projeto_data_fim, projeto_status)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $projetoNome,
        'Projeto simulado para desenvolvimento local.',
        $dirProj,
        $idAssignee,
        date('Y-m-d', strtotime('-30 days')),
        date('Y-m-d', strtotime('+60 days')),
        1,
    ]);
    $projetoId = (int) $pdo->lastInsertId();
    echo "[INSERIDO] projeto: {$projetoNome}\n";
}

$tarefasSimuladas = [
    [
        'titulo' => '[DEV] Revisar documentação da API',
        'desc' => 'Conferir endpoints e exemplos de payload.',
        'diretoria' => $dirPres,
        'status' => 'Em andamento',
    ],
    [
        'titulo' => '[DEV] Alinhar escopo do sprint com a diretoria',
        'desc' => 'Reunião rápida para priorizar backlog.',
        'diretoria' => $dirPres,
        'status' => 'Pendente',
    ],
    [
        'titulo' => '[DEV] Atualizar wireframes do dashboard',
        'desc' => 'Ajustar sidebar e estados vazios.',
        'diretoria' => $dirProj,
        'status' => 'Em andamento',
    ],
    [
        'titulo' => '[DEV] Validar migrações no CI',
        'desc' => 'Garantir migrate.php idempotente em pipeline.',
        'diretoria' => $dirProj,
        'status' => 'Concluída',
    ],
    [
        'titulo' => '[DEV] Preparar roteiro de testes manuais',
        'desc' => 'Login, registro e fluxos do menu.',
        'diretoria' => $dirProj,
        'status' => 'Pendente',
    ],
    [
        'titulo' => '[DEV] Revisar copy das telas de auth',
        'desc' => 'Mensagens de erro e sucesso.',
        'diretoria' => $dirPres,
        'status' => 'Backlog',
    ],
];

foreach ($tarefasSimuladas as $t) {
    $chk = $pdo->prepare('SELECT tasks_id FROM tasks WHERE tasks_titulo = ?');
    $chk->execute([$t['titulo']]);
    if ($chk->fetchColumn() !== false) {
        echo "[IGNORADO] task: {$t['titulo']} (já existia)\n";
        continue;
    }
    $ins = $pdo->prepare(
        'INSERT INTO tasks (tasks_titulo, tasks_desc, tasks_criado_por, tasks_atribuido_para, tasks_diretoria, tasks_projeto, tasks_status)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $ins->execute([
        $t['titulo'],
        $t['desc'],
        $idCreator,
        $idAssignee,
        $t['diretoria'],
        $projetoId,
        $t['status'],
    ]);
    echo "[INSERIDO] task: {$t['titulo']}\n";
}

echo "\nConcluído. Execute novamente sem duplicar registros (idempotente).\n";
echo "Login: use o RA e a senha definidos em SEED_ROOT_PASSWORD (presidente) ou SEED_DEV_PASSWORD (demais).\n";
