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
(1, 'Dia Letivo', '5757ff'),
(2, 'Feriado', 'ff0000'),
(3, 'Matrícula/Rematrícula', 'ff00ea'),
(4, 'Eventos', '008000'),
(5, 'Recesso', 'e8d317'),
(6, 'Semestre Letivo', 'ff6600'),
(8, 'Solicitações', '800080');

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
(9, 1, 0, 3, 'Solicitação de Extraordinário aproveitamento de estudos', '', '2018-03-23 00:00:00', '2018-04-10 23:59:59', 'Solicitação de aproveitamento de estudos para cursos no sistema de\r\ncrédito semestral do período 2018/2 conforme art.2º da Res. CONSEPE nº83/2017.', ''),
(10, 1, 0, 3, 'Período de inscrição dos grupos corais da UFMT', '', '2018-03-23 00:00:00', '2018-03-29 23:59:59', '', ''),
(11, 1, 0, 1, 'Período de avaliação dos projetos de extensão pela Câmara de Extensão.', '', '2018-03-23 00:00:00', '2018-03-29 23:59:59', '', ''),
(12, 1, 0, 3, 'Período de requerimento de ajuste de matrícula pelo aluno', 'SEI', '2018-03-26 00:00:00', '2018-03-31 23:59:59', 'Período de requerimento de ajuste de matrícula pelo aluno para análise e\r\nhomologação do coordenador de curso de graduação (semestrais e anuais), no período letivo 2018/1, pelo protocolo virtual.', ''),
(13, 1, 0, 3, 'Prazo para solicitação de aproveitamento de estudos para cursos no sistema de crédito semestral do período 2018/2', '', '2018-03-26 00:00:00', '2018-06-04 23:59:59', 'Prazo para solicitação de aproveitamento de estudos para cursos no sistema de\r\ncrédito semestral do período 2018/2 conforme art.2º da Res. CONSEPE nº83/2017.', ''),
(14, 1, 0, 1, 'Encerramento do 1º período para solicitação de diplomas de Pós-graduação Stricto Sensu', '', '2018-03-27 00:00:00', '2018-03-27 23:59:59', 'Encerramento do 1º período para solicitação de diplomas de Pós-graduação Stricto\r\nSensu da UFMT.', ''),
(15, 1, 0, 2, 'Paixão de Cristo', '', '2018-03-30 00:00:00', '2018-03-30 23:59:59', '', ''),
(16, 1, 0, 2, 'Feriado Nacional – Páscoa', '', '2018-04-01 00:00:00', '2018-04-01 23:59:59', '', ''),
(17, 1, 0, 1, 'Período de publicação dos editais para o processo seletivo 2018/2 dos cursos de Pós-graduação Stricto Sensu', '', '2018-04-02 00:00:00', '2018-04-27 23:59:59', 'Período de publicação dos editais para o processo seletivo 2018/2 dos cursos\r\nde Pós-graduação Stricto Sensu', ''),
(18, 1, 0, 1, 'Publicação do Edital do Programa de Apoio a Inclusão da PRAE', '', '2018-04-02 00:00:00', '2018-04-02 23:59:59', '', ''),
(19, 1, 0, 1, 'Período de solicitação de ônibus e de auxílio discente para apresentação de trabalhos e participação em eventos', 'SEI (Sistema Eletrônico de Informação)', '2018-04-03 00:00:00', '2018-04-03 23:59:59', 'Período de solicitação de ônibus e de auxílio discente para apresentação de trabalhos e participação em eventos externos nos meses de maio, junho, julho e agosto de 2018 – PRAE.', 'http://sei.ufmt.br'),
(20, 1, 1, 2, 'Aniversário de Cuiabá', '', '2018-04-08 00:00:00', '2018-04-08 23:59:59', '', ''),
(21, 1, 0, 1, 'Prazo de processos solicitando à secretaria da PROPG, concessão, cancelamento ou suspensão de bolsas CAPES.', '', '2018-04-10 00:00:00', '2018-04-10 23:59:59', 'Prazo para protocolo via SEI (Sistema Eletrônico de Informação), de\r\nprocessos solicitando à secretaria da PROPG, concessão, cancelamento ou suspensão de bolsas CAPES.', 'http://sei.ufmt.br'),
(22, 1, 0, 1, 'Período para os docentes registrarem os Planos de Ensino dos cursos de graduação no AVA', '', '2018-04-10 00:00:00', '2018-04-18 23:59:59', 'Período para os docentes registrarem os Planos de Ensino dos cursos de graduação\r\nno ambiente virtual de aprendizagem (AVA)', 'http://ava.ufmt.br');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_item_permissao`
--

CREATE TABLE `tbl_item_permissao` (
  `pri_id` int(11) NOT NULL,
  `pri_nome_menu` varchar(100) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_item_permissao`
--

INSERT INTO `tbl_item_permissao` (`pri_id`, `pri_nome_menu`) VALUES
(1, 'Eventos'),
(2, 'Categorias'),
(3, 'Períodos'),
(4, 'Campus'),
(5, 'Usuários');

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

--
-- Extraindo dados da tabela `tbl_permissoes`
--

INSERT INTO `tbl_permissoes` (`prm_id`, `prm_usuario`, `prm_item_permitido`, `prm_inserir`, `prm_alterar`, `prm_deletar`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 1),
(3, 1, 3, 1, 1, 1),
(4, 1, 4, 1, 1, 1),
(5, 1, 5, 1, 1, 1),
(30, 6, 5, 1, 0, 1),
(29, 6, 4, 1, 0, 1),
(28, 6, 3, 1, 0, 1),
(27, 6, 2, 1, 0, 1),
(26, 6, 1, 1, 0, 1);

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
(1, 'Administrador', 'alisonoliveira1@gmail.com', 'admin', 'admin'),
(6, 'Alison Oliveira', 'alison@usinadoingresso.com.br', 'alison1', 'alison1');

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
  MODIFY `cps_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tbl_eventos`
--
ALTER TABLE `tbl_eventos`
  MODIFY `eve_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tbl_item_permissao`
--
ALTER TABLE `tbl_item_permissao`
  MODIFY `pri_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_periodos`
--
ALTER TABLE `tbl_periodos`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_permissoes`
--
ALTER TABLE `tbl_permissoes`
  MODIFY `prm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
