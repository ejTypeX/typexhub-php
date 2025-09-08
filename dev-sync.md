# 🪟 Usando dev-sync no Windows

## 🚀 Solução PowerShell (Recomendada)

Use o script PowerShell adaptado para Windows:

```powershell
# Criar nova migration
.\dev-sync.ps1 nova

# Aplicar migrations pendentes
.\dev-sync.ps1 migrar

# Extrair estrutura do banco
.\dev-sync.ps1 extrair

# Mostrar ajuda
.\dev-sync.ps1 ajuda
```

## 🐧 Alternativa: Git Bash

Se preferir usar o script bash original:

```bash
# Abrir Git Bash e navegar para a pasta do projeto
cd /c/Users/vitor/Desktop/typexhub-php

# Executar o script bash
./scripts/dev-sync.sh nova
./scripts/dev-sync.sh sync
./scripts/dev-sync.sh manual
```

## 📝 Comandos Equivalentes

| Bash Original | PowerShell Windows | Descrição |
|---------------|-------------------|-----------|
| `./dev-sync.sh manual` | `.\dev-sync.ps1 nova` | Criar migration manual |
| `./dev-sync.sh sync` | `.\dev-sync.ps1 extrair` | Extrair estrutura |
| `php database/migrate.php` | `.\dev-sync.ps1 migrar` | Aplicar migrations |

## ✅ Verificação

O script PowerShell foi testado e funciona corretamente:
- ✅ Criação de migrations numeradas sequencialmente
- ✅ Template SQL com cabeçalho padronizado
- ✅ Integração com VS Code (abre automaticamente)
- ✅ Carregamento de variáveis do .env

## 🔄 Workflow de Desenvolvimento

1. **Criar nova migration:**
   ```powershell
   .\dev-sync.ps1 nova
   ```

2. **Editar o arquivo SQL gerado**

3. **Aplicar as mudanças:**
   ```powershell
   .\dev-sync.ps1 migrar
   ```

4. **Versionar:**
   ```bash
   git add database/migrations/
   git commit -m "feat: adiciona nova funcionalidade"
   ```
