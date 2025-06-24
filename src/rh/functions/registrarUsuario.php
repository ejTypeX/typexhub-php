<?php
include '../include/conexao.php';

var_dump($_POST);

$nome = $_POST['nome'] ?? '';
$sobrenome = $_POST['sobrenome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$ra = $_POST['ra'] ?? '';
$cargo = $_POST['cargo'] ?? '';
$diretoria = $_POST['diretoria'] ?? '';

if ($nome && $sobrenome && $email && $senha && $ra && $cargo && $diretoria) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario_nome, usuario_sobrenome, usuario_email, usuario_senha, usuario_ra, usuario_cargo, usuario_diretoria) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $sobrenome, $email, $senha_hash, $ra, $cargo, $diretoria]);
        echo 'Usuário cadastrado com sucesso!';
    } catch (PDOException $e) {
        echo 'Erro ao cadastrar: ' . $e->getMessage();
    }
} else {
    echo 'Preencha todos os campos!';
}
