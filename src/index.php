<?php 
session_start();

// Se o usuário estiver logado, redireciona para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: menu/dashboard/dashboard.php');
} else {
    // Se não estiver logado, redireciona para o login
    header('Location: auth/login.php');
}
exit;
?>