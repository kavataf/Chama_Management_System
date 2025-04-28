-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 02:44 PM
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
-- Database: `chama_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_amount` int(200) NOT NULL,
  `loan_duration` int(200) NOT NULL,
  `loan_purpose` varchar(200) NOT NULL,
  `loan_name` varchar(200) NOT NULL,
  `loan_status` varchar(200) NOT NULL,
  `application_date` varchar(200) NOT NULL,
  `interest_rate` decimal(10,0) NOT NULL,
  `total_payable` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `user_id`, `loan_id`, `loan_amount`, `loan_duration`, `loan_purpose`, `loan_name`, `loan_status`, `application_date`, `interest_rate`, `total_payable`) VALUES
(10, 11, 1, 50000, 8, 'start a business', 'Business Capital', 'Approved', '2025-03-11', 5, 62500),
(11, 11, 1, 50000, 8, 'start a business', 'Business Capital', 'Rejected', '2025-03-11', 5, 0),
(13, 12, 1, 210000, 8, 'business capital', 'business capital', 'Approved', '2025-04-07', 5, 222500),
(14, 12, 2, 250000, 10, 'medical', 'medical expenses', 'Approved', '2025-04-08', 5, 262500),
(15, 9, 3, 150000, 12, 'fees', 'school fees', 'Rejected', '2025-04-09', 0, 0),
(16, 11, 3, 100000, 12, 'fees', 'school fees', 'Approved', '2025-04-23', 5, 105000),
(17, 13, 3, 150000, 12, 'fees', 'school fees', 'Pending', '2025-04-23', 0, 0),
(18, 11, 4, 200000, 12, 'development fund', 'development fund', 'Pending', '2025-04-27', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `contribution_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `amount` int(200) NOT NULL,
  `due_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributions`
--

INSERT INTO `contributions` (`contribution_id`, `title`, `amount`, `due_date`, `created_at`) VALUES
(1, 'January contribution', 500, '2025-01-31', '2025-03-18 11:00:14'),
(2, 'Special Project Contribution', 5000, '2025-05-30', '2025-03-18 11:13:17'),
(3, 'February contribution', 500, '2025-02-28', '2025-03-18 11:00:14'),
(4, 'March contribution', 500, '2025-03-31', '2025-03-18 11:00:14'),
(5, 'Investment Contribution ', 1000, '2025-06-30', '2025-03-25 13:13:00'),
(6, 'Emergency Fund Contribution', 500, '2025-05-31', '2025-03-25 13:13:44'),
(7, 'Loan Repayment Contribution', 2000, '2025-05-01', '2025-03-25 13:14:30'),
(8, 'April Contribution', 500, '2025-04-01', '2025-04-27 11:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `user_id` int(200) NOT NULL,
  `member_name` varchar(200) NOT NULL,
  `member_gender` varchar(200) NOT NULL,
  `member_id_no` int(8) NOT NULL,
  `member_phone` varchar(200) NOT NULL,
  `member_email` varchar(200) NOT NULL,
  `total_savings` decimal(60,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `member_avatar` varchar(200) NOT NULL,
  `member_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `member_name`, `member_gender`, `member_id_no`, `member_phone`, `member_email`, `total_savings`, `created_at`, `member_avatar`, `member_status`) VALUES
