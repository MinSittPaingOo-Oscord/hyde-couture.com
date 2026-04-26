-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 07:26 PM
-- Server version: 8.0.33
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rangsit_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `accountID` int NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL,
  `passcode` varchar(2000) NOT NULL,
  `phoneNumber` varchar(500) NOT NULL,
  `birthday` date NOT NULL,
  `roleID` int NOT NULL,
  `pin` varchar(6) DEFAULT NULL,
  `profile` int DEFAULT NULL,
  `registerDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accountID`, `name`, `email`, `passcode`, `phoneNumber`, `birthday`, `roleID`, `pin`, `profile`, `registerDate`) VALUES
(1, 'Min Sitt Paing Oo', 'minsitt.p67@rsu.ac.th', 'Thanoswasright@198989', '0823059272', '2004-06-30', 2, '198989', 156, '2025-10-01'),
(2, 'Jennifer', 'nikkijen1411@gmail.com', 'Thanoswasright@1989', '09952090401', '2004-11-14', 1, NULL, 35, '2025-09-18'),
(3, 'Myat Thiri Khaing', 'myatthirikhaing@gmail.com', 'Thanoswasright@1989', '09952090401', '2005-02-16', 1, NULL, 36, '2025-11-01'),
(4, 'Taylor Swift', 'taylorswift@gmail.com', 'Thanoswasright@1989', '0823059272', '1989-12-13', 1, NULL, 38, '2025-10-30');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressID` int NOT NULL,
  `street` varchar(2000) NOT NULL,
  `township` varchar(2000) NOT NULL,
  `city` varchar(2000) NOT NULL,
  `state` varchar(2000) NOT NULL,
  `postalCode` varchar(2000) NOT NULL,
  `country` varchar(2000) NOT NULL,
  `completeAddress` varchar(2000) NOT NULL,
  `mapLink` varchar(2000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `accountID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`addressID`, `street`, `township`, `city`, `state`, `postalCode`, `country`, `completeAddress`, `mapLink`, `accountID`) VALUES
(1, 'Ek Charoean Alley 6', 'Lak Hok', 'Bangkok', 'Mueang Pathum Thani', '12000', 'Thailand', 'Ek Charoen 6 Alley, Lak Hok, Mueang Pathum Thani District, Pathum Thani 12000', 'https://maps.app.goo.gl/z3gLy4EaWnToyFDCA', 1),
(2, '17th street', 'Aung Myae Thar Zan', 'Mandalay', 'Mandalay', '05011', 'Myanmar', 'Cornor of 17th & 89th street, Chan Aye Thar Zan township, Mandalay', '', 2),
(3, '62th street', 'Aung Myae Thar Zan', 'Yangon', 'Yangon', '11421', 'Myanmar', '62th street, between 19th and 20th street, Yangon,Myanmar', '', 3),
(4, '19th street', 'North Dagon', 'Khao Yai', 'Pak Chong', '12000', 'Thailand', 'room 39, Movin\' pick resort, Khao Yai', 'https://maps.app.goo.gl/eDrSZrL1ZrBjjf2o6', 4),
(32, 'RS', 'rs', 'Bangkok', 'rs', 'rs', 'Thailand', 'RS, rs, rs, Bangkok, Thailand rs', 'https://maps.app.goo.gl/eaz5gCH24up9iJW98', NULL),
(33, 'Ek Chron 6 Alley', 'Lak Hok', 'Bangkok', 'Pathum Thani', '120000', 'Thailand', 'Plum Condo 69, Phase 3, Ek Chron 6 Alley, Lak Hok, Pathum Thani, Bangkok, Thailand 120000', '', NULL),
(34, '11 street', 'Aung Myae Thar Zan', 'Mandalay', 'Mandalay', '00011', 'Myanmar', '11 street, Aung Myae Thar Zan, Mandalay, Mandalay, Myanmar 00011', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int NOT NULL,
  `categoryName` varchar(500) NOT NULL,
  `parentID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`, `parentID`) VALUES
(1, 'Men', NULL),
(2, 'Women', NULL),
(3, 'Accessories', NULL),
(4, 'Footwear', NULL),
(5, 'New Arrival', NULL),
(6, 'Featured Collections', NULL),
(7, 'Shirts', 1),
(8, 'Shirts', 2),
(9, 'T-Shirts', 1),
(10, 'T-Shirts', 2),
(11, 'Pants', 1),
(12, 'Pants', 2),
(13, 'Jeans', 1),
(14, 'Jeans', 2),
(15, 'Jacket', 1),
(16, 'Jacket', 2),
(17, 'Dresses', 2),
(18, 'Skirts', 2),
(20, 'Tops', 1),
(21, 'Tops', 2),
(22, 'Unisex', NULL),
(23, 'Activewear', NULL),
(24, 'Shirts', 22),
(26, 'Pants', 22),
(27, 'Jeans', 22),
(28, 'Jacket', 22),
(30, 'Tshirts', 22),
(31, 'Hoodies', 1),
(32, 'Hoodies', 2),
(33, 'Hoodies', 22),
(34, 'Sweatpants', 1),
(35, 'Sweatpants', 2),
(36, 'Sweatpants', 22),
(37, 'Graphic Tees', 1),
(38, 'Graphic Tees', 2),
(39, 'Graphic Tees', 22),
(40, 'Beanies', 3),
(41, 'Joggers', 1),
(42, 'Joggers', 22),
(43, 'Shorts', 1),
(44, 'Shorts', 22),
(45, 'Graphic Tees', 1),
(46, 'Graphic Tees', 2),
(47, 'Graphic Tees', 22),
(48, 'Beanies', 3),
(49, 'Joggers', 1),
(50, 'Joggers', 22),
(51, 'Shorts', 1),
(52, 'Shorts', 22);

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `colorID` int NOT NULL,
  `colorName` varchar(500) NOT NULL,
  `colorCode` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `color`
