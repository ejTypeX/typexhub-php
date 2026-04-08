<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario'])) {
    $login_path = 'auth/login.php';
    
    if (strpos($_SERVER['REQUEST_URI'], '/menu/') !== false) {
        $login_path = '../../auth/login.php';
    } elseif (strpos($_SERVER['REQUEST_URI'], '/auth/') !== false) {
        $login_path = 'login.php';
    }
    
    header('Location: ' . $login_path);
    exit();
}

// Opcional: Verificar se o usuário ainda está ativo no banco
// include '../include/conexao.php';
// $stmt = $pdo->prepare("SELECT usr_ativo FROM usuario WHERE usr_id = ?");
// $stmt->execute([$_SESSION['usuario_id']]);
// $usuario_ativo = $stmt->fetchColumn();
// 
// if (!$usuario_ativo) {
//     session_destroy();
//     header('Location: ../auth/login.php?error=2');
//     exit();
// }
?>
