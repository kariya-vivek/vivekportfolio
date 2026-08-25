-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2025 at 06:16 PM
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
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `emailid` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `name`, `emailid`, `password`, `created_at`) VALUES
(1, 'vivek', 'vivekkariya22@gmail.com', '$2y$10$WkYlJrMNR0hLvfoLRQnHi.0lR7Q/TJ.gmT5kNsrYUXG7cqCeRahIO', '2025-08-19 14:12:52');

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
  `refund_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancel_orders`
--

INSERT INTO `cancel_orders` (`cancel_id`, `order_id`, `ucancelid`, `user_email`, `reason`, `comments`, `refund_amount`) VALUES
(1, 12, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'r44we3et5rterdtu', 6000.00),
(2, 14, 7, 'vivekkariya22@gmail.com', 'wrong-item', 'seseawaw', 5700.00),
(3, 7, 7, 'vivekkariya22@gmail.com', 'delayed', 'hfgfgdfsddsdsd', 190.00),
(4, 15, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'fdfdfseds', 190.00),
(5, 16, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'zxzxzxzxzxzxzx', 190.00),
(6, 16, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'zxzxzxzxzxzxzx', 190.00),
(7, 16, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'zxzxzxzxzxzxzx', 190.00);

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
  `order_status` varchar(50) NOT NULL DEFAULT 'ordered',
  `excepdelivdate` date DEFAULT NULL,
  `order_request_status` enum('pending','processed','complet') NOT NULL,
  `processed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `craftorder`
--

INSERT INTO `craftorder` (`uid`, `productid`, `orderid`, `fullname`, `email`, `productnm`, `quantity`, `price`, `address`, `paymentmethod`, `ordertime`, `order_status`, `excepdelivdate`, `order_request_status`, `processed_date`) VALUES
(7, 1001, 5, 'vivek', 'vivekkariya22@gmail.com', 'sawl', 6, 200, 'babra', '', '2025-08-21', 'ordered', '2025-08-26', 'processed', '2025-08-23'),
(7, 1001, 6, 'vivek', 'vivekkariya22@gmail.com', 'sawl', 5, 100, 'amreli', '', '2025-08-20', 'delivered', '2025-08-25', 'processed', '2025-08-24'),
(7, 1002, 7, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 4, 500, 'amreli', '', '2025-08-21', 'cancel', NULL, 'processed', NULL),
(7, 1002, 8, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 4, 300, 'xyz', '', '2025-08-16', 'return', '2025-08-21', 'processed', '2025-08-24'),
(7, 1003, 9, 'vivek', 'vivekkariya22@gmail.com', 's', 5, 600, 'zxzaa', '', '2025-08-16', 'shipped', NULL, 'processed', '2025-08-27'),
(7, 1004, 10, 'vivek', 'vivekkariya22@gmail.com', 'xyz', 50, 890, '123', '', '2025-08-16', 'delivered', NULL, 'pending', NULL),
(8, 1005, 11, 'ram', 'ram@gmail.com', 'xyz12', 2, 207, 'xyz', '', '2025-08-17', 'delivered', NULL, 'processed', '2025-08-27'),
(7, 1005, 12, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 3, 6000, 'amreli', 'cod', '2025-08-17', 'Delivered', '2025-08-25', 'pending', '2025-08-20'),
(7, 1005, 13, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 3, 6000, 'abcasdsds', 'netbanking', '2025-08-17', 'delivered', NULL, 'processed', NULL),
(7, 1005, 14, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 6, 6000, 'fgewqdefdq', 'card', '2025-08-17', 'cancel', NULL, 'processed', NULL),
(7, 1002, 15, 'vivek', 'vivekkariya22@gmail.com', '', 4, 200, 'sdsdzdzs', 'upi', '2025-08-24', 'cancel', NULL, 'pending', NULL),
(7, 1002, 16, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 100, 200, 'gfdserttuu', 'upi', '2025-08-24', 'cancel', NULL, 'pending', NULL),
(7, 1002, 17, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 18, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 19, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'adadasdasdaseas', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 20, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'adadasdasdaseas', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 21, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'adadasdasdaseas', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 22, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 23, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 24, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 25, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 26, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 27, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 28, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'rtererw', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 29, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 30, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'asasasdssda', 'upi', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 31, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 32, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'qwaeweweqqw', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 33, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 20, 200, 'gdfdsds', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 34, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 12, 200, 'sdsdsads', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 35, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 36, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 37, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 38, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 39, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'assasasasas', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 40, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 19, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 41, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 21, 200, 'asasasasasasas', 'cod', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 42, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 150, 200, 'asasasasasas', 'card', '2025-08-25', 'ordered', NULL, 'pending', NULL),
(7, 1002, 43, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'diasiaisaisas', 'cod', '2025-08-27', 'ordered', NULL, 'pending', NULL),
(7, 1002, 44, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 9, 200, 'tyftyfrtdfrdfdf', 'upi', '2025-08-27', 'ordered', NULL, 'pending', NULL),
(7, 1002, 45, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 3, 200, 'fdfdfdf', 'cod', '2025-08-27', 'ordered', NULL, 'pending', NULL),
(7, 1002, 46, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 1, 200, 'xfcxsdcxcxc', 'cod', '2025-08-27', 'ordered', NULL, 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `craftus_reg`
--

CREATE TABLE `craftus_reg` (
  `u_id` int(11) NOT NULL,
  `uname` varchar(80) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','suspend') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `craftus_reg`
--

INSERT INTO `craftus_reg` (`u_id`, `uname`, `email`, `mobile_no`, `password`, `status`) VALUES
(4, 'rohit', '', '', '$2y$10$Py/UF9nPELuyEjLpbqmDLugqIUJfLwOIDmaixdgk1sLBSw7PLoRA2', 'suspend'),
(7, 'vivek', 'vivekkariya22@gmail.com', '1234567890', '$2y$10$conrhJbKb6s/J8GFPBgUI.fUwPwjBHvIEnyoGKJBPw8rAsw6s2sRC', 'active'),
(8, 'ram', 'ram@gmail.com', '1234567890', '$2y$10$W9MTfluvWNrHHsV1U1HTl.AcWmqvt5ShUksn7fNY9g3OBflnzISgK', 'active');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` enum('COD','UPI','CreditCard') NOT NULL,
  `payment_status` enum('Pending','Completed','Failed') NOT NULL DEFAULT 'Pending',
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `user_id`, `payment_method`, `payment_status`, `amount`, `transaction_id`, `payment_date`) VALUES
(1, 43, 7, 'COD', 'Completed', 2000.00, 'COD-1756295754', '2025-08-27 11:55:54'),
(2, 44, 7, 'UPI', 'Completed', 1800.00, 'TXN-1756297382936', '2025-08-27 12:23:02'),
(3, 45, 7, 'COD', 'Pending', 600.00, 'COD-1756301133', '2025-08-27 13:25:33'),
(4, 46, 7, 'COD', 'Pending', 200.00, 'COD-1756301228', '2025-08-27 13:27:08');

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
  `stock_status` enum('in stock','out of stock') NOT NULL,
  `product_description` varchar(200) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspend') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_table`
