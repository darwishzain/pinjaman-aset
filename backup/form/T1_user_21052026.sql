-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 21, 2026 at 06:22 AM
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

--
-- Dumping data for table `T1_user`
--

INSERT INTO `T1_user` (`T1_userid`, `T1_username`, `T1_email`, `T1_passwordhash`, `T1_type`, `T1_group`) VALUES
('samplehandler01', 'Sample Handler 1', 'samplehandler@mail.com', '$2y$10$yzOTcQgdFl/EdKvkyHxqTeO.sXHGzXD0w3Qbybl1QbMICdGkQ8rui', 'handler', 'samplegroup'),
('samplemanager01', 'Sample Manager 1', 'samplemanager@mail.com', '$2y$10$yzOTcQgdFl/EdKvkyHxqTeO.sXHGzXD0w3Qbybl1QbMICdGkQ8rui', 'manager', 'samplegroup'),
('samplestaff01', 'Sample Staff 1', 'samplestaff@mail.com', '$2y$10$yzOTcQgdFl/EdKvkyHxqTeO.sXHGzXD0w3Qbybl1QbMICdGkQ8rui', 'staff', 'samplegroup'),
('sampleuser01', 'Sample User 1', 'sampleuser@mail.com', '$2y$10$yzOTcQgdFl/EdKvkyHxqTeO.sXHGzXD0w3Qbybl1QbMICdGkQ8rui', 'user', 'samplegroup');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `T1_user`
--
ALTER TABLE `T1_user`
  ADD PRIMARY KEY (`T1_userid`),
  ADD UNIQUE KEY `email` (`T1_email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
