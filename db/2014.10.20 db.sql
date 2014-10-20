 
-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `banking`;
CREATE DATABASE `banking` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `banking`;

DELIMITER ;;

CREATE PROCEDURE `process_transaction`()
BEGIN

   DECLARE exit handler for sqlexception
      BEGIN
          -- ERROR
        ROLLBACK;
      END;
 
    DECLARE exit handler for sqlwarning
      BEGIN
         -- WARNING
         ROLLBACK;
     END;

   IF @totalTransactions <> @validTransactionsCounter/2
   THEN
       ROLLBACK;
       select (@validTransactionsCounter);
   ELSE
      COMMIT;
       select (@totalTransactions);
   END IF;

END;;

DELIMITER ;

DROP TABLE IF EXISTS `accounts`;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `accounts` (`a_id`, `a_name`, `a_user`, `a_number`, `a_balance`, `a_timestamp`) VALUES
(1,	'alice personal acount',	1,	'1',	432,	'2014-10-11 17:55:40'),
(2,	'Starter',	2,	'2',	348,	'2014-10-11 22:34:16'),
(6,	'default account ',	7,	'kj',	0,	'2014-10-12 08:34:17'),
(7,	'default account ',	8,	'12356',	0,	'2014-10-12 09:20:37'),
(9,	'default account ',	10,	'123569',	-1019,	'2014-10-12 09:35:34'),
(10,	'default account ',	12,	'4444',	-10,	'2014-10-12 15:41:26');

DROP TABLE IF EXISTS `debug`;
CREATE TABLE `debug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `value` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `debug` (`id`, `name`, `value`, `timestamp`) VALUES
(44,	'@totalTransactions',	4,	'2014-10-20 20:41:32'),
(45,	'@sufficientBalance',	1,	'2014-10-20 20:41:32'),
(46,	'@validTransactionsCounter',	1,	'2014-10-20 20:41:32'),
(47,	'@transactionValid',	122,	'2014-10-20 20:41:32'),
(48,	'@transactionValid',	123,	'2014-10-20 20:41:32'),
(49,	'@codesDisabled',	1,	'2014-10-20 20:41:32'),
(50,	'@validTransactionsCounter',	2,	'2014-10-20 20:41:32'),
(51,	'@sufficientBalance',	1,	'2014-10-20 20:41:32'),
(52,	'@validTransactionsCounter',	3,	'2014-10-20 20:41:32'),
(53,	'@transactionValid',	124,	'2014-10-20 20:41:32'),
(54,	'@transactionValid',	125,	'2014-10-20 20:41:32'),
(55,	'@codesDisabled',	1,	'2014-10-20 20:41:32'),
(56,	'@validTransactionsCounter',	4,	'2014-10-20 20:41:32'),
(57,	'@sufficientBalance',	1,	'2014-10-20 20:41:32'),
(58,	'@validTransactionsCounter',	5,	'2014-10-20 20:41:32'),
(59,	'@transactionValid',	126,	'2014-10-20 20:41:32'),
(60,	'@transactionValid',	127,	'2014-10-20 20:41:32'),
(61,	'@codesDisabled',	1,	'2014-10-20 20:41:32'),
(62,	'@validTransactionsCounter',	6,	'2014-10-20 20:41:32'),
(63,	'@sufficientBalance',	1,	'2014-10-20 20:41:32'),
(64,	'@validTransactionsCounter',	7,	'2014-10-20 20:41:32'),
(65,	'@transactionValid',	128,	'2014-10-20 20:41:32'),
(66,	'@transactionValid',	129,	'2014-10-20 20:41:32'),
(67,	'@codesDisabled',	1,	'2014-10-20 20:41:32'),
(68,	'@validTransactionsCounter',	8,	'2014-10-20 20:41:32'),
(100,	'@totalTransactions',	4,	'2014-10-20 20:47:52'),
(101,	'@sufficientBalance',	1,	'2014-10-20 20:47:52'),
(102,	'@validTransactionsCounter',	1,	'2014-10-20 20:47:52'),
(103,	'@transactionValid',	140,	'2014-10-20 20:47:52'),
(104,	'@transactionValid',	141,	'2014-10-20 20:47:52'),
(105,	'@codesDisabled',	1,	'2014-10-20 20:47:52'),
(106,	'@validTransactionsCounter',	2,	'2014-10-20 20:47:52'),
(107,	'@sufficientBalance',	1,	'2014-10-20 20:47:52'),
(108,	'@validTransactionsCounter',	3,	'2014-10-20 20:47:52'),
(109,	'@transactionValid',	142,	'2014-10-20 20:47:52'),
(110,	'@transactionValid',	143,	'2014-10-20 20:47:52'),
(111,	'@codesDisabled',	1,	'2014-10-20 20:47:52'),
(112,	'@validTransactionsCounter',	4,	'2014-10-20 20:47:52'),
(113,	'@sufficientBalance',	1,	'2014-10-20 20:47:52'),
(114,	'@validTransactionsCounter',	5,	'2014-10-20 20:47:52'),
(115,	'@transactionValid',	144,	'2014-10-20 20:47:52'),
(116,	'@transactionValid',	145,	'2014-10-20 20:47:52'),
(117,	'@codesDisabled',	1,	'2014-10-20 20:47:52'),
(118,	'@validTransactionsCounter',	6,	'2014-10-20 20:47:52'),
(119,	'@sufficientBalance',	1,	'2014-10-20 20:47:52'),
(120,	'@validTransactionsCounter',	7,	'2014-10-20 20:47:52'),
(121,	'@transactionValid',	146,	'2014-10-20 20:47:52'),
(122,	'@transactionValid',	147,	'2014-10-20 20:47:52'),
(123,	'@codesDisabled',	1,	'2014-10-20 20:47:52'),
(124,	'@validTransactionsCounter',	8,	'2014-10-20 20:47:52');

DROP TABLE IF EXISTS `transactions`;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `transactions` (`t_id`, `t_account_from`, `t_amount`, `t_type`, `t_code`, `t_description`, `t_timestamp`, `t_account_to`, `t_confirmed`) VALUES
(140,	1,	-10,	0,	'alice0000000001',	'first deposit\n',	'2014-10-20 20:47:52',	2,	1),
(141,	2,	10,	0,	'alice0000000001',	'first deposit\n',	'2014-10-20 20:47:52',	1,	1),
(142,	1,	-20,	0,	'alice0000000002',	'second deposit\n',	'2014-10-20 20:47:52',	2,	1),
(143,	2,	20,	0,	'alice0000000002',	'second deposit\n',	'2014-10-20 20:47:52',	1,	1),
(144,	1,	-30,	0,	'alice0000000003',	'third deposit\n',	'2014-10-20 20:47:52',	2,	1),
(145,	2,	30,	0,	'alice0000000003',	'third deposit\n',	'2014-10-20 20:47:52',	1,	1),
(146,	1,	-40,	0,	'alice0000000004',	'fourth deposit\n',	'2014-10-20 20:47:52',	2,	1),
(147,	2,	40,	0,	'alice0000000004',	'fourth deposit\n',	'2014-10-20 20:47:52',	1,	1);

DROP TABLE IF EXISTS `transaction_codes`;
CREATE TABLE `transaction_codes` (
  `tc_code` varchar(15) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `tc_account` int(11) NOT NULL,
  `tc_active` tinyint(1) NOT NULL DEFAULT '1',
  `tc_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `transaction_codes` (`tc_code`, `tc_account`, `tc_active`, `tc_timestamp`) VALUES
