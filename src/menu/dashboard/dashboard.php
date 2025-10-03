<?php 
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

include "../../include/header.php";
?>

<div class="dashboard-container">
    <h1>Bem-vindo ao Dashboard, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>!</h1>
    <p>Você está logado com sucesso.</p>
    
    <div class="quick-links">
        <a href="../rh/rh.php" class="btn btn-primary">Recursos Humanos</a>
        <a href="../rh/listarUsuarios.php" class="btn btn-secondary">Ver Usuários</a>
        <a href="../../auth/logout.php" class="btn btn-danger">Sair</a>
    </div>
</div>

<style>
.dashboard-container {
    padding: 2rem;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.dashboard-container h1 {
    color: #FFFFFF;
    margin-bottom: 1rem;
}

.dashboard-container p {
    color: #D9D9D9;
    margin-bottom: 2rem;
}

.quick-links {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #393D46;
    color: white;
}

.btn-secondary {
    background-color: transparent;
    color: #D9D9D9;
    border: 2px solid #D9D9D9;
}

.btn-danger {
    background-color: #ef4444;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
}
</style>

</body>
</html>