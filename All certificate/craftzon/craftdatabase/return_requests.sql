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
-- Table structure for table `return_requests`
--

CREATE TABLE `return_requests` (
  `return_id` int(11) NOT NULL,
  `uretunid` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `emailid` varchar(255) NOT NULL,
  `comments` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Processing','Completed') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_requests`
--

INSERT INTO `return_requests` (`return_id`, `uretunid`, `order_id`, `reason`, `emailid`, `comments`, `photo`, `status`, `request_date`) VALUES
(2, 7, 10, 'damaged', '', 'not good', 'retundbimage/bamboo.jpg', 'Pending', '2025-08-17 09:11:44'),
(4, 7, 8, 'wrong_item', 'vivekkariya22@gmail.com', 'ftseteter', 'retundbimage/brass.jpg', 'Pending', '2025-08-17 15:40:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD PRIMARY KEY (`return_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
