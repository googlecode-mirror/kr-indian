-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2014 at 04:35 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kenrick`
--

-- --------------------------------------------------------

--
-- Table structure for table `phpfox_pages_type`
--

CREATE TABLE IF NOT EXISTS `phpfox_organization_type` (
  `type_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(200) NOT NULL,
  `time_stamp` int(10) unsigned NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  PRIMARY KEY (`type_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `phpfox_pages_type`
--

INSERT INTO `phpfox_organization_type` (`type_id`, `is_active`, `name`, `time_stamp`, `ordering`) VALUES
(1, 1, 'Entertainment', 1408634465, 1),
(2, 1, 'Brand or Product', 1408634465, 2),
(3, 1, 'Group or Community', 1408634465, 3),
(4, 1, 'Local Business or Place', 1408634465, 4),
(5, 1, 'Company, Organization, or Institution', 1408634465, 5),
(6, 1, 'Artist, Band or Public Figure', 1408634465, 6);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
