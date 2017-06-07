CREATE TABLE `dispatch` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `member_id` int(10) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `operation` int(11) NOT NULL,
  `created` int(11) DEFAULT '0',
  `updated` int(11) DEFAULT '0',
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `dispatch_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dispatch_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB
