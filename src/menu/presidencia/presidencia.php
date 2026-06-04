<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

include "../../include/conexao.php";

// Busca receita total
$stmtReceita = $pdo->query("SELECT COALESCE(SUM(valor), 0) as total FROM transacoes WHERE tipo = 'entrada'");
$receita = $stmtReceita->fetch()['total'];

// Busca despesas totais
$stmtDespesas = $pdo->query("SELECT COALESCE(SUM(valor), 0) as total FROM transacoes WHERE tipo = 'saida'");
$despesas = $stmtDespesas->fetch()['total'];

// Calcula lucro
$lucro = $receita - $despesas;

include "../../include/header.php";
?>

<script>
    function loadMoreProjects() {
        const container = document.querySelector('.container');
        const btnVerMais = document.getElementById('btnVerMais');
        const allCards = document.querySelectorAll('.card');
        const isExpanded = container.classList.contains('expanded');
        if (!isExpanded) {
            allCards.forEach(card => card.classList.add('show-all'));
            container.classList.add('expanded');
            btnVerMais.textContent = 'Ver menos';
        } else {
            allCards.forEach(card => card.classList.remove('show-all'));
            container.classList.remove('expanded');
            btnVerMais.textContent = 'Ver mais';
            document.getElementById('avisos_presidencia').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
</script>

<!-- DASHBOARD EXECUTIVO -->
<section id="section_presidencia">
    <div id="dashboard_executivo" class="section">
        <div class="header_section">
            <h1>Dashboard Executivo</h1>
        </div>
        <div style="display:flex; gap:1.5rem; flex-wrap:wrap; margin-top:1rem;">
            <div style="background:#23262F; border-radius:12px; padding:1.5rem 2rem; flex:1; min-width:180px;">
                <p style="color:#aaa; margin-bottom:0.5rem;">Receita Total</p>
                <h2 style="color:#4ade80; font-size:1.8rem;">R$ <?= number_format($receita, 2, ',', '.') ?></h2>
            </div>
            <div style="background:#23262F; border-radius:12px; padding:1.5rem 2rem; flex:1; min-width:180px;">
                <p style="color:#aaa; margin-bottom:0.5rem;">Despesas</p>
                <h2 style="color:#f87171; font-size:1.8rem;">R$ <?= number_format($despesas, 2, ',', '.') ?></h2>
            </div>
            <div style="background:#23262F; border-radius:12px; padding:1.5rem 2rem; flex:1; min-width:180px;">
                <p style="color:#aaa; margin-bottom:0.5rem;">Lucro</p>
                <h2 style="color:<?= $lucro >= 0 ? '#4ade80' : '#f87171' ?>; font-size:1.8rem;">R$ <?= number_format($lucro, 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</section>

<!-- AVISOS -->
<section id="section_presidencia">
    <div id="avisos_presidencia" class="section">
        <h1>Avisos</h1>
        <div class="container">
            <?php if (!empty($projetos)): ?>
                <?php foreach ($projetos as $projeto): ?>
                    <div class="card" data-id="<?= htmlspecialchars($projeto['id']) ?>">
                        <button class="status-button <?= getStatusClass($projeto['status']) ?>">
                            <span class="status-text"><?= htmlspecialchars($projeto['status']) ?></span>
                        </button>
                        <h2 class="project-title"><?= htmlspecialchars($projeto['nome_projeto']) ?></h2>
                        <p class="project-description"><?= htmlspecialchars($projeto['descricao']) ?></p>
                        <div class="divider"></div>
                        <div class="avatar-section">
                            <div class="avatar"></div>
                            <div class="user-info">
                                <span class="user-name"><?= htmlspecialchars($projeto['diretor_responsavel']) ?></span>
                                <span class="user-role"><?= htmlspecialchars($projeto['empresa'] ?? 'Empresa') ?></span>
                            </div>
                        </div>
                        <button class="action-button open-modal"
                            data-id="<?= htmlspecialchars($projeto['id']) ?>"
                            data-modal="modal_gerenciar_projeto"
                            title="Gerenciar projeto">
                            <div class="action-icon">
                                <i class="fa-solid fa-gear" style="color: white;"></i>
                            </div>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card" style="text-align: center; justify-content: center;">
                    <h2 class="project-title">Nenhum projeto encontrado</h2>
                    <p class="project-description">Não há projetos pendentes no momento.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if (true): ?>
            <button class="button_vermais" onclick="loadMoreProjects()">Ver mais</button>
        <?php endif; ?>
    </div>
</section>

<!-- METAS -->
<section id="section_presidencia">
    <div id="metas_presidencia" class="section">
        <div class="header_section">
            <h1>Metas</h1>
            <button class="button_adicionar">+</button>
        </div>
        <div class="container_metas">
            <div class="card_metas">
                <div class="metas_status">
                    <button class="status-button andamento status-text">
                        <span class="status-text">Em andamento</span>
                    </button>
                    <div class="meta_data">01/06/2025</div>
                </div>
                <div class="meta_titulo">Faturar 10000</div>
                <div class="meta_descricao">asdfdasdasdasdasdsadasdsadasdasdaasda</div>
                <div class="meta_avatar">
                    <div class="meta_user">
                        <div class="avatar"></div>
                        <div class="user-info">
                            <span class="user-name">Vitor Ferreira Viana</span>
                            <span class="user-role">Diretor-Presidente</span>
                        </div>
                    </div>
                    <button class="button_editar">
                        <svg class="edit_icon" width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.563 1.43191C22.4977 0.420157 20.7757 0.420157 19.7104 1.43191L18.2462 2.81788L23.0085 7.34075L24.4726 5.95017C25.5379 4.93841 25.5379 3.30297 24.4726 2.29121L23.563 1.43191ZM9.00876 11.5957C8.71203 11.8775 8.48341 12.224 8.35207 12.6074L6.91221 16.7099C6.77114 17.1072 6.88302 17.5461 7.19434 17.8464C7.50567 18.1467 7.96778 18.2483 8.39098 18.1143L12.7106 16.7469C13.1094 16.6221 13.4743 16.405 13.7759 16.1232L21.914 8.38947L17.1469 3.86198L9.00876 11.5957ZM5.29237 3.38613C2.71424 3.38613 0.622559 5.37268 0.622559 7.82122V19.6481C0.622559 22.0967 2.71424 24.0832 5.29237 24.0832H17.7452C20.3233 24.0832 22.415 22.0967 22.415 19.6481V15.213C22.415 14.3953 21.7194 13.7347 20.8584 13.7347C19.9974 13.7347 19.3018 14.3953 19.3018 15.213V19.6481C19.3018 20.4659 18.6062 21.1265 17.7452 21.1265H5.29237C4.43137 21.1265 3.73577 20.4659 3.73577 19.6481V7.82122C3.73577 7.0035 4.43137 6.34286 5.29237 6.34286H9.96218C10.8232 6.34286 11.5188 5.68221 11.5188 4.86449C11.5188 4.04677 10.8232 3.38613 9.96218 3.38613H5.29237Z" fill="white"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <button class="button_vermais" onclick="loadMoreProjects()">Ver mais</button>
    </div>
</section>

<!-- TASKS -->
<section id="section_presidencia">
    <div id="table_presidencia" class="section">
        <div class="header_section">
            <h1>Tasks (Diretoria)</h1>
            <button class="button_adicionar open-modal" data-modal="modal_criar_task_presidencia">+</button>
        </div>
        <div id="table_presidencia_mobile" class="container">
            <table class="table_presidencia">
                <thead>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Diretor Responsável</th>
                    <th>Status</th>
                    <th>Prazo</th>
                    <th></th>
                </thead>
                <tbody id="tasksTableBody">
                    <tr>
                        <td>Faturar R$ 10.000,00</td>
                        <td class="td_descricao">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="td-place-center">
                            <div class="avatar_section"><div class="avatar"></div>
                                <div class="user-info"><span class="user-name">Vitor Ferreira Viana</span><span class="user-role">Diretor-Presidente</span></div>
                            </div>
                        </td>
                        <td class="td-place-center"><span class="status-button concluido status-text">Concluído</span></td>
                        <td>01/06/2025</td>
                        <td><button class="button_editar open-modal" data-modal="modal_opcoes_task_presidencia"><svg class="edit_icon" width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.563 1.43191C22.4977 0.420157 20.7757 0.420157 19.7104 1.43191L18.2462 2.81788L23.0085 7.34075L24.4726 5.95017C25.5379 4.93841 25.5379 3.30297 24.4726 2.29121L23.563 1.43191ZM9.00876 11.5957C8.71203 11.8775 8.48341 12.224 8.35207 12.6074L6.91221 16.7099C6.77114 17.1072 6.88302 17.5461 7.19434 17.8464C7.50567 18.1467 7.96778 18.2483 8.39098 18.1143L12.7106 16.7469C13.1094 16.6221 13.4743 16.405 13.7759 16.1232L21.914 8.38947L17.1469 3.86198L9.00876 11.5957ZM5.29237 3.38613C2.71424 3.38613 0.622559 5.37268 0.622559 7.82122V19.6481C0.622559 22.0967 2.71424 24.0832 5.29237 24.0832H17.7452C20.3233 24.0832 22.415 22.0967 22.415 19.6481V15.213C22.415 14.3953 21.7194 13.7347 20.8584 13.7347C19.9974 13.7347 19.3018 14.3953 19.3018 15.213V19.6481C19.3018 20.4659 18.6062 21.1265 17.7452 21.1265H5.29237C4.43137 21.1265 3.73577 20.4659 3.73577 19.6481V7.82122C3.73577 7.0035 4.43137 6.34286 5.29237 6.34286H9.96218C10.8232 6.34286 11.5188 5.68221 11.5188 4.86449C11.5188 4.04677 10.8232 3.38613 9.96218 3.38613H5.29237Z" fill="white"/></svg></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="button_vermais" onclick="loadMoreProjects()">Ver mais</button>
    </div>
</section>

<!-- REUNIÕES -->
<section id="section_presidencia">
    <div id="reunioes_presidencia" class="section">
        <div class="header_section">
            <h1>Reuniões</h1>
            <button class="button_adicionar">+</button>
        </div>
        <div id="table_presidencia_mobile" class="container">
            <table class="table_presidencia table_presidencia_reuniao">
                <thead>
                    <th>Motivo</th><th>Data</th><th>Local</th><th>Horário</th><th>Descrição</th><th>Status</th><th></th>
                </thead>
                <tbody>
                    <tr>
                        <td class="td_motivo">Alinhamento das Diretorias</td>
                        <td>20/07/2025</td><td>Google Meeting</td><td class="td_horario">06:00 - 23:00</td>
                        <td class="td_descricao">Evento de empresas júniores do sudoeste do paraná</td>
                        <td class="td-place-center"><span class="status-button concluido status-text">Concluído</span></td>
                        <td><button class="button_editar"><svg class="edit_icon" width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.563 1.43191C22.4977 0.420157 20.7757 0.420157 19.7104 1.43191L18.2462 2.81788L23.0085 7.34075L24.4726 5.95017C25.5379 4.93841 25.5379 3.30297 24.4726 2.29121L23.563 1.43191ZM9.00876 11.5957C8.71203 11.8775 8.48341 12.224 8.35207 12.6074L6.91221 16.7099C6.77114 17.1072 6.88302 17.5461 7.19434 17.8464C7.50567 18.1467 7.96778 18.2483 8.39098 18.1143L12.7106 16.7469C13.1094 16.6221 13.4743 16.405 13.7759 16.1232L21.914 8.38947L17.1469 3.86198L9.00876 11.5957ZM5.29237 3.38613C2.71424 3.38613 0.622559 5.37268 0.622559 7.82122V19.6481C0.622559 22.0967 2.71424 24.0832 5.29237 24.0832H17.7452C20.3233 24.0832 22.415 22.0967 22.415 19.6481V15.213C22.415 14.3953 21.7194 13.7347 20.8584 13.7347C19.9974 13.7347 19.3018 14.3953 19.3018 15.213V19.6481C19.3018 20.4659 18.6062 21.1265 17.7452 21.1265H5.29237C4.43137 21.1265 3.73577 20.4659 3.73577 19.6481V7.82122C3.73577 7.0035 4.43137 6.34286 5.29237 6.34286H9.96218C10.8232 6.34286 11.5188 5.68221 11.5188 4.86449C11.5188 4.04677 10.8232 3.38613 9.96218 3.38613H5.29237Z" fill="white"/></svg></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="button_vermais" id="btnVerMaisTasks" onclick="loadMoreTasks()">Ver mais</button>
    </div>
</section>

<?php
include "modals/criar-task.php";
include "modals/opcoes-task.php";
?>
<script src="../../assets/js/modais.js"></script>