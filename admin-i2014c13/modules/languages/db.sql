--
-- Table structure for table `ECMS_LANGUAGES`
--

CREATE TABLE IF NOT EXISTS `##DB_TABLEPREFIX##LANGUAGES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `shortcode` varchar(2) NOT NULL,
  `longcode` varchar(6) NOT NULL,
  `defaultlang` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ECMS_LANGUAGES`
--

INSERT INTO `##DB_TABLEPREFIX##LANGUAGES` (`id`, `name`, `shortcode`, `longcode`, `defaultlang`, `published`) VALUES
(1, 'Slovenský (SK)', 'sk', 'sk-SK', 1, 1),
(3, 'English (EN)', 'en', 'en-US', 0, 1);