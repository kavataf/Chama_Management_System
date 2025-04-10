-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 09:52 PM
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
(10, 11, 1, 50000, 8, 'start a business', 'Business Capital', 'Approved', '2025-03-11', 5, 0),
(11, 11, 1, 50000, 8, 'start a business', 'Business Capital', 'Rejected', '2025-03-11', 5, 0),
(13, 12, 1, 210000, 8, 'business capital', 'business capital', 'Approved', '2025-04-07', 0, 210000);

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
(7, 'Loan Repayment Contribution', 2000, '2025-05-01', '2025-03-25 13:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(200) NOT NULL,
  `vendor_name` varchar(200) NOT NULL,
  `expense_type` varchar(200) NOT NULL,
  `reference_no` varchar(200) NOT NULL,
  `expense_amount` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `user_id` int(200) NOT NULL,
  `member_name` varchar(200) NOT NULL,
  `member_gender` varchar(200) NOT NULL,
  `member_id_no` varchar(200) NOT NULL,
  `member_phone` varchar(200) NOT NULL,
  `member_email` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `member_name`, `member_gender`, `member_id_no`, `member_phone`, `member_email`, `created_at`) VALUES
(9, 'Faith Kavata', 'Female', '2378929011', '0742156134', 'kavatafaith161@gmail.com', '2025-03-29 14:10:52'),
(11, 'Sabrina Simone', 'Female', '2378929019', '0769586256', 'nellykinky428@gmail.com', '2025-02-10 14:10:52'),
(12, 'Dwayne Johnson', 'Male', '2378456778', '0742156135', 'johnson@gmail.com', '2025-03-29 14:13:12'),
(13, 'Winnie', 'Female', '3456782910', '0742156137', 'winnie@gmail.com', '2025-03-29 14:14:35');

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
  `status` varchar(200) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_contributions`
--

INSERT INTO `member_contributions` (`id`, `contribution_id`, `member_id`, `amount_paid`, `payment_date`, `status`) VALUES
(2, 4, 11, 500, '2025-03-25 12:54:48', 'paid'),
(7, 1, 11, 500, '2025-03-25 13:19:48', 'paid'),
(9, 6, 11, 200, '2025-03-25 13:23:01', 'Partially Paid'),
(10, 5, 11, 500, '2025-03-29 13:06:18', 'Partially Paid'),
(11, 5, 9, 1000, '2025-04-05 17:13:47', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(200) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `member_id`, `message`, `status`, `sent_at`) VALUES
(1, 9, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(2, 12, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(3, 13, 'You have a pending contribution of $500 due on 2025-01-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(4, 9, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(5, 11, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(6, 12, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(7, 13, 'You have a pending contribution of $5000 due on 2025-05-30. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(8, 9, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(9, 11, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(10, 12, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(11, 13, 'You have a pending contribution of $500 due on 2025-02-28. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(12, 9, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(13, 12, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(14, 13, 'You have a pending contribution of $500 due on 2025-03-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(15, 9, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(16, 11, 'You have a remaining contribution balance of $500 due on 2025-06-30.', 'unread', '2025-04-03 14:48:37'),
(17, 12, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(18, 13, 'You have a pending contribution of $1000 due on 2025-06-30. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(19, 9, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(20, 11, 'You have a remaining contribution balance of $300 due on 2025-05-31.', 'unread', '2025-04-03 14:48:37'),
(21, 12, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(22, 13, 'You have a pending contribution of $500 due on 2025-05-31. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(23, 9, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(24, 11, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(25, 12, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '2025-04-03 14:48:37'),
(26, 13, 'You have a pending contribution of $2000 due on 2025-05-01. Please make your payment.', 'unread', '2025-04-03 14:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(200) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_name` varchar(200) NOT NULL,
  `loan_interest` int(200) NOT NULL,
  `loan_duration` varchar(200) NOT NULL,
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
(5, 1, 'Business Capital', 5, '8', 800, 200000, 'No', 30000, 'uploads/payment receipt.jpg', 10000, 'capital to start a business');

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
(24, 11, 10, '', 1000, 0, NULL, '2025-04-05', 'overdue'),
(25, 11, 10, '', 1000, 1000, '2025-04-07', '2025-04-20', 'paid'),
(26, 11, 10, '', 1000, 1000, '2025-04-07', '2025-04-20', ''),
(37, 12, 13, 'business capital', 26250, 20000, '2025-04-07', '2025-05-07', 'pending'),
(38, 12, 13, 'business capital', 26250, 0, NULL, '2025-06-07', 'pending'),
(39, 12, 13, 'business capital', 26250, 0, NULL, '2025-07-07', 'pending'),
(40, 12, 13, 'business capital', 26250, 0, NULL, '2025-08-07', 'pending'),
(41, 12, 13, 'business capital', 26250, 0, NULL, '2025-09-07', 'pending'),
(42, 12, 13, 'business capital', 26250, 0, NULL, '2025-10-07', 'pending'),
(43, 12, 13, 'business capital', 26250, 0, NULL, '2025-11-07', 'pending'),
(44, 12, 13, 'business capital', 26250, 0, NULL, '2025-12-07', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `savings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference_no` varchar(200) NOT NULL,
  `amount` decimal(60,0) NOT NULL,
  `savings_date` varchar(200) NOT NULL,
  `payment_method` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`savings_id`, `user_id`, `reference_no`, `amount`, `savings_date`, `payment_method`) VALUES
