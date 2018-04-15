-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2018 at 10:13 PM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thesis`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) DEFAULT NULL,
  `account_type_ID` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(72) NOT NULL,
  `status` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `reset_code` varchar(72) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `account_type_ID` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`account_type_ID`, `type`, `description`) VALUES
(1, 'Administrator', ''),
(2, 'Managerial', ''),
(3, 'Employee', '');

-- --------------------------------------------------------

--
-- Table structure for table `additional_contribution`
--

CREATE TABLE `additional_contribution` (
  `additional_contribution_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `contribution_type` int(11) NOT NULL,
  `period_end` date NOT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `date_filed` date NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_ID` int(11) NOT NULL,
  `employee_ID` int(11) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `city` varchar(20) NOT NULL,
  `zip_code` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `allotted_leave`
--

CREATE TABLE `allotted_leave` (
  `allotted_leave_ID` int(11) NOT NULL,
  `position_ID` int(11) NOT NULL,
  `sick_leave` int(11) NOT NULL,
  `vacation_leave` int(11) NOT NULL,
  `emergency_leave` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `allowance`
--

CREATE TABLE `allowance` (
  `allowance_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `transportation` int(11) NOT NULL,
  `gas` int(11) NOT NULL,
  `food` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `in_image` varchar(100) NOT NULL,
  `out_image` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_flexible`
--

CREATE TABLE `attendance_flexible` (
  `attendance_flexible_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `reason` varchar(100) NOT NULL,
  `date_filed` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_ID` int(11) NOT NULL,
  `employee_ID` int(11) NOT NULL,
  `landline` varchar(8) DEFAULT NULL,
  `mobile` varchar(13) DEFAULT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_ID` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_ID` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` varchar(1) NOT NULL,
  `civil_status` varchar(7) NOT NULL,
  `address_ID` int(11) DEFAULT NULL,
  `contact_ID` int(11) DEFAULT NULL,
  `bank_number` varchar(20) NOT NULL,
  `employee_detail_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_detail`
--

