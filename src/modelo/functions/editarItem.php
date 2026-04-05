<?php
include "../../include/conexao.php"; // Incluindo arquivo de conexão com o banco de dados

// Recebendo dados do formulário
$id = $_POST['id'] ?? null;
$campo1 = $_POST['campo1'] ?? '';
$campo2 = $_POST['campo2'] ?? '';

if ($id && $campo1 && $campo2) {
    $stmt = $pdo->prepare("UPDATE modelo SET campo1 = ?, campo2 = ? WHERE id = ?");
    $updated = $stmt->execute([$campo1, $campo2, $id]);

    if ($updated) {
        // Redireciona de volta para a página com a lista
        header("Location: ../index.php?msg=atualizado");
        exit;
    } else {
        echo "Erro ao atualizar o item.";
        header("Location: ../index.php?msg=erro");
        exit;
    }
} else {
    echo "Dados inválidos.";
}
