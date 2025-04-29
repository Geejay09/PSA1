-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 08:28 AM
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
-- Database: `dbpsa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_iar`
--

CREATE TABLE `tbl_iar` (
  `id` int(20) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `pr_no` varchar(50) NOT NULL,
  `iar_no` int(50) NOT NULL,
  `date` date NOT NULL,
  `property_no` varchar(15) NOT NULL,
  `descd` varchar(100) NOT NULL,
  `item` varchar(50) NOT NULL,
  `unit` enum('pc','bottle','ream','kg','liter','pack','box','roll','pair') NOT NULL,
  `quantity` int(255) NOT NULL,
  `invoice_no` int(50) NOT NULL,
  `rcc` varchar(50) NOT NULL,
  `d` date NOT NULL,
  `date_inspected` date NOT NULL,
  `date_recieved` date NOT NULL,
  `i_officer` varchar(50) NOT NULL,
  `custodian` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `id` int(15) NOT NULL,
  `stock_code` varchar(50) NOT NULL,
  `item` varchar(50) NOT NULL,
  `descode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_items`
--

INSERT INTO `tbl_items` (`id`, `stock_code`, `item`, `descode`) VALUES
(1, 'A.1', 'Acetate', ''),
(2, 'A.2.1', 'Alchohol', ''),
(3, 'A.3.1', 'Ballpen', 'Black'),
(5, 'A.4.1', 'Ballpen', 'Blue'),
(6, 'A.5', 'Ballpen', 'Red'),
(7, 'A.6', 'Battery', 'Size AA'),
(9, 'A.7', 'Battery', 'Size AAA'),
(10, 'A.9', 'Paper Clip', 'Jumbo'),
(11, 'A.10', 'Paper Clip', 'Box'),
(12, 'A.11', 'Columnar Book', '4 Column'),
(13, 'A.21', 'Cutter Blade', 'Big'),
(14, 'A.22', 'Envelope', 'Brown Long'),
(15, 'A.23', 'Envelope', 'Brown Short'),
(16, 'A.25', 'Expanded Envelope', 'Long'),
(17, 'A.25.1', 'Envelope', 'Plastic Expandable'),
(18, 'A.27', 'Mailing Envelope', 'White with window'),
(19, 'A.29,1', 'Eraser', 'Rubber'),
(20, 'A.30.1', 'Folder', 'Fancy'),
(21, 'A.31', 'Paper Fastener', 'Plastic/Metal'),
(22, 'A.34', 'Folder', 'Tag Board Long'),
(23, 'A.36', 'Folder', 'Tag Board, A4'),
(24, 'A.37', 'Glue', 'All Purpose'),
(25, 'A.39', 'Hard Bound Folder', 'Expanding, Legal'),
(26, 'A.40', 'Hard Bound Folder', 'Expanding, Short'),
(27, 'A.41.1', 'Index Card', '5x8 inches'),
(28, 'A.42', 'Stamp Pad Ink', 'Violet'),
(29, 'A.44.1', 'Permanent Marker', 'Black'),
(30, 'A.44.2', 'Permanent Marker', 'Blue'),
(31, 'A.44.7', 'Permanent Marker', 'Red'),
(32, 'A.48', 'Post It', '2x3 inches'),
(33, 'A.49.1', 'Post It', '3x4 inches'),
(34, 'A.50.1', 'Note Book', 'Spiral'),
(35, 'A.61', 'Bond Paper', 'A4, 70 GSM'),
(36, 'A.61.2', 'Bond Paper', 'A4, 80 GSM'),
(37, 'A.62', 'Bond Paper', 'Legal, 70 GSM'),
(38, 'A.64', 'Pencil', 'Monggol 2'),
(39, 'A.66.1', 'Pencil Sharpener', 'Small'),
(40, 'A.66.2', 'Pencil Sharpener', 'Big, Metal, Heavy Duty'),
(41, 'A.68.2', 'Record Book', '500 Pages'),
(42, 'A.69', 'Rubber Band', '#18'),
(43, 'A.70', 'Ruller', 'Plastic'),
(44, 'A.71.2', 'Scissors', 'Medium Size'),
(45, 'A.72', 'Sign Pen', 'Black'),
(46, 'A.72.1', 'Sign Pen', 'Blue'),
(47, 'A.76', 'Stamp Pad', 'Felt, 7.4 x 15 CM'),
(48, 'A.77.2', 'Staple Remover', 'Plier Type'),
(49, 'A.78.1', 'Staple Remover', 'Standard');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ris`
--

CREATE TABLE `tbl_ris` (
  `id` int(20) NOT NULL,
  `division` varchar(20) NOT NULL,
  `office` varchar(20) NOT NULL,
  `rcc` varchar(20) NOT NULL,
  `ris_no` varchar(20) NOT NULL,
  `stock_no` varchar(20) NOT NULL,
  `unit` enum('pc','bottle','ream','kg','liter','pack','box','roll','pair') NOT NULL,
  `des` varchar(20) NOT NULL,
  `item` varchar(50) NOT NULL,
  `qty` int(20) NOT NULL,
  `i_qty` int(20) NOT NULL,
  `remarks` varchar(20) NOT NULL,
  `purpose` varchar(200) NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `fc` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_ris`
--

INSERT INTO `tbl_ris` (`id`, `division`, `office`, `rcc`, `ris_no`, `stock_no`, `unit`, `des`, `item`, `qty`, `i_qty`, `remarks`, `purpose`, `receiver`, `fc`) VALUES
(100, '', 'PSA-Quirino', '', '', 'A.1', 'pc', '', 'Acetate', 1, 1, '0', '', 'John Doe', 'Locally Funded'),
(101, '', 'PSA-Quirino', '', '', 'A.1', 'pc', '', 'Acetate', 1, 1, '0', '', 'John Doe', 'Locally Funded'),
(102, '', 'PSA-Quirino', '', '', 'A.1', 'pc', '', 'Acetate', 1, 1, '0', '', 'John Doe', 'Regular'),
(103, '', 'PSA-Quirino', '', '', 'A.27', 'pc', 'White with window', 'Mailing Envelope', 1, 1, '0', '', 'John Doe', 'Regular'),
(104, '', 'PSA-Quirino', '', '', 'A.37', 'pc', 'All Purpose', 'Glue', 1, 1, '0', '', 'Alex Johnson', 'Regular'),
(105, '', 'PSA-Quirino', '', '', 'A.7', 'pc', 'Size AAA', 'Battery', 1, 1, '0', '', '', ''),
(108, 'PSA', 'PSA-Quirino', '999', '09602752556', 'A.34', 'pc', 'Tag Board Long', 'Folder', 5, 5, '0', 'Ako ay pogi', 'Alexander G. Austria', 'Regular'),
(109, 'PSA', 'PSA-Quirino', '999', '09602752556', 'A.41.1', 'ream', '5x8 inches', 'Index Card', 5, 5, '0', 'Ako ay pogi', 'Alexander G. Austria', 'Regular'),
(110, 'PSA', 'PSA-Quirino', '999', '09602752556', 'A.44.1', 'pc', 'Black', 'Permanent Marker', 3, 3, '0', 'Ako ay pogi', 'Alexander G. Austria', 'Regular'),
(111, 'PSA', 'PSA-Quirino', '01', '00002', 'A.4.1', 'pc', 'Blue', 'Ballpen', 5, 5, '0', 'asd', 'Archie C. Ferrer', 'Locally Funded');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rpci`
--

CREATE TABLE `tbl_rpci` (
  `id` int(20) NOT NULL,
  `des` varchar(20) NOT NULL,
  `date` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `article` varchar(20) NOT NULL,
  `descpt` varchar(20) NOT NULL,
  `stock_no` varchar(20) NOT NULL,
  `unit_value` float NOT NULL,
  `balance_per_card` int(20) NOT NULL,
  `on_hand_per_count` int(20) NOT NULL,
  `total_value` float NOT NULL,
  `s_qty` int(20) NOT NULL,
  `s_value` int(20) NOT NULL,
  `remarks` varchar(20) NOT NULL,
  `page_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rsmi`
--

CREATE TABLE `tbl_rsmi` (
  `id` int(11) NOT NULL,
  `entity_name` varchar(20) NOT NULL,
  `fund_cluster` varchar(20) NOT NULL,
  `serial_no` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `ris_no` varchar(20) NOT NULL,
  `rcc` varchar(20) NOT NULL,
  `stock_no` varchar(20) NOT NULL,
  `item` varchar(20) NOT NULL,
  `unit` enum('pc','bottle','ream','kg','liter','pack','box','') NOT NULL,
  `qty` int(20) NOT NULL,
  `unit_cost` float NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sc`
--

CREATE TABLE `tbl_sc` (
  `id` int(20) NOT NULL,
  `item` varchar(20) NOT NULL,
  `dscrtn` varchar(50) NOT NULL,
  `unit` enum('pc','bottle','ream','kg','liter','pack','roll','pair','box') NOT NULL,
  `stock_no` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `ref` varchar(20) NOT NULL,
  `receipt_qty` float NOT NULL,
  `issue_qty` float NOT NULL,
  `office` varchar(50) NOT NULL,
  `balance_qty` float NOT NULL,
  `no_days` varchar(20) NOT NULL,
  `entity` varchar(50) NOT NULL,
  `fund` enum('Locally Funded','Regular','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_sc`
--

INSERT INTO `tbl_sc` (`id`, `item`, `dscrtn`, `unit`, `stock_no`, `date`, `ref`, `receipt_qty`, `issue_qty`, `office`, `balance_qty`, `no_days`, `entity`, `fund`) VALUES
(119, 'Expanded Envelope', 'Long', 'pc', 'A.25', '2025-04-24', '001', 5, 5, 'Jane Smith', 0, '0', '0', ''),
(120, 'Folder', 'Tag Board Long', 'pc', 'A.34', '2025-04-24', '001', 5, 2, 'Jane Smith', 0, '0', '0', ''),
(121, 'Ballpen', 'Red', 'pc', 'A.5', '2025-04-28', '', 1, 1, '', 0, '0', '0', ''),
(122, 'Battery', 'Size AA', 'pc', 'A.6', '2025-04-28', '', 2, 2, '', 0, '0', '0', ''),
(123, 'Folder', 'Tag Board Long', 'ream', 'A.34', '2025-04-28', '', 1, 1, '', 0, '0', '0', ''),
(124, 'Folder', 'Tag Board Long', 'pc', 'A.34', '2025-04-28', '0001', 5, 5, 'Jane Smith', 0, '0', '0', ''),
(125, 'Folder', 'Tag Board Long', 'pc', 'A.34', '2025-04-28', '0001', 5, 5, 'Jane Smith', 0, '0', '0', ''),
(126, 'Folder', 'Tag Board Long', 'pc', 'A.34', '2025-04-28', '0001', 5, 5, 'Jane Smith', 0, '0', '0', ''),
(127, 'Index Card', '5x8 inches', 'ream', 'A.41.1', '2025-04-28', '11018', 3, 3, 'Jane Smith', 0, '0', '0', ''),
(128, 'Glue', 'All Purpose', 'pc', 'A.37', '2025-04-29', '0', 1, 1, 'Alex Johnson', 0, '0', 'Philippine Statistics Authority', 'Regular'),
(129, 'Battery', 'Size AAA', 'pc', 'A.7', '2025-04-29', '0', 1, 1, '', 0, '0', 'Philippine Statistics Authority', ''),
(130, 'Folder', 'Tag Board Long', 'pc', 'A.34', '2025-04-29', '9602752556', 5, 5, 'John Doe', 0, '0', 'Philippine Statistics Authority', 'Regular'),
(131, 'Mailing Envelope', 'White with window', 'pc', 'A.27', '2025-04-29', '9602752556', 1, 1, 'John Doe', 0, '0', 'Philippine Statistics Authority', 'Regular'),
(132, 'Folder', 'Tag Board Long', 'pc', 'A.34', '2025-04-29', '9602752556', 5, 5, 'Alexander G. Austria', 0, '0', 'Philippine Statistics Authority', 'Regular'),
(133, 'Index Card', '5x8 inches', 'ream', 'A.41.1', '2025-04-29', '9602752556', 5, 5, 'Alexander G. Austria', 0, '0', 'Philippine Statistics Authority', 'Regular'),
(134, 'Permanent Marker', 'Black', 'pc', 'A.44.1', '2025-04-29', '9602752556', 3, 3, 'Alexander G. Austria', 0, '0', 'Philippine Statistics Authority', 'Regular'),
(135, 'Ballpen', 'Blue', 'pc', 'A.4.1', '2025-04-29', '2', 5, 5, 'Archie C. Ferrer', 0, '0', 'Philippine Statistics Authority', 'Locally Funded');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slc`
--

CREATE TABLE `tbl_slc` (
  `id` int(20) NOT NULL,
  `item` varchar(20) NOT NULL,
  `des` varchar(50) NOT NULL,
  `unit` enum('pieces','bottle','','') NOT NULL,
  `stock_no` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `ref` varchar(20) NOT NULL,
  `r_qty` float NOT NULL,
  `r_unit_cost` float NOT NULL,
  `r_total_cost` float NOT NULL,
  `i_qty` float NOT NULL,
  `i_unit_cost` float NOT NULL,
  `i_total_cost` float NOT NULL,
  `b_qty` float NOT NULL,
  `b_unit_cost` float NOT NULL,
  `b_total_cost` float NOT NULL,
  `no.days` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(3, 'Admin', '$2y$10$scZMdCNEJD40W3/blRoBxeM.iio2J7jrMPa2Vx3mT6Edh1sxa1uWG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_iar`
--
ALTER TABLE `tbl_iar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ris`
--
ALTER TABLE `tbl_ris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rpci`
--
ALTER TABLE `tbl_rpci`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rsmi`
--
ALTER TABLE `tbl_rsmi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sc`
--
ALTER TABLE `tbl_sc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_slc`
--
ALTER TABLE `tbl_slc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_iar`
--
ALTER TABLE `tbl_iar`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_ris`
--
ALTER TABLE `tbl_ris`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `tbl_rpci`
--
ALTER TABLE `tbl_rpci`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rsmi`
--
ALTER TABLE `tbl_rsmi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sc`
--
ALTER TABLE `tbl_sc`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `tbl_slc`
--
ALTER TABLE `tbl_slc`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
