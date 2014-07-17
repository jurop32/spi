CREATE TABLE IF NOT EXISTS `##DB_TABLEPREFIX##USERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `fronttitles` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surename` varchar(50) NOT NULL,
  `endtitles` varchar(20) NOT NULL,
  `cookiesecret` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(256) NOT NULL,
  `loggedin` tinyint(1) NOT NULL,
  `usergroup` int(11) NOT NULL,
  `denylogin` tinyint(1) NOT NULL,
  `categoryaccess` int(11) NOT NULL,
  `newsletter` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ECMS_USERS`
--

INSERT INTO `ECMS_USERS` (`id`, `username`, `fronttitles`, `firstname`, `surename`, `endtitles`, `cookiesecret`, `password`, `email`, `loggedin`, `usergroup`, `denylogin`, `categoryaccess`, `newsletter`) VALUES
(1, 'root', 'Ing.', 'Ľudovít', 'Vörös', '', '63a9f0ea7bb98050796b649e85481845', 'e40cd151c90ca43b545d0632ab7818ad', 'ludovit.voros@exetra.sk', 1, 1, 0, 0, 0),
(2, 'admin', '', 'ECMS', 'Admin', '', '61e792f7c43c3a60a7f50cd5387c08b4', '99b85bdac3504f7e2c8df6722b7f186d', 'admin@admin.com', 1, 2, 0, 0, 0);