/*
SQLyog Community v12.5.0 (64 bit)
MySQL - 10.1.26-MariaDB : Database - e_wallet
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`e_wallet` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `e_wallet`;

/*Table structure for table `app_session` */

DROP TABLE IF EXISTS `app_session`;

CREATE TABLE `app_session` (
  `id` char(40) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `DATA` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `app_session` */

insert  into `app_session`(`id`,`expire`,`DATA`) values 
('4qMgrNLsErCj2zxiESr7y9VCoPcGeu6d',1568873650,'username = user1'),
('AC5MRB0Qy8wKRnPMQBf0vdf6mslMqbV-',1568873774,'username = user1'),
('Nbjlstgh4Deu_TOYdi1f4q80XBXJ3j_w',1568873576,'username = user1'),
('O29rtnqARgrx8oIVXtXaAVHcpIjPzi1z',1568897928,'username = user3'),
('XIkvfA8Aj_W_Ee5F3-CV-tnBo_TDPrEQ',1569049318,'username = user3');

/*Table structure for table `balance_bank` */

DROP TABLE IF EXISTS `balance_bank`;

CREATE TABLE `balance_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `balance` int(11) DEFAULT NULL,
  `balance_achieve` int(11) DEFAULT NULL,
  `code` varchar(200) DEFAULT NULL,
  `enable` tinyint(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `balance_bank` */

insert  into `balance_bank`(`id`,`balance`,`balance_achieve`,`code`,`enable`,`created_date`,`updated_date`) values 
(1,200000,20000000,'A667',1,'2019-09-20 14:02:38','2019-09-20 14:29:52');

/*Table structure for table `balance_bank_history` */

DROP TABLE IF EXISTS `balance_bank_history`;

CREATE TABLE `balance_bank_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `balance_bank_id` int(11) DEFAULT NULL,
  `balance_before` int(11) DEFAULT NULL,
  `balance_after` int(11) DEFAULT NULL,
  `activity` varchar(200) DEFAULT NULL,
  `type` enum('credit','debit') DEFAULT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL,
  `author` varchar(200) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `balance_bank_id` (`balance_bank_id`),
  CONSTRAINT `balance_bank_history_ibfk_1` FOREIGN KEY (`balance_bank_id`) REFERENCES `balance_bank` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `balance_bank_history` */

insert  into `balance_bank_history`(`id`,`balance_bank_id`,`balance_before`,`balance_after`,`activity`,`type`,`ip`,`location`,`user_agent`,`author`,`created_date`,`updated_date`) values 
(1,1,10000,20000,'topup','credit','192.168.0.1','Yogyakarta','BNI','admin','2019-09-20 15:48:16','2019-09-20 15:48:17');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `access_token` text,
  `last_login` datetime DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`id`,`name`,`username`,`email`,`password`,`access_token`,`last_login`,`created_date`,`updated_date`) values 
(1,'Susi Suryani','susi1','susi@gmail.com','$2y$13$b6pkLztKEuraVW1hqrcEOOsqaHikGheaZoCWXFhFWSPxPJxAyS6PS','RRwHnNSyLIP6DnvXY23opGQKzJp_LyDU','2019-09-18 16:25:56','2019-09-17 18:27:17','2019-09-18 16:25:56'),
(2,'Ridwan Hanif','user2','user2@gmail.com','$2y$13$yuRNLqjbcRIeFrg6hnmhQ.p1Ew.n8floB8/cNgu1421OnnVWRJ4zS',NULL,NULL,'2019-09-18 16:35:55',NULL),
(3,'darmawan kusuma','user3','email3@gmail.com','$2y$13$yuRNLqjbcRIeFrg6hnmhQ.p1Ew.n8floB8/cNgu1421OnnVWRJ4zS','XIkvfA8Aj_W_Ee5F3-CV-tnBo_TDPrEQ','2019-09-20 14:01:58','2019-09-18 19:56:46','2019-09-20 14:01:58');

/*Table structure for table `user_balance` */

DROP TABLE IF EXISTS `user_balance`;

CREATE TABLE `user_balance` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `balance_achieve` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_balance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `user_balance` */

insert  into `user_balance`(`id`,`user_id`,`balance`,`balance_achieve`,`created_date`,`updated_date`) values 
(1,1,20000,100000,'2019-09-19 11:47:46','2019-09-19 16:02:31'),
(6,3,0,10000000,'2019-09-20 17:18:21','2019-09-20 18:12:55'),
(7,2,40000,10000000,'2019-09-20 18:12:55','2019-09-20 17:18:43');

/*Table structure for table `user_balance_history` */

DROP TABLE IF EXISTS `user_balance_history`;

CREATE TABLE `user_balance_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_balance_id` int(11) DEFAULT NULL,
  `balance_before` int(11) DEFAULT NULL,
  `balance_after` int(11) DEFAULT NULL,
  `activity` varchar(200) DEFAULT NULL,
  `type` enum('credit','debit') DEFAULT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL,
  `author` varchar(200) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_balance_id` (`user_balance_id`),
  CONSTRAINT `user_balance_history_ibfk_1` FOREIGN KEY (`user_balance_id`) REFERENCES `user_balance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

/*Data for the table `user_balance_history` */

insert  into `user_balance_history`(`id`,`user_balance_id`,`balance_before`,`balance_after`,`activity`,`type`,`ip`,`location`,`user_agent`,`author`,`created_date`,`updated_date`) values 
(1,1,20000,25000,'topup','credit','1','Yogyakarta','BNI','admin','2019-09-19 12:45:15','2019-09-19 12:44:11'),
(14,6,0,10000,'topup','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 17:18:21','2019-09-20 17:18:21'),
(15,6,10000,20000,'topup','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 17:18:30','2019-09-20 17:18:30'),
(16,6,20000,10000,'transfer','debit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 17:18:43','2019-09-20 17:18:43'),
(17,6,0,10000,'transfer','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 17:18:43','2019-09-20 17:18:43'),
(18,6,10000,0,'transfer','debit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 17:19:13','2019-09-20 17:19:13'),
(19,6,10000,20000,'transfer','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 17:19:13','2019-09-20 17:19:13'),
(20,6,0,10000,'topup','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 18:11:20','2019-09-20 18:11:20'),
(21,6,10000,0,'transfer','debit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 18:11:22','2019-09-20 18:11:22'),
(22,6,20000,30000,'transfer','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 18:11:22','2019-09-20 18:11:22'),
(23,6,0,10000,'topup','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 18:12:47','2019-09-20 18:12:47'),
(24,6,10000,0,'transfer','debit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 18:12:55','2019-09-20 18:12:55'),
(25,6,30000,40000,'transfer','credit','127.0.0.1','yogyakarta','BNI','darmawan kusuma','2019-09-20 18:12:55','2019-09-20 18:12:55');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
