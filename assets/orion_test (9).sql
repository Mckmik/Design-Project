-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2018 at 06:28 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orion_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `csv_dates`
--

CREATE TABLE `csv_dates` (
  `entity` varchar(50) NOT NULL,
  `last_create` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `csv_dates`
--

INSERT INTO `csv_dates` (`entity`, `last_create`, `time`) VALUES
('customer', '2018-05-02', '09:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `street` varchar(25) NOT NULL,
  `city` varchar(25) NOT NULL,
  `state` varchar(2) NOT NULL DEFAULT 'MO',
  `zip` int(5) NOT NULL,
  `company_name` varchar(25) NOT NULL,
  `cust_email` varchar(50) NOT NULL,
  `cust_phone` varchar(12) NOT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL,
  `is_deleted` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cust_id`, `last_name`, `first_name`, `street`, `city`, `state`, `zip`, `company_name`, `cust_email`, `cust_phone`, `date_created`, `time_created`, `is_deleted`) VALUES
(1, 'Brown', 'Jan', '54 Walnut St', 'Chesterfield', 'MO', 63005, '', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'no'),
(2, 'McKeever', 'Michael', '727 Goddard Ave', 'Chesterfield', 'MO', 63005, 'Waterway Gas & Wash', 'mpmckeever914@gmail.com', '636-614-8790', '2018-04-21', '00:00:00', 'no'),
(3, 'Martin', 'Dave', '155 N Jefferson St', 'St. Louis', 'MO', 63125, 'Dave\'s General Store', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'no'),
(4, 'Henson', 'Bob', '63 Market St', 'St. Louis', 'MO', 63125, 'Bob\'s Pizza', 'teostest6@gmail.com', '787-956-2121', '2018-04-21', '00:00:00', 'no'),
(5, 'Shop N Save', 'Shop N Save', '225 W Clay St', 'St. Charles', 'MO', 63376, 'Shop N Save', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'no'),
(13, 'Fisher', 'Jack', '897 Olive Blvd', 'Chesterfield', 'MO', 63005, 'Fisher Auto', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'no'),
(14, 'Samson', 'Robert', '544 Jefferson St', 'St. Louis', 'MO', 63125, '', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'no'),
(21, 'Smith', 'Will', '642 Smith St', 'Los Angeles', 'CA', 90210, '', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'yes'),
(22, 'Schmoe', 'Joe', '522 Cardinal St', 'St. Louis', 'MO', 63125, 'Joe\'s Bar', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'no'),
(24, 'Tannehill', 'Andrew', '178 Old Oak Rd', 'St. Louis', 'MO', 63125, '', 'teostest6@gmail.com', '555-555-5555', '2018-04-21', '00:00:00', 'no'),
(25, 'Tarley', 'Sam', '123 Cold Rd', 'St. Louis', 'MO', 63366, '', 'teostest6@gmail.com', '555-555-5555', '2018-05-02', '09:25:00', 'no'),
(29, 'Caster', 'Kevin', '787 First Capital', 'St. Charles', 'MO', 63301, '', 'teostest6@gmail.com', '555-555-5555', '2018-05-02', '09:33:06', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `job_name` varchar(25) NOT NULL,
  `quoted_hours` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `complete_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `cust_id`, `job_name`, `quoted_hours`, `start_date`, `complete_date`) VALUES
(1, 4, '1st Fl Rough', '400.00', '2018-03-01', '2018-04-13'),
(2, 1, '3rd Fl Rough', '250.00', '2018-04-04', '2018-04-19'),
(3, 4, '2nd Fl Rough', '200.00', '2018-05-01', '0000-00-00'),
(4, 25, '2nd Fl Rough', '450.00', '2018-04-07', '2018-04-30'),
(5, 3, '2nd Fl Rough', '600.00', '2018-04-28', '0000-00-00'),
(6, 4, 'Deck Rough', '80.00', '2018-04-16', '2018-04-18');

-- --------------------------------------------------------

--
-- Table structure for table `timesheets`
--

CREATE TABLE `timesheets` (
  `emp_id` int(11) NOT NULL,
  `timesheet_id` int(11) NOT NULL,
  `week_start` date NOT NULL,
  `week_end` date NOT NULL,
  `authorization` text NOT NULL,
  `is_submitted` varchar(3) NOT NULL,
  `is_complete` varchar(3) NOT NULL,
  `per_diem` int(11) NOT NULL,
  `injury` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timesheets`
--

INSERT INTO `timesheets` (`emp_id`, `timesheet_id`, `week_start`, `week_end`, `authorization`, `is_submitted`, `is_complete`, `per_diem`, `injury`) VALUES
(1, 1, '2018-03-17', '2018-03-30', 'assets/images/signatures/e1ts1.png', 'yes', 'yes', 0, 'no'),
(1, 2, '2018-03-31', '2018-04-13', 'assets/images/signatures/e1ts2.png', 'yes', 'yes', 0, 'no'),
(1, 3, '2018-04-14', '2018-04-27', 'assets/images/signatures/e1ts3.png', 'yes', 'yes', 0, 'no'),
(1, 4, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(2, 1, '2018-03-31', '2018-04-13', 'assets/images/signatures/e2ts1.png', 'yes', 'yes', 0, 'no'),
(2, 2, '2018-04-14', '2018-04-27', 'assets/images/signatures/e2ts2.png', 'yes', 'yes', 0, 'no'),
(2, 3, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(3, 1, '2018-03-31', '2018-04-13', 'assets/images/signatures/e3ts1.png', 'yes', 'yes', 0, 'no'),
(3, 2, '2018-04-14', '2018-04-27', 'assets/images/signatures/e3ts2.png', 'yes', 'yes', 0, 'no'),
(3, 3, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(4, 1, '2018-03-31', '2018-04-13', 'assets/images/signatures/e4ts1.png', 'yes', 'yes', 4, 'no'),
(4, 2, '2018-04-14', '2018-04-27', 'assets/images/signatures/e4ts2.png', 'yes', 'yes', 0, 'no'),
(4, 3, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(5, 1, '2018-03-31', '2018-04-13', 'assets/images/signatures/e5ts1.png', 'yes', 'yes', 0, 'no'),
(5, 2, '2018-04-14', '2018-04-27', 'assets/images/signatures/e5ts2.png', 'yes', 'yes', 0, 'no'),
(5, 3, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(7, 1, '2018-03-31', '2018-04-13', 'assets/images/signatures/e7ts1.png', 'yes', 'yes', 4, 'no'),
(7, 2, '2018-04-14', '2018-04-27', 'assets/images/signatures/e7ts2.png', 'yes', 'no', 0, 'no'),
(7, 3, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(8, 1, '2018-03-31', '2018-04-13', 'assets/images/signatures/e8ts1.png', 'yes', 'yes', 0, 'no'),
(8, 2, '2018-04-14', '2018-04-27', 'assets/images/signatures/e8ts2.png', 'yes', 'no', 0, 'no'),
(8, 3, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(14, 1, '2018-04-14', '2018-04-27', '', 'no', 'no', 0, 'no'),
(14, 2, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(15, 1, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no'),
(16, 1, '2018-04-28', '2018-05-11', '', 'no', 'no', 0, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_lines`
--

CREATE TABLE `timesheet_lines` (
  `emp_id` int(11) NOT NULL,
  `timesheet_id` int(11) NOT NULL,
  `tl_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `wo_id` int(11) NOT NULL,
  `date_worked` date NOT NULL,
  `arrival` time NOT NULL,
  `departure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timesheet_lines`
--

INSERT INTO `timesheet_lines` (`emp_id`, `timesheet_id`, `tl_id`, `job_id`, `wo_id`, `date_worked`, `arrival`, `departure`) VALUES
(1, 1, 1, 1, 0, '2018-03-17', '13:30:00', '16:30:00'),
(1, 1, 2, 1, 0, '2018-03-19', '07:00:00', '15:15:00'),
(1, 1, 3, 1, 0, '2018-03-20', '08:00:00', '16:00:00'),
(1, 1, 4, 1, 0, '2018-03-21', '08:00:00', '16:00:00'),
(1, 1, 5, 1, 0, '2018-03-22', '08:00:00', '16:00:00'),
(1, 1, 6, 1, 0, '2018-03-26', '07:00:00', '15:00:00'),
(1, 1, 7, 1, 0, '2018-03-27', '07:00:00', '15:30:00'),
(1, 1, 8, 1, 0, '2018-03-28', '08:00:00', '15:30:00'),
(1, 1, 9, 1, 0, '2018-03-29', '08:00:00', '16:15:00'),
(1, 1, 10, 1, 0, '2018-03-30', '10:45:00', '15:15:00'),
(1, 1, 11, 1, 0, '2018-03-23', '08:00:00', '16:00:00'),
(1, 1, 12, 1, 0, '2018-03-17', '08:00:00', '13:30:00'),
(1, 2, 1, 2, 0, '2018-04-11', '08:00:00', '15:00:00'),
(1, 2, 2, 2, 0, '2018-04-12', '07:00:00', '15:30:00'),
(1, 2, 3, 2, 0, '2018-04-04', '07:00:00', '15:30:00'),
(1, 2, 4, 2, 0, '2018-04-05', '07:00:00', '15:30:00'),
(1, 2, 5, 2, 0, '2018-04-06', '07:00:00', '15:30:00'),
(1, 2, 6, 1, 0, '2018-04-02', '07:00:00', '15:30:00'),
(1, 2, 7, 1, 0, '2018-04-03', '07:00:00', '15:30:00'),
(1, 2, 8, 2, 0, '2018-04-09', '07:00:00', '15:30:00'),
(1, 2, 9, 2, 0, '2018-04-10', '07:00:00', '15:30:00'),
(1, 2, 10, 2, 0, '2018-04-13', '07:00:00', '15:30:00'),
(1, 3, 1, 0, 32, '2018-04-16', '10:00:00', '13:00:00'),
(1, 3, 2, 6, 0, '2018-04-16', '07:00:00', '10:00:00'),
(1, 3, 3, 6, 0, '2018-04-16', '13:00:00', '15:30:00'),
(1, 3, 4, 6, 0, '2018-04-17', '07:00:00', '15:30:00'),
(1, 3, 5, 6, 0, '2018-04-18', '07:00:00', '15:30:00'),
(1, 3, 6, 4, 0, '2018-04-23', '07:00:00', '15:30:00'),
(1, 3, 7, 4, 0, '2018-04-24', '07:00:00', '15:30:00'),
(1, 3, 8, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(1, 3, 9, 4, 0, '2018-04-26', '07:00:00', '15:30:00'),
(1, 3, 10, 4, 0, '2018-04-27', '07:00:00', '15:30:00'),
(1, 3, 11, 4, 0, '2018-04-19', '07:00:00', '15:30:00'),
(1, 3, 12, 4, 0, '2018-04-20', '07:00:00', '15:30:00'),
(2, 1, 1, 0, 17, '2018-04-07', '10:00:00', '12:00:00'),
(2, 1, 2, 1, 0, '2018-04-02', '07:00:00', '15:30:00'),
(2, 1, 3, 1, 0, '2018-04-03', '07:00:00', '15:30:00'),
(2, 1, 4, 1, 0, '2018-04-04', '07:00:00', '15:30:00'),
(2, 1, 5, 1, 0, '2018-04-09', '07:00:00', '15:30:00'),
(2, 1, 6, 1, 0, '2018-04-10', '07:00:00', '15:30:00'),
(2, 1, 7, 1, 0, '2018-04-11', '07:00:00', '15:30:00'),
(2, 2, 1, 4, 0, '2018-04-16', '07:00:00', '15:30:00'),
(2, 2, 2, 4, 0, '2018-04-17', '07:00:00', '15:30:00'),
(2, 2, 3, 4, 0, '2018-04-18', '07:00:00', '15:30:00'),
(2, 2, 4, 4, 0, '2018-04-19', '07:00:00', '15:30:00'),
(2, 2, 5, 4, 0, '2018-04-20', '07:00:00', '15:30:00'),
(2, 2, 6, 4, 0, '2018-04-23', '07:00:00', '15:30:00'),
(2, 2, 7, 4, 0, '2018-04-24', '07:00:00', '15:30:00'),
(2, 2, 8, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(2, 2, 9, 4, 0, '2018-04-26', '07:00:00', '15:30:00'),
(2, 2, 10, 4, 0, '2018-04-27', '07:00:00', '15:30:00'),
(2, 3, 1, 0, 33, '2018-05-02', '09:30:00', '13:30:00'),
(3, 1, 1, 1, 0, '2018-04-05', '07:00:00', '15:30:00'),
(3, 1, 2, 1, 0, '2018-04-06', '07:00:00', '15:30:00'),
(3, 1, 3, 1, 0, '2018-04-11', '07:00:00', '15:30:00'),
(3, 1, 4, 1, 0, '2018-04-12', '07:00:00', '15:30:00'),
(3, 1, 5, 1, 0, '2018-04-13', '07:00:00', '15:30:00'),
(3, 1, 6, 1, 0, '2018-04-04', '07:00:00', '15:30:00'),
(3, 2, 1, 6, 0, '2018-04-16', '07:00:00', '15:30:00'),
(3, 2, 2, 6, 0, '2018-04-17', '07:00:00', '12:30:00'),
(3, 2, 3, 2, 0, '2018-04-19', '07:00:00', '15:30:00'),
(3, 2, 4, 4, 0, '2018-04-23', '07:00:00', '15:30:00'),
(3, 2, 5, 4, 0, '2018-04-24', '07:00:00', '15:30:00'),
(3, 2, 6, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(4, 1, 1, 2, 0, '2018-04-04', '07:00:00', '15:30:00'),
(4, 1, 2, 2, 0, '2018-04-05', '07:00:00', '15:30:00'),
(4, 1, 3, 2, 0, '2018-04-06', '07:00:00', '15:30:00'),
(4, 1, 4, 4, 0, '2018-04-09', '07:00:00', '15:30:00'),
(4, 1, 5, 4, 0, '2018-04-10', '07:00:00', '15:30:00'),
(4, 1, 6, 4, 0, '2018-04-11', '07:00:00', '15:30:00'),
(4, 1, 7, 4, 0, '2018-04-12', '07:00:00', '15:30:00'),
(4, 1, 8, 4, 0, '2018-04-13', '07:00:00', '15:30:00'),
(4, 2, 1, 6, 0, '2018-04-16', '07:00:00', '15:30:00'),
(4, 2, 2, 6, 0, '2018-04-17', '07:00:00', '15:30:00'),
(4, 2, 3, 6, 0, '2018-04-18', '07:00:00', '15:30:00'),
(4, 2, 4, 4, 0, '2018-04-23', '07:00:00', '15:30:00'),
(4, 2, 5, 4, 0, '2018-04-24', '07:00:00', '15:30:00'),
(4, 2, 6, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(4, 2, 7, 4, 0, '2018-04-26', '07:00:00', '15:30:00'),
(4, 2, 8, 4, 0, '2018-04-27', '07:00:00', '15:30:00'),
(5, 1, 1, 1, 0, '2018-04-02', '07:00:00', '15:30:00'),
(5, 1, 2, 1, 0, '2018-04-03', '07:00:00', '15:30:00'),
(5, 1, 3, 1, 0, '2018-04-04', '07:00:00', '15:30:00'),
(5, 1, 4, 1, 0, '2018-04-05', '07:00:00', '15:30:00'),
(5, 1, 5, 1, 0, '2018-04-06', '07:00:00', '15:30:00'),
(5, 1, 6, 1, 0, '2018-04-09', '07:00:00', '15:30:00'),
(5, 1, 7, 1, 0, '2018-04-10', '07:00:00', '15:30:00'),
(5, 1, 8, 1, 0, '2018-04-11', '07:00:00', '15:30:00'),
(5, 1, 9, 1, 0, '2018-04-12', '07:00:00', '15:30:00'),
(5, 1, 10, 1, 0, '2018-04-13', '07:00:00', '15:30:00'),
(5, 2, 1, 6, 0, '2018-04-16', '07:00:00', '15:30:00'),
(5, 2, 2, 6, 0, '2018-04-17', '07:00:00', '15:30:00'),
(5, 2, 3, 6, 0, '2018-04-18', '07:00:00', '15:30:00'),
(5, 2, 4, 4, 0, '2018-04-20', '07:00:00', '15:30:00'),
(5, 2, 5, 4, 0, '2018-04-23', '07:00:00', '15:30:00'),
(5, 2, 6, 4, 0, '2018-04-24', '07:00:00', '15:30:00'),
(5, 2, 7, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(5, 2, 8, 4, 0, '2018-04-26', '07:00:00', '15:30:00'),
(5, 2, 9, 4, 0, '2018-04-27', '07:00:00', '15:30:00'),
(7, 1, 1, 4, 0, '2018-04-11', '07:30:00', '15:30:00'),
(7, 1, 2, 4, 0, '2018-04-09', '07:00:00', '15:30:00'),
(7, 1, 3, 4, 0, '2018-04-10', '07:00:00', '15:30:00'),
(7, 1, 4, 4, 0, '2018-04-12', '07:00:00', '15:30:00'),
(7, 1, 5, 4, 0, '2018-04-13', '07:00:00', '15:30:00'),
(7, 1, 6, 1, 0, '2018-04-02', '07:00:00', '15:30:00'),
(7, 1, 7, 1, 0, '2018-04-03', '07:00:00', '15:30:00'),
(7, 1, 8, 1, 0, '2018-04-04', '07:00:00', '15:30:00'),
(7, 1, 9, 1, 0, '2018-04-05', '07:00:00', '15:30:00'),
(7, 1, 10, 1, 0, '2018-04-06', '07:00:00', '15:30:00'),
(7, 2, 1, 2, 0, '2018-04-16', '07:00:00', '15:30:00'),
(7, 2, 2, 2, 0, '2018-04-17', '07:00:00', '15:30:00'),
(7, 2, 3, 2, 0, '2018-04-18', '07:00:00', '15:30:00'),
(7, 2, 4, 2, 0, '2018-04-19', '07:00:00', '15:30:00'),
(7, 2, 5, 2, 0, '2018-04-14', '07:00:00', '15:30:00'),
(7, 2, 6, 4, 0, '2018-04-23', '07:00:00', '15:30:00'),
(7, 2, 7, 4, 0, '2018-04-24', '07:00:00', '15:30:00'),
(7, 2, 8, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(7, 2, 9, 4, 0, '2018-04-26', '07:00:00', '15:30:00'),
(7, 2, 10, 4, 0, '2018-04-27', '07:00:00', '15:30:00'),
(8, 1, 1, 1, 0, '2018-04-10', '08:00:00', '16:00:00'),
(8, 1, 2, 1, 0, '2018-04-02', '07:00:00', '15:00:00'),
(8, 1, 3, 1, 0, '2018-04-03', '07:00:00', '15:30:00'),
(8, 1, 4, 1, 0, '2018-04-09', '07:00:00', '15:30:00'),
(8, 2, 1, 2, 0, '2018-04-16', '07:00:00', '15:30:00'),
(8, 2, 2, 2, 0, '2018-04-17', '07:00:00', '15:30:00'),
(8, 2, 3, 2, 0, '2018-04-18', '07:00:00', '15:30:00'),
(8, 2, 4, 2, 0, '2018-04-19', '07:00:00', '15:30:00'),
(8, 2, 5, 2, 0, '2018-04-14', '07:00:00', '15:30:00'),
(8, 2, 6, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(8, 2, 7, 4, 0, '2018-04-26', '07:00:00', '15:30:00'),
(8, 2, 8, 4, 0, '2018-04-27', '07:00:00', '15:30:00'),
(14, 1, 1, 2, 0, '2018-04-16', '07:00:00', '15:30:00'),
(14, 1, 2, 4, 0, '2018-04-24', '07:00:00', '15:30:00'),
(14, 1, 3, 2, 0, '2018-04-17', '07:00:00', '15:30:00'),
(14, 1, 4, 2, 0, '2018-04-19', '07:00:00', '15:30:00'),
(14, 1, 5, 2, 0, '2018-04-18', '07:00:00', '15:30:00'),
(14, 1, 6, 4, 0, '2018-04-23', '07:00:00', '15:30:00'),
(14, 1, 7, 4, 0, '2018-04-25', '07:00:00', '15:30:00'),
(14, 1, 8, 4, 0, '2018-04-26', '07:00:00', '15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `emp_num` int(11) NOT NULL,
  `is_tech` varchar(3) NOT NULL,
  `is_admin` varchar(3) NOT NULL,
  `user_phone` varchar(12) NOT NULL,
  `is_deleted` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `username`, `password`, `emp_num`, `is_tech`, `is_admin`, `user_phone`, `is_deleted`) VALUES
(1, 'Michael', 'McKeever', 'mpmxw6@mail.umsl.edu', 'mmckeever', 'deadcff17829eb6ec6835d27f88564c3', 1, 'yes', 'yes', '636-614-8790', 'no'),
(2, 'Nick', 'Armocida', 'nwad45@mail.umsl.edu', 'narmocida', 'deadcff17829eb6ec6835d27f88564c3', 2, 'yes', 'yes', '555-555-5555', 'no'),
(3, 'Dan', 'Ganter', 'dwg3n3@mail.umsl.edu', 'dganter', 'deadcff17829eb6ec6835d27f88564c3', 3, 'yes', 'yes', '555-555-5555', 'no'),
(4, 'Jeremiah', 'Washington', 'jerwash22@gmail.com', 'jwashington', 'deadcff17829eb6ec6835d27f88564c3', 4, 'yes', 'yes', '555-555-5555', 'no'),
(5, 'Regina', 'Raven', 'rlr8b4@mail.umsl.edu', 'rraven', 'deadcff17829eb6ec6835d27f88564c3', 5, 'yes', 'yes', '555-555-5555', 'no'),
(6, 'John', 'Doe', 'teostest6@gmail.com', 'doej', 'deadcff17829eb6ec6835d27f88564c3', 656, 'no', 'no', '555-555-5555', 'yes'),
(7, 'Bob', 'Thompson', 'teostest6@gmail.com', 'thompsonb', 'deadcff17829eb6ec6835d27f88564c3', 544, 'no', 'no', '555-555-5555', 'no'),
(8, 'Curt', 'Ochsner', 'teostest6@gmail.com', 'cosh', 'deadcff17829eb6ec6835d27f88564c3', 789, 'yes', 'no', '555-555-5555', 'no'),
(9, 'John', 'McCarthy', 'teostest6@gmail.com', 'jmac', 'deadcff17829eb6ec6835d27f88564c3', 8968, 'yes', 'no', '555-555-5555', 'yes'),
(10, 'Greg', 'Johnson', 'teostest6@gmail.com', 'gjohnson', 'f452c27889c3186dfe09ca08b376ef36', 3333, 'yes', 'yes', '555-555-5555', 'yes'),
(14, 'Joseph', 'Rottman', 'rottman@umsl.edu', 'jrottman', '9a2d581fd0c9a29a25236f5b63725ff3', 1111, 'yes', 'yes', '555-555-5555', 'yes'),
(15, 'Joseph', 'RottmanTech', 'rottmanj@umsl.edu', 'jrottman2', 'd2b4c79fa13668ed59ef792207667b14', 1112, 'yes', 'no', '555-555-5555', 'yes'),
(16, 'Joseph', 'RottmanBasic', 'rottmanj@msx.umsl.edu', 'jrottman3', 'a165b650025c32f974e838fc7783e258', 1113, 'no', 'no', '555-555-5555', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `user_salts`
--

CREATE TABLE `user_salts` (
  `user_id` int(11) NOT NULL,
  `salt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_salts`
--

INSERT INTO `user_salts` (`user_id`, `salt`) VALUES
(0, 2682594),
(1, 9999999),
(2, 9999999),
(3, 9999999),
(4, 9999999),
(5, 9999999),
(6, 9999999),
(7, 9999999),
(8, 9999999),
(9, 9999999),
(10, 5895847),
(14, 9657708),
(15, 1285794),
(16, 6527411);

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `wo_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `tech_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `service_time` time NOT NULL,
  `request_body` text NOT NULL,
  `work_description` text NOT NULL,
  `authorization` text NOT NULL,
  `is_complete` varchar(3) NOT NULL,
  `is_deleted` varchar(3) NOT NULL,
  `complete_date` date NOT NULL,
  `job_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `work_orders`
--

INSERT INTO `work_orders` (`wo_id`, `cust_id`, `tech_id`, `service_date`, `service_time`, `request_body`, `work_description`, `authorization`, `is_complete`, `is_deleted`, `complete_date`, `job_address`) VALUES
(17, 21, 2, '2018-04-07', '10:00:00', 'Fix kitchen lights', 'Installed new lights in kitchen', 'assets/pdfs/work_orders/wo17.pdf', 'yes', 'no', '2018-04-07', 'Same as billing'),
(32, 3, 1, '2018-04-16', '10:00:00', 'Routine Inspection', 'Performed inspection, everything looks okay', 'assets/pdfs/work_orders/wo32.pdf', 'yes', 'no', '2018-04-16', 'Same as billing'),
(33, 24, 2, '2018-05-02', '10:00:00', 'Check faulty wiring', 'Checked faulty wiring, replacement was necessary', 'assets/pdfs/work_orders/wo33.pdf', 'yes', 'no', '2018-05-02', 'Same as billing'),
(34, 13, 2, '2018-05-14', '10:00:00', 'Routine Inspection', '', '', 'no', 'no', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `work_order_lines`
--

CREATE TABLE `work_order_lines` (
  `wo_id` int(11) NOT NULL,
  `ol_id` int(11) NOT NULL,
  `type` varchar(8) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `material` varchar(50) NOT NULL,
  `cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `work_order_lines`
--

INSERT INTO `work_order_lines` (`wo_id`, `ol_id`, `type`, `quantity`, `material`, `cost`) VALUES
(17, 1, 'Material', '2.00', 'Fluorescent lights', '5.25'),
(17, 2, 'Labor', '2.00', 'installation', '70.00'),
(32, 1, 'Labor', '2.50', 'Inspection', '50.00'),
(33, 1, 'Material', '1.00', '100ft wiring', '20.00'),
(33, 2, 'Labor', '3.50', 'Replaced faulty wiring', '55.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `csv_dates`
--
ALTER TABLE `csv_dates`
  ADD PRIMARY KEY (`entity`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `timesheets`
--
ALTER TABLE `timesheets`
  ADD PRIMARY KEY (`emp_id`,`timesheet_id`);

--
-- Indexes for table `timesheet_lines`
--
ALTER TABLE `timesheet_lines`
  ADD PRIMARY KEY (`emp_id`,`timesheet_id`,`tl_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `emp_num` (`emp_num`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `last_name` (`last_name`);

--
-- Indexes for table `user_salts`
--
ALTER TABLE `user_salts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`wo_id`);

--
-- Indexes for table `work_order_lines`
--
ALTER TABLE `work_order_lines`
  ADD PRIMARY KEY (`wo_id`,`ol_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `wo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
