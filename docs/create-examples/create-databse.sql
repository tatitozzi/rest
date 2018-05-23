/*
SQLyog Community v12.5.1 (64 bit)
MySQL - 5.7.21 : Database - restoauth2ant-example-database
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`restoauth2ant-example-database` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `restoauth2ant-example-database`;

/*Table structure for table `table` */

DROP TABLE IF EXISTS `table`;

CREATE TABLE `table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `phone` varchar(100) CHARACTER SET latin1 NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nickname` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `table` */

/*Table structure for table `table-db-cnf` */

DROP TABLE IF EXISTS `table-db-cnf`;

CREATE TABLE `table-db-cnf` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '["integer"]',
  `name` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT '["any"]',
  `phone` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT '["phone"]',
  `email` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT '["email"]',
  `nickname` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT '["any"]',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `table-db-cnf` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
