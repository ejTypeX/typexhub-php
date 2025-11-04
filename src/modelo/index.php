<?php
include "../include/conexao.php"; // Incluindo arquivo de conexão com o banco de dados
echo "<h2>Modelo</h2>"; // Imprimindo título da página
?>


<button class="open-modal" data-modal="modal-1">
    Abrir Form
</button>

<?php
include "./modal/formulario.php"; // Incluindo formulário HTML
include "./functions/mostrarItem.php"; // Incluindo função para mostrar itens

?>

<script src="../modelo/js/modal.js"></script> <!-- Incluindo arquivo JavaScript para funcionalidade do modal -->

<?php
// Se há um ID na URL, abrir automaticamente o modal de edição
if (isset($_GET['id'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal-2');
            if (modal) {
                modal.showModal();
            }
        });
    </script>";
}
?>
