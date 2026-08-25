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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_table`
--
ALTER TABLE `product_table`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_table`
--
ALTER TABLE `product_table`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1006;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
