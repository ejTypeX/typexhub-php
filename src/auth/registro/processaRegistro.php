<?php

session_start();
include "../../include/conexao.php"; // Incluindo arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ra = $_POST["ra"];
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $cpf = $_POST["cpf"];
    $rg = $_POST["rg"];
    $nascimento = $_POST["nascimento"];
    $cargo = $_POST["cargo"];
    $diretoria = $_POST["diretoria"];
    $senha = $_POST["senha"];
    $confirmarSenha = $_POST["confirmarSenha"];

    $passwordHash = password_hash($senha, PASSWORD_DEFAULT);
    $erros = array();

    if (empty($diretoria)) {
        $diretoria = NULL;
    } else {
        // Se não estiver vazio, converte para inteiro (para garantir que seja um número)
        $diretoria = (int)$diretoria;
    }

    if (empty($ra) or empty($nome) or empty($sobrenome) or empty($email) or empty($telefone) or empty($cpf) or empty($nascimento) or empty($cargo) or empty($senha) or empty($confirmarSenha)) {
        array_push($errors, "Todos os campos obrigatórios devem ser preenchidos!");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Insira um email valido!");
    }
    if (strlen($telefone) < 10 or strlen($telefone) > 11) {
        array_push($errors, "Insira um telefone valido com DDD!");
    }
    if (strlen($cpf) !== 11) {
        array_push($errors, "Insira um CPF valido!");
    }
    if (strlen($rg) !== 9) {
        array_push($errors, "Insira um RG valido!");
    }
    if (strlen($senha) < 6) {
        array_push($errors, "A senha deve ter pelo menos 6 caracteres!");
    }
    if ($senha !== $confirmarSenha) {
        array_push($errors, "As senhas não coincidem!");
    }

    if (count($erros) > 0) {
        /*echo "<h3>Erros Encontrados:</h3>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }*/
            $_SESSION['erros'] = $erros;
        // Redireciona de volta para a página do formulário
        header("Location: registro.php"); 
        exit();
    } else {
        //insere no db
        $tablesStmt = $pdo->prepare("SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = DATABASE() AND TABLE_NAME = 'usuarios'");
        $tablesStmt->execute();
        $found = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

        if (in_array('usuarios', $found)) {
            $table = 'usuarios';
        } else {
            throw new Exception('Nenhuma tabela de atividades encontrada no banco de dados. Rode as migrations.');
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario_ra, usuario_nome, usuario_sobrenome, usuario_email, usuario_telefone, usuario_cpf, usuario_rg, usuario_nascimento, usuario_cargo, diretoria_id, usuario_senha) VALUES (:ra, :nome, :sobrenome, :email, :telefone, :cpf, :rg, :nascimento, :cargo, :diretoria, :senha)");
        $stmt->bindParam(':ra', $ra);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':nascimento', $nascimento);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':diretoria', $diretoria);
        $stmt->bindParam(':senha', $passwordHash);

        if ($stmt->execute()) {
            $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso! Faça login para continuar.";
            header('Location: ../login.php');
            exit();
        }
    }
}