(9, 'Faith Kavata', 'Female', 2147483647, '0742156134', 'kavatafaith61@gmail.com', 0, '2025-03-29 14:10:52', 'https://i.pravatar.cc/40?img=1', 'active'),
(11, 'Sabrina Simone', 'Female', 2147483647, '0769586256', 'nellykinky428@gmail.com', 60000, '2025-02-10 14:10:52', 'https://i.pravatar.cc/40?img=2', 'active'),
(12, 'Dwayne Johnson', 'Male', 2147483647, '0742156135', 'johnson@gmail.com', 0, '2025-03-29 14:13:12', 'https://i.pravatar.cc/40?img=3', 'active'),
(13, 'Winnie', 'Female', 2147483647, '0742156137', 'winnie@gmail.com', 0, '2025-03-29 14:14:35', 'https://i.pravatar.cc/40?img=4', 'suspended'),
(14, 'Angeline Jolie', 'Female', 237845677, '0742156134', 'angeline@gmail.com', 0, '2025-04-23 10:58:00', 'https://i.pravatar.cc/40?img=5', 'active'),
(15, 'Joy Lola', 'Female', 127682388, '0742156134', 'kavatafaith161@gmail.com', 0, '2025-04-24 08:45:47', '', 'active'),
(18, 'Faith Mwende', 'Female', 32617271, '0742156134', 'faith@gmail.com', 20000, '2025-04-28 12:02:27', '', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `member_contributions`
--

CREATE TABLE `member_contributions` (
  `id` int(11) NOT NULL,
  `contribution_id` int(200) NOT NULL,
  `member_id` int(200) NOT NULL,
  `amount_paid` float NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(200) NOT NULL DEFAULT 'pending',
  `reference` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_contributions`
--

INSERT INTO `member_contributions` (`id`, `contribution_id`, `member_id`, `amount_paid`, `payment_date`, `status`, `reference`) VALUES
(2, 4, 11, 500, '2025-03-25 12:54:48', 'paid', ''),
(7, 1, 11, 500, '2025-03-25 13:19:48', 'paid', ''),
(9, 6, 11, 200, '2025-03-25 13:23:01', 'Partially Paid', ''),
(10, 5, 11, 500, '2025-03-29 13:06:18', 'Partially Paid', ''),
(11, 5, 9, 1000, '2025-04-05 17:13:47', 'Paid', ''),
(12, 2, 11, 2000, '2025-04-27 08:49:51', 'Partially Paid', 'T737426137613761'),
(13, 3, 11, 500, '2025-04-27 08:58:21', 'Paid', 'T841943183879525'),
(14, 5, 11, 200, '2025-04-28 10:00:02', 'Partially Paid', 'T195896254991126'),
(15, 1, 18, 300, '2025-04-28 12:09:34', 'Partially Paid', 'T198502553133808');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(200) NOT NULL,
  `read_status` enum('unread','read') NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `member_id`, `message`, `status`, `read_status`, `sent_at`) VALUES
(1, 9, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(2, 12, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(3, 13, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(4, 9, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(5, 11, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(6, 12, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(7, 13, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(8, 9, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(9, 11, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(10, 12, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(11, 13, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(12, 9, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(13, 12, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(14, 13, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(15, 9, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(16, 11, 'You have a remaining contribution balance of $500 due on 2025-06-30.', 'unread', '', '2025-04-03 14:48:37'),
(17, 12, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(18, 13, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(19, 9, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(20, 11, 'You have a remaining contribution balance of $300 due on 2025-05-31.', 'unread', '', '2025-04-03 14:48:37'),
(21, 12, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(22, 13, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(23, 9, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(24, 11, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(25, 12, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(26, 13, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '', '2025-04-03 14:48:37'),
(27, 12, 'Your loan application (ID: 14) has been Approved.', 'loan_approved', 'unread', '2025-04-10 10:11:53'),
(28, 12, 'Your loan application (ID: 14) has been Approved.', 'loan_approved', 'unread', '2025-04-10 10:12:36'),
(29, 12, 'Your loan application (ID: 14) has been Approved.', 'loan_approved', 'unread', '2025-04-10 10:14:20'),
(30, 9, 'Your loan application (ID: 15) has been Rejected.', 'loan_rejected', 'unread', '2025-04-10 10:59:33'),
(31, 9, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(32, 12, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(33, 13, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(34, 9, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(35, 11, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(36, 12, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(37, 13, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(38, 9, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(39, 11, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(40, 12, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(41, 13, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(42, 9, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(43, 12, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(44, 13, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(45, 11, 'You have a remaining contribution balance of $500 due on 2025-06-30.', 'unread', 'unread', '2025-04-15 16:49:59'),
(46, 12, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(47, 13, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(48, 9, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(49, 11, 'You have a remaining contribution balance of $300 due on 2025-05-31.', 'unread', 'unread', '2025-04-15 16:49:59'),
(50, 12, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(51, 13, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(52, 9, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(53, 11, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(54, 12, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(55, 13, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-15 16:49:59'),
(56, 11, 'Your loan application (ID: 11) has been Rejected.', 'loan_rejected', 'unread', '2025-04-16 15:00:51'),
(57, 9, 'Your loan application (ID: 15) has been Rejected.', 'loan_rejected', 'unread', '2025-04-16 15:00:58'),
(58, 9, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(59, 12, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(60, 13, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(61, 14, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(62, 9, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(63, 11, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(64, 12, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(65, 13, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(66, 14, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(67, 9, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(68, 11, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(69, 12, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(70, 13, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(71, 14, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(72, 9, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(73, 12, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(74, 13, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(75, 14, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(76, 11, 'You have a remaining contribution balance of $500 due on 2025-06-30.', 'unread', 'unread', '2025-04-23 11:05:41'),
(77, 12, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(78, 13, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(79, 14, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(80, 9, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(81, 11, 'You have a remaining contribution balance of $300 due on 2025-05-31.', 'unread', 'unread', '2025-04-23 11:05:41'),
(82, 12, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(83, 13, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(84, 14, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(85, 9, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(86, 11, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(87, 12, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(88, 13, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(89, 14, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-23 11:05:41'),
(90, 9, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(91, 12, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(92, 13, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(93, 14, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(94, 15, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(95, 9, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(96, 11, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(97, 12, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(98, 13, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(99, 14, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(100, 15, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(101, 9, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(102, 11, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(103, 12, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(104, 13, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(105, 14, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(106, 15, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(107, 9, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(108, 12, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(109, 13, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(110, 14, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(111, 15, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(112, 11, 'You have a remaining contribution balance of $500 due on 2025-06-30.', 'unread', 'unread', '2025-04-26 08:57:03'),
(113, 12, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(114, 13, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(115, 14, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(116, 15, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(117, 9, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(118, 11, 'You have a remaining contribution balance of $300 due on 2025-05-31.', 'unread', 'unread', '2025-04-26 08:57:03'),
(119, 12, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(120, 13, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(121, 14, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(122, 15, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(123, 9, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(124, 11, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(125, 12, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(126, 13, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(127, 14, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(128, 15, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', 'unread', '2025-04-26 08:57:03'),
(129, 11, 'Your loan application (ID: 16) has been Approved.', 'loan_approved', 'unread', '2025-04-27 09:58:46');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(200) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_name` varchar(200) NOT NULL,
  `loan_interest` int(200) NOT NULL,
  `loan_duration` int(11) NOT NULL,
  `processing_fee` int(200) NOT NULL,
  `maximum_limit` int(200) NOT NULL,
  `loan_guarantors` varchar(200) NOT NULL,
  `member_savings` int(200) NOT NULL,
  `thumbnail` varchar(200) NOT NULL,
  `loan_penalty` int(200) NOT NULL,
  `loan_description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `loan_id`, `loan_name`, `loan_interest`, `loan_duration`, `processing_fee`, `maximum_limit`, `loan_guarantors`, `member_savings`, `thumbnail`, `loan_penalty`, `loan_description`) VALUES
(5, 1, 'Business Capital', 5, 8, 800, 200000, 'No', 30000, 'uploads/payment receipt.jpg', 10000, 'capital to start a business'),
(8, 2, 'medical expenses', 5, 10, 800, 300000, 'No', 30000, 'uploads/payment receipt.jpg', 5000, 'medical'),
(9, 3, 'school fees', 5, 12, 800, 200000, 'Yes', 30000, 'uploads/payment receipt.jpg', 5000, 'school fees'),
(10, 4, 'development fund', 5, 12, 800, 350000, 'Yes', 30000, 'uploads/payment receipt.jpg', 5000, 'development fund');

-- --------------------------------------------------------

--
-- Table structure for table `repayments`
--

CREATE TABLE `repayments` (
  `repayment_id` int(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_name` varchar(200) NOT NULL,
  `loan_amount` int(200) NOT NULL,
  `amount_paid` decimal(10,0) NOT NULL DEFAULT 0,
  `repayment_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','paid','overdue','') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repayments`
--

INSERT INTO `repayments` (`repayment_id`, `user_id`, `loan_id`, `loan_name`, `loan_amount`, `amount_paid`, `repayment_date`, `due_date`, `status`) VALUES
(25, 11, 10, 'business capital', 1000, 1000, '2025-04-07', '2025-04-20', 'paid'),
(26, 11, 10, 'business capital', 1000, 2150, '2025-04-23', '2025-04-20', 'paid'),
(37, 12, 13, 'business capital', 26250, 20000, '2025-04-07', '2025-05-07', 'pending'),
(38, 12, 13, 'business capital', 26250, 0, NULL, '2025-06-07', 'pending'),
(39, 12, 13, 'business capital', 26250, 0, NULL, '2025-07-07', 'pending'),
(40, 12, 13, 'business capital', 26250, 0, NULL, '2025-08-07', 'pending'),
(41, 12, 13, 'business capital', 26250, 0, NULL, '2025-09-07', 'pending'),
(42, 12, 13, 'business capital', 26250, 0, NULL, '2025-10-07', 'pending'),
(43, 12, 13, 'business capital', 26250, 0, NULL, '2025-11-07', 'pending'),
(44, 12, 13, 'business capital', 26250, 0, NULL, '2025-12-07', 'pending'),
(45, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-05-10', 'pending'),
(46, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-06-10', 'pending'),
(47, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-07-10', 'pending'),
(48, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-08-10', 'pending'),
(49, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-09-10', 'pending'),
(50, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-10-10', 'pending'),
(51, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-11-10', 'pending'),
(52, 12, 14, 'medical expenses', 26250, 0, NULL, '2025-12-10', 'pending'),
(53, 12, 14, 'medical expenses', 26250, 0, NULL, '2026-01-10', 'pending'),
(54, 12, 14, 'medical expenses', 26250, 0, NULL, '2026-02-10', 'pending'),
(55, 11, 16, 'school fees', 8750, 8750, '2025-04-27', '2025-05-27', 'paid'),
(56, 11, 16, 'school fees', 8750, 8750, '2025-04-27', '2025-06-27', 'paid'),
(57, 11, 16, 'school fees', 8750, 500, '2025-04-27', '2025-07-27', 'pending'),
(58, 11, 16, 'school fees', 8750, 0, NULL, '2025-08-27', 'pending'),
(59, 11, 16, 'school fees', 8750, 0, NULL, '2025-09-27', 'pending'),
(60, 11, 16, 'school fees', 8750, 0, NULL, '2025-10-27', 'pending'),
(61, 11, 16, 'school fees', 8750, 0, NULL, '2025-11-27', 'pending'),
(62, 11, 16, 'school fees', 8750, 0, NULL, '2025-12-27', 'pending'),
(63, 11, 16, 'school fees', 8750, 0, NULL, '2026-01-27', 'pending'),
(64, 11, 16, 'school fees', 8750, 0, NULL, '2026-02-27', 'pending'),
(65, 11, 16, 'school fees', 8750, 0, NULL, '2026-03-27', 'pending'),
(66, 11, 16, 'school fees', 8750, 0, NULL, '2026-04-27', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `savings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `reference` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `amount_saved` decimal(60,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`savings_id`, `user_id`, `email`, `reference`, `created_at`, `amount_saved`) VALUES
(0, 11, 'nellykinky428@gmail.com', 'T865159340615028', '2025-04-28 09:50:25', 20000),
(0, 18, 'faith@gmail.com', 'T746936808453710', '2025-04-28 15:06:28', 20000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_gender` varchar(200) NOT NULL,
  `user_id_no` int(8) NOT NULL,
  `user_phone` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_access_level` varchar(200) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `reset_token` varchar(200) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_gender`, `user_id_no`, `user_phone`, `user_email`, `user_access_level`, `user_password`, `reset_token`, `reset_expires`) VALUES
(8, 'Faith Kavata', '', 41109057, '0742156134', 'kavatafaith412@gmail.com', 'System Administrator', '$2y$10$uwRnIkjKMZgo1Y0sQmL/beWwHFg67kHotsmSo4StdPQBCzFvRC7WS', NULL, NULL),
(9, 'Faith Kavata', 'Female', 2147483647, '0742156134', 'kavatafaith61@gmail.com', 'Member', '$2y$10$88jprRnkPsJKZvyXMpDexuBZ6d1y2KiUZzHospw8LmE3tx0P4URlq', NULL, NULL),
(11, 'Sabrina Simone', 'Female', 2147483647, '0769586256', 'nellykinky428@gmail.com', 'Member', '$2y$10$RPPFGDGaVA.oR50F2kmgoeFL7Benn/3CXX8JGLrRsUKDcy2x2Y/v6', NULL, NULL),
(12, 'Dwayne Johnson', 'Male', 2147483647, '0742156135', 'johnson@gmail.com', 'Member', '$2y$10$muRmR1PkFOyupFI.86epHOeip9RpVKunJhCYmsNAegwyUX581rCVq', NULL, NULL),
(13, 'Winnie', 'Female', 2147483647, '0742156137', 'winnie@gmail.com', 'Member', '$2y$10$l6MW2oCegUEt9s5PjowJ8enZ3TilCCsioIcVV5FXBkhNeeNvroidS', NULL, NULL),
(14, 'Angeline Jolie', 'Female', 237845677, '0742156134', 'angeline@gmail.com', 'Member', '$2y$10$2lhRBafjmUq0MaehXOlfeetgL2GzkwyGOr8KEidtEikStZ9p5/84m', NULL, NULL),
(15, 'Joy Lola', 'Female', 127682388, '0742156134', 'kavatafaith161@gmail.com', 'Member', '$2y$10$fe/GT9bZ5R1t5lp6CRzBbeYwaJ6HbROC65dFP7.qJ4Mfk4gTlcVuq', NULL, NULL),
(18, 'Faith Mwende', 'Female', 32617271, '0742156134', 'faith@gmail.com', 'Member', '$2y$10$.8ykTrJnQ1o6xXpGSBr1/O/2NxOzSNrWWeqNG361jA3.0vTYCdcLK', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`contribution_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `member_contributions`
--
ALTER TABLE `member_contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contribution_id` (`contribution_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `loan_id` (`loan_id`);

--
-- Indexes for table `repayments`
--
ALTER TABLE `repayments`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `user_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `member_contributions`
--
ALTER TABLE `member_contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `repayments`
--
ALTER TABLE `repayments`
  MODIFY `repayment_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
