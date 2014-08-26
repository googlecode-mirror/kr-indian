-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2014 at 04:24 AM
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
-- Table structure for table `phpfox_pages_invite`
--

CREATE TABLE IF NOT EXISTS `phpfox_organization_invite` (
  `invite_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) unsigned NOT NULL,
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `visited_id` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `invited_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `invited_email` varchar(100) DEFAULT NULL,
  `time_stamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`invite_id`),
  KEY `listing_id` (`organization_id`),
  KEY `listing_id_2` (`organization_id`,`invited_user_id`),
  KEY `invited_user_id` (`invited_user_id`),
  KEY `listing_id_3` (`organization_id`,`visited_id`),
  KEY `listing_id_4` (`organization_id`,`visited_id`,`invited_user_id`),
  KEY `visited_id` (`visited_id`,`invited_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
