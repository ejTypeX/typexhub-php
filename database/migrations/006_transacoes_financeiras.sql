CREATE TABLE IF NOT EXISTS `transacoes` (
    `id`          INT          NOT NULL AUTO_INCREMENT,
    `descricao`   VARCHAR(255) NOT NULL,
    `tipo`        ENUM('entrada','saida') NOT NULL,
    `valor`       DECIMAL(10,2) NOT NULL,
    `data`        DATE         NOT NULL,
    `criado_em`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;