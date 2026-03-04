\c policia;

-- Tabela policia.unidade
INSERT INTO policia.unidade(id, nome, status)
    VALUES (9,'DIRETORIA DE INFORMÁTICA, MANUTENÇÃO E ESTATÍSTICA', 1);

-- Tabela usuario
INSERT INTO usuario(id, nome, email, senha, excluido, primeiro_acesso, cpf, nascimento, remember_token, unidade, status, fk_usuario_correicao, senha2, diretor, fk_unidade, updated_at)
VALUES (1,'DIME', 'dimepolicia@gmail.com','d161f8ff0e5c858f70409616359c61c69eee8153', false, false, '39531406200', '2010-10-10', null, null, 1, null, '$2y$10$roe3HQJbCNjlTD1X5zoKwO44iisVQU6uBw/.rEgqH4ryfDKiiueGi', null, 9, null),
       (26,'Philipe Barra', 'philipe.campos@policiacivil.pa.gov.br', 'd161f8ff0e5c858f70409616359c61c69eee8153', false, false, '39531406200', '2010-10-10', null, null, 1, null, '$2y$10$roe3HQJbCNjlTD1X5zoKwO44iisVQU6uBw/.rEgqH4ryfDKiiueGi', null, 9, null);

-- Tabela status
INSERT INTO seguranca.status(id, nome, descricao)
VALUES (1, 'Ativo', 'Ativo'),
       (0, 'Inativo', 'Inativo'),
       (2, 'Não Publicado', 'Não Publicado');

-- Tabela sistemas
INSERT INTO seguranca.sistema(id, nome, link, imagem, descricao, fk_status)
VALUES (35, 'Skeleton', null, null, 'Arquitetura modelo para sistemas', 1);

-- Tabela usuario_sistema
INSERT INTO usuario_sistema(sistema_id, usuario_id, status, fk_usuario_cadastro, fk_usuario_edicao)
VALUES (35, 26, 1, 1, 1);

-- Tabela risp
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (1, '1a RISP - Regiao da Capital - Belem, Distritos e Ilhas', 'Região da Capital', '1a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (2, '2a RISP - Regiao Metropolitana', 'Região Metropolitana de Belém', '2a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (3, '3a RISP - Regiao do Guama', 'Zona do Salgado', '3a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (4, '4a RISP - Regiao do Tocantins', 'Regional do Baixo Tocantins', '4a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (5, '5a RISP - Regiao do Marajo Oriental', 'Regional dos Campos do Marajó', '5a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (6, '6a RISP - Regiao do Caete', 'Zona Bragantina', '6a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (7, '7a RISP - Regiao do Capim', 'Regional da Zona Guajarina', '7a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (8, '8a RISP - Regiao do Marajo Ocidental', 'Regional das Ilhas', '8a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (9, '9a RISP - Regiao do Lago do Tucurui', 'Regional do Lago de Tucuruí', '9a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (10, '10a RISP - Regiao de Carajas', 'Sudeste do Pará', '10a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (11, '11a RISP - Regiao do Xingu', 'Regional do Xingú', '11a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (12, '12a RISP - Regiao do Baixo Amazonas', 'Regional do Baixo e Médio Amazonas', '12a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (13, '13a RISP - Regiao do Araguaia', 'Regional do Araguaia Paraense', '13a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (15, '15a RISP - Regiao do Tapajos', 'Regional do Tapajós', '15a RISP');
INSERT INTO policia.risp (id, nome, subnome, sigla) VALUES (14, '14a RISP - Regiao do Alto Xingu', 'Regional do Alto Xingu', '14a RISP');

-- Tabela uf
INSERT INTO policia.uf (id, uf, sigla) VALUES (1, 'Acre', 'AC');
INSERT INTO policia.uf (id, uf, sigla) VALUES (2, 'Alagoas', 'AL');
INSERT INTO policia.uf (id, uf, sigla) VALUES (3, 'Amazonas', 'AM');
INSERT INTO policia.uf (id, uf, sigla) VALUES (4, 'Amapá', 'AP');
INSERT INTO policia.uf (id, uf, sigla) VALUES (5, 'Bahia', 'BA');
INSERT INTO policia.uf (id, uf, sigla) VALUES (6, 'Ceará', 'CE');
INSERT INTO policia.uf (id, uf, sigla) VALUES (7, 'Distrito Federal', 'DF');
INSERT INTO policia.uf (id, uf, sigla) VALUES (8, 'Espírito Santo', 'ES');
INSERT INTO policia.uf (id, uf, sigla) VALUES (9, 'Goiás', 'GO');
INSERT INTO policia.uf (id, uf, sigla) VALUES (10, 'Maranhão', 'MA');
INSERT INTO policia.uf (id, uf, sigla) VALUES (11, 'Minas Gerais', 'MG');
INSERT INTO policia.uf (id, uf, sigla) VALUES (12, 'Mato Grosso do Sul', 'MS');
INSERT INTO policia.uf (id, uf, sigla) VALUES (13, 'Mato Grosso', 'MT');
INSERT INTO policia.uf (id, uf, sigla) VALUES (14, 'Pará', 'PA');
INSERT INTO policia.uf (id, uf, sigla) VALUES (15, 'Paraíba', 'PB');
INSERT INTO policia.uf (id, uf, sigla) VALUES (16, 'Pernambuco', 'PE');
INSERT INTO policia.uf (id, uf, sigla) VALUES (17, 'Piauí', 'PI');
INSERT INTO policia.uf (id, uf, sigla) VALUES (18, 'Paraná', 'PR');
INSERT INTO policia.uf (id, uf, sigla) VALUES (19, 'Rio de Janeiro', 'RJ');
INSERT INTO policia.uf (id, uf, sigla) VALUES (20, 'Rio Grande do Norte', 'RN');
INSERT INTO policia.uf (id, uf, sigla) VALUES (21, 'Rondônia', 'RO');
INSERT INTO policia.uf (id, uf, sigla) VALUES (22, 'Roraima', 'RR');
INSERT INTO policia.uf (id, uf, sigla) VALUES (23, 'Rio Grande do Sul', 'RS');
INSERT INTO policia.uf (id, uf, sigla) VALUES (24, 'Santa Catarina', 'SC');
INSERT INTO policia.uf (id, uf, sigla) VALUES (25, 'Sergipe', 'SE');
INSERT INTO policia.uf (id, uf, sigla) VALUES (26, 'São Paulo', 'SP');
INSERT INTO policia.uf (id, uf, sigla) VALUES (27, 'Tocantins', 'TO');

-- Tabela cidade
INSERT INTO policia.cidade (id, nome, nome_det, cep, uf, situacao, codigo_ibge, codigo_sefa) VALUES (4565, 'Belém', 'Belém', null, 'PA', 1, '1501402', 4278);
