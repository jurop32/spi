CREATE TABLE IF NOT EXISTS `##DB_TABLEPREFIX##ARTICLES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(250) NOT NULL,
  `article_title` varchar(250) NOT NULL,
  `article_createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `article_changeDate` datetime NOT NULL,
  `article_prologue` varchar(3000) NOT NULL,
  `article_content` text NOT NULL,
  `keywords` varchar(500) NOT NULL,
  `id_menucategory` int(11) NOT NULL,
  `author` varchar(200) NOT NULL,
  `layout` char(1) NOT NULL,
  `lang` int(3) NOT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `showsocials` tinyint(1) NOT NULL,
  `publish_date` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `topped` tinyint(1) NOT NULL,
  `homepage` tinyint(1) NOT NULL,
  `viewcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;