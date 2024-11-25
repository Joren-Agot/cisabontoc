-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 18, 2024 at 12:58 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cisa`
--

-- --------------------------------------------------------

--
-- Table structure for table `job_order`
--

CREATE TABLE `job_order` (
  `id` int NOT NULL,
  `client_id` int DEFAULT NULL,
  `requesting_dept` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_req` date DEFAULT NULL,
  `time_of_req` time DEFAULT NULL,
  `work_type` enum('PREVENTIVE_MAINTENANCE','CORRECTIVE_MAINTENANCE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_requested` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `others_detail` text COLLATE utf8mb4_unicode_ci,
  `action_taken` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `approved_by` int DEFAULT NULL,
  `name_of_requestor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_order`
--

INSERT INTO `job_order` (`id`, `client_id`, `requesting_dept`, `date_of_req`, `time_of_req`, `work_type`, `type`, `work_requested`, `others_detail`, `action_taken`, `status`, `approved_by`, `name_of_requestor`, `message_status`) VALUES
(11, 1, 'Cashier Office', '2024-07-29', '06:11:35', 'CORRECTIVE_MAINTENANCE', 'SOFTWARE', 'Others', 'jhhjgjxf', NULL, 'Approved', NULL, 'Joe', 'Read'),
(14, 1, 'Cashier Office', '2024-11-10', '15:14:13', 'CORRECTIVE_MAINTENANCE', 'SOFTWARE', 'Printer', '', NULL, 'Declined', NULL, 'fghgfhg', 'Read'),
(16, 9, 'lkmlkm', '2024-11-15', '14:40:08', 'PREVENTIVE_MAINTENANCE', 'HARDWARE', 'Cpu', '', NULL, 'Approved', NULL, 'lkmlkm', 'Read');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `job_order`
--
ALTER TABLE `job_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_job_order_client_id` (`client_id`),
  ADD KEY `fk_job_order_approved_by` (`approved_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `job_order`
--
ALTER TABLE `job_order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `job_order`
--
ALTER TABLE `job_order`
  ADD CONSTRAINT `fk_job_order_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `cisa_head` (`id`),
  ADD CONSTRAINT `fk_job_order_client_id` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
