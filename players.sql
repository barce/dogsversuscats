DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;

