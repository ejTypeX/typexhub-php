<?php 
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

include "../../include/header.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">


<body class="project-body">
    <div class="project-container">

        <!-- Avisos -->
        <div class="project-section">
            <div class="project-section-header">
                <h2 class="project-ms">Avisos</h2>
            </div>
            <div class="project-card">
                <p>Não há nenhuma tarefa em atraso!</p>
            </div>
        </div>

        <!-- TypeX Hub -->
        <div class="project-section">
            <div class="project-section-header project-section-header-center">
                <h1 class="project-title-center">TypeX Hub</h1>
            </div>

            <!-- Resumo -->
            <div class="project-card">
                <h3 class="project-title-card">Resumo:</h3>
                <p>Aplicativo para centralizar as necessidades de gestão de uma empresa júnior em um único aplicativo.
                </p>
                <p>...</p>
                <p>...</p>
            </div>
        </div>


        <!-- Tecnologia -->
        <div class="project-section">
            <div class="project-card">
                <h3 class="project-title-card">Tecnologia</h3>
                <p>PHP, MySQL, Docker e JavaScript</p>
            </div>
        </div>

        <!-- Links Úteis -->
        <div class="project-section">
            <div class="project-card">
                <h3 class="project-title-card">Links Úteis</h3>
                <p>Github: link</p><br>
                <p>Figma: link</p><br>
                <p>Email: contato@typexhub.com</p>
            </div>
        </div>

        <!-- TypeX Hub - Tabela de Tasks -->
        <div class="project-section">
            <div class="project-section-header">
                <h1>TypeX Hub</h1>
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

        <!-- Versões -->
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