<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuário</title>
</head>
<body>
    <form method="POST" action="registrarUsuario.php">
        <input type="text" name="nome" placeholder="Nome:" required>
        <input type="text" name="sobrenome" placeholder="Sobrenome:" required>
        <input type="email" name="email" placeholder="Email:" required>
        <input type="password" name="senha" placeholder="Senha:" required>
        <input type="text" name="ra" placeholder="RA:" required>
        <input type="text" name="cargo" placeholder="Cargo:" required>
        <input type="text" name="diretoria" placeholder="Diretoria:" required>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>