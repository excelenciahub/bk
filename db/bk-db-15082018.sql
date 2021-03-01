-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.21 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table test_amarshi.bk_admin_master
CREATE TABLE IF NOT EXISTS `bk_admin_master` (
  `admin_id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_admin_master: ~1 rows (approximately)
/*!40000 ALTER TABLE `bk_admin_master` DISABLE KEYS */;
INSERT INTO `bk_admin_master` (`admin_id`, `first_name`, `last_name`, `email`, `image`, `username`, `password`, `status`, `is_delete`, `created_time`, `created_ip`, `created_by`, `modified_time`, `modified_ip`, `modified_by`) VALUES
	(1, 'Super', 'Admin', 'superadmin@gmail.com', NULL, 'superadmin', '4297f44b13955235245b2497399d7a93', 1, 0, '2018-03-15 21:36:40', '', 0, '2018-04-10 19:54:14', '192.168.30.8', 1);
/*!40000 ALTER TABLE `bk_admin_master` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_category_master
CREATE TABLE IF NOT EXISTS `bk_category_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `slug` varchar(70) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` mediumtext,
  `keywords` mediumtext,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`,`slug`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_category_master: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_category_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_category_master` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_city_master
CREATE TABLE IF NOT EXISTS `bk_city_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `slug` varchar(70) NOT NULL,
  `state_id` int(10) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`,`slug`),
  KEY `state_id` (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_city_master: ~2 rows (approximately)
/*!40000 ALTER TABLE `bk_city_master` DISABLE KEYS */;
INSERT INTO `bk_city_master` (`id`, `name`, `slug`, `state_id`, `status`, `is_delete`, `created_time`, `created_ip`, `created_by`, `modified_time`, `modified_ip`, `modified_by`) VALUES
	(1, 'Rajkot', 'rajkot', 1, 1, 0, '2018-05-04 21:34:48', '127.0.0.1', 1, '2018-08-15 17:18:15', '192.168.30.8', 1),
	(2, 'Ahmedabad', 'ahmedabad', 1, 1, 0, '2018-08-15 17:18:39', '192.168.30.8', 1, '2018-08-15 17:18:39', '', 0);
/*!40000 ALTER TABLE `bk_city_master` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_db_exports
CREATE TABLE IF NOT EXISTS `bk_db_exports` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table test_amarshi.bk_db_exports: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_db_exports` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_db_exports` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_order_master
CREATE TABLE IF NOT EXISTS `bk_order_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `total_products` int(10) NOT NULL DEFAULT '0',
  `total_qty` int(10) NOT NULL DEFAULT '0',
  `invoice` varchar(50) DEFAULT NULL,
  `payment_type` int(2) DEFAULT '0' COMMENT '1=> Instant, 2=> Future',
  `payment_date` date DEFAULT NULL,
  `payment_payed_date` date DEFAULT NULL,
  `total_amount` float(10,2) DEFAULT '0.00',
  `note` mediumtext,
  `payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `invoice` (`invoice`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_order_master: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_order_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_order_master` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_order_products
CREATE TABLE IF NOT EXISTS `bk_order_products` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `product_id` int(10) NOT NULL DEFAULT '0',
  `qty` int(10) NOT NULL DEFAULT '0',
  `price` float(10,2) NOT NULL DEFAULT '0.00',
  `payable_amount` float(10,2) NOT NULL DEFAULT '0.00',
  `prices` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_order_products: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_order_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_order_products` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_product_master
CREATE TABLE IF NOT EXISTS `bk_product_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `category_id` int(10) DEFAULT '0',
  `slug` varchar(70) NOT NULL,
  `price` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `other_images` text,
  `title` varchar(100) DEFAULT NULL,
  `description` mediumtext,
  `keywords` mediumtext,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`,`slug`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_product_master: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_product_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_product_master` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_product_price
CREATE TABLE IF NOT EXISTS `bk_product_price` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) DEFAULT '0',
  `min_qty` int(10) DEFAULT NULL,
  `max_qty` int(10) DEFAULT NULL,
  `instant_price` float(10,2) DEFAULT NULL,
  `future_price` float(10,2) DEFAULT NULL,
  `is_delete` tinyint(1) DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_product_price: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_product_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_product_price` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_site_config
CREATE TABLE IF NOT EXISTS `bk_site_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `option` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `comment` text,
  `editable` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table test_amarshi.bk_site_config: ~14 rows (approximately)
