-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 06:10 PM
-- Server version: 5.5.40
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `roadmechs`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
`id` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` bigint(250) NOT NULL,
  `full name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `bio` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `profile_pic` varchar(250) DEFAULT NULL,
  `phone_no` varchar(50) DEFAULT NULL,
  `pay_rate` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `fb_uname` varchar(50) DEFAULT NULL,
  `wa_no` varchar(50) DEFAULT NULL,
  `insta_uname` varchar(50) DEFAULT NULL,
  `notification` int(10) NOT NULL DEFAULT '0' COMMENT ' 1= checked, 0 = unchecked',
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full name`, `email`, `password`, `bio`, `state`, `profile_pic`, `phone_no`, `pay_rate`, `address`, `fb_uname`, `wa_no`, `insta_uname`, `notification`, `role`) VALUES
(1, NULL, 'immanuel@yahoo.com', '$2y$10$Lj5lzGjQmjrZROVA/tagkO4WkdCDXsK21N.sXy30z6YRFLyN8dWuW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'mechanic'),
(2, NULL, 'chinweokwumary08@gmail.com', '$2y$10$9Sg4ZZ2atIFmr4rXNphRneX0wkFfxu/.TtEIsez8ydyMXtoAanoza', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'mechanic');

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
MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
