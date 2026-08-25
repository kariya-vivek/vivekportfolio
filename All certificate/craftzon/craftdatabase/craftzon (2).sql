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

-- --------------------------------------------------------

--
-- Table structure for table `craftorder`
--

CREATE TABLE `craftorder` (
  `uid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `productnm` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `paymentmethod` varchar(50) NOT NULL,
  `ordertime` date NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(50) NOT NULL DEFAULT 'ordered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `craftorder`
--

INSERT INTO `craftorder` (`uid`, `productid`, `orderid`, `fullname`, `email`, `productnm`, `quantity`, `price`, `address`, `paymentmethod`, `ordertime`, `order_status`) VALUES
(7, 1001, 5, 'vivek', 'vivekkariya22@gmail.com', 'sawl', 6, 200, 'babra', '', '2025-08-16', 'exchange'),
(7, 1001, 6, 'vivek', 'vivekkariya22@gmail.com', 'sawl', 5, 100, 'amreli', '', '2025-08-16', 'cancel'),
(7, 1002, 7, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 4, 500, 'amreli', '', '2025-08-16', 'delivered'),
(7, 1002, 8, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 4, 300, 'xyz', '', '2025-08-16', 'return'),
(7, 1003, 9, 'vivek', 'vivekkariya22@gmail.com', 's', 5, 600, 'zxzaa', '', '2025-08-16', 'shipped'),
(7, 1004, 10, 'vivek', 'vivekkariya22@gmail.com', 'xyz', 50, 890, '123', '', '2025-08-16', 'return'),
(8, 1005, 11, 'ram', 'ram@gmail.com', 'xyz12', 2, 207, 'xyz', '', '2025-08-17', 'delivered'),
(7, 1005, 12, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 3, 6000, 'amreli', 'cod', '2025-08-17', 'cancel'),
(7, 1005, 13, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 3, 6000, 'abcasdsds', 'netbanking', '2025-08-17', 'delivered'),
(7, 1005, 14, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 6, 6000, 'fgewqdefdq', 'card', '2025-08-17', 'cancel');

-- --------------------------------------------------------

--
-- Table structure for table `craftus_reg`
--

CREATE TABLE `craftus_reg` (
  `u_id` int(11) NOT NULL,
  `uname` varchar(80) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `craftus_reg`
--

INSERT INTO `craftus_reg` (`u_id`, `uname`, `email`, `mobile_no`, `password`) VALUES
(4, 'bans', '', '', '$2y$10$Py/UF9nPELuyEjLpbqmDLugqIUJfLwOIDmaixdgk1sLBSw7PLoRA2'),
(7, 'vivek', 'vivekkariya22@gmail.com', '1234567890', '$2y$10$conrhJbKb6s/J8GFPBgUI.fUwPwjBHvIEnyoGKJBPw8rAsw6s2sRC'),
(8, 'ram', 'ram@gmail.com', '1234567890', '$2y$10$W9MTfluvWNrHHsV1U1HTl.AcWmqvt5ShUksn7fNY9g3OBflnzISgK');

-- --------------------------------------------------------

--
-- Table structure for table `email_otp`
--

CREATE TABLE `email_otp` (
  `emailid` varchar(255) NOT NULL,
  `otp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_otp`
--

INSERT INTO `email_otp` (`emailid`, `otp`) VALUES
('', 3023),
('vivekkariya22@gmail.com', 7036);

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

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `fid` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_table`
--

CREATE TABLE `product_table` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(25) NOT NULL,
  `crafted_by` varchar(25) NOT NULL,
  `category` varchar(25) NOT NULL,
  `price` int(11) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `product_description` varchar(200) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_table`
--

INSERT INTO `product_table` (`product_id`, `product_name`, `crafted_by`, `category`, `price`, `stock_quantity`, `product_description`, `image`, `created_at`) VALUES
(1001, 'sawl', 'ramesh', 'home_decor', 200, 5, 'this is best', 'craftzonstroreimage/h5.jpeg', '2025-08-12'),
(1002, 'sawl black', 'ramesh', 'pottery', 200, 5, 'this is best and good', 'craftzonstroreimage/h6.jpeg', '2025-08-13'),
(1003, 's', 's', 'Handicrafts', 1, 1, 'a', 'craftzonstroreimage/h7.jpeg', '2025-08-14'),
(1004, 'xyz', 'v', 'bandhani', 234, 5, 'xyz', 'craftzonstroreimage/h8.jpeg', '2025-08-15'),
(1005, 'xyz12', 'abc', 'kutch_embroidery', 6000, 1, 'mhgff', 'craftzonstroreimage/h4.jpeg', '2025-08-11');

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

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `sellerid` int(11) NOT NULL,
  `storenm` varchar(30) NOT NULL,
  `sellernm` varchar(30) NOT NULL,
  `selleremailid` varchar(50) NOT NULL,
  `gstinno` varchar(15) NOT NULL,
  `shopimage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`sellerid`, `storenm`, `sellernm`, `selleremailid`, `gstinno`, `shopimage`) VALUES
(1, 'vk', 'vivek', 'vivekkariya22@gmail.com', '', 'craftzonstroreimage/WhatsApp Image 2025-07-25 at 6.06.34 PM (2).jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  ADD PRIMARY KEY (`cancel_id`);

--
-- Indexes for table `craftorder`
--
ALTER TABLE `craftorder`
  ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `craftus_reg`
--
ALTER TABLE `craftus_reg`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  ADD PRIMARY KEY (`exchange_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `product_table`
--
ALTER TABLE `product_table`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD PRIMARY KEY (`return_id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`sellerid`),
  ADD UNIQUE KEY `storenm` (`storenm`),
  ADD UNIQUE KEY `gstinno` (`gstinno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `craftorder`
--
ALTER TABLE `craftorder`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `craftus_reg`
--
ALTER TABLE `craftus_reg`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  MODIFY `exchange_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_table`
--
ALTER TABLE `product_table`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1006;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `sellerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
