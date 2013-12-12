/*
SQLyog Community v10.42 
MySQL - 5.5.24-log : Database - kohana-2-admin
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`kohana-2-admin` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `kohana-2-admin`;

/*Table structure for table `fileLinks` */

DROP TABLE IF EXISTS `fileLinks`;

CREATE TABLE `fileLinks` (
  `fileLinksFile_id` int(11) unsigned NOT NULL,
  `fileLinksItem_area` enum('client_records','products','orders','artworks','opportunities','tasks','template_documents') DEFAULT NULL,
  `fileLinksItem_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `fileLinksFile_id` (`fileLinksFile_id`,`fileLinksItem_area`,`fileLinksItem_id`),
  KEY `fileLinksItem_type` (`fileLinksItem_area`,`fileLinksItem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `fileLinks` */

/*Table structure for table `files` */

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_type` varchar(50) DEFAULT NULL,
  `file_token` varchar(20) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_name_system` varchar(255) DEFAULT NULL,
  `file_extension` varchar(10) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_mime_type` varchar(50) DEFAULT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `file_status` tinyint(1) unsigned DEFAULT '1',
  `file_created_date` datetime DEFAULT NULL,
  `file_updated_date` datetime DEFAULT NULL,
  `file_deleted` tinyint(1) DEFAULT '0',
  `file_deleted_date` datetime DEFAULT NULL,
  `file_last_editor` int(11) unsigned NOT NULL,
  PRIMARY KEY (`file_id`),
  UNIQUE KEY `file_token_3` (`file_token`),
  KEY `file_token` (`file_token`),
  KEY `file_status` (`file_status`),
  KEY `file_deleted` (`file_deleted`),
  KEY `file_token_2` (`file_token`,`file_status`,`file_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `files` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `nice_name` varchar(32) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`nice_name`,`description`) values (1,'login','Login','Login privileges, granted after account confirmation'),(2,'admin','Administrator','Administrative user, has access to everything.');

/*Table structure for table `roles_users` */

DROP TABLE IF EXISTS `roles_users`;

CREATE TABLE `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`),
  CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `roles_users` */

insert  into `roles_users`(`user_id`,`role_id`) values (1,1),(2,1),(1,2),(2,2);

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` varchar(127) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sessions` */

insert  into `sessions`(`session_id`,`last_activity`,`data`) values ('c5r7s878keh3hhfpfnqc7hgrd6',1386875239,'MLY4+BNbISKaytJKcE61er4NzEnSnyJ0AyUhm2cIe12ymBfa/0hdkHBrDxvNEOPYgK+KPouE6aI4YPMCVQzjKwnuGYw/u8fwkjYZJfZWit2jBlmANQKwJ5uE70r9cGIAIKafgecPKHlj/rJCLfnv7KZlehFgaAr2hsGcRNm/nckK69heUwr2Qq5h649x/xJq40meekrQwDuBGbbjCOZbzberw6XDzl+cfxE7/1Qnc43guf2EHOPBc3t31Y8AWN+VNCkCXf9BryszB6tliI60Cx7KocsKDexEv2RVuRphH071yK+D79LtTtHhFIGuGwwXW9QSRbedUMFb1rLw2aUH18dZxCgzj87xYtPV1jZZc9m8uT0YY9zOnQT1ZFJ1tIuGo+HWVH9VFep7dFT8ahhDZx6gdk3bO9D/wPV/ej4BWPRT3qxePF/58E/sG/f9G9Vovkocqbi0YoLGc6GkS3D3hwmF3EycENrcST/nMhMC3Wjz12LlUWb/XDI9vUk6KsmqJe9QaFDP11b7ItA0DkIUVJHJ4xEolLM6vSX9zYZ7TfeDQTPZz7Zt1iX/rZ/uVH+yrEic3QaqTofCJsKO1zgtQ9piiumMSadZWf54zs/B11V4xgZkNw/+bIyAEqk9AwVAQveCcirZJztET+mJ7hkWJ3isF9uT5eSGmzbsCRaQfaDv+aRCPyIcFUhpJ/GJ5TbOlRlXCUDst5cJg51yWvqGeZ5s3QIpyA24qtGgj3ud1yq9DsTfVtNlhmE4SagNx5pNU/9MkdjkZsfzEeql95s0vifnyWr5ieWe23Or7nVWC6twYBQ6AbuJ13kJgtXDuS3OYiZTBruvzzEt3Cxx8NZJVugnjapo4JjMcLHYfUdPKY+Wi1hA3vozrYOOI/K+WavHr+OpCn1UvblPbuSUREHfvgcjHt8JYltSp0ou869lenlefFJ0HtMkn6yWt+juVEV4Wzst2JqA1wshgAC8OM0L0zN6gcBTz54fyRw1hivUiUyWuII+EVLuLRm4RI2/njte7+lBXzViIzKDtBG80FbwW7/2badds4kOJz6dromTxKPgWthBRA==');

/*Table structure for table `user_tokens` */

DROP TABLE IF EXISTS `user_tokens`;

CREATE TABLE `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`),
  CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_tokens` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` char(100) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) unsigned DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `last_editor` tinyint(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`username`,`password`,`logins`,`last_login`,`status`,`created_date`,`updated_date`,`deleted`,`deleted_date`,`last_editor`) values (1,'test@example.com','test','8e8b3d6e7db133b870a8d2b8a017216b5f7a24def0e89f87c',1,1386800314,1,NULL,'2013-12-12 19:01:29',0,NULL,1),(2,'test2@example.com','test2','97bf93e8c4780b17954676d49092fd6ff9abba5f01546d3f2',0,NULL,1,'2013-12-12 19:02:08','2013-12-12 19:07:17',0,NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
