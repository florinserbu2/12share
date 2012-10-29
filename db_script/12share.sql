-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 29, 2012 at 01:12 AM
-- Server version: 5.1.50
-- PHP Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `12share`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adv_advertisers_content`
--

DROP TABLE IF EXISTS `tbl_adv_advertisers_content`;
CREATE TABLE IF NOT EXISTS `tbl_adv_advertisers_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`,`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_adv_advertisers_content`
--

INSERT INTO `tbl_adv_advertisers_content` (`id`, `contact_id`, `content_id`) VALUES
(1, 3, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adv_prizes`
--

DROP TABLE IF EXISTS `tbl_adv_prizes`;
CREATE TABLE IF NOT EXISTS `tbl_adv_prizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advertiser_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `url` text NOT NULL,
  `items_no` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `items_no` (`items_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tbl_adv_prizes`
--

INSERT INTO `tbl_adv_prizes` (`id`, `advertiser_id`, `name`, `price`, `url`, `items_no`) VALUES
(1, 1, 'Apple Iphone 4', 30000, 'http://12trade.ro', 8),
(2, 3, 'Air 200XJD', 2, 'http://adidasi.ro', 10),
(3, 4, 'Apple Iphone 5', 50000, 'http://telefoane-cool.net', 4),
(4, 5, 'Ou ochi', 100, 'http://oua-eco.ro', 30),
(5, 6, 'Toshiba Sattelite x2', 40000, 'http://laptop-nou.com', 4),
(18, 4, 'Samsung Galaxy S-', 3000, 'http://telefoane-mobile.ro', 12),
(16, 3, 'Nike Air 2', 300, 'http://adidasi.ro/nike', 5),
(17, 1, 'Laptop lenovo E320', 3000, 'http://12trade.ro/how-works', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adv_prizes_content`
--

DROP TABLE IF EXISTS `tbl_adv_prizes_content`;
CREATE TABLE IF NOT EXISTS `tbl_adv_prizes_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`prize_id`,`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_adv_prizes_content`
--

INSERT INTO `tbl_adv_prizes_content` (`id`, `prize_id`, `content_id`) VALUES
(8, 1, 8),
(9, 1, 9),
(7, 17, 7),
(5, 2, 5),
(6, 16, 6),
(10, 4, 10),
(11, 18, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adv_prizes_urls`
--

