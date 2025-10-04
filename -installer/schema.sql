-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: %%DATABASE%%:3306
-- Generation Time: Jan 07, 2025 at 03:39 PM
-- Server version: 10.5.27-MariaDB
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `%%DATABASE%%`
--
CREATE DATABASE IF NOT EXISTS `%%DATABASE%%` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `%%DATABASE%%`;

-- --------------------------------------------------------

--
-- Table structure for table `%%PREFIX%%Category`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%Category` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ParentId` int(11) DEFAULT NULL,
  `Name` tinytext NOT NULL,
  `Title` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Access` int(11) NOT NULL DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

REPLACE INTO `%%PREFIX%%Category` (`Id` , `Name` , `Title` , `Description`) VALUES
(1, 'uncategorized', 'Uncategorized', 'All the uncategorized contents'),
(2, 'main', 'Main', 'All the main contents');

-- --------------------------------------------------------

--
-- Table structure for table `%%PREFIX%%Content`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%Content` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryIds` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `TagIds` text DEFAULT NULL COMMENT 'Separate each Tag Id between two ''|''',
  `AuthorId` int(11) DEFAULT NULL,
  `EditorId` int(11) DEFAULT NULL,
  `Type` enum('Item', 'Post', 'Text', 'Image', 'Animation', 'Video', 'Audio', 'File', 'Service', 'Product', 'Merchandise', 'News', 'Article', 'Document', 'Collection', 'Course', 'Query', 'Form', 'Advertisement', 'Forum') NOT NULL DEFAULT 'Item',
  `Name` varchar(256) DEFAULT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Title` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Path` text DEFAULT NULL,
  `Content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Attach` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Priority` int(11) NOT NULL DEFAULT 0,
  `Access` int(11) DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `%%PREFIX%%Comment`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%Comment` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ReplyId` int(11) DEFAULT NULL,
  `GroupId` int(11) DEFAULT NULL,
  `Relation` VARCHAR(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Contact` varchar(256) NOT NULL,
  `Subject` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Attach` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Priority` int(11) NOT NULL DEFAULT 0,
  `Access` int(11) DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Table structure for table `%%PREFIX%%Message
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%Message` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ReplyId` int(11) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `From` varchar(256) NOT NULL,
  `To` longtext NOT NULL,
  `Subject` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Attach` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Type` VARCHAR(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Access` int(11) DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `%%PREFIX%%Session`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%Session` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(512) NOT NULL,
  `Value` text DEFAULT NULL,
  `Ip` varchar(128) DEFAULT NULL,
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` ),
  UNIQUE KEY `Key` (`Key` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `%%PREFIX%%Tag`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%Tag` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(512) NOT NULL,
  `Title` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` ),
  UNIQUE KEY `Name` (`Name` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `%%PREFIX%%User`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%User` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `GroupId` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `FirstName` varchar(256) DEFAULT NULL,
  `MiddleName` varchar(256) DEFAULT NULL,
  `LastName` varchar(256) DEFAULT NULL,
  `Gender` enum('Unspecified','Male','Female','X') NOT NULL DEFAULT 'Unspecified',
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Email` varchar(256) DEFAULT NULL,
  `Contact` varchar(256) DEFAULT NULL,
  `Address` varchar(1024) DEFAULT NULL,
  `Organization` varchar(256) DEFAULT NULL,
  `Path` varchar(1024) DEFAULT NULL,
  `Signature` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `Password` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` ),
  UNIQUE KEY `Signature` (`Signature` ) USING BTREE,
  UNIQUE KEY `Email` (`Email` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `%%PREFIX%%UserGroup`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%UserGroup` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(512) NOT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Title` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `Access` int(11) NOT NULL DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`Id` ),
  UNIQUE KEY `Name` (`Name` )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `%%PREFIX%%UserGroup`
--

REPLACE INTO `%%PREFIX%%UserGroup` (`Id` , `Name` , `Image` , `Title` , `Description` , `Access` , `Status` , `MetaData` ) VALUES
(1, 'ban', NULL, 'Ban', 'The Ban Group', -1, '-1', ''),
(2, 'guest', NULL, 'Guest', 'The Guest Group', 0, '', ''),
(3, 'registered', NULL, 'Registered', 'The Registered User Group', 1, '1', ''),
(4, 'special', NULL, 'Special', 'The Special User Group', 10, '1', ''),
(5, 'editor', NULL, 'Editor', 'The Editor User Group', 955555555, '1', ''),
(6, 'author', NULL, 'Author', 'The Author User Group', 966666666, '1', ''),
(7, 'manager', NULL, 'Manager', 'The Manager User Group', 977777777, '1', ''),
(8, 'administrator', NULL, 'Administrator', 'The Administrator User Group', 988888888, '1', ''),
(9, 'super', NULL, 'Super', 'The Super User Group', 999999999, '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `Translate_Lexicon`
--

CREATE TABLE IF NOT EXISTS `Translate_Lexicon` (
  `KeyCode` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ValueOptions` longtext DEFAULT NULL,
  PRIMARY KEY (`KeyCode` ),
  UNIQUE KEY `KeyCode` (`KeyCode` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
