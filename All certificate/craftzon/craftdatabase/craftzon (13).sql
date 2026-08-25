-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2025 at 05:26 PM
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
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `ad_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `user_email` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`ad_id`, `seller_id`, `productid`, `product_name`, `category`, `price`, `description`, `user_email`, `image`, `created_at`) VALUES
(25, 1, 1013, 'home_decor', 'surat_zari_craft', 20000.00, 'ckxodcxopcoixcioxc', 'vivekkariya22@gmail.com', 'advrtisephoto/1757688829_h2.jpeg', '2025-09-12 14:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `auction_table`
--

CREATE TABLE `auction_table` (
  `auction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `start_price` decimal(10,2) NOT NULL,
  `current_price` decimal(10,2) NOT NULL,
  `highest_bidder` int(11) DEFAULT NULL,
  `auction_fee` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('active','ended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auction_table`
--

INSERT INTO `auction_table` (`auction_id`, `product_id`, `start_price`, `current_price`, `highest_bidder`, `auction_fee`, `start_time`, `end_time`, `status`) VALUES
(1, 1014, 20000.00, 32000.00, 15, 0, '2025-09-06 13:57:49', '2025-09-07 13:57:49', 'ended'),
(2, 1015, 5000.00, 5000.00, NULL, 0, '2025-09-08 11:00:52', '2025-09-09 11:00:52', 'ended'),
(3, 1016, 213333.00, 213333.00, NULL, 0, '2025-09-09 18:44:21', '2025-09-10 18:44:21', 'ended'),
(4, 1017, 2000.00, 2000.00, NULL, 200, '2025-09-09 18:49:20', '2025-09-10 18:49:20', 'ended'),
(5, 1018, 5000.00, 10000.00, 7, 500, '2025-09-09 18:52:32', '2025-09-10 18:52:32', 'ended'),
(6, 1020, 5000.00, 5000.00, NULL, 500, '2025-09-12 12:01:47', '2025-09-13 12:01:47', 'active');

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
(7, 16, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'zxzxzxzxzxzxzx', 190.00),
(8, 18, 7, 'vivekkariya22@gmail.com', 'delayed', 'dsdsdasasasasasasasas', 190.00),
(9, 18, 7, 'vivekkariya22@gmail.com', 'changed-mind', 'sedsesesese', 190.00),
(10, 18, 7, 'vivekkariya22@gmail.com', 'wrong-item', 'dfsdsdsdsdsdsd', 190.00),
(11, 19, 7, 'vivekkariya22@gmail.com', 'wrong-item', 'sdsaassasasas', 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `uemailid` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`id`, `user_id`, `uemailid`, `name`, `email`, `message`, `created_at`) VALUES
(1, 7, '', 'vivek', 'vivekkariya22@gmail.com', 'dsrsddfsdsdsdsdsdsd', '2025-08-30 14:45:42'),
(2, 7, '', 'vivek', 'vivekkariya222@gmail.com', 'you product is not good', '2025-09-08 09:16:45'),
(3, 7, '', 'vivek', 'vivekkariya22@gmail.com', 'hello i can recieve you product but it is damage', '2025-09-08 09:19:45'),
(4, 7, '', 'vivek', 'vivekkariya22@gmail.com', 'srddfdfdfdfdf', '2025-09-08 09:20:45'),
(5, 7, 'vivekkariya22@gmail.com', 'vivek', 'vivek@gmail.com', 'csdzdzxzx', '2025-09-08 10:03:29'),
(6, 7, 'vivekkariya22@gmail.com', 'vivek', 'vivek@gmail.com', 'csdzdzxzx', '2025-09-08 10:03:30'),
(7, 7, 'vivekkariya22@gmail.com', 'vivek', 'vivek@gmail.com', 'csdzdzxzx', '2025-09-08 10:03:30'),
(8, 7, 'vivekkariya22@gmail.com', 'vivek', 'vivek@gmail.com', 'csdzdzxzx', '2025-09-08 10:03:31'),
(9, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:37:57'),
(10, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:38:01'),
(11, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:38:03'),
(12, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:38:04'),
(13, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:38:04'),
(14, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:38:05'),
(15, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:38:05'),
(16, 7, 'vivekkariya22@gmail.com', 'vivek', 'kariyabansi2005@gmail.com', 'sdlksdpsopidap[siopasasas', '2025-09-12 10:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `crafter_story`
--

CREATE TABLE `crafter_story` (
  `story_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `media_type` enum('image','video') NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crafter_story`
--

INSERT INTO `crafter_story` (`story_id`, `seller_id`, `title`, `description`, `media_type`, `media_path`, `created_at`) VALUES
(1, 1, 'theindiacraft', 'dfposdfopsopdsopdosodpsopdsdsds', 'image', 'crafter_storie/1757671984_1757602930_brass.jpg', '2025-09-12 10:13:04'),
(2, 1, 'wdsd', 'dsdasdas', 'image', 'crafter_storie/1757672198_1757608658_clay.jpg', '2025-09-12 10:16:38'),
(3, 1, 'indiancraftvideo', 'sdsdasasasas', 'video', 'crafter_storie/1757673238_Screen Recording 2025-09-05 124858.mp4', '2025-09-12 10:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `craftorder`
--

CREATE TABLE `craftorder` (
  `uid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
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
  `previous_status` varchar(50) DEFAULT NULL,
  `excepdelivdate` date DEFAULT NULL,
  `order_request_status` enum('pending','processed','complet') NOT NULL,
  `processed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `craftorder`
--

INSERT INTO `craftorder` (`uid`, `productid`, `seller_id`, `orderid`, `fullname`, `email`, `productnm`, `quantity`, `price`, `address`, `paymentmethod`, `ordertime`, `order_status`, `previous_status`, `excepdelivdate`, `order_request_status`, `processed_date`) VALUES
(7, 1001, 1, 5, 'vivek', 'vivekkariya22@gmail.com', 'sawl', 6, 200, 'babra', '', '2025-08-21', 'ordered', NULL, '2025-08-26', 'processed', '2025-08-23'),
(7, 1001, 1, 6, 'vivek', 'vivekkariya22@gmail.com', 'sawl', 5, 100, 'amreli', '', '2025-09-10', 'Delivered', NULL, '2025-08-25', 'processed', '2025-08-24'),
(7, 1002, 1, 7, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 4, 500, 'amreli', '', '2025-08-21', 'cancel', NULL, NULL, 'processed', NULL),
(7, 1002, 1, 8, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 4, 300, 'xyz', '', '2025-08-16', 'return', NULL, '2025-08-21', 'processed', '2025-08-24'),
(7, 1003, 1, 9, 'vivek', 'vivekkariya22@gmail.com', 's', 5, 600, 'zxzaa', '', '2025-08-16', 'shipped', NULL, NULL, 'processed', '2025-08-27'),
(7, 1004, 1, 10, 'vivek', 'vivekkariya22@gmail.com', 'xyz', 50, 890, '123', '', '2025-09-09', 'Delivered', NULL, NULL, 'pending', NULL),
(8, 1005, 1, 11, 'ram', 'ram@gmail.com', 'xyz12', 2, 207, 'xyz', '', '2025-08-17', 'Delivered', NULL, NULL, 'processed', '2025-08-27'),
(7, 1005, 1, 12, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 3, 6000, 'amreli', 'cod', '2025-08-17', 'Delivered', NULL, '2025-08-25', 'pending', '2025-08-20'),
(7, 1005, 1, 13, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 3, 6000, 'abcasdsds', 'netbanking', '2025-08-17', 'Delivered', NULL, NULL, 'processed', NULL),
(7, 1005, 0, 14, 'vivek', 'vivekkariya22@gmail.com', 'xyz12', 6, 6000, 'fgewqdefdq', 'card', '2025-08-17', 'cancel', NULL, NULL, 'processed', NULL),
(7, 1002, 0, 15, 'vivek', 'vivekkariya22@gmail.com', '', 4, 200, 'sdsdzdzs', 'upi', '2025-08-24', 'cancel', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 16, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 100, 200, 'gfdserttuu', 'upi', '2025-08-24', 'cancel', NULL, NULL, 'pending', NULL),
(7, 1002, 1, 17, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'Delivered', NULL, NULL, 'processed', '2025-09-02'),
(7, 1002, 0, 18, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'cancel', 'shipped', '2025-09-07', 'processed', '2025-09-02'),
(7, 1002, 0, 19, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'adadasdasdaseas', 'card', '2025-08-25', 'cancel', 'ordered', NULL, 'pending', NULL),
(7, 1002, 1, 20, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'adadasdasdaseas', 'card', '2025-08-25', 'Delivered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 21, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'adadasdasdaseas', 'card', '2025-08-25', 'ordered', NULL, '2025-09-07', 'processed', '2025-09-02'),
(7, 1002, 1, 22, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'Delivered', NULL, NULL, 'processed', '2025-09-02'),
(7, 1002, 1, 23, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'Delivered', NULL, NULL, 'processed', '2025-09-02'),
(7, 1002, 1, 24, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'Delivered', NULL, NULL, 'processed', '2025-09-02'),
(7, 1002, 1, 25, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'Delivered', NULL, NULL, 'processed', '2025-08-28'),
(7, 1002, 1, 26, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'Delivered', NULL, NULL, 'processed', '2025-08-28'),
(7, 1002, 1, 27, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'Delivered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 28, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'rtererw', 'cod', '2025-08-25', 'shipped', NULL, NULL, 'processed', '2025-08-28'),
(7, 1002, 0, 29, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'card', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 30, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'asasasdssda', 'upi', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 31, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 32, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'qwaeweweqqw', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 33, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 20, 200, 'gdfdsds', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 34, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 12, 200, 'sdsdsads', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 35, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 36, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 37, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 38, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 39, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'assasasasas', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 40, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 19, 200, 'sdsdsdsd', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 41, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 21, 200, 'asasasasasasas', 'cod', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 42, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 150, 200, 'asasasasasas', 'card', '2025-08-25', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 43, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 10, 200, 'diasiaisaisas', 'cod', '2025-08-27', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 0, 44, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 9, 200, 'tyftyfrtdfrdfdf', 'upi', '2025-08-27', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1002, 2, 45, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 3, 200, 'fdfdfdf', 'cod', '2025-08-27', 'Delivered', NULL, '2025-09-02', 'processed', '2025-08-28'),
(7, 1002, 0, 46, 'vivek', 'vivekkariya22@gmail.com', 'sawl black', 1, 200, 'xfcxsdcxcxc', 'cod', '2025-08-27', 'ordered', NULL, NULL, 'pending', NULL),
(15, 1014, 1, 47, 'rohan', 'vivekkariya22@gmail.com', 'auction1', 2, 32000, 'babra', 'card', '2025-09-07', 'Delivered', NULL, NULL, 'pending', NULL),
(7, 1011, 1, 49, 'vivek', 'vivekkariya22@gmail.com', 'sdjsdsi', 100, 5000, 'sdsdsdassasasas', 'cod', '2025-09-10', 'Delivered', 'Out for Delivery', '2025-09-17', 'processed', '2025-09-10'),
(7, 1011, 0, 50, 'vivek', 'vivekkariya22@gmail.com', 'sdjsdsi', 881, 5000, 'ewsesedsasdasa', 'upi', '2025-09-16', 'Shipped', 'ordered', '2025-09-23', 'processed', '2025-09-19'),
(7, 1011, 0, 51, 'vivek', 'vivekkariya22@gmail.com', 'sdjsdsi', 11, 5000, 'dsdsdawqwrefssd', 'cod', '2025-09-16', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1011, 1, 52, 'vivek', 'vivekkariya22@gmail.com', 'sdjsdsi', 2, 5000, 'csdsdsdassasa', 'cod', '2025-09-10', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1011, 0, 53, 'vivek', 'vivekkariya22@gmail.com', 'sdjsdsi', 1, 5000, 'cxzxzxzxzxzzx', 'cod', '2025-09-10', 'ordered', NULL, NULL, 'pending', NULL),
(8, 1011, 0, 54, 'ram', 'ram@gmail.com', 'sdjsdsi', 2, 5000, 'sdsdsdsdassasas', 'upi', '2025-09-10', 'ordered', NULL, NULL, 'pending', NULL),
(8, 1011, 0, 55, 'ram', 'ram@gmail.com', 'sdjsdsi', 1, 5000, 'gdfhftdadsadfsas', 'cod', '2025-09-10', 'ordered', NULL, NULL, 'pending', NULL),
(7, 1011, 1, 56, 'vivek', 'vivekkariya22@gmail.com', 'sdjsdsi', 1, 5000, 'srhdssddsdc', 'cod', '2025-09-10', 'ordered', NULL, NULL, 'pending', NULL),
(8, 1019, 2, 57, 'ram', 'ram@gmail.com', 'fsxfvfgsdasaz', 2, 10000, 'dfdsgddsd', 'cod', '2025-09-10', 'Delivered', NULL, NULL, 'pending', NULL);

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
  `profile_img` varchar(255) NOT NULL,
  `status` enum('active','suspend') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `craftus_reg`
--

INSERT INTO `craftus_reg` (`u_id`, `uname`, `email`, `mobile_no`, `password`, `profile_img`, `status`) VALUES
(4, 'rohit', '', '', '$2y$10$Py/UF9nPELuyEjLpbqmDLugqIUJfLwOIDmaixdgk1sLBSw7PLoRA2', 'userprofileimage/default.png', 'active'),
(7, 'vivek', 'vivekkariya22@gmail.com', '1234567890', '$2y$10$conrhJbKb6s/J8GFPBgUI.fUwPwjBHvIEnyoGKJBPw8rAsw6s2sRC', 'userprofileimage/brass.jpg', 'active'),
(8, 'ram', 'ram@gmail.com', '1234567890', '$2y$10$W9MTfluvWNrHHsV1U1HTl.AcWmqvt5ShUksn7fNY9g3OBflnzISgK', 'userprofileimage/default.png', 'active'),
(9, 'virat', 'vivekkariya22@gmail.com', '1234455512', '$2y$10$rJrYRxeMBYruGmR4p4pKp.UhODfe1vH19mmT2NqDl.0F99yig9W0S', 'userprofileimage/default.png', 'active'),
(10, 'vivek123', 'vivekkariya22@gmail.com', '9429012366', '$2y$10$/NqaEUqZg5NEmhR2ZjI.5.OQRJ8VMMvRgtnxVvUfzuLbG1OEna0bK', 'userprofileimage/brass.jpg', 'active'),
(11, 'vivek1234', 'vivekkariya22@gmail.com', '9428012323', '$2y$10$rnLwCHWbuAdbE3Q7qtnZtOcHcCeuQ3Z88JNDLtJ3X3HQJQMHkb4MS', 'userprofileimage/default.png', 'active'),
(15, 'rohan', 'vivekkariya22@gmail.com', '1234536789', '$2y$10$v2K3pNwYuucjYjPyZ.oT4OZqnGCjWlP2P60rQf8254i/K8IF3z5nG', 'userprofileimage/default.png', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `email_otp`
--

CREATE TABLE `email_otp` (
  `emailid` varchar(255) NOT NULL,
  `otp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`fid`, `order_id`, `user_name`, `rating`, `comment`, `created_at`) VALUES
(1, 17, 'vivek', 1, 'sdsdsdsds', '2025-08-30 16:00:59'),
(2, 45, 'vivek', 2, 'nice product', '2025-08-30 16:10:19'),
(3, 20, 'vivek', 2, '', '2025-08-30 16:22:07'),
(4, 27, 'vivek', 1, 'nice .....', '2025-08-30 16:22:55'),
(14, 23, 'vivek', 3, 'tg77979', '2025-08-30 17:21:21'),
(15, 22, 'vivek', 3, 'dsodosdosd', '2025-08-30 17:29:26'),
(22, 49, 'vivek', 4, 'nice product', '2025-09-16 07:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `followid` int(11) NOT NULL,
  `sellerid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`followid`, `sellerid`, `userid`) VALUES
(1, 1, 0),
(3, 12, 7);

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
(3, 45, 7, 'COD', 'Completed', 600.00, 'COD-1756301133', '2025-09-02 13:19:25'),
(4, 46, 7, 'COD', 'Pending', 200.00, 'COD-1756301228', '2025-08-27 13:27:08'),
(5, 47, 15, '', 'Completed', 32000.00, 'TXN-1757246925858', '2025-09-07 12:08:45'),
(7, 49, 7, 'COD', 'Completed', 500000.00, 'COD-1757487659', '2025-09-16 07:23:55'),
(8, 50, 7, 'UPI', 'Completed', 4405000.00, 'TXN-1758008236333', '2025-09-16 07:37:16'),
(9, 51, 7, 'COD', 'Pending', 55000.00, 'COD-1758008587', '2025-09-16 07:43:07'),
(10, 52, 7, 'COD', 'Pending', 10000.00, 'COD-1757490449', '2025-09-10 07:47:29'),
(11, 53, 7, 'COD', 'Pending', 5000.00, 'COD-1757490702', '2025-09-10 07:51:42'),
(12, 54, 8, 'UPI', 'Completed', 10000.00, 'TXN-1757490783785', '2025-09-10 07:53:03'),
(13, 55, 8, 'COD', 'Pending', 5000.00, 'COD-1757491048', '2025-09-10 07:57:28'),
(14, 56, 7, 'COD', 'Pending', 5000.00, 'COD-1757509880', '2025-09-10 13:11:20'),
(15, 57, 8, 'COD', 'Pending', 20000.00, 'COD-1757514954', '2025-09-10 14:35:54');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspend') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_table`
--

INSERT INTO `product_table` (`product_id`, `product_name`, `crafted_by`, `category`, `price`, `stock_quantity`, `stock_status`, `product_description`, `image`, `created_at`, `status`) VALUES
(1011, 'sdjsdsi', 'vivek', 'tangaliya_shawl', 500, 100, 'out of stock', 'fksdsopdspdspdsdosdosd', 'craftzonstroreimage/clay.jpg', '2025-09-06 16:10:05', 'active'),
(1013, 'home_decor', 'vivek', 'surat_zari_craft', 20000, 10, 'in stock', 'ckxodcxopcoixcioxc', 'craftzonstroreimage/decor.jpg', '2025-09-06 16:21:44', 'active'),
(1014, 'auction1', 'ram', 'auction', 20000, 2, 'in stock', 'zskaskoaoisiasiasas', 'craftzonstroreimage/download.jpeg', '2025-09-06 17:27:49', 'active'),
(1015, 'abcde', 'vivek', 'auction', 5000, 10, 'in stock', 'dsdsdksidsodisaidasasasas', 'craftzonstroreimage/h8.jpeg', '2025-09-08 14:30:52', 'active'),
(1016, 'dzdzdz', 'vivek', 'auction', 213333, 1, 'in stock', 'cdzdzszsZS', 'craftzonstroreimage/clay.jpg', '2025-09-09 22:14:21', 'active'),
(1017, 'dzdzdz', 'rohit', 'auction', 2000, 1, 'in stock', 'vdfdfdfsdfsdsdsdsdsd', 'craftzonstroreimage/clay.jpg', '2025-09-09 22:19:20', 'active'),
(1018, 'rte', 'vivek', 'auction', 5000, 1, 'in stock', 'fgfgfgfgfgfgfreeasasdfrfdaaASD', 'craftzonstroreimage/h5.jpeg', '2025-09-09 22:22:32', 'active'),
(1019, 'fsxfvfgsdasaz', 'ram', 'clayart', 10000, 100, 'in stock', 'xffchffdadsfasas', 'craftzonstroreimage/auction.jpg', '2025-09-10 20:05:24', 'active'),
(1020, 'acution image 2 ', 'vivek', 'auction', 5000, 1, 'in stock', 'asasasasas', 'craftzonstroreimage/1757608658_clay.jpg', '2025-09-12 15:31:47', 'active');

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
  `gstinno` varchar(15) NOT NULL,
  `shopimage` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspend') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`sellerid`, `storenm`, `sellernm`, `selleremailid`, `gstinno`, `shopimage`, `description`, `time`, `status`) VALUES
(1, 'vk', 'vivek', 'vivekkariya22@gmail.com', '', 'craftzonstroreimage/WhatsApp Image 2025-07-25 at 6.06.34 PM (2).jpeg', 'dsdsdssdsdsdassasas', '2025-08-21', 'active'),
(2, 'ram store', 'ram', 'ram@gmail.com', '29ABCDE1234F1Z6', 'craftzonstroreimage/bamboo.jpg', 'scjsdjsokdpzpocopxzldsp[dpzdopasops', '2025-08-30', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `seller_commission`
--

CREATE TABLE `seller_commission` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `month_year` varchar(7) NOT NULL,
  `delivered_sales` decimal(10,2) DEFAULT 0.00,
  `commission` decimal(10,2) DEFAULT 0.00,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `payment_method` varchar(50) NOT NULL DEFAULT 'upi',
  `upi_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_order_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_commission`
--

INSERT INTO `seller_commission` (`id`, `seller_id`, `month_year`, `delivered_sales`, `commission`, `status`, `payment_method`, `upi_id`, `created_at`, `last_order_id`) VALUES
(11, 1, '2025-09', 609000.00, 30450.00, 'unpaid', 'upi', 'vivek@sbi', '2025-09-10 14:32:09', 0),
(12, 2, '2025-09', 20000.00, 1000.00, 'unpaid', 'upi', NULL, '2025-09-10 14:36:30', 0);

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
(135, 7, 1011, 1, '2025-09-06 11:15:20'),
(136, 7, 1013, 1, '2025-09-11 13:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(30, 7, 1011, '2025-09-12 12:37:17'),
(31, 7, 1013, '2025-09-12 12:37:18'),
(33, 7, 1019, '2025-09-12 12:37:25');

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
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`ad_id`),
  ADD UNIQUE KEY `productid` (`productid`);

--
-- Indexes for table `auction_table`
--
ALTER TABLE `auction_table`
  ADD PRIMARY KEY (`auction_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `highest_bidder` (`highest_bidder`);

--
-- Indexes for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  ADD PRIMARY KEY (`cancel_id`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `crafter_story`
--
ALTER TABLE `crafter_story`
  ADD PRIMARY KEY (`story_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `craftorder`
--
ALTER TABLE `craftorder`
  ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `craftus_reg`
--
ALTER TABLE `craftus_reg`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `uname` (`uname`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`fid`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`followid`);

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
-- Indexes for table `seller_commission`
--
ALTER TABLE `seller_commission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `user_cart_ibfk_1` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `auction_table`
--
ALTER TABLE `auction_table`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `crafter_story`
--
ALTER TABLE `crafter_story`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `craftorder`
--
ALTER TABLE `craftorder`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `craftus_reg`
--
ALTER TABLE `craftus_reg`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `followid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_table`
--
ALTER TABLE `product_table`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1021;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `sellerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `seller_commission`
--
ALTER TABLE `seller_commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auction_table`
--
ALTER TABLE `auction_table`
  ADD CONSTRAINT `auction_table_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`),
  ADD CONSTRAINT `auction_table_ibfk_2` FOREIGN KEY (`highest_bidder`) REFERENCES `craftus_reg` (`u_id`);

--
-- Constraints for table `contactus`
--
ALTER TABLE `contactus`
  ADD CONSTRAINT `contactus_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `crafter_story`
--
ALTER TABLE `crafter_story`
  ADD CONSTRAINT `crafter_story_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller` (`sellerid`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `craftorder` (`orderid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seller_commission`
--
ALTER TABLE `seller_commission`
  ADD CONSTRAINT `seller_commission_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller` (`sellerid`);

--
-- Constraints for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