--

INSERT INTO `product_table` (`product_id`, `product_name`, `crafted_by`, `category`, `price`, `stock_quantity`, `stock_status`, `product_description`, `image`, `created_at`, `status`) VALUES
(1002, 'sawl black', 'vivek', 'pottery', 200, 9, 'in stock', 'this is best and good', 'craftzonstroreimage/h6.jpeg', '2025-08-13', 'active'),
(1010, 'cxddxs', 'vivek', 'auction', 10000, 1, 'in stock', 'xzszxzxzxzxzxzxz', 'craftzonstroreimage/auction.jpg', '2025-08-26', 'active');

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
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `approve_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_requests`
--

INSERT INTO `return_requests` (`return_id`, `uretunid`, `order_id`, `reason`, `emailid`, `comments`, `photo`, `status`, `request_date`, `approve_date`) VALUES
(2, 7, 10, 'damaged', '', 'not good', 'retundbimage/bamboo.jpg', 'Pending', '2025-08-17 09:11:44', NULL),
(4, 7, 8, 'wrong_item', 'vivekkariya22@gmail.com', 'ftseteter', 'retundbimage/brass.jpg', 'Pending', '2025-08-17 15:40:19', '2025-08-27'),
(5, 7, 8, 'damaged', 'vivekkariya22@gmail.com', 'fgdfdfsdsdsdaasas', 'retundbimage/h2.jpeg', 'Approved', '2025-08-27 16:04:01', '2025-08-27');

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `sellerid` int(11) NOT NULL,
  `storenm` varchar(30) NOT NULL,
  `sellernm` varchar(30) NOT NULL,
  `selleremailid` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `gstinno` varchar(15) NOT NULL,
  `shopimage` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspend') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`sellerid`, `storenm`, `sellernm`, `selleremailid`, `city`, `gstinno`, `shopimage`, `description`, `time`, `status`) VALUES
(1, 'vk', 'vivek', 'vivekkariya22@gmail.com', '', '', 'craftzonstroreimage/WhatsApp Image 2025-07-25 at 6.06.34 PM (2).jpeg', '', '2025-08-21', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_cart`
--

CREATE TABLE `user_cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_cart`
--

INSERT INTO `user_cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(91, 7, 1002, 6, '2025-08-27 13:50:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `emailid` (`emailid`);

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_order` (`order_id`),
  ADD KEY `fk_user` (`user_id`);

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
  ADD UNIQUE KEY `unique_email` (`selleremailid`),
  ADD UNIQUE KEY `selleremailid` (`selleremailid`),
  ADD UNIQUE KEY `storenm` (`storenm`),
  ADD UNIQUE KEY `sellernm` (`sellernm`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `craftorder`
--
ALTER TABLE `craftorder`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_table`
--
ALTER TABLE `product_table`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1011;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `sellerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `craftorder` (`orderid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
