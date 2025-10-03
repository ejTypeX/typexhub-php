-- ==================== MIGRATION 3: Alternando Tabela Usuarios ====================
-- Autor: Vitor
-- Descrição: Adicionando usr_ativo na tabela usuario

ALTER TABLE usuario
ADD COLUMN usr_ativo BOOLEAN NOT NULL DEFAULT TRUE;

-- Se o comando não funcionar, rode diretamente o comando no phpmyadmin
-- a senha do admin é "admin123"
INSERT INTO usuario (usr_nome, usr_ra, usr_email, usr_senha, usr_ativo)
VALUES ('Admin', 'admin123', 'admin@example.com', '$2y$10$lu2nyYYTJFIlxr1w0w3BlucS3vCvudSeWIaI1rJe3b44LKGyMyBgy', 1);


