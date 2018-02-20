-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema forumApp
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema forumapp
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `forumapp` ;

-- -----------------------------------------------------
-- Schema forumapp
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `forumapp` DEFAULT CHARACTER SET utf8 ;
USE `forumapp` ;

-- -----------------------------------------------------
-- Table `forumapp`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forumapp`.`users` ;

CREATE TABLE IF NOT EXISTS `forumapp`.`users` (
  `userId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userName` VARCHAR(100) NOT NULL,
  `userEmail` VARCHAR(200) NOT NULL,
  `userPassword` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE INDEX `idusers_UNIQUE` (`userId` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `forumapp`.`questions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forumapp`.`questions` ;

CREATE TABLE IF NOT EXISTS `forumapp`.`questions` (
  `questionId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `questionTitle` VARCHAR(300) NOT NULL,
  `questionDetails` MEDIUMTEXT NULL DEFAULT NULL,
  `questionCategory` VARCHAR(45) NOT NULL,
  `questionOwner` INT(10) UNSIGNED NOT NULL,
  `questionCreationTime` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`questionId`),
  UNIQUE INDEX `postId_UNIQUE` (`questionId` ASC),
  INDEX `fk_posts_users_idx` (`questionOwner` ASC),
  CONSTRAINT `fk_posts_users`
    FOREIGN KEY (`questionOwner`)
    REFERENCES `forumapp`.`users` (`userId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `forumapp`.`answers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `forumapp`.`answers` ;

CREATE TABLE IF NOT EXISTS `forumapp`.`answers` (
  `answertId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `answerText` MEDIUMTEXT NOT NULL,
  `answerOwner` INT(10) UNSIGNED NOT NULL,
  `answerInQuestion` INT(10) UNSIGNED NOT NULL,
  `answerCreationTime` TIMESTAMP NULL,
  PRIMARY KEY (`answerId`),
  UNIQUE INDEX `commentId_UNIQUE` (`answertId` ASC),
  INDEX `fk_comments_users1_idx` (`answerOwner` ASC),
  INDEX `fk_comments_posts1_idx` (`answerInQuestion` ASC),
  INDEX `commentId` (`answertId` ASC),
  CONSTRAINT `fk_comments_posts1`
    FOREIGN KEY (`answerInQuestion`)
    REFERENCES `forumapp`.`questions` (`questionId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`answerOwner`)
    REFERENCES `forumapp`.`users` (`userId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 14
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