('alice0000000001',	1,	1,	'0000-00-00 00:00:00'),
('alice0000000002',	1,	1,	'0000-00-00 00:00:00'),
('alice0000000003',	1,	1,	'2014-10-18 20:12:54'),
('alice0000000004',	1,	1,	'2014-10-18 20:13:02'),
('alice0000000005',	1,	1,	'2014-10-20 19:03:31'),
('alice0000000006',	1,	1,	'2014-10-20 19:03:43'),
('alice0000000007',	1,	1,	'2014-10-20 19:03:51'),
('alice0000000008',	1,	1,	'2014-10-20 19:03:58');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(20) DEFAULT NULL,
  `u_password` varchar(150) DEFAULT NULL,
  `u_active` tinyint(1) NOT NULL DEFAULT '0',
  `u_email` varchar(30) DEFAULT NULL,
  `u_type` int(11) NOT NULL DEFAULT '0',
  `u_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`u_id`, `u_name`, `u_password`, `u_active`, `u_email`, `u_type`, `u_timestamp`) VALUES
(1,	'alice',	'6384e2b2184bcbf58ecc',	0,	'alice@bank.com',	0,	'2014-10-11 17:47:15'),
(2,	'bob',	'9f9d51bc70ef21ca5c14f307980a29d8',	1,	'bob@banking.com',	1,	'2014-10-11 17:48:00'),
(3,	'kop',	'a5a7158118e59ee590424b55bb9aed17',	0,	'kop',	0,	'2014-10-11 22:54:34'),
(4,	'jjj',	'2af54305f183778d87de0c70c591fae4',	0,	'jjj',	0,	'2014-10-12 08:24:15'),
(5,	'nj',	'36b1c5be249ad6a533dcfa9654e73396',	0,	'nj',	0,	'2014-10-12 08:27:34'),
(6,	'op',	'7457cdd15d09bfc6c4dbb5d2b6f87390',	0,	'OP',	0,	'2014-10-12 08:30:11'),
(7,	'kkj',	'771f01104d905386a134a676167edccc',	0,	'kj',	0,	'2014-10-12 08:34:17'),
(8,	'sey',	'4a71369c79a5e5f9ee06f1cf2c4bac15',	0,	'sey',	0,	'2014-10-12 09:20:37'),
(10,	'seyk',	'202cb962ac59075b964b07152d234b70',	1,	'mohsen.ahmadv@gmail.com',	0,	'2014-10-12 09:35:34'),
(11,	'admin',	'202cb962ac59075b964b07152d234b70',	1,	'admin@g16banking.com',	1,	'2014-10-12 13:43:22'),
(12,	'alba',	'74efb8aac68e37c289dfcf260e19ab25',	1,	'alba',	0,	'2014-10-12 15:41:26'),
(13,	'behman',	'b6fa79f35323a85e281228230b491c9f',	1,	'sim@sim.de',	1,	'2014-10-17 00:27:56');

-- 2014-10-20 20:54:26