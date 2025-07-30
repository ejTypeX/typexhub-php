-- ==================== MIGRATION 001: ADICIONAR CONFIG INICIAL ====================
-- Data: 2025-07-20
-- Autor: vitorferreira
-- Descrição: Adicionar configuração inicial do sistema

-- 📄 Mudanças detectadas automaticamente:

-- 1. Primeiro criar a tabela diretorias (independente)
CREATE TABLE `diretorias` (
  `diretoria_id` int NOT NULL AUTO_INCREMENT,
  `diretoria_nome` varchar(150) NOT NULL,
  `diretoria_desc` text NOT NULL,
  `diretoria_cor` varchar(7) NOT NULL,
  `diretoria_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`diretoria_id`),
  UNIQUE KEY `diretoria_nome` (`diretoria_nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 2. Depois criar a tabela usuarios (depende de diretorias)
CREATE TABLE `usuarios` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `usuario_nome` varchar(100) NOT NULL,
  `usuario_sobrenome` varchar(100) NOT NULL,
  `usuario_email` varchar(150) NOT NULL,
  `usuario_senha` varchar(150) NOT NULL,
  `usuario_rg` varchar(100) NOT NULL,
  `usuario_cpf` varchar(100) NOT NULL,
  `usuario_telefone` varchar(100) NOT NULL,
  `usuario_nascimento` date NOT NULL,
  `usuario_cargo` varchar(100) NOT NULL,
  `diretoria_id` int NOT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `diretoria_id` (`diretoria_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`diretoria_id`) REFERENCES `diretorias` (`diretoria_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 3. Depois criar a tabela logs_sistema (depende de usuarios)
CREATE TABLE `logs_sistema` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `log_usuario` int NOT NULL,
  `log_acao` varchar(155) NOT NULL,
  `log_desc` text NOT NULL,
  `log_ocorreu_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `logs_fk_usuario` (`log_usuario`),
  CONSTRAINT `logs_fk_usuario` FOREIGN KEY (`log_usuario`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 4. Depois criar a tabela projetos (depende de usuarios e diretorias)
CREATE TABLE `projetos` (
  `projeto_id` int NOT NULL AUTO_INCREMENT,
  `projeto_nome` varchar(255) NOT NULL,
  `projeto_desc` text NOT NULL,
  `projeto_diretoria` int NOT NULL,
  `projeto_responsavel` int NOT NULL,
  `projeto_data_inicio` date NOT NULL,
  `projeto_data_fim` date NOT NULL,
  `projeto_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`projeto_id`),
  KEY `projeto_responsavel` (`projeto_responsavel`),
  KEY `projeto_diretoria` (`projeto_diretoria`),
  CONSTRAINT `projetos_ibfk_1` FOREIGN KEY (`projeto_responsavel`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `projetos_ibfk_2` FOREIGN KEY (`projeto_diretoria`) REFERENCES `diretorias` (`diretoria_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 5. Por último criar a tabela tasks (depende de usuarios, diretorias e projetos)
CREATE TABLE `tasks` (
  `tasks_id` int NOT NULL AUTO_INCREMENT,
  `tasks_titulo` varchar(255) NOT NULL,
  `tasks_desc` text NOT NULL,
  `tasks_criado_por` int NOT NULL,
  `tasks_atribuido_para` int NOT NULL,
  `tasks_diretoria` int NOT NULL,
  `tasks_projeto` int NOT NULL,
  `tasks_status` varchar(150) NOT NULL,
  PRIMARY KEY (`tasks_id`),
  KEY `tasks_fk_usuario` (`tasks_atribuido_para`),
  KEY `tasks_fk_criado_por` (`tasks_criado_por`),
  KEY `tasks_fk_diretoria` (`tasks_diretoria`),
  KEY `tasks_fk_projeto` (`tasks_projeto`),
  CONSTRAINT `tasks_fk_criado_por` FOREIGN KEY (`tasks_criado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tasks_fk_diretoria` FOREIGN KEY (`tasks_diretoria`) REFERENCES `diretorias` (`diretoria_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tasks_fk_projeto` FOREIGN KEY (`tasks_projeto`) REFERENCES `projetos` (`projeto_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tasks_fk_usuario` FOREIGN KEY (`tasks_atribuido_para`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ⚠️  ATENÇÃO: Migration gerada automaticamente!
-- ✏️  Revise e edite conforme necessário antes de aplicar
-- 🔍 Verifique o arquivo temp_diferencas.txt para mais detalhes

-- Atualiza versão do banco
UPDATE configuracoes SET valor = '001' WHERE chave = 'versao_db';