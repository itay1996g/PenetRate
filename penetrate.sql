-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2020 at 10:01 PM
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
  `Status` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clientside_scan`
--

INSERT INTO `clientside_scan` (`ClientSideScan_ID`, `ScanID`, `Path`, `Status`, `DATE`) VALUES
(1, 10, '', 'Created', '2020-04-09 12:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `ID` int(11) NOT NULL,
  `email` text NOT NULL,
  `message` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`ID`, `email`, `message`, `DATE`) VALUES
(1, 'etaiyaffe379@gmail.com', 'Hello', '2020-04-09 13:22:18'),
(2, 'etaiyaffe379@gmail.com', 'Write Me', '2020-04-09 13:22:30'),
(3, 'etaiyaffe379@gmail.com', 'Please', '2020-04-09 13:31:58'),
(4, 'etaiyaffe379@gmail.com', 'Hello My name is etai', '2020-04-09 13:32:04');

-- --------------------------------------------------------

--
-- Table structure for table `directories_scan`
--

CREATE TABLE `directories_scan` (
  `DirectoriesScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `directories_scan`
--

INSERT INTO `directories_scan` (`DirectoriesScan_ID`, `ScanID`, `Path`, `Status`, `DATE`) VALUES
(1, 10, '', 'Created', '2020-04-09 12:07:28');

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

--
-- Dumping data for table `findings_bank`
--

INSERT INTO `findings_bank` (`FindingID`, `name`, `description`, `recommendation`) VALUES
(1, 'Information Disclosure', 'It is a common practice to conceal internal information about servers and application components from the website users. This is important since users of a website have no need for information regarding the server’s type and version; while this information is relevant to system administrators and developers, it can be used by malicious attackers to uncover security vulnerabilities in the system.\r\n                                            During the security review it was noted that the system divulges information, such as the server’s type, via the HTTP responses headers and the use of Jetty as part of a malformed request.\r\n                                            This information can be used by malicious users in order to infiltrate the system.\r\n                                            Many products have known security flaws in them. Once discovered, the flaws become public and can be used by an attacker. It is also within the attacker’s capabilities to verify whether or not the products used within the website and the web server are the latest versions available. If not, an attacker may read about problematic issues that have been fixed in the latest versions of the products, and deduce that they still exist in the older version used by the application. This would allow the attacker to exploit the vulnerable server and application in a manner which may even allow them to execute code on the remote machine.\r\n', '■ Configure the web server to conceal redundant information regarding its type and version.\r\n                                            ■ Design custom error pages and do not use the templates provided by the web application server, that allow to gather information about the type of the server and the technologies in use.\r\n                                            ■ Define and apply security configuration standards and maintenance procedures for any and all platform components. The standard procedures should ensure that server and platform components are configured according to security best practices and that software security updates are installed regularly.'),
(2, 'Information Disclosure', 'It is a common practice to conceal internal information about servers and application components from the website users. This is important since users of a website have no need for information regarding the server’s type and version; while this information is relevant to system administrators and developers, it can be used by malicious attackers to uncover security vulnerabilities in the system.\r\n                                            During the security review it was noted that the system divulges information, such as the server’s type, via the HTTP responses headers and the use of Jetty as part of a malformed request.\r\n                                            This information can be used by malicious users in order to infiltrate the system.\r\n                                            Many products have known security flaws in them. Once discovered, the flaws become public and can be used by an attacker. It is also within the attacker’s capabilities to verify whether or not the products used within the website and the web server are the latest versions available. If not, an attacker may read about problematic issues that have been fixed in the latest versions of the products, and deduce that they still exist in the older version used by the application. This would allow the attacker to exploit the vulnerable server and application in a manner which may even allow them to execute code on the remote machine.\r\n', '■ Configure the web server to conceal redundant information regarding its type and version.\r\n                                            ■ Design custom error pages and do not use the templates provided by the web application server, that allow to gather information about the type of the server and the technologies in use.\r\n                                            ■ Define and apply security configuration standards and maintenance procedures for any and all platform components. The standard procedures should ensure that server and platform components are configured according to security best practices and that software security updates are installed regularly.');

-- --------------------------------------------------------

--
-- Table structure for table `generalvulnerabilities_scan`
--

