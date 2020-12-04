/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 10.3.15-MariaDB : Database - vimeo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vimeo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `vimeo`;

/*Table structure for table `links` */

DROP TABLE IF EXISTS `links`;

CREATE TABLE `links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `biolink_id` int(11) DEFAULT NULL,
  `domain_id` int(11) NOT NULL DEFAULT 0,
  `type` varchar(32) NOT NULL DEFAULT '',
  `subtype` varchar(32) DEFAULT NULL,
  `url` varchar(256) NOT NULL DEFAULT '',
  `location_url` varchar(512) DEFAULT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `settings` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_enabled` tinyint(4) NOT NULL DEFAULT 1,
  `date` datetime NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `url` (`url`(191)),
  KEY `type` (`type`),
  KEY `subtype` (`subtype`),
  CONSTRAINT `links_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `links_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=590 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

/*Data for the table `links` */

insert  into `links`(`link_id`,`project_id`,`user_id`,`biolink_id`,`domain_id`,`type`,`subtype`,`url`,`location_url`,`clicks`,`settings`,`order`,`start_date`,`end_date`,`is_enabled`,`date`) values 
(1,1,1,NULL,0,'biolink','base','instagramte',NULL,48,'{\"title\":\"My Featured Links \\ud83d\\udd25\",\"description\":\"Great App\",\"display_verified\":false,\"image\":\"1603812694.png\",\"background_type\":\"preset\",\"background\":\"one\",\"text_color\":\"#fff\",\"socials_color\":\"#fff\",\"google_analytics\":\"\",\"facebook_pixel\":\"\",\"display_branding\":true,\"branding\":{\"name\":\"\",\"url\":\"\"},\"seo\":{\"title\":\"\",\"meta_description\":\"\"},\"utm\":{\"medium\":\"\",\"source\":\"\"},\"socials\":{\"email\":\"\",\"tel\":\"\",\"whatsapp\":\"\",\"facebook\":\"\",\"facebook-messenger\":\"\",\"instagram\":\"\",\"twitter\":\"\",\"tiktok\":\"\",\"youtube\":\"\",\"soundcloud\":\"\",\"linkedin\":\"linkedddd\",\"spotify\":\"\",\"pinterest\":\"\"},\"font\":\"lato\"}',0,NULL,NULL,1,'2020-10-07 02:13:35'),
(580,1,1,1,0,'biolink','vimeo','lUQBBEvjqJ','https://vimeo.com/486898296',0,'[]',1,NULL,NULL,1,'2020-12-03 17:20:00');

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  PRIMARY KEY (`project_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4;

/*Data for the table `projects` */

insert  into `projects`(`project_id`,`user_id`,`name`,`date`) values 
(1,1,'vimeoPro','2020-08-31 11:27:57');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `twofa_secret` varchar(16) DEFAULT NULL,
  `email_activation_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `lost_password_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_id` bigint(20) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `active` int(11) NOT NULL DEFAULT 0,
  `package_id` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `package_expiration_date` datetime DEFAULT NULL,
  `package_settings` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `package_trial_done` tinyint(4) DEFAULT 0,
  `payment_subscription_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'english',
  `timezone` varchar(32) DEFAULT 'UTC',
  `date` datetime DEFAULT NULL,
  `ip` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `last_user_agent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_logins` int(11) DEFAULT 0,
  PRIMARY KEY (`user_id`),
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`user_id`,`email`,`password`,`name`,`token_code`,`twofa_secret`,`email_activation_code`,`lost_password_code`,`facebook_id`,`type`,`active`,`package_id`,`package_expiration_date`,`package_settings`,`package_trial_done`,`payment_subscription_id`,`language`,`timezone`,`date`,`ip`,`country`,`last_activity`,`last_user_agent`,`total_logins`) values 
(1,'support@linkinbio.xyz','abcxyz','AdminUser','',NULL,'','',NULL,1,1,'2','2050-07-15 11:26:19','{\"additional_global_domains\":true,\"deep_links\":true,\"no_ads\":true,\"removable_branding\":true,\"custom_branding\":true,\"custom_colored_links\":true,\"statistics\":true,\"google_analytics\":true,\"facebook_pixel\":true,\"custom_backgrounds\":true,\"verified\":true,\"scheduling\":true,\"seo\":true,\"utm\":true,\"socials\":true,\"fonts\":true,\"projects_limit\":-1,\"biolinks_limit\":-1,\"links_limit\":-1,\"domains_limit\":0}',1,'','english','UTC','2019-06-01 12:00:00','127.0.0.1','','2030-05-29 06:57:37','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36',157);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
