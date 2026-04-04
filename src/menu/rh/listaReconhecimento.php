<?php 
include "../../include/header.php";
include "../../include/conexao.php";
include "./repository/reconhecimentoRepository.php";

// Buscar usuários com suas informações relacionadas
try {
    $reconhecimento = findAll($pdo);
} catch (PDOException $e) {
    $usuarios = [];
    $error = "Erro ao buscar usuários: " . $e->getMessage();
}
?>

<style>
    .usuarios-container {
        padding: 2rem;
        min-height: calc(100vh - 100px);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: 'Inter', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0;
    }

    .btn-add {
        background-color: #393D46;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background-color: #006d71;
        transform: translateY(-2px);
    }

    .usuarios-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .usuario-card {
        background-color: var(--bg-container-color);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .usuario-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .usuario-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .usuario-foto {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #D9D9D9;
    }

    .usuario-foto.placeholder {
        background-color: #393D46;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.5rem;
    }

    .usuario-info h3 {
        font-family: 'Inter', sans-serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: #FFFFFF;
        margin: 0 0 0.25rem 0;
    }

    .usuario-info .cargo {
        font-size: 0.9rem;
        color: #B0B0B0;
        margin: 0;
    }

    .usuario-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #D9D9D9;
    }

    .detail-label {
        font-weight: 600;
        min-width: 80px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-ativo {
        background-color: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-inativo {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .usuario-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
    }

    .btn-edit {
        background-color: #3b82f6;
        color: white;
    }

    .btn-edit:hover {
        background-color: #2563eb;
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #B0B0B0;
        grid-column: 1 / -1;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #D9D9D9;
    }

    @media (max-width: 768px) {
        .usuarios-container {
            padding: 1rem;
        }
        
        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .usuarios-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="usuarios-container">
    <div class="page-header">
        <h1 class="page-title">Reconhecimentos Cadastrados</h1>
        <a href="criarReconhecimento.php" class="btn-add">+ Novo Reconhecimento</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-error" style="margin-bottom: 2rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="usuarios-grid">
        <?php if (empty($reconhecimento)): ?>
            <div class="empty-state">
                <h3>Nenhum reconhecimento encontrado</h3>
                <p>Clique em "Novo Reconhecimento" para cadastrar o primeiro Reconhecimento.</p>
            </div>
        <?php else: ?>
            <?php foreach ($reconhecimento as $rec): ?>
                <div class="usuario-card">
                    <div class="usuario-header">
                        
                        <div class="usuario-info">
                            <h3><?= htmlspecialchars($rec['membro_nome']) ?></h3>
                        </div>
                    </div>
                    
                    <div class="usuario-details">
                        <div class="detail-item">
                            <span class="detail-label">Diretor:</span>
                            <span><?= htmlspecialchars($rec['diretor_nome']) ?></span>
                        </div>
                        
                        <?php if ($rec['feito']): ?>
                            <div class="detail-item">
                                <span class="detail-label">Reconhecimento:</span>
                                <span><?= htmlspecialchars($rec['feito']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                      
                    </div>
                    
                    <div class="usuario-actions">
                        <a href="editarReconhecimento.php?id=<?= $rec['id'] ?>" class="btn-action btn-edit">
                            Editar
                        </a>
                        <button class="btn-action btn-delete" onclick="confirmarExclusao(<?= $rec['id'] ?>, '<?= htmlspecialchars($rec['feito']) ?>')">
                            Excluir
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmarExclusao(userId, userName) {
    if (confirm(`Tem certeza que deseja excluir o reconhecimento "${userName}"?\n\nEsta ação não pode ser desfeita.`)) {
        // Redirecionar para o script de exclusão
        window.location.href = `./controller/excluiReconhecimento.php?id=${userId}`;
    }
}
</script>

</body>
</html>