CREATE TABLE `generalvulnerabilities_scan` (
  `GeneralVulnerabilitiesScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `generalvulnerabilities_scan`
--

INSERT INTO `generalvulnerabilities_scan` (`GeneralVulnerabilitiesScan_ID`, `ScanID`, `Path`, `Status`, `DATE`) VALUES
(1, 10, '', 'Created', '2020-04-09 12:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `headers_scan`
--

CREATE TABLE `headers_scan` (
  `HeadersScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `headers_scan`
--

INSERT INTO `headers_scan` (`HeadersScan_ID`, `ScanID`, `Path`, `Status`, `DATE`) VALUES
(1, 10, '', 'Created', '2020-04-09 12:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `ports_scan`
--

CREATE TABLE `ports_scan` (
  `PortsScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ports_scan`
--

INSERT INTO `ports_scan` (`PortsScan_ID`, `ScanID`, `Path`, `Status`, `DATE`) VALUES
(2, 10, '', 'Created', '2020-04-09 12:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `scans`
--

CREATE TABLE `scans` (
  `ScanID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `URL` text NOT NULL,
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
  `Status` text NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `scans`
--

INSERT INTO `scans` (`ScanID`, `UserID`, `URL`, `Login_URL`, `username`, `password`, `Is_PortsScan`, `Is_SSLScan`, `Is_SubdomainsScan`, `Is_DirectoriesScan`, `Is_HeadersScan`, `Is_ClientSideScan`, `Is_GeneralVulnerabilitiesScan`, `Status`, `Date`) VALUES
(10, 6546, 'http://ins-isi.com/', 'Login URL', 'Username', 'Password', 1, 1, 1, 1, 1, 1, 1, 'Created', '2020-04-09 12:07:28'),
(11, 6546, 'http://ins-isi.com/', 'Login URL', 'Username', 'Password', 0, 0, 0, 0, 0, 0, 0, 'Created', '2020-04-09 12:15:08'),
(12, 6546, 'http://ins-isi.com/', 'Login URL', 'Username', 'Password', 0, 0, 0, 0, 0, 0, 0, 'Created', '2020-04-09 12:15:32'),
(13, 6546, 'http://ins-isi.com/', 'Login URL', 'Username', 'Password', 0, 0, 0, 0, 0, 0, 0, 'Finished', '2020-04-09 12:15:37');

-- --------------------------------------------------------

--
-- Table structure for table `ssl_scan`
--

CREATE TABLE `ssl_scan` (
  `SSLScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ssl_scan`
--

INSERT INTO `ssl_scan` (`SSLScan_ID`, `ScanID`, `Path`, `Status`, `DATE`) VALUES
(1, 10, '', 'Created', '2020-04-09 12:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `subdomains_scan`
--

CREATE TABLE `subdomains_scan` (
  `SubdomainsScan_ID` int(11) NOT NULL,
  `ScanID` int(11) NOT NULL,
  `Path` text NOT NULL,
  `Status` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subdomains_scan`
--

INSERT INTO `subdomains_scan` (`SubdomainsScan_ID`, `ScanID`, `Path`, `Status`, `DATE`) VALUES
(1, 10, '', 'Created', '2020-04-09 12:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Fullname` text NOT NULL,
  `Phone` text NOT NULL,
  `Position` text NOT NULL,
  `UserRole` text NOT NULL,
  `Email` text NOT NULL,
  `UserPassword` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Fullname`, `Phone`, `Position`, `UserRole`, `Email`, `UserPassword`) VALUES
(6546, 'ETAI', '0524704053', 'Freelancer', 'Admin', 'etaiyaffe379@gmail.com', 'Aa123456');

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
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`ID`);

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
  MODIFY `ClientSideScan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `directories_scan`
--
ALTER TABLE `directories_scan`
  MODIFY `DirectoriesScan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `findings_bank`
--
ALTER TABLE `findings_bank`
  MODIFY `FindingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `generalvulnerabilities_scan`
--
ALTER TABLE `generalvulnerabilities_scan`
  MODIFY `GeneralVulnerabilitiesScan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `headers_scan`
--
ALTER TABLE `headers_scan`
  MODIFY `HeadersScan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ports_scan`
--
ALTER TABLE `ports_scan`
  MODIFY `PortsScan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scans`
--
ALTER TABLE `scans`
  MODIFY `ScanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ssl_scan`
--
ALTER TABLE `ssl_scan`
  MODIFY `SSLScan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subdomains_scan`
--
ALTER TABLE `subdomains_scan`
  MODIFY `SubdomainsScan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6551;

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
