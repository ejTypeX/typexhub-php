<?php

include "../../include/conexao.php"; // Incluindo arquivo de conexão com o banco de dados

// IF (Se o metodo de requisição for POST, processa o formulário)
IF ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $campo1 = $_POST['campo1']; // Armazenando valor do campo 'campo1' em uma variável PHP
    $campo2 = $_POST['campo2']; // Armazenando valor do campo 'campo2' em uma variável PHP

    
    /*
    $stmt = statement (significa declaração) é uma variável que armazena a preparação da consulta SQL
    $pdo é a variável que representa a conexão com o banco de dados
    prepare() é um método que prepara uma consulta SQL para execução
    */

    // Preparando e executando a consulta SQL para inserir os dados no banco de dados
    $stmt = $pdo->prepare("INSERT INTO modelo (campo1, campo2) VALUES (:campo1, :campo2)");
    $stmt->bindParam(':campo1', $campo1); // bindParam() vincula o valor da variável $campo1 ao placeholder :campo1 na consulta SQL
    $stmt->bindParam(':campo2', $campo2); // bindParam() vincula o valor da variável $campo2 ao placeholder :campo2 na consulta SQL

    // Executando a consulta SQL
    // $stmt->execute() executa a consulta SQL preparada
    if ($stmt->execute()) {
        header('Location: ../index.php?success=1&message=Modelo inserido com sucesso!'); // Redireciona para a página de índice com uma url de sucesso
        exit();
    } else {
        echo "Erro ao inserir modelo.";
    }
}