<?php
$currentFile = $_SERVER['PHP_SELF'];
$basePath = '';

if (strpos($currentFile, '/menu/') !== false || strpos($currentFile, '/auth/') !== false) {
    $depth = substr_count(parse_url($currentFile, PHP_URL_PATH), '/') - 1;
    $basePath = str_repeat('../', $depth);
} else {
    $basePath = '';
}
?>
<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeX Hub</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/vars.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/reset.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/global.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/header.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/sidebar.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/projetos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" href="<?php echo $basePath; ?>assets/images/tx-logo.ico" type="image/x-icon">
</head>