DROP TABLE IF EXISTS `tbl_adv_prizes_urls`;
CREATE TABLE IF NOT EXISTS `tbl_adv_prizes_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_id` int(11) NOT NULL,
  `url_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prize_id` (`prize_id`,`url_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tbl_adv_prizes_urls`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbl_adv_urls`
--

DROP TABLE IF EXISTS `tbl_adv_urls`;
CREATE TABLE IF NOT EXISTS `tbl_adv_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advertiser_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advertiser_id` (`advertiser_id`,`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tbl_adv_urls`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbl_crm_contact_hub`
--

DROP TABLE IF EXISTS `tbl_crm_contact_hub`;
CREATE TABLE IF NOT EXISTS `tbl_crm_contact_hub` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `legal` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1=individual person, 2=company',
  `code` varchar(30) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=false, 1=true',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `type` (`legal`),
  KEY `status` (`status`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `tbl_crm_contact_hub`
--

INSERT INTO `tbl_crm_contact_hub` (`id`, `legal`, `code`, `status`, `deleted`) VALUES
(1, 2, NULL, 1, 0),
(2, 1, NULL, 1, 0),
(3, 2, NULL, 1, 0),
(4, 2, NULL, 1, 0),
(5, 2, NULL, 1, 0),
(6, 2, NULL, 1, 0),
(86, 1, NULL, 1, 0),
(87, 1, NULL, 1, 0),
(88, 1, NULL, 1, 0),
(89, 1, NULL, 1, 0),
(90, 1, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_crm_contact_user`
--

DROP TABLE IF EXISTS `tbl_crm_contact_user`;
CREATE TABLE IF NOT EXISTS `tbl_crm_contact_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` bigint(20) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `role_id` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'tbl_aim_system_roles',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `contact_id` (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `tbl_crm_contact_user`
--

INSERT INTO `tbl_crm_contact_user` (`id`, `contact_id`, `username`, `password`, `role_id`, `date_created`) VALUES
(52, 1, '12trade.ro', '4297f44b13955235245b2497399d7a93', 1, '2012-10-28 08:28:23'),
(53, 2, 'florin', '4297f44b13955235245b2497399d7a93', 2, '2012-10-28 08:28:23'),
(54, 3, 'adidasi.ro', '4297f44b13955235245b2497399d7a93', 1, '2012-10-28 11:10:47'),
(55, 4, 'telefoane-cool.net', '4297f44b13955235245b2497399d7a93', 1, '2012-10-28 11:10:47'),
(56, 5, 'oua-eco.ro', '4297f44b13955235245b2497399d7a93', 1, '2012-10-28 11:10:47'),
(57, 6, 'laptop-nou.com', '4297f44b13955235245b2497399d7a93', 1, '2012-10-28 11:19:22'),
(58, 86, 'florin2', '4297f44b13955235245b2497399d7a93', 2, '2012-10-28 17:46:25'),
(59, 87, 'florin3', '4297f44b13955235245b2497399d7a93', 2, '2012-10-28 17:50:15'),
(60, 88, 'florin4', '4297f44b13955235245b2497399d7a93', 2, '2012-10-28 23:52:15'),
(61, 89, 'user_1', '4297f44b13955235245b2497399d7a93', 2, '2012-10-29 00:51:42'),
(62, 90, 'user_2', '4297f44b13955235245b2497399d7a93', 2, '2012-10-29 00:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inv_uploaded_content`
--

DROP TABLE IF EXISTS `tbl_inv_uploaded_content`;
CREATE TABLE IF NOT EXISTS `tbl_inv_uploaded_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_type` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_inv_uploaded_content`
--

INSERT INTO `tbl_inv_uploaded_content` (`id`, `file_type`, `file_size`, `file_name`, `created_timestamp`) VALUES
(8, 'image/jpeg', '37.09kB', '586.JPEG', '2012-10-29 00:42:20'),
(3, 'image/jpeg', '124kB', '708.JPEG', '2012-10-28 19:54:06'),
(4, 'image/png', '19.31kB', '784.PNG', '2012-10-28 19:54:19'),
(5, 'image/jpeg', '44.1kB', '600.JPEG', '2012-10-28 23:49:00'),
(6, 'image/jpeg', '42.1kB', '799.JPEG', '2012-10-28 23:50:08'),
(7, 'image/jpeg', '108.68kB', '249.JPEG', '2012-10-29 00:09:12'),
(9, 'image/jpeg', '37.09kB', '671.JPEG', '2012-10-29 00:43:03'),
(10, 'image/jpeg', '9.05kB', '92.JPEG', '2012-10-29 00:59:28'),
(11, 'image/jpeg', '54.38kB', '131.JPEG', '2012-10-29 01:02:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pub_current_advertisers`
--

DROP TABLE IF EXISTS `tbl_pub_current_advertisers`;
CREATE TABLE IF NOT EXISTS `tbl_pub_current_advertisers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` int(11) NOT NULL,
  `advertiser_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `publisher_id` (`publisher_id`,`advertiser_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tbl_pub_current_advertisers`
--

INSERT INTO `tbl_pub_current_advertisers` (`id`, `publisher_id`, `advertiser_id`) VALUES
(4, 2, 3),
(5, 86, 1),
(6, 87, 1),
(8, 88, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pub_spent_credits`
--

DROP TABLE IF EXISTS `tbl_pub_spent_credits`;
CREATE TABLE IF NOT EXISTS `tbl_pub_spent_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `current_cost` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tbl_pub_spent_credits`
--

INSERT INTO `tbl_pub_spent_credits` (`id`, `publisher_id`, `prize_id`, `current_cost`, `timestamp`) VALUES
(1, 2, 2, 2, '2012-10-28 15:56:53'),
(7, 2, 16, 300, '2012-10-29 00:03:04'),
(9, 88, 4, 100, '2012-10-29 00:57:24'),
(5, 2, 2, 2, '2012-10-28 23:42:07'),
(8, 2, 2, 2, '2012-10-29 00:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sts_clicks`
--

DROP TABLE IF EXISTS `tbl_sts_clicks`;
CREATE TABLE IF NOT EXISTS `tbl_sts_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `cookie` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `tbl_sts_clicks`
--

INSERT INTO `tbl_sts_clicks` (`id`, `publisher_id`, `prize_id`, `cookie`, `timestamp`, `ip`) VALUES
(1, 2, 2, 'e28623dc9db91e57c5444ea89f31b0f4', '2012-10-28 15:26:04', '127.0.0.1'),
(2, 2, 2, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 15:26:40', '127.0.0.1'),
(3, 86, 1, '93db85e2209c13838ff95ccfa94cebd9', '2012-10-27 17:48:55', '127.0.0.1'),
(4, 87, 1, 'c81e728a9d4c2f636f067f89cc14862c', '2012-10-26 17:51:03', '127.0.0.1'),
(5, 87, 1, 'c81e728daaac2f636f067f89cc14862c', '2012-10-26 17:51:10', '127.0.0.1'),
(6, 86, 1, '93db85ed909c13838ff95ccfa94cebd9', '2012-10-28 17:51:55', '127.0.0.1'),
(7, 86, 1, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 17:52:05', '127.0.0.1'),
(8, 87, 1, 'c7e1249ffc03eb9ded908c236bd1996d', '2012-10-28 17:59:29', '127.0.0.1'),
(9, 2, 2, '93db85ed909c13838ff95ccfa94cebd9', '2012-10-28 18:10:49', '127.0.0.1'),
(10, 2, 2, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 18:11:10', '127.0.0.1'),
(11, 2, 2, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 18:11:17', '127.0.0.1'),
(12, 2, 2, 'e28623dc9dbse57c5444ea89f31b0f4', '2012-10-27 20:18:23', '127.0.0.1'),
(13, 2, 2, 'e28623dc9db91e57c5444ea89f31b0f4', '2012-10-28 20:20:28', '127.0.0.1'),
(14, 2, 2, 'e28623dc9db91e57c5444ea89f31b0f4', '2012-10-28 20:20:41', '127.0.0.1'),
(15, 2, 2, 'e28623dc9db91e57c5444ea89f31b0f4', '2012-10-28 20:22:07', '127.0.0.1'),
(16, 2, 2, 'e28623dc9db91e57c5444ea89f31b0f4', '2012-10-28 20:22:31', '127.0.0.1'),
(17, 86, 1, 'e28623dc9db91e5555444ea89f31b0f4', '2012-10-25 21:33:51', '127.0.0.1'),
(18, 2, 2, 'e28623dc9db91e57c5444ea89f31b0f4', '2012-10-28 23:26:46', '127.0.0.1'),
(19, 2, 2, '93db85ed909c13838ff95ccfa94cebd9', '2012-10-28 23:27:02', '127.0.0.1'),
(20, 2, 2, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 23:27:31', '127.0.0.1'),
(21, 2, 2, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 23:28:39', '127.0.0.1'),
(22, 2, 2, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 23:30:14', '127.0.0.1'),
(23, 2, 16, '82aa4b0af34c2313a562076992e50aa3', '2012-10-28 23:50:50', '127.0.0.1'),
(24, 2, 16, 'c81e728d9d4c2f636f067f89cc14862c', '2012-10-28 23:51:42', '127.0.0.1'),
(31, 88, 4, 'e28623dc9db91e57c5444ea89f31b0f4', '2012-10-29 00:55:43', '127.0.0.1'),
(30, 2, 2, '82aa4b0af34c2313a562076992e50aa3', '2012-10-29 00:43:39', '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sts_convs`
--

DROP TABLE IF EXISTS `tbl_sts_convs`;
CREATE TABLE IF NOT EXISTS `tbl_sts_convs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `stats_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `prize_id` (`prize_id`,`publisher_id`,`stats_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tbl_sts_convs`
--

INSERT INTO `tbl_sts_convs` (`id`, `prize_id`, `publisher_id`, `stats_id`, `timestamp`) VALUES
(2, 2, 2, 1, '2012-10-28 20:54:46'),
(5, 2, 2, 1, '2012-10-28 21:02:55'),
(6, 2, 2, 1, '2012-10-28 21:03:05'),
(7, 1, 86, 17, '2012-10-28 21:34:37'),
(10, 4, 88, 31, '2012-10-29 00:56:35');
