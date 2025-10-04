<?php 

include "../../include/conexao.php"; // Incluindo arquivo de conexão com o banco de dados

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparando e executando a consulta SQL para deletar o item com o ID especificado
    $stmt = $pdo->prepare("DELETE FROM modelo WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: ../index.php?deleted=1'); // Redireciona para a página de índice com uma mensagem de sucesso
        exit();
    } else {
        echo "Erro ao deletar item.";
    }
} else {
    echo "ID do item não especificado.";
}
