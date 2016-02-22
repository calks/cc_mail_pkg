DROP TABLE IF EXISTS `email_template`;
CREATE TABLE  `email_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `body_content` text,
  `subject_content` varchar(255) DEFAULT NULL,
  `legend` text,
  `changed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;