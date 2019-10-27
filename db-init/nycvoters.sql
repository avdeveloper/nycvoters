SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP TABLE IF EXISTS `future_parties`;
CREATE TABLE IF NOT EXISTS `future_parties` (
  `code` varchar(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

INSERT INTO `future_parties` (`code`, `name`) VALUES
('BLK', ''),
('CON', ''),
('DEM', ''),
('GRE', ''),
('IND', ''),
('LBT', ''),
('LIB', ''),
('OTH', ''),
('REF', ''),
('REP', ''),
('SAM', ''),
('WEP', ''),
('WOR', '');

DROP TABLE IF EXISTS `parties`;
CREATE TABLE IF NOT EXISTS `parties` (
  `code` varchar(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

INSERT INTO `parties` (`code`, `name`) VALUES
('3MB', ''),
('4FD', ''),
('9FD', ''),
('BLK', ''),
('CON', ''),
('DEM', ''),
('FBL', ''),
('FCO', ''),
('FDE', ''),
('FWO', ''),
('GRE', ''),
('IND', ''),
('LBT', ''),
('LIB', ''),
('MBL', ''),
('MDE', ''),
('MGR', ''),
('MIN', ''),
('MOT', ''),
('MRE', ''),
('OTH', ''),
('REP', ''),
('SAM', ''),
('UBL', ''),
('WOR', '');

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=ascii;

DROP TABLE IF EXISTS `voter_data`;
CREATE TABLE IF NOT EXISTS `voter_data` (
  `County_EMSID` varchar(9) NOT NULL DEFAULT '',
  `Last_Name` varchar(30) NOT NULL DEFAULT '',
  `First_Name` varchar(30) NOT NULL DEFAULT '',
  `Middle_Initial` varchar(1) NOT NULL DEFAULT '',
  `Name_Suffix` varchar(4) NOT NULL DEFAULT '',
  `House_Number` varchar(10) NOT NULL DEFAULT '',
  `House_Number_Suffix` varchar(10) NOT NULL DEFAULT '',
  `Apartment_Number` varchar(15) NOT NULL DEFAULT '',
  `Street_Name` varchar(50) NOT NULL DEFAULT '',
  `City` varchar(40) NOT NULL DEFAULT '',
  `Zip_Code` varchar(5) NOT NULL DEFAULT '',
  `Zip_Code4` varchar(4) NOT NULL DEFAULT '',
  `Mailing_Address_1` varchar(50) NOT NULL DEFAULT '',
  `Mailing_Address_2` varchar(50) NOT NULL DEFAULT '',
  `Mailing_Address_3` varchar(50) NOT NULL DEFAULT '',
  `Mailing_Address_4` varchar(50) NOT NULL DEFAULT '',
  `Birth_Date` date DEFAULT NULL,
  `Gender` varchar(1) NOT NULL DEFAULT '',
  `Political_Party` varchar(3) NOT NULL DEFAULT '',
  `Other_Party` varchar(30) NOT NULL DEFAULT '',
  `Election_District` varchar(3) NOT NULL DEFAULT '',
  `Assembly_District` varchar(2) NOT NULL DEFAULT '',
  `Congress_District` varchar(2) NOT NULL DEFAULT '',
  `Council_District` varchar(2) NOT NULL DEFAULT '',
  `Senate_District` varchar(2) NOT NULL DEFAULT '',
  `Civil_Court_District` varchar(2) NOT NULL DEFAULT '',
  `Judicial_District` varchar(2) NOT NULL DEFAULT '',
  `Registration_Date` date DEFAULT NULL,
  `Status_Code` varchar(2) NOT NULL DEFAULT '',
  `Voter_Type` varchar(1) NOT NULL DEFAULT '',
  `Eff_Status_Change_Date` varchar(8) NOT NULL DEFAULT '',
  `Year_Last_Voted` int(4) DEFAULT NULL,
  `Telephone` varchar(20) NOT NULL DEFAULT '',
  `Future_Party` varchar(3) NOT NULL DEFAULT '',
  `Future_Other_Party` varchar(15) NOT NULL DEFAULT '',
  `Future_Party_Effective_Date` date DEFAULT NULL,
  `Times_Voted` int(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`County_EMSID`),
  KEY `Last_Name` (`Last_Name`),
  KEY `First_Name` (`First_Name`),
  KEY `House_Number` (`House_Number`),
  KEY `Apartment_Number` (`Apartment_Number`),
  KEY `Street_Name` (`Street_Name`),
  KEY `City` (`City`),
  KEY `Zip_Code` (`Zip_Code`),
  KEY `Political_Party` (`Political_Party`),
  KEY `Election_district` (`Election_District`),
  KEY `Assembly_district` (`Assembly_District`),
  KEY `Congress_district` (`Congress_District`),
  KEY `Council_district` (`Council_District`),
  KEY `Senate_district` (`Senate_District`),
  KEY `Civil_Court_district` (`Civil_Court_District`),
  KEY `Judicial_District` (`Judicial_District`),
  KEY `Year_Last_Voted` (`Year_Last_Voted`),
  KEY `Times_Voted` (`Times_Voted`),
  KEY `Future_Party` (`Future_Party`),
  KEY `Telephone` (`Telephone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `voter_history`;
CREATE TABLE IF NOT EXISTS `voter_history` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `County_EMSID` char(9) NOT NULL,
  `County` int(2) NOT NULL,
  `Assembly_District` char(2) NOT NULL,
  `Election_District` char(3) NOT NULL,
  `Political_Party` char(3) NOT NULL,
  `Election_Date` date NOT NULL,
  `Election_Type` char(2) NOT NULL,
  `Voter_Type` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `County_EMSID_2` (`County_EMSID`,`Election_Date`,`Election_Type`),
  KEY `County_EMSID` (`County_EMSID`)
) ENGINE=InnoDB AUTO_INCREMENT=76298293 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
