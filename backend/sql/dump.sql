-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 07, 2021 at 04:37 PM
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
-- Database: `vacation`
--

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
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `userId`, `dateFrom`, `dateTo`, `reason`, `status`, `createdAt`, `modifiedAt`) VALUES
(1, 1, '2021-04-01', '2021-04-06', 'Easter vacation', 1, '2021-03-06 18:41:10', '2021-03-06 19:14:02'),
(2, 1, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 18:44:04', '2021-03-06 18:44:04'),
(3, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 20:44:32', '2021-03-06 20:44:32'),
(4, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 20:44:54', '2021-03-06 20:44:54'),
(5, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 20:45:26', '2021-03-06 20:45:26'),
(6, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 20:45:39', '2021-03-06 20:45:39'),
(7, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:02:15', '2021-03-06 21:02:15'),
(8, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:02:25', '2021-03-06 21:02:25'),
(9, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:03:28', '2021-03-06 21:03:28'),
(10, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:08:05', '2021-03-06 21:08:05'),
(11, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:12:25', '2021-03-06 21:12:25'),
(12, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:13:18', '2021-03-06 21:13:18'),
(13, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:15:04', '2021-03-06 21:15:04'),
(14, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:25:32', '2021-03-06 21:25:32'),
(15, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:25:45', '2021-03-06 21:25:45'),
(16, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:26:11', '2021-03-06 21:26:11'),
(17, 2, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-06 21:32:15', '2021-03-06 21:32:15'),
(18, 1, '2021-04-01', '2021-04-06', 'Easter vacation', 0, '2021-03-07 13:55:26', '2021-03-07 13:55:26');

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
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`, `type`, `createdAt`, `modifiedAt`) VALUES
(1, 'Panos', 'Pantazopoulos', 'takispadaz@gmail.com', '48a79ac638adb616f81e22fa11a0894e', 1, '2021-03-05 21:38:21', '2021-03-05 19:38:43'),
(2, 'Admin', 'User', 'takispadaz@hotmail.com', '48a79ac638adb616f81e22fa11a0894e', 2, '2021-03-06 22:24:01', '2021-03-07 12:20:23'),
(3, 'Panos', 'Pantazopoulos', 'panos@atnet.gr', 'sdsd', 1, '2021-03-07 12:22:43', '2021-03-07 12:26:21'),
(4, 'Panos', 'Pantazopoulos', 'panos@atnet.gr', '1a1dc91c907325c69271ddf0c944bc72', 1, '2021-03-07 13:24:56', '2021-03-07 13:24:56'),
(5, 'Panos', 'Pantazopoulos', 'panos@atnet.gr', '1a1dc91c907325c69271ddf0c944bc72', 1, '2021-03-07 13:25:08', '2021-03-07 13:25:08'),
(6, 'Panos', 'Pantazopoulos', 'panos@atnet.gr', '1a1dc91c907325c69271ddf0c944bc72', 1, '2021-03-07 13:32:30', '2021-03-07 13:32:30'),
(7, 'Panos', 'Pantazopoulos', 'panos@atnet.gr', '1a1dc91c907325c69271ddf0c944bc72', 1, '2021-03-07 13:33:11', '2021-03-07 13:33:11');

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
