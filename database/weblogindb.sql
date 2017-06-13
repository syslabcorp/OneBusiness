-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 13, 2017 at 11:49 AM
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
-- Table structure for table `corporation_masters`
--

CREATE TABLE `corporation_masters` (
  `corp_id` int(11) NOT NULL,
  `corp_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted',
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `feature_masters`
--

CREATE TABLE `feature_masters` (
  `feature_id` int(11) NOT NULL,
  `feature` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted',
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `module_masters`
--

CREATE TABLE `module_masters` (
  `module_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `corp_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted',
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rights_dave`
--

CREATE TABLE `rights_dave` (
  `template_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `access_delete` tinyint(1) NOT NULL DEFAULT '0',
  `access_add` tinyint(1) NOT NULL DEFAULT '0',
  `access_view` tinyint(1) NOT NULL DEFAULT '0',
  `access_edit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rights_detail`
--

CREATE TABLE `rights_detail` (
  `module_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `access_type` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rights_mstr`
--

CREATE TABLE `rights_mstr` (
  `module_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rights_template`
--

CREATE TABLE `rights_template` (
  `template_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `corp_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Inactive',
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for table `corporation_masters`
--
ALTER TABLE `corporation_masters`
  ADD PRIMARY KEY (`corp_id`);

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
-- Indexes for table `feature_masters`
--
ALTER TABLE `feature_masters`
  ADD PRIMARY KEY (`feature_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_masters`
--
ALTER TABLE `module_masters`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `rights_template`
--
ALTER TABLE `rights_template`
  ADD PRIMARY KEY (`template_id`);

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
-- AUTO_INCREMENT for table `corporation_masters`
--
ALTER TABLE `corporation_masters`
  MODIFY `corp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `feature_masters`
--
ALTER TABLE `feature_masters`
  MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `module_masters`
--
ALTER TABLE `module_masters`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `rights_template`
--
ALTER TABLE `rights_template`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `sysusers`
--
ALTER TABLE `sysusers`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The ID of the user. Autonumber everytime user is added.', AUTO_INCREMENT=58;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
