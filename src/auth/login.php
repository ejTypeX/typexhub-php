<?php
session_start();
$mensagem_sucesso = '';

if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = $_SESSION['mensagem_sucesso'];
    // Limpa a sessão para que não apareça novamente
    unset($_SESSION['mensagem_sucesso']);
}

if (isset($_SESSION['usuario'])) {
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TX Hub - Login</title>
    <link rel="shortcut icon" href="../assets/images/tx-logo.ico" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            margin: 0;
            background: #212832;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            color: var(--text-base);
        }

        .form-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .logo {
            width: 160px;
            height: auto;
            filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.3));
        }

        .form-container {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            transition: all 0.3s ease;
        }

        .form-container:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
            transform: scale(1.01);
        }

        .form-container label {
            display: block;
            font-size: 0.95rem;
            margin-bottom: 6px;
            color: #D9D9D9;
        }

        .form-container input {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            background-color: #1E1E1E;
            color: #D9D9D9;
            font-size: 1rem;
            transition: box-shadow 0.2s ease;
        }

        .form-container input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #D9D9D9;
        }

        .form-container button {
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            background-color: #393D46;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-container button:hover {
            background-color: #006d71;
            transform: translateY(-2px);
        }

        p {
            color: #D9D9D9;
        }

        a {
            color: lightblue;
        }

        a:hover {
            color: #00afb5;
        }

        .message-box {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            width: 100%;
            font-size: 0.95rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>

<body>
    <div class="form-wrapper">
        
        <?php if ($mensagem_sucesso): ?>
            <div class="message-box success">
                <?php echo htmlspecialchars($mensagem_sucesso); ?>
            </div>
        <?php endif; ?>

        <img class="logo" src="../assets/images/typex-logo.png" alt="Logo da TypeX" />
        <form class="form-container" action="autenticar.php" method="post">
            <label for="ra">RA:</label>
            <input type="text" name="ra" id="ra" required />

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required />

            <button type="submit">Entrar</button>
            <p>Não tenho uma conta <a href="./registro/registro.php">Registrar</a></p>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: #FF6B6B; text-align: center; margin-top: 10px;">
                RA ou senha incorretos. Tente novamente.
            </p>
        <?php endif; ?>
    </div>

    <footer style="
        position: absolute;
        bottom: 20px;
        width: 100%;
        text-align: center;
        font-size: 0.85rem;
        color: #D9D9D9;
        font-family: 'Poppins', sans-serif;
    ">
        &copy; <?php echo date("Y"); ?> TypeX. Todos os direitos reservados.
    </footer>
</body>
</body>

</html>