--

INSERT INTO `color` (`colorID`, `colorName`, `colorCode`) VALUES
(1, 'Black', '#000000'),
(2, 'WASHED BLACK', '#1A1A1A'),
(3, 'WASHED NAVY BLUE', '#2D3A4A'),
(4, 'Onyx Black', '#0F0F0F'),
(5, 'Graphite', '#3C3F41'),
(6, 'White', '#ffffff'),
(7, 'Red', '#ff0000'),
(8, 'Navy Blue', '#001F3F'),
(9, 'Forest Green', '#228B22'),
(10, 'Sand Beige', '#C9B7A2'),
(11, 'Olive', '#556B2F'),
(12, 'Grey Melange', '#A9A9A9'),
(13, 'Indigo', '#4B0082'),
(14, 'Olive', '#556B2F'),
(15, 'Grey Melange', '#A9A9A9'),
(16, 'Indigo', '#4B0082');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `discountID` int NOT NULL,
  `range1` int NOT NULL,
  `range2` int DEFAULT NULL,
  `percentage` double NOT NULL,
  `productID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discountID`, `range1`, `range2`, `percentage`, `productID`) VALUES
(1, 10, 19, 10, 1),
(2, 20, 29, 20, 1),
(3, 10, 19, 10, 2),
(4, 20, NULL, 30, 2);

-- --------------------------------------------------------

--
-- Table structure for table `favourite`
--

