-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 06:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `craftzon`
--

-- --------------------------------------------------------

--
-- Table structure for table `exchange_requests`
--

CREATE TABLE `exchange_requests` (
  `exchange_id` int(11) NOT NULL,
  `uexchangeid` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `emailid` varchar(255) NOT NULL,
  `comments` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Processing') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exchange_requests`
--

INSERT INTO `exchange_requests` (`exchange_id`, `uexchangeid`, `order_id`, `reason`, `emailid`, `comments`, `photo`, `status`, `request_date`) VALUES
(1, 7, 5, 'wrong_item', '', 'adasda', 'exchangeimage/h2.jpeg', 'Pending', '2025-08-17 15:30:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  ADD PRIMARY KEY (`exchange_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  MODIFY `exchange_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
