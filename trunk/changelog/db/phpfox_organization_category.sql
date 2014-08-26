-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2014 at 04:15 AM
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
-- Table structure for table `phpfox_pages_category`
--

CREATE TABLE IF NOT EXISTS `phpfox_organization_category` (
  `category_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` smallint(4) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `organization_type` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  KEY `type_id` (`type_id`,`is_active`),
  KEY `category_id` (`category_id`,`is_active`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;

