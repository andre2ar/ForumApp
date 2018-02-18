-- MySQL Script generated by MySQL Workbench
-- Sat Feb 17 16:01:19 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema formApp
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `formApp` ;

-- -----------------------------------------------------
-- Schema formApp
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `formApp` DEFAULT CHARACTER SET utf8 ;
USE `formApp` ;

-- -----------------------------------------------------
-- Table `formApp`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `formApp`.`users` ;

CREATE TABLE IF NOT EXISTS `formApp`.`users` (
  `userId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userName` VARCHAR(100) NOT NULL,
  `userEmail` VARCHAR(200) NOT NULL,
  `userPassword` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE INDEX `idusers_UNIQUE` (`userId` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `formApp`.`posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `formApp`.`posts` ;

CREATE TABLE IF NOT EXISTS `formApp`.`posts` (
  `postId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `postTitle` VARCHAR(300) NOT NULL,
  `postDetails` MEDIUMTEXT NULL,
  `postCategory` VARCHAR(45) NOT NULL,
  `postOwner` INT UNSIGNED NOT NULL,
  `postCreationTime` TIMESTAMP NULL,
  PRIMARY KEY (`postId`),
  UNIQUE INDEX `postId_UNIQUE` (`postId` ASC),
  INDEX `fk_posts_users_idx` (`postOwner` ASC),
  CONSTRAINT `fk_posts_users`
    FOREIGN KEY (`postOwner`)
    REFERENCES `formApp`.`users` (`userId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `formApp`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `formApp`.`comments` ;

CREATE TABLE IF NOT EXISTS `formApp`.`comments` (
  `commentId` INT UNSIGNED NOT NULL,
  `commentText` MEDIUMTEXT NOT NULL,
  `commentOwner` INT UNSIGNED NOT NULL,
  `commentInPost` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`commentId`),
  UNIQUE INDEX `commentId_UNIQUE` (`commentId` ASC),
  INDEX `fk_comments_users1_idx` (`commentOwner` ASC),
  INDEX `fk_comments_posts1_idx` (`commentInPost` ASC),
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`commentOwner`)
    REFERENCES `formApp`.`users` (`userId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_posts1`
    FOREIGN KEY (`commentInPost`)
    REFERENCES `formApp`.`posts` (`postId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
