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

<body class="pjt_body">
    <div class="pjt_container">

        <div id="tasks_projetos" class="pjt_section">
            <div class="pjt_section_header">
                <h2>Avisos <span class="pjt_badge">2</span></h2>
            </div>

            <div class="pjt_cards_row">
                <div class="pjt_card_linha">
                    <span class="pjt_card_badge pjt_vermelho">Advertência</span>
                    <div class="pjt_card_content">
                        <h3 class="pjt_title-card-projetos">TypeX Hub</h3>
                        <p class="pjt_description-card-projetos">Atraso na entrega da task "Grid: Seção Avisos na tela
                            de Projetos"</p>
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
                        <p class="pjt_description-card-projetos">Atraso na entrega da task "Grid: Seção Avisos na tela
                            de Projetos"</p>
                    </div>
                    <div class="pjt_card_footer">
                        <span class="pjt_role-card-projetos">Diretor-Presidente</span>
                        <span class="pjt_date-avisos">01/05/2025</span>
                    </div>
                </div>
            </div>

            <button class="pjt_section_button_vermais">Ver mais</button>
        </div>

        <div id="tasks_projetos" class="pjt_section">
            <div class="pjt_section_header">
                <h2>Projetos</h2>
                <button class="pjt_section_button_adicionar"><i class="bi bi-plus-lg"></i></button>
            </div>

            <div class="pjt_cards_row">
                <div class="pjt_card_projetos">
                    <span class="pjt_card_badge pjt_amarelo">Em Andamento</span>
                    <div class="pjt_card_content">
                        <h3 class="pjt_title-card-projetos">TypeX Hub</h3>
                        <p class="pjt_description-card-projetos">Desenvolvimento completo da plataforma TypeX Hub
                            incluindo frontend, backend e integrações com sistemas externos para gestão de projetos
                            corporativos.</p>
                    </div>
                    <div class="pjt_card_footer">
                        <div class="pjt_perfil">
                            <img class="pjt_role-card-projetos" src="https://randomuser.me/api/portraits/men/75.jpg"
                                alt="foto">
                            <span class="pjt_role-card-projetos pjt_description-card-projetos">Vitor Ferreira
                                Viana<br><small class="pjt_text-gray">Diretor-Presidente</small></span>
                        </div>
                        <button class="pjt_card_button_editar"><i class="bi bi-pencil-square"></i> Ver
                            Projeto...</button>
                    </div>
                </div>

                <div class="pjt_card_projetos">
                    <span class="pjt_card_badge pjt_vermelho">Não Iniciado</span>
                    <div class="pjt_card_content">
                        <h3 class="pjt_title-card-projetos">Parcerias</h3>
                        <p class="pjt_description-card-projetos">Estabelecimento de parcerias estratégicas com empresas
                            do setor para expansão de mercado e desenvolvimento de soluções conjuntas.</p>
                    </div>
                    <div class="pjt_card_footer">
                        <div class="pjt_perfil">
                            <img class="pjt_role-card-projetos" src="https://randomuser.me/api/portraits/men/75.jpg"
                                alt="foto">
                            <span class="pjt_role-card-projetos pjt_description-card-projetos">Vitor Ferreira
                                Viana<br><small class="pjt_text-gray">Diretor-Presidente</small></span>
                        </div>
                        <button class="pjt_card_button_editar"><i class="bi bi-pencil-square"></i> Ver
                            Projeto...</button>
                    </div>
                </div>
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
                    <tr class="pjt_tr">
                        <td class="pjt_td">Fazer front-end</td>
                        <td class="pjt_td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="pjt_td">
                            <div class="pjt_perfil">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="foto">
                                <span>Vitor Ferreira Viana<small class="pjt_text-gray">Diretor-Presidente</small></span>
                            </div>
                        </td>
                        <td class="pjt_td"><span class="pjt_status pjt_verde">Concluído</span></td>
                        <td class="pjt_td">01/06/2025</td>
                        <td class="pjt_td">
                            <button class="pjt_btn-acao">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="pjt_tr">
                        <td class="pjt_td">Faturar R$ 10.000,00</td>
                        <td class="pjt_td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="pjt_td">
                            <div class="pjt_perfil">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="foto">
                                <span>Vitor Ferreira Viana<small class="pjt_text-gray">Diretor-Presidente</small></span>
                            </div>
                        </td>
                        <td class="pjt_td"><span class="pjt_status pjt_amarelo">Em Andamento</span></td>
                        <td class="pjt_td">01/06/2025</td>
                        <td class="pjt_td">
                            <button class="pjt_btn-acao">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="pjt_tr">
                        <td class="pjt_td">Faturar R$ 10.000,00</td>
                        <td class="pjt_td">Obter um faturamento através de projetos, eventos, etc...</td>
                        <td class="pjt_td">
                            <div class="pjt_perfil">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="foto">
                                <span>Vitor Ferreira Viana<small class="pjt_text-gray">Diretor-Presidente</small></span>
                            </div>
                        </td>
                        <td class="pjt_td"><span class="pjt_status pjt_vermelho">Não Iniciado</span></td>
                        <td class="pjt_td">01/06/2025</td>
                        <td class="pjt_td">
                            <button class="pjt_btn-acao">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button class="pjt_section_button_vermais">Ver mais</button>
        </div>
    </div>
</body>

</html>