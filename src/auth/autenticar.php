<?php 
session_start();
include '../include/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    /* 
        Voce esta armazenando o valor do campo 'nome_do_campo' em uma variavel PHP chamada $variavel
        $variavel = $_POST['nome_do_campo'];
    */

    $ra = $_POST['ra'];
    $senha = $_POST['senha'];

    /* 
       $stmt (significa statement) é uma variavel que armazena a preparação da consulta SQL
       $pdo é a variavel que representa a conexão com o banco de dados
        prepare() é um método que prepara uma consulta SQL para execução
    */

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usr_ra = :ra");
    $stmt->bindParam(':ra', $ra); // bindParam() vincula o valor da variavel $ra ao placeholder :ra na consulta SQL
    $stmt->execute(); // execute() executa a consulta SQL preparada
    
    /* 
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        fetch() recupera a próxima linha do conjunto de resultados da consulta SQL
        PDO::FETCH_ASSOC é uma constante que indica que a linha deve ser retornada como um array associativo (com nomes de colunas como chaves)

        voce pode verificar isso com:
        var_dump($usuario);
    */
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe, se está ativo e se a senha está correta
    if ($usuario && $usuario['usr_ativo'] && password_verify($senha, $usuario['usr_senha'])) { 
        $_SESSION['usuario_id'] = $usuario['usr_id']; // Armazena o ID do usuário na sessão
        $_SESSION['usuario_ra'] = $usuario['usr_ra']; // Armazena o RA do usuário na sessão
        $_SESSION['usuario_nome'] = $usuario['usr_nome']; // Armazena o nome do usuário na sessão
        $_SESSION['usuario'] = $usuario; // Armazena todos os dados do usuário na sessão
        header('Location: ../menu/dashboard/dashboard.php'); // Redireciona para a página protegida
        exit();
    } else {
        header('Location: login.php?error=1'); // Redireciona de volta para a página de login com um erro
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}


?>