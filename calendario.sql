-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 08-Jul-2021 às 03:26
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
(1, 'Eventos'),
(2, 'Categorias'),
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
(4, 1, 4, 1, 1, 1);

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
(1, 'teste1', 'teste1@teste.com', 'admin', 'pbkdf2:sha256:260000$djM4ePzmroPyF0Y6$b09dd7fc6bf3eb1a6e668872a37f37f480295c6ed4b6dc0e94af99c26308b543');

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
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_comentarios`
--
ALTER TABLE `tbl_comentarios`
  MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_eventos`
--
ALTER TABLE `tbl_eventos`
  MODIFY `eve_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_item_permissao`
--
ALTER TABLE `tbl_item_permissao`
  MODIFY `pri_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_periodos`
--
ALTER TABLE `tbl_periodos`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_permissoes`
--
ALTER TABLE `tbl_permissoes`
  MODIFY `prm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
