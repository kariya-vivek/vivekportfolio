-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 06:21 PM
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
-- Table structure for table `cancel_orders`
--

CREATE TABLE `cancel_orders` (
  `cancel_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `ucancelid` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `reason` enum('delayed','wrong-item','changed-mind','duplicate','other') NOT NULL,
  `comments` text DEFAULT NULL,
  `cancel_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `refund_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancel_orders`
--

INSERT INTO `cancel_orders` (`cancel_id`, `order_id`, `ucancelid`, `user_email`, `reason`, `comments`, `cancel_status`, `request_date`, `refund_amount`) VALUES
(1, 12, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'r44we3et5rterdtu', 'Pending', '2025-08-17 16:13:54', 6000.00),
(2, 14, 7, 'vivekkariya22@gmail.com', 'wrong-item', 'seseawaw', 'Pending', '2025-08-17 16:19:16', 5700.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  ADD PRIMARY KEY (`cancel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
