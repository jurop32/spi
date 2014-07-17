CREATE TABLE IF NOT EXISTS `##DB_TABLEPREFIX##USERGROUPS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `default` varchar(10) NOT NULL,
  `home` varchar(10) NOT NULL,
  `articles` varchar(10) NOT NULL,
  `menu` varchar(10) NOT NULL,
  `videos` varchar(10) NOT NULL,
  `banners` varchar(10) NOT NULL,
  `filemanager` varchar(10) NOT NULL,
  `slideshow` varchar(10) NOT NULL,
  `eventscalendar` varchar(10) NOT NULL,
  `polls` varchar(10) NOT NULL,
  `profile` varchar(10) NOT NULL,
  `users` varchar(10) NOT NULL,
  `usergroups` varchar(10) NOT NULL,
  `tools` varchar(10) NOT NULL,
  `openinghours` varchar(10) NOT NULL,
  `dayoffer` varchar(10) NOT NULL,
  `newsletter` varchar(10) NOT NULL,
  `languages` varchar(10) NOT NULL,
  `help` varchar(10) NOT NULL,
  `stats` varchar(10) NOT NULL,
  `settings` varchar(10) NOT NULL,
  `denylogin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ECMS_USERGROUPS`
--

INSERT INTO `##DB_TABLEPREFIX##USERGROUPS` (`id`, `name`, `default`, `home`, `articles`, `menu`, `videos`, `banners`, `filemanager`, `slideshow`, `eventscalendar`, `polls`, `users`, `usergroups`, `tools`, `openinghours`, `dayoffer`, `newsletter`, `languages`, `help`, `stats`, `settings`, `denylogin`) VALUES
(1, 'root', '1', '1', '11111', '1111', '11', '11', '1', '11', '11', '11', '11111', '11111', '1', '1', '11', '11', '111', '11', '1', '1', '11', 0),
(2, 'Admins', '1', '1', '10000', '1000', '10', '10', '1', '10', '10', '10', '10000', '10000', '1', '1', '10', '10', '100', '10', '1', '1', '10', 0);