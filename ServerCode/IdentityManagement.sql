CREATE DATABASE IF NOT EXISTS identity;

CREATE TABLE IF NOT EXISTS `identity`.`user` (
  `UserID` INT NOT NULL UNIQUE AUTO_INCREMENT,
  `FirstName` VARCHAR(50) NOT NULL,
  `LastName` VARCHAR(50) NOT NULL,
  `Email` VARCHAR(255) NOT NULL,
  `PasswordHash` VARBINARY(64) NOT NULL,
  `Salt` VARCHAR(36) NOT NULL,
  `LockedUntil` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`UserID`));

CREATE TABLE IF NOT EXISTS `identity`.`face` (
  `FaceID` INT NOT NULL UNIQUE AUTO_INCREMENT,
  `UserID` INT NOT NULL,
  `File` VARCHAR(255) NOT NULL,
  `Created` DATETIME NOT NULL,
  PRIMARY KEY (`FaceID`),
  CONSTRAINT `FaceUserID` FOREIGN KEY (`UserID`)
    REFERENCES `user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE IF NOT EXISTS `identity`.`fingerprint` (
  `FingerprintID` INT NOT NULL UNIQUE AUTO_INCREMENT,
  `UserID` INT NOT NULL,
  `File` VARCHAR(255) NOT NULL,
  `Created` DATETIME NOT NULL,
  PRIMARY KEY (`FingerprintID`),
  CONSTRAINT `FingerprintUserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE IF NOT EXISTS `identity`.`iris` (
  `IrisID` INT NOT NULL UNIQUE AUTO_INCREMENT,
  `UserID` INT NOT NULL,
  `File` VARCHAR(255) NOT NULL,
  `Created` DATETIME NOT NULL,
  PRIMARY KEY (`IrisID`),
  CONSTRAINT `IrisUserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE IF NOT EXISTS `identity`.`login` (
  `LoginID` INT NOT NULL UNIQUE AUTO_INCREMENT,
  `UserID` INT NOT NULL,
  `Website` VARCHAR(255) NOT NULL,
  `Created` DATETIME NOT NULL,
  `Successful` BIT(1) NOT NULL,
  `LoginType` INT NOT NULL,
  `File` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`LoginID`),
  CONSTRAINT `LoginUserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE IF NOT EXISTS `identity`.`voice` (
  `VoiceID` INT NOT NULL UNIQUE AUTO_INCREMENT,
  `UserID` INT NOT NULL,
  `File` VARCHAR(255) NOT NULL,
  `Created` DATETIME NOT NULL,
  PRIMARY KEY (`VoiceID`),
  CONSTRAINT `VoiceUserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `identity`.`user` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE USER IF NOT EXISTS 'IMApp'@'%' identified by 't!5@m9N5QFAv8UFU';
GRANT SELECT ON identity.* to 'IMApp'@'%';
GRANT INSERT ON identity.* to 'IMApp'@'%';
GRANT UPDATE ON identity.* to 'IMApp'@'%';
GRANT DELETE ON identity.* to 'IMApp'@'%';
