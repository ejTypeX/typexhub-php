-- ==================== MIGRATION 005: RH CADASTROS ====================
-- Data: 2025-12-07
-- Autor: bruno
-- Descrição: Migration gerada automaticamente baseada nas mudanças detectadas

-- 📄 Mudanças detectadas automaticamente:


/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cargo_usuario`
--

CREATE TABLE `cargo_usuario` (
  `cou_id` int NOT NULL AUTO_INCREMENT,
  `cou_nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cou_status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cou_cargo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cou_inicio_vigencia` date DEFAULT NULL,
  `cou_fim_vigencia` date DEFAULT NULL,
  `entidade_ent_id` int DEFAULT NULL,
  `diretoria_dir_id` int DEFAULT NULL,
  `usuario_usr_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cou_id`),
  KEY `ix_cou_entidade` (`entidade_ent_id`),
  KEY `ix_cou_diretoria` (`diretoria_dir_id`),
  KEY `ix_cou_usuario` (`usuario_usr_id`),
  CONSTRAINT `fk_cou_diretoria` FOREIGN KEY (`diretoria_dir_id`) REFERENCES `diretoria` (`dir_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cou_entidade` FOREIGN KEY (`entidade_ent_id`) REFERENCES `entidade` (`ent_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cou_usuario` FOREIGN KEY (`usuario_usr_id`) REFERENCES `usuario` (`usr_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
CREATE TABLE `configuracoes` (
  `config_id` int NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) NOT NULL,
  `valor` text NOT NULL,
  `descricao` text,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `chave` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diretoria`
--

DROP TABLE IF EXISTS `diretoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diretoria` (
  `dir_id` int NOT NULL AUTO_INCREMENT,
  `dir_nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir_descricao` text COLLATE utf8mb4_unicode_ci,
  `dir_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entidade_ent_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`dir_id`),
  KEY `ix_diretoria_entidade` (`entidade_ent_id`),
  CONSTRAINT `fk_diretoria_entidade` FOREIGN KEY (`entidade_ent_id`) REFERENCES `entidade` (`ent_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diretorias`
--

DROP TABLE IF EXISTS `diretorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diretorias` (
  `diretoria_id` int NOT NULL AUTO_INCREMENT,
  `diretoria_nome` varchar(150) NOT NULL,
  `diretoria_desc` text NOT NULL,
  `diretoria_cor` varchar(7) NOT NULL,
  `diretoria_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`diretoria_id`),
  UNIQUE KEY `diretoria_nome` (`diretoria_nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `entidade`
--

DROP TABLE IF EXISTS `entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entidade` (
  `ent_id` int NOT NULL AUTO_INCREMENT,
  `ent_nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ent_documento` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs_sistema`
--

DROP TABLE IF EXISTS `logs_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `membro`
--

DROP TABLE IF EXISTS `membro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `rg` varchar(10) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `habilidades` text,
  `instagram` text,
  `github` text,
  `whatsapp` text,
  `email` text,
  `linkedin` text,
  `admissao` date DEFAULT NULL,
  `ra` int NOT NULL,
  `periodo` int DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `coeficiente` float DEFAULT NULL,
  `celular` int DEFAULT NULL,
  `endereco` text,
  PRIMARY KEY (`id`)
CREATE TABLE `migrations_controle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `migration_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` timestamp NULL DEFAULT NULL,
  `batch_number` int NOT NULL,
  `executed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `migration_name` (`migration_name`),
  KEY `idx_migration_name` (`migration_name`),
  KEY `idx_batch` (`batch_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projetos`
--

DROP TABLE IF EXISTS `projetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reconhecimento`
--

CREATE TABLE `reconhecimento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `membro_id` int DEFAULT NULL,
  `feito` text,
  `reconhecimento` text,
  `diretor_id` int DEFAULT NULL,
  `data_reconhecimento` date DEFAULT NULL,
  `created_at` date DEFAULT (now()),
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reconhecimento_membro` (`membro_id`),
  KEY `fk_reconhecimento_diretor` (`diretor_id`),
  CONSTRAINT `fk_reconhecimento_diretor` FOREIGN KEY (`diretor_id`) REFERENCES `diretoria` (`dir_id`),
  CONSTRAINT `fk_reconhecimento_membro` FOREIGN KEY (`membro_id`) REFERENCES `membro` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reuniao`
--

CREATE TABLE `reuniao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `motivo` varchar(255) NOT NULL,
  `data_reuniao` date NOT NULL,
  `local_reuniao` varchar(255) NOT NULL,
  `horas` varchar(50) NOT NULL,
  `descricao` text,
  `status_reuniao` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE `usuario` (
  `usr_id` int NOT NULL AUTO_INCREMENT,
  `usr_nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_documento` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_ra` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role_usr_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usr_ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `ux_usuario_email` (`usr_email`),
  KEY `fk_usuario_role` (`user_role_usr_id`),
  CONSTRAINT `fk_usuario_role` FOREIGN KEY (`user_role_usr_id`) REFERENCES `cargo_usuario` (`cou_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
CREATE TABLE `usuarios` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `usuario_ra` int DEFAULT NULL,
  `usuario_nome` varchar(100) NOT NULL,
  `usuario_sobrenome` varchar(100) NOT NULL,
  `usuario_email` varchar(150) NOT NULL,
  `usuario_senha` varchar(150) NOT NULL,
  `usuario_rg` varchar(100) NOT NULL,
  `usuario_cpf` varchar(100) NOT NULL,
  `usuario_telefone` varchar(100) NOT NULL,
  `usuario_nascimento` date NOT NULL,
  `usuario_cargo` varchar(100) NOT NULL,
  `diretoria_id` int DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `diretoria_id` (`diretoria_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`diretoria_id`) REFERENCES `diretorias` (`diretoria_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

CREATE TABLE `advertencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `membro_id` int DEFAULT NULL,
  `diretor_id` int DEFAULT NULL,
  `motivo` text,
  `acao_corretiva` text,
  `data` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `membro_id` (`membro_id`),
  KEY `diretor_id` (`diretor_id`),
  CONSTRAINT `advertencias_ibfk_1` FOREIGN KEY (`membro_id`) REFERENCES `membro` (`id`),
  CONSTRAINT `advertencias_ibfk_2` FOREIGN KEY (`diretor_id`) REFERENCES `diretoria` (`dir_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- ⚠️  ATENÇÃO: Migration gerada automaticamente!
-- ✏️  Revise e edite conforme necessário antes de aplicar
-- 🔍 Verifique o arquivo temp_diferencas.txt para mais detalhes

-- Atualiza versão do banco
UPDATE configuracoes SET valor = '005' WHERE chave = 'versao_db';
