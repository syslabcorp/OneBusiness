-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 15, 2017 at 10:44 AM
-- Server version: 5.5.47
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weblogindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `rights_template`
--

CREATE TABLE `rights_template` (
  `template_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `template_menus` varchar(255) DEFAULT NULL COMMENT 'Menu ids comma seprated',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Super Admin, 0-Normal User',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rights_template`
--
ALTER TABLE `rights_template`
  ADD PRIMARY KEY (`template_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rights_template`
--
ALTER TABLE `rights_template`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
