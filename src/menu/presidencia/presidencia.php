<?php 
include "../../include/header.php";
include "../../include/sidebar.php";

/*$stmt = $pdo->prepare("SELECT * FROM projetos WHERE status != 'Concluída'");
$stmt->execute();
$projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/
?>
<script>

    // Função para expandir/contrair cards
    function loadMoreProjects() {
        const container = document.querySelector('.container');
        const btnVerMais = document.getElementById('btnVerMais');
        const allCards = document.querySelectorAll('.card');
        
        // Verificar se está expandido
        const isExpanded = container.classList.contains('expanded');
        
        if (!isExpanded) {
            // Expandir - mostrar todos os cards
            allCards.forEach(card => card.classList.add('show-all'));
            container.classList.add('expanded');
            btnVerMais.textContent = 'Ver menos';
        } else {
            // Contrair - mostrar apenas os primeiros 6
            allCards.forEach(card => card.classList.remove('show-all'));
            container.classList.remove('expanded');
            btnVerMais.textContent = 'Ver mais';
            
            // Scroll suave para o topo da seção
            document.getElementById('avisos_presidencia').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }
</script>

<section id="section_avisos_presidencia">
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
                <!-- Card vazio caso não haja projetos -->
                <div class="card" style="text-align: center; justify-content: center;">
                    <h2 class="project-title">Nenhum projeto encontrado</h2>
                    <p class="project-description">Não há projetos pendentes no momento.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (/*count($projetos)*/true): ?>
            <button class="button_vermais" onclick="loadMoreProjects()">Ver mais</button>
        <?php endif; ?>
    </div>
</section>



</body>
</html>