CREATE TABLE IF NOT EXISTS `##DB_TABLEPREFIX##SLIDESHOW` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` int(11) NOT NULL,
  `file` varchar(256) NOT NULL,
  `heading` varchar(200) NOT NULL,
  `description` varchar(300) NOT NULL,
  `textposition` varchar(10) NOT NULL,
  `link` varchar(500) NOT NULL,
  `publish_from` date NOT NULL,
  `publish_to` date NOT NULL,
  `lang` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;