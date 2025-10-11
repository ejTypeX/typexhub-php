<dialog id="modal-1">
    <form method="POST" action="./functions/inserirItem.php">
        <h3>Formulário de Exemplo</h3>
        <label for="campo1">Campo 1:</label>
        <input type="text" id="campo1" name="campo1" required>
    
        <label for="campo2">Campo 2:</label>
        <input type="text" id="campo2" name="campo2" required>
    
        <button type="submit">Enviar</button>
    </form>
</dialog>

<?php 
include "../include/conexao.php";

// Pegando o ID do item a ser editado via GET (ex: editar.php?id=1)
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM modelo WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $item = null;
}
?>

<dialog id="modal-2">
    <form method="POST" action="./functions/editarItem.php">
        <h3>Formulário de Edição</h3>

        <!-- Passando o ID do item escondido -->
        <input type="hidden" name="id" value="<?= $item['id'] ?? '' ?>">

        <label for="edit-campo1">Campo 1:</label>
        <input type="text" id="edit-campo1" name="campo1" value="<?= htmlspecialchars($item['campo1'] ?? '') ?>" required>

        <label for="edit-campo2">Campo 2:</label>
        <input type="text" id="edit-campo2" name="campo2" value="<?= htmlspecialchars($item['campo2'] ?? '') ?>" required>

        <button type="submit">Atualizar</button>
        <button type="button" class="close-modal" data-modal="modal-2">Cancelar</button>
    </form>
</dialog>

