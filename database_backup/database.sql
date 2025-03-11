/*
SQLyog Ultimate v12.5.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - gates
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gates` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `gates`;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2025_02_16_131333_create_users_table',1),
(2,'2025_02_16_131347_create_posts_table',1);

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `posts` */

insert  into `posts`(`id`,`title`,`description`,`user_id`,`created_at`,`updated_at`) values 
(7,'News Title One','News First News First News First News FirstNews First',1,'2025-02-16 18:59:02','2025-02-16 18:59:06'),
(8,'News Title Two','News Two News Two News Two News Two News Two',2,'2025-02-16 19:00:10','2025-02-16 19:00:14'),
(9,'News Title Two','News Three News Three News Three News Three News Three News Three',1,'2025-02-16 19:01:17','2025-02-16 19:01:20'),
(11,'News Title Five','News five News five News five News five News five',3,'2025-02-16 19:02:46','2025-02-16 19:02:50'),
(12,'News Title Six','News six News six News six News six News six News six News six',2,'2025-02-16 19:03:49','2025-02-25 15:40:47'),
(14,'News Title Eight','News Eight News Eight News Eight News Eight News Eight News Eight News Eight News Eight News Eight News Eight',2,'2025-02-16 19:06:20','2025-02-25 15:17:04'),
(17,'News Title Twenty','News Twenty News Twenty News Twenty',1,'2025-02-25 17:52:47','2025-02-25 17:55:58'),
(18,'News Title Twenty One','News Twenty One News Twenty One News Twenty One News Twenty One',1,'2025-02-26 05:16:25','2025-02-26 05:16:25'),
(19,'News Title Twenty Two','News Twenty Two News Twenty Two News Twenty Two',1,'2025-02-26 05:22:22','2025-02-26 05:22:22'),
(20,'News Title Twenty Three','News Twenty Three News Twenty Three News Twenty Three',1,'2025-02-26 05:24:10','2025-02-26 05:39:36'),
(21,'News Title Twenty Four','News Twenty Four News Twenty Four News Twenty Four',1,'2025-02-26 05:35:16','2025-02-26 05:35:16'),
(22,'News Title Twenty Five','News Twenty Five News Twenty Five News Twenty Five',1,'2025-02-26 05:40:21','2025-02-26 05:47:24'),
(32,'News Title Twenty Six','News Twenty Six News Twenty Six News Twenty Six News Twenty Six',4,'2025-02-26 05:49:28','2025-02-26 19:20:19'),
(36,'News Title Twenty Seven','News Twenty Seven News Twenty Seven News Twenty SevenNews Twenty Seven',1,'2025-02-27 07:40:47','2025-02-27 07:40:47'),
(37,'News Title Fifteen','News Fifteen News Fifteen News Fifteen News Fifteen News Fifteen',6,'2025-02-27 07:53:39','2025-02-27 07:53:39');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `role` enum('Admin','Author') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`age`,`role`,`created_at`,`updated_at`) values 
(1,'Zohaib Ali','zohaib@gmail.com','$2y$12$FGUcVVMxiXThq0HaCY5A4.DBkJv0tvw4YO/bfxrMKftJEqShjqtVG',20,'Admin',NULL,NULL),
(2,'Waqar Ali','waqar@gmail.com','$2y$12$b8mKVVNpQDK.iSf/5hqQ1OLHlAq0GW4sf6EH6oTWeci2NFCRZm1RC',18,'Author',NULL,NULL),
(3,'Zama Ali','zama@gmail.com','$2y$12$YH5QkqtZhFN47I7/UbYlDuqxhf17H781kEoHeR48mct.beSmSSA6K',19,'Author',NULL,NULL),
(4,'Khaild Ali','khalid@gmail.com','$2y$12$U/Us.8iwSxgQhaFVI.TGKupdmos71RShWXrm7639seDTtHQJwm5Ye',20,'Author','2025-02-21 19:06:05','2025-02-21 19:06:05'),
(5,'Ashok Kumar','ashok@gmail.com','$2y$12$n/9k5Wite4A6rVj6gFKxcugKsEtXcJwyowp7HQOtAQiTZSRRQ/N6e',19,'Admin','2025-02-25 05:36:48','2025-02-25 05:36:48'),
(6,'Anees Ali','anees@gmail.com','$2y$12$HYccCgS4Dnx.nSGKhTmUk.oQKrgsy3fD5voZ1HFfWFqcdWrcDxpHq',22,'Author','2025-02-27 07:52:22','2025-02-27 07:52:22');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
