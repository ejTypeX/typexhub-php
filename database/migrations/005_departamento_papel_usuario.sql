-- ==================== MIGRATION 005: DEPARTAMENTO, PAPEL E COLUNAS EM USUARIO ====================
-- Descrição: Tabelas de domínio para departamentos e papéis (UNIQUE para idempotência em seeders)
--            e vínculo opcional em usuario + flag de senha temporária.

CREATE TABLE IF NOT EXISTS departamento (
    dep_id INT AUTO_INCREMENT PRIMARY KEY,
    dep_nome VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_departamento_nome (dep_nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS papel (
    pap_id INT AUTO_INCREMENT PRIMARY KEY,
    pap_nome VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_papel_nome (pap_nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE usuario
    ADD COLUMN dep_id INT NULL AFTER usr_ativo,
    ADD COLUMN pap_id INT NULL AFTER dep_id,
    ADD COLUMN usr_senha_temporaria TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = senha temporária (is_temporary_password)' AFTER pap_id;

ALTER TABLE usuario
    ADD CONSTRAINT fk_usuario_departamento
        FOREIGN KEY (dep_id) REFERENCES departamento(dep_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT fk_usuario_papel
        FOREIGN KEY (pap_id) REFERENCES papel(pap_id)
        ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE usuario
    ADD UNIQUE KEY ux_usuario_ra (usr_ra);
