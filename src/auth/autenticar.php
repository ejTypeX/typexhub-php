<?php
session_start();
include '../include/conexao.php';

$ra = $_POST['ra'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($ra && $senha) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario_ra = ?");
        $stmt->execute([$ra]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['usuario_senha'])) {
            $_SESSION['usuario'] = $user['usuario_nome'];
            $_SESSION['ra'] = $user['usuario_ra'];
            header('Location: ../rh/rh.php');
            exit;
        } else {
            echo 'RA ou senha inválidos!';
        }
    } catch (PDOException $e) {
        echo 'Erro de conexão: ' . $e->getMessage();
    }
} else {
    echo 'Preencha RA e senha!';
}
?>