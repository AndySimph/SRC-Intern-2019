-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 02, 2019 at 02:47 PM
-- Server version: 5.1.56
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `andy_dept`
--

CREATE TABLE IF NOT EXISTS `andy_dept` (
  `deptNum` varchar(3) NOT NULL,
  `deptName` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `andy_dept`
--

INSERT INTO `andy_dept` (`deptNum`, `deptName`) VALUES
('03', 'Cranks and Cams'),
('17', 'Whole Good Disassembly'),
('04', 'Engine Assembly'),
('06', 'Fuel Pumps'),
('08', 'Dyno, Paint and CAT'),
('09', 'Cylinder Heads'),
('10', 'Crankcases'),
('11', 'Connecting Rods'),
('13', 'Nozzles'),
('18', 'Whole Good Assembly'),
('12', 'Turbo Chargers');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
