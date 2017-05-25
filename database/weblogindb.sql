-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2017 at 12:56 PM
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
-- Table structure for table `demo_device`
--

CREATE TABLE `demo_device` (
  `device_name` varchar(50) NOT NULL,
  `sn` varchar(50) NOT NULL,
  `vc` varchar(50) NOT NULL,
  `ac` varchar(50) NOT NULL,
  `vkey` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `demo_device`
--

INSERT INTO `demo_device` (`device_name`, `sn`, `vc`, `ac`, `vkey`) VALUES
('Dikonia', 'C800V000926', '359A43B970CDE5E', 'A2G7008DDB83E4B2D657AX8G', 'A240F09E14E8C869D270D4AE2E435F70');

-- --------------------------------------------------------

--
-- Table structure for table `demo_finger`
--

CREATE TABLE `demo_finger` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `finger_id` int(11) UNSIGNED NOT NULL,
  `finger_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `demo_log`
--

CREATE TABLE `demo_log` (
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_name` varchar(50) NOT NULL,
  `data` text NOT NULL COMMENT 'sn+pc time'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sysusers`
--

CREATE TABLE `sysusers` (
  `UserID` int(11) NOT NULL COMMENT 'The ID of the user. Autonumber everytime user is added.',
  `Username` varchar(20) DEFAULT NULL COMMENT 'The username to be verified during the login process',
  `Full_Name` varchar(70) DEFAULT NULL COMMENT 'User full name',
  `password` tinyblob COMMENT 'User Password',
  `pswd_auth` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Password authentication flag. 1=Yes; 0=No',
  `otp_auth` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'OTP authentication flag.  1=Yes; 0=No',
  `bio_auth` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Biometric fingerprint authentication flag.  1=Yes; 0=No',
  `mobile_no` varchar(15) DEFAULT NULL COMMENT 'User''s mobile number where SMS authenication will be sent',
  `email` varchar(100) DEFAULT NULL COMMENT 'User''s email addres',
  `otp` varchar(255) DEFAULT NULL COMMENT 'Random number send as otp on user mobile',
  `otp_generate_time` varchar(255) DEFAULT NULL COMMENT 'Time when otp sent to user.',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demo_device`
--
ALTER TABLE `demo_device`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `demo_finger`
--
ALTER TABLE `demo_finger`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `demo_log`
--
ALTER TABLE `demo_log`
  ADD PRIMARY KEY (`log_time`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sysusers`
--
ALTER TABLE `sysusers`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `IDX_t_sysusers_2` (`Username`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sysusers`
--
ALTER TABLE `sysusers`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The ID of the user. Autonumber everytime user is added.';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
