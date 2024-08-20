-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 20, 2024 at 09:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `GYM`
--

-- --------------------------------------------------------

--
-- Table structure for table `Attendances`
--

CREATE TABLE `Attendances` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `AttendanceDate` date NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Present','Absent') NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Equipments`
--

CREATE TABLE `Equipments` (
  `EquipmentID` int(11) NOT NULL,
  `AddedBy` enum('owner','trainer','member','worker','janitor') NOT NULL,
  `EquipmentName` varchar(30) NOT NULL,
  `BuyingPrice` int(11) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Equipments`
--

INSERT INTO `Equipments` (`EquipmentID`, `AddedBy`, `EquipmentName`, `BuyingPrice`, `CreatedAt`, `CreatedBy`) VALUES
(1, 'owner', 'BenchPress', 26000, '2024-07-31 18:05:59', NULL),
(2, 'owner', 'Butterfly machine', 50000, '2024-07-31 18:09:18', NULL),
(4, 'owner', 'chest press', 25000, '2024-08-01 00:43:12', NULL),
(5, 'owner', 'Dumbell', 6000, '2024-08-09 23:26:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Exercises`
--

CREATE TABLE `Exercises` (
  `ID` int(11) NOT NULL,
  `ExerciseName` varchar(50) NOT NULL,
  `ComplexityLevel` enum('Basic','Intermediate','Advanced') NOT NULL,
  `MuscleGroup` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Notifications`
--

CREATE TABLE `Notifications` (
  `ID` int(11) NOT NULL,
  `Source` int(11) NOT NULL,
  `Destination` int(11) NOT NULL,
  `Message` varchar(256) NOT NULL,
  `Status` enum('sent','read') NOT NULL,
  `SentAt` datetime NOT NULL DEFAULT current_timestamp(),
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Notifications`
--

INSERT INTO `Notifications` (`ID`, `Source`, `Destination`, `Message`, `Status`, `SentAt`, `CreatedAt`, `CreatedBy`) VALUES
(1, 3, 1, 'Gym will be closed coming Saturday.', 'sent', '2024-07-23 17:43:34', '2024-07-23 17:43:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Packages`
--

CREATE TABLE `Packages` (
  `PackageID` int(11) NOT NULL,
  `PackageName` enum('gold','silver','platinum') NOT NULL,
  `PackagePrice` int(11) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Packages`
--

INSERT INTO `Packages` (`PackageID`, `PackageName`, `PackagePrice`, `CreatedAt`, `CreatedBy`) VALUES
(1, 'silver', 8000, '2024-08-02 03:14:15', NULL),
(2, 'gold', 2200, '2024-08-02 03:14:36', NULL),
(3, 'platinum', 3000, '2024-08-02 03:14:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Payments`
--

CREATE TABLE `Payments` (
  `PaymentID` int(11) NOT NULL,
  `PaidBy` int(11) NOT NULL,
  `PayerAmount` int(11) NOT NULL,
  `PaymentMethod` enum('online','cash') NOT NULL,
  `PaymentRecievedBy` enum('owner','trainer') NOT NULL,
  `PaymentProof` varchar(256) DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Timings`
--

CREATE TABLE `Timings` (
  `ID` int(11) NOT NULL,
  `Shifts` enum('morning','evening') NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) DEFAULT NULL,
  `startTime` varchar(10) NOT NULL,
  `endTime` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Timings`
--

INSERT INTO `Timings` (`ID`, `Shifts`, `CreatedAt`, `CreatedBy`, `startTime`, `endTime`) VALUES
(1, 'morning', '2024-08-01 02:44:27', NULL, '04:00:00', '00:00:00'),
(2, 'evening', '2024-08-01 02:45:18', NULL, '17:00:00', '23:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `UserInfo`
--

CREATE TABLE `UserInfo` (
  `ID` int(11) NOT NULL,
  `Gender` enum('male','female') NOT NULL,
  `Age` int(11) NOT NULL,
  `Weight` varchar(5) NOT NULL,
  `Height` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UserPackage`
--

CREATE TABLE `UserPackage` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PackageID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `UserPackage`
--

INSERT INTO `UserPackage` (`ID`, `UserID`, `PackageID`) VALUES
(1, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `ID` int(11) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `CreatedAt` date NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) DEFAULT NULL,
  `UpdatedAt` date DEFAULT current_timestamp(),
  `UpdatedBy` int(11) DEFAULT NULL,
  `UserType` enum('owner','trainer','member','worker','janitor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`ID`, `fname`, `lname`, `phone`, `email`, `username`, `password`, `CreatedAt`, `CreatedBy`, `UpdatedAt`, `UpdatedBy`, `UserType`) VALUES
(1, 'Fras', 'Irfan', '03038843714', 'fras.irfan@outlook.com', 'fras', '$2y$10$As/rIpB/LPcCoyjEqf1qwercdnlVXhpISnZ4KP4uEVQGznYTMM7di', '2024-08-17', NULL, '2024-08-17', NULL, 'owner'),
(2, 'Hassan', 'Faisal', '92', 'hassanfaisal@gmail.com', 'hassan', '$2y$10$2Lb0Cwf.fmNnyltW.SrC8Oaoibj9FsbvBuaC6..BOHmKFydyTS.gG', '2024-08-17', 0, '2024-08-17', NULL, 'member'),
(3, 'Qazal', 'Irfan', '03092017821', 'qazalirfan15@gmail.com', 'Qazal', '$2y$10$Wlf6Zos7sJLBwAQ5oJgkZu74r2z9ZUptmG42veegy7HOzruqR8xH.', '2024-08-17', 0, '2024-08-17', NULL, 'member'),
(4, 'Usman', 'Rashid', '92', 'usman@gmail.com', 'usman', '$2y$10$mVK2DPJjcHXDFL8mxDFnxOqUd9R14sEVTCOBEENiLuFYb2egVyHiy', '2024-08-17', 1, '2024-08-17', NULL, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `UserTimings`
--

CREATE TABLE `UserTimings` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TimingID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `UserTimings`
--

INSERT INTO `UserTimings` (`ID`, `UserID`, `TimingID`) VALUES
(1, 2, 1),
(2, 3, 2),
(3, 4, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Attendances`
--
ALTER TABLE `Attendances`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_attendances_userid_users_id` (`UserID`);

--
-- Indexes for table `Equipments`
--
ALTER TABLE `Equipments`
  ADD PRIMARY KEY (`EquipmentID`);

--
-- Indexes for table `Exercises`
--
ALTER TABLE `Exercises`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Notifications`
--
ALTER TABLE `Notifications`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_source` (`Source`),
  ADD KEY `fk_destination` (`Destination`);

--
-- Indexes for table `Packages`
--
ALTER TABLE `Packages`
  ADD PRIMARY KEY (`PackageID`);

--
-- Indexes for table `Payments`
--
ALTER TABLE `Payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `fk_paidBy_userID` (`PaidBy`);

--
-- Indexes for table `Timings`
--
ALTER TABLE `Timings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `UserInfo`
--
ALTER TABLE `UserInfo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `UserPackage`
--
ALTER TABLE `UserPackage`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PackageID` (`PackageID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `UserTimings`
--
ALTER TABLE `UserTimings`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `TimingID` (`TimingID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Attendances`
--
ALTER TABLE `Attendances`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Equipments`
--
ALTER TABLE `Equipments`
  MODIFY `EquipmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Exercises`
--
ALTER TABLE `Exercises`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Notifications`
--
ALTER TABLE `Notifications`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Packages`
--
ALTER TABLE `Packages`
  MODIFY `PackageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Payments`
--
ALTER TABLE `Payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `Timings`
--
ALTER TABLE `Timings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `UserInfo`
--
ALTER TABLE `UserInfo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserPackage`
--
ALTER TABLE `UserPackage`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `UserTimings`
--
ALTER TABLE `UserTimings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Attendances`
--
ALTER TABLE `Attendances`
  ADD CONSTRAINT `fk_attendances_userid_users_id` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `Payments`
--
ALTER TABLE `Payments`
  ADD CONSTRAINT `fk_paidBy_userID` FOREIGN KEY (`PaidBy`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `UserPackage`
--
ALTER TABLE `UserPackage`
  ADD CONSTRAINT `UserPackage_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserPackage_ibfk_2` FOREIGN KEY (`PackageID`) REFERENCES `Packages` (`PackageID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `UserTimings`
--
ALTER TABLE `UserTimings`
  ADD CONSTRAINT `UserTimings_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserTimings_ibfk_2` FOREIGN KEY (`TimingID`) REFERENCES `Timings` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
