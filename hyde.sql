-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2026 at 02:17 PM
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
-- Database: `hyde`
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
(1, 'Min Sitt Paing Oo', 'minsitt.p67@rsu.ac.th', 'Thanoswasright@198989', '0823059272', '2004-06-30', 2, '198989', 64, '2025-10-01'),
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
(32, 'RS', 'rs', 'Bangkok', 'rs', 'rs', 'Thailand', 'RS, rs, rs, Bangkok, Thailand rs', 'https://maps.app.goo.gl/eaz5gCH24up9iJW98', NULL);

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
(30, 'Tshirts', 22);

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
(7, 'Red', '#ff0000');

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
(2, 2, 2);

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
(1, 1, 1, 165000, 1, NULL, 1, 1, NULL),
(2, 1, 2, 67500, 1, NULL, 1, 4, NULL),
(3, 15, 1, 2475000, 2, 2227500, 1, 2, NULL),
(4, 1, 1, 165000, 3, NULL, 1, 4, NULL),
(5, 1, 4, 165000, 3, NULL, 1, 4, NULL),
(6, 1, 6, 77500, 3, NULL, 5, 2, NULL),
(7, 35, 2, 2362500, 4, 1653750, 1, 4, NULL),
(8, 1, 1, 165000, 4, NULL, 1, 3, NULL),
(53, 10, 1, 1650000, 67, 1485000, 1, 1, 1);

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
(1, 1, 232500, '2025-11-06', 1, 3, 1, 2, 1, 2, 0, '', '0'),
(2, 1, 2227500, '2025-11-08', 3, 5, 1, 3, 1, 3, 0, '', '0'),
(3, 1, 407500, '2025-11-05', 2, 1, 3, 4, 2, 4, 0, '', '0'),
(4, 0, 1818750, '2025-11-10', 2, 2, 1, 4, 2, 4, 0, '', '0'),
(67, 1, 1485000, '2026-02-06', 1, 1, 1, NULL, 2, 32, 1, 'Vivian Nora', 'Vivian is a gril from Rangsit uni, bangkok'),
(68, 1, 0, '2026-02-11', 1, 1, 2, 1, 1, 1, 1, NULL, NULL),
(69, 1, NULL, '2026-02-11', 1, 1, 1, 2, 1, 2, 1, NULL, NULL);

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

--
-- Dumping data for table `paymentslip`
--

INSERT INTO `paymentslip` (`paymentSlipID`, `paymentSlip`, `orderID`) VALUES
(1, 32, 3),
(2, 33, 4),
(3, 34, 4),
(4, 57, 4),
(5, 58, 4),
(6, 61, 4);

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
(68, 'paymentSlip_698625ec284209.06066564.jpg', NULL);

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
(1, 'ORIGAMI FADED WASH JEANS', 165000, NULL, '2025-10-31', 'AVAILABLE At Mercury physical stores at Taung Gyi', 4, 1),
(2, 'VERVESV OG CLASSIC LOGO TEE', 77500, 67500, '2025-10-30', 'Crafted from 100% cotton 210gsm midweight single jersey fabric. 1x1 rib round neck. \nNew boxy cropped fit silhouette. \nOne of our signature rainbow reflective logo print on front. Cut, sewn and printed in Yangon. Designed by Vervesv in Bangkok.', 2, 1),
(3, 'VERVESV Cotton Leather 6 Panel Hat', 57500, 50000, '2025-10-15', '• 100% washed cotton + PU leather\n• Embroidery eyelets\n• Flat embroidery logo\n• Tri glide buckle \n• Single stitch detailing at back \n• PU leather button on top\n• 6 panel cut \n• Designed by vervesv in Bangkok\n• Cut & sewn in China \n• Embroidered in Yangon', 2, 1),
(4, 'Iconic V3 sweatshirt in black', 165000, 155000, '2025-10-01', '• 420gsm heavyweight loopback terry \n• 85% cotton 15% polyester mixed\n• 450gsm 1x1 ribbed cuff, hem & round neck\n• Iconic artwork printed on front & back\n• Loose fit cut & streetwear silhouette\n• Cut & sewn in China\n• Printed & finished in Myanmar', 2, 0),
(5, 'Druga UV protection jacket', 200000, 191000, '2025-10-01', '• UPF50+ protection\n• 50g flyweight material \n• Water resistance \n• Double zipper closure\n• Hidden extra large pocket \n• For outdoors & sports', 3, 0),
(6, 'HEMi BACKLESS DRESS in charcoal', 77500, NULL, '2025-10-02', 'Where minimalism meets bold elegance. \nCrafted from 95% polyester 5% elastane 4 way stretch fabric. Designed to hug every curve while showcasing an effortlessly chic open-back cut, this dress redefines sophistication.\nAvailable exclusively online, the Hemi Backless is the statement piece you didn’t know you needed – until now. Pair it with heels for a night out, or make it your go-to power outfit.', 2, 0);

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
(39, 1, 12);

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
(31, 5, 1);

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
(8, '5XL');

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
(1, 570, 1, 1, 1),
(2, 99, 1, 2, 1),
(3, 99, 1, 3, 1),
(4, 988, 1, 4, 1),
(5, 1000, 1, 5, 1),
(6, 11109, 1, 6, 1),
(7, 2879, 2, 4, 1),
(8, 1000, 2, 5, 1),
(9, 10000, 3, 4, 2),
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
(23, 6777, 6, 5, 5);

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
  MODIFY `addressID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `colorID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discountID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `favourite`
--
ALTER TABLE `favourite`
  MODIFY `favID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `orderItemID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `orderr`
--
ALTER TABLE `orderr`
  MODIFY `orderID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `orderstatus`
--
ALTER TABLE `orderstatus`
  MODIFY `orderStatusID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paymentslip`
--
ALTER TABLE `paymentslip`
  MODIFY `paymentSlipID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `photoID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `productxcategory`
--
ALTER TABLE `productxcategory`
  MODIFY `productxcategoryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `relatedproduct`
--
ALTER TABLE `relatedproduct`
  MODIFY `relatedProductID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `sizeID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stockID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
