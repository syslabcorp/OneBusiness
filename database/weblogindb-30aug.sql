-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 30, 2017 at 11:18 AM
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
-- Table structure for table `Remit_group`
--

CREATE TABLE `Remit_group` (
  `group_ID` int(11) NOT NULL,
  `branch` text NOT NULL,
  `desc` text NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-Active , 0-Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Remit_group`
--

INSERT INTO `Remit_group` (`group_ID`, `branch`, `desc`, `status`) VALUES
(1, '67,69,71', 'Group 1', 1),
(2, '67,69,71,73', 'Group 2', 1),
(3, '68,70,72,74', 'Group 3', 0);

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
  `template_ID` int(11) DEFAULT NULL,
  `Area_type` text,
  `group_ID` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sysusers`
--

INSERT INTO `sysusers` (`UserID`, `Username`, `Full_Name`, `password`, `pswd_auth`, `otp_auth`, `bio_auth`, `mobile_no`, `email`, `otp`, `otp_generate_time`, `remember_token`, `template_ID`, `Area_type`, `group_ID`, `created_at`, `updated_at`) VALUES
(1, 'JAD', 'John A. Doe', NULL, 1, 1, 0, '09997001234', 'john.doe@gmail.com', NULL, NULL, '', 32, 'PR', '1,2', NULL, NULL),
(2, 'BMV', 'Betty M. Davis', NULL, 0, 0, 1, '08885004321', 'betty.davis@gmail.com', NULL, NULL, '', 28, 'CT', '2', NULL, NULL),
(16, 'aditya', 'Aditya', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 0, '+919876414156', 'aditya@dikonia.in', NULL, NULL, 'pvuGbZhcBaZ9GTvrpzNpDtnnu6I604XJhLLLEKJeWQbYpBhgOOkY2JMqJSeE', NULL, NULL, NULL, '2017-05-09 19:25:46', '2017-05-09 19:25:46'),
(12, 'vineet', 'Vineet', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 0, '+917906500917', 'vineet.kumar@dikonia.in', '8771', '1502257872', 'Xp1eqzkuzNAE04hX7iMle2KUaXLX0h0yeOxvbAJYWha5XmrkGsULHaDoegPV', 31, 'BR', '2,3', '2017-05-08 21:43:22', '2017-05-08 21:43:22'),
(19, 'syslab', 'syslab', 0x6662316561663262643966326137303133363032626532333563333035653761, 1, 1, 1, '+639952232153', 'syslab@gmail.com', '5725', '1496807859', '4iHjYVOvs6U9un1Nh3jIr9HyyV7DuSHMfeEG7Elu6o87Owilygukt1AXFbI4', NULL, NULL, NULL, '2017-05-10 19:46:09', '2017-05-10 19:46:09'),
(18, 'Okin107', 'Niko Goga', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 0, '+365088550505', 'nikogoga87@gmail.com', NULL, NULL, '3xNcVTZ9aD1vRdKHU2p4sFlKr3gYC3adioSXp6VEwHQjTvaSzQI27jQ72178', NULL, NULL, NULL, '2017-05-09 21:31:49', '2017-05-09 21:31:49'),
(51, 'kim', 'Kimberly', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 1, 1, '+639498002536', 'nepa.kimberly13@gmail.com', NULL, NULL, 'Byc8eMovjndIJRuz5gYgbJlGyvWK80ap6fDpKWW44RWBLHWUL517vXtV0JSt', NULL, NULL, NULL, '2017-05-11 15:16:59', '2017-05-11 15:16:59'),
(52, 'syslab1', 'syslab1', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+639952232153', 'syslab1@gmail.com', NULL, NULL, '4klLKiDQh7fUgLFMmk6PVGnUQgvkoXMDUiLlxlXnR9Eg9Tog0FTzQLiElfq0', NULL, NULL, NULL, '2017-05-11 16:05:57', '2017-05-11 16:05:57'),
(53, 'syslab2', 'syslab2', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 1, 1, '+639952232153', 'syslab2@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2017-05-11 16:07:58', '2017-05-11 16:07:58'),
(54, 'syslab3', 'syslab3', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+639952232153', 'asdsd@gmail.com', NULL, NULL, 'I502j1UrDgDwfO3WBN8g279OomtrjYqAtZrYFdbiyZyDyUKo7KxFDYnwh8qu', NULL, NULL, NULL, '2017-05-11 22:55:25', '2017-05-11 22:55:25'),
(55, 'test', 'test', 0x6235363637636131303833623631613236303964386532653834666537366236, 1, 1, 1, '7837537061', 'test@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2017-05-15 22:34:09', '2017-05-15 22:34:09'),
(56, 'syslab4', 'syslab4', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 1, 1, '+639952232153', 'syslab4@gmail.com', NULL, NULL, 'CjO9ra4LQqfIRiWK56sycQnK97snP7bZR1BNFIsspf4wgNaLhAxzaQr3FmFq', NULL, NULL, NULL, '2017-05-16 18:03:32', '2017-05-16 18:03:32'),
(57, 'syslab5', 'syslab5', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 1, 1, '+639952232153', 'syslab5@gmail.com', '7732', '1501815868', 'M1QMJdOudQFXXE7B11Vs65YNwiLGeHIwSuZDGnJm5ry7F0MlkxoZf8CWUOOY', NULL, NULL, NULL, '2017-06-05 22:17:35', '2017-06-05 22:17:35'),
(58, 'aditya1', 'Aditya', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 1, 1, '9876414156', 'aditya.bansal@dikonia.in', NULL, NULL, 'BBlrtsJ87LqhnKlOiNTMJkrnYk5pB6DPIMgHuaoS5B1uGKzNCwz0A0ROlHio', NULL, NULL, NULL, '2017-06-14 01:22:49', '2017-06-14 01:22:49'),
(59, 'fdghfh', 'dfgdf', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 0, '+638956412351', 'dfhgf@dfd.hj', NULL, NULL, 'uHx9aaTQjEGl5NiuDnIDEcdl8zmIPLUtSsQvWP8YagBUPmRzL1YSMmZEc5wa', NULL, NULL, NULL, '2017-06-14 02:16:30', '2017-06-14 02:16:30'),
(60, 'syslab6', 'syslab6', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+639952232153', 'syslab6@gmail.com', NULL, NULL, 'WX4jZT3Lj4QSXRs7xPDSjsd4HfHQ1scnMyCefwtVh0AlhaRe0c5gdIKIpHg1', NULL, NULL, NULL, '2017-06-27 02:27:27', '2017-06-27 02:27:27'),
(61, 'test_account', 'Test Account', 0x3565626532323934656364306530663038656162373639306432613665653639, 1, 0, 0, '', 'nastyuha23@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2017-07-03 03:58:00', '2017-07-03 03:58:00'),
(62, 'mitch123', 'mich paje', 0x6438353738656466383435386365303666626335626237366135386335636134, 1, 1, 1, '+639952232153', 'mitch123@gmail.com', NULL, NULL, 'L6z0llJtaAXW2cjMZSI7ITBUusbpJSc9pTDIqDsTqU53l5AMzhYtF8fZUYyi', NULL, NULL, NULL, '2017-07-13 21:51:37', '2017-07-13 21:51:37'),
(63, 'user1', 'user', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+639952232153', 'user@gmail.com', '3096', '1502359659', 'XPaHDsI45113XgUDBRMHnHDRT4QivUBf23HOrKqtSUTNZNOyL1dZcQK0mG7c', NULL, NULL, NULL, '2017-08-03 21:37:28', '2017-08-03 21:37:28'),
(64, 'juan', 'Juan Tamad', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 1, 0, '+639183030657', 'juantamad@gmail.com', NULL, NULL, 'THBsFCxTLhkpPAmeaVdTlcuBXGKHnWoB38Zw0qQVywp2EvKQyYa6cib6ovsm', NULL, NULL, NULL, '2017-08-08 21:25:31', '2017-08-08 21:25:31'),
(65, 'fluffy', 'fluffy user', 0x6365376263646136393563333061613266396535663339306338323064393835, 1, 1, 0, '+639478335654', 'fluffy@gmail.com', NULL, NULL, 'XrRAjsSso4L8X8nBoLfepJYxq2DoZt4CVdxicEq3omnLBHWbapTvLENVmgHH', NULL, NULL, NULL, '2017-08-08 21:28:52', '2017-08-08 21:28:52'),
(66, 'tuser', 'Test User', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 0, '+639183030657', 'juan_tamad@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2017-08-08 21:59:49', '2017-08-08 21:59:49'),
(67, 'fluff', 'fluff', 0x3737663937356132323338316138626462366331386465366165623762663033, 1, 1, 0, '+639478335654', 'fluffy123@gmail.com', NULL, NULL, 'QarHc9B2rCEQMXibNxlJnpJei3tuQJpQimcinoGALQS3W2bZhQcztdjTOgQb', NULL, NULL, NULL, '2017-08-10 20:40:04', '2017-08-10 20:40:04'),
(68, 'aaaa', 'assd', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+638054065056', 'diksha.ahuja@dikonia.in', NULL, NULL, 'iQsR6ul9xa5xTkGAzyha1ZMpn8V7OOco0alF0HTJX3K9PoRQqw67gXmhddyu', NULL, NULL, NULL, '2017-08-13 23:48:47', '2017-08-13 23:48:47'),
(69, 'Fluffy1', 'Fluffy1', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+638054065056', 'diksha11.ahuja@dikonia.in', NULL, NULL, 'XjDzeJHJ370Nd3kxYnKbQglKNh5bAyO6iujsW0Xm7ok9wuDHO178VqBW6vAa', NULL, NULL, NULL, '2017-08-14 01:07:38', '2017-08-14 01:07:38'),
(70, 'Diksha', 'Diksha', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+638054065056', 'dikshaahuja184@gmail.com', NULL, NULL, 'IrhjkxWDcurjI8Hy1NYrv3ey9SUcwYWdaKI8XAIviJWrZ6W3lRb6wT297LS2', NULL, NULL, NULL, '2017-08-14 01:25:36', '2017-08-14 01:25:36'),
(71, 'ahuja', 'Diksha Ahuja', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+638907654321', 'dikshaahuja1184@gmail.com', NULL, NULL, 'PXNay2N9oP7t6614TJKJYV1C5UvKticVFXOtyIHnzEcV9seAsz7KibfQ9mjM', NULL, NULL, NULL, '2017-08-14 05:13:12', '2017-08-14 05:13:12'),
(72, 'sweet', 'sweet', 0x6531306164633339343962613539616262653536653035376632306638383365, 1, 0, 1, '+631123456789', 'sweet@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2017-08-17 04:15:28', '2017-08-17 04:15:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_area`
--

CREATE TABLE `user_area` (
  `user_ID` int(11) NOT NULL,
  `branch` text NOT NULL,
  `city` text NOT NULL,
  `province` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_area`
--

INSERT INTO `user_area` (`user_ID`, `branch`, `city`, `province`) VALUES
(1, '', '', '1,2,3,4,5,6'),
(2, '', '1,9,2,6,3,7,4,10', ''),
(12, '69,74,72,73,71,67,68,70', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Remit_group`
--
ALTER TABLE `Remit_group`
  ADD PRIMARY KEY (`group_ID`);

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
-- AUTO_INCREMENT for table `Remit_group`
--
ALTER TABLE `Remit_group`
  MODIFY `group_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sysusers`
--
ALTER TABLE `sysusers`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The ID of the user. Autonumber everytime user is added.', AUTO_INCREMENT=73;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
