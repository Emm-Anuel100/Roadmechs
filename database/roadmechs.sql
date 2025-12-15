-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 06:10 PM
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
-- Database: `roadmechs`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` bigint(255) NOT NULL,
  `email_d` varchar(100) DEFAULT NULL,
  `email_m` varchar(100) DEFAULT NULL,
  `amount_ada` float NOT NULL DEFAULT 0,
  `transaction_date` date DEFAULT NULL,
  `tx_hash` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `email_d`, `email_m`, `amount_ada`, `transaction_date`, `tx_hash`) VALUES
(3, 'ch.immanuel@yahoo.co', 'ch.immanuel@yahoo.com', 68.3737, '2025-12-14', 'tx_nyr524ekf4'),
(4, 'ch.immanuel@yahoo.co', 'ch.immanuel@yahoo.com', 62.8765, '2025-12-15', 'tx_gyhrgth');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(250) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `bio` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `profile_pic` varchar(250) DEFAULT 'default_img.png',
  `phone_no` varchar(50) DEFAULT NULL,
  `pay_rate` bigint(50) DEFAULT 0,
  `wallet` varchar(255) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `fb_uname` varchar(50) DEFAULT NULL,
  `wa_no` varchar(50) DEFAULT NULL,
  `insta_uname` varchar(50) DEFAULT NULL,
  `notification` int(10) NOT NULL DEFAULT 0 COMMENT ' 1= checked, 0 = unchecked',
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `bio`, `state`, `profile_pic`, `phone_no`, `pay_rate`, `wallet`, `address`, `fb_uname`, `wa_no`, `insta_uname`, `notification`, `role`) VALUES
(13, 'Dave fred', 'ch.immanuel@yahoo.co', '$2y$10$mJvhcBFG3FwQT9K/f1d4Ou923wJrkcnspf2mngSOmNpu7PleoSFV.', 'Rolls Royce Driver.. in the city of Abuja', 'Kogi', 'IMG_693c5ece260cf8.92393655.jpg', '09075439515', 0, NULL, 'jikwoyi phase 3 abuja', 'fb', '812188', 'insta', 0, 'driver'),
(14, 'Sammy Dave', 'ch.immanuel@yahoo.com', '$2y$10$DnNlQcGzS.gY6enKdrZqE.RGQMt8172FgCDn6fikY/8Xu11TF9VMK', 'A real mechanic .. with years of experience', 'Abuja', 'IMG_693c5f261e8dc3.68660336.jpg', '090', 40000, 'oihugyfguiu867r6fgyhhres45r7t757tdt', 'jikwoyi phase 2 junction', 'emmy', '08121', 'emmy_1', 0, 'mechanic'),
(15, 'Adanna', 'ada@gmail.com', '$2y$10$ilVmyEjRuJRY8fHMM22GD.xj4P9GLp60xHZiiz7AYp8fO3EgrKxjW', 'world class', 'Abia', 'default_img.png', '09033636345', 45000, NULL, 'Agwangede jikwoyi phase 3', NULL, NULL, NULL, 0, 'mechanic');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
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
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
