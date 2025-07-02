# TypeX Hub

Projeto PHP com Docker e MySQL.

## Descrição

Este projeto é um ambiente PHP moderno, pronto para desenvolvimento web, com suporte a:

- Apache + PHP 8.2
- MySQL
- phpMyAdmin
- URLs amigáveis via .htaccess
- Estrutura organizada (src/, auth/, conexao.php, etc)
- Exemplo de login/logout com autenticação

## Estrutura de Pastas

```txt
/ (raiz)
├── docker-compose.yml
├── Dockerfile
├── src/
│   ├── index.php
│   ├── config.php
│   ├── conexao.php
│   └── auth/
│       ├── login.php
│       ├── logout.php
│       └── autenticar.php
└── ...
```

## Como rodar o projeto

1. **Pré-requisitos:**
   - Docker e Docker Compose instalados

2. **Subir o ambiente:**

   ```sh
   docker-compose up --build
   ```

   O Apache estará disponível em [http://localhost:8080]
   O phpMyAdmin estará em [http://localhost:8081]

3. **Login:**
   - O sistema possui exemplo de autenticação usando PDO e sessões
   - Para proteger páginas, use o trecho:
  
     ```php
     session_start();
     if (!isset($_SESSION['usuario'])) {
         header('Location: /auth/login.php');
         exit;
     }
     ```

Logout disponível em `/auth/logout.php`

4. **Configurações:**
   - Conexão PDO reutilizável em `src/include/conexao.php`

## Observações

- O acesso direto a diretórios sem index está bloqueado (`Options -Indexes`).
- O projeto é facilmente extensível para novas rotas e funcionalidades.
