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
│   └── auth/
│       ├── login.php
│       ├── logout.php
│       └── autenticar.php
│   └── include/
│       ├── conexao.php
│       ├── header.php
│       └── sidebar.php
│   └── menu/
│       ├── rh/
│       │   ├── rh.php
│       │   ├── criarUsuario.php
│       │   ├── listarUsuarios.php
│       │   ├── controller/
│       │   │   ├── criarAdvertencia.php
│       │   │   ├── editarAdvertencia.php
│       │   └── repository/
│       │       ├── advertenciaRepository.php
│       │       ├── membroRepository.php
│       ├── presidencia/
│       │   ├── presidencia.php
│       │   ├── criarTask.php
│       │   ├── listaTasks.php
│       │   ├── controller/
│       │   │   ├── criarTask.php
│       │   │   ├── editarTask.php
│       │   └── repository/
│       │       ├── taskRepository.php
│       │       └── reuniaoRepository.php
│       ├── projetos/
│       │   ├── projetos.php
│       │   ├── projeto_detalhes.php
│       │   ├── criarProjeto.php
│       │   ├── controller/
│       │   │   ├── criarProjeto.php
│       │   │   ├── editarProjeto.php
│       │   └── repository/
│       │       ├── projetoRepository.php
│       │       └── tarefaRepository.php
│       └── ...
└── ...
```

## 🏗️ Arquitetura do Sistema

O projeto segue um padrão arquitetural simples e organizado, separando responsabilidades em três camadas principais:

### 📄 Front-end (Páginas PHP)
- Arquivos na raiz de cada módulo (ex: `rh.php`, `criarUsuario.php`)
- Responsáveis pela interface do usuário e apresentação
- Contêm formulários HTML, exibição de dados e interações básicas

### 🎯 Controllers
- Localizados em `controller/` dentro de cada módulo
- Lidam com a lógica de negócio e processamento de requisições
- Validam dados de entrada, processam formulários e coordenam operações
- Chamam os repositories para acessar o banco de dados

### 🗄️ Repositories
- Localizados em `repository/` dentro de cada módulo
- Encapsulam todo o acesso ao banco de dados
- Contêm queries SQL, métodos CRUD (Create, Read, Update, Delete)
- Fornecem uma interface limpa para os controllers acessarem dados

Essa separação facilita a manutenção, teste e escalabilidade do código.

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
docker exec -it typexhub php database/migrate.php

# Verificar status das migrations
docker exec -it typexhub php database/migrate.php status

# Criar nova migration
./dev-sync.sh nova
```

### 🌱 Seeder de desenvolvimento

Após aplicar **todas** as migrations, popule departamentos, papéis e usuários de teste (idempotente — pode rodar várias vezes):

1. Defina no `.env` (na raiz do projeto), **sem commitar credenciais reais**:

   - `SEED_ROOT_PASSWORD` — senha do usuário root de desenvolvimento (papel **presidente**, `usr_senha_temporaria = 0`).
   - `SEED_DEV_PASSWORD` — senha compartilhada dos demais usuários seedados.
   - Opcional: `SEED_ROOT_EMAIL`, `SEED_ROOT_RA` (padrões: `presidente@dev.com`, `presidente.dev`).

2. Com Docker:

   ```bash
   docker exec -it typexhub php database/seed_dev.php
   ```

   Localmente (PHP na máquina, mesmo `.env`):

   ```bash
   php database/seed_dev.php
   ```

O script usa `password_hash()` (mesmo algoritmo padrão do PHP que o login com `password_verify`) e imprime no terminal o que foi **inserido** ou **ignorado** (já existia).

Inclui ainda **tarefas simuladas** na tabela legada `tasks` (migration 001): cria diretorias `[DEV] …`, usuários legados (`legacy.*@dev.local`), o projeto `[DEV] Projeto TypeX Hub` e várias tasks com títulos prefixados `[DEV]` (idempotentes por título).

**Rodar migrations + seed em sequência (Docker):**

```bash
docker exec -it typexhub php database/migrate.php
docker exec -it typexhub php database/seed_dev.php
```

(Defina `SEED_ROOT_PASSWORD` e `SEED_DEV_PASSWORD` no `.env` na raiz para o container receber as variáveis após `docker compose up`.)

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
