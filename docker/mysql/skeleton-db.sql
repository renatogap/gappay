
--
-- Estrutura para tabela `acesso`
--

CREATE TABLE `acesso` (
  `id` int(11) NOT NULL,
  `fk_usuario` int(11) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `login` timestamp(6) NULL DEFAULT NULL,
  `logout` timestamp(6) NULL DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ultimo_acesso` timestamp(6) NULL DEFAULT NULL,
  `session_id` char(26) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cardapio`
--

CREATE TABLE `cardapio` (
  `id` int(11) NOT NULL,
  `fk_tipo_cardapio` varchar(255) DEFAULT NULL,
  `fk_categoria` int(11) DEFAULT NULL,
  `fk_produto` int(11) DEFAULT NULL,
  `nome_item` varchar(255) DEFAULT NULL,
  `detalhe_item` varchar(255) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `unid` decimal(10,3) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cozinha` int(11) DEFAULT '1',
  `nome_item_en` varchar(180) DEFAULT NULL,
  `detalhe_item_en` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cardapio_categoria`
--

CREATE TABLE `cardapio_categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `fk_tipo_cardapio` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nome_en` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cardapio_foto`
--

CREATE TABLE `cardapio_foto` (
  `id` int(11) NOT NULL,
  `fk_cardapio` int(11) DEFAULT NULL,
  `foto` longblob,
  `thumbnail` longblob,
  `nome` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cardapio_tipo`
--

CREATE TABLE `cardapio_tipo` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `foto` longblob,
  `thumbnail` longblob,
  `nome_foto` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `apelido` varchar(255) DEFAULT NULL,
  `estoque` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cartao`
--

CREATE TABLE `cartao` (
  `id` int(11) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `data` date NOT NULL,
  `fk_situacao` int(11) NOT NULL,
  `cartao_gerado` int(11) DEFAULT '0',
  `dt_geracao_cartao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cartao_cliente`
--

CREATE TABLE `cartao_cliente` (
  `id` int(11) NOT NULL,
  `fk_cartao` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `fk_cliente_titular` int(11) DEFAULT NULL,
  `fk_dependente` int(11) DEFAULT NULL,
  `fk_tipo_cliente` int(11) NOT NULL,
  `fk_tipo_pagamento` int(11) DEFAULT NULL,
  `valor_atual` decimal(10,2) NOT NULL,
  `observacao` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `valor_cartao` decimal(10,2) DEFAULT NULL,
  `fk_cartao_transferido` int(11) DEFAULT NULL,
  `devolvido` char(1) DEFAULT 'N',
  `valor_devolvido` decimal(10,2) DEFAULT NULL,
  `dt_devolucao` timestamp NULL DEFAULT NULL,
  `notificacao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `fk_escola` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `fk_tipo_cliente` int(11) DEFAULT NULL,
  `dia_vencimento` char(2) DEFAULT NULL,
  `fk_cartao` int(11) DEFAULT NULL,
  `fk_cartao_cliente` int(11) DEFAULT NULL,
  `observacao` text,
  `status` int(11) NOT NULL DEFAULT '1',
  `fk_usuario` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_usuario_alt` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cobranca`
--

CREATE TABLE `cobranca` (
  `id` int(11) NOT NULL,
  `fk_cliente` int(11) NOT NULL,
  `mes_ref` char(2) NOT NULL,
  `ano_ref` char(4) NOT NULL,
  `dependentes` int(11) NOT NULL,
  `valor_cliente` decimal(10,2) NOT NULL,
  `valor_dependentes` decimal(10,2) DEFAULT '0.00',
  `valor_desconto` decimal(10,2) NOT NULL,
  `valor_total_cobranca` decimal(10,2) NOT NULL,
  `valor_pago` decimal(10,2) NOT NULL,
  `dt_vencimento` date NOT NULL,
  `dt_pagamento` date NOT NULL,
  `fk_forma_pagamento` int(11) NOT NULL,
  `observacao` text,
  `fk_usuario_cad` int(11) DEFAULT NULL,
  `fk_usuario_alt` int(11) DEFAULT NULL,
  `fk_usuario_del` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dependente`
--

CREATE TABLE `dependente` (
  `id` int(11) NOT NULL,
  `fk_cliente` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `fk_grau_parentesco` int(11) DEFAULT NULL,
  `fk_cartao_cliente` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fk_usuario_alt` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada_credito`
--

CREATE TABLE `entrada_credito` (
  `id` int(11) NOT NULL,
  `fk_cartao_cliente` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `fk_tipo_pagamento` int(11) NOT NULL,
  `observacao` longtext,
  `data` timestamp NULL DEFAULT NULL,
  `fk_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escola`
--

CREATE TABLE `escola` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `tipo_movimento` char(1) DEFAULT NULL,
  `fk_tipo_cardapio` int(11) DEFAULT NULL,
  `fk_item_cardapio` int(11) DEFAULT NULL,
  `qtd_atual` int(11) DEFAULT NULL,
  `estoque_minimo` int(11) DEFAULT NULL,
  `estoque_maximo` int(11) DEFAULT NULL,
  `fk_tipo_unidade_medida` int(11) DEFAULT NULL COMMENT '1-UNIDADE | 2-GARRAFA | 3-QUILO',
  `qtd_dose_por_garrafa` varchar(5) DEFAULT NULL,
  `dt_ultima_atualizacao` timestamp NULL DEFAULT NULL,
  `fk_usuario_cad` int(11) DEFAULT NULL,
  `fk_usuario_alt` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque_entrada`
--

CREATE TABLE `estoque_entrada` (
  `id` int(11) NOT NULL,
  `fk_tipo_cardapio` int(11) DEFAULT NULL,
  `fk_item_cardapio` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL,
  `observacao` text,
  `fk_usuario_cad` int(11) DEFAULT NULL,
  `fk_usuario_alt` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque_saida`
--

CREATE TABLE `estoque_saida` (
  `id` int(11) NOT NULL,
  `fk_tipo_cardapio` int(11) DEFAULT NULL,
  `fk_item_cardapio` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `fk_pedido_item` int(11) DEFAULT NULL,
  `observacao` text,
  `fk_usuario` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grau_parentesco`
--

CREATE TABLE `grau_parentesco` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `fk_cartao` int(11) NOT NULL,
  `fk_cartao_cliente` int(11) NOT NULL,
  `mesa` int(11) DEFAULT NULL,
  `taxa_servico` decimal(10,2) DEFAULT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `dt_pedido` timestamp NULL DEFAULT NULL,
  `dt_pronto` timestamp NULL DEFAULT NULL,
  `dt_entrega` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL,
  `fk_usuario` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_item`
--

CREATE TABLE `pedido_item` (
  `id` int(11) NOT NULL,
  `fk_pedido` int(11) NOT NULL,
  `fk_item_cardapio` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `quantidade` decimal(10,3) NOT NULL,
  `observacao` text,
  `status` int(11) NOT NULL COMMENT '1-SOLICITADO | 2-PRONTO | 3-CANCELADO',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dt_pronto` timestamp NULL DEFAULT NULL,
  `dt_entregue` timestamp NULL DEFAULT NULL,
  `visto_pela_cozinha` int(11) DEFAULT NULL,
  `visto_pelo_promotor` int(11) DEFAULT NULL,
  `fk_usuario_cancelamento` int(11) DEFAULT NULL,
  `dt_cancelamento` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `saida_credito`
--

CREATE TABLE `saida_credito` (
  `id` int(11) NOT NULL,
  `fk_cartao_cliente` int(11) DEFAULT NULL,
  `fk_pedido` int(11) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` timestamp NULL DEFAULT NULL,
  `observacao` text,
  `fk_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `seg_acao`
--

CREATE TABLE `seg_acao` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL DEFAULT 'get',
  `descricao` varchar(255) DEFAULT NULL,
  `destaque` tinyint(1) NOT NULL DEFAULT '0',
  `nome_amigavel` varchar(255) DEFAULT NULL,
  `obrigatorio` tinyint(1) NOT NULL DEFAULT '0',
  `grupo` varchar(255) DEFAULT NULL,
  `log_acesso` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `seg_dependencia`
--

CREATE TABLE `seg_dependencia` (
  `id` int(10) UNSIGNED NOT NULL,
  `acao_atual_id` int(10) UNSIGNED NOT NULL,
  `acao_dependencia_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `seg_grupo`
--

CREATE TABLE `seg_grupo` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `perfil_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `seg_historico`
--

CREATE TABLE `seg_historico` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `acao_id` int(10) UNSIGNED NOT NULL,
  `antes` longtext,
  `depois` longtext,
  `dt_historico` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `seg_menu`
--

CREATE TABLE `seg_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `acao_id` int(10) UNSIGNED DEFAULT NULL,
  `pai` int(10) UNSIGNED DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `icone` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ordem` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `seg_perfil`
--

CREATE TABLE `seg_perfil` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------

--
-- Estrutura para tabela `seg_permissao`
--

CREATE TABLE `seg_permissao` (
  `id` int(10) UNSIGNED NOT NULL,
  `acao_id` int(10) UNSIGNED NOT NULL,
  `perfil_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------

--
-- Estrutura para tabela `sistema`
--

CREATE TABLE `sistema` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------

--
-- Estrutura para tabela `situacao_cartao`
--

CREATE TABLE `situacao_cartao` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



-- --------------------------------------------------------

--
-- Estrutura para tabela `situacao_pedido`
--

CREATE TABLE `situacao_pedido` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_cliente`
--

CREATE TABLE `tipo_cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_pagamento`
--

CREATE TABLE `tipo_pagamento` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_unidade_medida`
--

CREATE TABLE `tipo_unidade_medida` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cpf` char(11) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `senha` varchar(150) DEFAULT NULL,
  `senha2` varchar(150) DEFAULT NULL,
  `dt_cadastro` timestamp(6) NULL DEFAULT NULL,
  `excluido` tinyint(4) DEFAULT NULL,
  `primeiro_acesso` tinyint(4) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_sistema`
--

CREATE TABLE `usuario_sistema` (
  `id` int(11) NOT NULL,
  `sistema_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `ultimo_acesso` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_tipo_cardapio`
--

CREATE TABLE `usuario_tipo_cardapio` (
  `id` int(11) NOT NULL,
  `fk_usuario` int(11) DEFAULT NULL,
  `fk_tipo_cardapio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acesso`
--
ALTER TABLE `acesso`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `cardapio`
--
ALTER TABLE `cardapio`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_produto` (`fk_produto`);

--
-- Índices de tabela `cardapio_categoria`
--
ALTER TABLE `cardapio_categoria`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `cardapio_foto`
--
ALTER TABLE `cardapio_foto`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `cardapio_tipo`
--
ALTER TABLE `cardapio_tipo`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `cartao`
--
ALTER TABLE `cartao`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_situacao` (`fk_situacao`) USING BTREE;

--
-- Índices de tabela `cartao_cliente`
--
ALTER TABLE `cartao_cliente`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_cartao` (`fk_cartao`) USING BTREE,
  ADD KEY `fk_tipo_cliente` (`fk_tipo_cliente`) USING BTREE,
  ADD KEY `fk_tipo_pagamento` (`fk_tipo_pagamento`) USING BTREE,
  ADD KEY `fk_usuario` (`fk_usuario`) USING BTREE,
  ADD KEY `cartao_cliente_ibfk_5` (`fk_cliente_titular`),
  ADD KEY `fk_dependente` (`fk_dependente`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `fk_escola` (`fk_escola`),
  ADD KEY `fk_cartao_cliente` (`fk_cartao_cliente`),
  ADD KEY `fk_cartao` (`fk_cartao`),
  ADD KEY `fk_tipo_cliente` (`fk_tipo_cliente`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `cobranca`
--
ALTER TABLE `cobranca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_index` (`fk_cliente`,`mes_ref`,`ano_ref`),
  ADD KEY `fk_forma_pagamento` (`fk_forma_pagamento`),
  ADD KEY `fk_usuario_cad` (`fk_usuario_cad`),
  ADD KEY `fk_usuario_alt` (`fk_usuario_alt`),
  ADD KEY `fk_usuario_del` (`fk_usuario_del`);

--
-- Índices de tabela `dependente`
--
ALTER TABLE `dependente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `entrada_credito`
--
ALTER TABLE `entrada_credito`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_cartao_cliente` (`fk_cartao_cliente`) USING BTREE,
  ADD KEY `fk_tipo_pagamento` (`fk_tipo_pagamento`) USING BTREE;

--
-- Índices de tabela `escola`
--
ALTER TABLE `escola`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_tipo_cardapio` (`fk_tipo_cardapio`) USING BTREE,
  ADD KEY `fk_item_cardapio` (`fk_item_cardapio`) USING BTREE;

--
-- Índices de tabela `estoque_entrada`
--
ALTER TABLE `estoque_entrada`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_tipo_cardapio` (`fk_tipo_cardapio`) USING BTREE,
  ADD KEY `fk_item_cardapio` (`fk_item_cardapio`) USING BTREE;

--
-- Índices de tabela `estoque_saida`
--
ALTER TABLE `estoque_saida`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_tipo_cardapio` (`fk_tipo_cardapio`) USING BTREE,
  ADD KEY `fk_item_cardapio` (`fk_item_cardapio`) USING BTREE,
  ADD KEY `fk_pedido_item` (`fk_pedido_item`) USING BTREE;

--
-- Índices de tabela `grau_parentesco`
--
ALTER TABLE `grau_parentesco`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_cartao` (`fk_cartao`) USING BTREE,
  ADD KEY `fk_cartao_cliente` (`fk_cartao_cliente`) USING BTREE;

--
-- Índices de tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_pedido` (`fk_pedido`) USING BTREE,
  ADD KEY `fk_item_cardapio` (`fk_item_cardapio`) USING BTREE;

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `saida_credito`
--
ALTER TABLE `saida_credito`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_cartao_cliente` (`fk_cartao_cliente`) USING BTREE,
  ADD KEY `fk_pedido` (`fk_pedido`) USING BTREE;

--
-- Índices de tabela `seg_acao`
--
ALTER TABLE `seg_acao`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `seg_acao_nome_method_index` (`nome`,`method`) USING BTREE;

--
-- Índices de tabela `seg_dependencia`
--
ALTER TABLE `seg_dependencia`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `seg_dependencia_acao_atual_id_acao_dependencia_id_unique` (`acao_atual_id`,`acao_dependencia_id`) USING BTREE,
  ADD KEY `seg_dependencia_acao_dependencia_id_foreign` (`acao_dependencia_id`) USING BTREE;

--
-- Índices de tabela `seg_grupo`
--
ALTER TABLE `seg_grupo`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `seg_grupo_perfil_id_foreign` (`perfil_id`) USING BTREE;

--
-- Índices de tabela `seg_historico`
--
ALTER TABLE `seg_historico`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `seg_historico_acao_id_foreign` (`acao_id`) USING BTREE;

--
-- Índices de tabela `seg_menu`
--
ALTER TABLE `seg_menu`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `seg_menu_acao_id_foreign` (`acao_id`) USING BTREE,
  ADD KEY `seg_menu_pai_foreign` (`pai`) USING BTREE;

--
-- Índices de tabela `seg_perfil`
--
ALTER TABLE `seg_perfil`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `seg_permissao`
--
ALTER TABLE `seg_permissao`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `seg_permissao_acao_id_perfil_id_unique` (`acao_id`,`perfil_id`) USING BTREE,
  ADD KEY `seg_permissao_perfil_id_foreign` (`perfil_id`) USING BTREE;

--
-- Índices de tabela `sistema`
--
ALTER TABLE `sistema`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `situacao_cartao`
--
ALTER TABLE `situacao_cartao`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `situacao_pedido`
--
ALTER TABLE `situacao_pedido`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `tipo_cliente`
--
ALTER TABLE `tipo_cliente`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `tipo_pagamento`
--
ALTER TABLE `tipo_pagamento`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `tipo_unidade_medida`
--
ALTER TABLE `tipo_unidade_medida`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `usuario_sistema`
--
ALTER TABLE `usuario_sistema`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `sistema_id` (`sistema_id`) USING BTREE,
  ADD KEY `usuario_id` (`usuario_id`) USING BTREE;

--
-- Índices de tabela `usuario_tipo_cardapio`
--
ALTER TABLE `usuario_tipo_cardapio`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acesso`
--
ALTER TABLE `acesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83580;

--
-- AUTO_INCREMENT de tabela `cardapio`
--
ALTER TABLE `cardapio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1857;

--
-- AUTO_INCREMENT de tabela `cardapio_categoria`
--
ALTER TABLE `cardapio_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT de tabela `cardapio_foto`
--
ALTER TABLE `cardapio_foto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `cardapio_tipo`
--
ALTER TABLE `cardapio_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cartao`
--
ALTER TABLE `cartao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65069;

--
-- AUTO_INCREMENT de tabela `cartao_cliente`
--
ALTER TABLE `cartao_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112351;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cobranca`
--
ALTER TABLE `cobranca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `dependente`
--
ALTER TABLE `dependente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `entrada_credito`
--
ALTER TABLE `entrada_credito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=428343;

--
-- AUTO_INCREMENT de tabela `escola`
--
ALTER TABLE `escola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque_entrada`
--
ALTER TABLE `estoque_entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque_saida`
--
ALTER TABLE `estoque_saida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grau_parentesco`
--
ALTER TABLE `grau_parentesco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=619791;

--
-- AUTO_INCREMENT de tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=982370;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2507;

--
-- AUTO_INCREMENT de tabela `saida_credito`
--
ALTER TABLE `saida_credito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=813105;

--
-- AUTO_INCREMENT de tabela `seg_acao`
--
ALTER TABLE `seg_acao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT de tabela `seg_dependencia`
--
ALTER TABLE `seg_dependencia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de tabela `seg_grupo`
--
ALTER TABLE `seg_grupo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=471;

--
-- AUTO_INCREMENT de tabela `seg_historico`
--
ALTER TABLE `seg_historico`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86582;

--
-- AUTO_INCREMENT de tabela `seg_menu`
--
ALTER TABLE `seg_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `seg_perfil`
--
ALTER TABLE `seg_perfil`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `seg_permissao`
--
ALTER TABLE `seg_permissao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=538;

--
-- AUTO_INCREMENT de tabela `sistema`
--
ALTER TABLE `sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `situacao_cartao`
--
ALTER TABLE `situacao_cartao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `situacao_pedido`
--
ALTER TABLE `situacao_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tipo_cliente`
--
ALTER TABLE `tipo_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tipo_pagamento`
--
ALTER TABLE `tipo_pagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tipo_unidade_medida`
--
ALTER TABLE `tipo_unidade_medida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10160;

--
-- AUTO_INCREMENT de tabela `usuario_sistema`
--
ALTER TABLE `usuario_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT de tabela `usuario_tipo_cardapio`
--
ALTER TABLE `usuario_tipo_cardapio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1025;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cardapio`
--
ALTER TABLE `cardapio`
  ADD CONSTRAINT `cardapio_ibfk_1` FOREIGN KEY (`fk_produto`) REFERENCES `produto` (`id`);

--
-- Restrições para tabelas `cartao`
--
ALTER TABLE `cartao`
  ADD CONSTRAINT `cartao_ibfk_1` FOREIGN KEY (`fk_situacao`) REFERENCES `situacao_cartao` (`id`);

--
-- Restrições para tabelas `cartao_cliente`
--
ALTER TABLE `cartao_cliente`
  ADD CONSTRAINT `cartao_cliente_ibfk_1` FOREIGN KEY (`fk_cartao`) REFERENCES `cartao` (`id`),
  ADD CONSTRAINT `cartao_cliente_ibfk_2` FOREIGN KEY (`fk_tipo_cliente`) REFERENCES `tipo_cliente` (`id`),
  ADD CONSTRAINT `cartao_cliente_ibfk_3` FOREIGN KEY (`fk_tipo_pagamento`) REFERENCES `tipo_pagamento` (`id`),
  ADD CONSTRAINT `cartao_cliente_ibfk_4` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `cartao_cliente_ibfk_5` FOREIGN KEY (`fk_cliente_titular`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `cartao_cliente_ibfk_6` FOREIGN KEY (`fk_dependente`) REFERENCES `dependente` (`id`);

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`fk_escola`) REFERENCES `escola` (`id`),
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`fk_cartao_cliente`) REFERENCES `cartao_cliente` (`id`),
  ADD CONSTRAINT `cliente_ibfk_3` FOREIGN KEY (`fk_cartao`) REFERENCES `cartao` (`id`),
  ADD CONSTRAINT `cliente_ibfk_4` FOREIGN KEY (`fk_tipo_cliente`) REFERENCES `tipo_cliente` (`id`),
  ADD CONSTRAINT `cliente_ibfk_5` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `cobranca`
--
ALTER TABLE `cobranca`
  ADD CONSTRAINT `cobranca_ibfk_1` FOREIGN KEY (`fk_cliente`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `cobranca_ibfk_2` FOREIGN KEY (`fk_forma_pagamento`) REFERENCES `tipo_pagamento` (`id`),
  ADD CONSTRAINT `cobranca_ibfk_3` FOREIGN KEY (`fk_usuario_cad`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `cobranca_ibfk_4` FOREIGN KEY (`fk_usuario_alt`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `cobranca_ibfk_5` FOREIGN KEY (`fk_usuario_del`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `entrada_credito`
--
ALTER TABLE `entrada_credito`
  ADD CONSTRAINT `entrada_credito_ibfk_1` FOREIGN KEY (`fk_cartao_cliente`) REFERENCES `cartao_cliente` (`id`),
  ADD CONSTRAINT `entrada_credito_ibfk_2` FOREIGN KEY (`fk_tipo_pagamento`) REFERENCES `tipo_pagamento` (`id`);

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`fk_tipo_cardapio`) REFERENCES `cardapio_tipo` (`id`),
  ADD CONSTRAINT `estoque_ibfk_2` FOREIGN KEY (`fk_item_cardapio`) REFERENCES `cardapio` (`id`);

--
-- Restrições para tabelas `estoque_entrada`
--
ALTER TABLE `estoque_entrada`
  ADD CONSTRAINT `estoque_entrada_ibfk_1` FOREIGN KEY (`fk_tipo_cardapio`) REFERENCES `cardapio_tipo` (`id`),
  ADD CONSTRAINT `estoque_entrada_ibfk_2` FOREIGN KEY (`fk_item_cardapio`) REFERENCES `cardapio` (`id`);

--
-- Restrições para tabelas `estoque_saida`
--
ALTER TABLE `estoque_saida`
  ADD CONSTRAINT `estoque_saida_ibfk_1` FOREIGN KEY (`fk_tipo_cardapio`) REFERENCES `cardapio_tipo` (`id`),
  ADD CONSTRAINT `estoque_saida_ibfk_2` FOREIGN KEY (`fk_item_cardapio`) REFERENCES `cardapio` (`id`),
  ADD CONSTRAINT `estoque_saida_ibfk_3` FOREIGN KEY (`fk_pedido_item`) REFERENCES `pedido_item` (`id`);

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`fk_cartao`) REFERENCES `cartao` (`id`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`fk_cartao_cliente`) REFERENCES `cartao_cliente` (`id`);

--
-- Restrições para tabelas `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD CONSTRAINT `pedido_item_ibfk_1` FOREIGN KEY (`fk_pedido`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `pedido_item_ibfk_2` FOREIGN KEY (`fk_item_cardapio`) REFERENCES `cardapio` (`id`);

--
-- Restrições para tabelas `saida_credito`
--
ALTER TABLE `saida_credito`
  ADD CONSTRAINT `saida_credito_ibfk_1` FOREIGN KEY (`fk_cartao_cliente`) REFERENCES `cartao_cliente` (`id`),
  ADD CONSTRAINT `saida_credito_ibfk_2` FOREIGN KEY (`fk_pedido`) REFERENCES `pedido` (`id`);

--
-- Restrições para tabelas `seg_dependencia`
--
ALTER TABLE `seg_dependencia`
  ADD CONSTRAINT `seg_dependencia_acao_atual_id_foreign` FOREIGN KEY (`acao_atual_id`) REFERENCES `seg_acao` (`id`),
  ADD CONSTRAINT `seg_dependencia_acao_dependencia_id_foreign` FOREIGN KEY (`acao_dependencia_id`) REFERENCES `seg_acao` (`id`);

--
-- Restrições para tabelas `seg_grupo`
--
ALTER TABLE `seg_grupo`
  ADD CONSTRAINT `seg_grupo_perfil_id_foreign` FOREIGN KEY (`perfil_id`) REFERENCES `seg_perfil` (`id`);

--
-- Restrições para tabelas `seg_historico`
--
ALTER TABLE `seg_historico`
  ADD CONSTRAINT `seg_historico_acao_id_foreign` FOREIGN KEY (`acao_id`) REFERENCES `seg_acao` (`id`);

--
-- Restrições para tabelas `seg_menu`
--
ALTER TABLE `seg_menu`
  ADD CONSTRAINT `seg_menu_acao_id_foreign` FOREIGN KEY (`acao_id`) REFERENCES `seg_acao` (`id`),
  ADD CONSTRAINT `seg_menu_pai_foreign` FOREIGN KEY (`pai`) REFERENCES `seg_menu` (`id`);

--
-- Restrições para tabelas `seg_permissao`
--
ALTER TABLE `seg_permissao`
  ADD CONSTRAINT `seg_permissao_acao_id_foreign` FOREIGN KEY (`acao_id`) REFERENCES `seg_acao` (`id`),
  ADD CONSTRAINT `seg_permissao_perfil_id_foreign` FOREIGN KEY (`perfil_id`) REFERENCES `seg_perfil` (`id`);

--
-- Restrições para tabelas `usuario_sistema`
--
ALTER TABLE `usuario_sistema`
  ADD CONSTRAINT `usuario_sistema_ibfk_1` FOREIGN KEY (`sistema_id`) REFERENCES `sistema` (`id`),
  ADD CONSTRAINT `usuario_sistema_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
