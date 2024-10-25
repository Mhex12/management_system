-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 10:14 AM
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
-- Database: `management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `password`, `position`, `salary`, `created_at`, `user_id`) VALUES
(1, 'John Doe', 'johndoe@example.com', '$2y$10$b3.q3bpSYXOZuXRdTW06kO0XR/jSeZZUgTHQ8g0ZwlB8YUifsfCna', 'Manager', 75000.00, '2024-10-24 02:38:53', NULL),
(3, 'Angelica', 'angelicas12@gmail.com', 'hello123', 'manager', 2000.00, '2024-10-25 01:37:34', 2),
(4, 'Shane', 'shane12@gmail.com', '', 'Manager', 1234.00, '2024-10-25 07:01:26', NULL),
(5, 'Shane', 'shane@gmail.com', '', 'Manager', 1234.00, '2024-10-25 07:01:40', NULL),
(6, 'Jose', 'angel@gmail.com', '$2y$10$fieXwTHwqukAUJUfZ18Ff.U40WduF2cOO6AgC6d3s.E304cAq/rre', '0', 432.00, '2024-10-25 07:20:47', 3),
(7, 'Dianne', 'dianne@gmail.com', '$2y$10$aIIO0XOzzeIPdR5ByfQDceoD/9ma2k5hn5PZbTiDzhQ7DbOFYFyZi', '0', 234.00, '2024-10-25 07:44:03', 2),
(8, 'lian', 'lian@gmail.com', '$2y$10$MFlzHTeWLKs8v6JhuxvbQeTAou.bzjlUB8fhl5H7PcOPVHboXWKLW', '0', 432.00, '2024-10-25 08:11:52', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Angel', 'angel@gmail.com', '$2y$10$l7A3wWfWGZtYYcQaDH1Z2uz26LTegcB10QFTQ.mrNGywBdHhfoxIu', 'user', '2024-10-24 05:05:14'),
(3, 'lia', 'lia@gmail.com', '$2y$10$KQ0OQ570iPWbJLsOSd4QpOsc/ShzWcDq9LTiAIDblR/oIOupKVe5a', 'user', '2024-10-25 03:25:19'),
(5, 'admin', 'admin@gmail.com', '$2y$10$.0dimspEGImZlg.ZoLEnzejoAS4amHgeHWMfLMJ0ReimPi7ZqxO.a', 'admin', '2024-10-25 05:39:29'),
(6, 'dsfs', 'dsfa@gmail.com', '$2y$10$b6qxIQIapFS7MnHhDLAzvuZccszxAVMLWFwdaQrPq.pto6Ipzhkwa', 'user', '2024-10-25 07:28:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
