-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 15-Set-2014 às 02:13
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `sangue`
--
CREATE DATABASE IF NOT EXISTS `sangue` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sangue`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `colaboradores`
--

CREATE TABLE IF NOT EXISTS `colaboradores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` int(100) NOT NULL,
  `nascimento` date NOT NULL,
  `tipo_sanguineo` varchar(6) NOT NULL,
  `sexo` char(1) NOT NULL,
  `uf` char(2) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `id_indicacao` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela para cadastro de doadores e receptores' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `demandas`
--

CREATE TABLE IF NOT EXISTS `demandas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_colaborador` int(11) NOT NULL,
  `id_local` int(11) NOT NULL,
  `qtde` int(11) NOT NULL,
  `validade` date NOT NULL,
  `nome` varchar(30) NOT NULL,
  `curtidas` int(11) NOT NULL,
  `compartilhamentos` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_colaborador` (`id_colaborador`),
  KEY `id_local` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `doacao`
--

CREATE TABLE IF NOT EXISTS `doacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_colaborador` int(11) NOT NULL,
  `id_demanda` int(11) NOT NULL,
  `id_local` int(11) NOT NULL,
  `curtidas` int(11) NOT NULL,
  `compartilhamentos` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_colaborador` (`id_colaborador`),
  KEY `id_demanda` (`id_demanda`),
  KEY `id_local` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventos`
--

CREATE TABLE IF NOT EXISTS `eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_colaborador` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `id_evento` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_colaborador` (`id_colaborador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `locais`
--

