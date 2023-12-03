-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2023 at 03:38 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gpta`
--
CREATE DATABASE IF NOT EXISTS `gpta` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gpta`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(3, 'admin', '$argon2i$v=19$m=65536,t=4,p=1$bEUxR2xQSDdKMzF1eGVyeQ$aAiczTJ2pqzPEBfrggYIS537EDy6IXyCejQecOyvjLo'),
(7, 'Secretary', '$argon2i$v=19$m=65536,t=4,p=1$RzhDZkMzckhxanFXOFVJbw$Qanmv0k5fXYNQkvjsoXe55+TqiH6wb8d0NujNqROmCU'),
(8, 'Treasurer', '$argon2i$v=19$m=65536,t=4,p=1$TTYwT3BXYzV0d20zcmsySA$KjxNls8zs1Y3l4T2lp3L0Fu9mPB1tKUeJsRm+H0xTbE');

-- --------------------------------------------------------

--
-- Table structure for table `fee`
--

CREATE TABLE `fee` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fee`
--

INSERT INTO `fee` (`id`, `amount`) VALUES
(1, 100);

-- --------------------------------------------------------

--
-- Table structure for table `password`
--

CREATE TABLE `password` (
  `id` int(11) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password`
--

INSERT INTO `password` (`id`, `pass`) VALUES
(1, '$argon2i$v=19$m=65536,t=4,p=1$dTB5cmtlbzgyTnZBTm80RQ$RD7V+nmmsqPEpvU0Ez4x/KXsZxpC/Pmm0ldDlHFZTS0');

-- --------------------------------------------------------

--
-- Table structure for table `report_data`
--

CREATE TABLE `report_data` (
  `id` int(11) NOT NULL,
  `reported_by` varchar(255) NOT NULL,
  `prepared_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_data`
--

INSERT INTO `report_data` (`id`, `reported_by`, `prepared_by`) VALUES
(1, 'MELDY BELEN G. SOSA', 'MARIETA L. RETANAL');

-- --------------------------------------------------------

--
-- Stand-in structure for view `sections`
-- (See below for the actual view)
--
CREATE TABLE `sections` (
`type` text
,`grade_level` int(11)
,`room` text
,`section` text
);

-- --------------------------------------------------------

--
-- Table structure for table `student_lists`
--

CREATE TABLE `student_lists` (
  `id` int(11) NOT NULL,
  `lastname` text NOT NULL,
  `firstname` text NOT NULL,
  `m_i` text NOT NULL,
  `type` text NOT NULL,
  `grade_level` int(11) NOT NULL,
  `room` text NOT NULL,
  `section` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_paid` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `full_name` varchar(255) GENERATED ALWAYS AS (concat(`firstname`,' ',coalesce(`m_i`,''),' ',`lastname`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `sections`
--
DROP TABLE IF EXISTS `sections`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sections`  AS SELECT DISTINCT `student_lists`.`type` AS `type`, `student_lists`.`grade_level` AS `grade_level`, `student_lists`.`room` AS `room`, `student_lists`.`section` AS `section` FROM `student_lists` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee`
--
ALTER TABLE `fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password`
--
ALTER TABLE `password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_data`
--
ALTER TABLE `report_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_lists`
--
ALTER TABLE `student_lists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fee`
--
ALTER TABLE `fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password`
--
ALTER TABLE `password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_data`
--
ALTER TABLE `report_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_lists`
--
ALTER TABLE `student_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
