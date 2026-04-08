-- adiciona campo RA na tabela usuarios
ALTER TABLE usuarios
ADD usuario_ra INT AFTER usuario_id;


-- diretoria pode ser nulo
ALTER TABLE usuarios
MODIFY COLUMN diretoria_id INT NULL;