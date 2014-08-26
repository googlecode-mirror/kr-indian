-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2014 at 04:28 AM
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
-- Table structure for table `phpfox_pages_perm`
--

CREATE TABLE IF NOT EXISTS `phpfox_organization_perm` (
  `organization_id` int(10) unsigned NOT NULL,
  `var_name` varchar(150) NOT NULL,
  `var_value` tinyint(1) NOT NULL DEFAULT '0',
  KEY `organization_id` (`organization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpfox_pages_perm`
--

INSERT INTO `phpfox_organization_perm` (`organization_id`, `var_name`, `var_value`) VALUES
(1, 'blog.share_blogs', 0),
(1, 'blog.view_browse_blogs', 0),
(1, 'event.share_events', 0),
(1, 'event.view_browse_events', 0),
(1, 'forum.share_forum', 0),
(1, 'forum.view_browse_forum', 0),
(1, 'link.share_links', 0),
(1, 'link.view_browse_links', 0),
(1, 'music.share_music', 0),
(1, 'music.view_browse_music', 0),
(1, 'organization.share_updates', 0),
(1, 'organization.view_browse_updates', 0),
(1, 'organization.view_browse_widgets', 0),
(1, 'photo.share_photos', 0),
(1, 'photo.view_browse_photos', 0),
(1, 'shoutbox.view_post_shoutbox', 0),
(1, 'video.share_videos', 0),
(1, 'video.view_browse_videos', 0),
(2, 'blog.share_blogs', 0),
(2, 'blog.view_browse_blogs', 0),
(2, 'event.share_events', 0),
(2, 'event.view_browse_events', 0),
(2, 'forum.share_forum', 0),
(2, 'forum.view_browse_forum', 0),
(2, 'link.share_links', 0),
(2, 'link.view_browse_links', 0),
(2, 'music.share_music', 0),
(2, 'music.view_browse_music', 0),
(2, 'organization.share_updates', 0),
(2, 'organization.view_browse_updates', 0),
(2, 'organization.view_browse_widgets', 0),
(2, 'photo.share_photos', 0),
(2, 'photo.view_browse_photos', 0),
(2, 'shoutbox.view_post_shoutbox', 0),
(2, 'video.share_videos', 0),
(2, 'video.view_browse_videos', 0),
(3, 'blog.share_blogs', 0),
(3, 'blog.view_browse_blogs', 0),
(3, 'event.share_events', 0),
(3, 'event.view_browse_events', 0),
(3, 'forum.share_forum', 0),
(3, 'forum.view_browse_forum', 0),
(3, 'link.share_links', 0),
(3, 'link.view_browse_links', 0),
(3, 'music.share_music', 0),
(3, 'music.view_browse_music', 0),
(3, 'organization.share_updates', 0),
(3, 'organization.view_browse_updates', 0),
(3, 'organization.view_browse_widgets', 0),
(3, 'photo.share_photos', 0),
(3, 'photo.view_browse_photos', 0),
(3, 'shoutbox.view_post_shoutbox', 0),
(3, 'video.share_videos', 0),
(3, 'video.view_browse_videos', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
