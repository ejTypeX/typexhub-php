<?php
$host = "db:3306";
$dbname = "typex";
$user = "root";
$pass = "masterkey";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT CONCAT('-',SUM(valor_principal)) as total FROM contas_a_pagar";
    $query = $pdo->query($sql);
    $resultado = $query->fetch(PDO::FETCH_ASSOC);
    $total = (float) $resultado['total'];

    header('Content-Type: application/json');
    echo json_encode(['total' => $total]);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>