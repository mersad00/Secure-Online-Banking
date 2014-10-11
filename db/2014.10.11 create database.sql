 
-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `banking`;
CREATE DATABASE `banking` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `banking`;

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) NOT NULL DEFAULT 'default account ',
  `a_user` int(11) NOT NULL COMMENT 'owner of the account',
  `a_number` int(16) NOT NULL,
  `a_balance` double NOT NULL,
  `a_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`a_id`),
  KEY `a_user` (`a_user`),
  CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`a_user`) REFERENCES `users` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `accounts` (`a_id`, `a_name`, `a_user`, `a_number`, `a_balance`, `a_timestamp`) VALUES
(1,     'alice personal acount',        1,      1,      100,    '2014-10-11 17:55:40');

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_account` int(11) NOT NULL,
  `t_amount` int(11) NOT NULL,
  `t_type` int(11) NOT NULL,
  `t_code` varchar(15) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `t_description` varchar(250) NOT NULL,
  `t_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  KEY `t_account` (`t_account`),
  KEY `t_code` (`t_code`),
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`t_code`) REFERENCES `transaction_codes` (`tc_code`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`t_account`) REFERENCES `accounts` (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `transactions` (`t_id`, `t_account`, `t_amount`, `t_type`, `t_code`, `t_description`, `t_timestamp`) VALUES
(1,     1,      100,    0,      'alice0000000001',      'first deposit',        '2014-10-11 18:47:20');

DROP TABLE IF EXISTS `transaction_codes`;
CREATE TABLE `transaction_codes` (
  `tc_code` varchar(15) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `tc_account` int(11) NOT NULL,
  `tc_active` bit(1) NOT NULL DEFAULT b'1' COMMENT 'code is used/unused',
  `tc_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `transaction_codes` (`tc_code`, `tc_account`, `tc_active`, `tc_timestamp`) VALUES
('alice0000000001',     1,      CONV('0', 2, 10) + 0,   '0000-00-00 00:00:00'),
('alice0000000002',     1,      CONV('1', 2, 10) + 0,   '0000-00-00 00:00:00');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(20) DEFAULT NULL,
  `u_password` varchar(20) DEFAULT NULL,
  `u_active` bit(1) NOT NULL DEFAULT b'0',
  `u_email` varchar(30) DEFAULT NULL,
  `u_type` int(11) NOT NULL DEFAULT '0',
  `u_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`u_id`, `u_name`, `u_password`, `u_active`, `u_email`, `u_type`, `u_timestamp`) VALUES
(1,     'alice',        '6384e2b2184bcbf58ecc', CONV('1', 2, 10) + 0,   'alice@bank.com',       0,      '2014-10-11 17:47:15'),
(2,     'bob',  '9f9d51bc70ef21ca5c14', CONV('1', 2, 10) + 0,   'bob@banking.com',      1,      '2014-10-11 17:48:00');

-- 2014-10-11 18:50:08