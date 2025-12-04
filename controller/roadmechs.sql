-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2025 at 11:49 AM
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
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` bigint(250) NOT NULL,
  `full name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
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
  `user_type` int(10) NOT NULL COMMENT '0 = driver, 1 = mechanic'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
