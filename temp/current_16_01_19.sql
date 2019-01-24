-- --------------------------------------------------------
-- Host:                         149.156.136.151
-- Wersja serwera:               5.5.54 - MySQL Community Server (GPL)
-- Serwer OS:                    Linux
-- HeidiSQL Wersja:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Zrzut struktury bazy danych mjosek
CREATE DATABASE IF NOT EXISTS `mjosek` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `mjosek`;

-- Zrzut struktury tabela mjosek.Address
CREATE TABLE IF NOT EXISTS `Address` (
  `id_address` int(11) NOT NULL AUTO_INCREMENT,
  `street` varchar(45) NOT NULL,
  `number` varchar(45) NOT NULL,
  `id_city` int(11) NOT NULL,
  `postal_code` varchar(45) NOT NULL,
  PRIMARY KEY (`id_address`),
  KEY `fkIdx_152` (`id_city`),
  CONSTRAINT `FK_152` FOREIGN KEY (`id_city`) REFERENCES `City` (`id_city`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Address: ~3 rows (około)
/*!40000 ALTER TABLE `Address` DISABLE KEYS */;
INSERT INTO `Address` (`id_address`, `street`, `number`, `id_city`, `postal_code`) VALUES
	(1, 'Testowa', '2', 1, '3-340'),
	(2, 'Monitor', '3', 2, '12-340'),
	(3, 'Wierzbowa', '1293', 2, '30-293');
/*!40000 ALTER TABLE `Address` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Casts
CREATE TABLE IF NOT EXISTS `Casts` (
  `id_cast` int(11) NOT NULL AUTO_INCREMENT,
  `id_role_type` int(11) NOT NULL,
  `id_person` int(11) NOT NULL,
  `id_character` int(11) NOT NULL,
  `id_show` int(11) NOT NULL,
  PRIMARY KEY (`id_cast`),
  KEY `fkIdx_246` (`id_role_type`),
  KEY `fkIdx_249` (`id_person`),
  KEY `fkIdx_252` (`id_character`),
  KEY `fkIdx_255` (`id_show`),
  CONSTRAINT `FK_246` FOREIGN KEY (`id_role_type`) REFERENCES `Role_type` (`id_role_type`),
  CONSTRAINT `FK_249` FOREIGN KEY (`id_person`) REFERENCES `Person` (`id_person`),
  CONSTRAINT `FK_252` FOREIGN KEY (`id_character`) REFERENCES `Characters` (`id_character`),
  CONSTRAINT `FK_255` FOREIGN KEY (`id_show`) REFERENCES `Shows` (`id_show`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Casts: ~7 rows (około)
/*!40000 ALTER TABLE `Casts` DISABLE KEYS */;
INSERT INTO `Casts` (`id_cast`, `id_role_type`, `id_person`, `id_character`, `id_show`) VALUES
	(1, 1, 1, 1, 1),
	(2, 2, 10, 6, 6),
	(3, 3, 2, 3, 1),
	(4, 1, 4, 5, 1),
	(6, 1, 11, 0, 6),
	(7, 2, 13, 11, 6),
	(8, 3, 9, 1, 6);
/*!40000 ALTER TABLE `Casts` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Characters
CREATE TABLE IF NOT EXISTS `Characters` (
  `id_character` int(11) NOT NULL AUTO_INCREMENT,
  `character_name` varchar(45) NOT NULL,
  `id_spectacle` int(11) NOT NULL,
  PRIMARY KEY (`id_character`),
  KEY `fk_id_spectacle` (`id_spectacle`),
  CONSTRAINT `fk_id_spectacle` FOREIGN KEY (`id_spectacle`) REFERENCES `Spectacle` (`id_spectacle`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Characters: ~11 rows (około)
/*!40000 ALTER TABLE `Characters` DISABLE KEYS */;
INSERT INTO `Characters` (`id_character`, `character_name`, `id_spectacle`) VALUES
	(0, 'Hektor', 5),
	(1, 'Helena', 5),
	(2, 'Shrek', 1),
	(3, 'Aragorn', 7),
	(4, 'Legolas', 7),
	(5, 'Harry Potter', 4),
	(6, 'Armor', 5),
	(7, 'Parys', 5),
	(8, 'Anioł I', 5),
	(10, 'Laban', 5),
	(11, 'Hekuba', 5);
/*!40000 ALTER TABLE `Characters` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.City
CREATE TABLE IF NOT EXISTS `City` (
  `id_city` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(45) NOT NULL,
  PRIMARY KEY (`id_city`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.City: ~3 rows (około)
/*!40000 ALTER TABLE `City` DISABLE KEYS */;
INSERT INTO `City` (`id_city`, `city`) VALUES
	(1, 'Kraków'),
	(2, 'Warszawa'),
	(3, 'Cieszyn');
/*!40000 ALTER TABLE `City` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Comments
CREATE TABLE IF NOT EXISTS `Comments` (
  `id_comment` int(11) NOT NULL AUTO_INCREMENT,
  `comment_text` varchar(1500) NOT NULL,
  `comment_date` datetime NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_spectacle` int(11) NOT NULL,
  `id_reply` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_comment`),
  KEY `fkIdx_195` (`id_user`),
  KEY `fkIdx_198` (`id_spectacle`),
  KEY `fk_id_reply` (`id_reply`),
  CONSTRAINT `fk_id_reply` FOREIGN KEY (`id_reply`) REFERENCES `Comments` (`id_comment`),
  CONSTRAINT `FK_195` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`),
  CONSTRAINT `FK_198` FOREIGN KEY (`id_spectacle`) REFERENCES `Spectacle` (`id_spectacle`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Comments: ~8 rows (około)
/*!40000 ALTER TABLE `Comments` DISABLE KEYS */;
INSERT INTO `Comments` (`id_comment`, `comment_text`, `comment_date`, `id_user`, `id_spectacle`, `id_reply`) VALUES
	(146, 'asd', '2019-01-11 16:07:16', 19, 5, NULL),
	(149, 'ad', '2019-01-11 16:04:06', 21, 5, 146),
	(151, 'asd', '2019-01-11 16:04:33', 21, 5, NULL),
	(155, 'test', '2019-01-11 16:08:15', 21, 5, NULL),
	(156, 'dsa', '2019-01-11 16:08:21', 21, 5, 151),
	(157, 'qwe', '2019-01-11 16:08:27', 21, 5, 156),
	(158, 'ŻÓŁĆ', '2019-01-11 17:03:15', 21, 5, NULL),
	(159, 'asd', '2019-01-13 18:59:43', 21, 5, NULL);
/*!40000 ALTER TABLE `Comments` ENABLE KEYS */;

-- Zrzut struktury funkcja mjosek.convertDatetimeToDateString
DELIMITER //
CREATE DEFINER=`mjosek`@`%` FUNCTION `convertDatetimeToDateString`(
	`vdate` datetime
) RETURNS varchar(40) CHARSET utf8
begin
	return (select DATE_FORMAT(vdate,'%d/%m/%Y'));
end//
DELIMITER ;

-- Zrzut struktury funkcja mjosek.createUrlPathToImg
DELIMITER //
CREATE DEFINER=`mjosek`@`%` FUNCTION `createUrlPathToImg`(vphotoname
varchar(40)) RETURNS varchar(100) CHARSET utf8
begin
	return (select concat('/media/', vphotoname));
end//
DELIMITER ;

-- Zrzut struktury procedura mjosek.createUser
DELIMITER //
CREATE DEFINER=`mjosek`@`%` PROCEDURE `createUser`(
	IN `login` VARCHAR(32),
	IN `hash` VARCHAR(64),
	IN `salt` VARCHAR(64),
	IN `id_user_type` INT,
	IN `email` VARCHAR(45)





)
    DETERMINISTIC
BEGIN
	DECLARE EXIT HANDLER FOR 1062 SELECT 'Login w uzyciu';
 	DECLARE EXIT HANDLER FOR 1216 select 'Probowano naruszyc wiezy integralnosci klucza obcego!';
	DECLARE EXIT HANDLER FOR 1048 SELECT 'Nie mozna wprowadzac wartosci nullowych!';
	DECLARE EXIT HANDLER FOR 1264	SELECT 'Out of range exception';
	DECLARE EXIT HANDLER FOR SQLEXCEPTION SELECT 'Niespodziewany_blad';
	insert into User(login, hash, salt, id_user_type, email) values (login, hash, salt, id_user_type, email);
END//
DELIMITER ;

-- Zrzut struktury tabela mjosek.Crew
CREATE TABLE IF NOT EXISTS `Crew` (
  `id_crew` int(11) NOT NULL AUTO_INCREMENT,
  `id_crew_type` int(11) NOT NULL,
  `id_show` int(11) NOT NULL,
  `id_person` int(11) NOT NULL,
  PRIMARY KEY (`id_crew`),
  KEY `fkIdx_243` (`id_crew_type`),
  KEY `fkIdx_258` (`id_show`),
  KEY `fkIdx_301` (`id_person`),
  CONSTRAINT `FK_243` FOREIGN KEY (`id_crew_type`) REFERENCES `Crew_type` (`id_crew_type`),
  CONSTRAINT `FK_258` FOREIGN KEY (`id_show`) REFERENCES `Shows` (`id_show`),
  CONSTRAINT `FK_301` FOREIGN KEY (`id_person`) REFERENCES `Person` (`id_person`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Crew: ~3 rows (około)
/*!40000 ALTER TABLE `Crew` DISABLE KEYS */;
INSERT INTO `Crew` (`id_crew`, `id_crew_type`, `id_show`, `id_person`) VALUES
	(6, 1, 6, 5),
	(7, 5, 6, 7),
	(8, 4, 6, 8);
/*!40000 ALTER TABLE `Crew` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Crew_type
CREATE TABLE IF NOT EXISTS `Crew_type` (
  `id_crew_type` int(11) NOT NULL AUTO_INCREMENT,
  `kind` varchar(45) NOT NULL,
  PRIMARY KEY (`id_crew_type`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Crew_type: ~8 rows (około)
/*!40000 ALTER TABLE `Crew_type` DISABLE KEYS */;
INSERT INTO `Crew_type` (`id_crew_type`, `kind`) VALUES
	(1, 'Reżyser'),
	(2, 'Operator światła'),
	(3, 'Scenarzysta'),
	(4, 'Scenograf'),
	(5, 'Dramaturg'),
	(6, 'Kostiumy'),
	(7, 'Muzyka'),
	(8, 'Wideo');
/*!40000 ALTER TABLE `Crew_type` ENABLE KEYS */;

-- Zrzut struktury zdarzenie mjosek.delete_old_login_logs
DELIMITER //
CREATE DEFINER=`mjosek`@`%` EVENT `delete_old_login_logs` ON SCHEDULE EVERY 1 DAY STARTS '2019-01-15 01:00:00' ON COMPLETION PRESERVE ENABLE DO BEGIN
	DELETE FROM Login_logs WHERE login_time < DATE_SUB(NOW(), INTERVAL 365 DAY);
END//
DELIMITER ;

-- Zrzut struktury zdarzenie mjosek.delete_old_sessions
DELIMITER //
CREATE DEFINER=`mjosek`@`%` EVENT `delete_old_sessions` ON SCHEDULE EVERY 1 DAY STARTS '2019-01-15 01:00:00' ON COMPLETION PRESERVE ENABLE DO BEGIN
	DELETE FROM Session WHERE login_time < DATE_SUB(NOW(), INTERVAL 30 DAY);
END//
DELIMITER ;

-- Zrzut struktury tabela mjosek.Gender
CREATE TABLE IF NOT EXISTS `Gender` (
  `id_gender` int(11) NOT NULL AUTO_INCREMENT,
  `gender` varchar(10) NOT NULL,
  PRIMARY KEY (`id_gender`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Gender: ~2 rows (około)
/*!40000 ALTER TABLE `Gender` DISABLE KEYS */;
INSERT INTO `Gender` (`id_gender`, `gender`) VALUES
	(1, 'Mężczyzna'),
	(2, 'Kobieta');
/*!40000 ALTER TABLE `Gender` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Genre
CREATE TABLE IF NOT EXISTS `Genre` (
  `id_genre` int(11) NOT NULL AUTO_INCREMENT,
  `genre` varchar(45) NOT NULL,
  PRIMARY KEY (`id_genre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
/*!50100 PARTITION BY RANGE (id_genre)
(PARTITION p0 VALUES LESS THAN (10000) ENGINE = InnoDB,
 PARTITION p1 VALUES LESS THAN (20000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (30000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (40000) ENGINE = InnoDB) */;

-- Zrzucanie danych dla tabeli mjosek.Genre: ~5 rows (około)
/*!40000 ALTER TABLE `Genre` DISABLE KEYS */;
INSERT INTO `Genre` (`id_genre`, `genre`) VALUES
	(1, 'Komedia'),
	(2, 'Dramat');
/*!40000 ALTER TABLE `Genre` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Hall
CREATE TABLE IF NOT EXISTS `Hall` (
  `id_hall` int(11) NOT NULL AUTO_INCREMENT,
  `hall_name` varchar(45) NOT NULL,
  `id_theater` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  PRIMARY KEY (`id_hall`),
  KEY `fkIdx_183` (`id_theater`),
  CONSTRAINT `FK_183` FOREIGN KEY (`id_theater`) REFERENCES `Theater` (`id_theater`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Hall: ~1 rows (około)
/*!40000 ALTER TABLE `Hall` DISABLE KEYS */;
INSERT INTO `Hall` (`id_hall`, `hall_name`, `id_theater`, `capacity`) VALUES
	(1, 'Duża Scena', 1, 200);
/*!40000 ALTER TABLE `Hall` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Login_logs
CREATE TABLE IF NOT EXISTS `Login_logs` (
  `id_login_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `ip_address` varchar(39) DEFAULT NULL,
  `webbrowser` varchar(200) DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_login_log`),
  KEY `fkIDu_login_log` (`id_user`),
  CONSTRAINT `fkIDu_login_log` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Login_logs: ~1 rows (około)
/*!40000 ALTER TABLE `Login_logs` DISABLE KEYS */;
INSERT INTO `Login_logs` (`id_login_log`, `id_user`, `ip_address`, `webbrowser`, `login_time`) VALUES
	(1, 21, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36', '2019-01-16 00:10:07');
/*!40000 ALTER TABLE `Login_logs` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Person
CREATE TABLE IF NOT EXISTS `Person` (
  `id_person` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(45) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `id_gender` int(11) NOT NULL,
  `id_city` int(11) DEFAULT NULL,
  `photo_name` varchar(45) DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `date_of_death` datetime DEFAULT NULL,
  PRIMARY KEY (`id_person`),
  KEY `fkIdx_267` (`id_gender`),
  KEY `fkIdx_270` (`id_city`),
  CONSTRAINT `FK_267` FOREIGN KEY (`id_gender`) REFERENCES `Gender` (`id_gender`),
  CONSTRAINT `FK_270` FOREIGN KEY (`id_city`) REFERENCES `City` (`id_city`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Person: ~11 rows (około)
/*!40000 ALTER TABLE `Person` DISABLE KEYS */;
INSERT INTO `Person` (`id_person`, `lastname`, `firstname`, `id_gender`, `id_city`, `photo_name`, `date_of_birth`, `date_of_death`) VALUES
	(1, 'Bond', 'James', 1, 3, NULL, '2019-01-06 00:49:40', NULL),
	(2, 'Elza', 'Anna', 2, 1, NULL, NULL, NULL),
	(3, 'Słoik', 'Michał', 2, 2, NULL, NULL, NULL),
	(4, 'Górny', 'Krzysztof', 1, 3, NULL, NULL, NULL),
	(5, 'Twarkowski', 'Łukasz', 1, 2, NULL, NULL, NULL),
	(7, 'Herbut', 'Anka', 2, 2, NULL, NULL, NULL),
	(8, 'Choromański', 'Piotr', 1, NULL, NULL, NULL, NULL),
	(9, 'Stoces Mizbeware', 'Marta', 2, NULL, NULL, NULL, NULL),
	(10, 'Brzyski', 'Bogdan', 1, NULL, NULL, NULL, NULL),
	(11, 'Kruszelnicki', 'Paweł', 1, NULL, NULL, NULL, NULL),
	(13, 'Budner', 'Iwona', 2, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `Person` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Person_info
CREATE TABLE IF NOT EXISTS `Person_info` (
  `id_person_info` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(1000) NOT NULL,
  `id_person` int(11) NOT NULL,
  `date_of_info` datetime NOT NULL,
  PRIMARY KEY (`id_person_info`),
  KEY `fkIdx_280` (`id_person`),
  CONSTRAINT `FK_280` FOREIGN KEY (`id_person`) REFERENCES `Person` (`id_person`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Person_info: ~4 rows (około)
/*!40000 ALTER TABLE `Person_info` DISABLE KEYS */;
INSERT INTO `Person_info` (`id_person_info`, `description`, `id_person`, `date_of_info`) VALUES
	(5, 'Umie biegać.', 1, '2019-01-05 21:06:26'),
	(6, 'Umie śpiewać.', 1, '2019-01-05 21:06:36'),
	(7, 'Miała wypadek.', 1, '2019-01-05 21:06:47'),
	(8, 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', 1, '2018-01-05 21:09:42');
/*!40000 ALTER TABLE `Person_info` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Role_type
CREATE TABLE IF NOT EXISTS `Role_type` (
  `id_role_type` int(11) NOT NULL AUTO_INCREMENT,
  `role_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id_role_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY HASH (id_role_type)
PARTITIONS 10 */;

-- Zrzucanie danych dla tabeli mjosek.Role_type: ~10 rows (około)
/*!40000 ALTER TABLE `Role_type` DISABLE KEYS */;
INSERT INTO `Role_type` (`id_role_type`, `role_type`) VALUES
	(1, 'Aktor Pierwszoplanowy'),
	(2, 'Aktor Drugoplanowy'),
	(3, 'Statysta');
/*!40000 ALTER TABLE `Role_type` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Session
CREATE TABLE IF NOT EXISTS `Session` (
  `id_session` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_cookie_session` varchar(64) NOT NULL,
  `ip_address` varchar(39) DEFAULT NULL,
  `webbrowser` varchar(200) DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_session`),
  KEY `fkIDu` (`id_user`),
  CONSTRAINT `fkIDu` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Session: ~0 rows (około)
/*!40000 ALTER TABLE `Session` DISABLE KEYS */;
/*!40000 ALTER TABLE `Session` ENABLE KEYS */;

-- Zrzut struktury procedura mjosek.showMostPopularSpectacles
DELIMITER //
CREATE DEFINER=`mjosek`@`%` PROCEDURE `showMostPopularSpectacles`(
	IN `amount` INT



)
BEGIN
	select id_spectacle, spectacle_name, description, createUrlPathToImg(photo_name) as photo_name, ROUND(sum_of_ratings/number_of_ratings, 2) as rating, number_of_ratings from Spectacle where number_of_ratings != 0 ORDER BY rating desc, number_of_ratings desc LIMIT amount;
END//
DELIMITER ;

-- Zrzut struktury tabela mjosek.Shows
CREATE TABLE IF NOT EXISTS `Shows` (
  `id_show` int(11) NOT NULL AUTO_INCREMENT,
  `id_spectacle` int(11) NOT NULL,
  `show_date` datetime NOT NULL,
  `id_hall` int(11) NOT NULL,
  PRIMARY KEY (`id_show`),
  KEY `fkIdx_215` (`id_spectacle`),
  KEY `fkIdx_295` (`id_hall`),
  CONSTRAINT `FK_215` FOREIGN KEY (`id_spectacle`) REFERENCES `Spectacle` (`id_spectacle`),
  CONSTRAINT `FK_295` FOREIGN KEY (`id_hall`) REFERENCES `Hall` (`id_hall`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Shows: ~4 rows (około)
/*!40000 ALTER TABLE `Shows` DISABLE KEYS */;
INSERT INTO `Shows` (`id_show`, `id_spectacle`, `show_date`, `id_hall`) VALUES
	(1, 1, '2019-01-05 15:32:38', 1),
	(2, 1, '2019-01-03 16:30:57', 1),
	(5, 1, '2020-01-05 16:34:18', 1),
	(6, 5, '2019-01-20 19:00:00', 1);
/*!40000 ALTER TABLE `Shows` ENABLE KEYS */;

-- Zrzut struktury procedura mjosek.showUpcomingPremieresSpectacles
DELIMITER //
CREATE DEFINER=`mjosek`@`%` PROCEDURE `showUpcomingPremieresSpectacles`(
	IN `amount` INT









)
BEGIN
	select id_spectacle, spectacle_name, description, convertDatetimeToDateString(date_of_premiere) as date_of_premiere_formatted, createUrlPathToImg(photo_name) as photo_name from Spectacle where date_of_premiere > SYSDATE() ORDER BY date_of_premiere asc LIMIT amount;
END//
DELIMITER ;

-- Zrzut struktury tabela mjosek.Spectacle
CREATE TABLE IF NOT EXISTS `Spectacle` (
  `id_spectacle` int(11) NOT NULL AUTO_INCREMENT,
  `spectacle_name` varchar(45) NOT NULL,
  `duration` varchar(45) NOT NULL,
  `date_of_premiere` datetime NOT NULL,
  `id_genre` int(11) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `photo_name` varchar(45) DEFAULT NULL,
  `number_of_ratings` int(11) DEFAULT '0',
  `sum_of_ratings` int(11) DEFAULT '0',
  PRIMARY KEY (`id_spectacle`),
  KEY `fkIdx_175` (`id_genre`),
  CONSTRAINT `FK_175` FOREIGN KEY (`id_genre`) REFERENCES `Genre` (`id_genre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Spectacle: ~4 rows (około)
/*!40000 ALTER TABLE `Spectacle` DISABLE KEYS */;
INSERT INTO `Spectacle` (`id_spectacle`, `spectacle_name`, `duration`, `date_of_premiere`, `id_genre`, `description`, `photo_name`, `number_of_ratings`, `sum_of_ratings`) VALUES
	(1, 'Shrek', '', '2021-00-00 00:00:00', 1, 'Twórcy tej wizualnie niezwykłej inscenizacji spojrzeli na słynny dramat jak na rodzaj dysku z danymi z przeszłości, a zachowane na nim najistotniejsze dla naszej cywilizacji motywy i wątki uległy przetworzeniu, swoistemu kulturowemu recyclingowi: „Mitologia i Stary Testament Wyspiańskiego zostaje zastąpiony nauką, technologią, formą. […] Wcale nie oglądamy na scenie misterium, jak chciał Wyspiański, tylko świadkujemy transowi, w jaki wpadają aktorzy, zatopieni w bezruchu, w przyglądaniu się sobie. To dziwny stan rozpłynięcia się ciała w rzeczywistości złożonej z obrazów, dźwięków i płaszczyzn. Twarkowski kładzie akcent na dwa wątki z dramatu: przygląda się braterskim relacjom Hektora i Parysa, Jakuba i Ezawa, zderza ich z figurami Ojca (Priam i Izaak). I nagle spod mitologicznych i biblijnych ornamentów wydobywają się bohaterowie próbujący zrozumieć, kim są i po co są” – napisał w „Dzienniku Polskim” Łukasz Drewniak.', NULL, 5, 25),
	(4, 'Harry Potter', '', '2020-00-00 00:00:00', 1, 'Twórcy tej wizualnie niezwykłej inscenizacji spojrzeli na słynny dramat jak na rodzaj dysku z danymi z przeszłości, a zachowane na nim najistotniejsze dla naszej cywilizacji motywy i wątki uległy przetworzeniu, swoistemu kulturowemu recyclingowi: „Mitologia i Stary Testament Wyspiańskiego zostaje zastąpiony nauką, technologią, formą. […] Wcale nie oglądamy na scenie misterium, jak chciał Wyspiański, tylko świadkujemy transowi, w jaki wpadają aktorzy, zatopieni w bezruchu, w przyglądaniu się sobie. To dziwny stan rozpłynięcia się ciała w rzeczywistości złożonej z obrazów, dźwięków i płaszczyzn. Twarkowski kładzie akcent na dwa wątki z dramatu: przygląda się braterskim relacjom Hektora i Parysa, Jakuba i Ezawa, zderza ich z figurami Ojca (Priam i Izaak). I nagle spod mitologicznych i biblijnych ornamentów wydobywają się bohaterowie próbujący zrozumieć, kim są i po co są” – napisał w „Dzienniku Polskim” Łukasz Drewniak.', NULL, 0, 0),
	(5, 'Akropolis', '150 min.', '2223-11-30 00:00:00', 2, 'Twórcy tej wizualnie niezwykłej inscenizacji spojrzeli na słynny dramat jak na rodzaj dysku z danymi z przeszłości, a zachowane na nim najistotniejsze dla naszej cywilizacji motywy i wątki uległy przetworzeniu, swoistemu kulturowemu recyclingowi: „Mitologia i Stary Testament Wyspiańskiego zostaje zastąpiony nauką, technologią, formą. […] Wcale nie oglądamy na scenie misterium, jak chciał Wyspiański, tylko świadkujemy transowi, w jaki wpadają aktorzy, zatopieni w bezruchu, w przyglądaniu się sobie. To dziwny stan rozpłynięcia się ciała w rzeczywistości złożonej z obrazów, dźwięków i płaszczyzn. Twarkowski kładzie akcent na dwa wątki z dramatu: przygląda się braterskim relacjom Hektora i Parysa, Jakuba i Ezawa, zderza ich z figurami Ojca (Priam i Izaak). I nagle spod mitologicznych i biblijnych ornamentów wydobywają się bohaterowie próbujący zrozumieć, kim są i po co są” – napisał w „Dzienniku Polskim” Łukasz Drewniak.', 'Akropolis.jpg', 3, 12),
	(7, 'Władca pierścieni', '', '2019-01-30 16:14:11', 1, 'Twórcy tej wizualnie niezwykłej inscenizacji spojrzeli na słynny dramat jak na rodzaj dysku z danymi z przeszłości, a zachowane na nim najistotniejsze dla naszej cywilizacji motywy i wątki uległy przetworzeniu, swoistemu kulturowemu recyclingowi: „Mitologia i Stary Testament Wyspiańskiego zostaje zastąpiony nauką, technologią, formą. […] Wcale nie oglądamy na scenie misterium, jak chciał Wyspiański, tylko świadkujemy transowi, w jaki wpadają aktorzy, zatopieni w bezruchu, w przyglądaniu się sobie. To dziwny stan rozpłynięcia się ciała w rzeczywistości złożonej z obrazów, dźwięków i płaszczyzn. Twarkowski kładzie akcent na dwa wątki z dramatu: przygląda się braterskim relacjom Hektora i Parysa, Jakuba i Ezawa, zderza ich z figurami Ojca (Priam i Izaak). I nagle spod mitologicznych i biblijnych ornamentów wydobywają się bohaterowie próbujący zrozumieć, kim są i po co są” – napisał w „Dzienniku Polskim” Łukasz Drewniak.', NULL, 30, 149);
/*!40000 ALTER TABLE `Spectacle` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.Theater
CREATE TABLE IF NOT EXISTS `Theater` (
  `id_theater` int(11) NOT NULL AUTO_INCREMENT,
  `theater_name` varchar(100) NOT NULL,
  `id_address` int(11) NOT NULL,
  `photo_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_theater`),
  KEY `fkIdx_164` (`id_address`),
  CONSTRAINT `FK_164` FOREIGN KEY (`id_address`) REFERENCES `Address` (`id_address`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.Theater: ~1 rows (około)
/*!40000 ALTER TABLE `Theater` DISABLE KEYS */;
INSERT INTO `Theater` (`id_theater`, `theater_name`, `id_address`, `photo_name`) VALUES
	(1, 'Stary Teatr im. Heleny Modrzejewskiej w Krakowie', 1, NULL);
/*!40000 ALTER TABLE `Theater` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.User
CREATE TABLE IF NOT EXISTS `User` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `id_user_type` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `login_email` (`login`,`email`),
  KEY `fkIdx_157` (`id_user_type`),
  CONSTRAINT `FK_157` FOREIGN KEY (`id_user_type`) REFERENCES `User_type` (`id_user_type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.User: ~19 rows (około)
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` (`id_user`, `login`, `hash`, `salt`, `id_user_type`, `email`) VALUES
	(1, 'zbigniew1959', 'test', 'test', 1, 'test@test.pl'),
	(3, 'kaska13', '', '', 3, ''),
	(4, 'arturek_kwiatuszek', '', '', 3, ''),
	(6, 'moniczka', '', '', 3, ''),
	(7, 'pjoter', '', '', 3, ''),
	(10, 'michalek1996', '', '', 3, ''),
	(19, '123', 'd8daa1d23bce06dcf72c090543e9b14893b00ab1', '8e92777581d3c63ed158ca056af3d87b1670979c', 3, '123'),
	(21, 'qaz', '0277188273004fe3d4b6eb535cb7fc5ed850870a', '8046ea3151ac6499432a1ee4aa05581a7619eb8f', 3, 'qaz'),
	(65, 'askdo13', '8ed84e75d1c31c2b80343635c7b32ceca531c872', '195d37999d6ca28e45c9502f300072e44449f8a9', 3, 'asdasd@asdkofd.pl'),
	(66, 'asdko8', '7985224990190bd33cc39bc8f06d4adea01b4943', 'd1738ada081f33f58564bbfe3d4ca7fa4adec0e5', 3, 'aosdas982A@pl.pl'),
	(67, 'asddsa', '94467b35042686767bf39dcd89d86a02c90ce262', 'd090cc00c5a13d13c4450f0cc16340e08958d71a', 3, 'testowymarcin@lys.pl'),
	(68, 'asddsa1', '0bad3cb06e0dc79a83bd0d0cb2ce3cf6e685c22a', '971cdc34e5229f150e37f30a27325f8dea892cdc', 3, 'asd@asd.pl'),
	(69, 'asddsa12', 'ebd1c38265706bac93ea3a463a978a8a48a0c2cf', '55aedabd87d247f5dddbcc9a9b84574191b6792f', 3, 'asd@asd.pla');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.User_copy
CREATE TABLE IF NOT EXISTS `User_copy` (
  `id_user_copy` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `id_user_type` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  PRIMARY KEY (`id_user_copy`),
  KEY `fkIdx_157a` (`id_user_type`),
  CONSTRAINT `FK_157a` FOREIGN KEY (`id_user_type`) REFERENCES `User_type` (`id_user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.User_copy: ~1 rows (około)
/*!40000 ALTER TABLE `User_copy` DISABLE KEYS */;
INSERT INTO `User_copy` (`id_user_copy`, `login`, `hash`, `salt`, `id_user_type`, `email`) VALUES
	(1, '456', '112b65eaa0aa4ea4351fd2e947238800e6c9d3b5', '8763e8837a1591763831725974ab1f5dc3083e85', 3, '456');
/*!40000 ALTER TABLE `User_copy` ENABLE KEYS */;

-- Zrzut struktury tabela mjosek.User_type
CREATE TABLE IF NOT EXISTS `User_type` (
  `id_user_type` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli mjosek.User_type: ~3 rows (około)
/*!40000 ALTER TABLE `User_type` DISABLE KEYS */;
INSERT INTO `User_type` (`id_user_type`, `user_type`) VALUES
	(1, 'Administrator'),
	(3, 'User');
/*!40000 ALTER TABLE `User_type` ENABLE KEYS */;

-- Zrzut struktury widok mjosek.view_all_address_index
-- Tworzenie tymczasowej tabeli aby przezwyciężyć błędy z zależnościami w WIDOKU
CREATE TABLE `view_all_address_index` (
	`street` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`number` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`city` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`id_address` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Zrzut struktury widok mjosek.view_all_person_index
-- Tworzenie tymczasowej tabeli aby przezwyciężyć błędy z zależnościami w WIDOKU
CREATE TABLE `view_all_person_index` (
	`firstname` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`lastname` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`city` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`id_person` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Zrzut struktury widok mjosek.view_all_spectacle_index
-- Tworzenie tymczasowej tabeli aby przezwyciężyć błędy z zależnościami w WIDOKU
CREATE TABLE `view_all_spectacle_index` (
	`spectacle_name` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`genre` VARCHAR(45) NOT NULL COLLATE 'utf8_general_ci',
	`id_spectacle` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Zrzut struktury wyzwalacz mjosek.Log_login
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `Log_login` AFTER INSERT ON `Session` FOR EACH ROW BEGIN
	INSERT INTO Login_logs (id_user,ip_address,webbrowser,login_time) VALUES(new.id_user, new.ip_address, new.webbrowser, new.login_time);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Zrzut struktury wyzwalacz mjosek.User_before_delete
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `User_before_delete` BEFORE DELETE ON `User` FOR EACH ROW BEGIN
	INSERT INTO User_copy (login, hash, salt, id_user_type, email) VALUES(old.login, old.hash, old.salt, old.id_user_type, old.email);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Zrzut struktury widok mjosek.view_all_address_index
-- Usuwanie tabeli tymczasowej i tworzenie ostatecznej struktury WIDOKU
DROP TABLE IF EXISTS `view_all_address_index`;
CREATE ALGORITHM=UNDEFINED DEFINER=`mjosek`@`%` SQL SECURITY DEFINER VIEW `view_all_address_index` AS select `Address`.`street` AS `street`,`Address`.`number` AS `number`,`City`.`city` AS `city`,`Address`.`id_address` AS `id_address` from (`Address` join `City` on((`Address`.`id_city` = `City`.`id_city`)));

-- Zrzut struktury widok mjosek.view_all_person_index
-- Usuwanie tabeli tymczasowej i tworzenie ostatecznej struktury WIDOKU
DROP TABLE IF EXISTS `view_all_person_index`;
CREATE ALGORITHM=UNDEFINED DEFINER=`mjosek`@`%` SQL SECURITY DEFINER VIEW `view_all_person_index` AS select `Person`.`firstname` AS `firstname`,`Person`.`lastname` AS `lastname`,`City`.`city` AS `city`,`Person`.`id_person` AS `id_person` from (`Person` join `City` on((`Person`.`id_city` = `City`.`id_city`)));

-- Zrzut struktury widok mjosek.view_all_spectacle_index
-- Usuwanie tabeli tymczasowej i tworzenie ostatecznej struktury WIDOKU
DROP TABLE IF EXISTS `view_all_spectacle_index`;
CREATE ALGORITHM=UNDEFINED DEFINER=`mjosek`@`%` SQL SECURITY DEFINER VIEW `view_all_spectacle_index` AS select `Spectacle`.`spectacle_name` AS `spectacle_name`,`Genre`.`genre` AS `genre`,`Spectacle`.`id_spectacle` AS `id_spectacle` from (`Spectacle` join `Genre` on((`Spectacle`.`id_genre` = `Genre`.`id_genre`)));

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
