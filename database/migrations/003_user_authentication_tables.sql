-- ==================== MIGRATION 3: USER_AUTHENTICATION_TABLES ====================
-- Data: 2025-09-02 03:46:08
-- Autor: Kurt
-- Descrição: User authentication tables

-- 1) Entidade
CREATE TABLE entidade (
    ent_id INT AUTO_INCREMENT PRIMARY KEY,
    ent_nome VARCHAR(255) NOT NULL,
    ent_documento VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Usuário 
CREATE TABLE usuario (
    usr_id INT AUTO_INCREMENT PRIMARY KEY,
    usr_nome VARCHAR(255) NOT NULL,
    usr_documento VARCHAR(25) DEFAULT NULL,
    usr_email VARCHAR(120) NOT NULL,
    usr_senha VARCHAR(255) NOT NULL,
    usr_ra VARCHAR(255) DEFAULT NULL,
    usr_foto VARCHAR(255) DEFAULT NULL,
    user_role_usr_id INT DEFAULT NULL, -- referência para CargoUsuario.cou_id (adicionada depois)
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_usuario_email (usr_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3) Diretoria
CREATE TABLE diretoria (
    dir_id INT AUTO_INCREMENT PRIMARY KEY,
    dir_nome VARCHAR(255) NOT NULL,
    dir_descricao TEXT DEFAULT NULL,
    dir_status VARCHAR(50) DEFAULT NULL,
    entidade_ent_id INT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_diretoria_entidade (entidade_ent_id),
    CONSTRAINT fk_diretoria_entidade FOREIGN KEY (entidade_ent_id)
        REFERENCES entidade(ent_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4) CargoUsuário
CREATE TABLE cargo_usuario (
    cou_id INT AUTO_INCREMENT PRIMARY KEY,
    cou_nome VARCHAR(255) NOT NULL,
    cou_status VARCHAR(100) DEFAULT NULL,
    cou_cargo VARCHAR(100) DEFAULT NULL,
    cou_inicio_vigencia DATE DEFAULT NULL,
    cou_fim_vigencia DATE DEFAULT NULL,
    entidade_ent_id INT DEFAULT NULL,
    diretoria_dir_id INT DEFAULT NULL,
    usuario_usr_id INT DEFAULT NULL, -- quem ocupa esse cargo
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_cou_entidade (entidade_ent_id),
    INDEX ix_cou_diretoria (diretoria_dir_id),
    INDEX ix_cou_usuario (usuario_usr_id),
    CONSTRAINT fk_cou_entidade FOREIGN KEY (entidade_ent_id)
        REFERENCES entidade(ent_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    CONSTRAINT fk_cou_diretoria FOREIGN KEY (diretoria_dir_id)
        REFERENCES diretoria(dir_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    CONSTRAINT fk_cou_usuario FOREIGN KEY (usuario_usr_id)
        REFERENCES usuario(usr_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE usuario
    ADD CONSTRAINT fk_usuario_role
    FOREIGN KEY (user_role_usr_id)
    REFERENCES cargo_usuario(cou_id)
    ON DELETE SET NULL
    ON UPDATE CASCADE;