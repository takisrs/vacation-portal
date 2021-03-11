-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 11, 2021 at 07:18 PM
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
(1, 1, '2021-05-01', '2021-05-06', 'Easter vacation', '1', '2021-03-10 23:00:55', '2021-03-10 23:04:25'),
(2, 1, '2021-08-10', '2021-08-25', 'Summer holidays to Amorgos', '0', '2021-03-10 23:02:11', '2021-03-10 23:02:11'),
(3, 1, '2021-07-01', '2021-07-03', 'Personal issues', '2', '2021-03-10 23:02:51', '2021-03-10 23:04:45'),
(4, 3, '2021-07-01', '2021-07-03', 'Personal issues', '0', '2021-03-11 13:08:14', '2021-03-11 13:08:14'),
(7, 1, '2021-06-22', '2021-06-27', 'Personal reason', '1', '2021-03-11 14:00:11', '2021-03-11 14:17:56'),
(8, 1, '2021-12-23', '2021-12-27', 'Christmas Holidays', '1', '2021-03-11 19:09:37', '2021-03-11 19:12:08'),
(9, 3, '2021-04-20', '2021-04-27', 'Personal issues', '2', '2021-03-11 19:16:05', '2021-03-11 19:17:53'),
(10, 3, '2021-08-15', '2021-08-28', 'Summer Holidays', '1', '2021-03-11 19:17:20', '2021-03-11 19:17:43');

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
(1, 'Panos', 'Pantazopoulos', 'takispadaz@gmail.com', 'ae86ba1819939deb7b9874abc478a811', '1', '2021-03-11 00:44:17', '2021-03-11 19:10:22'),
(2, 'Admin', 'User', 'admin@vacationapp.gr', '46c17fb99b9c8c8ae8214834e1edf6b1', '2', '2021-03-11 00:50:32', '2021-03-10 22:51:33'),
(3, 'Example', 'User', 'demo@vacationapp.gr', 'ae86ba1819939deb7b9874abc478a811', '1', '2021-03-10 23:07:07', '2021-03-10 23:07:07');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
