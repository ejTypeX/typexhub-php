<?php

include "include/header.php";
?>

<link rel="stylesheet" href="assets/css/dashboard.css">

<body class="pjt_body">
    <div class="pjt_container">
        <div class="pjt_section">
            <div class="dashboard_user_greeting">
                <h1>Olá, Vitor Ferreira Viana</h1>
            </div>
            <div class="dashboard_user_info">
                <div class="dashboard_info_item">
                    <span class="dashboard_info_label">Cargo:</span>
                    <span class="dashboard_info_value">Diretor-Presidente</span>
                </div>
                <div class="dashboard_info_item">
                    <span class="dashboard_info_label">Diretoria:</span>
                    <span class="dashboard_info_value">Presidência</span>
                </div>
                <div class="dashboard_info_item">
                    <span class="dashboard_info_label">Tempo:</span>
                    <span class="dashboard_info_value">2 anos de EJ</span>
                </div>
                <div class="dashboard_info_item">
                    <span class="dashboard_info_label">Tasks Concluídas:</span>
                    <span class="dashboard_info_value">15</span>
                </div>
                <div class="dashboard_info_item">
                    <span class="dashboard_info_label">Projetos:</span>
                    <span class="dashboard_info_value">8</span>
                </div>
            </div>
        </div>

        <div class="pjt_section">
            <div class="pjt_section_header">
                <h2>Avisos <span class="pjt_badge">2</span></h2>
            </div>

            <div class="pjt_cards_row">
                <div class="pjt_card_linha">
                    <span class="pjt_card_badge pjt_vermelho">Advertência</span>
                    <div class="pjt_card_content">
                        <h3 class="pjt_title-card-projetos">TypeX Hub</h3>
                        <p class="pjt_description-card-projetos">Atraso na entrega da task "Grid: Seção Avisos na tela de Projetos"</p>
                    </div>
                    <div class="pjt_card_footer">
                        <span class="pjt_role-card-projetos">Diretor-Presidente</span>
                        <span class="pjt_date-avisos">01/05/2025</span>
                    </div>
                </div>

                <div class="pjt_card_linha">
                    <span class="pjt_card_badge pjt_vermelho">Advertência</span>
                    <div class="pjt_card_content">
                        <h3 class="pjt_title-card-projetos">TypeX Hub</h3>
                        <p class="pjt_description-card-projetos">Atraso na entrega da task "Grid: Seção Avisos na tela de Projetos"</p>
                    </div>
                    <div class="pjt_card_footer">
                        <span class="pjt_role-card-projetos">Diretor-Presidente</span>
                        <span class="pjt_date-avisos">01/06/2025</span>
                    </div>
                </div>
            </div>

            <button class="pjt_section_button_vermais">Ver mais</button>
        </div>

        <div class="pjt_section">
            <div class="pjt_section_header">
                <h2>Tasks</h2>
                <button class="pjt_section_button_adicionar"><i class="bi bi-plus-lg"></i></button>
            </div>

            <div class="pjt_tasks_header_buttons">
                <button class="pjt_filter_button active">Kanban</button>
                <button class="pjt_filter_button">Lista</button>
            </div>

            <div class="dashboard_kanban_container">
                <div class="dashboard_kanban_column">
                    <div class="dashboard_kanban_header pjt_vermelho">
                        <h3>Não Iniciado</h3>
                    </div>
                    <div class="dashboard_kanban_cards">
                        <div class="dashboard_kanban_card">
                            <span class="pjt_card_badge pjt_vermelho">Não Iniciado</span>
                            <div class="pjt_card_content">
                                <h3 class="pjt_title-card-projetos">Abrir CNPJ</h3>
                                <p class="pjt_description-card-projetos">Abrir o CNPJ após o reconhecimento dos documentos.</p>
                            </div>
                            <div class="dashboard_kanban_card_footer">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span class="pjt_role-card-projetos">Igor Kassumoto<br><small class="pjt_text-gray">Vice-Presidente</small></span>
                                </div>
                                <button class="pjt_card_button_editar">Ver Mais</button>
                            </div>
                        </div>
                        <div class="dashboard_kanban_card">
                            <span class="pjt_card_badge pjt_vermelho">Não Iniciado</span>
                            <div class="pjt_card_content">
                                <h3 class="pjt_title-card-projetos">Abrir CNPJ</h3>
                                <p class="pjt_description-card-projetos">Abrir o CNPJ após o reconhecimento dos documentos.</p>
                            </div>
                            <div class="dashboard_kanban_card_footer">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span class="pjt_role-card-projetos">Igor Kassumoto<br><small class="pjt_text-gray">Vice-Presidente</small></span>
                                </div>
                                <button class="pjt_card_button_editar">Ver Mais</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard_kanban_column">
                    <div class="dashboard_kanban_header pjt_amarelo">
                        <h3>Em Andamento</h3>
                    </div>
                    <div class="dashboard_kanban_cards">
                        <div class="dashboard_kanban_card">
                            <span class="pjt_card_badge pjt_amarelo">Em Andamento</span>
                            <div class="pjt_card_content">
                                <h3 class="pjt_title-card-projetos">Faturar R$ 10.000,00</h3>
                                <p class="pjt_description-card-projetos">Obter um faturamento através de projetos, eventos, etc...</p>
                            </div>
                            <div class="dashboard_kanban_card_footer">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span class="pjt_role-card-projetos">Vitor Ferreira Viana<br><small class="pjt_text-gray">Diretor-Presidente</small></span>
                                </div>
                                <button class="pjt_card_button_editar">Ver Mais</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard_kanban_column">
                    <div class="dashboard_kanban_header pjt_verde">
                        <h3>Concluído</h3>
                    </div>
                    <div class="dashboard_kanban_cards">
                        <div class="dashboard_kanban_card">
                            <span class="pjt_card_badge pjt_verde">Concluído</span>
                            <div class="pjt_card_content">
                                <h3 class="pjt_title-card-projetos">Documentação Inicial</h3>
                                <p class="pjt_description-card-projetos">Obter a documentação inicial para a abertura da empresa júnior.</p>
                            </div>
                            <div class="dashboard_kanban_card_footer">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span class="pjt_role-card-projetos">Gabriel Fontana<br><small class="pjt_text-gray">Vice-Presidente</small></span>
                                </div>
                                <button class="pjt_card_button_editar">Ver Mais</button>
                            </div>
                        </div>
                        <div class="dashboard_kanban_card">
                            <span class="pjt_card_badge pjt_verde">Concluído</span>
                            <div class="pjt_card_content">
                                <h3 class="pjt_title-card-projetos">Documentação Inicial</h3>
                                <p class="pjt_description-card-projetos">Obter a documentação inicial para a abertura da empresa júnior.</p>
                            </div>
                            <div class="dashboard_kanban_card_footer">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span class="pjt_role-card-projetos">Gabriel Fontana<br><small class="pjt_text-gray">Vice-Presidente</small></span>
                                </div>
                                <button class="pjt_card_button_editar">Ver Mais</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="pjt_section_button_vermais">Ver mais</button>
        </div>

        <div class="pjt_section">
            <div class="pjt_section_header">
                <h2>Tasks</h2>
                <button class="pjt_section_button_adicionar"><i class="bi bi-plus-lg"></i></button>
            </div>

            <div class="pjt_tasks_header_buttons">
                <button class="pjt_filter_button">Kanban</button>
                <button class="pjt_filter_button active">Lista</button>
            </div>

            <div class="dashboard_list_container">
                <table class="pjt_tasks_table">
                    <thead>
                        <tr class="pjt_tr">
                            <th class="text-light">Nome</th>
                            <th class="text-light">Descrição</th>
                            <th class="text-light">Diretor Responsável</th>
                            <th class="text-light">Status</th>
                            <th class="text-light">Prazo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="pjt_tr">
                            <td class="pjt_td">Faturar R$ 10.000,00</td>
                            <td class="pjt_td">Obter um faturamento através de projetos, eventos, etc...</td>
                            <td class="pjt_td">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span>Vitor Ferreira Viana<small class="pjt_text-gray">Diretor-Presidente</small></span>
                                </div>
                            </td>
                            <td class="pjt_td"><span class="pjt_status pjt_verde">Concluído</span></td>
                            <td class="pjt_td">
                                <div class="prazo-wrapper">
                                    <span>01/06/2025</span>
                                    <button class="pjt_btn-acao">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="pjt_tr">
                            <td class="pjt_td">Faturar R$ 10.000,00</td>
                            <td class="pjt_td">Obter um faturamento através de projetos, eventos, etc...</td>
                            <td class="pjt_td">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span>Vitor Ferreira Viana<small class="pjt_text-gray">Diretor-Presidente</small></span>
                                </div>
                            </td>
                            <td class="pjt_td"><span class="pjt_status pjt_amarelo">Em Andamento</span></td>
                            <td class="pjt_td">
                                <div class="prazo-wrapper">
                                    <span>01/06/2025</span>
                                    <button class="pjt_btn-acao">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="pjt_tr">
                            <td class="pjt_td">Faturar R$ 10.000,00</td>
                            <td class="pjt_td">Obter um faturamento através de projetos, eventos, etc...</td>
                            <td class="pjt_td">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span>Vitor Ferreira Viana<small class="pjt_text-gray">Diretor-Presidente</small></span>
                                </div>
                            </td>
                            <td class="pjt_td"><span class="pjt_status pjt_vermelho">Não Iniciado</span></td>
                            <td class="pjt_td">
                                <div class="prazo-wrapper">
                                    <span>01/08/2025</span>
                                    <button class="pjt_btn-acao">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="pjt_tr">
                            <td class="pjt_td">Faturar R$ 10.000,00</td>
                            <td class="pjt_td">Obter um faturamento através de projetos, eventos, etc...</td>
                            <td class="pjt_td">
                                <div class="pjt_perfil">
                                    <i class="bi bi-person-fill" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span>Vitor Ferreira Viana<small class="pjt_text-gray">Diretor-Presidente</small></span>
                                </div>
                            </td>
                            <td class="pjt_td"><span class="pjt_status pjt_verde">Concluído</span></td>
                            <td class="pjt_td">
                                <div class="prazo-wrapper">
                                    <span>01/06/2025</span>
                                    <button class="pjt_btn-acao">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button class="pjt_section_button_vermais">Ver mais</button>
        </div>
    </div>
</body>

</html>

