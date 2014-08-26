-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2014 at 04:33 AM
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
-- Table structure for table `phpfox_pages_text`
--

CREATE TABLE IF NOT EXISTS `phpfox_organization_text` (
  `organization_id` int(10) unsigned NOT NULL,
  `text` mediumtext,
  `text_parsed` mediumtext,
  UNIQUE KEY `organization_id` (`organization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpfox_pages_text`
--

INSERT INTO `phpfox_pages_text` (`page_id`, `text`, `text_parsed`) VALUES
(1, '', ''),
(2, 'Zing Ta', 'Zing Ta'),
(3, 'Dame', 'Dame');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
