--
-- Table structure for table `ECMS_BANNERS`
--

CREATE TABLE IF NOT EXISTS `##DB_TABLEPREFIX##BANNERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(300) NOT NULL,
  `link` varchar(500) NOT NULL,
  `location` varchar(10) NOT NULL COMMENT 'the category id in content or the other location like pagetop',
  `position` varchar(10) NOT NULL COMMENT 'the position in content or elsewhere',
  `active` tinyint(1) NOT NULL,
  `openin` tinyint(1) NOT NULL,
  `type` int(1) NOT NULL,
  `viewcount` int(11) NOT NULL,
  `maxviewcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ECMS_BANNERS`
--

