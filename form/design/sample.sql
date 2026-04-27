-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2026 at 10:52 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `bd-asset-rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `bd-asset`
--

CREATE TABLE `bd-asset` (
  `assetid` varchar(50) NOT NULL,
  `label` varchar(255) NOT NULL,
  `handler` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `inventory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin','pic') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `email`, `password_hash`, `role`) VALUES
('12345', 'darwish.matzain1@gmail.com', 'ddd', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bd-asset`
--
ALTER TABLE `bd-asset`
  ADD PRIMARY KEY (`assetid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`);
COMMIT;
