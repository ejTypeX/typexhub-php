<?php 

include "../include/conexao.php"; // Incluindo arquivo de conexão com o banco de dados

?>

<table>
    <tr>
        <th>ID</th>
        <th>Campo 1</th>
        <th>Campo 2</th>
        <th>Ações</th>
    </tr>
    <?php
    // Preparando e executando a consulta SQL para selecionar todos os itens da tabela 'modelo'
    $stmt = $pdo->query("SELECT * FROM modelo"); // query() executa a consulta SQL diretamente
    // Loop para percorrer os resultados da consulta e exibir cada item em uma linha da tabela
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>"; // Exibindo o ID do item
        echo "<td>" . htmlspecialchars($row['campo1']) . "</td>"; // Exibindo o valor do campo 'campo1'
        echo "<td>" . htmlspecialchars($row['campo2']) . "</td>"; // Exibindo o valor do campo 'campo2'
        echo "<td>";
        echo "<a href='index.php?id=" . urlencode($row['id']) . "#modal-edit' class=\"open-modal\" data-modal=\"modal-2\">Editar</a> | "; // Link para editar o item
        echo "<a href='./functions/deletarItem.php?id=" . urlencode($row['id']) . "' onclick=\"return confirm('Tem certeza que deseja deletar este item?');\">Deletar</a>"; // Link para deletar o item com confirmação
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>