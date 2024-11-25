-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 23, 2024 at 10:58 AM
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
-- Table structure for table `work_requests`
--

CREATE TABLE `work_requests` (
  `id` int NOT NULL,
  `client_id` int DEFAULT NULL,
  `requesting_dept` varchar(100) DEFAULT NULL,
  `date_of_req` date DEFAULT NULL,
  `time_of_req` time DEFAULT NULL,
  `work_requested` varchar(255) DEFAULT NULL,
  `others_detail` text,
  `description_of_work_request` text,
  `action_taken` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `cisa_head_id` int DEFAULT NULL,
  `message_status` varchar(50) DEFAULT 'Unread',
  `approved_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `work_requests`
--

INSERT INTO `work_requests` (`id`, `client_id`, `requesting_dept`, `date_of_req`, `time_of_req`, `work_requested`, `others_detail`, `description_of_work_request`, `action_taken`, `status`, `cisa_head_id`, `message_status`, `approved_date`) VALUES
(37, 1, 'Cashier Office', '2024-11-11', '16:46:20', 'System Development/Enhancement, Website Development/Enhancement, Information System (IS) Account', '', 'gfdgf', '', 'Pending', 1, 'Unread', NULL),
(38, 9, 'hubhubuh', '2024-11-15', '14:39:41', 'System Development/Enhancement', '', 'jnjinjin', '', 'Approved', 1, 'Read', '2024-11-23 10:53:00');

--
-- Triggers `work_requests`
--
DELIMITER $$
CREATE TRIGGER `trigger_work_request_approval` AFTER UPDATE ON `work_requests` FOR EACH ROW BEGIN
  IF NEW.status = 'Approved' AND OLD.status != 'Approved' THEN
    INSERT INTO `work_request_events` (`work_request_id`, `approved_by`, `comments`)
    VALUES (NEW.id, NEW.cisa_head_id, 'Work request approved.');
  END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `work_requests`
--
ALTER TABLE `work_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cisa_head` (`cisa_head_id`),
  ADD KEY `fk_client_id` (`client_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `work_requests`
--
ALTER TABLE `work_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `work_requests`
--
ALTER TABLE `work_requests`
  ADD CONSTRAINT `fk_cisa_head_id` FOREIGN KEY (`cisa_head_id`) REFERENCES `admin_info` (`admin_id`),
  ADD CONSTRAINT `fk_client_id` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
