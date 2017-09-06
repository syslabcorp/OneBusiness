-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 06, 2017 at 12:09 PM
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
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `UserID` bigint(11) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `UserName` varchar(150) NOT NULL,
  `acct_no` varchar(15) NOT NULL DEFAULT '000000000000',
  `Password` varchar(60) DEFAULT NULL,
  `passwrd` tinyblob,
  `Position` varchar(30) DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  `Level` tinyint(4) NOT NULL DEFAULT '1',
  `Hired` date DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Bday` date DEFAULT NULL,
  `Sex` varchar(6) DEFAULT '0',
  `TIN` varchar(30) NOT NULL DEFAULT '0',
  `SSS` varchar(30) NOT NULL DEFAULT '0',
  `PHIC` varchar(30) NOT NULL DEFAULT '0',
  `Pagibig` varchar(30) NOT NULL DEFAULT '0',
  `rate_id` int(11) DEFAULT NULL,
  `FullRate` double(13,2) NOT NULL DEFAULT '0.00',
  `Rate` decimal(13,4) NOT NULL DEFAULT '0.0000',
  `PayBasis` int(11) NOT NULL DEFAULT '0',
  `Status_Tbl` varchar(5) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  `Branch` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `SQ_Active` tinyint(1) NOT NULL DEFAULT '0',
  `SQ_Branch` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `Technician` tinyint(1) NOT NULL DEFAULT '0',
  `TechActive` tinyint(1) NOT NULL DEFAULT '0',
  `AllowedMins` int(7) DEFAULT NULL,
  `LoginsLeft` int(7) DEFAULT NULL,
  `LastUnfrmPaid` date DEFAULT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `MidName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `SuffixName` varchar(10) DEFAULT NULL,
  `template` blob,
  `template2` blob,
  `template3` blob,
  `template4` blob,
  `split_type` char(2) NOT NULL DEFAULT 'O',
  `enrolled` tinyint(4) NOT NULL DEFAULT '0',
  `Full_Name` varchar(70) DEFAULT NULL,
  `pswd_auth` tinyint(1) NOT NULL DEFAULT '1',
  `otp_auth` tinyint(1) NOT NULL DEFAULT '0',
  `bio_auth` tinyint(1) NOT NULL DEFAULT '0',
  `mobile_no` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_generate_time` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `rights_template_id` int(11) NOT NULL DEFAULT '0',
  `Area_type` text,
  `group_ID` text,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `idx_Username` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `UserID` bigint(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
