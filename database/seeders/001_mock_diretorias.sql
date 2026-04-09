-- ==================== SEEDER 1: MOCK_DIRETORIAS ====================
-- Data: 2025-07-31 21:44:06
-- Autor: Kurt
-- Descrição: Mock diretorias

-- Exemplo de INSERT:
-- INSERT INTO tabela (coluna1, coluna2) VALUES ('valor1', 'valor2');
-- INSERT INTO tabela (coluna1, coluna2) VALUES ('valor3', 'valor4');

INSERT INTO `diretorias` (`diretoria_nome`,     `diretoria_desc`,                                             `diretoria_cor`, `diretoria_status`) VALUES
('Financeiro',      'Responsável pela gestão financeira, planejamento orçamentário e controle de custos.',    '#1E90FF',         1),
('Recursos Humanos','Gerencia recrutamento, desenvolvimento de colaboradores e políticas de benefícios.',   '#32CD32',         1),
('Marketing',       'Planeja campanhas, comunicação institucional e estratégias de branding.',               '#FF8C00',         1),
('Projetos',      'Desenvolvimento de sistemas, infraestrutura de TI e suporte a usuários.',               '#8A2BE2',         1),
('Infraestrutura',  'Gerencia a infraestrutura física e tecnológica da empresa.',                     '#490037ff',         0),
('Presidência',     'Coordenação das atividades da empresa e representação institucional.',                     '#ff0000ff',         1);
