<?php

include "../../include/header.php";
include "../../include/conexao.php";

$stmt = $pdo->query("
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
    ORDER BY p.projeto_data_inicio DESC
");
$projetos = $stmt->fetchAll();

$hoje = date('Y-m-d');
$stmtAvisos = $pdo->query("
    SELECT 
        p.projeto_id,
        p.projeto_nome,
        p.projeto_data_inicio,
        p.projeto_data_fim,
        p.projeto_status,
        u.usuario_nome,
        u.usuario_sobrenome,
        u.usuario_cargo,
        DATEDIFF(p.projeto_data_fim, '$hoje') as dias_restantes
    FROM projetos p
    INNER JOIN usuarios u ON p.projeto_responsavel = u.usuario_id
    WHERE 
        (p.projeto_status = 0 AND p.projeto_data_inicio < '$hoje')
        OR (p.projeto_status = 1 AND p.projeto_data_fim < '$hoje')
        OR (p.projeto_status = 1 AND p.projeto_data_fim >= '$hoje' AND p.projeto_data_fim <= DATE_ADD('$hoje', INTERVAL 7 DAY))
    ORDER BY 
        CASE 
            WHEN p.projeto_data_fim < '$hoje' THEN 1
            WHEN p.projeto_status = 0 AND p.projeto_data_inicio < '$hoje' THEN 2
            ELSE 3
        END,
        p.projeto_data_fim ASC
    LIMIT 10
");
$avisos = $stmtAvisos->fetchAll();

$stmtTasks = $pdo->query("
    SELECT 
        t.tasks_id,
        t.tasks_titulo,
        t.tasks_desc,
        t.tasks_status,
        u.usuario_nome,
        u.usuario_sobrenome,
        u.usuario_cargo,
        p.projeto_nome,
        p.projeto_data_fim as projeto_prazo
    FROM tasks t
    INNER JOIN usuarios u ON t.tasks_atribuido_para = u.usuario_id
    INNER JOIN projetos p ON t.tasks_projeto = p.projeto_id
    ORDER BY t.tasks_id DESC
    LIMIT 20
");
$tasks = $stmtTasks->fetchAll();

function getStatusClass($status) {
    switch($status) {
        case 1: return 'pjt_amarelo';
        case 0: return 'pjt_vermelho';
        default: return 'pjt_amarelo';
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

function getTaskStatusClass($status) {
    $statusLower = strtolower($status);
    if (strpos($statusLower, 'concluído') !== false || strpos($statusLower, 'concluido') !== false || strpos($statusLower, 'finalizado') !== false) {
        return 'pjt_verde';
    } elseif (strpos($statusLower, 'andamento') !== false || strpos($statusLower, 'em progresso') !== false) {
        return 'pjt_amarelo';
    } else {
        return 'pjt_vermelho';
    }
}

function getTaskStatusText($status) {
    return ucfirst($status);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<body class="pjt_body">
    <div class="pjt_container">

        <div id="tasks_projetos" class="pjt_section">
            <div class="pjt_section_header">
                <h2>Avisos <span class="pjt_badge"><?php echo count($avisos); ?></span></h2>
            </div>

            <div class="pjt_cards_row">
                <?php if (empty($avisos)): ?>
                    <div class="pjt_card_linha">
                        <div class="pjt_card_content">
                            <p class="pjt_description-card-projetos">Não há avisos no momento. Todos os projetos estão em dia!</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($avisos as $aviso): 
                        $hoje = new DateTime();
                        $dataFim = new DateTime($aviso['projeto_data_fim']);
                        $dataInicio = new DateTime($aviso['projeto_data_inicio']);
                        $diasRestantes = (int)$aviso['dias_restantes'];
                        
                        if ($aviso['projeto_status'] == 0 && $dataInicio < $hoje) {
                            $tipoAviso = 'Não Iniciado';
                            $diasAtraso = $hoje->diff($dataInicio)->days;
                            $mensagem = "O projeto '{$aviso['projeto_nome']}' deveria ter sido iniciado há {$diasAtraso} dia(s) e ainda não foi.";
                        } elseif ($dataFim < $hoje && $aviso['projeto_status'] == 1) {
                            $tipoAviso = 'Atrasado';
                            $diasAtraso = $hoje->diff($dataFim)->days;
                            $mensagem = "O projeto '{$aviso['projeto_nome']}' está atrasado há {$diasAtraso} dia(s). Prazo venceu em " . formatarData($aviso['projeto_data_fim']) . ".";
                        } elseif ($diasRestantes <= 7 && $diasRestantes > 0) {
                            $tipoAviso = 'Prazo Próximo';
                            $mensagem = "O projeto '{$aviso['projeto_nome']}' está próximo do prazo. Restam {$diasRestantes} dia(s). Prazo: " . formatarData($aviso['projeto_data_fim']) . ".";
                        } elseif ($diasRestantes == 0) {
                            $tipoAviso = 'Prazo Hoje';
                            $mensagem = "O projeto '{$aviso['projeto_nome']}' vence hoje!";
                        } else {
                            $tipoAviso = 'Atenção';
                            $mensagem = "O projeto '{$aviso['projeto_nome']}' requer atenção.";
                        }
                    ?>
                        <div class="pjt_card_linha">
                            <span class="pjt_card_badge pjt_vermelho"><?php echo ($tipoAviso); ?></span>
                            <div class="pjt_card_content">
                                <h3 class="pjt_title-card-projetos"><?php echo ($aviso['projeto_nome']); ?></h3>
                                <p class="pjt_description-card-projetos"><?php echo ($mensagem); ?></p>
                            </div>
                            <div class="pjt_card_footer">
                                <span class="pjt_role-card-projetos"><?php echo ($aviso['usuario_nome'] . ' ' . $aviso['usuario_sobrenome'] . ' - ' . $aviso['usuario_cargo']); ?></span>
                                <span class="pjt_date-avisos"><?php echo formatarData($aviso['projeto_data_fim']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (count($avisos) > 0): ?>
                <button class="pjt_section_button_vermais">Ver mais</button>
            <?php endif; ?>
        </div>

        <div id="tasks_projetos" class="pjt_section">
            <div class="pjt_section_header">
                <h2>Projetos</h2>
                <button class="pjt_section_button_adicionar"><i class="bi bi-plus-lg"></i></button>
            </div>

            <div class="pjt_cards_row">
                <?php if (empty($projetos)): ?>
                    <div class="pjt_card_projetos">
                        <div class="pjt_card_content">
                            <p class="pjt_description-card-projetos">Nenhum projeto encontrado.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($projetos as $projeto): ?>
                        <div class="pjt_card_projetos">
                            <span class="pjt_card_badge <?php echo getStatusClass($projeto['projeto_status']); ?>">
                                <?php echo getStatusText($projeto['projeto_status']); ?>
                            </span>
                            <div class="pjt_card_content">
                                <h3 class="pjt_title-card-projetos"><?php echo ($projeto['projeto_nome']); ?></h3>
                                <p class="pjt_description-card-projetos">
                                    <?php echo ($projeto['projeto_desc']); ?>
                                </p>
                            </div>
                            <div class="pjt_card_footer">
                                <div class="pjt_perfil">
                                    <img class="pjt_role-card-projetos" src="https://randomuser.me/api/portraits/men/<?php echo rand(1, 99); ?>.jpg" alt="foto">
                                    <span class="pjt_role-card-projetos pjt_description-card-projetos">
                                        <?php echo ($projeto['usuario_nome'] . ' ' . $projeto['usuario_sobrenome']); ?>
                                        <br><small class="pjt_text-gray"><?php echo ($projeto['usuario_cargo']); ?></small>
                                    </span>
                                </div>
                                <a href="projeto_detalhes.php?id=<?php echo $projeto['projeto_id']; ?>" class="pjt_card_button_editar">
                                    <i class="bi bi-pencil-square"></i> Ver Projeto...
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button class="pjt_section_button_vermais">Histórico</button>
        </div>

        <div id="tasks_projetos" class="pjt_section">
            <div class="pjt_section_header">
                <h2>Tasks</h2>
                <button class="pjt_section_button_adicionar"><i class="bi bi-plus-lg"></i></button>
            </div>

            <div class="pjt_tasks_header_buttons">
                <button class="pjt_filter_button active">Kanban</button>
                <button class="pjt_filter_button">Roadmap</button>
            </div>

            <table class="pjt_tasks_table">
                <thead>
                    <tr class="pjt_tr">
                        <th class="text-light">Título</th>
                        <th class="text-light">Descrição</th>
                        <th class="text-light">Diretor Responsável</th>
                        <th class="text-light">Status</th>
                        <th class="text-light">Prazo</th>
                        <th class="text-light">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tasks)): ?>
                        <tr class="pjt_tr">
                            <td class="pjt_td" colspan="6" style="text-align: center; padding: 2rem;">
                                <p>Nenhuma task encontrada.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tasks as $task): ?>
                            <tr class="pjt_tr">
                                <td class="pjt_td"><?php echo ($task['tasks_titulo']); ?></td>
                                <td class="pjt_td"><?php echo ($task['tasks_desc']); ?></td>
                                <td class="pjt_td">
                                    <div class="pjt_perfil">
                                        <img src="https://randomuser.me/api/portraits/men/<?php echo rand(1, 99); ?>.jpg" alt="foto">
                                        <span>
                                            <?php echo ($task['usuario_nome'] . ' ' . $task['usuario_sobrenome']); ?>
                                            <small class="pjt_text-gray"><?php echo ($task['usuario_cargo']); ?></small>
                                        </span>
                                    </div>
                                </td>
                                <td class="pjt_td">
                                    <span class="pjt_status <?php echo getTaskStatusClass($task['tasks_status']); ?>">
                                        <?php echo getTaskStatusText($task['tasks_status']); ?>
                                    </span>
                                </td>
                                <td class="pjt_td">
                                    <?php 
                                    if (!empty($task['projeto_prazo'])) {
                                        echo formatarData($task['projeto_prazo']);
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td class="pjt_td">
                                    <button class="pjt_btn-acao">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <button class="pjt_section_button_vermais">Ver mais</button>
        </div>
    </div>
</body>


</html>