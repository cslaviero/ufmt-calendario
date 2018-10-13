--
-- Database: `calendario`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_campus`
--

CREATE TABLE `tbl_campus` (
  `cps_id` int(11) NOT NULL,
  `cps_nome` varchar(100) COLLATE utf8mb4_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Extraindo dados da tabela `tbl_campus`
--

INSERT INTO `tbl_campus` (`cps_id`, `cps_nome`) VALUES
(1, 'Cuiabá'),
(2, 'Rondonópolis'),
(3, 'Várzea Grande'),
(4, 'Sinop'),
(5, 'Araguaia-Pontal'),
(6, 'Araguaia-B. do Garças');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_categoria`
--

CREATE TABLE `tbl_categoria` (
  `cat_id` int(11) NOT NULL,
  `cat_nome` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `cat_cor` varchar(8) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_categoria`
--

INSERT INTO `tbl_categoria` (`cat_id`, `cat_nome`, `cat_cor`) VALUES
(1, 'Dia Letivo', '#0080FF'),
(2, 'Feriado', '#E73A2F'),
(3, 'Matrícula/Rematrícula', '#E72FBF'),
(4, 'Eventos', '#2CBA15'),
(5, 'Recesso', '#EBCB23'),
(6, 'Semestre Letivo', '#e7922f');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_eventos`
--

CREATE TABLE `tbl_eventos` (
  `eve_id` int(11) NOT NULL,
  `eve_periodo` int(11) NOT NULL,
  `eve_campus` int(11) DEFAULT '0',
  `eve_categoria` int(11) NOT NULL,
  `eve_nome` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `eve_local` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `eve_data_ini` datetime NOT NULL,
  `eve_data_fim` datetime NOT NULL,
  `eve_descricao` mediumtext COLLATE utf8_swedish_ci NOT NULL,
  `eve_url` varchar(255) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_eventos`
--

INSERT INTO `tbl_eventos` (`eve_id`, `eve_periodo`, `eve_campus`, `eve_categoria`, `eve_nome`, `eve_local`, `eve_data_ini`, `eve_data_fim`, `eve_descricao`, `eve_url`) VALUES
(1, 1, 0, 6, 'Período Letivo 2018/1', 'Todos os campus', '2018-03-21 00:00:00', '2018-10-19 00:00:00', 'Período Letivo 2018/1', ''),
(3, 1, 0, 3, 'Período de ajuste de matrícula ON-LINE pelo aluno no sistema de créditos.', 'Site da UFMT', '2018-03-21 00:00:00', '2018-09-22 23:59:59', 'Início  do  período  de  ajuste  de matrícula  ON-LINE  pelo  aluno  no sistema  de  créditos (cancelar  e/ou acrescentar  disciplinas),  conforme Resolução  Consepe  nº52/1994  para  o período letivo 2018/1, campi de Cuiabá, Rondonópolis e Várzea Grande.', 'http://www.ufmt.br'),
(5, 1, 0, 2, 'Evento teste', 'teste', '2018-09-27 00:00:00', '2018-09-27 23:59:59', '', ''),
(6, 1, 0, 4, 'Evento teste 2', 'teste 2', '2018-09-29 14:00:00', '2018-09-29 20:00:00', '', ''),
(7, 1, 0, 3, 'Teste outubro', '', '2018-10-10 00:00:00', '2018-10-12 23:59:59', '', ''),
(8, 1, 0, 4, 'Teste 4', 'teste 4', '2018-08-14 00:00:00', '2018-09-29 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_item_permissao`
--

CREATE TABLE `tbl_item_permissao` (
  `pri_id` int(11) NOT NULL,
  `pri_nome_menu` varchar(100) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_periodos`
--

CREATE TABLE `tbl_periodos` (
  `prd_id` int(11) NOT NULL,
  `prd_nome` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `prd_data_ini` datetime NOT NULL,
  `prd_data_fim` datetime NOT NULL,
  `prd_url` text COLLATE utf8_swedish_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_periodos`
--

INSERT INTO `tbl_periodos` (`prd_id`, `prd_nome`, `prd_data_ini`, `prd_data_fim`, `prd_url`) VALUES
(1, 'Calendário 2018/1', '2018-03-21 00:00:00', '2018-10-19 00:00:00', 'http://www.ufmt.br/graduacao/arquivos/ce7cc5f9bf5faea5860bccdba31d8768.pdf'),
(2, 'Calendário 2018/2', '2018-10-19 00:00:01', '2019-04-16 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_permissoes`
--

CREATE TABLE `tbl_permissoes` (
  `prm_id` int(11) NOT NULL,
  `prm_usuario` int(11) NOT NULL,
  `prm_item_permitido` int(11) NOT NULL,
  `prm_inserir` smallint(1) NOT NULL,
  `prm_alterar` smallint(1) NOT NULL,
  `prm_deletar` smallint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `usu_id` int(11) NOT NULL,
  `usu_nome` varchar(100) NOT NULL,
  `usu_email` varchar(200) NOT NULL,
  `usu_usuario` varchar(60) NOT NULL,
  `usu_senha` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`usu_id`, `usu_nome`, `usu_email`, `usu_usuario`, `usu_senha`) VALUES
(1, 'Administrador', 'alisonoliveira1@gmail.com', 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_campus`
--
ALTER TABLE `tbl_campus`
  ADD PRIMARY KEY (`cps_id`);

--
-- Indexes for table `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `tbl_eventos`
--
ALTER TABLE `tbl_eventos`
  ADD PRIMARY KEY (`eve_id`);

--
-- Indexes for table `tbl_item_permissao`
--
ALTER TABLE `tbl_item_permissao`
  ADD PRIMARY KEY (`pri_id`);

--
-- Indexes for table `tbl_periodos`
--
ALTER TABLE `tbl_periodos`
  ADD PRIMARY KEY (`prd_id`);

--
-- Indexes for table `tbl_permissoes`
--
ALTER TABLE `tbl_permissoes`
  ADD PRIMARY KEY (`prm_id`);

--
-- Indexes for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`usu_id`),
  ADD KEY `usu_id` (`usu_id`),
  ADD KEY `usu_id_2` (`usu_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_campus`
--
ALTER TABLE `tbl_campus`
  MODIFY `cps_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_eventos`
--
ALTER TABLE `tbl_eventos`
  MODIFY `eve_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tbl_item_permissao`
--
ALTER TABLE `tbl_item_permissao`
  MODIFY `pri_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_periodos`
--
ALTER TABLE `tbl_periodos`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_permissoes`
--
ALTER TABLE `tbl_permissoes`
  MODIFY `prm_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
