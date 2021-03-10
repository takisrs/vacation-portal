-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 10, 2021 at 10:53 AM
-- Server version: 5.7.32
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vacation_portal_db`
--
CREATE DATABASE IF NOT EXISTS `vacation_portal_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vacation_portal_db`;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `dateFrom` date NOT NULL,
  `dateTo` date NOT NULL,
  `reason` text,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `userId`, `dateFrom`, `dateTo`, `reason`, `status`, `createdAt`, `modifiedAt`) VALUES
(1, 1, '2021-04-01', '2021-04-06', 'Easter vacation', '1', '2021-03-06 18:41:10', '2021-03-06 19:14:02'),
(2, 1, '2021-04-01', '2021-04-06', 'Easter vacation', '0', '2021-03-06 18:44:04', '2021-03-06 18:44:04'),
(18, 1, '2021-04-01', '2021-04-06', 'Easter vacation', '0', '2021-03-07 13:55:26', '2021-03-07 13:55:26'),
(19, 1, '2021-04-01', '2021-04-06', 'Easter vacation', '0', '2021-03-09 20:06:59', '2021-03-09 20:06:59'),
(20, 16, '2021-03-23', '2021-03-27', 'vacations', '0', '2021-03-09 23:03:54', '2021-03-09 23:03:54'),
(21, 16, '2021-03-17', '2021-03-24', 'fdfdsf', '2', '2021-03-09 23:09:22', '2021-03-09 23:11:40'),
(22, 16, '2021-03-31', '2021-04-14', 'demo', '1', '2021-03-09 23:13:48', '2021-03-09 23:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`, `type`, `createdAt`, `modifiedAt`) VALUES
(1, 'Panos1', 'Pantazopoulos1', 'panos@atnet.gr', '202cb962ac59075b964b07152d234b70', '2', '2021-03-05 21:38:21', '2021-03-09 23:23:14'),
(16, 'Demo', 'User', 'takispadaz@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '1', '2021-03-09 23:02:43', '2021-03-10 10:11:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId_FK` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_idx` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `userId_FK` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
