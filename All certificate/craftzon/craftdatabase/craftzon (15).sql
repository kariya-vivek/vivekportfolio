-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 12:48 AM
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
(19, 1, 14, 1, 'om', 'omomcollection49@gmail.com', 'Multicolored Jaipuri Zari', 2, 2000, 'babra', 'cod', '2025-09-17', 'ordered', NULL, NULL, 'pending', NULL),
(19, 2, 14, 2, 'om', 'omomcollection49@gmail.com', 'Scalloped Golden Zari Lac', 1, 999, 'babra', 'upi', '2025-09-17', 'ordered', NULL, NULL, 'pending', NULL),
(19, 5, 14, 3, 'om', 'omomcollection49@gmail.com', 'Handpainted clay Karwa', 1, 1500, 'babra', 'cod', '2025-09-17', 'ordered', NULL, NULL, 'pending', NULL);

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
(18, 'ram', 'vivekkariya22@gmail.com', '2389126266', '$2y$10$4HzKOI6rUi.57xTSh0aFqe2y8Q5cO3nPf8NIZVI.e.z4VwzqElMS.', 'userprofileimage/default.png', 'active'),
(19, 'om', 'omomcollection49@gmail.com', '3478923232', '$2y$10$k.7CvEVj8PVFOVM2aS7Cy.e3OqxsxiBZ/mEMVKKsury5eL2OBnOEO', 'userprofileimage/u9.png', 'active');

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

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `followid` int(11) NOT NULL,
  `sellerid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
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
(1, 1, 19, 'COD', 'Pending', 4000.00, 'COD-1758126472', '2025-09-17 16:27:52'),
(2, 2, 19, 'UPI', 'Completed', 999.00, 'TXN-1758126517821', '2025-09-17 16:28:37'),
(3, 3, 19, 'COD', 'Pending', 1500.00, 'COD-1758126542', '2025-09-17 16:29:02');

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
(1, 'Multicolored Jaipuri Zari', 'ram', 'surat_zari_craft', 2000, 48, 'in stock', 'A saree in Jaipuri weave with multicolored threads + zari work in border & pallu. Elegant design, good for festive/special occasions.', 'craftzonstroreimage/WhatsAppImage2024-05-02at2.33.19PM.jpg', '2025-09-17 03:18:48', 'active'),
(2, 'Scalloped Golden Zari Lac', 'ram', 'surat_zari_craft', 999, 79, 'in stock', 'Golden zari lace in scalloped pattern – good for trimming saree edges, dupattas, blouses.', 'craftzonstroreimage/BQ4B6X~1.WEB', '2025-09-17 03:21:14', 'active'),
(3, 'Delhi Blue Art pottery Cu', 'ram', 'pottery', 2500, 30, 'in stock', 'A beautiful blue-pottery terracotta curio in Buddha motif, perfect for home décor or altar use. Lead-free and glazed in traditional Delhi Blue style.', 'craftzonstroreimage/Delhi_20Blue_20Art_20Pottery_20Curio_20-_20Buddha_20-_20ADBP11_203.jpg', '2025-09-17 03:23:01', 'active'),
(5, 'Handpainted clay Karwa', 'ram', 'clayart', 1500, 39, 'in stock', 'Eco-friendly clay pooja kalash, hand painted, for festivals like Karwa Chauth or general pooja. Decorative & symbolic.', 'craftzonstroreimage/Q8A9985_112e4db4-5bc3-4416-abdb-0873cae62d0e.jpg', '2025-09-17 03:26:30', 'active'),
(6, 'Brass Kirti Mukha Wall Ma', 'ram', 'brassart', 4999, 25, 'in stock', 'A decorative brass “Kirti Mukha” (face motif) set in a colorful hand-painted wooden frame. Ethnic wall art with strong traditional symbolism.', 'craftzonstroreimage/8722.jpg', '2025-09-17 03:31:20', 'active'),
(7, 'Ganesh‑Laxmi‑Saraswati Tr', 'ram', 'brassart', 5999, 20, 'in stock', 'A beautifully crafted trio of Brass deities Ganesh, Laxmi, and Saraswati—a symbolic group for prosperity, knowledge & wealth. Perfect for pooja rooms or gifting.', 'craftzonstroreimage/30366.jpg', '2025-09-17 03:32:56', 'active'),
(8, 'Delhi Blue Art clay Cu', 'ram', 'clayart', 9999, 15, 'in stock', 'A curio / display piece in Delhi Blue style—a flat kettle shaped ceramic item, more decorative than functional. Adds artistic flair.', 'craftzonstroreimage/Delhi_20Blue_20Art_20Pottery_20Curio_20__20Flat_20Kettle_20-_20ADBP09_203.jpg', '2025-09-17 03:34:31', 'active'),
(9, 'Jaipur Blue Pottery Small', 'ram', 'pottery', 2499, 9, 'in stock', 'Set of 4 handcrafted dessert bowls, typical Jaipur Blue Pottery with regal blue-floral design. Great for serving desserts or as decorative accent.', 'craftzonstroreimage/Jaipur_20Blue_20Pottery_20Small_20Dessert_20Bowls_20-_20Royal_20Blue_20Floral_20_28Set_20of_204_29_20-_20SBBP01G.jpg', '2025-09-17 03:35:26', 'active'),
(10, 'Wooden Engraved Tray ‑ Re', 'ram', 'home_decor', 1999, 60, 'in stock', 'Handcrafted wooden tray with red floral engraving, metal handles. Excellent as a decorative serving tray or accent piece.', 'craftzonstroreimage/Wood_20_26_20Dhokra_20UtilityBox_20with_20Dhokra_20Flower_20Handle_20-_20Log_20Box_20-_20KWT01Q.jpg', '2025-09-17 03:38:22', 'active'),
(11, 'Wooden Engraved Tray ‑ Re', 'ram', 'home_decor', 3999, 8, 'in stock', 'Handcrafted wooden tray with red floral engraving, metal handles. Excellent as a decorative serving tray or accent piece.', 'craftzonstroreimage/HandcraftedWoodenEngravedTray-RedFloral-SJH62.jpg', '2025-09-17 03:39:29', 'active'),
(12, 'Yellow Pure Silk Bandhani', 'ram', 'bandhani', 1250, 100, 'in stock', 'Pure silk/ silk-feel Bandhani saree with spread butti (small tied dots) work, bright yellow tone. Great festive piece.', 'craftzonstroreimage/1_655f69a3-e5fa-4f5b-9356-0968d1a811bc.jpg', '2025-09-17 03:52:58', 'active'),
(13, 'Purple Modal Bandhani Sar', 'ram', 'bandhani', 7999, 20, 'in stock', 'A rich purple modal silk Bandhani saree with fine tie-dye work, elegant drape, with blouse piece included. Good for semi-formal or festival wear.', 'craftzonstroreimage/C8C6E96F-7B57-4BD4-A81C-B287398F0AB5.jpg', '2025-09-17 03:53:46', 'active'),
(14, 'Gujrati Stitch Art Silk S', 'ram', 'kutch_embroidery', 5000, 30, 'in stock', 'Art silk saree with full Kutch embroidery (Gujarati stitch), borders & pallu highlighted. Comes with blouse. Good for festivities or special occasions.', 'craftzonstroreimage/1D845ADD-3DD5-494F-8FCF-1BE12991AC73.jpg', '2025-09-17 03:55:00', 'active'),
(15, '‘Ambika’ Pure Cotton Kutc', 'ram', 'kutch_embroidery', 6799, 35, 'in stock', 'Pure cotton saree named “Ambika”, with Kutch embroidery in maroon thread on white base. Lightweight and traditional style; takes ~30 days to handembroider. Blouse piece not included.', 'craftzonstroreimage/1_e802f18a-337d-48d8-8956-c4dd4f955245.png', '2025-09-17 03:56:00', 'active'),
(16, 'Green Patola Silk Zari We', 'ram', 'patola_slik_sarees', 2250, 45, 'in stock', 'Deep green Patola silk with zari weaving, vibrant motifs. Rich look.', 'craftzonstroreimage/3_53dcab4f-cced-42e9-b917-b5fbcfc6ed22.jpg', '2025-09-17 03:57:01', 'active'),
(17, 'Sky‑Blue Pochampally Pato', 'ram', 'patola_slik_sarees', 999, 40, 'in stock', 'Soft silk saree in sky blue with classic Patola print weave. Comes with unstitched blouse. Great for festive / party wear.', 'craftzonstroreimage/4_4c84c2d0-f9d1-45bc-92ec-892f07fc1fec.jpg', '2025-09-17 03:58:11', 'active'),
(18, 'Large Merino Black Tangal', 'ram', 'tangaliya_shawl', 6000, 20, 'in stock', 'Black base merino shawl with dense Tangaliya dot work and fine raised texture. Larger size, more dramatic presence.', 'craftzonstroreimage/DSL-1197_1.jpg', '2025-09-17 03:59:52', 'active'),
(19, 'Tangaliya Merino Wool Sha', 'ram', 'tangaliya_shawl', 13499, 20, 'in stock', ' Premium merino wool shawl with Tangaliya weave. Very soft, fine detailing, more refined color palette. Good for gifting or formal wear.', 'craftzonstroreimage/R7GIL3~1.WEB', '2025-09-17 04:01:20', 'active'),
(20, 'Krishna Handpainted Clay ', 'om', 'clayart', 699, 60, 'in stock', 'Hand-painted clay wall frame featuring Lord Krishna; traditional craftsmanship meets home décor. Ideal gift or shrine décor.', 'craftzonstroreimage/0X2A0399_da16b165-bc8f-4167-899c-82e8d5f63c3b.jpg', '2025-09-17 04:21:35', 'active'),
(21, 'Arthanareeswarar Clay Sta', 'om', 'clayart', 1750, 15, 'in stock', 'Clay statue (Arthanareeswarar / Shivan Parvati) ~12.5 inches high. Handcrafted, decorative item for puja / home décor.', 'craftzonstroreimage/IMG_7090.jpg', '2025-09-17 04:23:01', 'active'),
(22, 'Panchamukhi Hanuman Statu', 'om', 'brassart', 6999, 10, 'in stock', 'Panchamukhi Hanuman (five-faced) brass idol – a spiritually strong piece with mythological significance. For devotional décor or gifting during religious festivals.', 'craftzonstroreimage/18_ac7a27c8-9783-49f2-9483-899ab0dd0ca2.jpg', '2025-09-17 04:25:04', 'active'),
(23, 'Majestic Prabhawali Large', 'om', 'brassart', 14999, 30, 'in stock', 'A large brass prabhavali (halo-style ornament) with intricate detailing. Great for wall mounting above idols or as a standalone art piece.', 'craftzonstroreimage/2335.jpg', '2025-09-17 04:25:48', 'active'),
(24, 'Jaipur Blue Pottery Utili', 'om', 'pottery', 2499, 30, 'in stock', 'Two utility storage jars with lids, decorated in blue & lime shades, useful for storing tea, spices, dry snacks. Stylish + functional.', 'craftzonstroreimage/Jaipur_20Blue_20Pottery_20Utility_20Jars_20with_20Lids_20-_20Lime_20Fresh_20_28Set_20of_202_29_20-_20SBBP01I_201.jpg', '2025-09-17 04:26:55', 'active'),
(25, 'Kadai: Manipur Black Pott', 'om', 'pottery', 5599, 99, 'in stock', 'Traditional cookware from Manipur, made of black pottery clay (“Loree Hamlei”). Good for cooking, retains heat, gives food a distinct aroma. Mix of art + ut', 'craftzonstroreimage/kadai-manipur-black-pottery-zishta-traditional-cookware.jpeg', '2025-09-17 04:27:46', 'active'),
(26, 'Kavad Craft Portable Shri', 'om', 'home_decor', 3899, 60, 'in stock', 'Painted wooden shrine (Kavad style) with panels telling Ramayan scenes. Decorative & spiritual ambience + storytelling art.', 'craftzonstroreimage/Kavad_20Craft_20Curio_20-_20Ramayan_2C_20Painted_20Wood_20Portable_20Shrine_20_28Green_2011__29_20-_20BKA101.jpg', '2025-09-17 04:28:51', 'active'),
(27, 'Flower Rangoli Beadwork', 'om', 'home_decor', 750, 150, 'in stock', 'Hand-painted beadwork rangoli (flower motif). Good for pooja room / decorative wall/display. Adds festive charm.', 'craftzonstroreimage/632A2490_ef319baa-4283-4b08-8b9a-21dd77754342.jpg', '2025-09-17 04:30:18', 'active'),
(28, 'Pure Viscose Bandhani Sar', 'om', 'bandhani', 2500, 100, 'in stock', 'Bottle-green viscose Bandhani saree, flowy and light, with decorative border. Great for festive or casual use.', 'craftzonstroreimage/2FBCE65C-BF23-45FD-8DE9-44D7E1CF3BEF.jpg', '2025-09-17 04:31:35', 'active'),
(29, 'Casual Bandhani Saree wit', 'om', 'bandhani', 1700, 40, 'in stock', 'Lightweight georgette Bandhani saree with subtle zari lines, casual & suitable for everyday wear. Comes with blouse, nice width.', 'craftzonstroreimage/RJS1146-2.jpg', '2025-09-17 04:32:29', 'active'),
(30, 'Zari & Sequins Lace/Reel ', 'om', 'surat_zari_craft', 552, 200, 'in stock', 'Lace reel combining zari & sequin work, long length – good for dress materials, embellishments.', 'craftzonstroreimage/0RBGQH~1.WEB', '2025-09-17 04:33:26', 'active'),
(31, 'Black‑Golden Weaving Zari', 'om', 'surat_zari_craft', 80, 300, 'in stock', 'A weaving border, black base with golden zari, ~5 meters – useful for wholesalers, retailers.', 'craftzonstroreimage/SPLMTH~1.WEB', '2025-09-17 04:34:12', 'active'),
(32, 'Mashru Silk Blouse with K', 'om', 'kutch_embroidery', 7150, 80, 'in stock', ' A pre-stitched blouse in pure Mashru silk, detailed with Kutch embroidery — threads + mirrors. Perfect to match with plain sarees or solids; adds a traditional artisan touch.', 'craftzonstroreimage/DSC09124.jpg', '2025-09-17 04:35:20', 'active'),
(33, 'Pearl Grey Raw Silk Wall ', 'om', 'kutch_embroidery', 16000, 30, 'in stock', 'Large wall hanging made from raw silk in a pearl-grey shade, beautifully hand embroidered by women artisans from Kutch. Includes mirror work and traditional motifs. Ideal for putting on walls as state', 'craftzonstroreimage/Exclusive_20Raw_20Silk_20Kutch_20Embroidered_20Wall_20Hanging_20-_20Pearl_20Grey_20_28Extra_20Large_29_20-_20TWH102.jpg', '2025-09-17 04:36:23', 'active'),
(34, 'Tangaliya Merino Wool Sha', 'om', 'tangaliya_shawl', 1800, 50, 'in stock', 'Premium merino wool shawl with Tangaliya weave. Very soft, fine detailing, more refined color palette. Good for gifting or formal wear.', 'craftzonstroreimage/tp3.jpg', '2025-09-17 05:08:16', 'active'),
(35, 'Manyavar Men’s Elegance S', 'om', 'tangaliya_shawl', 1999, 45, 'in stock', 'Elegant men’s shawl styled in Tangaliya-inspired motif. Probably wool blend; looks formal and festive.', 'craftzonstroreimage/tp5.jpg', '2025-09-17 05:09:18', 'active'),
(36, 'Intricate Pattachitra Art', 'om', 'woodenart', 10700, 40, 'in stock', ' Hand-painted Pattachitra art of Radha & Krishna / Dasha Avatar on wood. Comes with a stand so can be used as wall or table décor. Elaborate traditional motifs.', 'craftzonstroreimage/Intricate_20Pattachitra_20Art_20Wooden_20Wall_Table_20Plaque_20_2818__29_20-_20Radha_20Krishna_2C_20Dasha_20Avatar_20-_20PWP01C.jpg', '2025-09-17 21:34:03', 'active'),
(37, 'Wooden Garuda Statue Anti', 'om', 'woodenart', 23000, 30, 'in stock', 'A beautifully carved wooden icon of Garuda (mythical bird), finished in antique paint / polishing. Ideal for spiritual décor or centrepiece.', 'craftzonstroreimage/4276.jpg', '2025-09-17 21:34:54', 'active'),
(38, 'Leather Puppet Radha‑Kris', 'om', 'leatherart', 8000, 60, 'in stock', 'Wall hanging made with leather puppet work of Radha & Krishna. Traditional folk style, painted leather. Great for ethnic décor.', 'craftzonstroreimage/SFNWBX~1.WEB', '2025-09-17 21:36:43', 'active'),
(39, 'Tripura Bamboo Wall Art –', 'om', 'bambooart', 1999, 30, 'in stock', 'Handcrafted bamboo wall art from Tripura, featuring a Royal Boat design with peacock & temple motifs. Natural bamboo, polished finish.', 'craftzonstroreimage/TR_012_B.jpeg', '2025-09-17 21:41:08', 'active'),
(40, 'Handcrafted Bamboo Lord G', 'om', 'bambooart', 999, 20, 'in stock', 'Small bamboo statue of Lord Ganesh. Colourful finish, used for home or office décor. Eco-friendly piece.', 'craftzonstroreimage/original1.3864253.2.jpg', '2025-09-17 21:42:23', 'active'),
(41, 'Handcrafted Leather Journ', 'om', 'leatherart', 2000, 13, 'in stock', 'Vintage-style handmade leather journal with rustic paper, ideal for sketching, writing, or gifting.', 'craftzonstroreimage/lp1.png', '2025-09-17 21:45:38', 'active'),
(42, 'Traditional Leather Handb', 'om', 'leatherart', 2200, 12, 'in stock', 'Premium-quality handbag made from genuine leather, featuring artisanal stitching and a timeless look.', 'craftzonstroreimage/lp2.jpg', '2025-09-17 21:47:52', 'active');

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
(14, 'TraditionTrove', 'ram', 'vivekkariya22@gmail.com', '32AAJJJ9012J1Z0', 'craftzonstroreimage/ChatGPT Image Sep 16, 2025, 03_32_05 PM~11.png', 'Preserving India’s cultural crafts – textiles, jewelry, and décor items.', '2025-09-17', 'active'),
(15, 'CraftsCulture', 'om', 'omomcollection49@gmail.com', '27AAFFF5678F1Z6', 'craftzonstroreimage/ChatGPT Image Sep 16, 2025, 03_32_05 PM~7.png', ' Fusion of modern and traditional handicrafts, décor items, and accessories.', '2025-09-17', 'active');

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
(7, 18, 2, 8, '2025-09-17 22:47:25');

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
  ADD UNIQUE KEY `productid` (`productid`),
  ADD KEY `fk_ads_seller` (`seller_id`);

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
  ADD PRIMARY KEY (`cancel_id`),
  ADD KEY `fk_cancel_order` (`order_id`),
  ADD KEY `fk_cancel_user` (`ucancelid`);

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
  ADD UNIQUE KEY `uname` (`uname`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auction_table`
