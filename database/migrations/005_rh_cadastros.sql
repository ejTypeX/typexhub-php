-- ==================== MIGRATION 005: RH CADASTROS ====================
-- Data: 2025-12-07
-- Autor: bruno
-- Descrição: Migration gerada automaticamente baseada nas mudanças detectadas

-- 📄 Mudanças detectadas automaticamente:


/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cargo_usuario`
--


/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diretoria`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;


--
-- Table structure for table `diretorias`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;


--
-- Table structure for table `entidade`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;


--
-- Table structure for table `logs_sistema`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;


--
-- Table structure for table `membro`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `membro` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE IF NOT EXISTS `migrations_controle` (
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;


--
-- Table structure for table `reconhecimento`
--

CREATE TABLE IF NOT EXISTS `reconhecimento` (
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

CREATE TABLE IF NOT EXISTS `reuniao` (
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;


/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`



CREATE TABLE IF NOT EXISTS `advertencias` (
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
