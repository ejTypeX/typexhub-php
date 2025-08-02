<?php
// Calcula o caminho relativo para a pasta assets baseado na URL atual
function obterAssetsPath() {
    $uri = $_SERVER['REQUEST_URI'];
    
    if (strpos($uri, '/menu/') !== false) {
        return '../../assets/';
    }
    
    return '../assets/';
}
$assets_path = obterAssetsPath();
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeX Hub</title>
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/vars.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/reset.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/header.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/sidebar.css">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/global.css">
    <link rel="icon" href="<?php echo $assets_path; ?>images/tx-logo.ico" type="image/x-icon">
    <script src="<?php echo $assets_path; ?>js/sidebar.js" defer></script>
</head>
<body>
    <header>
        <img src="<?php echo $assets_path; ?>images/tx-logo.png" alt="TX Logo">
        <h1>TypeX Hub</h1>
    </header>