-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 10:26 AM
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
-- Database: `velvetvials`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `adminID` int(11) NOT NULL,
  `adminName` varchar(100) NOT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `adminPassword` varchar(255) NOT NULL,
  `adminPhoneNum` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`adminID`, `adminName`, `adminEmail`, `adminPassword`, `adminPhoneNum`) VALUES
(1, 'VelvetyMe', 'velvetVials@gmail.com', '$2y$10$Z//xuN92rQYhYb2kLkE7Retgh/WRfBs1nScDV5nOpTSh3AxAgIIHe', '019-9637483');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderID` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `orderDate` datetime DEFAULT current_timestamp(),
  `orderTotPay` decimal(10,2) DEFAULT NULL,
  `orderMethodPay` varchar(255) DEFAULT NULL,
  `orderStatus` enum('Pending','Shipped','Cancelled') DEFAULT 'Pending',
  `deliveryDate` datetime DEFAULT (current_timestamp() + interval 3 day),
  `deliveryAddress` varchar(255) DEFAULT NULL,
  `products` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderID`, `userId`, `orderDate`, `orderTotPay`, `orderMethodPay`, `orderStatus`, `deliveryDate`, `deliveryAddress`, `products`) VALUES
(30, 11, '2025-01-12 17:45:37', 96.38, 'creditCard', 'Shipped', '2025-01-15 17:45:37', 'Perth, Australia', 'a:3:{i:0;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D02\";s:8:\"prodName\";s:23:\" Woodland Sage Diffuser\";s:9:\"prodPrice\";s:5:\"30.00\";s:8:\"quantity\";i:1;}i:2;a:4:{s:6:\"prodID\";s:3:\"P02\";s:8:\"prodName\";s:22:\"Blossom Breeze Perfume\";s:9:\"prodPrice\";s:5:\"49.99\";s:8:\"quantity\";i:1;}}'),
(31, 1, '2025-01-14 16:18:06', 67.40, 'creditCard', 'Shipped', '2025-01-17 16:18:06', 'Uitm Arau, Perlis', 'a:3:{i:0;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D02\";s:8:\"prodName\";s:23:\" Woodland Sage Diffuser\";s:9:\"prodPrice\";s:5:\"30.00\";s:8:\"quantity\";i:1;}i:2;a:4:{s:6:\"prodID\";s:3:\"P02\";s:8:\"prodName\";s:22:\"Blossom Breeze Perfume\";s:9:\"prodPrice\";s:5:\"49.99\";s:8:\"quantity\";i:1;}}'),
(33, 1, '2025-01-14 20:45:39', 104.68, 'creditCard', 'Shipped', '2025-01-17 20:45:39', 'Uitm Arau, Perlis', 'a:3:{i:0;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D02\";s:8:\"prodName\";s:23:\" Woodland Sage Diffuser\";s:9:\"prodPrice\";s:5:\"30.00\";s:8:\"quantity\";i:1;}i:2;a:4:{s:6:\"prodID\";s:3:\"P02\";s:8:\"prodName\";s:22:\"Blossom Breeze Perfume\";s:9:\"prodPrice\";s:5:\"49.99\";s:8:\"quantity\";i:1;}}'),
(34, 1, '2025-01-14 20:48:54', 85.74, 'Paypal', 'Pending', '2025-01-17 20:48:54', 'Uitm Arau, Perlis', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:2;}i:1;a:4:{s:6:\"prodID\";s:3:\"P02\";s:8:\"prodName\";s:22:\"Blossom Breeze Perfume\";s:9:\"prodPrice\";s:5:\"49.99\";s:8:\"quantity\";i:1;}}'),
(35, 1, '2025-01-14 21:18:46', 101.97, 'Credit Card', 'Shipped', '2025-01-17 21:18:46', 'Uitm Arau, Perlis', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:2;}i:1;a:4:{s:6:\"prodID\";s:3:\"P02\";s:8:\"prodName\";s:22:\"Blossom Breeze Perfume\";s:9:\"prodPrice\";s:5:\"49.99\";s:8:\"quantity\";i:1;}}'),
(39, 11, '2025-01-17 01:18:49', 45.98, 'creditCard', 'Pending', '2025-01-20 01:18:49', 'Perth, Australia', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:4:\"SC01\";s:8:\"prodName\";s:18:\"Velvet Rose Candle\";s:9:\"prodPrice\";s:5:\"19.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:1;}}'),
(40, 11, '2025-01-17 01:34:33', 15.74, 'Credit Card', 'Pending', '2025-01-20 01:34:33', 'Perth, Australia', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:4:\"SC01\";s:8:\"prodName\";s:18:\"Velvet Rose Candle\";s:9:\"prodPrice\";s:5:\"19.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:1;}}'),
(41, 11, '2025-01-17 02:03:31', 51.82, 'Credit Card', 'Pending', '2025-01-20 02:03:31', 'Perth, Australia', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:4:\"SC01\";s:8:\"prodName\";s:18:\"Velvet Rose Candle\";s:9:\"prodPrice\";s:5:\"19.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:2;}}'),
(43, 11, '2025-01-17 02:27:27', 43.18, 'Credit Card', 'Pending', '2025-01-20 02:27:27', 'Perth, Australia', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:4:\"SC01\";s:8:\"prodName\";s:18:\"Velvet Rose Candle\";s:9:\"prodPrice\";s:5:\"19.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:2;}}'),
(44, 11, '2025-01-17 02:28:10', 48.94, 'Credit Card', 'Pending', '2025-01-20 02:28:10', 'Perth, Australia', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:4:\"SC01\";s:8:\"prodName\";s:18:\"Velvet Rose Candle\";s:9:\"prodPrice\";s:5:\"19.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:2;}}'),
(45, 11, '2025-01-17 02:39:27', 57.58, 'Credit Card', 'Pending', '2025-01-20 02:39:27', 'Perth, Australia', 'a:2:{i:0;a:4:{s:6:\"prodID\";s:4:\"SC01\";s:8:\"prodName\";s:18:\"Velvet Rose Candle\";s:9:\"prodPrice\";s:5:\"19.99\";s:8:\"quantity\";i:1;}i:1;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:2;}}'),
(50, 11, '2025-01-19 04:16:40', 32.50, 'Credit Card', 'Pending', '2025-01-22 04:16:40', 'Perth, Australia', 'a:1:{i:0;a:4:{s:6:\"prodID\";s:3:\"D02\";s:8:\"prodName\";s:23:\" Woodland Sage Diffuser\";s:9:\"prodPrice\";s:5:\"30.00\";s:8:\"quantity\";i:1;}}'),
(51, 23, '2025-01-19 04:18:19', 24.00, 'Credit Card', 'Pending', '2025-01-22 04:18:19', 'Kajang', 'a:1:{i:0;a:4:{s:6:\"prodID\";s:3:\"D02\";s:8:\"prodName\";s:23:\" Woodland Sage Diffuser\";s:9:\"prodPrice\";s:5:\"30.00\";s:8:\"quantity\";i:1;}}'),
(52, 23, '2025-01-19 12:33:07', 32.50, 'Credit Card', 'Pending', '2025-01-22 12:33:07', 'Kajang', 'a:1:{i:0;a:4:{s:6:\"prodID\";s:3:\"D02\";s:8:\"prodName\";s:23:\" Woodland Sage Diffuser\";s:9:\"prodPrice\";s:5:\"30.00\";s:8:\"quantity\";i:1;}}'),
(57, 1, '2025-01-19 14:50:08', 32.50, 'Credit Card', 'Pending', '2025-01-22 14:50:08', 'Uitm Arau, Perlis', 'a:1:{i:0;a:4:{s:6:\"prodID\";s:3:\"D02\";s:8:\"prodName\";s:23:\" Woodland Sage Diffuser\";s:9:\"prodPrice\";s:5:\"30.00\";s:8:\"quantity\";i:1;}}'),
(58, 11, '2025-01-19 15:52:11', 28.69, 'Credit Card', 'Pending', '2025-01-22 15:52:11', 'Perth, Australia', 'a:1:{i:0;a:4:{s:6:\"prodID\";s:3:\"D01\";s:8:\"prodName\";s:21:\"Ocean Breeze Diffuser\";s:9:\"prodPrice\";s:5:\"25.99\";s:8:\"quantity\";i:1;}}');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prodID` varchar(255) NOT NULL,
  `prodName` varchar(255) NOT NULL,
  `prodCat` varchar(100) NOT NULL,
  `prodDesc` text DEFAULT NULL,
  `prodPrice` decimal(10,2) NOT NULL,
  `prodStock` int(11) NOT NULL,
  `prodImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prodID`, `prodName`, `prodCat`, `prodDesc`, `prodPrice`, `prodStock`, `prodImage`) VALUES
('D01', 'Ocean Breeze Diffuser', 'Diffuser', 'Crisp marine notes with a touch of salt and sea air, evoking the ocean.', 25.00, 0, 'images/diffuser/1.png'),
('D02', ' Woodland Sage Diffuser', 'Diffuser', 'Earthy and herbal, this diffuser brings the freshness of the outdoors inside.', 30.00, 103, 'images/diffuser/3.png'),
('D03', 'Peony Blossom Diffuser', 'Diffuser', 'A sweet and delicate floral fragrance to brighten up any space.', 27.99, 80, 'images/diffuser/4.png'),
('D04', 'Cedar & Pine Diffuser', 'Diffuser', 'Warm and woody, perfect for creating a cozy cabin-like environment', 22.00, 50, 'images/diffuser/2.png'),
('E01', 'Peppermint Essential Oil', 'Aromatherapy', 'A cool, invigorating oil perfect for focus or refreshing tired muscles.', 16.99, 55, 'images/essentialOil/1.png'),
('E02', 'Eucalyptus Essential Oil', 'Aromatherapy', 'Ideal for clearing the mind, with a fresh, medicinal scent to open airways.', 15.99, 66, 'images/essentialOil/2.png'),
('E03', 'Lavender Essential Oil', 'Aromatherapy', 'Known for its calming properties, great for relaxation and better sleep.', 17.99, 65, 'images/essentialOil/3.png'),
('E04', ' Lemon Essential Oil', 'Aromatherapy', 'Energizing and zesty, perfect for boosting mood and cleaning purposes.', 14.99, 77, 'images/essentialOil/4.png'),
('P01', 'Midnight Noir Eau de Parfum', 'Perfume', 'A sensual blend of dark berries, patchouli, and smoky incense for a mysterious and alluring scent.', 39.99, 150, 'images/perfume/5.png'),
('P02', 'Blossom Breeze Perfume', 'Perfume', 'A rich floral fragrance featuring jasmine, lily of the valley, and a hint of citrus.', 49.99, 120, 'images/perfume/6.png'),
('P03', 'Ocean Breeze Cologne', 'Perfume', 'A refreshing cologne with a cool sea breeze aroma, complemented by notes of citrus and ocean mist.', 29.99, 200, 'images/perfume/7.png'),
('P04', 'Velvet Oud Perfume', 'Perfume', 'A luxurious and rich combination of oud wood, amber, and a touch of saffron, perfect for evening wear.\r\n', 45.00, 100, 'images/perfume/4.png'),
('R01', 'Crisp Linen Room Spray', 'Room Spray', 'The fresh, clean scent of just-washed linen with a subtle floral note.', 22.99, 88, 'images/RoomSpray/6.png'),
('R02', 'Lemon Verbena Room Spray', 'Room Spray', 'Invigorating lemon with verbena, perfect for uplifting any space.', 21.99, 65, 'images/RoomSpray/4.png'),
('R03', 'Cashmere & Silk Room Spray', 'Room Spray', 'A luxurious blend of soft cashmere and rich vanilla for a comforting feel.', 23.99, 55, 'images/RoomSpray/3.png'),
('R04', 'Winter Spice Room Spray', 'Room Spray', 'Cinnamon, clove, and orange zest blend together for a warm, festive scent.', 22.99, 76, 'images/RoomSpray/5.png'),
('R05', 'Lavender & Chamomile Room Spray', 'Room Spray', 'Earthy and grounding, often used for meditation and stress relief.', 23.99, 69, 'images/RoomSpray/1.png'),
('SC01', 'Velvet Rose Candle', 'Scented Candle', 'A calming blend of rose petals and soft musk for a romantic ambiance.', 19.99, 79, 'images/candles/5.png'),
('SC02', 'Lavender Fields Candle', 'Scented Candle', 'Relaxing lavender with hints of eucalyptus for a calming atmosphere.', 15.99, 60, 'images/candles/4.png'),
('SC03', 'Amber & Sandalwood Candle', 'Scented Candle', 'A warm and earthy fragrance that adds depth and coziness to any room.', 18.00, 100, 'images/candles/6.png'),
('SC04', 'Citrus Zest Candle', 'Scented Candle', 'Bright, energizing notes of lemon, lime, and orange, perfect for mornings.', 22.50, 70, 'images/candles/3.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userFname` varchar(100) NOT NULL,
  `userLname` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPhone` varchar(15) NOT NULL,
  `userAddress` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userMembership` varchar(10) NOT NULL,
  `signup_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `userStatus` enum('active','inactive') DEFAULT 'active',
  `userPrefStatus` varchar(50) DEFAULT NULL,
  `loyaltyPoint` int(11) DEFAULT 0,
  `loyaltyEarn` int(11) DEFAULT 0,
  `loyaltyRedeem` int(11) DEFAULT 0,
  `lastCheckIn` datetime DEFAULT NULL,
  `membershipExpiryDate` date DEFAULT NULL,
  `membershipRequest` varchar(50) DEFAULT NULL,
  `isRequestApproved` tinyint(1) DEFAULT 0,
  `approveStat` enum('Pending','Approved') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userFname`, `userLname`, `userEmail`, `userPhone`, `userAddress`, `userPassword`, `userMembership`, `signup_date`, `userStatus`, `userPrefStatus`, `loyaltyPoint`, `loyaltyEarn`, `loyaltyRedeem`, `lastCheckIn`, `membershipExpiryDate`, `membershipRequest`, `isRequestApproved`, `approveStat`) VALUES
(1, 'syuha', 'nasir', 'syuhana@gmail.com', '013-2963561', 'Uitm Arau, Perlis', '$2y$10$r/0iZ2ihNqS5V6t6gIzTbeMYt9Sc0XYg/S3njtPLQqwd6z1jhElo6', 'Silver', '2025-01-04 16:31:35', 'active', 'Peppermint, lavender', 393, 25, 199, '2025-01-14 13:44:00', '2025-02-18', NULL, 0, NULL),
(11, 'Anis', 'Yasmin', 'aniya@gmail.com', '010-9087654', 'Perth, Australia', '$2y$10$nIVfZSZ5uB.QzhZOwuTElu/agGV2cZo4tiVNr52iBJ6Fx06tvDVvK', 'Silver', '2025-01-12 07:17:22', 'active', 'Floral', 360, 15, 90, '2025-01-18 12:37:11', '2025-02-18', NULL, 1, NULL),
(23, 'Ain', 'Muthalib', 'ain@gmail.com', '0122365895', 'Kajang', '$2y$10$XK5nK6GmoBV.RM15DpMyOeJJc06Nlvrm.EC36Kr/GP33A5z/PuSIC', 'Silver', '2025-01-18 07:10:38', 'active', NULL, 57, 5, 24, '2025-01-18 08:49:24', '2025-02-19', NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `adminEmail` (`adminEmail`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prodID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
