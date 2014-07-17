--
-- Table structure for table `ECMS_MENU`
--

CREATE TABLE IF NOT EXISTS `##DB_TABLEPREFIX##MENU` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `parentid` int(11) NOT NULL,
  `orderno` int(11) NOT NULL,
  `link` varchar(500) NOT NULL,
  `type` char(1) NOT NULL,
  `layout` char(1) NOT NULL,
  `lang` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `display_new_articles` tinyint(1) NOT NULL, 
  `show_in_menu` tinyint(1) NOT NULL,
  `show_in_footer` tinyint(1) NOT NULL,
  `viewcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;