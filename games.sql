DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `player_id1` mediumint(5) NOT NULL,
  `player_id2` mediumint(5) ,
  `last_move` enum(1, 2),
  `board` text,
  `game_start` datetime, 
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `winner_id` mediumint(5),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;

