<?php 

include "../../include/header.php";
include "../../include/conexao.php";

$projeto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($projeto_id > 0) {
    $stmt = $pdo->prepare("
        SELECT 
            p.projeto_id,
            p.projeto_nome,
            p.projeto_desc,
            p.projeto_data_inicio,
            p.projeto_data_fim,
            p.projeto_status,
            u.usuario_nome,
            u.usuario_sobrenome,
            u.usuario_cargo,
            d.diretoria_nome
        FROM projetos p
        INNER JOIN usuarios u ON p.projeto_responsavel = u.usuario_id
        INNER JOIN diretorias d ON p.projeto_diretoria = d.diretoria_id
        WHERE p.projeto_id = ?
    ");
    $stmt->execute([$projeto_id]);
    $projeto = $stmt->fetch();
    
    if (!$projeto) {
        header('Location: projetos.php');
        exit;
    }
} else {
    header('Location: projetos.php');
    exit;
}

function getStatusClass($status) {
    switch($status) {
        case 1: return 'project-status-yellow';
        case 0: return 'project-status-red';
        default: return 'project-status-yellow';
    }
}

function getStatusText($status) {
    switch($status) {
        case 1: return 'Em Andamento';
        case 0: return 'Não Iniciado';
        default: return 'Em Andamento';
    }
}

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">


<body class="project-body">
    <div class="project-container">

        <div class="project-section">
            <div class="project-section-header">
                <h2 class="project-ms">Avisos</h2>
            </div>
            <div class="project-card">
                <p>Não há nenhuma tarefa em atraso!</p>
            </div>
        </div>

        <div class="project-section">
            <div class="project-section-header project-section-header-center">
                <h1 class="project-title-center"><?php echo ($projeto['projeto_nome']); ?></h1>
            </div>

            <div class="project-card">
                <h3 class="project-title-card">Resumo:</h3>
                <p><?php echo ($projeto['projeto_desc']); ?></p>
                <p><strong>Diretoria:</strong> <?php echo ($projeto['diretoria_nome']); ?></p>
                <p><strong>Responsável:</strong> <?php echo ($projeto['usuario_nome'] . ' ' . $projeto['usuario_sobrenome']); ?> - <?php echo ($projeto['usuario_cargo']); ?></p>
                <p><strong>Data de Início:</strong> <?php echo formatarData($projeto['projeto_data_inicio']); ?></p>
                <p><strong>Data de Término:</strong> <?php echo formatarData($projeto['projeto_data_fim']); ?></p>
                <p><strong>Status:</strong> <span class="project-status <?php echo getStatusClass($projeto['projeto_status']); ?>"><?php echo getStatusText($projeto['projeto_status']); ?></span></p>
            </div>
        </div>

        <div class="project-section">
            <div class="project-card">
                <h3 class="project-title-card">Tecnologia</h3>
                <p>PHP, MySQL, Docker e JavaScript</p>
            </div>
        </div>

        <div class="project-section">
            <div class="project-card">
                <h3 class="project-title-card">Links Úteis</h3>
                <p>Github: link</p><br>
                <p>Figma: link</p><br>
                <p>Email: contato@typexhub.com</p>
            </div>
        </div>

        <div class="project-section">
            <div class="project-section-header">
                <h1><?php echo ($projeto['projeto_nome']); ?> - Tasks</h1>
            </div>

            <table class="project-table">
                <thead>
                    <tr class="project-tr">
                        <th class="project-th text-light">Informações</th>
                        <th class="project-th text-light">Descrição</th>
                        <th class="project-th text-light">Responsável</th>
                        <th class="project-th text-light">Status</th>
                        <th class="project-th text-light">Prazo</th>
                        <th class="project-th text-light">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="project-tr">
                        <td class="project-td">Modelling Figma</td>
                        <td class="project-td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="project-td">
                            <div class="project-profile">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="foto">
                                <span>Vitor Ferreira Viana<br><small
                                        class="project-text-gray">Diretor-Presidente</small></span>
                            </div>
                        </td>
                        <td class="project-td"><span class="project-status project-status-green">Concluído</span></td>
                        <td class="project-td">01/06/2025</td>
                        <td class="project-td">
                            <button class="project-btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="project-tr">
                        <td class="project-td">Prototipagem</td>
                        <td class="project-td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="project-td">
                            <div class="project-profile">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="foto">
                                <span>Vitor Ferreira Viana<br><small
                                        class="project-text-gray">Diretor-Presidente</small></span>
                            </div>
                        </td>
                        <td class="project-td"><span class="project-status project-status-yellow">Em Andamento</span>
                        </td>
                        <td class="project-td">01/06/2025</td>
                        <td class="project-td">
                            <button class="project-btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="project-tr">
                        <td class="project-td">Versão Beta</td>
                        <td class="project-td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="project-td">
                            <div class="project-profile">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="foto">
                                <span>Vitor Ferreira Viana<br><small
                                        class="project-text-gray">Diretor-Presidente</small></span>
                            </div>
                        </td>
                        <td class="project-td"><span class="project-status project-status-red">Não Iniciado</span></td>
                        <td class="project-td">01/06/2025</td>
                        <td class="project-td">
                            <button class="project-btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="project-tr">
                        <td class="project-td">Requisitos</td>
                        <td class="project-td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="project-td">
                            <div class="project-profile">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="foto">
                                <span>Vitor Ferreira Viana<br><small
                                        class="project-text-gray">Diretor-Presidente</small></span>
                            </div>
                        </td>
                        <td class="project-td"><span class="project-status project-status-green">Concluído</span></td>
                        <td class="project-td">01/06/2025</td>
                        <td class="project-td">
                            <button class="project-btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="project-section">
            <div class="project-section-header">
                <h1>Versões</h1>
            </div>

            <table class="project-table">
                <thead>
                    <tr class="project-tr">
                        <th class="project-th text-light">Versão</th>
                        <th class="project-th text-light">Descrição</th>
                        <th class="project-th text-light">Status</th>
                        <th class="project-th text-light">Lançamento</th>
                        <th class="project-th text-light">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="project-tr">
                        <td class="project-td">Beta 1.0</td>
                        <td class="project-td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="project-td"><span class="project-status project-status-green">Concluído</span></td>
                        <td class="project-td">01/06/2025</td>
                        <td class="project-td">
                            <button class="project-btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="project-tr">
                        <td class="project-td">Beta 2.0</td>
                        <td class="project-td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="project-td"><span class="project-status project-status-yellow">Em Andamento</span>
                        </td>
                        <td class="project-td">01/06/2025</td>
                        <td class="project-td">
                            <button class="project-btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="project-tr">
                        <td class="project-td">Final 1.0</td>
                        <td class="project-td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="project-td"><span class="project-status project-status-red">Não Iniciado</span></td>
                        <td class="project-td">01/06/2025</td>
                        <td class="project-td">
                            <button class="project-btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>