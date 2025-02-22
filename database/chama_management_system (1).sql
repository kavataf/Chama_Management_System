-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2025 at 08:37 AM
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
  `application_id` int(200) NOT NULL,
  `user_id` int(200) NOT NULL,
  `loan_amount` int(200) NOT NULL,
  `loan_duration` int(200) NOT NULL,
  `loan_purpose` varchar(200) NOT NULL,
  `loan_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `member_email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `member_name`, `member_gender`, `member_id_no`, `member_phone`, `member_email`) VALUES
(9, 'Faith Kavata', 'Female', '2378929011', '0742156134', 'kavatafaith161@gmail.com'),
(10, 'Sabrina Simone', 'Female', '2378929019', '0769586256', 'sabrina@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(200) NOT NULL,
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

INSERT INTO `products` (`product_id`, `loan_name`, `loan_interest`, `loan_duration`, `processing_fee`, `maximum_limit`, `loan_guarantors`, `member_savings`, `thumbnail`, `loan_penalty`, `loan_description`) VALUES
(1, 'Business Capital', 5000, '8', 800, 200000, 'Yes', 30000, 'uploads/payment receipt.jpg', 10000, 'Capital to start business');

-- --------------------------------------------------------

--
-- Table structure for table `repayments`
--

CREATE TABLE `repayments` (
  `repayment_id` int(200) NOT NULL,
  `member_name` varchar(200) NOT NULL,
  `member_id_no` int(200) NOT NULL,
  `loan_name` varchar(200) NOT NULL,
  `loan_amount` int(200) NOT NULL,
  `loan_interest` int(200) NOT NULL,
  `processing_fee` int(200) NOT NULL,
  `amount_paid` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `savings_id` int(200) NOT NULL,
  `member_name` varchar(200) NOT NULL,
  `member_id_no` int(200) NOT NULL,
  `member_phone` int(200) NOT NULL,
  `reference_no` varchar(200) NOT NULL,
  `amount` int(200) NOT NULL,
  `savings_date` date NOT NULL,
  `payment_method` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`savings_id`, `member_name`, `member_id_no`, `member_phone`, `reference_no`, `amount`, `savings_date`, `payment_method`) VALUES
(1, 'Sabrina Simone', 2147483647, 742156134, 'SDTRGYUHJ', 20000, '2025-01-06', 'Mpesa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(200) NOT NULL,
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
(8, 'Faith Kavata', '', '', '', 'kavatafaith412@gmail.com', 'System Administrator', '$2y$10$uwRnIkjKMZgo1Y0sQmL/beWwHFg67kHotsmSo4StdPQBCzFvRC7WS', ''),
(9, 'Faith Kavata', 'Female', '2378929011', '0742156134', 'kavatafaith161@gmail.com', 'Member', '$2y$10$88jprRnkPsJKZvyXMpDexuBZ6d1y2KiUZzHospw8LmE3tx0P4URlq', 'cb79cec80007a462'),
(10, 'Sabrina Simone', 'Female', '2378929019', '0769586256', 'sabrina@gmail.com', 'Member', '$2y$10$7XjouLgsj85bRImYegnpRepKK00vl5deA8fe.3/DMqx2BdxyyGqrS', '7028eaceda261197');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`);

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `repayments`
--
ALTER TABLE `repayments`
  ADD PRIMARY KEY (`repayment_id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`savings_id`);

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
  MODIFY `application_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `user_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `repayments`
--
ALTER TABLE `repayments`
  MODIFY `repayment_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `savings_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