/*!40000 ALTER TABLE `bk_site_config` DISABLE KEYS */;
INSERT INTO `bk_site_config` (`id`, `option`, `value`, `comment`, `editable`) VALUES
	(1, 'SITE_NAME', 'Balkrushna Proteins', 'Site name', 1),
	(2, 'SITE_SHORT_NAME', 'BK', 'Site short name', 1),
	(3, 'RECORD_PER_PAGE', '10', 'Listing record per page', 1),
	(4, 'NOIMAGEFOUND', 'notfound.gif', 'No image found', 0),
	(5, 'INVOICE', '0', 'Invoice sequence', 0),
	(6, 'DEVELOPED_BY', 'Amarshi Jamod', 'Developer name', 0),
	(7, 'DEVELOPER_EMAIL', 'amarshi.jamod@gmail.com', 'Developer email', 0),
	(8, 'VERSION', '1.4.0', 'Version', 0),
	(9, 'GST_NO', '123', 'GST No', 1),
	(10, 'PAN_NO', '123', 'PAN No', 1),
	(11, 'MOBILE_NO', '1234567890,7894561230', 'Mobile No (, Seperated)', 1),
	(12, 'ADDRESS', 'Near Swaminarayan Mandir, At. Patrapasar, Ta. Dist. Junagadh.', 'Full Address', 1),
	(13, 'INFO', 'information', 'Invoice info (User HTML tag for formatting)', 1),
	(14, 'INVOICE_DISCLAIMER', 'disclaimer 1, desclaimer 2', 'Invoice desclaimer (, Seperated)', 1);
/*!40000 ALTER TABLE `bk_site_config` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_site_config_log
CREATE TABLE IF NOT EXISTS `bk_site_config_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `option` varchar(100) DEFAULT NULL,
  `value` text,
  `comment` text,
  `editable` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '0=>In Active, 1=> Active ',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '0=>Not Deleted, 1=> Deleted',
  `created_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_time` timestamp NULL DEFAULT NULL,
  `modified_ip` varchar(50) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_site_config_log: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_site_config_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_site_config_log` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_state_master
CREATE TABLE IF NOT EXISTS `bk_state_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `slug` varchar(70) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`,`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_state_master: ~1 rows (approximately)
/*!40000 ALTER TABLE `bk_state_master` DISABLE KEYS */;
INSERT INTO `bk_state_master` (`id`, `name`, `code`, `slug`, `status`, `is_delete`, `created_time`, `created_ip`, `created_by`, `modified_time`, `modified_ip`, `modified_by`) VALUES
	(1, 'Gujarat', 'GJ', 'gujarat', 1, 0, '2018-08-15 16:35:19', '', 0, '2018-08-15 17:25:17', '192.168.30.8', 1);
