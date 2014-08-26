-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2014 at 04:07 AM
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
-- Table structure for table `phpfox_pages`
--

CREATE TABLE IF NOT EXISTS `phpfox_organization` (
  `organization_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL DEFAULT '0',
  `view_id` tinyint(1) NOT NULL DEFAULT '0',
  `type_id` smallint(4) unsigned NOT NULL,
  `category_id` mediumint(8) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `reg_method` tinyint(1) NOT NULL DEFAULT '0',
  `landing_page` varchar(50) DEFAULT NULL,
  `time_stamp` int(10) unsigned DEFAULT NULL,
  `image_path` varchar(75) DEFAULT NULL,
  `image_server_id` tinyint(3) NOT NULL DEFAULT '0',
  `total_like` int(10) unsigned NOT NULL DEFAULT '0',
  `total_dislike` int(10) unsigned NOT NULL DEFAULT '0',
  `total_comment` int(10) unsigned NOT NULL DEFAULT '0',
  `privacy` tinyint(1) NOT NULL DEFAULT '0',
  `designer_style_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `cover_photo_id` int(11) unsigned DEFAULT NULL,
  `cover_photo_position` varchar(4) DEFAULT NULL,
  `location_latitude` decimal(30,27) DEFAULT NULL,
  `location_longitude` decimal(30,27) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `use_timeline` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`organization_id`),
  KEY `category_id` (`category_id`),
  KEY `view_id_2` (`view_id`),
  KEY `app_id` (`app_id`),
  KEY `app_id_2` (`app_id`,`view_id`,`privacy`),
  KEY `app_id_3` (`app_id`,`view_id`,`user_id`),
  KEY `app_id_4` (`app_id`,`view_id`),
  KEY `view_id` (`view_id`,`title`,`privacy`),
  KEY `organization_id` (`organization_id`,`view_id`),
  KEY `latitude` (`location_latitude`,`location_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