(4, 11, 'SDTRGYUHJ', 70000, '2025-02-17', 'Mpesa'),
(8, 12, 'SDTRGYUHJ', 100000, '2025', 'Mpesa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_gender` varchar(200) NOT NULL,
  `user_id_no` varchar(200) NOT NULL,
  `user_phone` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_access_level` varchar(200) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `user_unhashed_password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_gender`, `user_id_no`, `user_phone`, `user_email`, `user_access_level`, `user_password`, `user_unhashed_password`) VALUES
(8, 'Faith Kavata', '', '41109057', '0742156134', 'kavatafaith412@gmail.com', 'System Administrator', '$2y$10$uwRnIkjKMZgo1Y0sQmL/beWwHFg67kHotsmSo4StdPQBCzFvRC7WS', ''),
(9, 'Faith Kavata', 'Female', '2378929011', '0742156134', 'kavatafaith161@gmail.com', 'Member', '$2y$10$88jprRnkPsJKZvyXMpDexuBZ6d1y2KiUZzHospw8LmE3tx0P4URlq', 'cb79cec80007a462'),
(11, 'Sabrina Simone', 'Female', '2378929019', '0769586256', 'nellykinky428@gmail.com', 'Member', '$2y$10$RPPFGDGaVA.oR50F2kmgoeFL7Benn/3CXX8JGLrRsUKDcy2x2Y/v6', ''),
(12, 'Dwayne Johnson', 'Male', '2378456778', '0742156135', 'johnson@gmail.com', 'Member', '$2y$10$muRmR1PkFOyupFI.86epHOeip9RpVKunJhCYmsNAegwyUX581rCVq', '5d1913b2b282aa1f'),
(13, 'Winnie', 'Female', '3456782910', '0742156137', 'winnie@gmail.com', 'Member', '$2y$10$l6MW2oCegUEt9s5PjowJ8enZ3TilCCsioIcVV5FXBkhNeeNvroidS', '5008a738fa068501');

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
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

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
  ADD PRIMARY KEY (`savings_id`),
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
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `user_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `member_contributions`
--
ALTER TABLE `member_contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `repayments`
--
ALTER TABLE `repayments`
  MODIFY `repayment_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `savings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`loan_id`) REFERENCES `products` (`loan_id`);

--
-- Constraints for table `member_contributions`
--
ALTER TABLE `member_contributions`
  ADD CONSTRAINT `member_contributions_ibfk_1` FOREIGN KEY (`contribution_id`) REFERENCES `contributions` (`contribution_id`),
  ADD CONSTRAINT `member_contributions_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`user_id`);

--
-- Constraints for table `repayments`
--
ALTER TABLE `repayments`
  ADD CONSTRAINT `repayments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `repayments_ibfk_2` FOREIGN KEY (`loan_id`) REFERENCES `applications` (`application_id`);

--
-- Constraints for table `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