CREATE TABLE `employee_detail` (
  `employee_detail_ID` int(11) NOT NULL,
  `employee_ID` int(11) NOT NULL,
  `assigned_ID` int(11) NOT NULL,
  `position_ID` int(11) NOT NULL,
  `department_ID` int(11) NOT NULL,
  `supervisor_ID` int(11) DEFAULT NULL,
  `dependent` int(11) DEFAULT NULL,
  `SSS` varchar(12) NOT NULL,
  `TIN` varchar(15) NOT NULL,
  `PhilHealth` varchar(14) DEFAULT NULL,
  `HDMF` varchar(14) DEFAULT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `date_hired` date NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `account_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave`
--

CREATE TABLE `employee_leave` (
  `employee_leave_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `sick_leave` float NOT NULL,
  `vacation_leave` float NOT NULL,
  `parental_leave` float NOT NULL,
  `emergency_leave` float NOT NULL,
  `accrued` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_taken`
--

CREATE TABLE `employee_leave_taken` (
  `employee_leave_taken_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `leave_type_ID` int(11) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `total_days` float DEFAULT NULL,
  `reason` varchar(100) NOT NULL,
  `date_filed` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `validated_by` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `remark` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_overtime`
--

CREATE TABLE `employee_overtime` (
  `employee_overtime_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `overtime_type_ID` int(11) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `reason` varchar(100) NOT NULL,
  `date_filed` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `validated_by` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `remark` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary`
--

CREATE TABLE `employee_salary` (
  `employee_salary_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `basic_salary` int(11) NOT NULL,
  `allowance_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `holiday_ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE `leave_type` (
  `leave_type_ID` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`leave_type_ID`, `type`, `description`) VALUES
(1, 'Sick Leave', ''),
(2, 'Vacation Leave', ''),
(3, 'Parental Leave', ''),
(4, 'Emergency Leave', ''),
(5, 'Accrued', '');

-- --------------------------------------------------------

--
-- Table structure for table `overtime_type`
--

CREATE TABLE `overtime_type` (
  `overtime_type_ID` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `overtime_type`
--

INSERT INTO `overtime_type` (`overtime_type_ID`, `type`, `description`) VALUES
(1, 'Legal', ''),
(2, 'Sunday', ''),
(3, 'Special Day', ''),
(4, 'Regular Holiday', '');

-- --------------------------------------------------------

--
-- Table structure for table `period`
--

CREATE TABLE `period` (
  `period_ID` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `cutoff` date NOT NULL,
  `previous_cutoff` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `position_ID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`position_ID`, `title`, `description`) VALUES
(1, 'IT Personnel', ''),
(2, 'HR Manager', ''),
(3, 'Finance Head', ''),
(4, 'Payroll Officer', ''),
(5, 'General Administrative Manager', ''),
(6, 'Employee', ''),
(7, 'Supervisor', '');

-- --------------------------------------------------------

--
-- Table structure for table `salary_report`
--

CREATE TABLE `salary_report` (
  `salary_report_ID` int(11) NOT NULL,
  `employee_detail_ID` int(11) NOT NULL,
  `basic_salary` float NOT NULL,
  `transportation` int(11) NOT NULL,
  `gas` int(11) NOT NULL,
  `food` int(11) NOT NULL,
  `SSS` float NOT NULL,
  `HDMF` float NOT NULL,
  `PhilHealth` float NOT NULL,
  `late` float NOT NULL,
  `absent` float NOT NULL,
  `legal` float NOT NULL,
  `sunday` float NOT NULL,
  `sunday_excess` float NOT NULL,
  `special_day` float NOT NULL,
  `special_excess` float NOT NULL,
  `regular_holiday` float NOT NULL,
  `regular_excess` float NOT NULL,
  `bonus` float NOT NULL,
  `withholding_tax` float NOT NULL,
  `period_ID` int(11) NOT NULL,
  `date_issued` datetime NOT NULL,
  `date_received` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salary_report_discrepancy`
--

CREATE TABLE `salary_report_discrepancy` (
  `salary_report_discrepancy_ID` int(11) NOT NULL,
  `salary_report_ID` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `date_filed` date NOT NULL,
  `filed_by` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE `system_log` (
  `system_log_ID` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `account_ID` int(11) NOT NULL,
  `action` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `user_log_id` int(11) NOT NULL,
  `account_ID` int(11) NOT NULL,
  `session_start` datetime NOT NULL,
  `session_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_ID`),
  ADD KEY `account_type_account` (`account_type_ID`),
  ADD KEY `employee_detail_account` (`employee_detail_ID`);

--
-- Indexes for table `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`account_type_ID`);

--
-- Indexes for table `additional_contribution`
--
ALTER TABLE `additional_contribution`
  ADD PRIMARY KEY (`additional_contribution_ID`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_ID`),
  ADD KEY `address_employee` (`employee_ID`);

--
-- Indexes for table `allotted_leave`
--
ALTER TABLE `allotted_leave`
  ADD PRIMARY KEY (`allotted_leave_ID`),
  ADD KEY `position_allotted_leave` (`position_ID`);

--
-- Indexes for table `allowance`
--
ALTER TABLE `allowance`
  ADD PRIMARY KEY (`allowance_ID`),
  ADD KEY `employee_salary_allowance` (`employee_detail_ID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_ID`),
  ADD KEY `attendance_employee_detail` (`employee_detail_ID`);

--
-- Indexes for table `attendance_flexible`
--
ALTER TABLE `attendance_flexible`
  ADD PRIMARY KEY (`attendance_flexible_ID`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_ID`),
  ADD KEY `contact_employee` (`employee_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_ID`);

--
-- Indexes for table `employee_detail`
--
ALTER TABLE `employee_detail`
  ADD PRIMARY KEY (`employee_detail_ID`),
  ADD KEY `department_employee_detail` (`department_ID`),
  ADD KEY `employee_detail_employee` (`employee_ID`),
  ADD KEY `employee_detail_position` (`position_ID`);

--
-- Indexes for table `employee_leave`
--
ALTER TABLE `employee_leave`
  ADD PRIMARY KEY (`employee_leave_ID`),
  ADD KEY `employee_detail_employee_leave` (`employee_detail_ID`),
  ADD KEY `period_employee_leave` (`year`);

--
-- Indexes for table `employee_leave_taken`
--
ALTER TABLE `employee_leave_taken`
  ADD PRIMARY KEY (`employee_leave_taken_ID`),
  ADD KEY `employee_leave_employee_leave_taken` (`employee_detail_ID`),
  ADD KEY `employee_leave_taken_leave_type` (`leave_type_ID`);

--
-- Indexes for table `employee_overtime`
--
ALTER TABLE `employee_overtime`
  ADD PRIMARY KEY (`employee_overtime_ID`),
  ADD KEY `overtime_employee_detail` (`employee_detail_ID`),
  ADD KEY `overtime_type_overtime` (`overtime_type_ID`);

--
-- Indexes for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD PRIMARY KEY (`employee_salary_ID`),
  ADD KEY `employee_salary_employee_detail` (`employee_detail_ID`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`holiday_ID`);

--
-- Indexes for table `leave_type`
--
ALTER TABLE `leave_type`
  ADD PRIMARY KEY (`leave_type_ID`);

--
-- Indexes for table `overtime_type`
--
ALTER TABLE `overtime_type`
  ADD PRIMARY KEY (`overtime_type_ID`);

--
-- Indexes for table `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`period_ID`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`position_ID`);

--
-- Indexes for table `salary_report`
--
ALTER TABLE `salary_report`
  ADD PRIMARY KEY (`salary_report_ID`) USING BTREE,
  ADD KEY `period_salary_report` (`period_ID`),
  ADD KEY `salary_report_employee_detail` (`employee_detail_ID`);

--
-- Indexes for table `salary_report_discrepancy`
--
ALTER TABLE `salary_report_discrepancy`
  ADD PRIMARY KEY (`salary_report_discrepancy_ID`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`system_log_ID`),
  ADD KEY `system_log_account` (`account_ID`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`user_log_id`),
  ADD KEY `user_log_account` (`account_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `account_type`
--
ALTER TABLE `account_type`
  MODIFY `account_type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `additional_contribution`
--
ALTER TABLE `additional_contribution`
  MODIFY `additional_contribution_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `allotted_leave`
--
ALTER TABLE `allotted_leave`
  MODIFY `allotted_leave_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `allowance`
--
ALTER TABLE `allowance`
  MODIFY `allowance_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attendance_flexible`
--
ALTER TABLE `attendance_flexible`
  MODIFY `attendance_flexible_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_detail`
--
ALTER TABLE `employee_detail`
  MODIFY `employee_detail_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_leave`
--
ALTER TABLE `employee_leave`
  MODIFY `employee_leave_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_leave_taken`
--
ALTER TABLE `employee_leave_taken`
  MODIFY `employee_leave_taken_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_overtime`
--
ALTER TABLE `employee_overtime`
  MODIFY `employee_overtime_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_salary`
--
ALTER TABLE `employee_salary`
  MODIFY `employee_salary_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `holiday`
--
ALTER TABLE `holiday`
  MODIFY `holiday_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `leave_type`
--
ALTER TABLE `leave_type`
  MODIFY `leave_type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `overtime_type`
--
ALTER TABLE `overtime_type`
  MODIFY `overtime_type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `period`
--
ALTER TABLE `period`
  MODIFY `period_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `position_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `salary_report`
--
ALTER TABLE `salary_report`
  MODIFY `salary_report_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `salary_report_discrepancy`
--
ALTER TABLE `salary_report_discrepancy`
  MODIFY `salary_report_discrepancy_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `system_log_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `user_log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_type_account` FOREIGN KEY (`account_type_ID`) REFERENCES `account_type` (`account_type_ID`),
  ADD CONSTRAINT `employee_detail_account` FOREIGN KEY (`employee_detail_ID`) REFERENCES `employee_detail` (`employee_detail_ID`);

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_employee` FOREIGN KEY (`employee_ID`) REFERENCES `employee` (`employee_ID`);

--
-- Constraints for table `allotted_leave`
--
ALTER TABLE `allotted_leave`
  ADD CONSTRAINT `position_allotted_leave` FOREIGN KEY (`position_ID`) REFERENCES `position` (`position_ID`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_employee_detail` FOREIGN KEY (`employee_detail_ID`) REFERENCES `employee_detail` (`employee_detail_ID`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_employee` FOREIGN KEY (`employee_ID`) REFERENCES `employee` (`employee_ID`);

--
-- Constraints for table `employee_detail`
--
ALTER TABLE `employee_detail`
  ADD CONSTRAINT `department_employee_detail` FOREIGN KEY (`department_ID`) REFERENCES `department` (`department_ID`),
  ADD CONSTRAINT `employee_detail_employee` FOREIGN KEY (`employee_ID`) REFERENCES `employee` (`employee_ID`),
  ADD CONSTRAINT `employee_detail_position` FOREIGN KEY (`position_ID`) REFERENCES `position` (`position_ID`);

--
-- Constraints for table `employee_leave`
--
ALTER TABLE `employee_leave`
  ADD CONSTRAINT `employee_detail_employee_leave` FOREIGN KEY (`employee_detail_ID`) REFERENCES `employee_detail` (`employee_detail_ID`);

--
-- Constraints for table `employee_leave_taken`
--
ALTER TABLE `employee_leave_taken`
  ADD CONSTRAINT `employee_leave_taken_leave_type` FOREIGN KEY (`leave_type_ID`) REFERENCES `leave_type` (`leave_type_ID`);

--
-- Constraints for table `employee_overtime`
--
ALTER TABLE `employee_overtime`
  ADD CONSTRAINT `overtime_employee_detail` FOREIGN KEY (`employee_detail_ID`) REFERENCES `employee_detail` (`employee_detail_ID`),
  ADD CONSTRAINT `overtime_type_overtime` FOREIGN KEY (`overtime_type_ID`) REFERENCES `overtime_type` (`overtime_type_ID`);

--
-- Constraints for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD CONSTRAINT `employee_salary_employee_detail` FOREIGN KEY (`employee_detail_ID`) REFERENCES `employee_detail` (`employee_detail_ID`);

--
-- Constraints for table `salary_report`
--
ALTER TABLE `salary_report`
  ADD CONSTRAINT `employee_detail_salary_report` FOREIGN KEY (`employee_detail_ID`) REFERENCES `employee_detail` (`employee_detail_ID`),
  ADD CONSTRAINT `period_salary_report` FOREIGN KEY (`period_ID`) REFERENCES `period` (`period_ID`),
  ADD CONSTRAINT `salary_report_employee_detail` FOREIGN KEY (`employee_detail_ID`) REFERENCES `employee_detail` (`employee_detail_ID`);

--
-- Constraints for table `system_log`
--
ALTER TABLE `system_log`
  ADD CONSTRAINT `system_log_account` FOREIGN KEY (`account_ID`) REFERENCES `account` (`account_ID`);

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `user_log_account` FOREIGN KEY (`account_ID`) REFERENCES `account` (`account_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
