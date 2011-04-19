USE `tournaments`;

-- --------------------------------------------------------

--
-- Table structure for table `tourney_users`
--
CREATE TABLE IF NOT EXISTS `tourney_users` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `email`      varchar(255) NOT NULL,
  `username`   varchar(255) NOT NULL,
  `password`   varchar(255) NOT NULL,
  `salt`       varchar(50) NOT NULL,
  `role_id`    int(5) DEFAULT 10,
  `ip_address` varchar(20) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created`    datetime DEFAULT NULL,
  `updated`    datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY (`email`),
  KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `tourney_user_settings`
--
CREATE TABLE IF NOT EXISTS `tourney_user_settings` (
  `id`         int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `tourney_tournaments`
--
CREATE TABLE IF NOT EXISTS `tourney_tournaments` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `user_id`     int(11) NOT NULL,
  `title`       varchar(255) NOT NULL,
  `description` text DEFAULT '',
  `size`        int(5) DEFAULT 4,
  `show_seeds`  tinyint(1) DEFAULT 0,
  `data`        text DEFAULT '',
  `status_id`   int(5) DEFAULT 10,
  `created`     datetime DEFAULT NULL,
  `updated`     datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Constraints for table `tourney_users_settings`
--
ALTER TABLE `tourney_user_settings`
  ADD CONSTRAINT `tourney_user_settings_FK_1` FOREIGN KEY (`id`) REFERENCES `tourney_users` (`id`);

--
-- Constraints for table `tourney_tournaments`
--
ALTER TABLE `tourney_tournaments`
  ADD CONSTRAINT `tourney_tournaments_FK_1` FOREIGN KEY (`user_id`) REFERENCES `tourney_users` (`id`);

--
-- Dummy data for table `tourney_users` and `tourney_user_settings`
--
INSERT INTO `tourney_users` VALUES(1, 'jim@jimyi.com', 'jim', 'd3b570c2e923f9c8ee67b557565b0935ea963c93', '1232119794c046534df4950.02424473', 50, NULL, NULL, '2010-05-31 21:41:08', '2010-05-31 21:41:08');
INSERT INTO `tourney_users` VALUES(2, 'david@hostducky.com', 'david', '21e1174cd79b6eb78d2df7e38348b1ffb3cbff01', '755346914c04671430ef70.83993313', 10, NULL, NULL, '2010-05-31 21:49:08', '2010-05-31 21:49:08');
INSERT INTO `tourney_user_settings` (`id`) VALUES(1);
INSERT INTO `tourney_user_settings` (`id`) VALUES(2);
