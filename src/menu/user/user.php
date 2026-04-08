<?php 
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

include "../../include/header.php";
?>
</body>
</html>