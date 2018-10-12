CREATE TABLE `trivia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(100) DEFAULT NULL,
  `options` text,
  `answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `matchmaking` (
  `userid` varchar(32) DEFAULT NULL,
  `request_time` int(11) NOT NULL,
  KEY `request_time` (`request_time`)
);
