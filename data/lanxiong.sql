CREATE DATABASE `test`;

USE `test`;

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT 0,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `tags` int(10) DEFAULT 0,
  `pic` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` INT(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
