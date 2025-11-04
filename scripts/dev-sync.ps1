# ==================== SCRIPT DE DESENVOLVIMENTO HÍBRIDO - VERSÃO POWERSHELL ====================

Write-Host "🔄 TypeX Hub - Sincronização de Banco de Desenvolvimento" -ForegroundColor Cyan
Write-Host "======================================================="

if (Test-Path .env) {
    Get-Content .env | ForEach-Object {
        if ($_ -match '^([^#][^=]*)=(.*)$') {
            [Environment]::SetEnvironmentVariable($matches[1], $matches[2], "Process")
        }
    }
    $DB_USER = $env:DB_USER
    $DB_PASSWORD = $env:DB_PASSWORD
    $DB_NAME = $env:DB_NAME
    $DB_ROOT_PASSWORD = $env:DB_ROOT_PASSWORD
} else {
    Write-Host "⚠️  Arquivo .env não encontrado. Usando valores padrão..." -ForegroundColor Yellow
    $DB_USER = "root"
    $DB_PASSWORD = "root123"
    $DB_NAME = "typexhub"
}

function Criar-Migration-Manual {
    Write-Host "📝 Criando nova migration manual..." -ForegroundColor Green
    
    $migrationFiles = Get-ChildItem "database/migrations" -Filter "*.sql" -ErrorAction SilentlyContinue
    if ($migrationFiles) {
        $ultimoNum = ($migrationFiles | ForEach-Object { [int]($_.Name.Substring(0,3)) } | Measure-Object -Maximum).Maximum
    } else {
        $ultimoNum = 0
    }
    
    $proximoNum = ($ultimoNum + 1).ToString("000")
    
    $nomeMigration = Read-Host "📋 Nome da migration (ex: adicionar_tabela_usuarios)"
    
    $arquivoMigration = "database/migrations/${proximoNum}_${nomeMigration}.sql"
    
    $dataAtual = Get-Date -Format "yyyy-MM-dd"
    $usuario = $env:USERNAME
    
    $conteudoMigration = @"
-- ==================== MIGRATION ${proximoNum}: $($nomeMigration.Replace('_', ' ').ToUpper()) ====================
-- Data: $dataAtual
-- Autor: $usuario
-- Descrição: Adicione aqui a descrição da migration

-- Suas mudanças aqui
-- Exemplo:
-- CREATE TABLE IF NOT EXISTS nova_tabela (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- Atualiza versão do banco
UPDATE configuracoes SET valor = '${proximoNum}' WHERE chave = 'versao_db';
"@

    $conteudoMigration | Out-File -FilePath $arquivoMigration -Encoding UTF8
    
    Write-Host "✅ Migration manual criada: $arquivoMigration" -ForegroundColor Green
    Write-Host "✏️  Edite o arquivo e adicione suas mudanças SQL" -ForegroundColor Yellow
    
    Write-Host "🚀 Abrindo arquivo para edição..." -ForegroundColor Cyan
    Start-Process "code" -ArgumentList $arquivoMigration -ErrorAction SilentlyContinue
}

function Extrair-Estrutura {
    Write-Host "📊 Extraindo estrutura atual do banco..." -ForegroundColor Cyan
    
    $command = "docker exec typexhub-db mysqldump -u$DB_USER -p$DB_ROOT_PASSWORD --no-data --routines --triggers --single-transaction --add-drop-table --add-locks --create-options $DB_NAME"
    
    try {
        $estrutura = Invoke-Expression $command
        $estrutura | Out-File -FilePath "temp_estrutura_atual.sql" -Encoding UTF8
        Write-Host "✅ Estrutura atual extraída para temp_estrutura_atual.sql" -ForegroundColor Green
        return $true
    } catch {
        Write-Host "❌ Erro ao extrair estrutura do banco: $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
}

function Aplicar-Migrations {
    Write-Host "🚀 Aplicando migrations pendentes..." -ForegroundColor Cyan
    
    try {
        docker exec -it typexhub php database/migrate.php
        Write-Host "✅ Migrations aplicadas com sucesso!" -ForegroundColor Green
    } catch {
        Write-Host "❌ Erro ao aplicar migrations: $($_.Exception.Message)" -ForegroundColor Red
    }
}

function Mostrar-Ajuda {
    Write-Host "💡 Uso: .\dev-sync.ps1 {nova|extrair|migrar|ajuda}" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "📋 Comandos disponíveis:" -ForegroundColor Cyan
    Write-Host "  nova     - Cria migration manual vazia" -ForegroundColor White
    Write-Host "  extrair  - Extrai estrutura atual do banco" -ForegroundColor White
    Write-Host "  migrar   - Aplica migrations pendentes" -ForegroundColor White
    Write-Host "  ajuda    - Mostra esta ajuda" -ForegroundColor White
    Write-Host ""
    Write-Host "🚀 Workflow recomendado:" -ForegroundColor Cyan
    Write-Host "  1. .\dev-sync.ps1 nova" -ForegroundColor White
    Write-Host "  2. Edite o arquivo SQL gerado" -ForegroundColor White
    Write-Host "  3. .\dev-sync.ps1 migrar" -ForegroundColor White
    Write-Host "  4. git add database/migrations/ && git commit" -ForegroundColor White
}

switch ($args[0]) {
    "nova" {
        Criar-Migration-Manual
    }
    "extrair" {
        Extrair-Estrutura
    }
    "migrar" {
        Aplicar-Migrations
    }
    "ajuda" {
        Mostrar-Ajuda
    }
    default {
        if ($args.Count -eq 0) {
            Mostrar-Ajuda
        } else {
            Write-Host "❌ Comando '$($args[0])' não reconhecido" -ForegroundColor Red
            Mostrar-Ajuda
        }
    }
}
