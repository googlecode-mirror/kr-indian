CREATE TABLE IF NOT EXISTS `v_upload` (
  `upload_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `in_process` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) DEFAULT NULL,
  `time_stamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`upload_id`),
  KEY `in_process` (`in_process`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;