CREATE TABLE `favourite` (
  `favID` int NOT NULL,
  `accountID` int NOT NULL,
  `productID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `favourite`
--

INSERT INTO `favourite` (`favID`, `accountID`, `productID`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 1, 49),
(4, 1, 48);

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `orderItemID` int NOT NULL,
  `quantity` int NOT NULL,
  `productID` int NOT NULL,
  `totalCost` double NOT NULL,
  `orderID` int NOT NULL,
  `discountedTotalCost` double DEFAULT NULL,
  `color` int NOT NULL,
  `size` int NOT NULL,
  `isStockReduce` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`orderItemID`, `quantity`, `productID`, `totalCost`, `orderID`, `discountedTotalCost`, `color`, `size`, `isStockReduce`) VALUES
(77, 11, 1, 18150, 82, 16335, 1, 1, 1),
(78, 21, 2, 14175, 82, 9922.5, 1, 4, 1),
(79, 1, 3, 500, 82, NULL, 2, 4, 1),
(80, 11, 1, 16335, 83, NULL, 1, 5, NULL),
(81, 21, 2, 9922.499999999998, 83, NULL, 1, 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderr`
--

CREATE TABLE `orderr` (
  `orderID` int NOT NULL,
  `paymentValid` tinyint DEFAULT NULL,
  `totalCost` double DEFAULT NULL,
  `orderDate` date DEFAULT NULL,
  `paymentStatus` int DEFAULT NULL,
  `orderStatus` int DEFAULT NULL,
  `trackingStatus` int DEFAULT NULL,
  `accountID` int DEFAULT NULL,
  `paymentType` int DEFAULT NULL,
  `addressID` int DEFAULT NULL,
  `isManual` tinyint DEFAULT NULL,
  `manualCustomerName` varchar(2000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `manualNote` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `orderr`
--

INSERT INTO `orderr` (`orderID`, `paymentValid`, `totalCost`, `orderDate`, `paymentStatus`, `orderStatus`, `trackingStatus`, `accountID`, `paymentType`, `addressID`, `isManual`, `manualCustomerName`, `manualNote`) VALUES
(82, 1, 26757.5, '2026-04-26', 2, 1, 2, 4, 2, 4, 1, NULL, NULL),
(83, NULL, 26292.5, '2026-04-27', 1, 1, 1, 1, 1, 34, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderstatus`
--

CREATE TABLE `orderstatus` (
  `orderStatusID` int NOT NULL,
  `orderStatus` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `orderstatus`
--

INSERT INTO `orderstatus` (`orderStatusID`, `orderStatus`) VALUES
(1, 'Active Order'),
(2, 'Completed Order'),
(3, 'Failed Order'),
(5, 'Return Order'),
(6, 'Cancal Order');

-- --------------------------------------------------------

--
-- Table structure for table `paymentslip`
--

CREATE TABLE `paymentslip` (
  `paymentSlipID` int NOT NULL,
  `paymentSlip` int NOT NULL,
  `orderID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `paymentstatus`
--

CREATE TABLE `paymentstatus` (
  `paymentStatusID` int NOT NULL,
  `paymentStatus` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `paymentstatus`
--

INSERT INTO `paymentstatus` (`paymentStatusID`, `paymentStatus`) VALUES
(1, 'haven\'t paid'),
(2, 'already paid'),
(3, 'will pay when order arrive');

-- --------------------------------------------------------

--
-- Table structure for table `paymenttype`
--

CREATE TABLE `paymenttype` (
  `paymentTypeID` int NOT NULL,
  `paymentType` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `paymenttype`
--

INSERT INTO `paymenttype` (`paymentTypeID`, `paymentType`) VALUES
(1, 'Cash on delivery'),
(2, 'Bank Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `photoID` int NOT NULL,
  `photoName` varchar(500) NOT NULL,
  `productID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`photoID`, `photoName`, `productID`) VALUES
(1, 'p1_i1.jpg', 1),
(2, 'p1_i2.jpg', 1),
(3, 'p1_i3.jpg', 1),
(4, 'p1_i4.jpg', 1),
(5, 'p1_i5.jpg', 1),
(8, 'p2_i2.jpg', 2),
(9, 'p2_i3.jpg', 2),
(10, 'p2_i4.jpg', 2),
(11, 'p2_i5.jpg', 2),
(12, 'p2_i6.jpg', 2),
(13, 'p2_i7.jpg', 2),
(14, 'p3_i1.jpg', 3),
(15, 'p3_i2.jpg', 3),
(16, 'p3_i3.jpg', 3),
(17, 'p3_i4.jpg', 3),
(18, 'p4_i1.jpg', 4),
(19, 'p4_i2.jpg', 4),
(20, 'p4_i3.jpg', 4),
(21, 'p4_i4.jpg', 4),
(22, 'p5_i1.jpg', 5),
(23, 'p5_i2.jpg', 5),
(24, 'p5_i3.jpg', 5),
(25, 'p5_i4.jpg', 5),
(26, 'p5_i5.jpg', 5),
(28, 'p6_i1.jpg', 6),
(29, 'p6_i2.jpg', 6),
(30, 'p6_i3.jpg', 6),
(31, 'p6_i4.jpg', 6),
(32, 'slip_order3_1.jpg', NULL),
(33, 'slip_order4_1.jpg', NULL),
(34, 'slip_order4_2.jpg', NULL),
(35, 'jennifer.jpg', NULL),
(36, 'myat.jpg', NULL),
(37, 'minsitt.jpg', NULL),
(38, 'taylor.jpg', NULL),
(39, 'hhh.jpg', NULL),
(40, 'photo_2025-02-12_00-54-12 - Copy.jpg', NULL),
(41, 'Purple Black Simple Music CD Cover.png', NULL),
(42, 'photo_2025-02-12_00-54-12 - Copy.jpg', NULL),
(56, 'profile_69438676acc326.81532826.jpg', NULL),
(57, 'paymentSlip_6948f2680d7aa4.97234463.jpg', NULL),
(58, 'paymentSlip_6948f2e9d674a0.88582765.jpg', NULL),
(59, 'Screenshot 2025-10-22 at 4.19.34 PM.png', NULL),
(60, 'minsitt.jpg', NULL),
(61, 'paymentSlip_696a37c06b05f4.52388053.jpg', NULL),
(62, 'IMG_1088.JPG', NULL),
(63, 'IMG_1088.JPG', NULL),
(64, 'minsitt.jpg', NULL),
(65, 'paymentSlip_69838486d70700.92088116.jpg', NULL),
(66, 'paymentSlip_698384906447e6.88017636.jpg', NULL),
(67, 'paymentSlip_698387c8a66c98.08596404.jpg', NULL),
(68, 'paymentSlip_698625ec284209.06066564.jpg', NULL),
(69, 'p7_i69bd8f97b18fb.png', 7),
(70, 'p7_i69bd8f97b20c3.png', 7),
(71, 'p7_i69bd8f97b2535.png', 7),
(72, 'p8_i69bd8fab5d7a9.png', 8),
(73, 'p8_i69bd8fab5dea6.png', 8),
(74, 'p8_i69bd8fab5e97d.png', 8),
(75, 'p9_i69bd8fe79a982.png', 9),
(76, 'p9_i69bd8fe79b24d.png', 9),
(77, 'p9_i69bd8fe79b886.png', 9),
(78, 'p32_i69bda1cd2571e.png', 32),
(79, 'p32_i69bda1cd26ae1.png', 32),
(80, 'p32_i69bda1cd27531.png', 32),
(81, 'p32_i69bda1cd28968.png', 32),
(82, 'p33_i69bda1dd57e32.png', 33),
(83, 'p33_i69bda1dd5891e.png', 33),
(84, 'p33_i69bda1dd590e4.png', 33),
(85, 'p33_i69bda1dd594d2.png', 33),
(86, 'p34_i69bda1f7aeac4.png', 34),
(87, 'p34_i69bda1f7af14f.png', 34),
(88, 'p34_i69bda1f7af4a1.png', 34),
(89, 'p34_i69bda1f7af7a6.png', 34),
(90, 'p35_i69bda21daeac6.png', 35),
(91, 'p35_i69bda21daf251.png', 35),
(92, 'p35_i69bda21daf6b8.png', 35),
(93, 'p35_i69bda21dafa72.png', 35),
(94, 'p36_i69bda24e3cd77.png', 36),
(95, 'p36_i69bda24e3d35d.png', 36),
(96, 'p36_i69bda24e3d7c8.png', 36),
(97, 'p36_i69bda24e3ddf3.png', 36),
(98, 'p37_i69bda270a603a.png', 37),
(99, 'p37_i69bda270a653f.png', 37),
(100, 'p37_i69bda270a689e.png', 37),
(101, 'p37_i69bda270a6cee.png', 37),
(102, 'p51_i69bda29edb37e.png', 51),
(103, 'p51_i69bda29edbdd5.png', 51),
(104, 'p51_i69bda29edc779.png', 51),
(105, 'p51_i69bda29edcc67.png', 51),
(106, 'p50_i69bda2b1322e5.png', 50),
(107, 'p50_i69bda2b13270e.png', 50),
(108, 'p50_i69bda2b132ab5.png', 50),
(109, 'p50_i69bda2b132f94.png', 50),
(110, 'p49_i69bda2bd9961c.png', 49),
(111, 'p49_i69bda2bd99a32.png', 49),
(112, 'p49_i69bda2bd99dfb.png', 49),
(113, 'p49_i69bda2bd9a5db.png', 49),
(114, 'p48_i69bda2e28b9be.png', 48),
(115, 'p48_i69bda2e28bf55.png', 48),
(116, 'p48_i69bda2e28c4bf.png', 48),
(117, 'p48_i69bda2e28c80a.png', 48),
(118, 'p47_i69bda368e049e.png', 47),
(119, 'p47_i69bda368e0f17.png', 47),
(120, 'p47_i69bda368e1735.png', 47),
(121, 'p47_i69bda368e1d2f.png', 47),
(122, 'p46_i69bda37fafc82.png', 46),
(123, 'p46_i69bda37fb047e.png', 46),
(124, 'p46_i69bda37fb082e.png', 46),
(125, 'p46_i69bda37fb0b90.png', 46),
(126, 'p45_i69bda3995731b.png', 45),
(127, 'p45_i69bda39957aaf.png', 45),
(128, 'p45_i69bda39957f49.png', 45),
(129, 'p45_i69bda3995846b.png', 45),
(130, 'p44_i69bda3b52a36f.png', 44),
(131, 'p44_i69bda3b52a758.png', 44),
(132, 'p44_i69bda3b52ab31.png', 44),
(133, 'p44_i69bda3b52af8d.png', 44),
(134, 'p43_i69bda3d916398.png', 43),
(135, 'p43_i69bda3d916a29.png', 43),
(136, 'p43_i69bda3d916e82.png', 43),
(137, 'p43_i69bda3d9172b8.png', 43),
(138, 'p42_i69bda44a7e1a7.png', 42),
(139, 'p42_i69bda44a7e72f.png', 42),
(140, 'p42_i69bda44a7eb40.png', 42),
(141, 'p42_i69bda44a7f0f6.png', 42),
(142, 'p41_i69bda46f38043.png', 41),
(143, 'p41_i69bda46f3860e.png', 41),
(144, 'p41_i69bda46f3892e.png', 41),
(145, 'p41_i69bda46f38bfa.png', 41),
(146, 'p40_i69bda49205c0b.png', 40),
(147, 'p40_i69bda492061fd.png', 40),
(148, 'p40_i69bda492067f7.png', 40),
(149, 'p40_i69bda49206d1d.png', 40),
(150, 'p39_i69bda4a792400.png', 39),
(151, 'p39_i69bda4a792a0e.png', 39),
(152, 'p39_i69bda4a792f7f.png', 39),
(153, 'p39_i69bda4a793593.png', 39),
(154, 'p38_i69bda4f5f25d1.png', 38),
(155, 'p38_i69bda4f5f2bbe.png', 38),
(156, 'scarlet-witch-shattered-reality-uishdkm8023yg9x0.jpg', NULL),
(157, 'paymentSlip_69ecf18495293.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int NOT NULL,
  `productName` varchar(500) NOT NULL,
  `price` double NOT NULL,
  `discountedPrice` double DEFAULT NULL,
  `postedDate` date DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `waitingWeek` int NOT NULL,
  `preorder` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productName`, `price`, `discountedPrice`, `postedDate`, `description`, `waitingWeek`, `preorder`) VALUES
(1, 'ORIGAMI FADED WASH JEANS', 1650, NULL, '2025-10-31', 'AVAILABLE At Mercury physical stores at Taung Gyi', 4, 1),
(2, 'VERVESV OG CLASSIC LOGO TEE', 775, 675, '2025-10-30', 'Crafted from 100% cotton 210gsm midweight single jersey fabric. 1x1 rib round neck. \nNew boxy cropped fit silhouette. \nOne of our signature rainbow reflective logo print on front. Cut, sewn and printed in Yangon. Designed by Vervesv in Bangkok.', 2, 1),
(3, 'VERVESV Cotton Leather 6 Panel Hat', 575, 500, '2025-10-15', '• 100% washed cotton + PU leather\n• Embroidery eyelets\n• Flat embroidery logo\n• Tri glide buckle \n• Single stitch detailing at back \n• PU leather button on top\n• 6 panel cut \n• Designed by vervesv in Bangkok\n• Cut & sewn in China \n• Embroidered in Yangon', 2, 1),
(4, 'Iconic V3 sweatshirt in black', 1650, 1550, '2025-10-01', '• 420gsm heavyweight loopback terry \n• 85% cotton 15% polyester mixed\n• 450gsm 1x1 ribbed cuff, hem & round neck\n• Iconic artwork printed on front & back\n• Loose fit cut & streetwear silhouette\n• Cut & sewn in China\n• Printed & finished in Myanmar', 2, 0),
(5, 'Druga UV protection jacket', 2000, 1910, '2025-10-01', '• UPF50+ protection\n• 50g flyweight material \n• Water resistance \n• Double zipper closure\n• Hidden extra large pocket \n• For outdoors & sports', 3, 0),
(6, 'HEMi BACKLESS DRESS in charcoal', 775, NULL, '2025-10-02', 'Where minimalism meets bold elegance. \nCrafted from 95% polyester 5% elastane 4 way stretch fabric. Designed to hug every curve while showcasing an effortlessly chic open-back cut, this dress redefines sophistication.\nAvailable exclusively online, the Hemi Backless is the statement piece you didn’t know you needed – until now. Pair it with heels for a night out, or make it your go-to power outfit.', 2, 0),
(7, 'HYDE SIGNATURE OVERSIZED HOODIE', 1450, 1250, '2026-03-20', 'Premium heavyweight French terry cotton hoodie. Boxy oversized fit with ribbed cuffs and hem. Signature VERVESV embroidery on front and back. Cut and sewn in Myanmar.', 2, 0),
(8, 'HYDE UTILITY CARGO SWEATPANTS', 950, NULL, '2026-03-20', 'Relaxed fit cargo sweatpants featuring multiple utility pockets. Made from premium 350gsm cotton fleece for ultimate comfort. Elastic waistband with drawstring. Designed in Bangkok, sewn in Yangon.', 3, 1),
(9, 'HYDE VINTAGE TRUCKER JACKET', 3200, 2800, '2026-03-20', 'Classic trucker style jacket in premium genuine leather. Lined with soft cotton. Snap button closure, multiple pockets. Timeless streetwear essential. Made in Myanmar.', 5, 0),
(32, 'HYDE REFLECTIVE LOGO ZIP HOODIE', 1850, NULL, '2026-03-21', 'Heavyweight 380gsm fleece zip-up hoodie. Reflective VERVESV logo on chest and back. Kangaroo pocket, ribbed cuffs. Designed in Bangkok.', 3, 1),
(33, 'HYDE WIDE-LEG BAGGY JEANS', 1450, 1250, '2026-03-21', 'Light wash baggy wide-leg jeans. Low-rise relaxed fit with destroyed details at knees. 100% cotton denim.', 2, 0),
(34, 'HYDE MINIMAL BOMBER JACKET', 2200, 1950, '2026-03-21', 'Navy blue lightweight bomber. Satin finish outer, quilted lining. Ribbed collar/cuffs/hem. Hidden pockets.', 4, 0),
(35, 'HYDE CROPPED HEAVY SWEATSHIRT', 950, NULL, '2026-03-21', 'Boxy cropped sweatshirt in grey melange. 400gsm loopback cotton. Dropped shoulders, ribbed trim.', 2, 0),
(36, 'HYDE SIGNATURE EMBROIDERED BEANIE', 450, NULL, '2026-03-21', 'Classic cuffed beanie in black acrylic knit. Flat embroidered VERVESV logo. One size fits most.', 1, 0),
(37, 'HYDE FLAME GRAPHIC TEE', 750, 650, '2026-03-21', 'Oversized 210gsm cotton tee with flame graphic print. Distressed wash finish. Cut & sewn in Yangon.', 1, 0),
(38, 'HYDE HEAVY FLEECE ZIP HOODIE', 1950, NULL, '2026-03-21', 'Premium 400gsm fleece zip hoodie with contrast lining. Embroidery logo on sleeve.', 3, 1),
(39, 'HYDE BAGGY CARGO JEANS', 1550, 1350, '2026-03-21', 'Dark wash baggy jeans with cargo pockets and adjustable hem. Streetwear essential.', 2, 0),
(40, 'HYDE SATIN BOMBER JACKET', 2300, 2100, '2026-03-21', 'Olive satin bomber jacket with quilted interior and ribbed details.', 4, 0),
(41, 'HYDE CROP TOP SWEATSHIRT', 850, NULL, '2026-03-21', 'Women/unisex cropped heavy sweatshirt in sand beige. Perfect layering piece.', 2, 0),
(42, 'HYDE WINTER BEANIE WITH PATCH', 480, NULL, '2026-03-21', 'Thick knit beanie with leather patch logo. Unisex accessory.', 1, 0),
(43, 'HYDE RELAXED JOGGERS', 1050, 950, '2026-03-21', 'Heavy fleece joggers with side zip pockets and elastic cuffs.', 2, 0),
(44, 'HYDE OVERSIZED DENIM JACKET', 2450, 2150, '2026-03-21', 'Indigo oversized denim trucker jacket with vintage wash.', 4, 0),
(45, 'HYDE LIGHTWEIGHT WINDBREAKER', 1750, NULL, '2026-03-21', 'Olive tech windbreaker with UPF50+ and hidden pockets.', 3, 0),
(46, 'HYDE CREW NECK SWEATER', 1350, 1250, '2026-03-21', 'Heavyweight 420gsm crew neck sweater in grey melange.', 2, 0),
(47, 'HYDE 3-PACK GRAPHIC TEE SET', 1650, NULL, '2026-03-21', 'Set of 3 oversized graphic tees (black, white, olive).', 1, 1),
(48, 'HYDE REFLECTIVE RUNNING JACKET', 2650, 2350, '2026-03-21', 'Tech reflective jacket with zip-off hood. Perfect for night runs.', 3, 0),
(49, 'HYDE VINTAGE WASH JEANS', 1580, 1380, '2026-03-21', 'Medium wash straight-leg jeans with subtle distressing.', 2, 0),
(50, 'HYDE LEATHER TRUCKER HAT', 650, NULL, '2026-03-21', 'Premium leather 6-panel trucker hat with embroidered logo.', 1, 0),
(51, 'HYDE TECH CARGO SHORTS', 980, 880, '2026-03-21', 'Relaxed cargo shorts with multiple utility pockets and drawstring.', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `productxcategory`
--

CREATE TABLE `productxcategory` (
  `productxcategoryID` int NOT NULL,
  `productID` int NOT NULL,
  `categoryID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `productxcategory`
--

INSERT INTO `productxcategory` (`productxcategoryID`, `productID`, `categoryID`) VALUES
(3, 2, 9),
(4, 2, 10),
(5, 3, 3),
(6, 4, 20),
(7, 4, 21),
(11, 5, 15),
(12, 5, 16),
(13, 5, 28),
(14, 6, 17),
(36, 1, 13),
(37, 1, 27),
(38, 1, 14),
(39, 1, 12),
(46, 7, 31),
(47, 7, 33),
(50, 9, 15),
(51, 9, 28),
(52, 8, 34),
(53, 8, 36),
(108, 34, 15),
(109, 34, 28),
(110, 35, 20),
(111, 35, 21),
(112, 36, 40),
(113, 37, 37),
(114, 37, 39),
(115, 51, 43),
(116, 51, 44),
(117, 50, 40),
(118, 49, 13),
(119, 49, 27),
(120, 48, 15),
(121, 48, 28),
(122, 47, 37),
(123, 47, 39),
(124, 46, 20),
(125, 46, 21),
(126, 45, 15),
(127, 45, 28),
(128, 44, 15),
(129, 44, 28),
(130, 43, 41),
(131, 43, 42),
(132, 42, 40),
(133, 41, 32),
(134, 41, 21),
(135, 40, 15),
(136, 40, 28),
(137, 39, 13),
(138, 39, 27),
(139, 38, 31),
(140, 38, 33),
(141, 32, 6),
(142, 32, 5),
(143, 33, 6),
(144, 33, 5);

-- --------------------------------------------------------

--
-- Table structure for table `relatedproduct`
--

CREATE TABLE `relatedproduct` (
  `relatedProductID` int NOT NULL,
  `productID1` int NOT NULL,
  `productID2` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `relatedproduct`
--

INSERT INTO `relatedproduct` (`relatedProductID`, `productID1`, `productID2`) VALUES
(1, 5, 4),
(28, 1, 4),
(29, 4, 1),
(30, 1, 5),
(31, 5, 1),
(126, 34, 3),
(127, 3, 34),
(128, 34, 6),
(129, 6, 34),
(146, 37, 35),
(147, 35, 37),
(148, 37, 36),
(149, 36, 37),
(150, 51, 34),
(151, 34, 51),
(152, 50, 34),
(153, 34, 50),
(154, 49, 34),
(155, 34, 49),
(156, 48, 34),
(157, 34, 48),
(158, 47, 34),
(159, 34, 47),
(160, 46, 34),
(161, 34, 46),
(162, 42, 50),
(163, 50, 42),
(164, 42, 3),
(165, 3, 42),
(166, 41, 45),
(167, 45, 41),
(168, 41, 46),
(169, 46, 41),
(180, 38, 35),
(181, 35, 38),
(182, 32, 35),
(183, 35, 32),
(184, 32, 36),
(185, 36, 32),
(186, 32, 37),
(187, 37, 32),
(188, 32, 38),
(189, 38, 32),
(190, 32, 39),
(191, 39, 32),
(192, 32, 2),
(193, 2, 32),
(194, 32, 3),
(195, 3, 32),
(196, 33, 36),
(197, 36, 33),
(198, 33, 37),
(199, 37, 33),
(200, 33, 38),
(201, 38, 33),
(202, 33, 39),
(203, 39, 33),
(204, 33, 40),
(205, 40, 33);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `reviewID` int NOT NULL,
  `review` longtext NOT NULL,
  `productID` int NOT NULL,
  `accountID` int NOT NULL,
  `visible` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleID` int NOT NULL,
  `roleName` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`roleID`, `roleName`) VALUES
(1, 'user'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `sizeID` int NOT NULL,
  `sizeName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`sizeID`, `sizeName`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'XXL'),
(6, 'XXXL'),
(7, '4XL'),
(8, '5XL'),
(9, 'XS');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stockID` int NOT NULL,
  `quantity` int NOT NULL,
  `productID` int NOT NULL,
  `sizeID` int NOT NULL,
  `colorID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stockID`, `quantity`, `productID`, `sizeID`, `colorID`) VALUES
(1, 500, 1, 1, 1),
(2, 99, 1, 2, 1),
(3, 99, 1, 3, 1),
(4, 988, 1, 4, 1),
(5, 1000, 1, 5, 1),
(6, 11109, 1, 6, 1),
(7, 2858, 2, 4, 1),
(8, 1000, 2, 5, 1),
(9, 9999, 3, 4, 2),
(10, 7000, 3, 4, 3),
(11, 80000, 4, 4, 1),
(12, 1500, 4, 5, 1),
(13, 4000, 5, 3, 1),
(14, 5000, 5, 4, 1),
(15, 7000, 6, 2, 4),
(16, 1000, 6, 2, 5),
(17, 1000, 6, 3, 4),
(18, 1000, 6, 3, 5),
(19, 10, 6, 4, 4),
(20, 1009, 6, 4, 5),
(21, 10, 6, 5, 4),
(23, 6777, 6, 5, 5),
(27, 80, 7, 9, 1),
(28, 150, 7, 1, 8),
(29, 120, 7, 2, 9),
(30, 90, 7, 4, 10),
(31, 60, 8, 9, 8),
(32, 100, 8, 1, 9),
(33, 200, 8, 3, 1),
(34, 150, 8, 4, 10),
(35, 30, 9, 9, 1),
(36, 40, 9, 1, 8),
(37, 25, 9, 3, 9),
(38, 35, 9, 4, 10),
(43, 60, 32, 2, 1),
(44, 100, 32, 3, 5),
(45, 70, 32, 4, 1),
(46, 50, 32, 9, 5),
(47, 200, 33, 3, 13),
(48, 150, 33, 4, 13),
(49, 100, 33, 1, 1),
(50, 80, 33, 2, 1),
(51, 45, 34, 1, 8),
(52, 90, 34, 2, 1),
(53, 65, 34, 3, 5),
(54, 40, 34, 4, 8),
(55, 130, 35, 2, 12),
(56, 85, 35, 3, 1),
(57, 110, 35, 4, 12),
(58, 55, 35, 1, 5),
(59, 300, 36, 1, 1),
(60, 250, 36, 2, 1),
(61, 200, 36, 3, 1),
(62, 150, 36, 4, 1),
(63, 95, 37, 1, 6),
(64, 140, 37, 2, 1),
(65, 75, 37, 3, 11),
(66, 60, 37, 4, 6),
(67, 55, 38, 2, 1),
(68, 85, 38, 3, 5),
(69, 70, 38, 4, 1),
(70, 45, 38, 9, 5),
(71, 180, 39, 3, 13),
(72, 130, 39, 4, 13),
(73, 95, 39, 1, 1),
(74, 75, 39, 2, 1),
(75, 40, 40, 1, 11),
(76, 75, 40, 2, 1),
(77, 50, 40, 3, 8),
(78, 35, 40, 4, 11),
(79, 110, 41, 2, 10),
(80, 70, 41, 3, 12),
(81, 90, 41, 4, 10),
(82, 50, 41, 1, 6),
(83, 280, 42, 1, 1),
(84, 240, 42, 2, 1),
(85, 190, 42, 3, 1),
(86, 140, 42, 4, 1),
(87, 85, 43, 2, 1),
(88, 120, 43, 3, 9),
(89, 95, 43, 4, 1),
(90, 65, 43, 9, 8),
(91, 35, 44, 1, 13),
(92, 60, 44, 2, 1),
(93, 45, 44, 3, 13),
(94, 30, 44, 4, 1),
(95, 70, 45, 1, 11),
(96, 95, 45, 2, 8),
(97, 55, 45, 3, 11),
(98, 40, 45, 4, 8),
(99, 100, 46, 2, 12),
(100, 65, 46, 3, 1),
(101, 80, 46, 4, 12),
(102, 45, 46, 1, 5),
(103, 150, 47, 1, 1),
(104, 120, 47, 2, 6),
(105, 90, 47, 3, 11),
(106, 70, 47, 4, 1),
(107, 30, 48, 1, 5),
(108, 55, 48, 2, 1),
(109, 40, 48, 3, 5),
(110, 25, 48, 4, 1),
(111, 160, 49, 3, 13),
(112, 110, 49, 4, 13),
(113, 85, 49, 1, 1),
(114, 65, 49, 2, 1),
(115, 190, 50, 1, 1),
(116, 180, 50, 2, 1),
(117, 150, 50, 3, 1),
(118, 120, 50, 4, 1),
(119, 95, 51, 1, 11),
(120, 130, 51, 2, 1),
(121, 75, 51, 3, 12),
(122, 50, 51, 4, 11);

-- --------------------------------------------------------

--
-- Table structure for table `trackingstatus`
--

CREATE TABLE `trackingstatus` (
  `trackingStatusID` int NOT NULL,
  `trackingStatus` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `trackingstatus`
--

INSERT INTO `trackingstatus` (`trackingStatusID`, `trackingStatus`) VALUES
(1, 'packing order'),
(2, 'order shipped'),
(3, 'order delivered');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accountID`),
  ADD KEY `roleID` (`roleID`),
  ADD KEY `profile` (`profile`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressID`),
  ADD KEY `accountID` (`accountID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`),
  ADD KEY `parentID` (`parentID`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`colorID`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discountID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `favourite`
--
ALTER TABLE `favourite`
  ADD PRIMARY KEY (`favID`),
  ADD KEY `userID` (`accountID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`orderItemID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `color` (`color`),
  ADD KEY `size` (`size`);

--
-- Indexes for table `orderr`
--
ALTER TABLE `orderr`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `orderStatus` (`orderStatus`),
  ADD KEY `paymentStatus` (`paymentStatus`),
  ADD KEY `trackingStatus` (`trackingStatus`),
  ADD KEY `paymentType` (`paymentType`),
  ADD KEY `accountID` (`accountID`),
  ADD KEY `orderr_ibfk_6` (`addressID`);

--
-- Indexes for table `orderstatus`
--
ALTER TABLE `orderstatus`
  ADD PRIMARY KEY (`orderStatusID`);

--
-- Indexes for table `paymentslip`
--
ALTER TABLE `paymentslip`
  ADD PRIMARY KEY (`paymentSlipID`),
  ADD KEY `paymentSlip` (`paymentSlip`),
  ADD KEY `orderID` (`orderID`);

--
-- Indexes for table `paymentstatus`
--
ALTER TABLE `paymentstatus`
  ADD PRIMARY KEY (`paymentStatusID`);

--
-- Indexes for table `paymenttype`
--
ALTER TABLE `paymenttype`
  ADD PRIMARY KEY (`paymentTypeID`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`photoID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `productxcategory`
--
ALTER TABLE `productxcategory`
  ADD PRIMARY KEY (`productxcategoryID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `relatedproduct`
--
ALTER TABLE `relatedproduct`
  ADD PRIMARY KEY (`relatedProductID`),
  ADD KEY `productID1` (`productID1`),
  ADD KEY `productID2` (`productID2`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `accountID` (`accountID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleID`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`sizeID`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stockID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `sizeID` (`sizeID`),
  ADD KEY `colorID` (`colorID`);

--
-- Indexes for table `trackingstatus`
--
ALTER TABLE `trackingstatus`
  ADD PRIMARY KEY (`trackingStatusID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `accountID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `colorID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discountID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `favourite`
--
ALTER TABLE `favourite`
  MODIFY `favID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `orderItemID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `orderr`
--
ALTER TABLE `orderr`
  MODIFY `orderID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `orderstatus`
--
ALTER TABLE `orderstatus`
  MODIFY `orderStatusID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paymentslip`
--
ALTER TABLE `paymentslip`
  MODIFY `paymentSlipID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `paymentstatus`
--
ALTER TABLE `paymentstatus`
  MODIFY `paymentStatusID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paymenttype`
--
ALTER TABLE `paymenttype`
  MODIFY `paymentTypeID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `photoID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `productxcategory`
--
ALTER TABLE `productxcategory`
  MODIFY `productxcategoryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `relatedproduct`
--
ALTER TABLE `relatedproduct`
  MODIFY `relatedProductID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `reviewID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `roleID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `sizeID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stockID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `trackingstatus`
--
ALTER TABLE `trackingstatus`
  MODIFY `trackingStatusID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `role` (`roleID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`profile`) REFERENCES `photo` (`photoID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parentID`) REFERENCES `category` (`categoryID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `discount_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `favourite`
--
ALTER TABLE `favourite`
  ADD CONSTRAINT `favourite_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `favourite_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orderr` (`orderID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderitem_ibfk_3` FOREIGN KEY (`color`) REFERENCES `color` (`colorID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderitem_ibfk_4` FOREIGN KEY (`size`) REFERENCES `size` (`sizeID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `orderr`
--
ALTER TABLE `orderr`
  ADD CONSTRAINT `orderr_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderr_ibfk_2` FOREIGN KEY (`orderStatus`) REFERENCES `orderstatus` (`orderStatusID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderr_ibfk_3` FOREIGN KEY (`paymentStatus`) REFERENCES `paymentstatus` (`paymentStatusID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderr_ibfk_4` FOREIGN KEY (`trackingStatus`) REFERENCES `trackingstatus` (`trackingStatusID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderr_ibfk_5` FOREIGN KEY (`paymentType`) REFERENCES `paymenttype` (`paymentTypeID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orderr_ibfk_6` FOREIGN KEY (`addressID`) REFERENCES `address` (`addressID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `paymentslip`
--
ALTER TABLE `paymentslip`
  ADD CONSTRAINT `paymentslip_ibfk_1` FOREIGN KEY (`paymentSlip`) REFERENCES `photo` (`photoID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `paymentslip_ibfk_2` FOREIGN KEY (`orderID`) REFERENCES `orderr` (`orderID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `productxcategory`
--
ALTER TABLE `productxcategory`
  ADD CONSTRAINT `productxcategory_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `productxcategory_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `relatedproduct`
--
ALTER TABLE `relatedproduct`
  ADD CONSTRAINT `relatedproduct_ibfk_1` FOREIGN KEY (`productID1`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `relatedproduct_ibfk_2` FOREIGN KEY (`productID2`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`sizeID`) REFERENCES `size` (`sizeID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `stock_ibfk_3` FOREIGN KEY (`colorID`) REFERENCES `color` (`colorID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