--
ALTER TABLE `auction_table`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crafter_story`
--
ALTER TABLE `crafter_story`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `craftorder`
--
ALTER TABLE `craftorder`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `craftus_reg`
--
ALTER TABLE `craftus_reg`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `followid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_table`
--
ALTER TABLE `product_table`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `sellerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `seller_commission`
--
ALTER TABLE `seller_commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD CONSTRAINT `fk_ads_product` FOREIGN KEY (`productid`) REFERENCES `product_table` (`product_id`),
  ADD CONSTRAINT `fk_ads_seller` FOREIGN KEY (`seller_id`) REFERENCES `seller` (`sellerid`);

--
-- Constraints for table `auction_table`
--
ALTER TABLE `auction_table`
  ADD CONSTRAINT `auction_table_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`),
  ADD CONSTRAINT `auction_table_ibfk_2` FOREIGN KEY (`highest_bidder`) REFERENCES `craftus_reg` (`u_id`),
  ADD CONSTRAINT `fk_auction_bidder` FOREIGN KEY (`highest_bidder`) REFERENCES `craftus_reg` (`u_id`),
  ADD CONSTRAINT `fk_auction_product` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`);

--
-- Constraints for table `cancel_orders`
--
ALTER TABLE `cancel_orders`
  ADD CONSTRAINT `fk_cancel_order` FOREIGN KEY (`order_id`) REFERENCES `craftorder` (`orderid`),
  ADD CONSTRAINT `fk_cancel_user` FOREIGN KEY (`ucancelid`) REFERENCES `craftus_reg` (`u_id`);

--
-- Constraints for table `contactus`
--
ALTER TABLE `contactus`
  ADD CONSTRAINT `contactus_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_contact_user` FOREIGN KEY (`user_id`) REFERENCES `craftus_reg` (`u_id`);

--
-- Constraints for table `crafter_story`
--
ALTER TABLE `crafter_story`
  ADD CONSTRAINT `crafter_story_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller` (`sellerid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_story_seller` FOREIGN KEY (`seller_id`) REFERENCES `seller` (`sellerid`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `fk_feedback_order` FOREIGN KEY (`order_id`) REFERENCES `craftorder` (`orderid`);

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
