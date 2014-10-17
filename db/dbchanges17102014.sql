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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'alice personal acount',1,'1',1134,'2014-10-11 17:55:40'),(2,'Starter',2,'2',18,'2014-10-11 22:34:16'),(6,'default account ',7,'kj',0,'2014-10-12 08:34:17'),(7,'default account ',8,'12356',0,'2014-10-12 09:20:37'),(9,'default account ',10,'123569',-1019,'2014-10-12 09:35:34'),(10,'default account ',12,'4444',-10,'2014-10-12 15:41:26');
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
  `tc_active` tinyint(1) NOT NULL DEFAULT '1',
  `tc_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_codes`
--

LOCK TABLES `transaction_codes` WRITE;
/*!40000 ALTER TABLE `transaction_codes` DISABLE KEYS */;
INSERT INTO `transaction_codes` VALUES ('/3nz+a+QBo3FPtK',10,0,'2014-10-12 15:41:27'),('/I3txzXGJl/xiPI',10,1,'2014-10-12 15:41:27'),('/to6DC3k/IbY933',10,1,'2014-10-12 15:41:27'),('/un1d1tQqJLZMUz',9,0,'2014-10-12 09:35:34'),('/zSuqxo1OFahGd6',9,0,'2014-10-12 09:35:34'),('+22GyVbzBXZA2bs',10,1,'2014-10-12 15:41:27'),('+82r+9nhudXQHvQ',10,1,'2014-10-12 15:41:27'),('+VRXMp40gK9H4Go',9,0,'2014-10-12 09:35:34'),('0FzVRe04smR2MCq',9,0,'2014-10-12 09:35:34'),('0k/NcEEhzMtdeE8',9,0,'2014-10-12 09:35:34'),('0rqgdVLWlND2Gfb',10,1,'2014-10-12 15:41:27'),('0YHCFkBzrYGo8km',9,1,'2014-10-12 09:35:34'),('1/pVDcEB5bLMfYm',10,1,'2014-10-12 15:41:27'),('17BULmP3/pZx7U1',9,1,'2014-10-12 09:35:34'),('1rQj5b7AnXb0Vid',9,1,'2014-10-12 09:35:34'),('1tchW27+qOadhi0',10,1,'2014-10-12 15:41:27'),('1VGV7tXbXnhQ2ey',9,1,'2014-10-12 09:35:34'),('1X+FgLIwTc+Nk/R',10,1,'2014-10-12 15:41:27'),('1yvn5axnVJTjRza',9,1,'2014-10-12 09:35:34'),('2dViM/HrSBc83wn',10,1,'2014-10-12 15:41:27'),('2m7PU82oCLsJD69',9,1,'2014-10-12 09:35:34'),('2ssCvvqmUd3Q4vZ',10,1,'2014-10-12 15:41:27'),('3jTU+CW8lp35uL7',9,1,'2014-10-12 09:35:34'),('4QpcstV53X4vS9j',10,1,'2014-10-12 15:41:26'),('5GhTk9ydBy1SgM2',9,1,'2014-10-12 09:35:34'),('5hhR8vD742+DoSp',9,1,'2014-10-12 09:35:34'),('5Hklk9UyvW5ldcA',9,1,'2014-10-12 09:35:34'),('5kGvFf6uLtuCczO',9,1,'2014-10-12 09:35:34'),('5mOtVc7cjrb/6SC',9,1,'2014-10-12 09:35:34'),('5z58FhJ1ZWs/4uj',10,1,'2014-10-12 15:41:27'),('6ApJlQoGUj5MNbF',10,1,'2014-10-12 15:41:27'),('6JX3qK808kW00h5',9,1,'2014-10-12 09:35:34'),('7eOJk+Iz58iRkvh',9,1,'2014-10-12 09:35:34'),('8wmm2zpdsOMuxki',10,1,'2014-10-12 15:41:27'),('8XtICOUeSnw+VnD',10,1,'2014-10-12 15:41:27'),('9rQS195zdz8lz2E',9,1,'2014-10-12 09:35:34'),('A2FrdU0P1twSmx9',10,1,'2014-10-12 15:41:27'),('aFedrF/DTC7Wm74',9,1,'2014-10-12 09:35:34'),('AKdmWGSsRpoYpro',10,1,'2014-10-12 15:41:27'),('alice0000000001',1,0,'0000-00-00 00:00:00'),('alice0000000002',1,1,'0000-00-00 00:00:00'),('azmfZH0av6Tf2fD',9,1,'2014-10-12 09:35:34'),('AZQPAQAypvd3525',9,1,'2014-10-12 09:35:34'),('b0bCCe+RqvQJB56',9,1,'2014-10-12 09:35:34'),('b0JK7HjzzLyaJaW',9,1,'2014-10-12 09:35:34'),('BbGcFujkwCSrhxO',9,1,'2014-10-12 09:35:34'),('bd0MtSeaV2p1+ru',9,1,'2014-10-12 09:35:34'),('bEoNbca7Mm+AO8p',9,1,'2014-10-12 09:35:34'),('bgIFaq6Bs+vL41l',9,1,'2014-10-12 09:35:34'),('bm7ZfUhNmMmwaDg',10,1,'2014-10-12 15:41:27'),('BoW2CCUVeYA/TT1',10,1,'2014-10-12 15:41:26'),('BpfAHRyBdDvzSC2',9,1,'2014-10-12 09:35:34'),('BQ9tQ0Gadqed+Rz',9,1,'2014-10-12 09:35:34'),('brqd0qGLSmIKoIG',9,1,'2014-10-12 09:35:34'),('CHsdZWtmu7q8LW5',9,1,'2014-10-12 09:35:34'),('ClB1dmrQW7YgQem',10,1,'2014-10-12 15:41:27'),('cNFgk6N0Ac5mruI',10,1,'2014-10-12 15:41:27'),('CSTMJ7bt7R+J5Zh',10,1,'2014-10-12 15:41:27'),('CveS2JDraSOf4Wf',10,1,'2014-10-12 15:41:27'),('d+DyhO+oK2+TpxC',10,1,'2014-10-12 15:41:27'),('d3mT3napcY0y83n',10,1,'2014-10-12 15:41:27'),('d5Zblpk0D/E2QUm',9,1,'2014-10-12 09:35:34'),('DA8A4Mm8aXlyMf2',9,1,'2014-10-12 09:35:34'),('DFM+x0keoNUH2LV',9,1,'2014-10-12 09:35:34'),('dGAEkU/e0v7Xh2q',10,1,'2014-10-12 15:41:27'),('dSUShFs/tUYBJIm',9,1,'2014-10-12 09:35:34'),('Ec/9aK2dTir9nWR',10,1,'2014-10-12 15:41:27'),('EH86BMI3Chx2cGf',10,1,'2014-10-12 15:41:27'),('EiyWBETyXryPmcx',9,1,'2014-10-12 09:35:34'),('eXZWJhP7l+WQK2t',9,1,'2014-10-12 09:35:34'),('f8jQswQmfplpyEC',9,1,'2014-10-12 09:35:34'),('FaT0cw8C2CbyeXY',9,1,'2014-10-12 09:35:34'),('fFJFFlkOuVlMBSk',10,1,'2014-10-12 15:41:27'),('ficKDBMYXiOVxEh',9,1,'2014-10-12 09:35:34'),('FiUuixGRaM7QaJB',10,1,'2014-10-12 15:41:27'),('GjS8AWl7j6Mx/oP',10,1,'2014-10-12 15:41:27'),('GjUG6JsigDUG+To',10,1,'2014-10-12 15:41:27'),('GKIffvkkamOq6BH',9,1,'2014-10-12 09:35:34'),('Gm76lMnGsTfIsIl',9,1,'2014-10-12 09:35:34'),('GOWVaEn6m08JDZn',9,1,'2014-10-12 09:35:34'),('gTH3MycEZZ7y+Yk',9,1,'2014-10-12 09:35:34'),('gzTfjf8Uvkl1VZI',10,1,'2014-10-12 15:41:27'),('h+d6zs+lSJ2CHTy',10,1,'2014-10-12 15:41:27'),('h8kYhF7TQXGP0qX',9,1,'2014-10-12 09:35:34'),('H9rQe89pOaaoZtZ',9,1,'2014-10-12 09:35:34'),('HApHBnHfVkXxMdI',10,1,'2014-10-12 15:41:27'),('hCiv8UthQjKcDht',10,1,'2014-10-12 15:41:27'),('hFb1jnWS6eCYOfZ',10,1,'2014-10-12 15:41:27'),('hFkQTj/dTYAal82',9,1,'2014-10-12 09:35:34'),('hGYNwZbAPcUFSho',10,1,'2014-10-12 15:41:27'),('hhIKq5Mx3tNvqcN',9,1,'2014-10-12 09:35:34'),('hlyDn3nIUROJFgc',10,1,'2014-10-12 15:41:27'),('hvGhOUHOTZJBWXL',9,1,'2014-10-12 09:35:34'),('HwoOkIdRkR/Is2f',10,1,'2014-10-12 15:41:27'),('i2yBv9zDXgUuYEO',10,1,'2014-10-12 15:41:27'),('i76AJquMYNLEcg+',10,1,'2014-10-12 15:41:27'),('i7QvtQdfutGTqub',10,1,'2014-10-12 15:41:27'),('idiZWWnNOUQ9t7t',10,1,'2014-10-12 15:41:27'),('IhbvN8g9Z8l+NJZ',10,1,'2014-10-12 15:41:27'),('InxBbSIn6YPArk9',9,1,'2014-10-12 09:35:34'),('j4IG33XyJUzilf6',9,1,'2014-10-12 09:35:34'),('j7QJ65THOLulcMM',9,1,'2014-10-12 09:35:34'),('j9f6p53RyQ9wUWU',9,1,'2014-10-12 09:35:34'),('JB4Vufosio0neg/',9,1,'2014-10-12 09:35:34'),('jBf7VH4HEvIykFq',9,1,'2014-10-12 09:35:34'),('JfvO8TJBQ5pCXa6',10,1,'2014-10-12 15:41:27'),('jHyfOpI2/QHZpqt',9,1,'2014-10-12 09:35:34'),('jLO5nniZbRuVohN',10,1,'2014-10-12 15:41:27'),('jTMs4p69MQ2oBZB',10,1,'2014-10-12 15:41:27'),('jvZKZoGv6Y92MF0',10,1,'2014-10-12 15:41:27'),('K/KO84lH5ulrny8',10,1,'2014-10-12 15:41:27'),('kH8/yZ5awSJWHEc',10,1,'2014-10-12 15:41:27'),('KIOzW72VoIzqcpK',10,1,'2014-10-12 15:41:27'),('KmWtsnoFE01/+cj',10,1,'2014-10-12 15:41:27'),('Kys9hNl5AvGnlVS',10,1,'2014-10-12 15:41:27'),('Kz63iRO+PdPBknu',10,1,'2014-10-12 15:41:27'),('lam5yTHxyQW5kJB',10,1,'2014-10-12 15:41:27'),('lizrpwrhox2SRHz',9,1,'2014-10-12 09:35:34'),('lpQQ1wxBUlv235o',10,1,'2014-10-12 15:41:27'),('LZeSEfJWizShRMA',9,1,'2014-10-12 09:35:34'),('m4I+UL1BApo8WDF',9,1,'2014-10-12 09:35:34'),('MaCreAAlauz1uIq',10,1,'2014-10-12 15:41:27'),('Mc0Z15JxIm4zpSj',10,1,'2014-10-12 15:41:27'),('McA4Dg0tMMH9UKH',10,1,'2014-10-12 15:41:27'),('Mfgt878zXIn0iSs',10,1,'2014-10-12 15:41:27'),('MlQeL3OoWsHH4ir',10,1,'2014-10-12 15:41:27'),('MNblv9PsnoIdRvh',10,1,'2014-10-12 15:41:27'),('MY8wGscpAuc5hiN',9,1,'2014-10-12 09:35:34'),('mZgtrgyufhhotHE',9,1,'2014-10-12 09:35:34'),('NCU+uQyVBKSduVJ',10,1,'2014-10-12 15:41:27'),('NQHmB7PCj7Fy516',10,1,'2014-10-12 15:41:27'),('NZBcs0fyR24xLSI',9,1,'2014-10-12 09:35:34'),('OaugJSC6K2zcnbL',10,1,'2014-10-12 15:41:27'),('OB3pyiHx+7O0hN3',10,1,'2014-10-12 15:41:27'),('ODhLYz5SkyFpUZ2',9,1,'2014-10-12 09:35:34'),('OeRdQ1TpyIT6cjS',9,1,'2014-10-12 09:35:34'),('OMRHq614VYJySbf',10,1,'2014-10-12 15:41:27'),('oYGLdUgtAOmWn/I',9,1,'2014-10-12 09:35:34'),('p/ul+lw2Oajcpqw',9,1,'2014-10-12 09:35:34'),('P1gherh1tPxOZCe',10,1,'2014-10-12 15:41:27'),('pigWkdZEOhkVFie',9,1,'2014-10-12 09:35:34'),('PO7m/kyFH4h+yNQ',9,1,'2014-10-12 09:35:34'),('poERz5EVX0Pb0Pu',9,1,'2014-10-12 09:35:34'),('pRyLE/sreq47jyS',9,1,'2014-10-12 09:35:34'),('q0p7/njvaYCJsJ9',9,1,'2014-10-12 09:35:34'),('q0s2hFH587Thv3c',9,1,'2014-10-12 09:35:34'),('q45CNrUm6eDQGna',10,1,'2014-10-12 15:41:26'),('QfIEU+d328QjBCX',9,1,'2014-10-12 09:35:34'),('QIgJn9ug0UXgKtp',9,1,'2014-10-12 09:35:34'),('qJFuoAFK1LmqMAz',9,1,'2014-10-12 09:35:34'),('QlSMiY3ZNtcYEoJ',10,1,'2014-10-12 15:41:27'),('QMKI+3nbCzk91Ek',9,1,'2014-10-12 09:35:34'),('QriIWK7McHEWJr/',10,1,'2014-10-12 15:41:27'),('qRw0jRGRWCkBKOL',10,1,'2014-10-12 15:41:27'),('QyAKgeS/qtpObfi',9,1,'2014-10-12 09:35:34'),('rsAel0p3KDKyyVs',9,1,'2014-10-12 09:35:34'),('RyFAK72BJSCRryS',10,1,'2014-10-12 15:41:27'),('SLsDeXTyVT4y1PP',10,1,'2014-10-12 15:41:27'),('t3aoGmPzEMWyZmV',9,1,'2014-10-12 09:35:34'),('T5jcmrLkS/isHbT',10,1,'2014-10-12 15:41:27'),('tDMExh4WMjZVN3V',10,1,'2014-10-12 15:41:26'),('TdOl0IHFwTEo+h+',10,1,'2014-10-12 15:41:27'),('Tfd/T223aoT3P6N',9,1,'2014-10-12 09:35:34'),('ttxlrs27bp1O6RV',9,1,'2014-10-12 09:35:34'),('TxLBfwxe73/YMiO',10,1,'2014-10-12 15:41:27'),('Udjrv8B9ybfISV9',10,1,'2014-10-12 15:41:27'),('UEQe3vg1ec1ck8h',10,1,'2014-10-12 15:41:27'),('ULLWxTw09IbjEwt',10,1,'2014-10-12 15:41:27'),('UmBM5pcWpUnKQVL',9,1,'2014-10-12 09:35:34'),('umhIVotoVFbQeY9',10,1,'2014-10-12 15:41:27'),('UovgEQGf4vlocqp',9,1,'2014-10-12 09:35:34'),('vzvKpaPgt1319r7',10,1,'2014-10-12 15:41:27'),('W/BOtWgwNUrcg0h',9,1,'2014-10-12 09:35:34'),('W/p4kzvzbFYM5cB',9,1,'2014-10-12 09:35:34'),('W1YxYfa4rcgASA9',10,1,'2014-10-12 15:41:27'),('W25KBYf5b6l7HLm',9,1,'2014-10-12 09:35:34'),('w66IGGqgfs0x+DM',9,1,'2014-10-12 09:35:34'),('wbqxQU2iMHWiKgf',10,1,'2014-10-12 15:41:27'),('wfBV1NUczPYb1/5',10,1,'2014-10-12 15:41:27'),('wMeOYr15fRGBV0E',10,1,'2014-10-12 15:41:27'),('WNKzHKaqxChDpH6',9,1,'2014-10-12 09:35:34'),('WVPyXezW068BFDz',10,1,'2014-10-12 15:41:27'),('wXbkmcPIBthVNcP',10,1,'2014-10-12 15:41:27'),('XAwO4FHvnnSRhhP',9,1,'2014-10-12 09:35:34'),('xcULAt9/QSpFaVY',10,1,'2014-10-12 15:41:27'),('XgQnE2nezWi1+Tt',9,1,'2014-10-12 09:35:34'),('xhVlYJ6J2VeFTIu',10,1,'2014-10-12 15:41:27'),('XIOepGxGis7PhKH',10,1,'2014-10-12 15:41:27'),('XQIWR07Q/p/2cOG',9,1,'2014-10-12 09:35:34'),('xQJAmC8GQcPRgOg',9,1,'2014-10-12 09:35:34'),('XXsMTcVjbT2lOoR',9,1,'2014-10-12 09:35:34'),('yd9yGxU6CBPID2w',9,1,'2014-10-12 09:35:34'),('yED/vjWpP+jedWe',10,1,'2014-10-12 15:41:27'),('YLGTnpafGWjcBwe',9,1,'2014-10-12 09:35:34'),('yRqKQRq1bpRYN5H',10,1,'2014-10-12 15:41:27'),('yvET8C7tfccZAzI',10,1,'2014-10-12 15:41:27'),('Z2uRMavtY/TkADu',10,1,'2014-10-12 15:41:27'),('Z6FZ7fnXtyQlfiP',10,1,'2014-10-12 15:41:27'),('ZGaDWeqYnShWBAF',9,1,'2014-10-12 09:35:34'),('zKPOUxHYj89OP6J',9,1,'2014-10-12 09:35:34'),('ZMISI9Fn0QuBJ3f',9,1,'2014-10-12 09:35:34'),('zz+SZIMZgkczo1w',10,1,'2014-10-12 15:41:27');
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
  `t_confirmed` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`t_id`),
  KEY `t_account` (`t_account_from`),
  KEY `t_code` (`t_code`),
  KEY `transactions_ibfk_3` (`t_account_to`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`t_account_from`) REFERENCES `accounts` (`a_id`),
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`t_code`) REFERENCES `transaction_codes` (`tc_code`),
  CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`t_account_to`) REFERENCES `accounts` (`a_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,1,100,0,'alice0000000001','first deposit','2014-10-11 18:47:20',0,1),(2,1,20,0,'Alice 9320923','ur money','2014-10-11 22:36:09',2,1),(3,2,3,1,'Alice 9320923','ur money','2014-10-11 22:50:44',1,1),(6,9,-10,0,'/un1d1tQqJLZMUz','money','2014-10-12 12:01:09',1,1),(7,1,10,0,'/un1d1tQqJLZMUz','money','2014-10-12 12:01:09',9,1),(8,9,-5,0,'/un1d1tQqJLZMUz','money','2014-10-12 12:59:49',2,1),(9,2,5,0,'/un1d1tQqJLZMUz','money','2014-10-12 12:59:49',9,1),(10,9,-1,0,'/zSuqxo1OFahGd6','ola','2014-10-12 13:05:26',1,1),(11,1,1,0,'/zSuqxo1OFahGd6','ola','2014-10-12 13:05:26',9,1),(18,9,-1000,0,'+VRXMp40gK9H4Go','needs confirmation','2014-10-12 15:02:21',1,1),(19,1,1000,0,'+VRXMp40gK9H4Go','needs confirmation','2014-10-12 15:02:21',9,1),(20,9,-3,0,'0FzVRe04smR2MCq','blocked trans','2014-10-12 15:31:43',1,1),(21,1,3,0,'0FzVRe04smR2MCq','blocked trans','2014-10-12 15:31:43',9,1),(22,9,-2000,0,'0k/NcEEhzMtdeE8','new','2014-10-12 15:32:26',1,1),(23,1,2000,0,'0k/NcEEhzMtdeE8','new','2014-10-12 15:32:26',9,1),(24,10,-10,0,'/3nz+a+QBo3FPtK','first','2014-10-12 15:44:00',2,1),(25,2,10,0,'/3nz+a+QBo3FPtK','first','2014-10-12 15:44:00',10,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'alice','6384e2b2184bcbf58ecc',0,'alice@bank.com',0,'2014-10-11 17:47:15'),(2,'bob','9f9d51bc70ef21ca5c14f307980a29d8',1,'bob@banking.com',1,'2014-10-11 17:48:00'),(3,'kop','a5a7158118e59ee590424b55bb9aed17',0,'kop',0,'2014-10-11 22:54:34'),(4,'jjj','2af54305f183778d87de0c70c591fae4',0,'jjj',0,'2014-10-12 08:24:15'),(5,'nj','36b1c5be249ad6a533dcfa9654e73396',0,'nj',0,'2014-10-12 08:27:34'),(6,'op','7457cdd15d09bfc6c4dbb5d2b6f87390',0,'OP',0,'2014-10-12 08:30:11'),(7,'kkj','771f01104d905386a134a676167edccc',0,'kj',0,'2014-10-12 08:34:17'),(8,'sey','4a71369c79a5e5f9ee06f1cf2c4bac15',0,'sey',0,'2014-10-12 09:20:37'),(10,'seyk','202cb962ac59075b964b07152d234b70',1,'mohsen.ahmadv@gmail.com',0,'2014-10-12 09:35:34'),(11,'admin','202cb962ac59075b964b07152d234b70',1,'admin@g16banking.com',1,'2014-10-12 13:43:22'),(12,'alba','74efb8aac68e37c289dfcf260e19ab25',1,'alba',0,'2014-10-12 15:41:26'),(13,'behman','b6fa79f35323a85e281228230b491c9f',1,'sim@sim.de',1,'2014-10-17 00:27:56');
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

-- Dump completed on 2014-10-17  2:59:01
