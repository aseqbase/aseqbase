-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
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
-- Database: `localhost`
--
CREATE DATABASE IF NOT EXISTS `localhost` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `localhost`;

-- --------------------------------------------------------

--
-- Table structure for table `aseq_Category`
--

CREATE TABLE IF NOT EXISTS `aseq_Category` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ParentID` int(11) DEFAULT NULL,
  `Name` tinytext NOT NULL,
  `Title` varchar(512) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Content` text DEFAULT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Access` int(11) NOT NULL DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aseq_Content`
--

CREATE TABLE IF NOT EXISTS `aseq_Content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryIDs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `TagIDs` text DEFAULT NULL COMMENT 'Separate each TagID between two ''|''',
  `AuthorID` int(11) DEFAULT 0,
  `EditorID` int(11) DEFAULT 0,
  `Type` enum('Post','Text','Image','Animation','Video','Audio','File','Service','Product','News','Article','Document','Collection','Course','Query','Form','Advertisement') NOT NULL DEFAULT 'Post',
  `Name` varchar(256) DEFAULT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '/asset/general/avatar.png',
  `Title` varchar(1024) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `Path` text DEFAULT NULL,
  `Content` longtext DEFAULT NULL,
  `Attach` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Priority` int(11) NOT NULL DEFAULT 0,
  `Access` int(11) DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=1048 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aseq_Post`
--

CREATE TABLE IF NOT EXISTS `aseq_Post` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryIDs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `TagIDs` text DEFAULT NULL COMMENT 'Separate each TagID between two ''|''',
  `AuthorID` int(11) DEFAULT 0,
  `EditorID` int(11) DEFAULT 0,
  `Type` enum('Post','Text','Image','Animation','Video','Audio','File','Service','Product','News','Article','Document','Collection','Course','Query','Form','Advertisement') NOT NULL DEFAULT 'Post',
  `Name` varchar(256) DEFAULT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '/asset/general/avatar.png',
  `Title` varchar(1024) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `Path` text DEFAULT NULL,
  `Content` longtext DEFAULT NULL,
  `Attach` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Priority` int(11) NOT NULL DEFAULT 0,
  `Access` int(11) DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aseq_Session`
--

CREATE TABLE IF NOT EXISTS `aseq_Session` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(512) NOT NULL,
  `Value` text DEFAULT NULL,
  `IP` varchar(128) DEFAULT NULL,
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Key` (`Key`)
) ENGINE=InnoDB AUTO_INCREMENT=71069 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aseq_Tag`
--

CREATE TABLE IF NOT EXISTS `aseq_Tag` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(512) NOT NULL,
  `Title` varchar(512) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aseq_User`
--

CREATE TABLE IF NOT EXISTS `aseq_User` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) NOT NULL,
  `FirstName` varchar(256) DEFAULT NULL,
  `MiddleName` varchar(256) DEFAULT NULL,
  `LastName` varchar(256) DEFAULT NULL,
  `Gender` enum('Unspecified','Male','Female','X') DEFAULT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Bio` text DEFAULT NULL,
  `Email` varchar(256) DEFAULT NULL,
  `Contact` varchar(256) DEFAULT NULL,
  `Address` varchar(1024) DEFAULT NULL,
  `Organization` varchar(256) DEFAULT NULL,
  `Path` varchar(1024) DEFAULT NULL,
  `Signature` varchar(256) NOT NULL,
  `Password` varchar(1024) DEFAULT NULL,
  `Status` tinytext DEFAULT NULL,
  `CreateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Signature` (`Signature`) USING BTREE,
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aseq_UserGroup`
--

CREATE TABLE IF NOT EXISTS `aseq_UserGroup` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(512) NOT NULL,
  `Image` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Title` varchar(512) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `Access` int(11) NOT NULL DEFAULT 0,
  `Status` tinytext DEFAULT NULL,
  `MetaData` longtext DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=2147483648 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `aseq_UserGroup`
--

INSERT INTO `aseq_UserGroup` (`ID`, `Name`, `Image`, `Title`, `Description`, `Access`, `Status`, `MetaData`) VALUES
(0, 'Guest', NULL, 'Guest', 'The Guest Group', 0, '', ''),
(1, 'Super', NULL, 'Super', 'The Super User Group', 1, '1', ''),
(5, 'Manager', NULL, 'Manager', 'The Manager User Group', 5, '1', ''),
(8, 'Author', NULL, 'Author', 'The Author User Group', 8, '1', ''),
(9, 'Editor', NULL, 'Editor', 'The Editor User Group', 9, '1', ''),
(11, 'Special', NULL, 'Special', 'The Special User Group', 11, '1', ''),
(100, 'Registered', NULL, 'Registered', 'The Registered User Group', 100, '1', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
