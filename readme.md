# 🚀 TypeX Hub

> Projeto PHP com Docker e MySQL

## 📄 Descrição

Este projeto oferece um ambiente completo e moderno para desenvolvimento em PHP, com os seguintes recursos integrados:

- 🔧 Apache + PHP 8.2
- 🛢️ MySQL com suporte a `phpMyAdmin`
- 🌐 URLs amigáveis via `.htaccess`
- 🗂️ Estrutura organizada (`src/`, `auth/`, `conexao.php`, etc)
- 🔐 Sistema de autenticação (login/logout) com sessões

## 📁 Estrutura de Pastas

``` bash
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

## ▶️ Como rodar o projeto

### ✅ Pré-requisitos

- Docker instalado
- Docker Compose instalado

#### 📦 Subir o ambiente

```bash
docker-compose up --build
```

- Acesse o Apache em: [http://localhost:8080](http://localhost:8080)  
- Acesse o phpMyAdmin em: [http://localhost:8081](http://localhost:8081)

#### 🔐 Login e Autenticação

O sistema possui exemplo de autenticação usando **PDO + sessões**.

Para proteger páginas internas, utilize o seguinte trecho:

```php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /auth/login.php');
    exit;
}
```

Logout disponível em: `/auth/logout.php`

## ⚙️ Configurações e Banco de Dados

- Conexão PDO reutilizável: `src/include/conexao.php`

### 🗄️ Sistema de Migrations

O projeto usa **migrations** para versionar o banco de dados:

```bash
# Aplicar todas as migrations pendentes
php database/migrate.php

# Verificar status das migrations
php database/migrate.php status

# Criar nova migration
./dev-sync.sh nova
```

### 🔄 Workflow de Desenvolvimento (Híbrido)

**Mais prático:** Desenvolva no phpMyAdmin + Migrations para versionamento

1. **Desenvolva rapidamente no phpMyAdmin:**

   - Acesse: [http://localhost:8081](http://localhost:8081)
   - Crie tabelas, modifique estruturas
   - Teste queries e dados

2. **Capture mudanças automaticamente:**

   ```bash
   ./dev-sync.sh sync    # Extrai estrutura atual
   ./dev-sync.sh nova    # Cria migration baseada nas mudanças
   ```

3. **Versione e compartilhe:**

   ```bash
   git add database/migrations/
   git commit -m "feat: adiciona nova funcionalidade"
   git push
   ```

4. **Equipe sincroniza:**

   ```bash
   git pull
   php database/migrate.php  # Aplica mudanças automaticamente
   ```

## 🛠️ Visão Geral do Processo de Desenvolvimento com Git

Este projeto utiliza uma estrutura de versionamento com Git baseada em branches. O fluxo de trabalho é dividido conforme abaixo:

### 🌳 Branches principais

- **`main`** → Código em produção (estável e validado)
- **`develop`** → Desenvolvimento contínuo
- **`homolog`** → Ambiente de testes e validação

### 🔄 mainclo de desenvolvimentomain

1. **Criar uma branch para sua funcionalidade**  
   A partir da `develop`, crie umaain com nome descritivo:

   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/login-google
   ```

   ---

2. **Desenvolver sua funcionalidade**  
   Faça commits e pushes normalmente:

   ```bash
   git add .
   git commit -m "feat: implementa login com Google"
   git push origin feature/login-google
   ```

    ---

3. **Abrir um Pull Request para a `develop`**  
   Após finalizar, crie um Pull Request da branch `feature/*` para `develop` (via GitHub ou GitLab).  
   O merge será feito após revisão e aprovação.

   ---
4. **Enviar para `homolog`**  
   Quando a `develop` estiver com múltiplas features testadas:

   ```bash
   git checkout homolog
   git pull origin homolog
   git merge develop
   git push origin homolog
   ```

    ---
5. **Enviar para `main` (produção)**  
   Após testes e validações na `homolog`:

   ```bash
   git checkout main
   git pull origin main
   git merge homolog
   git push origin main
   ```

---

## 📌 Observações

- O acesso direto a diretórios sem `index` está bloqueado com `Options -Indexes` no Apache.
- O projeto é facilmente extensível para novas rotas, funcionalidades e autenticações.

---
