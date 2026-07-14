-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 21, 2026 at 03:30 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd_assetdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `T1_user`
--

CREATE TABLE `T1_user` (
  `T1_userid` varchar(255) NOT NULL,
  `T1_username` varchar(100) NOT NULL,
  `T1_email` varchar(255) NOT NULL,
  `T1_passwordhash` varchar(255) NOT NULL,
  `T1_type` varchar(50) NOT NULL DEFAULT 'user',
  `T1_group` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `T2_asset`
--

CREATE TABLE `T2_asset` (
  `T2_assetid` varchar(50) NOT NULL,
  `T2_label` varchar(255) DEFAULT NULL,
  `T2_type` varchar(20) NOT NULL,
  `T2T1_handlerid` varchar(50) NOT NULL COMMENT 'userid of handler',
  `T2_status` varchar(50) NOT NULL,
  `T2_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`T2_details`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `T3_request`
--

CREATE TABLE `T3_request` (
  `T3_requestid` varchar(50) NOT NULL,
  `T3T1_userid` varchar(50) NOT NULL COMMENT 'user that made request',
  `T3_submittime` datetime NOT NULL,
  `T3_reason` text NOT NULL,
  `T3_remark` varchar(255) DEFAULT NULL,
  `T3_purpose` varchar(30) NOT NULL,
  `T3_type` enum('loan','book') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_nopad_ci NOT NULL COMMENT 'type loan or book',
  `T3_datetouse` date NOT NULL,
  `T3_managerapprove` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `T3_handlerapprove` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`T3_handlerapprove`)),
  `T3_status` varchar(40) NOT NULL,
  `T3_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `T4_loan`
--

CREATE TABLE `T4_loan` (
  `T4T3_requestid` varchar(50) NOT NULL,
  `T4T2_assetid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `T4_location` varchar(50) NOT NULL,
  `T4_datetoreceive` date NOT NULL,
  `T4_timereceive` datetime NOT NULL,
  `T4_timereturn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `T1_user`
--
ALTER TABLE `T1_user`
  ADD PRIMARY KEY (`T1_userid`),
  ADD UNIQUE KEY `email` (`T1_email`);

--
-- Indexes for table `T2_asset`
--
ALTER TABLE `T2_asset`
  ADD PRIMARY KEY (`T2_assetid`),
  ADD UNIQUE KEY `assetid` (`T2_assetid`),
  ADD KEY `asset_handler` (`T2T1_handlerid`);

--
-- Indexes for table `T3_request`
--
ALTER TABLE `T3_request`
  ADD PRIMARY KEY (`T3_requestid`),
  ADD KEY `requestbyuser` (`T3T1_userid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `T2_asset`
--
ALTER TABLE `T2_asset`
  ADD CONSTRAINT `asset_handler` FOREIGN KEY (`T2T1_handlerid`) REFERENCES `T1_user` (`T1_userid`);

--
-- Constraints for table `T3_request`
--
ALTER TABLE `T3_request`
  ADD CONSTRAINT `requestbyuser` FOREIGN KEY (`T3T1_userid`) REFERENCES `T1_user` (`T1_userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
