-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 17, 2024 at 04:26 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin_info_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `admin_info_id`) VALUES
(1, 'admin-01', 'adminpass123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_info`
--

CREATE TABLE `admin_info` (
  `admin_id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_info`
--

INSERT INTO `admin_info` (`admin_id`, `first_name`, `middle_name`, `last_name`, `address`, `contact_number`, `email`, `image_path`) VALUES
(1, 'Admin', 'Manage', 'A', 'San Ramon Bontoc Southern Leyte', '987-654-3210', 'admin@example.com', '../core/assets/images/users/admin/test.png');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `description`, `image_path`) VALUES
(1, '📢ENROLLMENT!!!', 'Enrollment is now open. Please verify your credentials to determine eligibility for enrollment.', NULL),
(2, '💡Image Upload!!!', 'Did you know that you can now upload profile picture to your account?', NULL),
(3, '📄Check Documents', 'Please make sure to check everything you have written to avoid any mistakes when submitting.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cisa`
--

CREATE TABLE `cisa` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_info_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cisa`
--

INSERT INTO `cisa` (`id`, `username`, `password`, `role_info_id`, `created_at`) VALUES
(1, 'cisa', 'cisa', 1, '2024-10-09 01:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `cisa_head`
--

CREATE TABLE `cisa_head` (
  `id` int NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cisa_info`
--

CREATE TABLE `cisa_info` (
  `id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cisa_info`
--

INSERT INTO `cisa_info` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `role_id`, `created_at`) VALUES
(1, 'staff123', 'm', '123', 'cx@vdxv.com', 21, '2024-10-09 01:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `client_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `username`, `password`, `client_id`) VALUES
(1, 'B-01001', 'password123', 1),
(9, 'testuser', 'testpassword', 9);

-- --------------------------------------------------------

--
-- Table structure for table `client_feedback`
--

CREATE TABLE `client_feedback` (
  `id` int NOT NULL,
  `control_no` varchar(255) DEFAULT NULL,
  `client_type` text,
  `date` date DEFAULT NULL,
  `sex` text,
  `age` int DEFAULT NULL,
  `region_of_residence` varchar(255) DEFAULT NULL,
  `service_availed` varchar(255) DEFAULT NULL,
  `cc1` text,
  `cc2` text,
  `cc3` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `client_feedback`
--

INSERT INTO `client_feedback` (`id`, `control_no`, `client_type`, `date`, `sex`, `age`, `region_of_residence`, `service_availed`, `cc1`, `cc2`, `cc3`, `created_at`) VALUES
(46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-16 16:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `client_info`
--

CREATE TABLE `client_info` (
  `client_id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `client_info`
--

INSERT INTO `client_info` (`client_id`, `first_name`, `middle_name`, `last_name`, `status`, `address`, `contact_number`, `email`, `image_path`) VALUES
(1, 'Joren', 'Lawag', 'Agot', 'Continuing', 'Dagsa Sogod Southern Leyte', '1234567890', 'sample@gmail.com', '../core/assets/images/users/admin/me.jpg'),
(9, 'gh', 'fdg', 'fdg', NULL, NULL, NULL, 'adam@phpzag.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `pc_info`
--

CREATE TABLE `pc_info` (
  `pc_id` int NOT NULL,
  `pc` varchar(255) NOT NULL,
  `serial_no` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `tech` varchar(255) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `condition` varchar(50) NOT NULL DEFAULT 'Working',
  `message_status` varchar(50) DEFAULT 'Unread',
  `client_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pc_info`
--

INSERT INTO `pc_info` (`pc_id`, `pc`, `serial_no`, `user`, `dept`, `tech`, `date`, `condition`, `message_status`, `client_id`) VALUES
(18, 'PC 5', '4323235', 'New Guest', 'Registrar\'s Office', 'Phone', '2024-06-29 16:00:00', 'Submitted', 'Unread', 1),
(19, 'fgbvfrrw', 'dsasdsa', 'fgfdg', 'Registrar\'s Office', 'fdgfg', '2024-06-30 16:00:00', 'Submitted', 'Unread', 1),
(20, 'PC2', '342435643', 'Cashier', 'Accounting Office', 'Staff', '2024-06-30 16:00:00', 'Submitted', 'Unread', 1),
(22, 'PC 5', '4323235', 'New Guest', 'Budget Officer\'s Office', 'Phone', '2024-07-27 16:00:00', 'Working', 'Unread', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `role_type`, `created_at`) VALUES
(21, 'cisa', 'staff', '2024-10-09 01:33:59'),
(22, 'Student', 'Student', '2024-11-10 14:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_list`
--

CREATE TABLE `schedule_list` (
  `id` int NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `schedule_list`
--

INSERT INTO `schedule_list` (`id`, `title`, `description`, `start_datetime`, `end_datetime`) VALUES
(1, 'Sample 101', 'This is a sample schedule only.', '2022-01-10 10:30:00', '2022-01-11 18:00:00'),
(2, 'Sample 102', 'Sample Description 102', '2022-01-08 09:30:00', '2022-01-08 11:30:00'),
(4, 'Sample 102', 'Sample Description', '2022-01-12 14:00:00', '2022-01-12 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `staff_info_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password`, `staff_info_id`) VALUES
(1, 'staff-01', 'regpass123', 1),
(3, 'testuser111', 'testpassword', 14);

-- --------------------------------------------------------

--
-- Table structure for table `staff_info`
--

CREATE TABLE `staff_info` (
  `staff_id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff_info`
--

INSERT INTO `staff_info` (`staff_id`, `first_name`, `middle_name`, `last_name`, `address`, `contact_number`, `email`, `image_path`, `role`) VALUES
(1, 'Staff A', 'Staff', 'A', 'San Ramon Bontoc Southern Leyte', '123-456-7890', 'staff.A@gmail.com', '../core/assets/images/users/admin/6.png', 'Cashier Staff'),
(2, 'Asta', 'Black', 'Clover', 'Poblacion ', '12345678', 'asta@gmail.com', NULL, 'Registrar Staff'),
(3, 'Trini', 'Balbon', 'Cabel', 'San Salvador Matalom, Leyte', '09075004234', 'hahaha@gmail.com', NULL, 'Maintenance'),
(4, 'SANDRA', 'BAUL', 'WABINA', 'Canlupao Tomas Oppus Southern Leyte', '0987654321', 'wabinasandra@gmail.com', NULL, 'Nurse'),
(5, 'Vanessa', 'Epiz', 'Lawag', 'Sitio cagang, Polahongon, mahaplag, leyte', '09971953583', 'gawalvans24@gmail.com', NULL, 'Registrar Staff'),
(6, 'Mary grave', 'Vadesa', 'Makitong', 'Sogod', '09126543156', 'elmeraflormerka@gmail.com', NULL, 'Cashier Staff'),
(7, 'Niña ', 'Lindio', 'Gay', 'Consolacion, Sogod Southern Leyte ', '09512263835', 'gaynina90@gmail.com', NULL, 'Maintenance'),
(8, 'Mary grave', 'Vadesa', 'Makitong', 'Sogod', '09126543156', 'makitongvadesa@gmai.com', NULL, 'Registrar Staff'),
(9, 'Joedane', 'Angelo', 'Simo', 'Manglit', '09654996097', 'kapitanbodi@gmail.com', NULL, 'Nurse'),
(14, 'qqq', '3w', 'fdfz', NULL, NULL, 'a@gmail.com', NULL, 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `status_details`
--

CREATE TABLE `status_details` (
  `id` int NOT NULL,
  `row_id` int NOT NULL,
  `status` varchar(10) NOT NULL,
  `details` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `condition` varchar(50) NOT NULL DEFAULT 'Working',
  `pc_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status_details`
--

INSERT INTO `status_details` (`id`, `row_id`, `status`, `details`, `created_at`, `condition`, `pc_id`) VALUES
(5, 1, 'ok', '', '2024-07-28 16:45:54', 'Working', NULL),
(6, 1, 'ok', '', '2024-07-28 16:46:22', 'Working', NULL),
(7, 2, 'repair', '', '2024-07-28 16:46:24', 'Working', NULL),
(8, 1, 'repair', '', '2024-07-28 16:47:13', 'Working', 22),
(9, 2, 'repair', '', '2024-07-28 16:47:24', 'Working', 22),
(10, 3, 'repair', '', '2024-07-28 16:49:07', 'Working', 22),
(11, 4, 'ok', '', '2024-07-29 00:52:58', 'Working', 22),
(12, 5, 'ok', '', '2024-10-06 01:08:28', 'Working', 22),
(13, 6, 'repair', '', '2024-10-09 01:32:17', 'Working', 22),
(14, 7, 'ok', '', '2024-11-02 04:17:08', 'Working', 22),
(15, 8, 'ok', '', '2024-11-10 16:58:40', 'Working', 22),
(16, 9, 'ok', '', '2024-11-10 17:22:17', 'Working', 22);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_info_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `username`, `password`, `role_info_id`, `created_at`) VALUES
(1, 'student1', 'password123', 1, '2024-11-10 14:38:15'),
(9, 'hello', '123456789', 9, '2024-11-17 15:11:30'),
(10, 'aa', '123456789', 10, '2024-11-17 15:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `course`, `role_id`, `created_at`) VALUES
(1, 'Yumiko', 'Mio', 'Miko', 'adam@phpzag.com', '', 22, '2024-11-10 14:38:15'),
(9, 'gh', NULL, 'y5y', 'aa@gmail.com', '', 22, '2024-11-17 15:11:30'),
(10, 'aa', NULL, 'aa', 'aa@gmail.com', 'Information Technology', 22, '2024-11-17 15:29:18');

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
  `message_status` varchar(50) DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `work_requests`
--

INSERT INTO `work_requests` (`id`, `client_id`, `requesting_dept`, `date_of_req`, `time_of_req`, `work_requested`, `others_detail`, `description_of_work_request`, `action_taken`, `status`, `cisa_head_id`, `message_status`) VALUES
(37, 1, 'Cashier Office', '2024-11-11', '16:46:20', 'System Development/Enhancement, Website Development/Enhancement, Information System (IS) Account', '', 'gfdgf', '', 'Done', 1, 'Read'),
(38, 9, 'hubhubuh', '2024-11-15', '14:39:41', 'System Development/Enhancement', '', 'jnjinjin', '', 'Approved', 1, 'Read');

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

-- --------------------------------------------------------

--
-- Table structure for table `work_request_events`
--

CREATE TABLE `work_request_events` (
  `event_id` int NOT NULL,
  `work_request_id` int NOT NULL,
  `approval_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_by` int DEFAULT NULL,
  `comments` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `work_request_events`
--

INSERT INTO `work_request_events` (`event_id`, `work_request_id`, `approval_timestamp`, `approved_by`, `comments`) VALUES
(1, 38, '2024-11-17 02:16:32', 1, 'Work request approved.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_ibfk_1` (`admin_info_id`);

--
-- Indexes for table `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cisa`
--
ALTER TABLE `cisa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_info_id` (`role_info_id`);

--
-- Indexes for table `cisa_head`
--
ALTER TABLE `cisa_head`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cisa_info`
--
ALTER TABLE `cisa_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_feedback`
--
ALTER TABLE `client_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_info`
--
ALTER TABLE `client_info`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_order`
--
ALTER TABLE `job_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_job_order_client_id` (`client_id`),
  ADD KEY `fk_job_order_approved_by` (`approved_by`);

--
-- Indexes for table `pc_info`
--
ALTER TABLE `pc_info`
  ADD PRIMARY KEY (`pc_id`),
  ADD KEY `fk_client_info` (`client_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_list`
--
ALTER TABLE `schedule_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_ibfk_1` (`staff_info_id`);

--
-- Indexes for table `staff_info`
--
ALTER TABLE `staff_info`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `status_details`
--
ALTER TABLE `status_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pc_info` (`pc_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_info_id` (`role_info_id`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `work_requests`
--
ALTER TABLE `work_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cisa_head` (`cisa_head_id`),
  ADD KEY `fk_client_id` (`client_id`);

--
-- Indexes for table `work_request_events`
--
ALTER TABLE `work_request_events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `work_request_id` (`work_request_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_info`
--
ALTER TABLE `admin_info`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cisa`
--
ALTER TABLE `cisa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cisa_head`
--
ALTER TABLE `cisa_head`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cisa_info`
--
ALTER TABLE `cisa_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `client_feedback`
--
ALTER TABLE `client_feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `client_info`
--
ALTER TABLE `client_info`
  MODIFY `client_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order`
--
ALTER TABLE `job_order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pc_info`
--
ALTER TABLE `pc_info`
  MODIFY `pc_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff_info`
--
ALTER TABLE `staff_info`
  MODIFY `staff_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `status_details`
--
ALTER TABLE `status_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_info`
--
ALTER TABLE `student_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `work_requests`
--
ALTER TABLE `work_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `work_request_events`
--
ALTER TABLE `work_request_events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`admin_info_id`) REFERENCES `admin_info` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cisa`
--
ALTER TABLE `cisa`
  ADD CONSTRAINT `cisa_ibfk_1` FOREIGN KEY (`role_info_id`) REFERENCES `cisa_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cisa_info`
--
ALTER TABLE `cisa_info`
  ADD CONSTRAINT `cisa_info_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `job_order`
--
ALTER TABLE `job_order`
  ADD CONSTRAINT `fk_job_order_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `cisa_head` (`id`),
  ADD CONSTRAINT `fk_job_order_client_id` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `pc_info`
--
ALTER TABLE `pc_info`
  ADD CONSTRAINT `fk_client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`staff_info_id`) REFERENCES `staff_info` (`staff_id`);

--
-- Constraints for table `status_details`
--
ALTER TABLE `status_details`
  ADD CONSTRAINT `fk_pc_info` FOREIGN KEY (`pc_id`) REFERENCES `pc_info` (`pc_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`role_info_id`) REFERENCES `student_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_info`
--
ALTER TABLE `student_info`
  ADD CONSTRAINT `student_info_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_requests`
--
ALTER TABLE `work_requests`
  ADD CONSTRAINT `fk_cisa_head_id` FOREIGN KEY (`cisa_head_id`) REFERENCES `admin_info` (`admin_id`),
  ADD CONSTRAINT `fk_client_id` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `work_request_events`
--
ALTER TABLE `work_request_events`
  ADD CONSTRAINT `work_request_events_ibfk_1` FOREIGN KEY (`work_request_id`) REFERENCES `work_requests` (`id`),
  ADD CONSTRAINT `work_request_events_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `admin_info` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
