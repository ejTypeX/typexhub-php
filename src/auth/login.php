<?php
session_start();
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
</style>
</head>
<body>
    <div class="form-wrapper">
        <img class="logo" src="../assets/images/typex-logo.png" alt="Logo da TypeX" />
        <form class="form-container" action="autenticar.php" method="post">
            <label for="ra">RA:</label>
            <input type="text" name="ra" id="ra" required />

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required />

            <button type="submit">Entrar</button>
        </form>
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