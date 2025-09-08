#!/bin/bash

# ==================== SCRIPT DE DESENVOLVIMENTO HÍBRIDO ====================

echo "🔄 TypeX Hub - Sincronização de Banco de Desenvolvimento"
echo "======================================================="

# Carrega variáveis do .env se existir
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
else
    echo "⚠️  Arquivo .env não encontrado. Usando valores padrão..."
    DB_USER="root"
    DB_PASSWORD="root123"
    DB_NAME="typexhub"
fi

# Função para extrair estrutura atual do banco
extrair_estrutura() {
    echo "📊 Extraindo estrutura atual do banco..."
    
    docker exec typexhub-db mysqldump \
        -u${DB_USER} -p${DB_PASSWORD} \
        --no-data \
        --routines \
        --triggers \
        --single-transaction \
        --add-drop-table \
        --add-locks \
        --create-options \
        ${DB_NAME} > temp_estrutura_atual.sql
    
    if [ $? -eq 0 ]; then
        echo "✅ Estrutura atual extraída para temp_estrutura_atual.sql"
    else
        echo "❌ Erro ao extrair estrutura do banco"
        exit 1
    fi
}

extrair_estrutura_migrations() {
    echo "📋 Recriando estrutura das migrations..."
    
    docker exec typexhub-db mysql -u${DB_USER} -p${DB_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS temp_migrations;"
    
    for migration in database/migrations/*.sql; do
        if [ -f "$migration" ]; then
            echo "  📄 Aplicando: $(basename $migration)"
            docker exec -i typexhub-db mysql -u${DB_USER} -p${DB_PASSWORD} temp_migrations < "$migration"
        fi
    done
    
    docker exec typexhub-db mysqldump \
        -u${DB_USER} -p${DB_PASSWORD} \
        --no-data \
        --routines \
        --triggers \
        --single-transaction \
        --add-drop-table \
        --add-locks \
        --create-options \
        temp_migrations > temp_estrutura_migrations.sql
    
    docker exec typexhub-db mysql -u${DB_USER} -p${DB_PASSWORD} -e "DROP DATABASE temp_migrations;"
    
    echo "✅ Estrutura das migrations extraída para temp_estrutura_migrations.sql"
}

comparar_estruturas() {
    echo "🔍 Comparando estruturas para detectar mudanças..."
    
    if [ ! -f "temp_estrutura_atual.sql" ]; then
        echo "❌ Arquivo temp_estrutura_atual.sql não encontrado"
        exit 1
    fi
    
    if [ ! -f "temp_estrutura_migrations.sql" ]; then
        echo "❌ Arquivo temp_estrutura_migrations.sql não encontrado"
        exit 1
    fi
    
    diff temp_estrutura_migrations.sql temp_estrutura_atual.sql > temp_diferencas.txt
    
    if [ -s temp_diferencas.txt ]; then
        echo "⚠️  MUDANÇAS DETECTADAS!"
        echo "📄 Diferenças encontradas em temp_diferencas.txt"
        echo ""
        echo "📋 Resumo das mudanças:"
        grep -E "CREATE TABLE|ALTER TABLE|DROP TABLE|ADD COLUMN|DROP COLUMN" temp_diferencas.txt | head -10
        return 0
    else
        echo "✅ Nenhuma mudança detectada na estrutura"
        return 1
    fi
}

gerar_migration_automatica() {
    echo "🤖 Gerando migration automática baseada nas mudanças..."
    
    ULTIMO_NUM=$(ls database/migrations/ | grep -o '^[0-9]*' | sort -n | tail -1 2>/dev/null || echo "000")
    PROXIMO_NUM=$(printf "%03d" $((ULTIMO_NUM + 1)))
    
    read -p "📋 Nome da migration (ex: adicionar_tabela_produtos): " NOME_MIGRATION
    
    ARQUIVO_MIGRATION="database/migrations/${PROXIMO_NUM}_${NOME_MIGRATION}.sql"
    
    echo "� Analisando mudanças para gerar SQL..."
    
    cat > "$ARQUIVO_MIGRATION" << EOF
-- ==================== MIGRATION ${PROXIMO_NUM}: $(echo $NOME_MIGRATION | tr '_' ' ' | tr '[:lower:]' '[:upper:]') ====================
-- Data: $(date +%Y-%m-%d)
-- Autor: $USER
-- Descrição: Migration gerada automaticamente baseada nas mudanças detectadas

EOF
    
    echo "-- 📄 Mudanças detectadas automaticamente:" >> "$ARQUIVO_MIGRATION"
    echo "" >> "$ARQUIVO_MIGRATION"
    
    grep -A 20 "^> CREATE TABLE" temp_diferencas.txt | grep -v "^--" | sed 's/^> //' >> "$ARQUIVO_MIGRATION"
    
    grep "^> ALTER TABLE" temp_diferencas.txt | sed 's/^> //' >> "$ARQUIVO_MIGRATION"
    
    cat >> "$ARQUIVO_MIGRATION" << EOF

-- ⚠️  ATENÇÃO: Migration gerada automaticamente!
-- ✏️  Revise e edite conforme necessário antes de aplicar
-- 🔍 Verifique o arquivo temp_diferencas.txt para mais detalhes

-- Atualiza versão do banco
UPDATE configuracoes SET valor = '${PROXIMO_NUM}' WHERE chave = 'versao_db';
EOF

    echo "✅ Migration automática criada: $ARQUIVO_MIGRATION"
    echo "⚠️  IMPORTANTE: Revise o arquivo antes de aplicar!"
    
    echo ""
    echo "📄 Conteúdo da migration gerada:"
    echo "================================"
    cat "$ARQUIVO_MIGRATION"
}

criar_migration_manual() {
    echo "📝 Criando nova migration manual..."
    
    ULTIMO_NUM=$(ls database/migrations/ | grep -o '^[0-9]*' | sort -n | tail -1 2>/dev/null || echo "000")
    PROXIMO_NUM=$(printf "%03d" $((ULTIMO_NUM + 1)))
    
    read -p "📋 Nome da migration (ex: adicionar_tabela_usuarios): " NOME_MIGRATION
    
    ARQUIVO_MIGRATION="database/migrations/${PROXIMO_NUM}_${NOME_MIGRATION}.sql"
    
    cat > "$ARQUIVO_MIGRATION" << EOF
-- ==================== MIGRATION ${PROXIMO_NUM}: $(echo $NOME_MIGRATION | tr '_' ' ' | tr '[:lower:]' '[:upper:]') ====================
-- Data: $(date +%Y-%m-%d)
-- Autor: $USER
-- Descrição: Adicione aqui a descrição da migration

-- Suas mudanças aqui
-- Exemplo:
-- CREATE TABLE IF NOT EXISTS nova_tabela (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- Atualiza versão do banco
UPDATE configuracoes SET valor = '${PROXIMO_NUM}' WHERE chave = 'versao_db';
EOF

    echo "✅ Migration manual criada: $ARQUIVO_MIGRATION"
    echo "✏️  Edite o arquivo e adicione suas mudanças SQL"
}

limpar_temp() {
    echo "🧹 Limpando arquivos temporários..."
    rm -f temp_estrutura_atual.sql temp_estrutura_migrations.sql temp_diferencas.txt
    echo "✅ Arquivos temporários removidos"
}

case "$1" in
    "extrair")
        extrair_estrutura
        ;;
    "comparar")
        extrair_estrutura
        extrair_estrutura_migrations
        comparar_estruturas
        ;;
    "auto")
        extrair_estrutura
        extrair_estrutura_migrations
        if comparar_estruturas; then
            gerar_migration_automatica
        else
            echo "ℹ️  Nenhuma mudança detectada para gerar migration"
        fi
        limpar_temp
        ;;
    "manual")
        criar_migration_manual
        ;;
    "sync")
        echo "🔄 Sincronização completa..."
        extrair_estrutura
        extrair_estrutura_migrations
        if comparar_estruturas; then
            echo ""
            read -p "🤖 Gerar migration automática? (s/n): " RESPOSTA
            if [[ $RESPOSTA =~ ^[Ss]$ ]]; then
                gerar_migration_automatica
            else
                echo "💡 Use './dev-sync.sh manual' para criar migration manual"
            fi
        fi
        limpar_temp
        ;;
    "clean")
        limpar_temp
        ;;
    *)
        echo "💡 Uso: $0 {extrair|comparar|auto|manual|sync|clean}"
        echo ""
        echo "📋 Comandos disponíveis:"
        echo "  extrair  - Extrai estrutura atual do banco"
        echo "  comparar - Compara estrutura atual vs migrations"
        echo "  auto     - Gera migration automática das mudanças"
        echo "  manual   - Cria migration manual vazia"
        echo "  sync     - Sincronização completa (recomendado)"
        echo "  clean    - Remove arquivos temporários"
        echo ""
        echo "🚀 Workflow recomendado:"
        echo "  1. Faça mudanças no phpMyAdmin"
        echo "  2. Execute: ./dev-sync.sh sync"
        echo "  3. Revise a migration gerada"
        echo "  4. Commit: git add database/migrations/ && git commit"
        ;;
esac