CREATE TABLE IF NOT EXISTS `locais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `latitude` decimal(11,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `CEP` varchar(8) NOT NULL,
  `endereco` varchar(140) NOT NULL,
  `bairro` varchar(30) NOT NULL,
  `cidade` varchar(60) NOT NULL,
  `UF` varchar(2) NOT NULL,
  `telefone` varchar(12) NOT NULL,
  `site` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Extraindo dados da tabela `locais`
--

INSERT INTO `locais` (`id`, `nome`, `latitude`, `longitude`, `CEP`, `endereco`, `bairro`, `cidade`, `UF`, `telefone`, `site`) VALUES
(2, 'HEMORGS - HEMOCENTRO DO RS', '-30.06324950', '-51.17878120', '90650001', 'Av. Bento Gonçalves, 3722', 'Partenon', 'Porto Alegre', 'RS', '5133366755', 'www.hemocentro.rs.gov.br'),
(3, 'HEMOPASSO - HEMOCENTRO DE PASSO FUNDO', '-28.25411760', '-52.42018800', '99010120', 'Av. Sete de Setembro, 1055', 'Centro', 'Passo Fundo', 'RS', '5433115555', ''),
(6, 'HEMOCS - HEMOCENTRO DE CAXIAS DO SUL', '-29.16396950', '-51.18472920', '95020360', 'Rua Ernesto Alves, 2260', 'Centro', 'Caxias do Sul', 'RS', '5432904576', ''),
(7, 'HEMOSAR - HEMOCENTRO DE SANTA ROSA', '-27.86845660', '-54.48617360', '98900000', 'Rua Boa Vista, 401', 'Centro', 'Santa Rosa', 'RS', '5535114343', ''),
(8, 'HEMOCENTRO DE CRUZ ALTA', '-28.64463780', '-53.60798970', '98010770', 'Rua Barão do Rio Branco, 1445', '', 'Cruz Alta', 'RS', '5533263478', ''),
(9, 'HEMOPEL - HEMOCENTRO DE PELOTAS', '-31.75805780', '-52.34920010', '96015140', 'Av. Bento Gonçalves, 4569', 'Centro', 'Pelotas', 'RS', '5332223002', ''),
(10, 'HEMOCENTRO DE ALEGRETE', '-29.77800750', '-55.79174680', '97541260', 'Rua General Sampaio, 10', 'Canudos', 'Alegrete', 'RS', '5534264127', ''),
(11, 'HEMORGS - REGIONAL DE SANTA MARIA', '-29.69267060', '-53.79440180', '98090000', 'Rua Alameda Santiago do Chile, 35', '', 'Santa Maria', 'RS', '5532215262', ''),
(12, 'HEMOCENTRO DE PALMEIRA DAS MISSÕES', '-27.90106700', '-53.31065220', '98300000', 'Rua Gen. Osório, 351', '', 'Palmeira das Missões', 'RS', '5537421480', ''),
(13, 'micmmed equipamentos maedicohospitalares ltda', '-30.03590370', '-51.20065600', '', 'r dr lauro de oliveira 44', '', 'Porto Alegre', '', '555133332277', ''),
(14, 'unimed porto alegre sociedade cooperativa de trabalho maedico ltda  ci', '-30.03767560', '-51.21049640', '', 'av venaancio aires 1040', '', 'Porto Alegre', '', '555133164646', ''),
(15, 'centro maedico ipanema  cavalhada', '-30.13849490', '-51.22076760', '', 'av juca batista 518', '', 'Porto Alegre', '', '555132485804', ''),
(16, 'salute centro maedico  navegantes', '-30.10269100', '-51.23294320', '', 'av otto niemeyer 2695', '', 'Porto Alegre', '', '555130126002', ''),
(17, 'radicom clainica de diagnaostico maedico por imagem  menino deus', '-30.05085380', '-51.21803050', '', 'av arico veraissimo 624', '', 'Porto Alegre', '', '555132182400', ''),
(18, 'instituto maedico legal  jardim guanabara', '-30.04566250', '-51.20923210', '', 'av ipiranga 1887', '', 'Porto Alegre', '', '555132235409', ''),
(19, 'maedico psiquiatra  petraopolis', '-30.04170840', '-51.18284770', '', 'av taquara 564 ap 501', '', 'Porto Alegre', '', '555133327417', ''),
(20, 'art medical produtos madicohospitalares  santana', '-30.04900330', '-51.20639410', '', 'rua domingos crescencio 394', '', 'Porto Alegre', '', '32313415', ''),
(21, 'silisul comercio e representacao de material medico cirurgico ltda  m', '-30.02908740', '-51.20297740', '', 'r floraencio ygartuadr 292', '', 'Porto Alegre', '', '555133325251', ''),
(22, 'quaantica w distribuidora e com de produtos maedico hospitalar ltda  pe', '-30.05047490', '-51.18684140', '', 'rua br amazonas 756 sl 102', '', 'Porto Alegre', '', '555133883577', ''),
(23, 'salute centro maedico  restinga', '-30.03464710', '-51.21765840', '', 'av econ nilo wulff 847', '', 'Porto Alegre', '', '555132613866', ''),
(24, 'dinaamica material maedico hospitalar  azenha', '-30.06043010', '-51.22274340', '', 'rua josae alencar 868 sl 606', '', 'Porto Alegre', '', '555132321709', ''),
(25, 'consultaorio maedico', '-30.02966050', '-51.22273590', '', 'pc d feliciano 39 ap 1202', '', 'Porto Alegre', '', '555132241333', ''),
(26, 'unisaaude sul cooperativa de trabalho maedico da regiaao sul  floresta', '-30.02561940', '-51.21414310', '', 'rua ernesto alves 299', '', 'Porto Alegre', '', '555132212982', ''),
(27, 'centro maedico praia do guaaiba  menino deus', '-30.06017710', '-51.22916700', '', 'rua antenor lemos 57 an 6', '', 'Porto Alegre', '', '555132316266', ''),
(28, 'consultaorio maedico neurologista dr amir josae dos santos  auxiliadora', '-30.02579800', '-51.19643930', '', 'rua cel bordini 830 s 505', '', 'Porto Alegre', '', '555133311989', ''),
(29, 'medcare produtos e equipamentos maedico hospitalares  saao sebastiaao', '-29.99654940', '-51.13115600', '', 'av assis brasil 6186 s 408', '', 'Porto Alegre', '', '555133657769', ''),
(30, 'centro medico zona sul ltda  tristeza', '-30.10645240', '-51.25438330', '', 'r vicente failace 471', '', 'Porto Alegre', '', '555132684652', ''),
(31, 'maximedsul comercio de produtos medicohospitalares ltda  saao gerald', '-30.00842440', '-51.20388020', '', 'av polaonia 530', '', 'Porto Alegre', '', '555133260759', ''),
(32, 'instramed indastria madico hospitalar ltda  petraopolis', '-30.03985420', '-51.17652720', '', 'av protasio alves 3371', '', 'Porto Alegre', '', '33344199', ''),
(33, 'citoson serviaos auxiliares diagnaostico maedico', '-30.02974470', '-51.22431680', '', 'rua dos andradas 1711 s 901', '', 'Porto Alegre', '', '555132284061', ''),
(34, 'radicom clainica de diagnaostico maedico por imagem  cristo redentor', '-30.01070660', '-51.15808430', '', 'rua alvares cabral 65', '', 'Porto Alegre', '', '555133453500', ''),
(35, 'clinica medico odontologica gheno ltda  independaencia', '-30.03142760', '-51.20230460', '', 'r castro alves 819', '', 'Porto Alegre', '', '555133312244', ''),
(36, 'centro maedico sogipa  saao joaao', '-30.00935320', '-51.18874670', '', 'rua br cotegipe 400', '', 'Porto Alegre', '', '555133421705', ''),
(37, 'igmed comercio de material medico hospitalar ltda  floresta', '-30.02466810', '-51.21135400', '', 'av cristaovaao colombo 682', '', 'Porto Alegre', '', '555130242626', ''),
(38, 'instramed indaustria maedico hospitalar ltda', '-30.03985420', '-51.17652720', '', 'av protaasio alves 3371', '', 'Porto Alegre', '', '555133344199', ''),
(39, 'centro medico tristeza ltda  cristal', '-30.11433200', '-51.25469970', '', 'av wenceslau escobar 2961', '', 'Porto Alegre', '', '555132692829', ''),
(40, 'odontotec assistaencia taecnica maedico odontolaogica  saao geraldosaao jo', '-30.01317980', '-51.19692890', '', 'rua buarque macedo 610 an t', '', 'Porto Alegre', '', '555133420731', ''),
(41, 'fufamed comaercio e importaaaao maedico hospitalar ltda  saao joaao', '-30.00586210', '-51.17456770', '', 'r nicolaus 1080', '', 'Porto Alegre', '', '555133617700', ''),
(42, 'consultaorio maedico luiz carlos fadel  independaencia', '-30.02732550', '-51.21131120', '', 'rua gonaalo carvalho 209 s 502', '', 'Porto Alegre', '', '555133115107', ''),
(43, 'dentalsul comaercio e indaustria de material odonto maedico ltda', '-30.03051730', '-51.22406540', '', 'rua gal vitorino 265', '', 'Porto Alegre', '', '555132123361', ''),
(44, 'centro medico praia do guaiba sociedade simples ltda  menino deus', '-30.06017710', '-51.22916700', '', 'r antenor lemos 57', '', 'Porto Alegre', '', '555132312868', ''),
(45, 'centro maedico do sarandi  sarandi', '-29.99476360', '-51.12949110', '', 'rua abaetae 29 an 1', '', 'Porto Alegre', '', '555133644383', ''),
(46, 'fufamed comaercio e importaaaao maedico hospitalar ltda  saao joaao', '-30.00586210', '-51.17456770', '', 'rua s nicolau 1080', '', 'Porto Alegre', '', '555133617700', ''),
(47, 'mitra cooperativa trab maedico odonto assis e ocupacional ltda  azenha', '-30.05421860', '-51.21518070', '', 'rua 20 de setembro 404 s 203', '', 'Porto Alegre', '', '555132193429', ''),
(48, 'mk produtos medico hospitalares ltda  partenon', '-30.05566540', '-51.19064550', '', 'r portuguesa 455', '', 'Porto Alegre', '', '555133151787', ''),
(49, 'art medical produtos maedico hospitalares ltda  santana', '-30.04900330', '-51.20639410', '', 'rua domingos crescaencio 394 an 2', '', 'Porto Alegre', '', '555132313415', ''),
(50, 'citoson serviaos auxiliares do diagnaostico maedico', '-30.02974470', '-51.22431680', '', 'rua dos andradas 1711', '', 'Porto Alegre', '', '555132284061', ''),
(51, 'art medical produtos maedicohospitalares', '-30.04900330', '-51.20639410', '', 'r domingos crescaencio 394', '', 'Porto Alegre', '', '555132313415', ''),
(52, 'consultaorio maedico leandro branchtein', '-30.02963650', '-51.20351770', '', 'rua da laura 226 cj 404', '', 'Porto Alegre', '', '555133332564', ''),
(53, 'inbphport indaustria brasileira de equipamentos maedico esportivo  flo', '-30.00396960', '-51.20402590', '', 'rua santos dumont 1766', '', 'Porto Alegre', '', '555133586900', ''),
(54, 'consultaorio maedico gastro intestinal e cirur geral  independaencia', '-30.02834310', '-51.20815520', '', 'pc jaulio castilhos 20 cj 304', '', 'Porto Alegre', '', '555133112008', ''),
(55, 'unimed porto alegre sociedade cooperativa de trabalho maedico ltda  mo', '-30.02421080', '-51.20114800', '', 'rua olavo barreto viana 100', '', 'Porto Alegre', '', '555132644793', ''),
(56, 'b  v distrib de medicamentos e material maedico hospitalar', '-30.01132130', '-51.20563130', '', 'av s paulo 969', '', 'Porto Alegre', '', '555133234500', ''),
(57, 'freda roberto  maedico oftalmologista  auxiliadora', '-30.02170490', '-51.19693800', '', 'r cel bordine 414', '', 'Porto Alegre', '', '555133370505', ''),
(58, 'dental brasil sul distribuidora medico hospitalar e odontologica ltda', '-30.03048330', '-51.22508880', '', 'r vitorinogal 169', '', 'Porto Alegre', '', '555133304561', ''),
(59, 'instramed ind maedico hospitalar  rio branco', '-30.03985420', '-51.17652720', '', 'av protaasio alves 3371', '', 'Porto Alegre', '', '555133344199', ''),
(60, 'cond edif centro medico albert sabin  auxiliadora', '-30.02579800', '-51.19643930', '', 'r bordinicel 830', '', 'Porto Alegre', '', '555133320956', ''),
(61, 'consultaorio maedico oftamologia', '-30.03464710', '-51.21765840', '', 'rua vig josae inaacio 263 sl 61', '', 'Porto Alegre', '', '555132129330', ''),
(62, 'wma comaercio de produtos e equipamentos maedico hospital  menino deus', '-30.04707330', '-51.22600500', '', 'rua 17 junho 389', '', 'Porto Alegre', '', '555132240917', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_evento`
--

CREATE TABLE IF NOT EXISTS `tipo_evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `prazo` int(11) NOT NULL COMMENT 'Prazo em Dias',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tipo_evento`
--

INSERT INTO `tipo_evento` (`id`, `descricao`, `prazo`) VALUES
(1, 'Tatuagem', 365),
(2, 'Piercing', 365);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `demandas`
--
ALTER TABLE `demandas`
  ADD CONSTRAINT `FK_2` FOREIGN KEY (`id_local`) REFERENCES `locais` (`id`),
  ADD CONSTRAINT `FK_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id`);

--
-- Limitadores para a tabela `doacao`
--
ALTER TABLE `doacao`
  ADD CONSTRAINT `FK_6` FOREIGN KEY (`id_local`) REFERENCES `locais` (`id`),
  ADD CONSTRAINT `FK_4` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id`),
  ADD CONSTRAINT `FK_5` FOREIGN KEY (`id_demanda`) REFERENCES `demandas` (`id`);

--
-- Limitadores para a tabela `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `FK_3` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
