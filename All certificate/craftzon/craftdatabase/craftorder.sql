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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `craftorder`
--
ALTER TABLE `craftorder`
  ADD PRIMARY KEY (`orderid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `craftorder`
--
ALTER TABLE `craftorder`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
