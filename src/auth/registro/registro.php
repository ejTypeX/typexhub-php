<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TX Hub - Sign Up</title>
    <link rel="shortcut icon" href="../../assets/images/tx-logo.ico" type="image/x-icon" />
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
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
            max-height: 42.13px;
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
    </style>
</head>

<body>
    <div class="form-wrapper">
        <img class="logo" src="../../assets/images/typex-logo.png" alt="Logo da TypeX" />
        <form class="form-container" action="./processaRegistro.php" method="post">
            <div class="register">

                <label for="ra">RA:</label>
                <input type="text" name="ra" id="ra" required />

                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required />

                <label for="sobrenome">Sobrenome:</label>
                <input type="text" name="sobrenome" id="sobrenome" required />

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required />

                <label for="telefone">Telefone:</label>
                <input type="tel" name="telefone" id="telefone" required />

                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required />

            </div>

            <div class="register">
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" id="cpf" required />

                <label for="rg">RG:</label>
                <input type="text" name="rg" id="rg" required />

                <label for="nascimento">Data de nascimento:</label>
                <input type="date" name="nascimento" id="nascimento" required />

                <label for="cargo">Cargo:</label>
                <input type="text" name="cargo" id="cargo" required />

                <label for="diretoria">Diretoria:</label>
                <input type="text" name="diretoria" id="diretoria" />

                <label for="confirmarSenha">Repita a Senha:</label>
                <input type="password" name="confirmarSenha" id="confirmarSenha" required />

            </div>

            <button type="submit">Registrar</button>
            <p>Já tenho uma conta <a href="../login.php">Fazer login</a></p>
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