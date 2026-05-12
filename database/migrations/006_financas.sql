CREATE TABLE IF NOT EXISTS `contas_a_receber` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `observacao` VARCHAR(255) NOT NULL,
  `valor_principal` DECIMAL(10,2) NOT NULL,
  `data_emissao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `data_vencimento` DATE NOT NULL,
  `status` ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente',
  `id_cliente` VARCHAR(100) DEFAULT NULL,
);

CREATE TABLE IF NOT EXISTS `contas_a_pagar` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `observacao` VARCHAR(255) NOT NULL,
  `valor_principal` DECIMAL(10,2) NOT NULL,
  `data_emissao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `data_vencimento` DATE NOT NULL,
  `status` ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente',
  `id_cliente` VARCHAR(100) DEFAULT NULL,
);