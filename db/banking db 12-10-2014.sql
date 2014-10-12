-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: banking
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `banking`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `banking` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `banking`;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) NOT NULL DEFAULT 'default account ',
  `a_user` int(11) NOT NULL COMMENT 'owner of the account',
  `a_number` varchar(20) NOT NULL,
  `a_balance` double NOT NULL,
  `a_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`a_id`),
  KEY `a_user` (`a_user`),
  CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`a_user`) REFERENCES `users` (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'alice personal acount',1,'1',100,'2014-10-11 17:55:40'),(2,'Starter',2,'2',0,'2014-10-11 22:34:16'),(6,'default account ',7,'kj',0,'2014-10-12 08:34:17');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_codes`
--

DROP TABLE IF EXISTS `transaction_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_codes` (
  `tc_code` varchar(15) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `tc_account` int(11) NOT NULL,
  `tc_active` bit(1) NOT NULL DEFAULT b'1' COMMENT 'code is used/unused',
  `tc_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_codes`
--

LOCK TABLES `transaction_codes` WRITE;
/*!40000 ALTER TABLE `transaction_codes` DISABLE KEYS */;
INSERT INTO `transaction_codes` VALUES ('alice0000000001',1,'\0','0000-00-00 00:00:00'),('alice0000000002',1,'','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `transaction_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_account_from` int(11) NOT NULL,
  `t_amount` int(11) NOT NULL,
  `t_type` int(11) NOT NULL,
  `t_code` varchar(15) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `t_description` varchar(250) NOT NULL,
  `t_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `t_account_to` int(11) NOT NULL,
  PRIMARY KEY (`t_id`),
  KEY `t_account` (`t_account_from`),
  KEY `t_code` (`t_code`),
  KEY `transactions_ibfk_3` (`t_account_to`),
  CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`t_account_to`) REFERENCES `accounts` (`a_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`t_account_from`) REFERENCES `accounts` (`a_id`),
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`t_code`) REFERENCES `transaction_codes` (`tc_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,1,100,0,'alice0000000001','first deposit','2014-10-11 18:47:20',0),(2,1,20,0,'Alice 9320923','ur money','2014-10-11 22:36:09',2),(3,2,3,1,'Alice 9320923','ur money','2014-10-11 22:50:44',1);
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(20) DEFAULT NULL,
  `u_password` varchar(150) DEFAULT NULL,
  `u_active` tinyint(1) NOT NULL DEFAULT '0',
  `u_email` varchar(30) DEFAULT NULL,
  `u_type` int(11) NOT NULL DEFAULT '0',
  `u_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'alice','6384e2b2184bcbf58ecc',1,'alice@bank.com',0,'2014-10-11 17:47:15'),(2,'bob','9f9d51bc70ef21ca5c14f307980a29d8',1,'bob@banking.com',1,'2014-10-11 17:48:00'),(3,'kop','a5a7158118e59ee590424b55bb9aed17',0,'kop',0,'2014-10-11 22:54:34'),(4,'jjj','2af54305f183778d87de0c70c591fae4',0,'jjj',0,'2014-10-12 08:24:15'),(5,'nj','36b1c5be249ad6a533dcfa9654e73396',0,'nj',0,'2014-10-12 08:27:34'),(6,'op','7457cdd15d09bfc6c4dbb5d2b6f87390',0,'OP',0,'2014-10-12 08:30:11'),(7,'kkj','771f01104d905386a134a676167edccc',0,'kj',0,'2014-10-12 08:34:17');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-12 10:37:11
