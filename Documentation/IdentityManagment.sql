-- MySQL Script generated by MySQL Workbench
-- Sun Jan 24 23:34:56 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema identity
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema identity
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `identity` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `identity` ;

-- -----------------------------------------------------
-- Table `identity`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `identity`.`user` (
  `UserID` INT NOT NULL,
  `FIrstName` VARCHAR(50) NULL DEFAULT NULL,
  `LastName` VARCHAR(50) NULL DEFAULT NULL,
  `Email` VARCHAR(255) NULL DEFAULT NULL,
  `PasswordHash` VARBINARY(64) NULL DEFAULT NULL,
  `Salt` VARCHAR(36) NULL DEFAULT NULL,
  `LockedUntil` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`UserID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `identity`.`face`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `identity`.`face` (
  `FaceID` INT NOT NULL,
  `UserID` INT NOT NULL,
  `FIle` VARCHAR(255) NULL DEFAULT NULL,
  `Created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`FaceID`),
  INDEX `UserID_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `UserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `identity`.`fingerprint`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `identity`.`fingerprint` (
  `FingerprintID` INT NOT NULL,
  `UserID` INT NOT NULL,
  `File` VARCHAR(255) NULL DEFAULT NULL,
  `Created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`FingerprintID`),
  INDEX `UserID_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `UserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `identity`.`iris`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `identity`.`iris` (
  `IrisID` INT NOT NULL,
  `UserID` INT NULL DEFAULT NULL,
  `File` VARCHAR(255) NULL DEFAULT NULL,
  `Created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`IrisID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `identity`.`login`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `identity`.`login` (
  `LoginID` INT NOT NULL,
  `UserID` INT NOT NULL,
  `Website` VARCHAR(255) NULL DEFAULT NULL,
  `Created` DATETIME NULL DEFAULT NULL,
  `Successful` BIT(1) NULL DEFAULT NULL,
  `LoginType` INT NULL DEFAULT NULL,
  `File` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`LoginID`),
  INDEX `UserID_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `UserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `identity`.`voice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `identity`.`voice` (
  `VoiceID` INT NOT NULL,
  `UserID` INT NOT NULL,
  `File` VARCHAR(255) NULL DEFAULT NULL,
  `Created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`VoiceID`),
  INDEX `UserID_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `UserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;