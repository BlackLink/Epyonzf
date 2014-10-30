/*
Navicat MySQL Data Transfer

Source Server         : EngSoft
Source Server Version : 50616
Source Host           : localhost:3306
Source Database       : epyon

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2014-10-30 01:30:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cidade`
-- ----------------------------
DROP TABLE IF EXISTS `cidade`;
CREATE TABLE `cidade` (
  `idCidade` int(11) NOT NULL AUTO_INCREMENT,
  `nomeCidade` varchar(45) NOT NULL,
  `codEstado` int(11) NOT NULL,
  PRIMARY KEY (`idCidade`),
  KEY `fk_Cidade_Estado1_idx` (`codEstado`),
  CONSTRAINT `fk_Cidade_Estado1` FOREIGN KEY (`codEstado`) REFERENCES `estado` (`idEstado`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cidade
-- ----------------------------

-- ----------------------------
-- Table structure for `cliente`
-- ----------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `E-mail` varchar(50) NOT NULL,
  `codTelefone` int(11) NOT NULL,
  `codEndereco` int(11) NOT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `fk_Cliente_Telefone1_idx` (`codTelefone`),
  KEY `fk_Cliente_Endereco1_idx` (`codEndereco`),
  CONSTRAINT `fk_Cliente_Endereco1` FOREIGN KEY (`codEndereco`) REFERENCES `endereco` (`idEndereco`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Cliente_Telefone1` FOREIGN KEY (`codTelefone`) REFERENCES `telefone` (`idTelefone`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cliente
-- ----------------------------

-- ----------------------------
-- Table structure for `cliente-pessoa-fisica`
-- ----------------------------
DROP TABLE IF EXISTS `cliente-pessoa-fisica`;
CREATE TABLE `cliente-pessoa-fisica` (
  `idCliente-Pessoa-Fisica` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `CPF` varchar(11) NOT NULL,
  `RG` varchar(11) NOT NULL,
  `dataNascimento` date NOT NULL,
  `codCliente` int(11) NOT NULL,
  `codCompra` int(11) NOT NULL,
  `sexo` char(1) NOT NULL,
  PRIMARY KEY (`idCliente-Pessoa-Fisica`),
  KEY `fk_Cliente-Pessoa-Fisica_Cliente1_idx` (`codCliente`),
  KEY `fk_Cliente-Pessoa-Fisica_Compra1_idx` (`codCompra`),
  CONSTRAINT `fk_Cliente-Pessoa-Fisica_Cliente1` FOREIGN KEY (`codCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Cliente-Pessoa-Fisica_Compra1` FOREIGN KEY (`codCompra`) REFERENCES `compra` (`idCompra`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cliente-pessoa-fisica
-- ----------------------------

-- ----------------------------
-- Table structure for `cliente-pessoa-juridica`
-- ----------------------------
DROP TABLE IF EXISTS `cliente-pessoa-juridica`;
CREATE TABLE `cliente-pessoa-juridica` (
  `idCliente-Pessoa-Juridica` int(11) NOT NULL AUTO_INCREMENT,
  `CNPJ` varchar(11) NOT NULL,
  `razaoSocial` varchar(45) NOT NULL,
  `nomeFantasia` varchar(45) NOT NULL,
  `codCliente` int(11) NOT NULL,
  `codCompra` int(11) NOT NULL,
  PRIMARY KEY (`idCliente-Pessoa-Juridica`),
  KEY `fk_Cliente-Pessoa-Juridica_Cliente1_idx` (`codCliente`),
  KEY `fk_Cliente-Pessoa-Juridica_Compra1_idx` (`codCompra`),
  CONSTRAINT `fk_Cliente-Pessoa-Juridica_Cliente1` FOREIGN KEY (`codCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Cliente-Pessoa-Juridica_Compra1` FOREIGN KEY (`codCompra`) REFERENCES `compra` (`idCompra`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cliente-pessoa-juridica
-- ----------------------------

-- ----------------------------
-- Table structure for `compra`
-- ----------------------------
DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `idCompra` int(11) NOT NULL AUTO_INCREMENT,
  `codTipoPlano` int(11) NOT NULL,
  `codDadosCartao` int(11) NOT NULL,
  `codPreCliente` int(11) NOT NULL,
  PRIMARY KEY (`idCompra`),
  KEY `fk_Compra_Tipo-Plano1_idx` (`codTipoPlano`),
  KEY `fk_Compra_Dados-Cartao1_idx` (`codDadosCartao`),
  KEY `fk_Compra_Pre-Cliente1_idx` (`codPreCliente`),
  CONSTRAINT `fk_Compra_Dados-Cartao1` FOREIGN KEY (`codDadosCartao`) REFERENCES `dados-cartao` (`idDados-Cartao`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Compra_Pre-Cliente1` FOREIGN KEY (`codPreCliente`) REFERENCES `pre-cliente` (`idPreCliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Compra_Tipo-Plano1` FOREIGN KEY (`codTipoPlano`) REFERENCES `tipo-plano` (`idTipo-Plano`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of compra
-- ----------------------------

-- ----------------------------
-- Table structure for `dados-cartao`
-- ----------------------------
DROP TABLE IF EXISTS `dados-cartao`;
CREATE TABLE `dados-cartao` (
  `idDados-Cartao` int(11) NOT NULL AUTO_INCREMENT,
  `numeroCartao` int(11) NOT NULL,
  `nomeCartao` varchar(45) NOT NULL,
  `codigoSeguranca` int(11) NOT NULL,
  `dataValidade` date NOT NULL,
  `codTipoCartao` int(11) NOT NULL,
  PRIMARY KEY (`idDados-Cartao`),
  KEY `fk_Dados-Cartao_Tipo-Cartao1_idx` (`codTipoCartao`),
  CONSTRAINT `fk_Dados-Cartao_Tipo-Cartao1` FOREIGN KEY (`codTipoCartao`) REFERENCES `tipo-cartao` (`idTipo-Cartao`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of dados-cartao
-- ----------------------------

-- ----------------------------
-- Table structure for `email-coloboradores`
-- ----------------------------
DROP TABLE IF EXISTS `email-coloboradores`;
CREATE TABLE `email-coloboradores` (
  `idEmail-Coloboradores` int(11) NOT NULL AUTO_INCREMENT,
  `nomeCompleto` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `codCliente` int(11) NOT NULL,
  PRIMARY KEY (`idEmail-Coloboradores`),
  KEY `fk_Email-Coloboradores_Cliente-Pessoa-Fisica1_idx` (`codCliente`),
  CONSTRAINT `fk_Email-Coloboradores_Cliente-Pessoa-Fisica1` FOREIGN KEY (`codCliente`) REFERENCES `cliente-pessoa-fisica` (`idCliente-Pessoa-Fisica`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Email-Coloboradores_Cliente-Pessoa-Juridica1` FOREIGN KEY (`codCliente`) REFERENCES `cliente-pessoa-juridica` (`idCliente-Pessoa-Juridica`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of email-coloboradores
-- ----------------------------

-- ----------------------------
-- Table structure for `endereco`
-- ----------------------------
DROP TABLE IF EXISTS `endereco`;
CREATE TABLE `endereco` (
  `idEndereco` int(11) NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(50) NOT NULL,
  `complemento` varchar(45) NOT NULL,
  `numero` int(11) NOT NULL,
  `bairro` varchar(30) NOT NULL,
  `codCidade` int(11) NOT NULL,
  `codPais` int(11) NOT NULL,
  `codEstado` int(11) NOT NULL,
  `cep` varchar(9) NOT NULL,
  PRIMARY KEY (`idEndereco`),
  KEY `fk_Endereco_Pais1_idx` (`codPais`),
  KEY `fk_Endereco_Estado1_idx` (`codEstado`),
  KEY `fk_Endereco_Cidade1_idx` (`codCidade`),
  CONSTRAINT `fk_Endereco_Cidade1` FOREIGN KEY (`codCidade`) REFERENCES `cidade` (`idCidade`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Endereco_Estado1` FOREIGN KEY (`codEstado`) REFERENCES `estado` (`idEstado`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Endereco_Pais1` FOREIGN KEY (`codPais`) REFERENCES `pais` (`idPais`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of endereco
-- ----------------------------

-- ----------------------------
-- Table structure for `esp-projetos`
-- ----------------------------
DROP TABLE IF EXISTS `esp-projetos`;
CREATE TABLE `esp-projetos` (
  `idEsp-Projetos` int(11) NOT NULL AUTO_INCREMENT,
  `custoUsuario` float NOT NULL,
  `esforcoUsuario` float NOT NULL,
  `prazoUsuario` float NOT NULL,
  `outros_custos` float NOT NULL,
  `num_casos_usos` int(11) NOT NULL,
  `fluxos_casos_usos` int(11) NOT NULL,
  `linhas_casos_uso` int(11) NOT NULL,
  `num_interfaces` int(11) NOT NULL,
  `num_sub_interfaces` int(11) NOT NULL,
  `num_elem_interfaces` int(11) NOT NULL,
  `num_mod_usuarios` int(11) NOT NULL,
  `num_saidas` int(11) NOT NULL,
  `num_geradores_result` int(11) NOT NULL,
  `idProjetos` int(11) NOT NULL,
  `tipo-projeto` char(1) NOT NULL,
  PRIMARY KEY (`idEsp-Projetos`)
  
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of esp-projetos
-- ----------------------------

-- ----------------------------
-- Table structure for `estado`
-- ----------------------------
DROP TABLE IF EXISTS `estado`;
CREATE TABLE `estado` (
  `idEstado` int(11) NOT NULL AUTO_INCREMENT,
  `nomeEstado` varchar(2) NOT NULL,
  `codPais` int(11) NOT NULL,
  PRIMARY KEY (`idEstado`),
  KEY `fk_Estado_Pais1_idx` (`codPais`),
  CONSTRAINT `fk_Estado_Pais1` FOREIGN KEY (`codPais`) REFERENCES `pais` (`idPais`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of estado
-- ----------------------------

-- ----------------------------
-- Table structure for `estimativas`
-- ----------------------------
DROP TABLE IF EXISTS `estimativas`;
CREATE TABLE `estimativas` (
  `idEstimativas` int(11) NOT NULL AUTO_INCREMENT,
  `tempo` varchar(45) NOT NULL,
  `custo` varchar(45) NOT NULL,
  `esforço` varchar(45) NOT NULL,
  `codProjeto` int(11) NOT NULL,
  PRIMARY KEY (`idEstimativas`),
  KEY `fk_Estimativas_Projetos1_idx` (`codProjeto`),
  CONSTRAINT `fk_Estimativas_Projetos1` FOREIGN KEY (`codProjeto`) REFERENCES `projetos` (`idProjetos`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of estimativas
-- ----------------------------

-- ----------------------------
-- Table structure for `login`
-- ----------------------------
DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `idLogin` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `codCliente` int(11) NOT NULL,
  PRIMARY KEY (`idLogin`),
  KEY `fk_Login_Cliente-Pessoa-Fisica1_idx` (`codCliente`),
  CONSTRAINT `fk_Login_Cliente-Pessoa-Fisica1` FOREIGN KEY (`codCliente`) REFERENCES `cliente-pessoa-fisica` (`idCliente-Pessoa-Fisica`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Login_Cliente-Pessoa-Juridica1` FOREIGN KEY (`codCliente`) REFERENCES `cliente-pessoa-juridica` (`idCliente-Pessoa-Juridica`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of login
-- ----------------------------

-- ----------------------------
-- Table structure for `pais`
-- ----------------------------
DROP TABLE IF EXISTS `pais`;
CREATE TABLE `pais` (
  `idPais` int(11) NOT NULL AUTO_INCREMENT,
  `nomePais` varchar(20) NOT NULL COMMENT 'Nome do País.',
  `nacionalidade` varchar(20) NOT NULL,
  PRIMARY KEY (`idPais`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pais
-- ----------------------------

-- ----------------------------
-- Table structure for `pre-cliente`
-- ----------------------------
DROP TABLE IF EXISTS `pre-cliente`;
CREATE TABLE `pre-cliente` (
  `idPreCliente` int(11) NOT NULL AUTO_INCREMENT,
  `Nome Completo` varchar(45) NOT NULL,
  `E-mail` varchar(50) NOT NULL,
  PRIMARY KEY (`idPreCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pre-cliente
-- ----------------------------


-- ----------------------------
-- Table structure for `esp-projetos-extras`
-- ----------------------------
DROP TABLE IF EXISTS `esp-projetos-extras`;
CREATE TABLE `esp-projetos-extras` (
  `idEsp-Projetos-Extras` int(11) NOT NULL AUTO_INCREMENT,
  `nivel-colaborador` float NOT NULL,
  `quant-hora` float NOT NULL,
  `valor-hora` float NOT NULL,
  PRIMARY KEY (`idEsp-Projetos-Extras`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of esp-projetos-extras
-- ----------------------------

-- ----------------------------
-- Table structure for `projetos`
-- ----------------------------
DROP TABLE IF EXISTS `projetos`;
CREATE TABLE `projetos` (
  `idProjetos` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `descricao` text NOT NULL,
  `dataInicio` date NOT NULL,
  `dataFim` date NOT NULL,
  `codCliente` int(11) NOT NULL,
  `codEspProj` int(11) NOT NULL,
  `codEspProjExtra` int(11) NOT NULL,
  PRIMARY KEY (`idProjetos`),

  KEY `fk_Projetos_Cliente1_idx` (`codCliente`),
  CONSTRAINT `fk_Projetos_Cliente1` FOREIGN KEY (`codCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE ON UPDATE CASCADE,

  KEY `fk_Projetos_Esp-Projetos_idx` (`codEspProj`),
  CONSTRAINT `fk_Esp-Projetos_Projetos` FOREIGN KEY (`codEspProj`) REFERENCES `esp-projetos` (`idEsp-Projetos`) ON DELETE CASCADE ON UPDATE CASCADE,

  KEY `fk_Projetos_Esp-Projetos-Extra_idx` (`codEspProjExtra`),
  CONSTRAINT `fk_Esp-Projetos-Extras_Projetos` FOREIGN KEY (`codEspProjExtra`) REFERENCES `esp-projetos-extras` (`idEsp-Projetos-Extras`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of projetos
-- ----------------------------

-- ----------------------------
-- Table structure for `telefone`
-- ----------------------------
DROP TABLE IF EXISTS `telefone`;
CREATE TABLE `telefone` (
  `idTelefone` int(11) NOT NULL AUTO_INCREMENT,
  `telefone` varchar(15) NOT NULL,
  `celular` varchar(15) NOT NULL,
  PRIMARY KEY (`idTelefone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of telefone
-- ----------------------------

-- ----------------------------
-- Table structure for `tipo-cartao`
-- ----------------------------
DROP TABLE IF EXISTS `tipo-cartao`;
CREATE TABLE `tipo-cartao` (
  `idTipo-Cartao` int(11) NOT NULL AUTO_INCREMENT,
  `operadoraCartao` varchar(20) NOT NULL,
  PRIMARY KEY (`idTipo-Cartao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tipo-cartao
-- ----------------------------

-- ----------------------------
-- Table structure for `tipo-plano`
-- ----------------------------
DROP TABLE IF EXISTS `tipo-plano`;
CREATE TABLE `tipo-plano` (
  `idTipo-Plano` int(11) NOT NULL AUTO_INCREMENT,
  `plano` varchar(10) NOT NULL,
  `valorPlano` decimal(10,0) NOT NULL,
  PRIMARY KEY (`idTipo-Plano`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tipo-plano
-- ----------------------------