/*!40000 ALTER TABLE `bk_state_master` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_stop_words
CREATE TABLE IF NOT EXISTS `bk_stop_words` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `word` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0=>In Active, 1=> Active ',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '0=>Not Deleted, 1=> Deleted',
  `created_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_time` timestamp NULL DEFAULT NULL,
  `modified_ip` varchar(50) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `word` (`word`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=latin1;

-- Dumping data for table test_amarshi.bk_stop_words: ~175 rows (approximately)
/*!40000 ALTER TABLE `bk_stop_words` DISABLE KEYS */;
INSERT INTO `bk_stop_words` (`id`, `word`, `status`, `is_delete`, `created_time`, `created_ip`, `created_by`, `modified_time`, `modified_ip`, `modified_by`) VALUES
	(1, 'a', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(2, 'about', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(3, 'above', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(4, 'after', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(5, 'again', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(6, 'against', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(7, 'all', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(8, 'am', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(9, 'an', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(10, 'and', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(11, 'any', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(12, 'are', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(13, 'aren\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(14, 'as', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(15, 'at', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(16, 'be', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(17, 'because', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(18, 'been', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(19, 'before', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(20, 'being', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(21, 'below', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(22, 'between', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(23, 'both', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(24, 'but', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(25, 'by', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(26, 'can\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(27, 'cannot', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(28, 'could', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(29, 'couldn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(30, 'did', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(31, 'didn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(32, 'do', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(33, 'does', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(34, 'doesn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(35, 'doing', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(36, 'don\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(37, 'down', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(38, 'during', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(39, 'each', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(40, 'few', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(41, 'for', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(42, 'from', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(43, 'further', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(44, 'had', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(45, 'hadn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(46, 'has', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(47, 'hasn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(48, 'have', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(49, 'haven\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(50, 'having', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(51, 'he', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(52, 'he\'d', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(53, 'he\'ll', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(54, 'he\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(55, 'her', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(56, 'here', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(57, 'here\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(58, 'hers', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(59, 'herself', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(60, 'him', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(61, 'himself', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(62, 'his', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(63, 'how', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(64, 'how\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(65, 'i', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(66, 'i\'d', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(67, 'i\'ll', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(68, 'i\'m', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(69, 'i\'ve', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(70, 'if', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(71, 'in', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(72, 'into', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(73, 'is', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(74, 'isn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(75, 'it', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(76, 'it\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(77, 'its', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(78, 'itself', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(79, 'let\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(80, 'me', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(81, 'more', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(82, 'most', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(83, 'mustn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(84, 'my', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(85, 'myself', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(86, 'no', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(87, 'nor', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(88, 'not', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(89, 'of', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(90, 'off', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(91, 'on', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(92, 'once', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(93, 'only', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(94, 'or', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(95, 'other', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(96, 'ought', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(97, 'our', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(98, 'ours', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(99, 'ourselves', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(100, 'out', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(101, 'over', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(102, 'own', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(103, 'same', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(104, 'shan\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(105, 'she', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(106, 'she\'d', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(107, 'she\'ll', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(108, 'she\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(109, 'should', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(110, 'shouldn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(111, 'so', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(112, 'some', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(113, 'such', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(114, 'than', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(115, 'that', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(116, 'that\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(117, 'the', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(118, 'their', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(119, 'theirs', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(120, 'them', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(121, 'themselves', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(122, 'then', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(123, 'there', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(124, 'there\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(125, 'these', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(126, 'they', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(127, 'they\'d', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(128, 'they\'ll', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(129, 'they\'re', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(130, 'they\'ve', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(131, 'this', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(132, 'those', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(133, 'through', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(134, 'to', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(135, 'too', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(136, 'under', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(137, 'until', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(138, 'up', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(139, 'very', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(140, 'was', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(141, 'wasn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(142, 'we', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(143, 'we\'d', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(144, 'we\'ll', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(145, 'we\'re', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(146, 'we\'ve', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(147, 'were', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(148, 'weren\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(149, 'what', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(150, 'what\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(151, 'when', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(152, 'when\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(153, 'where', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(154, 'where\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(155, 'which', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(156, 'while', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(157, 'who', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(158, 'who\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(159, 'whom', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(160, 'why', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(161, 'why\'s', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(162, 'with', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(163, 'won\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(164, 'would', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(165, 'wouldn\'t', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(166, 'you', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(167, 'you\'d', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(168, 'you\'ll', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(169, 'you\'re', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(170, 'you\'ve', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(171, 'your', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(172, 'yours', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(173, 'yourself', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(174, 'yourselves', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1),
	(175, 'zero', 1, 0, '2018-02-01 15:23:20', '192.168.30.8', 1, '2018-02-01 18:38:35', '192.168.30.8', 1);
/*!40000 ALTER TABLE `bk_stop_words` ENABLE KEYS */;

-- Dumping structure for table test_amarshi.bk_user_master
CREATE TABLE IF NOT EXISTS `bk_user_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `mobile_no` varchar(100) DEFAULT NULL,
  `gst_no` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `city_id` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(20) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_ip` varchar(20) NOT NULL,
  `modified_by` int(10) NOT NULL,
  PRIMARY KEY (`id`,`slug`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table test_amarshi.bk_user_master: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user_master` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
