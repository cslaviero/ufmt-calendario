-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 06-Jul-2021 às 00:12
-- Versão do servidor: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calendario`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `alembic_version`
--

CREATE TABLE `alembic_version` (
  `version_num` varchar(32) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `alembic_version`
--

INSERT INTO `alembic_version` (`version_num`) VALUES
('b6b880adabd6');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_categoria`
--

CREATE TABLE `tbl_categoria` (
  `cat_id` int(11) NOT NULL,
  `cat_nome` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `cat_cor` varchar(8) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_categoria`
--

INSERT INTO `tbl_categoria` (`cat_id`, `cat_nome`, `cat_cor`) VALUES
(1, 'Dia Letivo', '0000ff'),
(2, 'Feriado', 'ff0000'),
(3, 'Matrícula/Rematrícula', 'ff00ea'),
(4, 'Eventos', '008000'),
(5, 'Recesso', 'ffff00'),
(6, 'Semestre Letivo', 'ff6600'),
(7, 'Solicitações', '800080');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_comentarios`
--

CREATE TABLE `tbl_comentarios` (
  `com_id` int(11) NOT NULL,
  `com_nome` varchar(40) COLLATE utf8_swedish_ci NOT NULL,
  `com_email` varchar(40) COLLATE utf8_swedish_ci DEFAULT NULL,
  `com_curso` varchar(40) COLLATE utf8_swedish_ci DEFAULT NULL,
  `com_texto` text COLLATE utf8_swedish_ci NOT NULL,
  `com_nota` varchar(2) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_comentarios`
--

INSERT INTO `tbl_comentarios` (`com_id`, `com_nome`, `com_email`, `com_curso`, `com_texto`, `com_nota`) VALUES
(1, 'teste9', 'teste9@teste.com', 'curso9', 'ok teste 9', '4'),
(2, '4', '4', '4', 'As avaliações são públicas, e é possível editá-las. Todos podem ver o nome e a foto da sua Conta do Google. Os desenvolvedores também conseguem ver seu país e informações do dispositivo (como idioma, modelo e versão do SO) e podem usar esses dados para responder a você.', '4'),
(3, '5', '5', '5', 'As avaliações são públicas, e é possível editá-las. Todos podem ver o nome e a foto da sua Conta do Google. Os desenvolvedores também conseguem ver seu país e informações do dispositivo (como idioma, modelo e versão do SO) e podem usar esses dados para responder a você.\r\nAs avaliações são públicas, e é possível editá-las. Todos podem ver o nome e a foto da sua Conta do Google. Os desenvolvedores também conseguem ver seu país e informações do dispositivo (como idioma, modelo e versão do SO) e podem usar esses dados para responder a você.\r\nAs avaliações são públicas, e é possível editá-las. Todos podem ver o nome e a foto da sua Conta do Google. Os desenvolvedores também conseguem ver seu país e informações do dispositivo (como idioma, modelo e versão do SO) e podem usar esses dados para responder a você.\r\nAs avaliações são públicas, e é possível editá-las. Todos podem ver o nome e a foto da sua Conta do Google. Os desenvolvedores também conseguem ver seu país e informações do dispositivo (como idioma, modelo e versão do SO) e podem usar esses dados para responder a você.', '2'),
(4, 'Teste5', 'teste5@teste.com', '', 'Teste5  celular', '4');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_eventos`
--

CREATE TABLE `tbl_eventos` (
  `eve_id` int(11) NOT NULL,
  `eve_periodo` int(11) NOT NULL,
  `eve_categoria` int(11) NOT NULL,
  `eve_nome` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `eve_local` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `eve_data_ini` datetime NOT NULL,
  `eve_data_fim` datetime NOT NULL,
  `eve_descricao` text COLLATE utf8_swedish_ci NOT NULL,
  `eve_url` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_eventos`
--

INSERT INTO `tbl_eventos` (`eve_id`, `eve_periodo`, `eve_categoria`, `eve_nome`, `eve_local`, `eve_data_ini`, `eve_data_fim`, `eve_descricao`, `eve_url`) VALUES
(1, 1, 6, 'Período Letivo 2018/1', 'Todos os campus', '2018-03-21 00:00:00', '2018-10-19 00:00:00', 'Início do Período Letivo 2018/1', ''),
(3, 1, 3, 'Período de ajuste de matrícula ON-LINE pelo aluno no sistema de créditos.', 'Site da UFMT', '2018-03-21 00:00:00', '2018-09-22 23:59:59', 'Início  do  período  de  ajuste  de matrícula  ON-LINE  pelo  aluno  no sistema  de  créditos (cancelar  e/ou acrescentar  disciplinas),  conforme Resolução  Consepe  nº52/1994  para  o período letivo 2018/1, campi de Cuiabá, Rondonópolis e Várzea Grande.', 'http://www.ufmt.br'),
(9, 1, 3, 'Solicitação de Extraordinário aproveitamento de estudos', '', '2018-03-23 00:00:00', '2018-04-10 23:59:59', 'Solicitação de aproveitamento de estudos para cursos no sistema de\r\ncrédito semestral do período 2018/2 conforme art.2º da Res. CONSEPE nº83/2017.', ''),
(10, 1, 3, 'Período de inscrição dos grupos corais da UFMT', '', '2018-03-23 00:00:00', '2018-03-29 23:59:59', '', ''),
(11, 1, 1, 'Período de avaliação dos projetos de extensão pela Câmara de Extensão.', '', '2018-03-23 00:00:00', '2018-03-29 23:59:59', '', ''),
(12, 1, 3, 'Período de requerimento de ajuste de matrícula pelo aluno', 'SEI', '2018-03-26 00:00:00', '2018-03-31 23:59:59', 'Período de requerimento de ajuste de matrícula pelo aluno para análise e\r\nhomologação do coordenador de curso de graduação (semestrais e anuais), no período letivo 2018/1, pelo protocolo virtual.', ''),
(13, 1, 3, 'Prazo para solicitação de aproveitamento de estudos para cursos no sistema de crédito semestral do período 2018/2', '', '2018-03-26 00:00:00', '2018-06-04 23:59:59', 'Prazo para solicitação de aproveitamento de estudos para cursos no sistema de\r\ncrédito semestral do período 2018/2 conforme art.2º da Res. CONSEPE nº83/2017.', ''),
(14, 1, 1, 'Encerramento do 1º período para solicitação de diplomas de Pós-graduação Stricto Sensu', '', '2018-03-27 00:00:00', '2018-03-27 23:59:59', 'Encerramento do 1º período para solicitação de diplomas de Pós-graduação Stricto\r\nSensu da UFMT.', ''),
(15, 1, 2, 'Paixão de Cristo', '', '2018-03-30 00:00:00', '2018-03-30 23:59:59', '', ''),
(16, 1, 2, 'Feriado Nacional – Páscoa', '', '2018-04-01 00:00:00', '2018-04-01 23:59:59', '', ''),
(17, 1, 1, 'Período de publicação dos editais para o processo seletivo 2018/2 dos cursos de Pós-graduação Stricto Sensu', '', '2018-04-02 00:00:00', '2018-04-27 23:59:59', 'Período de publicação dos editais para o processo seletivo 2018/2 dos cursos\r\nde Pós-graduação Stricto Sensu', ''),
(18, 1, 1, 'Publicação do Edital do Programa de Apoio a Inclusão da PRAE', '', '2018-04-02 00:00:00', '2018-04-02 23:59:59', '', ''),
(19, 1, 1, 'Período de solicitação de ônibus e de auxílio discente para apresentação de trabalhos e participação em eventos', 'SEI (Sistema Eletrônico de Informação)', '2018-04-03 00:00:00', '2018-04-03 23:59:59', 'Período de solicitação de ônibus e de auxílio discente para apresentação de trabalhos e participação em eventos externos nos meses de maio, junho, julho e agosto de 2018 – PRAE.', 'http://sei.ufmt.br'),
(20, 1, 2, 'Aniversário de Cuiabá', '', '2018-04-08 00:00:00', '2018-04-08 23:59:59', '', ''),
(21, 1, 1, 'Prazo de processos solicitando à secretaria da PROPG, concessão, cancelamento ou suspensão de bolsas CAPES.', '', '2018-04-10 00:00:00', '2018-04-10 23:59:59', 'Prazo para protocolo via SEI (Sistema Eletrônico de Informação), de\r\nprocessos solicitando à secretaria da PROPG, concessão, cancelamento ou suspensão de bolsas CAPES.', 'http://sei.ufmt.br'),
(22, 1, 1, 'Período para os docentes registrarem os Planos de Ensino dos cursos de graduação no AVA', '', '2018-04-10 00:00:00', '2018-04-18 23:59:59', 'Período para os docentes registrarem os Planos de Ensino dos cursos de graduação\r\nno ambiente virtual de aprendizagem (AVA)', 'http://ava.ufmt.br'),
(31, 13, 1, 'evento1', 'local eve1', '2021-05-02 00:00:00', '2021-05-02 23:59:59', 'desc eve 1\r\n                                    \r\n                                    ', 'url eve 1'),
(37, 13, 3, 'teste72', 'local eve72', '2021-05-03 14:44:51', '2021-05-25 11:28:07', 'eve72', 'www.teste72'),
(39, 13, 7, 'teste74', 'local eve74', '2021-05-24 11:39:24', '2021-06-08 14:08:50', 'eve74\r\n                                    ', 'www.teste74'),
(40, 13, 2, 'teste75', 'local eve75', '2021-07-03 00:00:00', '2021-08-28 23:59:59', 'evento teste 75', 'www.teste74'),
(41, 14, 6, 'teste 77', 'local eve77', '2021-06-25 22:14:31', '2021-06-27 22:14:47', 'evento teste 77\r\n                                    ', 'www.teste77'),
(44, 1, 3, 'teste22edit', 'local eve88', '2021-05-03 14:44:51', '2021-05-25 11:28:07', 'p', 'www.teste3'),
(45, 14, 1, 'teste 29-06', 'local eve 29-06', '2021-06-08 23:26:21', '2021-06-25 23:26:25', 'desc eve 29-06', 'www eve 29-06'),
(48, 14, 2, 'teste729', '9', '2021-05-24 11:39:24', '2021-09-14 00:00:00', '9', '9'),
(49, 1, 5, 'teste701', 'teste701', '2021-06-04 11:27:45', '2021-06-08 11:27:55', 'teste701', 'teste701'),
(77, 1, 2, 'teste704', '', '2021-06-30 13:07:14', '2021-07-01 13:07:16', '', ''),
(91, 13, 6, 'teste 05-07  inicio dia 11, acontagem começa em 5 a 11, são 7dias', 'local eve1', '2021-07-11 12:05:43', '2021-07-16 12:06:02', 'yxz\r\n                                    ', 'www.teste7'),
(92, 14, 1, 'teste43', '43', '2021-07-07 18:26:37', '2021-07-06 18:26:33', '43\r\n', '43'),
(93, 14, 4, 'eve34', 'eve34', '2021-07-14 18:56:34', '2021-07-15 18:56:39', 'eve34', 'eve34'),
(94, 14, 3, 'eve12L', 'local eve12', '2021-07-15 18:58:22', '2021-08-17 18:58:16', 'texto eve12\r\n                                    ', 'urleve12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_item_permissao`
--

CREATE TABLE `tbl_item_permissao` (
  `pri_id` int(11) NOT NULL,
  `pri_nome_menu` varchar(200) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_item_permissao`
--

INSERT INTO `tbl_item_permissao` (`pri_id`, `pri_nome_menu`) VALUES
(2, 'Categorias'),
(1, 'Eventos'),
(3, 'Períodos'),
(4, 'Usuários');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_periodos`
--

INSERT INTO `tbl_periodos` (`prd_id`, `prd_nome`, `prd_data_ini`, `prd_data_fim`, `prd_url`) VALUES
(1, 'Calendário 2019/1', '2021-05-18 04:44:00', '2021-05-18 04:44:00', 'http://www.google.com'),
(3, 'Calendário 2018/1', '2018-03-21 00:00:00', '2018-10-19 00:00:00', 'http://www.urf.com.br'),
(4, 'Calendário 2018/2', '2018-10-19 00:00:01', '2019-04-16 00:00:00', NULL),
(7, 'teste5', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'www.teste5'),
(8, 'teste7', '2021-05-07 14:08:42', '0000-00-00 00:00:00', 'www.teste7'),
(11, 'teste92', '2021-02-09 17:18:03', '2021-02-10 17:18:08', 'www.teste92'),
(13, 'calendário 2020/2', '2021-05-10 14:08:42', '2021-05-20 21:21:21', 'https://ufr.edu.br/'),
(14, 'teste101', '2021-05-07 14:08:42', '2021-05-09 14:08:50', 'http://localhost/phpmyadmin/sql.php?db=calendario&goto=db_structure.php&table=tbl_periodos&pos=0'),
(17, 'teste15', '2021-05-07 14:08:42', '2021-05-25 11:28:07', 'www.teste105'),
(19, 'teste9', '2021-05-07 14:08:42', '2021-05-25 11:28:07', 'www.teste7'),
(22, 'teste12', '2021-05-07 14:08:42', '2021-05-08 14:08:50', 'www.teste12'),
(24, 'teste71', '2021-05-07 14:08:42', '2021-05-25 11:28:07', 'www.teste71'),
(26, 'teste73', '2021-05-07 14:08:42', '2021-05-07 14:08:42', 'www.teste72'),
(27, 'teste74', '2021-05-07 14:08:42', '2021-05-07 14:08:42', 'www.teste7'),
(28, 'teste75', '2021-05-24 11:39:24', '2021-05-24 11:39:24', 'www.teste75'),
(31, 'teste78', '2021-05-24 11:39:24', '2021-05-24 11:39:24', 'www.teste3'),
(34, 'teste223', '2000-01-10 00:00:00', '2000-01-11 00:00:00', 'www.teste223'),
(38, 'vazio2', '2021-06-30 12:06:17', '2021-06-30 12:06:17', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_permissoes`
--

CREATE TABLE `tbl_permissoes` (
  `prm_id` int(11) NOT NULL,
  `prm_usuario` int(11) NOT NULL,
  `prm_item_permitido` int(11) NOT NULL,
  `prm_inserir` smallint(6) DEFAULT NULL,
  `prm_alterar` smallint(6) DEFAULT NULL,
  `prm_deletar` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_permissoes`
--

INSERT INTO `tbl_permissoes` (`prm_id`, `prm_usuario`, `prm_item_permitido`, `prm_inserir`, `prm_alterar`, `prm_deletar`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 1),
(3, 1, 3, 1, 1, 1),
(4, 1, 4, 1, 1, 1),
(5, 2, 1, 0, 1, 0),
(6, 2, 2, 1, 0, 1),
(7, 2, 3, 0, 1, 0),
(8, 2, 4, 1, 0, 1),
(17, 8, 1, 0, 0, 1),
(18, 8, 2, 1, 0, 0),
(19, 8, 3, 1, 0, 0),
(20, 8, 4, 0, 0, 1),
(21, 9, 1, 1, 1, 0),
(22, 9, 2, 0, 1, 1),
(23, 9, 3, 0, 1, 1),
(24, 9, 4, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `usu_id` int(11) NOT NULL,
  `usu_nome` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `usu_email` varchar(200) COLLATE utf8_swedish_ci NOT NULL,
  `usu_usuario` varchar(60) COLLATE utf8_swedish_ci NOT NULL,
  `usu_senha` text COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Extraindo dados da tabela `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`usu_id`, `usu_nome`, `usu_email`, `usu_usuario`, `usu_senha`) VALUES
(1, 'teste1', 'teste1@teste.com', 'admin', 'pbkdf2:sha256:260000$NoUjSMhPWsY2XTlN$1b83b47e5900badeae57be6c257f9c10309c74378f0e3b8c150d8441917a37f6'),
(2, 'teste2', 'teste2@teste.com', 'teste2', 'pbkdf2:sha256:260000$T68qnwMUIWzmIXTP$a4a79f8358a63fb1c13a76c4316da9eb157fd1f18dbaee8f14590d328568e467'),
(8, 'teste3', 'teste3@gmail.com', 'admin', 'pbkdf2:sha256:260000$jFhDfRwc3FdmHRL3$b265ffdf0ae65cc0cc18479eb5f09f5d1eb9521cffc1468c34be31e4140afc2c'),
(9, 'teste4', 'teste4@teste.com', 'admin', 'pbkdf2:sha256:260000$3k7LFcvhznZYHiGt$7f905a52f2657b11f7305865acbe90c2df9546a17694d71cd2341acc31e1814d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alembic_version`
--
ALTER TABLE `alembic_version`
  ADD PRIMARY KEY (`version_num`);

--
-- Indexes for table `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_nome` (`cat_nome`),
  ADD UNIQUE KEY `cat_nome_2` (`cat_nome`);

--
-- Indexes for table `tbl_comentarios`
--
ALTER TABLE `tbl_comentarios`
  ADD PRIMARY KEY (`com_id`);

--
-- Indexes for table `tbl_eventos`
--
ALTER TABLE `tbl_eventos`
  ADD PRIMARY KEY (`eve_id`),
  ADD KEY `eve_categoria` (`eve_categoria`),
  ADD KEY `eve_periodo` (`eve_periodo`);

--
-- Indexes for table `tbl_item_permissao`
--
ALTER TABLE `tbl_item_permissao`
  ADD PRIMARY KEY (`pri_id`),
  ADD UNIQUE KEY `pri_nome_menu` (`pri_nome_menu`);

--
-- Indexes for table `tbl_periodos`
--
ALTER TABLE `tbl_periodos`
  ADD PRIMARY KEY (`prd_id`),
  ADD UNIQUE KEY `prd_nome` (`prd_nome`),
  ADD UNIQUE KEY `prd_nome_2` (`prd_nome`);

--
-- Indexes for table `tbl_permissoes`
--
ALTER TABLE `tbl_permissoes`
  ADD PRIMARY KEY (`prm_id`),
  ADD KEY `prm_item_permitido` (`prm_item_permitido`),
  ADD KEY `prm_usuario` (`prm_usuario`);

--
-- Indexes for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`usu_id`),
  ADD UNIQUE KEY `usu_email` (`usu_email`),
  ADD UNIQUE KEY `usu_email_2` (`usu_email`),
  ADD UNIQUE KEY `usu_nome` (`usu_nome`),
  ADD UNIQUE KEY `usu_nome_2` (`usu_nome`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_comentarios`
--
ALTER TABLE `tbl_comentarios`
  MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_eventos`
--
ALTER TABLE `tbl_eventos`
  MODIFY `eve_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `tbl_item_permissao`
--
ALTER TABLE `tbl_item_permissao`
  MODIFY `pri_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_periodos`
--
ALTER TABLE `tbl_periodos`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_permissoes`
--
ALTER TABLE `tbl_permissoes`
  MODIFY `prm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tbl_eventos`
--
ALTER TABLE `tbl_eventos`
  ADD CONSTRAINT `tbl_eventos_ibfk_1` FOREIGN KEY (`eve_categoria`) REFERENCES `tbl_categoria` (`cat_id`),
  ADD CONSTRAINT `tbl_eventos_ibfk_2` FOREIGN KEY (`eve_periodo`) REFERENCES `tbl_periodos` (`prd_id`);

--
-- Limitadores para a tabela `tbl_permissoes`
--
ALTER TABLE `tbl_permissoes`
  ADD CONSTRAINT `tbl_permissoes_ibfk_1` FOREIGN KEY (`prm_item_permitido`) REFERENCES `tbl_item_permissao` (`pri_id`),
  ADD CONSTRAINT `tbl_permissoes_ibfk_2` FOREIGN KEY (`prm_usuario`) REFERENCES `tbl_usuarios` (`usu_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
