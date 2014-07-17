CREATE TABLE IF NOT EXISTS `#DB_TABLEPREFIX##minislider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(50) NOT NULL,
  `filename` varchar(300) NOT NULL,
  `url` varchar(500) NOT NULL,
  `lang` int(3) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `orderno` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;