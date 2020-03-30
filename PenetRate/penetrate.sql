-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2020 at 09:38 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penetrate`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientside_scan`
--

CREATE TABLE `clientside_scan` (
  `ClientSideScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `directories_scan`
--

CREATE TABLE `directories_scan` (
  `DirectoriesScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `findings_bank`
--

CREATE TABLE `findings_bank` (
  `FindingID` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `recommendation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `generalvulnerabilities_scan`
--

CREATE TABLE `generalvulnerabilities_scan` (
  `GeneralVulnerabilitiesScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `headers_scan`
--

CREATE TABLE `headers_scan` (
  `HeadersScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ports_scan`
--

CREATE TABLE `ports_scan` (
  `PortsScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scans`
--

CREATE TABLE `scans` (
  `ScanID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `URL` int(11) NOT NULL,
  `Login_URL` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `Is_PortsScan` tinyint(1) NOT NULL,
  `Is_SSLScan` tinyint(1) NOT NULL,
  `Is_SubdomainsScan` tinyint(1) NOT NULL,
  `Is_DirectoriesScan` tinyint(1) NOT NULL,
  `Is_HeadersScan` tinyint(1) NOT NULL,
  `Is_ClientSideScan` tinyint(1) NOT NULL,
  `Is_GeneralVulnerabilitiesScan` tinyint(1) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ssl_scan`
--

CREATE TABLE `ssl_scan` (
  `SSLScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subdomains_scan`
--

CREATE TABLE `subdomains_scan` (
  `SubdomainsScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Fullname` text NOT NULL,
  `Phone` text NOT NULL,
  `Position` text NOT NULL,
  `Role` text NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientside_scan`
--
ALTER TABLE `clientside_scan`
  ADD PRIMARY KEY (`ClientSideScan_ID`),
  ADD KEY `ScanID` (`ScanID`);

--
-- Indexes for table `directories_scan`
--
ALTER TABLE `directories_scan`
  ADD PRIMARY KEY (`DirectoriesScan_ID`),
  ADD KEY `ScanID` (`ScanID`);

--
-- Indexes for table `findings_bank`
--
ALTER TABLE `findings_bank`
  ADD PRIMARY KEY (`FindingID`);

--
-- Indexes for table `generalvulnerabilities_scan`
--
ALTER TABLE `generalvulnerabilities_scan`
  ADD PRIMARY KEY (`GeneralVulnerabilitiesScan_ID`),
  ADD KEY `ScanID` (`ScanID`);

--
-- Indexes for table `headers_scan`
--
ALTER TABLE `headers_scan`
  ADD PRIMARY KEY (`HeadersScan_ID`),
  ADD KEY `ScanID` (`ScanID`);

--
-- Indexes for table `ports_scan`
--
ALTER TABLE `ports_scan`
  ADD PRIMARY KEY (`PortsScan_ID`),
  ADD KEY `ScanID` (`ScanID`);

--
-- Indexes for table `scans`
--
ALTER TABLE `scans`
  ADD PRIMARY KEY (`ScanID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `ssl_scan`
--
ALTER TABLE `ssl_scan`
  ADD PRIMARY KEY (`SSLScan_ID`),
  ADD KEY `ScanID` (`ScanID`);

--
-- Indexes for table `subdomains_scan`
--
ALTER TABLE `subdomains_scan`
  ADD PRIMARY KEY (`SubdomainsScan_ID`),
  ADD KEY `ScanID` (`ScanID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientside_scan`
--
ALTER TABLE `clientside_scan`
  MODIFY `ClientSideScan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `directories_scan`
--
ALTER TABLE `directories_scan`
  MODIFY `DirectoriesScan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `findings_bank`
--
ALTER TABLE `findings_bank`
  MODIFY `FindingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `generalvulnerabilities_scan`
--
ALTER TABLE `generalvulnerabilities_scan`
  MODIFY `GeneralVulnerabilitiesScan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `headers_scan`
--
ALTER TABLE `headers_scan`
  MODIFY `HeadersScan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ports_scan`
--
ALTER TABLE `ports_scan`
  MODIFY `PortsScan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scans`
--
ALTER TABLE `scans`
  MODIFY `ScanID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ssl_scan`
--
ALTER TABLE `ssl_scan`
  MODIFY `SSLScan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subdomains_scan`
--
ALTER TABLE `subdomains_scan`
  MODIFY `SubdomainsScan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clientside_scan`
--
ALTER TABLE `clientside_scan`
  ADD CONSTRAINT `clientside_scan_ibfk_1` FOREIGN KEY (`ScanID`) REFERENCES `scans` (`ScanID`);

--
-- Constraints for table `directories_scan`
--
ALTER TABLE `directories_scan`
  ADD CONSTRAINT `directories_scan_ibfk_1` FOREIGN KEY (`ScanID`) REFERENCES `scans` (`ScanID`);

--
-- Constraints for table `generalvulnerabilities_scan`
--
ALTER TABLE `generalvulnerabilities_scan`
  ADD CONSTRAINT `generalvulnerabilities_scan_ibfk_1` FOREIGN KEY (`ScanID`) REFERENCES `scans` (`ScanID`);

--
-- Constraints for table `headers_scan`
--
ALTER TABLE `headers_scan`
  ADD CONSTRAINT `headers_scan_ibfk_1` FOREIGN KEY (`ScanID`) REFERENCES `scans` (`ScanID`);

--
-- Constraints for table `ports_scan`
--
ALTER TABLE `ports_scan`
  ADD CONSTRAINT `ports_scan_ibfk_1` FOREIGN KEY (`ScanID`) REFERENCES `scans` (`ScanID`);

--
-- Constraints for table `scans`
--
ALTER TABLE `scans`
  ADD CONSTRAINT `scans_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `ssl_scan`
--
ALTER TABLE `ssl_scan`
  ADD CONSTRAINT `ssl_scan_ibfk_1` FOREIGN KEY (`ScanID`) REFERENCES `scans` (`ScanID`);

--
-- Constraints for table `subdomains_scan`
--
ALTER TABLE `subdomains_scan`
  ADD CONSTRAINT `subdomains_scan_ibfk_1` FOREIGN KEY (`ScanID`) REFERENCES `scans` (`ScanID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
