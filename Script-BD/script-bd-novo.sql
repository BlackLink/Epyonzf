SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `epyon`.`pais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`pais` (
  `idPais` INT(11) NOT NULL AUTO_INCREMENT,
  `nomePais` VARCHAR(20) NOT NULL COMMENT 'Nome do País.',
  `nacionalidade` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`idPais`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`estado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`estado` (
  `idEstado` INT(11) NOT NULL AUTO_INCREMENT,
  `nomeEstado` VARCHAR(2) NOT NULL,
  `codPais` INT(11) NOT NULL,
  PRIMARY KEY (`idEstado`),
  INDEX `fk_Estado_Pais1_idx` (`codPais` ASC),
  CONSTRAINT `fk_Estado_Pais1`
    FOREIGN KEY (`codPais`)
    REFERENCES `epyon`.`pais` (`idPais`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`cidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`cidade` (
  `idCidade` INT(11) NOT NULL AUTO_INCREMENT,
  `nomeCidade` VARCHAR(45) NOT NULL,
  `codEstado` INT(11) NOT NULL,
  PRIMARY KEY (`idCidade`),
  INDEX `fk_Cidade_Estado1_idx` (`codEstado` ASC),
  CONSTRAINT `fk_Cidade_Estado1`
    FOREIGN KEY (`codEstado`)
    REFERENCES `epyon`.`estado` (`idEstado`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`endereco` (
  `idEndereco` INT(11) NOT NULL AUTO_INCREMENT,
  `logradouro` VARCHAR(50) NOT NULL,
  `complemento` VARCHAR(45) NOT NULL,
  `numero` INT(11) NOT NULL,
  `bairro` VARCHAR(30) NOT NULL,
  `codCidade` INT(11) NOT NULL,
  `codPais` INT(11) NOT NULL,
  `codEstado` INT(11) NOT NULL,
  `cep` VARCHAR(9) NOT NULL,
  PRIMARY KEY (`idEndereco`),
  INDEX `fk_Endereco_Pais1_idx` (`codPais` ASC),
  INDEX `fk_Endereco_Estado1_idx` (`codEstado` ASC),
  INDEX `fk_Endereco_Cidade1_idx` (`codCidade` ASC),
  CONSTRAINT `fk_Endereco_Cidade1`
    FOREIGN KEY (`codCidade`)
    REFERENCES `epyon`.`cidade` (`idCidade`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Endereco_Estado1`
    FOREIGN KEY (`codEstado`)
    REFERENCES `epyon`.`estado` (`idEstado`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Endereco_Pais1`
    FOREIGN KEY (`codPais`)
    REFERENCES `epyon`.`pais` (`idPais`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`telefone`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`telefone` (
  `idTelefone` INT(11) NOT NULL AUTO_INCREMENT,
  `telefone` VARCHAR(15) NOT NULL,
  `celular` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`idTelefone`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`cliente` (
  `idCliente` INT(11) NOT NULL AUTO_INCREMENT,
  `E-mail` VARCHAR(50) NOT NULL,
  `codTelefone` INT(11) NOT NULL,
  `codEndereco` INT(11) NOT NULL,
  PRIMARY KEY (`idCliente`),
  INDEX `fk_Cliente_Telefone1_idx` (`codTelefone` ASC),
  INDEX `fk_Cliente_Endereco1_idx` (`codEndereco` ASC),
  CONSTRAINT `fk_Cliente_Endereco1`
    FOREIGN KEY (`codEndereco`)
    REFERENCES `epyon`.`endereco` (`idEndereco`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cliente_Telefone1`
    FOREIGN KEY (`codTelefone`)
    REFERENCES `epyon`.`telefone` (`idTelefone`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`tipo-cartao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`tipo-cartao` (
  `idTipo-Cartao` INT(11) NOT NULL AUTO_INCREMENT,
  `operadoraCartao` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`idTipo-Cartao`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`dados-cartao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`dados-cartao` (
  `idDados-Cartao` INT(11) NOT NULL AUTO_INCREMENT,
  `numeroCartao` INT(11) NOT NULL,
  `nomeCartao` VARCHAR(45) NOT NULL,
  `codigoSeguranca` INT(11) NOT NULL,
  `dataValidade` VARCHAR(4) NOT NULL,
  `codTipoCartao` INT(11) NOT NULL,
  PRIMARY KEY (`idDados-Cartao`),
  INDEX `fk_Dados-Cartao_Tipo-Cartao1_idx` (`codTipoCartao` ASC),
  CONSTRAINT `fk_Dados-Cartao_Tipo-Cartao1`
    FOREIGN KEY (`codTipoCartao`)
    REFERENCES `epyon`.`tipo-cartao` (`idTipo-Cartao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`pre-cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`pre-cliente` (
  `idPreCliente` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome Completo` VARCHAR(45) NOT NULL,
  `E-mail` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idPreCliente`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`tipo-plano`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`tipo-plano` (
  `idTipo-Plano` INT(11) NOT NULL AUTO_INCREMENT,
  `plano` VARCHAR(10) NOT NULL,
  `valorPlano` DECIMAL(10,0) NOT NULL,
  PRIMARY KEY (`idTipo-Plano`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`compra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`compra` (
  `idCompra` INT(11) NOT NULL AUTO_INCREMENT,
  `codTipoPlano` INT(11) NOT NULL,
  `codDadosCartao` INT(11) NOT NULL,
  `codPreCliente` INT(11) NOT NULL,
  PRIMARY KEY (`idCompra`),
  INDEX `fk_Compra_Tipo-Plano1_idx` (`codTipoPlano` ASC),
  INDEX `fk_Compra_Dados-Cartao1_idx` (`codDadosCartao` ASC),
  INDEX `fk_Compra_Pre-Cliente1_idx` (`codPreCliente` ASC),
  CONSTRAINT `fk_Compra_Dados-Cartao1`
    FOREIGN KEY (`codDadosCartao`)
    REFERENCES `epyon`.`dados-cartao` (`idDados-Cartao`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Compra_Pre-Cliente1`
    FOREIGN KEY (`codPreCliente`)
    REFERENCES `epyon`.`pre-cliente` (`idPreCliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Compra_Tipo-Plano1`
    FOREIGN KEY (`codTipoPlano`)
    REFERENCES `epyon`.`tipo-plano` (`idTipo-Plano`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`cliente-pessoa-fisica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`cliente-pessoa-fisica` (
  `idCliente-Pessoa-Fisica` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `CPF` VARCHAR(11) NOT NULL,
  `RG` VARCHAR(11) NOT NULL,
  `dataNascimento` DATE NOT NULL,
  `codCliente` INT(11) NOT NULL,
  `codCompra` INT(11) NOT NULL,
  `sexo` CHAR(1) NOT NULL,
  PRIMARY KEY (`idCliente-Pessoa-Fisica`),
  INDEX `fk_Cliente-Pessoa-Fisica_Cliente1_idx` (`codCliente` ASC),
  INDEX `fk_Cliente-Pessoa-Fisica_Compra1_idx` (`codCompra` ASC),
  CONSTRAINT `fk_Cliente-Pessoa-Fisica_Cliente1`
    FOREIGN KEY (`codCliente`)
    REFERENCES `epyon`.`cliente` (`idCliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cliente-Pessoa-Fisica_Compra1`
    FOREIGN KEY (`codCompra`)
    REFERENCES `epyon`.`compra` (`idCompra`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`cliente-pessoa-juridica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`cliente-pessoa-juridica` (
  `idCliente-Pessoa-Juridica` INT(11) NOT NULL AUTO_INCREMENT,
  `CNPJ` VARCHAR(11) NOT NULL,
  `razaoSocial` VARCHAR(45) NOT NULL,
  `nomeFantasia` VARCHAR(45) NOT NULL,
  `codCliente` INT(11) NOT NULL,
  `codCompra` INT(11) NOT NULL,
  PRIMARY KEY (`idCliente-Pessoa-Juridica`),
  INDEX `fk_Cliente-Pessoa-Juridica_Cliente1_idx` (`codCliente` ASC),
  INDEX `fk_Cliente-Pessoa-Juridica_Compra1_idx` (`codCompra` ASC),
  CONSTRAINT `fk_Cliente-Pessoa-Juridica_Cliente1`
    FOREIGN KEY (`codCliente`)
    REFERENCES `epyon`.`cliente` (`idCliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Cliente-Pessoa-Juridica_Compra1`
    FOREIGN KEY (`codCompra`)
    REFERENCES `epyon`.`compra` (`idCompra`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`email-colaboradores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`email-colaboradores` (
  `idEmail-Colaboradores` INT(11) NOT NULL AUTO_INCREMENT,
  `nomeCompleto` VARCHAR(100) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `codCliente` INT(11) NOT NULL,
  PRIMARY KEY (`idEmail-colaboradores`),
  INDEX `fk_email-colaboradores_cliente1_idx` (`codCliente` ASC),
  CONSTRAINT `fk_email-colaboradores_cliente1`
    FOREIGN KEY (`codCliente`)
    REFERENCES `epyon`.`cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`esp-projetos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`esp-projetos` (
  `idEsp-Projetos` INT(11) NOT NULL AUTO_INCREMENT,
  `custoUsuario` FLOAT NOT NULL,
  `esforcoUsuario` FLOAT NOT NULL,
  `prazoUsuario` FLOAT NOT NULL,
  `outros_custos` FLOAT NOT NULL,
  `num_casos_usos` INT(11) NOT NULL,
  `fluxos_casos_usos` INT(11) NOT NULL,
  `linhas_casos_uso` INT(11) NOT NULL,
  `num_interfaces` INT(11) NOT NULL,
  `num_sub_interfaces` INT(11) NOT NULL,
  `num_elem_interfaces` INT(11) NOT NULL,
  `num_mod_usuarios` INT(11) NOT NULL,
  `num_saidas` INT(11) NOT NULL,
  `num_geradores_result` INT(11) NOT NULL,
  `idProjetos` INT(11) NOT NULL,
  `tipo-projeto` CHAR(1) NOT NULL,
  PRIMARY KEY (`idEsp-Projetos`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`esp-projetos-extras`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`esp-projetos-extras` (
  `idEsp-Projetos-Extras` INT(11) NOT NULL AUTO_INCREMENT,
  `nivel-colaborador` FLOAT NOT NULL,
  `quant-hora` FLOAT NOT NULL,
  `valor-hora` FLOAT NOT NULL,
  PRIMARY KEY (`idEsp-Projetos-Extras`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`projetos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`projetos` (
  `idProjetos` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `descricao` TEXT NOT NULL,
  `dataInicio` DATE NOT NULL,
  `dataFim` DATE NOT NULL,
  `codCliente` INT(11) NOT NULL,
  `codEspProj` INT(11) NOT NULL,
  `codEspProjExtra` INT(11) NOT NULL,
  PRIMARY KEY (`idProjetos`),
  INDEX `fk_Projetos_Cliente1_idx` (`codCliente` ASC),
  INDEX `fk_Projetos_Esp-Projetos_idx` (`codEspProj` ASC),
  INDEX `fk_Projetos_Esp-Projetos-Extra_idx` (`codEspProjExtra` ASC),
  CONSTRAINT `fk_Esp-Projetos-Extras_Projetos`
    FOREIGN KEY (`codEspProjExtra`)
    REFERENCES `epyon`.`esp-projetos-extras` (`idEsp-Projetos-Extras`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Esp-Projetos_Projetos`
    FOREIGN KEY (`codEspProj`)
    REFERENCES `epyon`.`esp-projetos` (`idEsp-Projetos`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Projetos_Cliente1`
    FOREIGN KEY (`codCliente`)
    REFERENCES `epyon`.`cliente` (`idCliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`estimativas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`estimativas` (
  `idEstimativas` INT(11) NOT NULL AUTO_INCREMENT,
  `tempo` VARCHAR(45) NOT NULL,
  `custo` VARCHAR(45) NOT NULL,
  `esforço` VARCHAR(45) NOT NULL,
  `codProjeto` INT(11) NOT NULL,
  PRIMARY KEY (`idEstimativas`),
  INDEX `fk_Estimativas_Projetos1_idx` (`codProjeto` ASC),
  CONSTRAINT `fk_Estimativas_Projetos1`
    FOREIGN KEY (`codProjeto`)
    REFERENCES `epyon`.`projetos` (`idProjetos`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `epyon`.`login`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `epyon`.`login` (
  `idLogin` INT(11) NOT NULL AUTO_INCREMENT,
  `user` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `codCliente` INT(11) NOT NULL,
  PRIMARY KEY (`idLogin`),
  INDEX `fk_login_cliente1_idx` (`codCliente` ASC),
  CONSTRAINT `fk_login_cliente1`
    FOREIGN KEY (`codCliente`)
    REFERENCES `epyon`.`cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
