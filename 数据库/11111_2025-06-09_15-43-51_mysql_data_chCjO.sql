-- MySQL dump 10.13  Distrib 5.6.50, for Linux (x86_64)
--
-- Host: localhost    Database: 11111
-- ------------------------------------------------------
-- Server version	5.6.50-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `drama`
--

DROP TABLE IF EXISTS `drama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drama` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '短剧名称',
  `type` varchar(50) NOT NULL COMMENT '分类',
  `desc` text NOT NULL COMMENT '简介',
  `img` varchar(255) NOT NULL COMMENT '封面图片',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '网盘销售价格',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '网盘普及版价格',
  `cost2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '网盘专业版价格',
  `bfprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '整部销售价格',
  `bfcost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '整部普及版价格',
  `bfcost2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '整部专业版价格',
  `download_url` varchar(255) NOT NULL COMMENT '网盘地址',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热门',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `is_hot` (`is_hot`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短剧表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drama`
--

LOCK TABLES `drama` WRITE;
/*!40000 ALTER TABLE `drama` DISABLE KEYS */;
/*!40000 ALTER TABLE `drama` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drama_comment`
--

DROP TABLE IF EXISTS `drama_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drama_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `drama_id` int(11) NOT NULL COMMENT '短剧ID',
  `content` text NOT NULL COMMENT '评论内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `drama_id` (`drama_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短剧评论表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drama_comment`
--

LOCK TABLES `drama_comment` WRITE;
/*!40000 ALTER TABLE `drama_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `drama_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drama_order`
--

DROP TABLE IF EXISTS `drama_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drama_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(50) NOT NULL COMMENT '订单号',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `drama_id` int(11) NOT NULL COMMENT '短剧ID',
  `amount` decimal(10,2) NOT NULL COMMENT '支付金额',
  `type` varchar(20) NOT NULL COMMENT '订单类型',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `paytime` int(11) DEFAULT NULL COMMENT '支付时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `user_id` (`user_id`),
  KEY `drama_id` (`drama_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短剧订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drama_order`
--

LOCK TABLES `drama_order` WRITE;
/*!40000 ALTER TABLE `drama_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `drama_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drama_type`
--

DROP TABLE IF EXISTS `drama_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drama_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='短剧分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drama_type`
--

LOCK TABLES `drama_type` WRITE;
/*!40000 ALTER TABLE `drama_type` DISABLE KEYS */;
INSERT INTO `drama_type` VALUES (1,'都市情感','都市情感类短剧',1,1743007591),(2,'古装武侠','古装武侠类短剧',2,1743007591),(3,'悬疑推理','悬疑推理类短剧',3,1743007591),(4,'青春校园','青春校园类短剧',4,1743007591),(5,'科幻奇幻','科幻奇幻类短剧',5,1743007591),(6,'其他','其他类型短剧',6,1743007591);
/*!40000 ALTER TABLE `drama_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hm_video`
--

DROP TABLE IF EXISTS `hm_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hm_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '视频ID',
  `name` varchar(100) NOT NULL COMMENT '集数名称',
  `url` varchar(255) NOT NULL COMMENT '播放地址',
  `num` int(11) NOT NULL COMMENT '集数',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频详情表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hm_video`
--

LOCK TABLES `hm_video` WRITE;
/*!40000 ALTER TABLE `hm_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `hm_video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hm_videolist`
--

DROP TABLE IF EXISTS `hm_videolist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hm_videolist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '视频名称',
  `desc` text COMMENT '视频描述',
  `img` varchar(255) NOT NULL COMMENT '封面图片',
  `type` int(11) NOT NULL COMMENT '分类ID',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价格',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价格',
  `bfcost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '播放成本价格',
  `bfprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '播放销售价格',
  `download_url` varchar(255) DEFAULT NULL COMMENT '下载地址',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频列表表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hm_videolist`
--

LOCK TABLES `hm_videolist` WRITE;
/*!40000 ALTER TABLE `hm_videolist` DISABLE KEYS */;
/*!40000 ALTER TABLE `hm_videolist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hm_videotype`
--

DROP TABLE IF EXISTS `hm_videotype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hm_videotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hm_videotype`
--

LOCK TABLES `hm_videotype` WRITE;
/*!40000 ALTER TABLE `hm_videotype` DISABLE KEYS */;
/*!40000 ALTER TABLE `hm_videotype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_agent_level`
--

DROP TABLE IF EXISTS `pre_agent_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_agent_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '等级名称',
  `commission_rate` decimal(10,2) NOT NULL COMMENT '佣金比例',
  `upgrade_amount` decimal(10,2) NOT NULL COMMENT '升级金额',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='代理等级';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_agent_level`
--

LOCK TABLES `pre_agent_level` WRITE;
/*!40000 ALTER TABLE `pre_agent_level` DISABLE KEYS */;
INSERT INTO `pre_agent_level` VALUES (1,'普通代理',5.00,0.00,1,'2025-03-24 03:31:17'),(2,'高级代理',8.00,10000.00,1,'2025-03-24 03:31:17'),(3,'VIP代理',12.00,50000.00,1,'2025-03-24 03:31:17'),(4,'普通代理',5.00,0.00,1,'2025-03-24 03:38:40'),(5,'高级代理',8.00,10000.00,1,'2025-03-24 03:38:40'),(6,'VIP代理',12.00,50000.00,1,'2025-03-24 03:38:40');
/*!40000 ALTER TABLE `pre_agent_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_agent_promotion`
--

DROP TABLE IF EXISTS `pre_agent_promotion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_agent_promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL COMMENT '代理ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` decimal(10,2) NOT NULL COMMENT '订单金额',
  `commission` decimal(10,2) NOT NULL COMMENT '佣金金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理推广记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_agent_promotion`
--

LOCK TABLES `pre_agent_promotion` WRITE;
/*!40000 ALTER TABLE `pre_agent_promotion` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_agent_promotion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_distribution_level`
--

DROP TABLE IF EXISTS `pre_distribution_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_distribution_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `points` int(11) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_points` (`points`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_distribution_level`
--

LOCK TABLES `pre_distribution_level` WRITE;
/*!40000 ALTER TABLE `pre_distribution_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_distribution_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_drama`
--

DROP TABLE IF EXISTS `pre_drama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_drama` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `cover` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_episodes` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_drama`
--

LOCK TABLES `pre_drama` WRITE;
/*!40000 ALTER TABLE `pre_drama` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_drama` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_drama_episode`
--

DROP TABLE IF EXISTS `pre_drama_episode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_drama_episode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drama_id` int(11) NOT NULL,
  `episode_no` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_drama_id` (`drama_id`),
  KEY `idx_episode_no` (`episode_no`),
  CONSTRAINT `fk_drama_episode_drama_id` FOREIGN KEY (`drama_id`) REFERENCES `pre_drama` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_drama_episode`
--

LOCK TABLES `pre_drama_episode` WRITE;
/*!40000 ALTER TABLE `pre_drama_episode` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_drama_episode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_favorites`
--

DROP TABLE IF EXISTS `pre_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `pid` int(11) NOT NULL COMMENT '短剧ID',
  `addtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_pid` (`uid`,`pid`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='收藏';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_favorites`
--

LOCK TABLES `pre_favorites` WRITE;
/*!40000 ALTER TABLE `pre_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_history`
--

DROP TABLE IF EXISTS `pre_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `pid` int(11) NOT NULL COMMENT '短剧ID',
  `vid` int(11) NOT NULL COMMENT '剧集ID',
  `addtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_pid_vid` (`uid`,`pid`,`vid`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  KEY `vid` (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='观看历史';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_history`
--

LOCK TABLES `pre_history` WRITE;
/*!40000 ALTER TABLE `pre_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_progress`
--

DROP TABLE IF EXISTS `pre_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `pid` int(11) NOT NULL COMMENT '短剧ID',
  `vid` int(11) NOT NULL COMMENT '剧集ID',
  `progress` int(11) NOT NULL DEFAULT '0' COMMENT '播放进度(秒)',
  `addtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_pid_vid` (`uid`,`pid`,`vid`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  KEY `vid` (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='播放进度';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_progress`
--

LOCK TABLES `pre_progress` WRITE;
/*!40000 ALTER TABLE `pre_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_system_settings`
--

DROP TABLE IF EXISTS `pre_system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `description` text,
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_system_settings`
--

LOCK TABLES `pre_system_settings` WRITE;
/*!40000 ALTER TABLE `pre_system_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_user_level`
--

DROP TABLE IF EXISTS `pre_user_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_user_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '1.00',
  `points` int(11) NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_points` (`points`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_user_level`
--

LOCK TABLES `pre_user_level` WRITE;
/*!40000 ALTER TABLE `pre_user_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_user_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_video`
--

DROP TABLE IF EXISTS `pre_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '短剧ID',
  `num` int(11) NOT NULL COMMENT '剧集号',
  `url` varchar(255) NOT NULL COMMENT '视频地址',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `cost` decimal(10,2) DEFAULT '0.00' COMMENT '成本价',
  `cost2` decimal(10,2) DEFAULT '0.00' COMMENT '成本价2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pid_num` (`pid`,`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='剧集列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_video`
--

LOCK TABLES `pre_video` WRITE;
/*!40000 ALTER TABLE `pre_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_videotype`
--

DROP TABLE IF EXISTS `pre_videotype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_videotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:0禁用,1正常',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `uptime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_videotype`
--

LOCK TABLES `pre_videotype` WRITE;
/*!40000 ALTER TABLE `pre_videotype` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_videotype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_account`
--

DROP TABLE IF EXISTS `shua_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `permission` text,
  `addtime` datetime DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_account`
--

LOCK TABLES `shua_account` WRITE;
/*!40000 ALTER TABLE `shua_account` DISABLE KEYS */;
INSERT INTO `shua_account` VALUES (1,'yejianbo','yejianbo','order,refund,shop,price,faka,site,tixian,workorder,message,article,shequ,set,account','2022-03-22 05:06:17','2022-11-26 04:54:39',1);
/*!40000 ALTER TABLE `shua_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_admin`
--

DROP TABLE IF EXISTS `shua_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `lasttime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_admin`
--

LOCK TABLES `shua_admin` WRITE;
/*!40000 ALTER TABLE `shua_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_advanced_settings`
--

DROP TABLE IF EXISTS `shua_advanced_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_advanced_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '设置名称',
  `value` text NOT NULL COMMENT '设置值',
  `description` varchar(255) DEFAULT NULL COMMENT '设置说明',
  `type` varchar(20) NOT NULL COMMENT '设置类型',
  `group` varchar(20) NOT NULL COMMENT '设置分组',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  `updatetime` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_advanced_settings`
--

LOCK TABLES `shua_advanced_settings` WRITE;
/*!40000 ALTER TABLE `shua_advanced_settings` DISABLE KEYS */;
INSERT INTO `shua_advanced_settings` VALUES (1,'virtual_order','1',NULL,'','',0,1,NULL,'2025-04-27 22:42:35'),(2,'order_display','1',NULL,'','',0,1,NULL,'2025-04-27 22:42:37');
/*!40000 ALTER TABLE `shua_advanced_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_agent_level`
--

DROP TABLE IF EXISTS `shua_agent_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_agent_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '等级名称',
  `commission_rate` decimal(5,2) NOT NULL COMMENT '佣金比例',
  `upgrade_amount` decimal(10,2) NOT NULL COMMENT '升级金额',
  `description` text COMMENT '等级描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='代理等级表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_agent_level`
--

LOCK TABLES `shua_agent_level` WRITE;
/*!40000 ALTER TABLE `shua_agent_level` DISABLE KEYS */;
INSERT INTO `shua_agent_level` VALUES (1,'普通代理',5.00,0.00,'默认代理等级'),(2,'高级代理',8.00,1000.00,'累计佣金达到1000元可升级'),(3,'VIP代理',12.00,5000.00,'累计佣金达到5000元可升级'),(4,'普通代理',5.00,0.00,'默认代理等级'),(5,'高级代理',8.00,1000.00,'累计佣金达到1000元可升级'),(6,'VIP代理',12.00,5000.00,'累计佣金达到5000元可升级'),(7,'普通代理',5.00,0.00,'默认代理等级'),(8,'高级代理',8.00,1000.00,'累计佣金达到1000元可升级'),(9,'VIP代理',12.00,5000.00,'累计佣金达到5000元可升级'),(10,'普通代理',5.00,0.00,'默认代理等级'),(11,'高级代理',8.00,1000.00,'累计佣金达到1000元可升级'),(12,'VIP代理',12.00,5000.00,'累计佣金达到5000元可升级'),(13,'普通代理',5.00,0.00,'默认代理等级'),(14,'高级代理',8.00,1000.00,'累计佣金达到1000元可升级'),(15,'VIP代理',12.00,5000.00,'累计佣金达到5000元可升级');
/*!40000 ALTER TABLE `shua_agent_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_agent_promotion`
--

DROP TABLE IF EXISTS `shua_agent_promotion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_agent_promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL COMMENT '代理ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `promotion_time` datetime NOT NULL COMMENT '推广时间',
  `commission_amount` decimal(10,2) NOT NULL COMMENT '佣金金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理推广记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_agent_promotion`
--

LOCK TABLES `shua_agent_promotion` WRITE;
/*!40000 ALTER TABLE `shua_agent_promotion` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_agent_promotion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_apps`
--

DROP TABLE IF EXISTS `shua_apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `taskid` int(11) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(128) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `icon` varchar(256) DEFAULT NULL,
  `package` varchar(128) DEFAULT NULL,
  `android_url` varchar(256) DEFAULT NULL,
  `ios_url` varchar(256) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_apps`
--

LOCK TABLES `shua_apps` WRITE;
/*!40000 ALTER TABLE `shua_apps` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_apps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_article`
--

DROP TABLE IF EXISTS `shua_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `addtime` datetime NOT NULL,
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_article`
--

LOCK TABLES `shua_article` WRITE;
/*!40000 ALTER TABLE `shua_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_banners`
--

DROP TABLE IF EXISTS `shua_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `image` varchar(255) NOT NULL COMMENT '图片地址',
  `url` varchar(255) DEFAULT NULL COMMENT '链接地址',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0禁用 1启用',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP,
  `uptime` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_banners`
--

LOCK TABLES `shua_banners` WRITE;
/*!40000 ALTER TABLE `shua_banners` DISABLE KEYS */;
INSERT INTO `shua_banners` VALUES (1,'热门短剧','https://example.com/banner1.jpg','/?mod=play&id=1',1,1,'2024-03-29 16:20:00',NULL),(2,'VIP专区','https://example.com/banner2.jpg','/?mod=vip',2,1,'2024-03-29 16:20:00',NULL),(3,'新剧上线','https://example.com/banner3.jpg','/?mod=play&id=2',3,1,'2024-03-29 16:20:00',NULL);
/*!40000 ALTER TABLE `shua_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_cache`
--

DROP TABLE IF EXISTS `shua_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_cache` (
  `k` varchar(32) NOT NULL,
  `v` longtext,
  `expire` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_cache`
--

LOCK TABLES `shua_cache` WRITE;
/*!40000 ALTER TABLE `shua_cache` DISABLE KEYS */;
INSERT INTO `shua_cache` VALUES ('config','a:361:{i:404;s:63:\"平台维护中！最快明天恢复最迟后天恢复！！！\";s:10:\"adminlogin\";s:19:\"2025-05-13 15:06:44\";s:9:\"admin_pwd\";s:11:\"wangji520..\";s:10:\"admin_user\";s:5:\"admin\";s:5:\"alert\";s:762:\"1.请你知悉并确认，会员服务为虚拟产品，开通分站会员服务后，若你中途主动取消服务或要求终止资格，你已支付的开通分站会员服务费用将不予退还，不支持退款。\r\n2.如你有其他与服务售后相关的问题，可以通过平台工单形式联系客服进行反馈。\r\n3.如使用会员服务过程中出现纠纷，你应当与本平台友好协商解决。\r\n4.建立合作关系后的合作有效期内，客户购买产品课程并支付订单为有效订单，您可获得推广提成。\r\n5.开通分站后务必在后台准确填写收款结算信息，佣金支持随时可结算，提现后，推广提成会结算至后台填写的收款账户中，推广费用以实际到账金额为准。\";s:11:\"alipay2_api\";s:1:\"0\";s:14:\"alipay_account\";s:0:\"\";s:10:\"alipay_api\";s:1:\"3\";s:12:\"alipay_appid\";s:16:\"2021003142611405\";s:10:\"alipay_key\";s:0:\"\";s:10:\"alipay_pid\";s:0:\"\";s:17:\"alipay_privatekey\";s:1624:\"MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCFgYriai5wrERfnxcokqRFYtuXwRteuGMd7Ail1YFGqs3RgRbTo8ecpnG+9hvsPvp7qteP4WXefI6cD/4Z7T/SsaE3teYccz/k+EV/2AzXiUHmL2gdh34ZKVQSU43EG8qNWubDaLKxCQINNtAVDjUVzChcaX6PfPv2LHco6wzSuO2n7GXj+U6WlNL4cLor5dgmuz8sLgc1Ubf0R2jCTkBAiSYeW5zaqIR/d++EBJ1KUE8CKJw/FjxOEu1LhDm1C01aUthQmrY6b9EVW9JtpPVobbV7hsXbYSZtZlvbXX2a623lRwhrST2xu19etomRKMF56JgdlOZ7H3Yxsx/6CnBTAgMBAAECggEAPEtbDBJkUuKaJo1VCi/gHK4e+pRt5gNkUDkvVqXsMpwlQ/8G7iJDnk3VhSblSXbP7VYk8IgdV7PtPDJE0jTB5EfwxZZTDHpIWwJ9cNWERztoFatHXD67BmYR4Az4M59RhTGOkqYQPcARuyIMK9Y0x1liyQmj7O3HZVCDqGkva2MHtpoYCHcBN9Sl0xwqenYHNGcl8L4f9kMRJxlnetSUgpawJxESYJPemX7iJCgtbge+rN24bmUQUQLwZ9gm59Ovca58vdpVXaPmLym1tlkV8zHuaHzbOsrJdmUFCWnHglWeJsf5ckB0p4672AXOnSdiqBx8Epy0JC1Ak0w0Z9s3AQKBgQD1u7kumxZnhwzQ8iMqIIKWVjeO+kcHVZAnv13Mm4XVG35MjzLAVJFTSVSz9IQivAHgt1OO+2W2fhW1aK44XCOzihl9Cw3lId4PhXHs/vtCxwS76G8eK17ZFMWnNQrFgkWKX7aDvzRx3nevcYJ70fw05ddqGgg0ilIaaWGwhi4vkwKBgQCLFXnSoyzkD5EpalKieq6CIGDdDQEP7HYqEzpEiGFcJ3QsfXy/GiViU8qq60jm/4gvLqZM1dTTdvFtAL18ojXh+INE60tvckSs98HcKOiXqOa8+XQrb4mXWyqdLAQSg65gyzUB5NIwc0fPNoRqt7xV62ocDG8taNL9xyKTjc+0QQKBgDgX0f3BiSu4uEQ6AazsHuMSazxIBU63SWDJXjAGkFZFIF95kKw2obXRV3R8rG0BXaxeLMTGvkEaJvpyGtLitYOWUpsomtNjF72TSJ5A1jo4E+yQ8kyAufXzt+qjQQI06orbrl4El4QmtEtoLdD17gxlKvcdWiIkMPm2dIZHGuPhAoGAcqpnAFjVNsT6yalFrXz1iITHNxd0i7tIhFHL1RqXIhHI/rEKesdEkPdB/H30YbXEghm0s14Lk7fZPSPCFRHq2H05REW0sJIFupCcvDTh5b8zVBNZSkIvrqBT1ramONlll5mQ/xm2jIlOZ7CrpQwL8s4ecjdyJX7pjJ5zjDD7T8ECgYEA1mdGWv3M7alBt8oKRHX0e1ynlG37PhZav59+xMLyAx51DD+lQCTcIv+zTTF5eJp9Z24YlQFJlTb0v+AnqNOJFaKykRghJwv7EQ1ZItgEUeIfo7ujhwPTQEAVoHZi7FbKZ1oaU7DFAIdhOi8zTWB8+va9U4GRlXTlmbcBWURjxmk=\";s:16:\"alipay_publickey\";s:392:\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAn9JWpw/UVsx2oa9969HLdxpF0SlZqmdf4NgWQtnc3D60m19oN3GkTzPLs5lI9fKQ/p6iDKc0obyCAMT+n4ODa5fAPoUV06euuusItZ+yPnHxMg40i9DKIpyi6yJXBc+xTpL9//t1ALMMR5nxzqVAVEqnAq5pI6iV2wqPlUMqJrEqUfy5hUcCG6Th9KhNu8kk5mv8mKXlxmHUUAi8CufEIcyky7bBpTf8FXxzjjLjhqTvUGy0u1M53oJuYecT2cDFON61IBR+7B77hjoLTUcIUqpHupEv220UKE3AIjIyjuSLXG4EuJreD5MnQwypMosLIVxlyypEKTKNiU4y52wDqQIDAQAB\";s:7:\"anounce\";s:14256:\"<section data-role=\"outer\" label=\"Powered by 135editor.com\" style=\"\"><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section><p style=\"vertical-align:inherit;\"><marquee behavior=\"alternate\" style=\"display: inline-block; overflow: hidden; text-align: initial; white-space: nowrap; box-sizing: border-box; margin: 0px; padding: 0px;\"><section style=\"will-change: transform;\" class=\"\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; font-size: 18px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: red; font-size: 18px;\"><strong>平台24小时自助下单，欢迎代理加盟~</strong></span></strong></span></section></marquee></p><section class=\"_135editor\" data-tools=\"135\" data-id=\"95165\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; color: rgba(0, 0, 0, 0.87); font-size: 14px; background-color: rgb(255, 255, 255); font-family: Avenir, Helvetica, Arial, sans-serif;\"><section style=\"box-sizing: border-box; margin: 15px 0px; padding: 0px; display: block; text-align: center;\"><section data-width=\"100%\" style=\"box-sizing:border-box; margin: 0px; padding: 15px; display: block; border-image: initial; border-width: 1px 1px 3px; border-radius: 15px; border-style: solid; border-color: #057df5; background-color: #ffffff; width: 100%;\"><section style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">下单须知</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">下单前请看清商品的介绍，不要填错下单所需要填写的正确邮箱账号！</span></p></section></section><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">付款注意事项</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">付款请打开手机浏览器付款！电脑网页暂不支持付款，最好注册登录账号购买！如出现付款不出码/请第一时间点击网站订单找/如还是没有请填写工单联系客服处理。</span></p></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">同款平台搭建</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">1.如果你也希望拥有一间属于自己的项目商城小店</span></strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">2.可以在会员中心注册→</span><span style=\"background-color: #ffff00;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000; background-color: #ffff00;\"><a href=\"/user/regsite.php\" target=\"_blank\" style=\"color: #ff0000; text-decoration-line: underline; background-color: #ffff00;\">开通分站</a></span></span></strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">3.一键拥有自己的商城→享受特价购买项目</span></strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">4.每天轻松推广就可以赚佣金</span></strong></p></section></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">我们承诺</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">本平台是虚拟资源项目分享网站，所有的项目皆为市面上别处卖几百到几千不等的项目，本站仅作分享揭秘，项目教程内均包含全套教程讲解，其他问题请自行研究。</span></p></section></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">售后须知</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\">不提供任何项目一对一教学指导！如遇项目和谐等不可抗拒因素无售后，项目分享利润极低可能给你不了伺候大爷般的服务态度，用户请仔细阅读本站条例，虚拟物品具有可复制性，一经拍下发货，视为认可项目注意事项说明！概不退款！如有问题可提交工单反馈！</p></section></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">免责申明</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">客服在线时间：早上12点至晚上24点，售后问题会在此时间段给予处理。</span></p></section></section></section></section></section></section><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section></section>\";s:5:\"api01\";s:0:\"\";s:5:\"api02\";s:0:\"\";s:6:\"apikey\";s:32:\"045084ea263aa170269869356696118a\";s:8:\"appalert\";s:9:\"APP下载\";s:17:\"appcreate_default\";s:1:\"1\";s:13:\"appcreate_diy\";s:1:\"1\";s:13:\"appcreate_key\";s:32:\"f6f5053701ec6e17c19722cad3fd4459\";s:15:\"appcreate_nonav\";s:1:\"0\";s:14:\"appcreate_open\";s:1:\"1\";s:15:\"appcreate_price\";s:1:\"2\";s:16:\"appcreate_price2\";s:1:\"1\";s:15:\"appcreate_theme\";s:7:\"#00BFFF\";s:6:\"appurl\";s:14:\"/?mod=app&id=2\";s:10:\"articlenum\";s:2:\"10\";s:15:\"article_rewrite\";s:1:\"0\";s:6:\"banner\";s:9:\"/666.webp\";s:9:\"blacklist\";s:0:\"\";s:6:\"bottom\";s:0:\"\";s:5:\"build\";s:10:\"2016-01-15\";s:17:\"captcha_open_free\";s:1:\"1\";s:16:\"captcha_open_reg\";s:1:\"1\";s:9:\"cdnpublic\";s:1:\"1\";s:9:\"chatframe\";s:0:\"\";s:5:\"cishu\";s:1:\"3\";s:7:\"cjcishu\";s:1:\"1\";s:7:\"cjmoney\";s:1:\"0\";s:5:\"cjmsg\";s:9:\"测试中\";s:10:\"classblock\";s:1:\"0\";s:10:\"codepay_id\";s:4:\"1002\";s:11:\"codepay_key\";s:32:\"ihiRBeMo4cHi6E2gI2RMrCdKXoM76Mdx\";s:5:\"cos01\";s:0:\"\";s:5:\"cos02\";s:0:\"\";s:7:\"cronkey\";s:6:\"353450\";s:13:\"cron_lasttime\";s:19:\"2025-05-20 03:45:02\";s:9:\"daiguaurl\";s:0:\"\";s:9:\"datepoint\";s:522:\"a:7:{i:0;a:3:{s:4:\"date\";s:4:\"0517\";s:6:\"orders\";s:2:\"15\";s:5:\"money\";d:111.67;}i:1;a:3:{s:4:\"date\";s:4:\"0516\";s:6:\"orders\";s:2:\"11\";s:5:\"money\";d:53.22;}i:2;a:3:{s:4:\"date\";s:4:\"0515\";s:6:\"orders\";s:1:\"6\";s:5:\"money\";d:123.69;}i:3;a:3:{s:4:\"date\";s:4:\"0514\";s:6:\"orders\";s:1:\"9\";s:5:\"money\";d:447.56;}i:4;a:3:{s:4:\"date\";s:4:\"0513\";s:6:\"orders\";s:1:\"7\";s:5:\"money\";d:136.6;}i:5;a:3:{s:4:\"date\";s:4:\"0512\";s:6:\"orders\";s:2:\"13\";s:5:\"money\";d:22.92;}i:6;a:3:{s:4:\"date\";s:4:\"0511\";s:6:\"orders\";s:1:\"2\";s:5:\"money\";d:7.14;}}\";s:10:\"defaultcid\";s:3:\"892\";s:11:\"description\";s:279:\"专注移动互联网创业赚钱实战项目，不定期分享项目实战、项目思维、案例，带你一起走向致富之路。平台提供百万价值的实战培训资料，精准引流爆粉实战，实操网络知识项目，分享流量变现的实操方法与技巧\";s:8:\"epay_key\";s:32:\"5553ZNV55kblE507gBk753E72S56g577\";s:9:\"epay_key2\";s:32:\"5553ZNV55kblE507gBk753E72S56g577\";s:8:\"epay_pid\";s:9:\"197327540\";s:9:\"epay_pid2\";s:9:\"197327540\";s:8:\"epay_url\";s:22:\"https://mzf.zfbtx.top/\";s:9:\"epay_url2\";s:22:\"https://mzf.zfbtx.top/\";s:10:\"faka_input\";s:1:\"0\";s:14:\"faka_inputname\";s:30:\"随便输入，或输入邮箱\";s:9:\"faka_mail\";s:216:\"<b>商品名称：</b> [name]<br/><b>购买时间：</b>[date]<br/><b>以下是你的卡密信息：</b><br/>[kmdata]<br/>----------<br/><b>使用说明：</b><br/>[alert]<br/>----------<br/>全民易站<br/>[domain]\";s:13:\"faka_showleft\";s:1:\"1\";s:12:\"fanghong_api\";s:1:\"0\";s:13:\"fanghong_type\";s:1:\"3\";s:12:\"fanghong_url\";s:0:\"\";s:12:\"fenzhan_adds\";s:1:\"1\";s:11:\"fenzhan_buy\";s:1:\"1\";s:12:\"fenzhan_cost\";s:2:\"10\";s:13:\"fenzhan_cost2\";s:2:\"20\";s:13:\"fenzhan_daifu\";s:1:\"1\";s:14:\"fenzhan_domain\";s:55:\"wamsg.cn,baiduw.top,yidegouw.com,58kanong.com,bbscs.xyz\";s:13:\"fenzhan_editd\";s:1:\"2\";s:16:\"fenzhan_edithtml\";s:1:\"1\";s:14:\"fenzhan_expiry\";s:1:\"0\";s:12:\"fenzhan_free\";s:1:\"0\";s:12:\"fenzhan_gift\";s:0:\"\";s:17:\"fenzhan_jiakuanka\";s:1:\"1\";s:12:\"fenzhan_kfqq\";s:1:\"1\";s:12:\"fenzhan_page\";s:1:\"0\";s:13:\"fenzhan_price\";s:2:\"10\";s:14:\"fenzhan_price2\";s:2:\"88\";s:18:\"fenzhan_pricelimit\";s:1:\"0\";s:12:\"fenzhan_rank\";s:1:\"0\";s:16:\"fenzhan_regalert\";s:1:\"1\";s:15:\"fenzhan_regrand\";s:1:\"0\";s:14:\"fenzhan_remain\";s:75:\"www.58kanong.com,www.baiduw.top,www.wamsg.cn,www.yidegouw.com,www.bbscs.xyz\";s:13:\"fenzhan_skimg\";s:1:\"1\";s:16:\"fenzhan_template\";s:1:\"0\";s:14:\"fenzhan_tixian\";s:1:\"1\";s:21:\"fenzhan_tixian_alipay\";s:1:\"1\";s:17:\"fenzhan_tixian_qq\";s:1:\"0\";s:17:\"fenzhan_tixian_wx\";s:1:\"0\";s:15:\"fenzhan_upgrade\";s:2:\"68\";s:6:\"footer\";s:182:\"此站点任何信息【包括销售商品】跟平台程序运营者无关，\r\n如有问题请联系客服页面的站长，点此?查看免责条款。\r\n搭建同款无限版主站\";s:10:\"forcelogin\";s:1:\"0\";s:14:\"forceloginhome\";s:1:\"0\";s:8:\"forcermb\";s:1:\"0\";s:11:\"gg_announce\";s:0:\"\";s:8:\"gg_panel\";s:0:\"\";s:9:\"gg_search\";s:400:\"<span class=\"label label-primary\">待处理</span> 说明正在努力提交到服务器！<p></p><p></p><span class=\"label label-success\">已完成</span> 已经提交到接口正在处理！<p></p><p></p><span class=\"label label-warning\">处理中</span> 已经开始为您开单 请耐心等！<p></p><p></p><span class=\"label label-danger\">有异常</span> 下单信息有误 联系客服处理！\";s:8:\"gift_log\";s:1:\"1\";s:9:\"gift_open\";s:1:\"0\";s:11:\"hide_tongji\";s:1:\"0\";s:21:\"index_class_num_style\";s:1:\"8\";s:14:\"invite_content\";s:330:\"专注移动互联网创业赚钱实战项目，不定期分享项目实战、项目思维、案例，带你一起走向致富之路。平台提供百万价值的实战培训资料，精准引流爆粉实战，实操网络知识项目，分享流量变现的实操方法与技巧，各种最新热门项目帮助你快速致富！\";s:10:\"invite_tid\";s:1:\"1\";s:6:\"iskami\";s:1:\"1\";s:14:\"jiamengzhuzhan\";s:0:\"\";s:7:\"jiangli\";s:1:\"5\";s:8:\"jiangli2\";s:2:\"15\";s:8:\"jiangli3\";s:2:\"30\";s:8:\"keywords\";s:21:\"互联网创业项目\";s:4:\"kfqq\";s:9:\"188988850\";s:4:\"kfwx\";s:9:\"188988850\";s:12:\"login_apiurl\";s:2:\"23\";s:11:\"login_appid\";s:2:\"23\";s:12:\"login_appkey\";s:2:\"23\";s:8:\"login_qq\";s:1:\"0\";s:8:\"login_wx\";s:1:\"0\";s:11:\"mail_apikey\";s:24:\"LTAI5tFL2gKENS3xbyZwi8J4\";s:12:\"mail_apiuser\";s:30:\"7pvX2zZJLn4aFga6DGureYoKIHubZx\";s:10:\"mail_cloud\";s:1:\"0\";s:9:\"mail_name\";s:16:\"qmyz@foxmail.com\";s:10:\"mail_name2\";s:0:\"\";s:9:\"mail_port\";s:3:\"465\";s:8:\"mail_pwd\";s:16:\"fqmsnxrzdwlfbgia\";s:9:\"mail_recv\";s:0:\"\";s:9:\"mail_smtp\";s:11:\"smtp.qq.com\";s:8:\"maintain\";s:8:\"20250520\";s:11:\"message_buy\";s:1:\"1\";s:14:\"message_duijie\";s:1:\"1\";s:12:\"message_type\";s:1:\"0\";s:17:\"message_workorder\";s:1:\"1\";s:11:\"micropayapi\";s:1:\"2\";s:12:\"micropay_key\";s:32:\"2uzcqowg4guuco7ncwqnchkch8n42yyo\";s:14:\"micropay_mchid\";s:35:\"http://yzf.bbscs.xyz/Pay_Index.html\";s:12:\"micropay_pid\";s:9:\"220476828\";s:5:\"modal\";s:237:\"遇到了问题，请到会员中心-售后反馈内提交工单，客服会在12小时内回复并协助你处理。 遇到了问题，请到会员中心-售后反馈内提交工单，客服会在12小时内回复并协助你处理。\r\n\";s:8:\"musicurl\";s:0:\"\";s:14:\"openbatchorder\";s:1:\"1\";s:9:\"ordername\";s:13:\"[time][order]\";s:6:\"payapi\";s:2:\"-1\";s:7:\"payapi2\";s:2:\"-1\";s:6:\"paymsg\";s:203:\"<hr/>小提示：<b style=\"color:red\">如果微信出现无法付款时，您可以把微信的钱转到QQ里，然后使用QQ钱包支付！<a href=\"./?mod=wx\" target=\"_blank\">点击查看教程</a></b>\";s:11:\"pricejk_cid\";s:2963:\"893,925,905,924,923,904,435,892,329,75,915,116,433,434,9,12,306,436,55,56,10,46,21,20,11,45,48,50,19,43,2,1,291,292,293,330,331,332,333,334,335,336,337,338,339,340,341,342,343,344,345,346,347,444,465,467,469,470,471,472,473,474,478,953,954,481,482,483,484,485,486,487,488,489,490,491,494,1032,956,894,506,507,512,513,515,516,517,518,519,520,521,523,524,525,526,528,529,537,531,532,533,534,535,536,538,539,541,542,543,544,545,546,547,548,550,551,552,553,554,555,557,558,559,560,562,564,565,566,567,568,570,571,572,575,576,577,579,580,584,585,586,587,588,589,592,607,624,641,642,643,644,645,646,647,649,654,651,926,652,977,653,658,657,655,656,978,659,660,650,661,662,663,664,665,666,667,671,672,673,674,675,676,677,678,679,680,688,689,690,691,692,693,694,695,696,697,698,699,700,701,702,703,706,707,709,710,711,712,713,714,715,716,717,718,719,721,722,723,724,725,726,727,728,729,730,731,732,733,734,735,736,737,738,739,740,741,742,743,744,769,745,746,747,748,749,750,751,752,753,757,759,760,761,762,763,764,765,766,767,768,770,771,772,773,774,777,778,779,780,781,782,783,784,785,786,787,788,789,790,791,792,793,794,795,796,797,798,799,800,801,802,803,804,805,806,807,808,809,810,811,812,813,814,815,816,817,818,819,820,821,822,823,824,825,826,827,828,829,830,831,832,833,834,835,836,837,838,839,840,841,842,843,844,845,846,847,848,849,850,851,852,853,854,855,856,857,858,859,860,861,862,863,864,865,866,867,868,869,870,871,872,873,874,875,876,877,878,879,880,881,882,883,884,885,886,887,888,889,890,53,54,44,13,895,896,897,898,899,900,901,902,903,907,908,909,910,911,912,921,927,1207,928,929,930,931,932,981,934,933,935,936,937,938,939,940,941,922,914,942,943,944,945,946,975,976,952,947,948,949,951,950,955,957,958,959,960,961,962,963,964,965,966,967,968,970,971,972,973,974,1033,979,980,982,983,984,985,986,987,988,989,990,991,992,993,994,995,996,997,998,999,1000,1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1015,1016,1017,1018,1019,1020,1021,1022,1024,1023,1025,1026,1027,1028,1029,1030,1031,1035,1036,1037,1038,1039,1040,1041,1042,1043,1044,1045,1046,1047,1048,1049,1050,1051,1052,1053,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1070,1071,1072,1073,1074,1075,1076,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,1087,1089,1090,1091,1092,1093,1094,1095,1096,1097,1098,1099,1100,1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113,1114,1115,1116,1117,1118,1119,1120,1121,1122,1123,1124,1125,1126,1127,1128,1129,1130,1131,1132,1133,1134,1135,1136,1137,1138,1139,1141,1142,1143,1144,1145,1146,1147,1148,1149,1150,1151,1152,1153,1154,1155,1156,1157,1158,1159,1160,1161,1162,1163,1164,1165,1166,1167,1168,1169,1170,1171,1172,1173,1174,1175,1176,1177,1178,1179,1180,1181,1182,1183,1184,1185,1186,1187,1188,1189,1190,1191,1192,1193,1194,1195,1196,1197,1198,1199,1200,1201,1202,1203,1204,1205,1206,1208,1209,1210,1211,1212,1213,1214,1215,1216,1217,1218,1219,1220,1221,1222,1223,1224,1225,1226,1227,1228,1229,1230,1231,1232\";s:12:\"pricejk_edit\";s:1:\"0\";s:16:\"pricejk_lasttime\";s:19:\"2025-05-20 03:40:02\";s:14:\"pricejk_status\";s:2:\"ok\";s:12:\"pricejk_time\";s:3:\"600\";s:12:\"pricejk_yile\";s:1:\"0\";s:11:\"qiandao_day\";s:1:\"7\";s:15:\"qiandao_limitip\";s:1:\"1\";s:12:\"qiandao_mult\";s:4:\"1.01\";s:14:\"qiandao_reward\";s:11:\"0.1|0.1|0.1\";s:6:\"qqjump\";s:1:\"0\";s:9:\"qqpay_api\";s:1:\"0\";s:9:\"qqpay_key\";s:0:\"\";s:9:\"qqpay_pid\";s:0:\"\";s:4:\"qqzf\";s:0:\"\";s:6:\"qqzfid\";s:0:\"\";s:7:\"qqzfkey\";s:0:\"\";s:15:\"queryorderlimit\";s:1:\"1\";s:12:\"recharge_min\";s:0:\"\";s:5:\"rgb01\";s:6:\"9a58f5\";s:7:\"rwbt_01\";s:42:\"御剑八荒（每日新区）点击下载\";s:7:\"rwbt_02\";s:42:\"狂暴传奇（每日新区）点击下载\";s:7:\"rwbt_03\";s:45:\"大侠无限刀（每日新区）点击下载\";s:7:\"rwbt_04\";s:45:\"三国封魔传（每日新区）点击下载\";s:7:\"rwbt_05\";s:42:\"龙翔复古（每日新区）点击下载\";s:7:\"rwbt_06\";s:42:\"王者战歌（每日新区）点击下载\";s:7:\"rwbt_07\";s:42:\"逍遥火龙（每日新区）点击下载\";s:7:\"rwbt_08\";s:42:\"天命传说（每日新区）点击下载\";s:7:\"rwbt_09\";s:42:\"诸天神龙（每日新区）点击下载\";s:7:\"rwbt_10\";s:42:\"传说之城（每日新区）点击下载\";s:7:\"rwbt_11\";s:43:\"龙腾天下2（每日新区）点击下载\";s:7:\"rwbt_12\";s:57:\"自由之刃（第二季）（每日新区）点击下载\";s:7:\"rwbt_13\";s:46:\"1.76复古传奇（每日新区）点击下载\";s:7:\"rwbt_14\";s:42:\"黑暗光年（每日新区）点击下载\";s:7:\"rwbt_15\";s:46:\"新自由之刃1（每日新区）点击下载\";s:7:\"rwbt_16\";s:48:\"新自由之刃二（每周新区）点击下载\";s:7:\"rwbt_17\";s:47:\"1.76复古传奇1（每日新区）点击下载\";s:7:\"rwbt_18\";s:42:\"龙翔冰雪（每日新区）点击下载\";s:7:\"rwbt_19\";s:58:\"冰雪归来 2024.2月出品（每日新区）点击下载\";s:7:\"rwbt_20\";s:0:\"\";s:7:\"rwkq_01\";s:1:\"1\";s:7:\"rwkq_02\";s:1:\"1\";s:7:\"rwkq_03\";s:1:\"1\";s:7:\"rwkq_04\";s:1:\"1\";s:7:\"rwkq_05\";s:1:\"1\";s:7:\"rwkq_06\";s:1:\"1\";s:7:\"rwkq_07\";s:1:\"1\";s:7:\"rwkq_08\";s:1:\"1\";s:7:\"rwkq_09\";s:1:\"1\";s:7:\"rwkq_10\";s:1:\"1\";s:8:\"rwkq_101\";s:7:\"sdfsfsd\";s:8:\"rwkq_102\";s:9:\"sdfsdfsdf\";s:8:\"rwkq_103\";s:21:\"大范甘迪示范岗\";s:8:\"rwkq_104\";s:0:\"\";s:8:\"rwkq_105\";s:0:\"\";s:7:\"rwkq_11\";s:1:\"1\";s:7:\"rwkq_12\";s:1:\"1\";s:7:\"rwkq_13\";s:1:\"1\";s:7:\"rwkq_14\";s:1:\"1\";s:7:\"rwkq_15\";s:1:\"1\";s:7:\"rwkq_16\";s:1:\"1\";s:7:\"rwkq_17\";s:1:\"1\";s:7:\"rwkq_18\";s:1:\"1\";s:7:\"rwkq_19\";s:1:\"1\";s:7:\"rwkq_20\";s:1:\"0\";s:7:\"rwlj_01\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=119&pid=1775\";s:7:\"rwlj_02\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=113&pid=1775\";s:7:\"rwlj_03\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=107&pid=1775\";s:7:\"rwlj_04\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=105&pid=1775\";s:7:\"rwlj_05\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=103&pid=1775\";s:7:\"rwlj_06\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=101&pid=1775\";s:7:\"rwlj_07\";s:60:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=99&pid=1775\";s:7:\"rwlj_08\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=129&pid=1775\";s:7:\"rwlj_09\";s:60:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=93&pid=1775\";s:7:\"rwlj_10\";s:60:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=89&pid=1775\";s:7:\"rwlj_11\";s:60:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=83&pid=1775\";s:7:\"rwlj_12\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=127&pid=1775\";s:7:\"rwlj_13\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=121&pid=1775\";s:7:\"rwlj_14\";s:60:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=11&pid=1775\";s:7:\"rwlj_15\";s:59:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=3&pid=1775\";s:7:\"rwlj_16\";s:59:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=5&pid=1775\";s:7:\"rwlj_17\";s:60:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=39&pid=1775\";s:7:\"rwlj_18\";s:60:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=37&pid=1775\";s:7:\"rwlj_19\";s:61:\"https://hw.fuhua58.com/mobile/Downfile/index?gid=117&pid=1775\";s:7:\"rwlj_20\";s:0:\"\";s:7:\"rwnr_01\";s:110:\"骷髅传奇高爆版，上线就送绝版时装+500路费 上线一只小骷髅，多种时装随你转化！\";s:7:\"rwnr_02\";s:47:\"每日下午15时准点开新区！激情四射\";s:7:\"rwnr_03\";s:126:\"专属无限刀白嫖版本，上线就送自动回收/自动拾取等等，散人的天堂，地图无限制·爆率全开放\";s:7:\"rwnr_04\";s:154:\"多职业MMOARPG手游，沿用了战法道的游戏设定，进阶转职玩法，在保留原味的复古的同时，实现了九职业进阶转化玩法\";s:7:\"rwnr_05\";s:126:\"经典1.76三职业特色复古，一切靠打，游戏平衡 融入了沉默、神器、冰雪等特色元素，玩法丰富!\";s:7:\"rwnr_06\";s:64:\"上线送500路费+满级会员+自动拾取+自动回收+冰龙\";s:7:\"rwnr_07\";s:102:\"火龙冰雪融合版本，创新，丰富 上线自动回收.自动拾取.赞助，冠名免费白嫖\";s:7:\"rwnr_08\";s:153:\"一款经典武侠传奇打金手游，上线即可免费领满VIP，首冲免费送，打怪就能爆真充，不肝不氪，散人也能玩转传奇。\";s:7:\"rwnr_09\";s:66:\"无暗坑，充值可爆，零氪当大佬。可激情，可扛米\";s:7:\"rwnr_10\";s:81:\"开局【满赞助】上线送300路费，送自动回收，自动拾取送冰龙\";s:7:\"rwnr_11\";s:90:\"1.80微变电竞传奇上线就送1万真实充值,充值0.1折纯元宝服，充值货币\";s:7:\"rwnr_12\";s:224:\"只为给万千传奇兄弟打造真正的免费打金服。游戏独创特色魂环系统、战灵系统、守护系统，承诺所有野外地图无限制进入，打出实物-秒回收灵符，BOSS也能爆出炫酷时装\";s:7:\"rwnr_13\";s:102:\"游戏中延续了战、法、道三大职业的设定，并采用了大世界、多人在线等玩法\";s:7:\"rwnr_14\";s:42:\"散人激情，酷炫，一切全靠爆！\";s:7:\"rwnr_15\";s:131:\"1.76三职业魂环攻速常规服，抖音魂环赞助传奇同款， 百万年魂环上线满攻速，会员免费，千倍爆率\";s:7:\"rwnr_16\";s:97:\"登录送满V，88自动拾取回收，顶赞188（送异火），做任务得魂环，3000送龙\";s:7:\"rwnr_17\";s:151:\"上‮就线‬送1000真实充‮大值‬礼包，单‮币货‬服，打怪掉落，装‮回备‬收获‮的得‬元宝就‮充是‬值货币\";s:7:\"rwnr_18\";s:94:\"零氪玩通关进游就送5000真实充值自动回收，自动拾取，切割称号免费送\";s:7:\"rwnr_19\";s:70:\"全网首款 冰雪三职业连击版本，带给你不一样的体验\";s:7:\"rwnr_20\";s:0:\"\";s:7:\"rwtp_01\";s:76:\"https://hwcdn.fuhua95.com/game/20240115/d6346fc411a80c796b86537c6ed26b84.png\";s:7:\"rwtp_02\";s:76:\"https://hwcdn.fuhua95.com/game/20240108/657f863664a07932e0645ea4781cea02.png\";s:7:\"rwtp_03\";s:76:\"https://hwcdn.fuhua95.com/game/20231225/6c4aa421f788b353dabe5111d91c7a50.png\";s:7:\"rwtp_04\";s:76:\"https://hwcdn.fuhua95.com/game/20231128/f8b929c79ab14c1023ea37ec6264d69e.jpg\";s:7:\"rwtp_05\";s:76:\"https://hwcdn.fuhua95.com/game/20231115/d87ed2caf7a22fea3d3545d0a78104f3.png\";s:7:\"rwtp_06\";s:76:\"https://hwcdn.fuhua95.com/game/20231104/1677cc83444ffa356a98c5bdf54595ea.jpg\";s:7:\"rwtp_07\";s:76:\"https://hwcdn.fuhua95.com/game/20231022/a7416547ed00eaed371c80ae18519df0.png\";s:7:\"rwtp_08\";s:76:\"https://hwcdn.fuhua95.com/game/20240325/da67dc7e5519f11c08257abfa4c1444e.png\";s:7:\"rwtp_09\";s:76:\"https://hwcdn.fuhua95.com/game/20230919/cb11eb26580a87be0b6857db30673683.png\";s:7:\"rwtp_10\";s:76:\"https://hwcdn.fuhua95.com/game/20230919/2ff619ee5d82716e06ba6196dcb20ba4.png\";s:8:\"rwtp_101\";s:8:\"32123132\";s:8:\"rwtp_102\";s:5:\"sdfsf\";s:8:\"rwtp_103\";s:18:\"电饭锅电饭锅\";s:8:\"rwtp_104\";s:0:\"\";s:8:\"rwtp_105\";s:0:\"\";s:7:\"rwtp_11\";s:76:\"https://hwcdn.fuhua95.com/game/20230901/a34d3b4cd720a8fbe6dd5df43dc62849.png\";s:7:\"rwtp_12\";s:76:\"https://hwcdn.fuhua95.com/game/20240320/3c7b2319867bcfb02790c58c245da783.png\";s:7:\"rwtp_13\";s:76:\"https://hwcdn.fuhua95.com/game/20240228/0915ca1abc92e8a813dacd3ef3e7ae61.png\";s:7:\"rwtp_14\";s:76:\"https://hwcdn.fuhua95.com/game/20230115/ddb47211012cbc5f00ff818b79be60bd.png\";s:7:\"rwtp_15\";s:76:\"https://hwcdn.fuhua95.com/game/20230111/67803c1a3fe3b3e8dae1bf16b16269f7.png\";s:7:\"rwtp_16\";s:76:\"https://hwcdn.fuhua95.com/game/20230111/c59c967fcebe32b455301e602f2c0a08.png\";s:7:\"rwtp_17\";s:76:\"https://hwcdn.fuhua95.com/game/20230411/903af4927730f95c29d82e72c2dc6b0d.png\";s:7:\"rwtp_18\";s:76:\"https://hwcdn.fuhua95.com/game/20230419/050690bb8a1d399d0a061744608ce18d.jpg\";s:7:\"rwtp_19\";s:76:\"https://hwcdn.fuhua95.com/game/20240129/8e2179aa3bb73e6775eb45d1280223a5.png\";s:7:\"rwtp_20\";s:0:\"\";s:11:\"search_open\";s:1:\"1\";s:10:\"selfrefund\";s:1:\"0\";s:15:\"shopdesc_editor\";s:1:\"1\";s:12:\"shoppingcart\";s:1:\"1\";s:14:\"show_changepwd\";s:1:\"1\";s:13:\"show_complain\";s:1:\"1\";s:8:\"sitename\";s:14:\"58全民易站\";s:13:\"sitename_hide\";s:1:\"1\";s:11:\"speedy_list\";s:2874:\"从新挑选一件商品，把链接或者标题完整发我！仅更换一次^好的 我们会尽快核实为您处理！^可以看一下平台的别的课程，可以帮您免费调换一次哦，如果需要调换课程，请提交工单注明需要的【课程名字】，感谢您的支持！^由于商品过多，无法批量更改，可以选择4个分类批量更改，其他的手动更改，站长群里都有说过^资源都是由全国的作者和商家提供，却有此业务，具体收益还需自行研究，咱们平台只作为资源分享，有些确有价值有些未经详细测试还需，自行研究！^1.关闭杀毒软件 2.删出文件和压缩包 3.重新去网盘内下载 4.解压按教程操作^如果您觉得该课程对您的帮助不大的情况下，可以看一下平台的别的课程，可以帮您免费调换一次哦，如果需要调换课程，请提交工单注明需要的【课程名字】，感谢您的支持！^咱们平台主要是做全网项目收集，外面割韭菜的项目在这里全部只需要几十块就可以买到了，并不存在欺骗，咱们平台不保证所有项目都是能赚钱的，只能确保项目的教程和附带的软件和外面的一模一样，并且是可以运行的!^如果您觉得该课程对您的帮助不大的情况下，可以看一下平台的别的课程，可以帮您免费调换一次哦，如果需要调换课程，请提交工单注明需要的【课程名字】，感谢您的支持！^该项目出现异常，已帮您做退款处理^该脚本维护中，已给您退回账户，您可以看看其他资源商品，或者等待维护好在从新购买！^刷分都有掉粉的可能性的 这个无法避免 只要在售后期24小时内才可以售后 兄弟^核实该脚本已和谐，为您做退回处理^删除手机现有APP从新安装不要更新，会员功能全部能正常使用，^确定提供的链接上补单链接，掉粉账号？^https://v.douyin.com/iFPkdysj/(替换自己刷粉的补粉链接)\n初始0.下单1000.现在剩899.申请补101个粉丝 按照这个格式发我^你提交的格式链接与你本次提交的订单号不匹配，^风控一般正常24小时内完成^在未开始刷之前，把视频链接下架，机房上粉失败，会自动给你退回，退回时间已机房为准，如已陆续在上粉状态，不可操作，否则中途中断，无任何售后！^高速粉渠道有点慢你可以 下最新上的闪电粉比较高效率^关闭杀毒软件 打开补丁 再点开软件^已退^已帮你申请拦截退单，如明天下午没成功可以在此 回复^该通道没有凭证 如果需要有凭证的，这两天可以申请上架，^几万件商品 每天都在上上下下 根本检测不完，而且很多老口子 时间过去 但是依然有效，由于您账户状况良好，可以给您直接退单一次\";s:10:\"sptjzsjg01\";s:12:\"快速放款\";s:10:\"sptjzsjg02\";s:12:\"高价兑现\";s:10:\"sptjzsjg03\";s:0:\"\";s:10:\"sptjzslj01\";s:32:\"https://d.kanongquan.net/s/PmZKx\";s:10:\"sptjzslj02\";s:5:\"89972\";s:10:\"sptjzslj03\";s:0:\"\";s:10:\"sptjzstp01\";s:61:\"https://weixin-att.kanongyun.com/2024-02-22_65d725676f41d.png\";s:10:\"sptjzstp02\";s:65:\"https://cdn.vitfintech.com/statics/weapp/icon/icon-jifenjiage.png\";s:10:\"sptjzstp03\";s:0:\"\";s:10:\"sptjzswz01\";s:12:\"贷款超市\";s:10:\"sptjzswz02\";s:15:\"信用卡积分\";s:10:\"sptjzswz03\";s:0:\"\";s:7:\"spxq_xx\";s:42:\"购买商品成功，祝您事事顺心！\";s:8:\"spzs_car\";s:1:\"0\";s:10:\"spzs_sales\";s:1:\"0\";s:9:\"staticurl\";s:17:\"qmcy.58kanong.com\";s:5:\"style\";s:1:\"1\";s:9:\"syggw_car\";s:1:\"1\";s:6:\"syskey\";s:32:\"JJWi0vQqcD09iCPQZrJ0wJrPyD0J9Jp8\";s:8:\"template\";s:9:\"storenews\";s:10:\"template_m\";s:1:\"0\";s:18:\"template_showprice\";s:1:\"0\";s:18:\"template_showsales\";s:1:\"0\";s:20:\"template_virtualdata\";s:1:\"1\";s:10:\"tenpay_api\";s:1:\"0\";s:10:\"tenpay_key\";s:0:\"\";s:10:\"tenpay_pid\";s:0:\"\";s:5:\"title\";s:18:\"创业项目基地\";s:11:\"tixian_days\";s:0:\"\";s:9:\"tixian_js\";s:83:\"你提交的提现申请将会在24小时内到账，请耐心等待客服打款。\";s:12:\"tixian_limit\";s:1:\"1\";s:10:\"tixian_min\";s:2:\"10\";s:11:\"tixian_rate\";s:2:\"97\";s:16:\"tongji_cachetime\";s:10:\"1745682685\";s:11:\"tongji_time\";s:3:\"100\";s:5:\"txurl\";s:0:\"\";s:13:\"ui_background\";s:1:\"3\";s:16:\"ui_backgroundurl\";s:104:\"//cn.bing.com/th?id=OHR.LaternFestival2024_ZH-CN8050981828_1920x1080.jpg&rf=LaDigue_1920x1080.jpg&pid=hp\";s:7:\"ui_bing\";s:1:\"1\";s:12:\"ui_bing_date\";s:8:\"20240224\";s:9:\"ui_color1\";s:0:\"\";s:9:\"ui_color2\";s:0:\"\";s:10:\"ui_colorto\";s:1:\"0\";s:7:\"ui_shop\";s:1:\"0\";s:7:\"ui_user\";s:1:\"0\";s:12:\"updatestatus\";s:1:\"1\";s:21:\"updatestatus_interval\";s:1:\"1\";s:21:\"updatestatus_lasttime\";s:19:\"2025-05-20 03:45:02\";s:10:\"user_level\";s:1:\"0\";s:9:\"user_open\";s:1:\"1\";s:11:\"verify_open\";s:1:\"0\";s:7:\"version\";s:4:\"2055\";s:10:\"wechat_api\";s:1:\"1\";s:15:\"wechat_apptoken\";s:35:\"AT_Xp7VvuIqCRJNBoOlRwm2Q1amHqSM5Ac7\";s:13:\"wechat_appuid\";s:5:\"23004\";s:12:\"wechat_sckey\";s:34:\"SCT134077TsY25pgvTTQcCgh0Ny2wzefxk\";s:13:\"wenzhangdizhi\";s:0:\"\";s:4:\"work\";s:0:\"\";s:14:\"workorder_open\";s:1:\"1\";s:13:\"workorder_pic\";s:1:\"1\";s:14:\"workorder_type\";s:76:\"问题反馈|订单链接错误|充值没到账|求项目资源|其他问题\";s:9:\"wxpay_api\";s:1:\"8\";s:11:\"wxpay_appid\";s:18:\"wx04bebd85c96a7faf\";s:15:\"wxpay_appsecret\";s:32:\"ef928db77b17a3062917d9bfe9f0f860\";s:12:\"wxpay_domain\";s:0:\"\";s:9:\"wxpay_key\";s:32:\"1t0flb2mrajaatjul5nhyaeitvhizbmp\";s:11:\"wxpay_mchid\";s:10:\"1604815382\";s:4:\"wxzf\";s:20:\"http://mq.bbscs.xyz/\";s:6:\"wxzfid\";s:1:\"1\";s:7:\"wxzfkey\";s:32:\"56df26c08d326c85a67c2490475a3e69\";s:6:\"wywz03\";s:0:\"\";s:4:\"xwgg\";s:20:\"24小时自动发货\";s:8:\"xwgg_car\";s:1:\"1\";s:5:\"ywsm1\";s:0:\"\";s:3:\"zfb\";s:0:\"\";s:5:\"zfbid\";s:0:\"\";s:6:\"zfbimg\";s:0:\"\";s:6:\"zfbkey\";s:0:\"\";}',0),('getcount','a:2:{s:4:\"time\";i:1749452952;s:4:\"data\";a:40:{s:4:\"code\";i:0;s:4:\"yxts\";d:3434;s:6:\"count1\";s:5:\"41852\";s:6:\"count2\";s:5:\"41809\";s:6:\"count3\";s:2:\"43\";s:7:\"count77\";s:1:\"0\";s:7:\"count78\";s:1:\"0\";s:6:\"count4\";s:1:\"0\";s:6:\"count5\";d:0;s:6:\"count6\";s:1:\"4\";s:6:\"count7\";s:1:\"0\";s:6:\"count8\";d:0;s:6:\"count9\";d:0;s:7:\"count10\";d:0;s:7:\"count11\";d:0;s:7:\"count12\";d:0;s:7:\"count13\";d:0;s:7:\"count14\";d:0;s:7:\"count15\";d:0;s:7:\"count16\";d:0;s:7:\"count17\";s:1:\"0\";s:7:\"count18\";d:0;s:7:\"count21\";s:1:\"0\";s:7:\"count22\";s:1:\"0\";s:7:\"count23\";s:1:\"0\";s:7:\"count24\";d:0;s:7:\"count25\";d:0;s:7:\"count26\";d:0;s:7:\"numrows\";s:1:\"2\";s:8:\"numrows1\";s:1:\"2\";s:8:\"numrows2\";s:1:\"0\";s:8:\"numrows3\";s:1:\"0\";s:11:\"daili_money\";d:6792.16;s:11:\"daili_point\";d:0;s:7:\"fzjr_xf\";d:0;s:7:\"fzzr_xf\";d:0;s:7:\"count97\";N;s:7:\"count98\";s:5:\"41852\";s:7:\"count99\";s:1:\"4\";s:5:\"chart\";a:4:{s:4:\"date\";a:7:{i:0;a:2:{i:0;i:1;i:1;s:4:\"0528\";}i:1;a:2:{i:0;i:2;i:1;s:4:\"0529\";}i:2;a:2:{i:0;i:3;i:1;s:4:\"0602\";}i:3;a:2:{i:0;i:4;i:1;s:4:\"0604\";}i:4;a:2:{i:0;i:5;i:1;s:4:\"0605\";}i:5;a:2:{i:0;i:6;i:1;s:4:\"0607\";}i:6;a:2:{i:0;i:7;i:1;s:4:\"0608\";}}s:6:\"orders\";a:7:{i:0;a:2:{i:0;i:1;i:1;s:2:\"99\";}i:1;a:2:{i:0;i:2;i:1;s:3:\"105\";}i:2;a:2:{i:0;i:3;i:1;s:1:\"0\";}i:3;a:2:{i:0;i:4;i:1;s:1:\"0\";}i:4;a:2:{i:0;i:5;i:1;s:1:\"0\";}i:5;a:2:{i:0;i:6;i:1;s:1:\"0\";}i:6;a:2:{i:0;i:7;i:1;s:1:\"0\";}}s:5:\"money\";a:7:{i:0;a:2:{i:0;i:1;i:1;d:209.42;}i:1;a:2:{i:0;i:2;i:1;d:88.54;}i:2;a:2:{i:0;i:3;i:1;d:0;}i:3;a:2:{i:0;i:4;i:1;d:0;}i:4;a:2:{i:0;i:5;i:1;d:0;}i:5;a:2:{i:0;i:6;i:1;d:0;}i:6;a:2:{i:0;i:7;i:1;d:0;}}s:8:\"maintain\";s:8:\"20250609\";}}}',0),('pricejk_type1','shangzhanwl,jiuwu,daishua,yunbao',0),('pricejk_type2','kayixin,yiqida,kakayun,zhike,shangmeng,kashangwl,qingjiu,yile',0),('pricerules','a:32:{i:2;a:4:{s:4:\"kind\";s:1:\"1\";s:3:\"p_2\";s:4:\"4.00\";s:3:\"p_1\";s:4:\"6.00\";s:3:\"p_0\";s:4:\"9.99\";}i:3;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.60\";s:3:\"p_0\";s:4:\"2.00\";}i:5;a:4:{s:4:\"kind\";s:1:\"1\";s:3:\"p_2\";s:4:\"5.00\";s:3:\"p_1\";s:4:\"8.00\";s:3:\"p_0\";s:5:\"10.00\";}i:6;a:4:{s:4:\"kind\";s:1:\"1\";s:3:\"p_2\";s:4:\"0.03\";s:3:\"p_1\";s:4:\"0.05\";s:3:\"p_0\";s:4:\"0.08\";}i:7;a:4:{s:4:\"kind\";s:1:\"1\";s:3:\"p_2\";s:4:\"1.00\";s:3:\"p_1\";s:4:\"3.00\";s:3:\"p_0\";s:4:\"5.99\";}i:10;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:20;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.05\";s:3:\"p_1\";s:4:\"1.08\";s:3:\"p_0\";s:4:\"1.20\";}i:21;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.03\";s:3:\"p_1\";s:4:\"1.04\";s:3:\"p_0\";s:4:\"1.05\";}i:22;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.02\";s:3:\"p_1\";s:4:\"1.02\";s:3:\"p_0\";s:4:\"1.03\";}i:23;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.04\";s:3:\"p_1\";s:4:\"1.06\";s:3:\"p_0\";s:4:\"1.07\";}i:24;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.30\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:25;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.24\";s:3:\"p_1\";s:4:\"1.60\";s:3:\"p_0\";s:4:\"2.00\";}i:26;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:27;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"7.00\";s:3:\"p_1\";s:5:\"10.00\";s:3:\"p_0\";s:5:\"30.00\";}i:28;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:30;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:31;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.04\";s:3:\"p_1\";s:4:\"1.06\";s:3:\"p_0\";s:4:\"1.07\";}i:33;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.40\";s:3:\"p_1\";s:4:\"1.60\";s:3:\"p_0\";s:4:\"1.80\";}i:34;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.04\";s:3:\"p_1\";s:4:\"1.06\";s:3:\"p_0\";s:4:\"1.08\";}i:35;a:4:{s:4:\"kind\";s:1:\"1\";s:3:\"p_2\";s:4:\"0.50\";s:3:\"p_1\";s:4:\"2.50\";s:3:\"p_0\";s:4:\"6.49\";}i:37;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:38;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.10\";s:3:\"p_1\";s:4:\"1.35\";s:3:\"p_0\";s:4:\"1.45\";}i:39;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.03\";s:3:\"p_1\";s:4:\"1.04\";s:3:\"p_0\";s:4:\"1.06\";}i:40;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:42;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.10\";s:3:\"p_1\";s:4:\"1.20\";s:3:\"p_0\";s:4:\"1.30\";}i:43;a:4:{s:4:\"kind\";s:1:\"1\";s:3:\"p_2\";s:4:\"0.50\";s:3:\"p_1\";s:4:\"3.50\";s:3:\"p_0\";s:4:\"7.49\";}i:44;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"0.00\";s:3:\"p_1\";s:4:\"0.00\";s:3:\"p_0\";s:4:\"0.00\";}i:45;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.20\";s:3:\"p_1\";s:4:\"1.40\";s:3:\"p_0\";s:4:\"1.60\";}i:46;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.10\";s:3:\"p_1\";s:4:\"1.35\";s:3:\"p_0\";s:4:\"1.45\";}i:47;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.10\";s:3:\"p_1\";s:4:\"1.35\";s:3:\"p_0\";s:4:\"1.45\";}i:48;a:4:{s:4:\"kind\";s:1:\"1\";s:3:\"p_2\";s:4:\"0.09\";s:3:\"p_1\";s:4:\"5.99\";s:3:\"p_0\";s:4:\"9.98\";}i:49;a:4:{s:4:\"kind\";s:1:\"0\";s:3:\"p_2\";s:4:\"1.10\";s:3:\"p_1\";s:4:\"1.30\";s:3:\"p_0\";s:4:\"1.50\";}}',0),('ThirdPluginsList','a:14:{i:0;a:12:{s:4:\"name\";s:11:\"third_jiuwu\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:12:\"玖伍社区\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:10;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:1;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"登录账号\";s:8:\"password\";s:12:\"登录密码\";s:6:\"paypwd\";b:0;s:7:\"paytype\";s:12:\"支付方式\";}s:4:\"code\";s:5:\"jiuwu\";}i:1;a:12:{s:4:\"name\";s:10:\"third_yile\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"亿乐SUP\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.1\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:11;s:8:\"showedit\";b:0;s:6:\"showip\";b:1;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:5:\"AppID\";s:8:\"password\";s:6:\"秘钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:4:\"yile\";}i:2;a:12:{s:4:\"name\";s:13:\"third_kayixin\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"卡易信\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:13;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"客户编号\";s:8:\"password\";s:12:\"接口密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:7:\"kayixin\";}i:3;a:11:{s:4:\"name\";s:12:\"third_kayisu\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"卡易速\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:14;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"登录账号\";s:8:\"password\";s:12:\"登录密码\";s:6:\"paypwd\";s:12:\"支付密码\";s:7:\"paytype\";b:0;}s:4:\"code\";s:6:\"kayisu\";}i:4;a:12:{s:4:\"name\";s:15:\"third_kashangwl\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"卡商网\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"sort\";i:21;s:4:\"link\";s:0:\"\";s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"商家编号\";s:8:\"password\";s:12:\"接口密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:9:\"kashangwl\";}i:5;a:12:{s:4:\"name\";s:17:\"third_shangzhanwl\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"商战网\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:22;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:1;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"商家编号\";s:8:\"password\";s:12:\"接口密钥\";s:6:\"paypwd\";s:12:\"支付密码\";s:7:\"paytype\";b:0;}s:4:\"code\";s:11:\"shangzhanwl\";}i:6;a:12:{s:4:\"name\";s:15:\"third_shangmeng\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"商盟网\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:23;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"登录账号\";s:8:\"password\";s:12:\"接口密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:9:\"shangmeng\";}i:7;a:12:{s:4:\"name\";s:12:\"third_yiqida\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"亿奇达\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:23;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"登录账号\";s:8:\"password\";s:12:\"接口密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:6:\"yiqida\";}i:8;a:12:{s:4:\"name\";s:11:\"third_zhike\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"直客SUP\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:24;s:8:\"showedit\";b:0;s:6:\"showip\";b:1;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:8:\"应用ID\";s:8:\"password\";s:12:\"应用密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:5:\"zhike\";}i:9;a:12:{s:4:\"name\";s:12:\"third_yunbao\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:12:\"云宝发卡\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:26;s:8:\"showedit\";b:0;s:6:\"showip\";b:1;s:7:\"pricejk\";i:1;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"登录账号\";s:8:\"password\";s:12:\"对接密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:6:\"yunbao\";}i:10;a:12:{s:4:\"name\";s:13:\"third_kakayun\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:9:\"卡卡云\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:27;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:8:\"用户ID\";s:8:\"password\";s:12:\"对接密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:7:\"kakayun\";}i:11;a:12:{s:4:\"name\";s:16:\"third_liuliangka\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:15:\"发傲流量卡\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:31;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:0;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:8:\"用户ID\";s:8:\"password\";s:5:\"Token\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:10:\"liuliangka\";}i:12;a:12:{s:4:\"name\";s:13:\"third_qingjiu\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:12:\"晴玖商城\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:31;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:2;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:8:\"用户ID\";s:8:\"password\";s:12:\"对接密钥\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:7:\"qingjiu\";}i:13;a:12:{s:4:\"name\";s:13:\"third_daishua\";s:4:\"type\";s:5:\"third\";s:5:\"title\";s:15:\"同系统对接\";s:6:\"author\";s:6:\"彩虹\";s:7:\"version\";s:3:\"1.0\";s:4:\"link\";s:0:\"\";s:4:\"sort\";i:32;s:8:\"showedit\";b:0;s:6:\"showip\";b:0;s:7:\"pricejk\";i:1;s:5:\"input\";a:5:{s:3:\"url\";s:12:\"网站域名\";s:8:\"username\";s:12:\"登录账号\";s:8:\"password\";s:12:\"登录密码\";s:6:\"paypwd\";b:0;s:7:\"paytype\";b:0;}s:4:\"code\";s:7:\"daishua\";}}',0),('today_total','',0),('tongji','a:7:{s:6:\"orders\";s:5:\"10858\";s:7:\"orders1\";s:5:\"10858\";s:7:\"orders2\";s:2:\"14\";s:5:\"money\";d:54148.43;s:6:\"money1\";d:278.29;s:4:\"site\";s:5:\"12476\";s:4:\"gift\";N;}',0),('yesterday_total','a:3:{s:5:\"value\";s:9:\"105288.33\";s:10:\"createTime\";i:1686422452;s:10:\"updateTime\";i:0;}',0);
/*!40000 ALTER TABLE `shua_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_cart`
--

DROP TABLE IF EXISTS `shua_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_cart` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(32) NOT NULL,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `tid` int(11) NOT NULL,
  `input` text NOT NULL,
  `num` int(11) unsigned NOT NULL DEFAULT '1',
  `money` varchar(32) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `blockdj` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_cart`
--

LOCK TABLES `shua_cart` WRITE;
/*!40000 ALTER TABLE `shua_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_category`
--

DROP TABLE IF EXISTS `shua_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sort_order` int(11) DEFAULT '0',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_category`
--

LOCK TABLES `shua_category` WRITE;
/*!40000 ALTER TABLE `shua_category` DISABLE KEYS */;
INSERT INTO `shua_category` VALUES (1,'都市',1,'2025-03-25 05:03:26'),(2,'言情',2,'2025-03-25 05:03:26'),(3,'古装',3,'2025-03-25 05:03:26'),(4,'悬疑',4,'2025-03-25 05:03:26'),(5,'喜剧',5,'2025-03-25 05:03:26'),(6,'其他',6,'2025-03-25 05:03:26'),(7,'都市',1,'2025-03-25 05:22:23'),(8,'言情',2,'2025-03-25 05:22:23'),(9,'古装',3,'2025-03-25 05:22:23'),(10,'悬疑',4,'2025-03-25 05:22:23'),(11,'喜剧',5,'2025-03-25 05:22:23'),(12,'其他',6,'2025-03-25 05:22:23');
/*!40000 ALTER TABLE `shua_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_chapter_cache`
--

DROP TABLE IF EXISTS `shua_chapter_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_chapter_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL COMMENT '缓存键名',
  `value` text NOT NULL COMMENT '缓存内容',
  `expire_time` datetime NOT NULL COMMENT '过期时间',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='章节缓存表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_chapter_cache`
--

LOCK TABLES `shua_chapter_cache` WRITE;
/*!40000 ALTER TABLE `shua_chapter_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_chapter_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_class`
--

DROP TABLE IF EXISTS `shua_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_class` (
  `cid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '10',
  `name` varchar(255) NOT NULL,
  `shopimg` text,
  `block` text,
  `blockpay` varchar(80) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `cidr` int(11) DEFAULT NULL,
  `nr` text NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_class`
--

LOCK TABLES `shua_class` WRITE;
/*!40000 ALTER TABLE `shua_class` DISABLE KEYS */;
INSERT INTO `shua_class` VALUES (1,1,1,'123123',NULL,NULL,NULL,1,0,'');
/*!40000 ALTER TABLE `shua_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_config`
--

DROP TABLE IF EXISTS `shua_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_config` (
  `k` varchar(32) NOT NULL,
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_config`
--

LOCK TABLES `shua_config` WRITE;
/*!40000 ALTER TABLE `shua_config` DISABLE KEYS */;
INSERT INTO `shua_config` VALUES ('404','平台维护中！最快明天恢复最迟后天恢复！！！'),('adminlogin','2025-06-08 22:31:41'),('admin_pwd','wangji520..'),('admin_user','admin'),('alert','1.请你知悉并确认，会员服务为虚拟产品，开通分站会员服务后，若你中途主动取消服务或要求终止资格，你已支付的开通分站会员服务费用将不予退还，不支持退款。\r\n2.如你有其他与服务售后相关的问题，可以通过平台工单形式联系客服进行反馈。\r\n3.如使用会员服务过程中出现纠纷，你应当与本平台友好协商解决。\r\n4.建立合作关系后的合作有效期内，客户购买产品课程并支付订单为有效订单，您可获得推广提成。\r\n5.开通分站后务必在后台准确填写收款结算信息，佣金支持随时可结算，提现后，推广提成会结算至后台填写的收款账户中，推广费用以实际到账金额为准。'),('alipay2_api','0'),('alipay_account',''),('alipay_api','3'),('alipay_appid','2021003142611405'),('alipay_key',''),('alipay_pid',''),('alipay_privatekey','MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCFgYriai5wrERfnxcokqRFYtuXwRteuGMd7Ail1YFGqs3RgRbTo8ecpnG+9hvsPvp7qteP4WXefI6cD/4Z7T/SsaE3teYccz/k+EV/2AzXiUHmL2gdh34ZKVQSU43EG8qNWubDaLKxCQINNtAVDjUVzChcaX6PfPv2LHco6wzSuO2n7GXj+U6WlNL4cLor5dgmuz8sLgc1Ubf0R2jCTkBAiSYeW5zaqIR/d++EBJ1KUE8CKJw/FjxOEu1LhDm1C01aUthQmrY6b9EVW9JtpPVobbV7hsXbYSZtZlvbXX2a623lRwhrST2xu19etomRKMF56JgdlOZ7H3Yxsx/6CnBTAgMBAAECggEAPEtbDBJkUuKaJo1VCi/gHK4e+pRt5gNkUDkvVqXsMpwlQ/8G7iJDnk3VhSblSXbP7VYk8IgdV7PtPDJE0jTB5EfwxZZTDHpIWwJ9cNWERztoFatHXD67BmYR4Az4M59RhTGOkqYQPcARuyIMK9Y0x1liyQmj7O3HZVCDqGkva2MHtpoYCHcBN9Sl0xwqenYHNGcl8L4f9kMRJxlnetSUgpawJxESYJPemX7iJCgtbge+rN24bmUQUQLwZ9gm59Ovca58vdpVXaPmLym1tlkV8zHuaHzbOsrJdmUFCWnHglWeJsf5ckB0p4672AXOnSdiqBx8Epy0JC1Ak0w0Z9s3AQKBgQD1u7kumxZnhwzQ8iMqIIKWVjeO+kcHVZAnv13Mm4XVG35MjzLAVJFTSVSz9IQivAHgt1OO+2W2fhW1aK44XCOzihl9Cw3lId4PhXHs/vtCxwS76G8eK17ZFMWnNQrFgkWKX7aDvzRx3nevcYJ70fw05ddqGgg0ilIaaWGwhi4vkwKBgQCLFXnSoyzkD5EpalKieq6CIGDdDQEP7HYqEzpEiGFcJ3QsfXy/GiViU8qq60jm/4gvLqZM1dTTdvFtAL18ojXh+INE60tvckSs98HcKOiXqOa8+XQrb4mXWyqdLAQSg65gyzUB5NIwc0fPNoRqt7xV62ocDG8taNL9xyKTjc+0QQKBgDgX0f3BiSu4uEQ6AazsHuMSazxIBU63SWDJXjAGkFZFIF95kKw2obXRV3R8rG0BXaxeLMTGvkEaJvpyGtLitYOWUpsomtNjF72TSJ5A1jo4E+yQ8kyAufXzt+qjQQI06orbrl4El4QmtEtoLdD17gxlKvcdWiIkMPm2dIZHGuPhAoGAcqpnAFjVNsT6yalFrXz1iITHNxd0i7tIhFHL1RqXIhHI/rEKesdEkPdB/H30YbXEghm0s14Lk7fZPSPCFRHq2H05REW0sJIFupCcvDTh5b8zVBNZSkIvrqBT1ramONlll5mQ/xm2jIlOZ7CrpQwL8s4ecjdyJX7pjJ5zjDD7T8ECgYEA1mdGWv3M7alBt8oKRHX0e1ynlG37PhZav59+xMLyAx51DD+lQCTcIv+zTTF5eJp9Z24YlQFJlTb0v+AnqNOJFaKykRghJwv7EQ1ZItgEUeIfo7ujhwPTQEAVoHZi7FbKZ1oaU7DFAIdhOi8zTWB8+va9U4GRlXTlmbcBWURjxmk='),('alipay_publickey','MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAn9JWpw/UVsx2oa9969HLdxpF0SlZqmdf4NgWQtnc3D60m19oN3GkTzPLs5lI9fKQ/p6iDKc0obyCAMT+n4ODa5fAPoUV06euuusItZ+yPnHxMg40i9DKIpyi6yJXBc+xTpL9//t1ALMMR5nxzqVAVEqnAq5pI6iV2wqPlUMqJrEqUfy5hUcCG6Th9KhNu8kk5mv8mKXlxmHUUAi8CufEIcyky7bBpTf8FXxzjjLjhqTvUGy0u1M53oJuYecT2cDFON61IBR+7B77hjoLTUcIUqpHupEv220UKE3AIjIyjuSLXG4EuJreD5MnQwypMosLIVxlyypEKTKNiU4y52wDqQIDAQAB'),('anounce','<section data-role=\"outer\" label=\"Powered by 135editor.com\" style=\"\"><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section><p style=\"vertical-align:inherit;\"><marquee behavior=\"alternate\" style=\"display: inline-block; overflow: hidden; text-align: initial; white-space: nowrap; box-sizing: border-box; margin: 0px; padding: 0px;\"><section style=\"will-change: transform;\" class=\"\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; font-size: 18px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: red; font-size: 18px;\"><strong>平台24小时自助下单，欢迎代理加盟~</strong></span></strong></span></section></marquee></p><section class=\"_135editor\" data-tools=\"135\" data-id=\"95165\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; color: rgba(0, 0, 0, 0.87); font-size: 14px; background-color: rgb(255, 255, 255); font-family: Avenir, Helvetica, Arial, sans-serif;\"><section style=\"box-sizing: border-box; margin: 15px 0px; padding: 0px; display: block; text-align: center;\"><section data-width=\"100%\" style=\"box-sizing:border-box; margin: 0px; padding: 15px; display: block; border-image: initial; border-width: 1px 1px 3px; border-radius: 15px; border-style: solid; border-color: #057df5; background-color: #ffffff; width: 100%;\"><section style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">下单须知</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">下单前请看清商品的介绍，不要填错下单所需要填写的正确邮箱账号！</span></p></section></section><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">付款注意事项</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">付款请打开手机浏览器付款！电脑网页暂不支持付款，最好注册登录账号购买！如出现付款不出码/请第一时间点击网站订单找/如还是没有请填写工单联系客服处理。</span></p></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">同款平台搭建</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">1.如果你也希望拥有一间属于自己的项目商城小店</span></strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">2.可以在会员中心注册→</span><span style=\"background-color: #ffff00;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000; background-color: #ffff00;\"><a href=\"/user/regsite.php\" target=\"_blank\" style=\"color: #ff0000; text-decoration-line: underline; background-color: #ffff00;\">开通分站</a></span></span></strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">3.一键拥有自己的商城→享受特价购买项目</span></strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><strong><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #ff0000;\">4.每天轻松推广就可以赚佣金</span></strong></p></section></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">我们承诺</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">本平台是虚拟资源项目分享网站，所有的项目皆为市面上别处卖几百到几千不等的项目，本站仅作分享揭秘，项目教程内均包含全套教程讲解，其他问题请自行研究。</span></p></section></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">售后须知</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\">不提供任何项目一对一教学指导！如遇项目和谐等不可抗拒因素无售后，项目分享利润极低可能给你不了伺候大爷般的服务态度，用户请仔细阅读本站条例，虚拟物品具有可复制性，一经拍下发货，视为认可项目注意事项说明！概不退款！如有问题可提交工单反馈！</p></section></section></section><section class=\"_135editor\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block;\"><section style=\"box-sizing: border-box; margin: 6px 0px 0px; padding: 0px; display: flex; justify-content: flex-start; align-items: flex-start;\"><section style=\"box-sizing:border-box; margin: 4px 0px 0px; padding: 0px; display: block; width: 26px;\"><section style=\"box-sizing:border-box; margin: 0px; padding: 0px; display: block; width: 26px; height: 20px;\"><section style=\"box-sizing: border-box; margin: 0px 0px -14px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; border: 2px solid rgb(5, 125, 245); overflow: hidden;transform: rotate(0deg);-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);-o-transform: rotate(0deg);\"></section><section style=\"box-sizing: border-box; margin: 0px 0px 0px 4px; padding: 0px; display: block; width: 16px; height: 16px; border-radius: 100%; background: rgb(187, 187, 248); overflow: hidden;\"></section></section></section><section class=\"135brush\" data-brushtype=\"text\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; display: block; flex: 1 1 0%; font-size: 16px; color: #057df5; text-align: justify; letter-spacing: 1.5px;\"><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px;\">免责申明</strong></p><p style=\"vertical-align:inherit;box-sizing: border-box; margin: 0px; padding: 0px; color: #333333; font-size: 15px;\"><span open=\"\" helvetica=\"\" font-size:=\"\" text-align:=\"\" display:=\"\" inline=\"\" style=\"box-sizing: border-box; margin: 0px; padding: 0px; color: #646464;\">客服在线时间：早上12点至晚上24点，售后问题会在此时间段给予处理。</span></p></section></section></section></section></section></section><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section><section class=\"_135editor\" data-role=\"paragraph\"><p style=\"vertical-align:inherit;\"><br></p></section></section>'),('api01',''),('api02',''),('apikey','045084ea263aa170269869356696118a'),('appalert','APP下载'),('appcreate_default','1'),('appcreate_diy','1'),('appcreate_key','f6f5053701ec6e17c19722cad3fd4459'),('appcreate_nonav','0'),('appcreate_open','1'),('appcreate_price','2'),('appcreate_price2','1'),('appcreate_theme','#00BFFF'),('appurl','/?mod=app&id=2'),('articlenum','10'),('article_rewrite','0'),('banner','/666.webp'),('blacklist',''),('bottom',''),('build','2016-01-15'),('cache',''),('captcha_open_free','1'),('captcha_open_reg','1'),('cdnpublic','1'),('chatframe',''),('cishu','3'),('cjcishu','1'),('cjmoney','0'),('cjmsg','测试中'),('classblock','0'),('codepay_id','1002'),('codepay_key','ihiRBeMo4cHi6E2gI2RMrCdKXoM76Mdx'),('cos01',''),('cos02',''),('cronkey','353450'),('cron_lasttime','2025-05-24 00:40:01'),('daiguaurl',''),('datepoint','a:7:{i:0;a:3:{s:4:\"date\";s:4:\"0608\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:1;a:3:{s:4:\"date\";s:4:\"0607\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:2;a:3:{s:4:\"date\";s:4:\"0605\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:3;a:3:{s:4:\"date\";s:4:\"0604\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:4;a:3:{s:4:\"date\";s:4:\"0602\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:5;a:3:{s:4:\"date\";s:4:\"0529\";s:6:\"orders\";s:3:\"105\";s:5:\"money\";d:88.54;}i:6;a:3:{s:4:\"date\";s:4:\"0528\";s:6:\"orders\";s:2:\"99\";s:5:\"money\";d:209.42;}}'),('defaultcid','892'),('description','专注移动互联网创业赚钱实战项目，不定期分享项目实战、项目思维、案例，带你一起走向致富之路。平台提供百万价值的实战培训资料，精准引流爆粉实战，实操网络知识项目，分享流量变现的实操方法与技巧'),('epay_key','5553ZNV55kblE507gBk753E72S56g577'),('epay_key2','5553ZNV55kblE507gBk753E72S56g577'),('epay_pid','197327540'),('epay_pid2','197327540'),('epay_url','https://mzf.zfbtx.top/'),('epay_url2','https://mzf.zfbtx.top/'),('faka_input','0'),('faka_inputname','随便输入，或输入邮箱'),('faka_mail','<b>商品名称：</b> [name]<br/><b>购买时间：</b>[date]<br/><b>以下是你的卡密信息：</b><br/>[kmdata]<br/>----------<br/><b>使用说明：</b><br/>[alert]<br/>----------<br/>全民易站<br/>[domain]'),('faka_showleft','1'),('fanghong_api','0'),('fanghong_type','3'),('fanghong_url',''),('fenzhan_adds','1'),('fenzhan_buy','1'),('fenzhan_cost','10'),('fenzhan_cost2','20'),('fenzhan_daifu','1'),('fenzhan_domain','wamsg.cn,baiduw.top,yidegouw.com,58kanong.com,bbscs.xyz'),('fenzhan_editd','2'),('fenzhan_edithtml','1'),('fenzhan_expiry','0'),('fenzhan_free','0'),('fenzhan_gift',''),('fenzhan_jiakuanka','1'),('fenzhan_kfqq','1'),('fenzhan_page','0'),('fenzhan_price','10'),('fenzhan_price2','88'),('fenzhan_pricelimit','0'),('fenzhan_rank','0'),('fenzhan_regalert','1'),('fenzhan_regrand','0'),('fenzhan_remain','www.58kanong.com,www.baiduw.top,www.wamsg.cn,www.yidegouw.com,www.bbscs.xyz'),('fenzhan_skimg','1'),('fenzhan_template','0'),('fenzhan_tixian','1'),('fenzhan_tixian_alipay','1'),('fenzhan_tixian_qq','0'),('fenzhan_tixian_wx','0'),('fenzhan_upgrade','68'),('footer','此站点任何信息【包括销售商品】跟平台程序运营者无关，\r\n如有问题请联系客服页面的站长，点此?查看免责条款。\r\n搭建同款无限版主站'),('forcelogin','0'),('forceloginhome','0'),('forcermb','0'),('gg_announce',''),('gg_panel',''),('gg_search','<span class=\"label label-primary\">待处理</span> 说明正在努力提交到服务器！<p></p><p></p><span class=\"label label-success\">已完成</span> 已经提交到接口正在处理！<p></p><p></p><span class=\"label label-warning\">处理中</span> 已经开始为您开单 请耐心等！<p></p><p></p><span class=\"label label-danger\">有异常</span> 下单信息有误 联系客服处理！'),('gift_log','1'),('gift_open','0'),('hide_tongji','0'),('index_class_num_style','8'),('invite_content','专注移动互联网创业赚钱实战项目，不定期分享项目实战、项目思维、案例，带你一起走向致富之路。平台提供百万价值的实战培训资料，精准引流爆粉实战，实操网络知识项目，分享流量变现的实操方法与技巧，各种最新热门项目帮助你快速致富！'),('invite_tid','1'),('iskami','1'),('jiamengzhuzhan',''),('jiangli','5'),('jiangli2','15'),('jiangli3','30'),('keywords','互联网创业项目'),('kfqq','188988850'),('kfwx','188988850'),('login_apiurl','23'),('login_appid','23'),('login_appkey','23'),('login_qq','0'),('login_wx','0'),('mail_apikey','LTAI5tFL2gKENS3xbyZwi8J4'),('mail_apiuser','7pvX2zZJLn4aFga6DGureYoKIHubZx'),('mail_cloud','0'),('mail_name','qmyz@foxmail.com'),('mail_name2',''),('mail_port','465'),('mail_pwd','fqmsnxrzdwlfbgia'),('mail_recv',''),('mail_smtp','smtp.qq.com'),('maintain','20250609'),('message_buy','1'),('message_duijie','1'),('message_type','0'),('message_workorder','1'),('micropayapi','2'),('micropay_key','2uzcqowg4guuco7ncwqnchkch8n42yyo'),('micropay_mchid','http://yzf.bbscs.xyz/Pay_Index.html'),('micropay_pid','220476828'),('modal','遇到了问题，请到会员中心-售后反馈内提交工单，客服会在12小时内回复并协助你处理。 遇到了问题，请到会员中心-售后反馈内提交工单，客服会在12小时内回复并协助你处理。\r\n'),('musicurl',''),('openbatchorder','1'),('ordername','[time][order]'),('payapi','-1'),('payapi2','-1'),('paymsg','<hr/>小提示：<b style=\"color:red\">如果微信出现无法付款时，您可以把微信的钱转到QQ里，然后使用QQ钱包支付！<a href=\"./?mod=wx\" target=\"_blank\">点击查看教程</a></b>'),('pricejk_cid','893,925,905,924,923,904,435,892,329,75,915,116,433,434,9,12,306,436,55,56,10,46,21,20,11,45,48,50,19,43,2,1,291,292,293,330,331,332,333,334,335,336,337,338,339,340,341,342,343,344,345,346,347,444,465,467,469,470,471,472,473,474,478,953,954,481,482,483,484,485,486,487,488,489,490,491,494,1032,956,894,506,507,512,513,515,516,517,518,519,520,521,523,524,525,526,528,529,537,531,532,533,534,535,536,538,539,541,542,543,544,545,546,547,548,550,551,552,553,554,555,557,558,559,560,562,564,565,566,567,568,570,571,572,575,576,577,579,580,584,585,586,587,588,589,592,607,624,641,642,643,644,645,646,647,649,654,651,926,652,977,653,658,657,655,656,978,659,660,650,661,662,663,664,665,666,667,671,672,673,674,675,676,677,678,679,680,688,689,690,691,692,693,694,695,696,697,698,699,700,701,702,703,706,707,709,710,711,712,713,714,715,716,717,718,719,721,722,723,724,725,726,727,728,729,730,731,732,733,734,735,736,737,738,739,740,741,742,743,744,769,745,746,747,748,749,750,751,752,753,757,759,760,761,762,763,764,765,766,767,768,770,771,772,773,774,777,778,779,780,781,782,783,784,785,786,787,788,789,790,791,792,793,794,795,796,797,798,799,800,801,802,803,804,805,806,807,808,809,810,811,812,813,814,815,816,817,818,819,820,821,822,823,824,825,826,827,828,829,830,831,832,833,834,835,836,837,838,839,840,841,842,843,844,845,846,847,848,849,850,851,852,853,854,855,856,857,858,859,860,861,862,863,864,865,866,867,868,869,870,871,872,873,874,875,876,877,878,879,880,881,882,883,884,885,886,887,888,889,890,53,54,44,13,895,896,897,898,899,900,901,902,903,907,908,909,910,911,912,921,927,1207,928,929,930,931,932,981,934,933,935,936,937,938,939,940,941,922,914,942,943,944,945,946,975,976,952,947,948,949,951,950,955,957,958,959,960,961,962,963,964,965,966,967,968,970,971,972,973,974,1033,979,980,982,983,984,985,986,987,988,989,990,991,992,993,994,995,996,997,998,999,1000,1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1015,1016,1017,1018,1019,1020,1021,1022,1024,1023,1025,1026,1027,1028,1029,1030,1031,1035,1036,1037,1038,1039,1040,1041,1042,1043,1044,1045,1046,1047,1048,1049,1050,1051,1052,1053,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1070,1071,1072,1073,1074,1075,1076,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,1087,1089,1090,1091,1092,1093,1094,1095,1096,1097,1098,1099,1100,1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113,1114,1115,1116,1117,1118,1119,1120,1121,1122,1123,1124,1125,1126,1127,1128,1129,1130,1131,1132,1133,1134,1135,1136,1137,1138,1139,1141,1142,1143,1144,1145,1146,1147,1148,1149,1150,1151,1152,1153,1154,1155,1156,1157,1158,1159,1160,1161,1162,1163,1164,1165,1166,1167,1168,1169,1170,1171,1172,1173,1174,1175,1176,1177,1178,1179,1180,1181,1182,1183,1184,1185,1186,1187,1188,1189,1190,1191,1192,1193,1194,1195,1196,1197,1198,1199,1200,1201,1202,1203,1204,1205,1206,1208,1209,1210,1211,1212,1213,1214,1215,1216,1217,1218,1219,1220,1221,1222,1223,1224,1225,1226,1227,1228,1229,1230,1231,1232'),('pricejk_edit','0'),('pricejk_lasttime','2025-05-24 00:40:01'),('pricejk_status','ok'),('pricejk_time','600'),('pricejk_yile','0'),('qiandao_day','7'),('qiandao_limitip','1'),('qiandao_mult','1.01'),('qiandao_reward','0.1|0.1|0.1'),('qqjump','0'),('qqpay_api','0'),('qqpay_key',''),('qqpay_pid',''),('qqzf',''),('qqzfid',''),('qqzfkey',''),('queryorderlimit','1'),('recharge_min',''),('rgb01','9a58f5'),('rwbt_01','御剑八荒（每日新区）点击下载'),('rwbt_02','狂暴传奇（每日新区）点击下载'),('rwbt_03','大侠无限刀（每日新区）点击下载'),('rwbt_04','三国封魔传（每日新区）点击下载'),('rwbt_05','龙翔复古（每日新区）点击下载'),('rwbt_06','王者战歌（每日新区）点击下载'),('rwbt_07','逍遥火龙（每日新区）点击下载'),('rwbt_08','天命传说（每日新区）点击下载'),('rwbt_09','诸天神龙（每日新区）点击下载'),('rwbt_10','传说之城（每日新区）点击下载'),('rwbt_11','龙腾天下2（每日新区）点击下载'),('rwbt_12','自由之刃（第二季）（每日新区）点击下载'),('rwbt_13','1.76复古传奇（每日新区）点击下载'),('rwbt_14','黑暗光年（每日新区）点击下载'),('rwbt_15','新自由之刃1（每日新区）点击下载'),('rwbt_16','新自由之刃二（每周新区）点击下载'),('rwbt_17','1.76复古传奇1（每日新区）点击下载'),('rwbt_18','龙翔冰雪（每日新区）点击下载'),('rwbt_19','冰雪归来 2024.2月出品（每日新区）点击下载'),('rwbt_20',''),('rwkq_01','1'),('rwkq_02','1'),('rwkq_03','1'),('rwkq_04','1'),('rwkq_05','1'),('rwkq_06','1'),('rwkq_07','1'),('rwkq_08','1'),('rwkq_09','1'),('rwkq_10','1'),('rwkq_101','sdfsfsd'),('rwkq_102','sdfsdfsdf'),('rwkq_103','大范甘迪示范岗'),('rwkq_104',''),('rwkq_105',''),('rwkq_11','1'),('rwkq_12','1'),('rwkq_13','1'),('rwkq_14','1'),('rwkq_15','1'),('rwkq_16','1'),('rwkq_17','1'),('rwkq_18','1'),('rwkq_19','1'),('rwkq_20','0'),('rwlj_01','https://hw.fuhua58.com/mobile/Downfile/index?gid=119&pid=1775'),('rwlj_02','https://hw.fuhua58.com/mobile/Downfile/index?gid=113&pid=1775'),('rwlj_03','https://hw.fuhua58.com/mobile/Downfile/index?gid=107&pid=1775'),('rwlj_04','https://hw.fuhua58.com/mobile/Downfile/index?gid=105&pid=1775'),('rwlj_05','https://hw.fuhua58.com/mobile/Downfile/index?gid=103&pid=1775'),('rwlj_06','https://hw.fuhua58.com/mobile/Downfile/index?gid=101&pid=1775'),('rwlj_07','https://hw.fuhua58.com/mobile/Downfile/index?gid=99&pid=1775'),('rwlj_08','https://hw.fuhua58.com/mobile/Downfile/index?gid=129&pid=1775'),('rwlj_09','https://hw.fuhua58.com/mobile/Downfile/index?gid=93&pid=1775'),('rwlj_10','https://hw.fuhua58.com/mobile/Downfile/index?gid=89&pid=1775'),('rwlj_11','https://hw.fuhua58.com/mobile/Downfile/index?gid=83&pid=1775'),('rwlj_12','https://hw.fuhua58.com/mobile/Downfile/index?gid=127&pid=1775'),('rwlj_13','https://hw.fuhua58.com/mobile/Downfile/index?gid=121&pid=1775'),('rwlj_14','https://hw.fuhua58.com/mobile/Downfile/index?gid=11&pid=1775'),('rwlj_15','https://hw.fuhua58.com/mobile/Downfile/index?gid=3&pid=1775'),('rwlj_16','https://hw.fuhua58.com/mobile/Downfile/index?gid=5&pid=1775'),('rwlj_17','https://hw.fuhua58.com/mobile/Downfile/index?gid=39&pid=1775'),('rwlj_18','https://hw.fuhua58.com/mobile/Downfile/index?gid=37&pid=1775'),('rwlj_19','https://hw.fuhua58.com/mobile/Downfile/index?gid=117&pid=1775'),('rwlj_20',''),('rwnr_01','骷髅传奇高爆版，上线就送绝版时装+500路费 上线一只小骷髅，多种时装随你转化！'),('rwnr_02','每日下午15时准点开新区！激情四射'),('rwnr_03','专属无限刀白嫖版本，上线就送自动回收/自动拾取等等，散人的天堂，地图无限制·爆率全开放'),('rwnr_04','多职业MMOARPG手游，沿用了战法道的游戏设定，进阶转职玩法，在保留原味的复古的同时，实现了九职业进阶转化玩法'),('rwnr_05','经典1.76三职业特色复古，一切靠打，游戏平衡 融入了沉默、神器、冰雪等特色元素，玩法丰富!'),('rwnr_06','上线送500路费+满级会员+自动拾取+自动回收+冰龙'),('rwnr_07','火龙冰雪融合版本，创新，丰富 上线自动回收.自动拾取.赞助，冠名免费白嫖'),('rwnr_08','一款经典武侠传奇打金手游，上线即可免费领满VIP，首冲免费送，打怪就能爆真充，不肝不氪，散人也能玩转传奇。'),('rwnr_09','无暗坑，充值可爆，零氪当大佬。可激情，可扛米'),('rwnr_10','开局【满赞助】上线送300路费，送自动回收，自动拾取送冰龙'),('rwnr_11','1.80微变电竞传奇上线就送1万真实充值,充值0.1折纯元宝服，充值货币'),('rwnr_12','只为给万千传奇兄弟打造真正的免费打金服。游戏独创特色魂环系统、战灵系统、守护系统，承诺所有野外地图无限制进入，打出实物-秒回收灵符，BOSS也能爆出炫酷时装'),('rwnr_13','游戏中延续了战、法、道三大职业的设定，并采用了大世界、多人在线等玩法'),('rwnr_14','散人激情，酷炫，一切全靠爆！'),('rwnr_15','1.76三职业魂环攻速常规服，抖音魂环赞助传奇同款， 百万年魂环上线满攻速，会员免费，千倍爆率'),('rwnr_16','登录送满V，88自动拾取回收，顶赞188（送异火），做任务得魂环，3000送龙'),('rwnr_17','上‮就线‬送1000真实充‮大值‬礼包，单‮币货‬服，打怪掉落，装‮回备‬收获‮的得‬元宝就‮充是‬值货币'),('rwnr_18','零氪玩通关进游就送5000真实充值自动回收，自动拾取，切割称号免费送'),('rwnr_19','全网首款 冰雪三职业连击版本，带给你不一样的体验'),('rwnr_20',''),('rwtp_01','https://hwcdn.fuhua95.com/game/20240115/d6346fc411a80c796b86537c6ed26b84.png'),('rwtp_02','https://hwcdn.fuhua95.com/game/20240108/657f863664a07932e0645ea4781cea02.png'),('rwtp_03','https://hwcdn.fuhua95.com/game/20231225/6c4aa421f788b353dabe5111d91c7a50.png'),('rwtp_04','https://hwcdn.fuhua95.com/game/20231128/f8b929c79ab14c1023ea37ec6264d69e.jpg'),('rwtp_05','https://hwcdn.fuhua95.com/game/20231115/d87ed2caf7a22fea3d3545d0a78104f3.png'),('rwtp_06','https://hwcdn.fuhua95.com/game/20231104/1677cc83444ffa356a98c5bdf54595ea.jpg'),('rwtp_07','https://hwcdn.fuhua95.com/game/20231022/a7416547ed00eaed371c80ae18519df0.png'),('rwtp_08','https://hwcdn.fuhua95.com/game/20240325/da67dc7e5519f11c08257abfa4c1444e.png'),('rwtp_09','https://hwcdn.fuhua95.com/game/20230919/cb11eb26580a87be0b6857db30673683.png'),('rwtp_10','https://hwcdn.fuhua95.com/game/20230919/2ff619ee5d82716e06ba6196dcb20ba4.png'),('rwtp_101','32123132'),('rwtp_102','sdfsf'),('rwtp_103','电饭锅电饭锅'),('rwtp_104',''),('rwtp_105',''),('rwtp_11','https://hwcdn.fuhua95.com/game/20230901/a34d3b4cd720a8fbe6dd5df43dc62849.png'),('rwtp_12','https://hwcdn.fuhua95.com/game/20240320/3c7b2319867bcfb02790c58c245da783.png'),('rwtp_13','https://hwcdn.fuhua95.com/game/20240228/0915ca1abc92e8a813dacd3ef3e7ae61.png'),('rwtp_14','https://hwcdn.fuhua95.com/game/20230115/ddb47211012cbc5f00ff818b79be60bd.png'),('rwtp_15','https://hwcdn.fuhua95.com/game/20230111/67803c1a3fe3b3e8dae1bf16b16269f7.png'),('rwtp_16','https://hwcdn.fuhua95.com/game/20230111/c59c967fcebe32b455301e602f2c0a08.png'),('rwtp_17','https://hwcdn.fuhua95.com/game/20230411/903af4927730f95c29d82e72c2dc6b0d.png'),('rwtp_18','https://hwcdn.fuhua95.com/game/20230419/050690bb8a1d399d0a061744608ce18d.jpg'),('rwtp_19','https://hwcdn.fuhua95.com/game/20240129/8e2179aa3bb73e6775eb45d1280223a5.png'),('rwtp_20',''),('search_open','1'),('selfrefund','0'),('shopdesc_editor','1'),('shoppingcart','1'),('show_changepwd','1'),('show_complain','1'),('sitename','58全民易站'),('sitename_hide','1'),('speedy_list','从新挑选一件商品，把链接或者标题完整发我！仅更换一次^好的 我们会尽快核实为您处理！^可以看一下平台的别的课程，可以帮您免费调换一次哦，如果需要调换课程，请提交工单注明需要的【课程名字】，感谢您的支持！^由于商品过多，无法批量更改，可以选择4个分类批量更改，其他的手动更改，站长群里都有说过^资源都是由全国的作者和商家提供，却有此业务，具体收益还需自行研究，咱们平台只作为资源分享，有些确有价值有些未经详细测试还需，自行研究！^1.关闭杀毒软件 2.删出文件和压缩包 3.重新去网盘内下载 4.解压按教程操作^如果您觉得该课程对您的帮助不大的情况下，可以看一下平台的别的课程，可以帮您免费调换一次哦，如果需要调换课程，请提交工单注明需要的【课程名字】，感谢您的支持！^咱们平台主要是做全网项目收集，外面割韭菜的项目在这里全部只需要几十块就可以买到了，并不存在欺骗，咱们平台不保证所有项目都是能赚钱的，只能确保项目的教程和附带的软件和外面的一模一样，并且是可以运行的!^如果您觉得该课程对您的帮助不大的情况下，可以看一下平台的别的课程，可以帮您免费调换一次哦，如果需要调换课程，请提交工单注明需要的【课程名字】，感谢您的支持！^该项目出现异常，已帮您做退款处理^该脚本维护中，已给您退回账户，您可以看看其他资源商品，或者等待维护好在从新购买！^刷分都有掉粉的可能性的 这个无法避免 只要在售后期24小时内才可以售后 兄弟^核实该脚本已和谐，为您做退回处理^删除手机现有APP从新安装不要更新，会员功能全部能正常使用，^确定提供的链接上补单链接，掉粉账号？^https://v.douyin.com/iFPkdysj/(替换自己刷粉的补粉链接)\n初始0.下单1000.现在剩899.申请补101个粉丝 按照这个格式发我^你提交的格式链接与你本次提交的订单号不匹配，^风控一般正常24小时内完成^在未开始刷之前，把视频链接下架，机房上粉失败，会自动给你退回，退回时间已机房为准，如已陆续在上粉状态，不可操作，否则中途中断，无任何售后！^高速粉渠道有点慢你可以 下最新上的闪电粉比较高效率^关闭杀毒软件 打开补丁 再点开软件^已退^已帮你申请拦截退单，如明天下午没成功可以在此 回复^该通道没有凭证 如果需要有凭证的，这两天可以申请上架，^几万件商品 每天都在上上下下 根本检测不完，而且很多老口子 时间过去 但是依然有效，由于您账户状况良好，可以给您直接退单一次'),('sptjzsjg01','快速放款'),('sptjzsjg02','高价兑现'),('sptjzsjg03',''),('sptjzslj01','https://d.kanongquan.net/s/PmZKx'),('sptjzslj02','89972'),('sptjzslj03',''),('sptjzstp01','https://weixin-att.kanongyun.com/2024-02-22_65d725676f41d.png'),('sptjzstp02','https://cdn.vitfintech.com/statics/weapp/icon/icon-jifenjiage.png'),('sptjzstp03',''),('sptjzswz01','贷款超市'),('sptjzswz02','信用卡积分'),('sptjzswz03',''),('spxq_xx','购买商品成功，祝您事事顺心！'),('spzs_car','0'),('spzs_sales','0'),('staticurl','qmcy.58kanong.com'),('style','1'),('syggw_car','1'),('syskey','JJWi0vQqcD09iCPQZrJ0wJrPyD0J9Jp8'),('template','storenews'),('template_m','0'),('template_showprice','0'),('template_showsales','0'),('template_virtualdata','1'),('tenpay_api','0'),('tenpay_key',''),('tenpay_pid',''),('title','创业项目基地'),('tixian_days',''),('tixian_js','你提交的提现申请将会在24小时内到账，请耐心等待客服打款。'),('tixian_limit','1'),('tixian_min','10'),('tixian_rate','97'),('tongji_cachetime','1745682685'),('tongji_time','100'),('txurl',''),('ui_background','3'),('ui_backgroundurl','//cn.bing.com/th?id=OHR.LaternFestival2024_ZH-CN8050981828_1920x1080.jpg&rf=LaDigue_1920x1080.jpg&pid=hp'),('ui_bing','1'),('ui_bing_date','20240224'),('ui_color1',''),('ui_color2',''),('ui_colorto','0'),('ui_shop','0'),('ui_user','0'),('updatestatus','1'),('updatestatus_interval','1'),('updatestatus_lasttime','2025-05-24 00:44:01'),('user_level','0'),('user_open','1'),('verify_open','0'),('version','2055'),('wechat_api','1'),('wechat_apptoken','AT_Xp7VvuIqCRJNBoOlRwm2Q1amHqSM5Ac7'),('wechat_appuid','23004'),('wechat_sckey','SCT134077TsY25pgvTTQcCgh0Ny2wzefxk'),('wenzhangdizhi',''),('work',''),('workorder_open','1'),('workorder_pic','1'),('workorder_type','问题反馈|订单链接错误|充值没到账|求项目资源|其他问题'),('wxpay_api','8'),('wxpay_appid','wx04bebd85c96a7faf'),('wxpay_appsecret','ef928db77b17a3062917d9bfe9f0f860'),('wxpay_domain',''),('wxpay_key','1t0flb2mrajaatjul5nhyaeitvhizbmp'),('wxpay_mchid','1604815382'),('wxzf','http://mq.bbscs.xyz/'),('wxzfid','1'),('wxzfkey','56df26c08d326c85a67c2490475a3e69'),('wywz03',''),('xwgg','24小时自动发货'),('xwgg_car','1'),('ywsm1',''),('zfb',''),('zfbid',''),('zfbimg',''),('zfbkey','');
/*!40000 ALTER TABLE `shua_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_content_check_log`
--

DROP TABLE IF EXISTS `shua_content_check_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_content_check_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL COMMENT '内容类型',
  `content_id` int(11) NOT NULL COMMENT '内容ID',
  `check_time` datetime NOT NULL COMMENT '审核时间',
  `check_user` varchar(50) NOT NULL COMMENT '审核人',
  `check_result` tinyint(1) NOT NULL COMMENT '审核结果',
  `check_reason` text COMMENT '审核原因',
  PRIMARY KEY (`id`),
  KEY `type_content_id` (`type`,`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='内容审核日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_content_check_log`
--

LOCK TABLES `shua_content_check_log` WRITE;
/*!40000 ALTER TABLE `shua_content_check_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_content_check_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_duanju`
--

DROP TABLE IF EXISTS `shua_duanju`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_duanju` (
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `cid` int(11) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '10',
  `name` varchar(255) NOT NULL,
  `value` int(11) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prid` int(11) NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prices` varchar(100) DEFAULT NULL,
  `input` varchar(2500) NOT NULL,
  `inputs` varchar(255) DEFAULT NULL,
  `desc` text,
  `alert` text,
  `shopimg` text,
  `validate` tinyint(1) NOT NULL DEFAULT '0',
  `valiserv` varchar(15) DEFAULT NULL,
  `min` int(11) NOT NULL DEFAULT '0',
  `max` int(11) NOT NULL DEFAULT '0',
  `is_curl` tinyint(1) NOT NULL DEFAULT '0',
  `curl` varchar(255) DEFAULT NULL,
  `repeat` tinyint(1) NOT NULL DEFAULT '0',
  `multi` tinyint(1) NOT NULL DEFAULT '0',
  `shequ` int(3) NOT NULL DEFAULT '0',
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `goods_type` int(11) NOT NULL DEFAULT '0',
  `goods_param` text,
  `close` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  `sales` int(11) NOT NULL DEFAULT '0',
  `stock` int(11) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `pro` int(11) NOT NULL,
  `wx_test` varchar(255) DEFAULT NULL,
  `tc` decimal(11,1) NOT NULL COMMENT '提成',
  `zyurl` varchar(255) DEFAULT NULL,
  `daim` text,
  `top` int(10) NOT NULL DEFAULT '0',
  `toptime` datetime NOT NULL,
  `last` int(10) NOT NULL DEFAULT '0',
  `inzid` longtext,
  PRIMARY KEY (`tid`),
  KEY `cid` (`cid`),
  KEY `price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_duanju`
--

LOCK TABLES `shua_duanju` WRITE;
/*!40000 ALTER TABLE `shua_duanju` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_duanju` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_duanju_class`
--

DROP TABLE IF EXISTS `shua_duanju_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_duanju_class` (
  `cid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常 0禁用',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短剧分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_duanju_class`
--

LOCK TABLES `shua_duanju_class` WRITE;
/*!40000 ALTER TABLE `shua_duanju_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_duanju_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_duanju_order`
--

DROP TABLE IF EXISTS `shua_duanju_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_duanju_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `orderid` varchar(50) NOT NULL COMMENT '订单号',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `duanju_id` int(11) NOT NULL COMMENT '短剧ID',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0未支付 1已支付',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `paytime` datetime DEFAULT NULL COMMENT '支付时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`),
  KEY `duanju_id` (`duanju_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短剧订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_duanju_order`
--

LOCK TABLES `shua_duanju_order` WRITE;
/*!40000 ALTER TABLE `shua_duanju_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_duanju_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_faka`
--

DROP TABLE IF EXISTS `shua_faka`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_faka` (
  `kid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `km` varchar(255) DEFAULT NULL,
  `pw` varchar(255) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `usetime` datetime DEFAULT NULL,
  `orderid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`kid`),
  KEY `orderid` (`orderid`),
  KEY `tid` (`tid`),
  KEY `getleft` (`tid`,`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_faka`
--

LOCK TABLES `shua_faka` WRITE;
/*!40000 ALTER TABLE `shua_faka` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_faka` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_faquan`
--

DROP TABLE IF EXISTS `shua_faquan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_faquan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `nr` text NOT NULL,
  `img` text NOT NULL,
  `img2` text NOT NULL,
  `img3` text NOT NULL,
  `img4` text NOT NULL,
  `img5` text NOT NULL,
  `img6` text NOT NULL,
  `img7` text NOT NULL,
  `img8` text NOT NULL,
  `img9` text NOT NULL,
  `img10` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_faquan`
--

LOCK TABLES `shua_faquan` WRITE;
/*!40000 ALTER TABLE `shua_faquan` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_faquan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_fenzhan_top`
--

DROP TABLE IF EXISTS `shua_fenzhan_top`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_fenzhan_top` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` int(10) NOT NULL,
  `zid` int(10) NOT NULL,
  `time` bigint(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_fenzhan_top`
--

LOCK TABLES `shua_fenzhan_top` WRITE;
/*!40000 ALTER TABLE `shua_fenzhan_top` DISABLE KEYS */;
INSERT INTO `shua_fenzhan_top` VALUES (2,124756,11251,1744171277),(3,124755,11251,1744171288),(4,124754,11251,1744171290),(5,124753,11251,1744171292),(6,124752,11251,1744171298),(7,124751,11251,1744171303),(8,124750,11251,1744171305),(9,124749,11251,1744171308),(10,124747,11251,1744171312),(11,124746,11251,1744171336);
/*!40000 ALTER TABLE `shua_fenzhan_top` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_finance_order`
--

DROP TABLE IF EXISTS `shua_finance_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_finance_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_finance_order`
--

LOCK TABLES `shua_finance_order` WRITE;
/*!40000 ALTER TABLE `shua_finance_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_finance_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_fxbl`
--

DROP TABLE IF EXISTS `shua_fxbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_fxbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lv1` int(10) NOT NULL,
  `lv2` int(10) NOT NULL,
  `lv3` int(10) NOT NULL,
  `lv4` int(10) NOT NULL,
  `lv5` int(10) NOT NULL,
  `lv6` int(10) NOT NULL,
  `lv7` int(10) NOT NULL,
  `lv8` int(10) NOT NULL,
  `lv9` int(10) NOT NULL,
  `lv10` int(10) NOT NULL,
  `lv11` int(10) NOT NULL,
  `lv12` int(10) NOT NULL,
  `lv13` int(10) NOT NULL,
  `lv14` int(10) NOT NULL,
  `lv15` int(10) NOT NULL,
  `lv16` int(10) NOT NULL,
  `lv17` int(10) NOT NULL,
  `lv18` int(10) NOT NULL,
  `lv19` int(10) NOT NULL,
  `lv20` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_fxbl`
--

LOCK TABLES `shua_fxbl` WRITE;
/*!40000 ALTER TABLE `shua_fxbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_fxbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_gao`
--

DROP TABLE IF EXISTS `shua_gao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_gao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `cid` int(11) unsigned NOT NULL,
  `shopimg` text,
  `desc` text,
  `download` text,
  `wx_test` varchar(255) DEFAULT NULL,
  `is_price` varchar(255) DEFAULT NULL,
  `msg` text,
  `price` decimal(10,2) NOT NULL,
  `sales` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `zid` int(11) unsigned NOT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_gao`
--

LOCK TABLES `shua_gao` WRITE;
/*!40000 ALTER TABLE `shua_gao` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_gao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_gift`
--

DROP TABLE IF EXISTS `shua_gift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_gift` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `rate` int(3) NOT NULL,
  `ok` tinyint(1) NOT NULL DEFAULT '0',
  `not` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_gift`
--

LOCK TABLES `shua_gift` WRITE;
/*!40000 ALTER TABLE `shua_gift` DISABLE KEYS */;
INSERT INTO `shua_gift` VALUES (1,'测试奖品',57428,50,0,0),(2,'居家必备，整理收纳刚需品， 3个多小时纯赚1000多！',57422,80,0,0);
/*!40000 ALTER TABLE `shua_gift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_giftlog`
--

DROP TABLE IF EXISTS `shua_giftlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_giftlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL,
  `gid` int(11) unsigned NOT NULL,
  `userid` varchar(32) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `addtime` datetime DEFAULT NULL,
  `tradeno` varchar(32) DEFAULT NULL,
  `input` varchar(64) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_giftlog`
--

LOCK TABLES `shua_giftlog` WRITE;
/*!40000 ALTER TABLE `shua_giftlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_giftlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_goods`
--

DROP TABLE IF EXISTS `shua_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT '商品ID',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL COMMENT '售价',
  `cost` decimal(10,2) NOT NULL COMMENT '成本价',
  `class` int(11) NOT NULL COMMENT '分类ID',
  `content` text COMMENT '商品详情',
  `shopimg` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片链接',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1显示 0隐藏',
  `doc` varchar(255) DEFAULT NULL COMMENT '资源文档路径',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常/已审核 0待审核/未通过',
  `user_id` int(11) DEFAULT NULL COMMENT '投稿用户ID',
  `is_secret` tinyint(1) NOT NULL DEFAULT '0' COMMENT '隐藏信息购买可见',
  `is_admin_only` tinyint(1) NOT NULL DEFAULT '0' COMMENT '仅后台可见',
  `image` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `hidden_content` text COMMENT '隐藏内容（购买后可见）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_goods`
--

LOCK TABLES `shua_goods` WRITE;
/*!40000 ALTER TABLE `shua_goods` DISABLE KEYS */;
INSERT INTO `shua_goods` VALUES (1,0,'奥术大师大所多',111.00,0.00,0,'萨达大所多','','2025-06-08 23:00:16',1,'',1,NULL,0,0,'/uploads/resources/6845a5804959f.jpg',''),(19,0,'2222222222',333.00,0.00,0,'222222222','','2025-06-09 15:27:46',1,'',1,NULL,0,0,'/uploads/resources/68468cf2ef4b3.jpg','1111111111111');
/*!40000 ALTER TABLE `shua_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_invite`
--

DROP TABLE IF EXISTS `shua_invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_invite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(11) unsigned NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `qq` varchar(20) NOT NULL,
  `input` text NOT NULL,
  `key` varchar(30) NOT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `plan` int(11) unsigned NOT NULL DEFAULT '0',
  `click` int(11) unsigned NOT NULL DEFAULT '0',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `nid` (`nid`),
  KEY `qq` (`qq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_invite`
--

LOCK TABLES `shua_invite` WRITE;
/*!40000 ALTER TABLE `shua_invite` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_invite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_invitelog`
--

DROP TABLE IF EXISTS `shua_invitelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_invitelog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iid` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `orderid` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iid` (`iid`,`status`),
  KEY `iidip` (`iid`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_invitelog`
--

LOCK TABLES `shua_invitelog` WRITE;
/*!40000 ALTER TABLE `shua_invitelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_invitelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_inviteshop`
--

DROP TABLE IF EXISTS `shua_inviteshop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_inviteshop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `times` tinyint(1) NOT NULL DEFAULT '0',
  `value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sort` int(11) NOT NULL DEFAULT '10',
  `addtime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_inviteshop`
--

LOCK TABLES `shua_inviteshop` WRITE;
/*!40000 ALTER TABLE `shua_inviteshop` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_inviteshop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_ip`
--

DROP TABLE IF EXISTS `shua_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_ip` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ips` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_ip`
--

LOCK TABLES `shua_ip` WRITE;
/*!40000 ALTER TABLE `shua_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_ip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_kms`
--

DROP TABLE IF EXISTS `shua_kms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_kms` (
  `kid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '1',
  `km` varchar(255) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` timestamp NULL DEFAULT NULL,
  `usetime` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `orderid` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`kid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_kms`
--

LOCK TABLES `shua_kms` WRITE;
/*!40000 ALTER TABLE `shua_kms` DISABLE KEYS */;
INSERT INTO `shua_kms` VALUES (1,1,0,64832,5,'q5hsVCT00MNh3ZVlKZ',0.00,'2024-01-26 20:48:46',NULL,0,0),(2,1,0,64832,5,'Yc0HGuYwnrjR9GChYY',0.00,'2024-01-26 20:48:46',NULL,0,0),(3,1,0,64832,5,'kkPjYQWTynLpd9LnV8',0.00,'2024-01-26 20:48:46',NULL,0,0),(4,1,0,64832,5,'nGSNqNdarjSByLXmIs',0.00,'2024-01-26 20:48:46',NULL,0,0),(5,1,0,64832,5,'MPz6y9JlW7kIbJms7c',0.00,'2024-01-26 20:48:46',NULL,0,0),(6,0,0,0,1,'hpu3NWi8q7K3JNTl0o',10.00,'2024-02-26 08:22:09',NULL,0,0),(7,0,0,0,1,'bcsoobxw6eAa59lP6I',10.00,'2024-02-26 08:22:09',NULL,0,0),(8,1,0,58368,1,'Jt5iEMlE4ToghgdSUQ',0.00,'2024-03-27 08:36:32','2024-03-27 08:37:03',1,11285),(9,1,0,68394,1,'BeZJhVZmPxWWedXACw',0.00,'2024-03-27 08:42:29','2024-03-27 08:45:13',1,11287),(10,1,0,68394,1,'AnD3tQFpVrUnLrrydG',0.00,'2024-03-27 08:42:29',NULL,0,0);
/*!40000 ALTER TABLE `shua_kms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_logs`
--

DROP TABLE IF EXISTS `shua_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(32) NOT NULL,
  `param` varchar(255) NOT NULL,
  `result` text,
  `addtime` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_logs`
--

LOCK TABLES `shua_logs` WRITE;
/*!40000 ALTER TABLE `shua_logs` DISABLE KEYS */;
INSERT INTO `shua_logs` VALUES (1,'后台登录','IP:59.60.248.154','null','2025-05-24 12:37:06',1),(2,'分站登录','User:123456 IP:59.60.248.154','null','2025-05-24 12:39:15',1),(3,'分站登录','User:123456 IP:59.60.248.154','null','2025-05-25 15:06:07',1),(4,'分站登录','User:123456 IP:120.38.234.105','null','2025-05-26 15:34:38',1),(5,'分站登录','User:123456 IP:120.38.234.105','null','2025-05-26 18:41:14',1),(6,'分站登录','User:123456 IP:120.38.234.105','null','2025-05-26 23:21:38',1),(7,'分站登录','User:12345678 IP:120.38.234.105','null','2025-05-27 21:32:18',1),(8,'分站登录','User:12345678 IP:120.38.234.105','null','2025-05-27 21:59:01',1),(9,'后台登录','IP:120.38.234.105','null','2025-05-27 23:02:31',1),(10,'分站登录','User:12345678 IP:120.38.234.105','null','2025-05-27 23:57:47',1),(11,'分站登录','User:123456789 IP:120.38.234.105','null','2025-05-28 21:05:52',1),(12,'后台登录','IP:120.38.234.105','null','2025-05-29 15:01:44',1),(13,'分站登录','User:123456789 IP:120.38.234.105','null','2025-05-29 18:31:21',1),(14,'分站登录','User:123456789 IP:120.38.234.105','null','2025-05-29 22:33:04',1),(15,'分站登录','User:123456 IP:120.38.234.105','null','2025-05-30 17:55:02',1),(16,'后台登录','IP:27.150.45.23','null','2025-06-03 19:44:54',1),(17,'分站登录','User:1234567 IP:120.34.193.161','null','2025-06-06 21:28:21',1),(18,'分站登录','User:1234567 IP:110.89.215.101','null','2025-06-06 23:37:52',1),(19,'分站登录','User:1234567 IP:27.150.52.19','null','2025-06-07 00:40:35',1),(20,'后台登录','IP:120.34.192.14','null','2025-06-08 18:50:33',1),(21,'后台登录','IP:120.34.192.14','null','2025-06-08 19:24:23',1),(22,'后台登录','IP:120.34.192.14','null','2025-06-08 19:24:42',1),(23,'后台登录','IP:120.34.192.14','null','2025-06-08 19:34:04',1),(24,'后台登录','IP:120.34.192.14','null','2025-06-08 19:41:41',1),(25,'后台登录','IP:117.31.62.40','null','2025-06-08 22:31:41',1);
/*!40000 ALTER TABLE `shua_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_lucky_bag`
--

DROP TABLE IF EXISTS `shua_lucky_bag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_lucky_bag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `amount` decimal(10,2) NOT NULL COMMENT '获得金额',
  `addtime` datetime NOT NULL COMMENT '获得时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态:0未领取,1已领取',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='福袋记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_lucky_bag`
--

LOCK TABLES `shua_lucky_bag` WRITE;
/*!40000 ALTER TABLE `shua_lucky_bag` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_lucky_bag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_message`
--

DROP TABLE IF EXISTS `shua_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `type` int(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `addtime` datetime NOT NULL,
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_message`
--

LOCK TABLES `shua_message` WRITE;
/*!40000 ALTER TABLE `shua_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_news`
--

DROP TABLE IF EXISTS `shua_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` text,
  `time` datetime DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `content` mediumtext,
  `img` varchar(255) DEFAULT NULL,
  `add_time` datetime DEFAULT NULL,
  `read_count` int(11) DEFAULT NULL,
  `detail_url` varchar(255) DEFAULT NULL,
  `cover_url` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public_content` longtext,
  `vip_content` longtext,
  `category_id` int(11) DEFAULT '0',
  `summary` text,
  `price` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(1) DEFAULT '1',
  `price_guest` decimal(10,2) NOT NULL DEFAULT '9.99',
  `price_junior` decimal(10,2) NOT NULL DEFAULT '6.00',
  `price_senior` decimal(10,2) NOT NULL DEFAULT '4.00',
  `vip_only` tinyint(1) NOT NULL DEFAULT '0' COMMENT '仅会员可见',
  `vip_price` decimal(10,2) DEFAULT NULL COMMENT '会员专属价格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_news`
--

LOCK TABLES `shua_news` WRITE;
/*!40000 ALTER TABLE `shua_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_news_banner`
--

DROP TABLE IF EXISTS `shua_news_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_news_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `position` varchar(10) NOT NULL DEFAULT 'left',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_news_banner`
--

LOCK TABLES `shua_news_banner` WRITE;
/*!40000 ALTER TABLE `shua_news_banner` DISABLE KEYS */;
INSERT INTO `shua_news_banner` VALUES (1,'https://cdn.aimallol.com/statics/weapp/bg/zuji-activity/bg-3.png','',0,1,'left'),(2,'https://cdn.aimallol.com/statics/weapp/bg/enterprise-report/bg-card2.png','',0,1,'mid'),(3,'/news/18.png','',0,1,'right');
/*!40000 ALTER TABLE `shua_news_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_news_category`
--

DROP TABLE IF EXISTS `shua_news_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_news_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `url` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_news_category`
--

LOCK TABLES `shua_news_category` WRITE;
/*!40000 ALTER TABLE `shua_news_category` DISABLE KEYS */;
INSERT INTO `shua_news_category` VALUES (1,'个人信贷','https://cdn.aimallol.com/statics/weapp/icon/icon-gedai.png',10,1,'/news/list.php?cat=1'),(2,'企业贷','https://cdn.aimallol.com/statics/weapp/icon/icon-qiyedai.png',9,1,NULL),(3,'商城/小额','https://cdn.aimallol.com/statics/weapp/icon/icon-shop-index.png',8,1,NULL),(4,'信用租机','https://cdn.aimallol.com/statics/weapp/icon/icon-zuji.png',7,1,NULL),(5,'积分兑换','https://cdn.aimallol.com/statics/weapp/icon/icon-jifen.png',6,1,''),(7,'资源交易','https://aa.wamsg.cn/template/storenews/image/icon13.png',0,1,'/news/resourcemarket.php');
/*!40000 ALTER TABLE `shua_news_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_news_nav`
--

DROP TABLE IF EXISTS `shua_news_nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_news_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `icon2` varchar(255) DEFAULT NULL,
  `bg` varchar(255) DEFAULT NULL,
  `is_circle` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_news_nav`
--

LOCK TABLES `shua_news_nav` WRITE;
/*!40000 ALTER TABLE `shua_news_nav` DISABLE KEYS */;
INSERT INTO `shua_news_nav` VALUES (1,'个人中心','https://aa.wamsg.cn/assets/img/xtb/shouye/0001.png','/news/index.php',1,1,NULL,NULL,0),(2,'信用租机','https://aa.wamsg.cn/assets/img/Product/class_08dd0979dd21c2a83a0bba87c52eba2c.png','/news/index.php?tab=2',2,1,NULL,NULL,0),(3,'消息','https://aa.wamsg.cn/assets/img/xtb/shouye/0003.png','/news/index.php?tab=msg',3,1,NULL,NULL,0),(4,'首页','https://aa.wamsg.cn/assets/img/xtb/shouye/0002.png','/news/index.php',4,1,NULL,NULL,0);
/*!40000 ALTER TABLE `shua_news_nav` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_news_order`
--

DROP TABLE IF EXISTS `shua_news_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_news_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `add_time` datetime NOT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_news_order`
--

LOCK TABLES `shua_news_order` WRITE;
/*!40000 ALTER TABLE `shua_news_order` DISABLE KEYS */;
INSERT INTO `shua_news_order` VALUES (1,1,1,1.00,'2025-06-06 18:48:15',1);
/*!40000 ALTER TABLE `shua_news_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_novel`
--

DROP TABLE IF EXISTS `shua_novel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_novel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '小说标题',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `cover` varchar(255) NOT NULL COMMENT '封面图片',
  `description` text COMMENT '小说简介',
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `source_url` varchar(255) DEFAULT NULL COMMENT '来源URL',
  `check_time` datetime DEFAULT NULL COMMENT '审核时间',
  `check_reason` text COMMENT '审核原因',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小说表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_novel`
--

LOCK TABLES `shua_novel` WRITE;
/*!40000 ALTER TABLE `shua_novel` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_novel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_novel_cache`
--

DROP TABLE IF EXISTS `shua_novel_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_novel_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL COMMENT '缓存键名',
  `value` text NOT NULL COMMENT '缓存内容',
  `expire_time` datetime NOT NULL COMMENT '过期时间',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小说缓存表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_novel_cache`
--

LOCK TABLES `shua_novel_cache` WRITE;
/*!40000 ALTER TABLE `shua_novel_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_novel_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_novel_category`
--

DROP TABLE IF EXISTS `shua_novel_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_novel_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `sort_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小说分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_novel_category`
--

LOCK TABLES `shua_novel_category` WRITE;
/*!40000 ALTER TABLE `shua_novel_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_novel_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_novel_chapter`
--

DROP TABLE IF EXISTS `shua_novel_chapter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_novel_chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `novel_id` int(11) NOT NULL COMMENT '小说ID',
  `title` varchar(255) NOT NULL COMMENT '章节标题',
  `content` longtext NOT NULL COMMENT '章节内容',
  `sort_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_novel_id` (`novel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小说章节表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_novel_chapter`
--

LOCK TABLES `shua_novel_chapter` WRITE;
/*!40000 ALTER TABLE `shua_novel_chapter` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_novel_chapter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_novel_comment`
--

DROP TABLE IF EXISTS `shua_novel_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_novel_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `novel_id` int(11) NOT NULL COMMENT '小说ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `content` text NOT NULL COMMENT '评论内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_novel_id` (`novel_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小说评论表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_novel_comment`
--

LOCK TABLES `shua_novel_comment` WRITE;
/*!40000 ALTER TABLE `shua_novel_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_novel_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_orders`
--

DROP TABLE IF EXISTS `shua_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `input` varchar(256) NOT NULL,
  `input2` varchar(256) DEFAULT NULL,
  `input3` varchar(256) DEFAULT NULL,
  `input4` varchar(256) DEFAULT NULL,
  `input5` varchar(256) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `duanju` varchar(50) DEFAULT NULL,
  `value` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `djzt` tinyint(1) NOT NULL DEFAULT '0',
  `djorder` varchar(32) DEFAULT NULL,
  `url` varchar(32) DEFAULT NULL,
  `result` text,
  `userid` varchar(32) DEFAULT NULL,
  `tradeno` varchar(32) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `uptime` int(11) DEFAULT NULL,
  `intc` int(11) NOT NULL,
  `vid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `input` (`input`(191)),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_orders`
--

LOCK TABLES `shua_orders` WRITE;
/*!40000 ALTER TABLE `shua_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_pay`
--

DROP TABLE IF EXISTS `shua_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_pay` (
  `trade_no` varchar(64) NOT NULL,
  `api_trade_no` varchar(64) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `channel` varchar(10) DEFAULT NULL,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `tid` int(11) NOT NULL,
  `input` text NOT NULL,
  `num` int(11) unsigned NOT NULL DEFAULT '1',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `money` varchar(32) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `inviteid` int(11) unsigned DEFAULT NULL,
  `domain` varchar(64) DEFAULT NULL,
  `blockdj` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `vid` int(11) NOT NULL DEFAULT '0',
  `sbid` varbinary(64) NOT NULL,
  PRIMARY KEY (`trade_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_pay`
--

LOCK TABLES `shua_pay` WRITE;
/*!40000 ALTER TABLE `shua_pay` DISABLE KEYS */;
INSERT INTO `shua_pay` VALUES ('20250524124001172',NULL,'rmb',NULL,1,-2,'update|1|1|si17i6q.wamsg.cn|搜索页曝光 1000个|2026-05-24 12:40:01',1,'2025-05-24 12:40:01','2025-05-24 12:40:03','自助开通站点','10','59.60.248.154','57307120fd773c146f2f63ecd01e0ddb',NULL,NULL,0,1,0,''),('20250525213052997',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-25 21:30:52','2025-05-25 21:30:53','购买短剧播放:灵探特莱丝','0.6','59.60.248.154','1',NULL,NULL,0,1,41563,''),('20250526013725825',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 01:37:25','2025-05-26 01:37:27','购买短剧地址:恐惧街：舞会王后','0.1','59.60.248.154','1',NULL,NULL,0,1,41566,''),('20250526021608743',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 02:16:08','2025-05-26 02:16:09','购买短剧地址:奉天往事','0.1','59.60.248.154','1',NULL,NULL,0,1,512,''),('20250526021713817',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 02:17:13','2025-05-26 02:17:17','购买短剧播放:奉天往事','9.99','59.60.248.154','1',NULL,NULL,0,1,512,''),('20250526024947233',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 02:49:47','2025-05-26 02:49:48','购买短剧地址:踩界法庭','0.1','59.60.248.154','1',NULL,NULL,0,1,26923,''),('20250526025145713',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 02:51:45','2025-05-26 02:51:46','购买短剧播放:老夫子之魔界梦战记','9.99','59.60.248.154','1',NULL,NULL,0,1,41555,''),('20250526025450998',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 02:54:50','2025-05-26 02:54:51','购买短剧地址:恐惧街：舞会王后','0.1','59.60.248.154','1',NULL,NULL,0,1,41566,''),('20250526025622255',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 02:56:22','2025-05-26 02:56:24','购买短剧播放:恐惧街：舞会王后','9.99','59.60.248.154','1',NULL,NULL,0,1,41566,''),('20250526030349666',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 03:03:49','2025-05-26 03:03:51','购买短剧地址:恐惧街：舞会王后','0.1','59.60.248.154','1',NULL,NULL,0,1,41566,''),('20250526030557159',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 03:05:57','2025-05-26 03:05:58','购买短剧播放:恐惧街：舞会王后','0.6','59.60.248.154','1',NULL,NULL,0,1,41566,''),('20250526145731894',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 14:57:31','2025-05-26 14:57:32','购买短剧播放:恐惧街：舞会王后','0.6','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526150428905',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 15:04:28','2025-05-26 15:04:29','购买短剧播放:恐惧街：舞会王后','0.6','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526150805212',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 15:08:05','2025-05-26 15:08:07','购买短剧地址:恐惧街：舞会王后','0.1','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526150818660',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 15:08:18','2025-05-26 15:08:19','购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526152221894',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:22:21',NULL,'购买短剧播放:布鲁伊 第一季','9.99','120.38.234.105','1',NULL,NULL,0,1,41565,''),('20250526152356888',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:23:56',NULL,'购买短剧播放:灵探特莱丝','9.99','120.38.234.105','1',NULL,NULL,0,1,41563,''),('20250526154107601',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:41:07',NULL,'购买短剧播放:灵探特莱丝','9.99','120.38.234.105','1',NULL,NULL,0,1,41563,''),('20250526154343873',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:43:43',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526154802946',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:48:02',NULL,'购买短剧播放:漩涡','9.99','120.38.234.105','1',NULL,NULL,0,1,41559,''),('20250526155229731',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:52:29',NULL,'购买短剧播放:老夫子之魔界梦战记','9.99','120.38.234.105','1',NULL,NULL,0,1,41555,''),('20250526155245314',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:52:45',NULL,'购买短剧播放:重生七零小辣媳','9.99','120.38.234.105','1',NULL,NULL,0,1,36455,''),('20250526155304378',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:53:04',NULL,'购买短剧播放:地府聊天群','9.99','120.38.234.105','1',NULL,NULL,0,1,36451,''),('20250526155400392',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:54:00',NULL,'购买短剧地址:灵探特莱丝','0.1','120.38.234.105','1',NULL,NULL,0,1,41563,''),('20250526155442756',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 15:54:42',NULL,'购买短剧播放:灵探特莱丝','9.99','120.38.234.105','1',NULL,NULL,0,1,41563,''),('20250526160055841',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 16:00:55',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526160105766',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 16:01:05',NULL,'购买短剧地址:布鲁伊 第一季','0.1','120.38.234.105','1',NULL,NULL,0,1,41565,''),('20250526160140469',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 16:01:40','2025-05-26 16:01:41','购买短剧地址:奉天往事','0.1','120.38.234.105','1',NULL,NULL,0,1,512,''),('20250526160159874',NULL,'rmb',NULL,1,-4,'1|点播|1',1,'2025-05-26 16:01:59','2025-05-26 16:02:00','购买短剧播放:一切从遇见你开始','9.99','120.38.234.105','1',NULL,NULL,0,1,768,''),('20250526160335351',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 16:03:35',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526160410393',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 16:04:10',NULL,'购买短剧播放:在地下城尋求邂逅是否搞錯了什麼 第五季','9.99','120.38.234.105','1',NULL,NULL,0,1,41558,''),('20250526160526664',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 16:05:26',NULL,'购买短剧播放:离开','9.99','120.38.234.105','1',NULL,NULL,0,1,41562,''),('20250526170344769',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 17:03:44',NULL,'购买短剧播放:布鲁伊 第一季','0.6','120.38.234.105','1',NULL,NULL,0,1,41565,''),('20250526170814245',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 17:08:14',NULL,'购买短剧播放:在地下城尋求邂逅是否搞錯了什麼 第五季','9.99','120.38.234.105','1',NULL,NULL,0,1,41558,''),('20250526171339228',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 17:13:39',NULL,'购买短剧播放:拉撒路','9.99','120.38.234.105','1',NULL,NULL,0,1,41557,''),('20250526171623983',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 17:16:23',NULL,'购买短剧播放:PL81霹雳兵烽决之玄象裂变','0.6','120.38.234.105','1',NULL,NULL,0,1,41554,''),('20250526173240634',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 17:32:40',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526174542949',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 17:45:42',NULL,'购买短剧播放:PL84霹雳天机','9.99','120.38.234.105','1',NULL,NULL,0,1,41556,''),('20250526180313837',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 18:03:13',NULL,'购买短剧播放:Thunderbolt Fantasy 东离剑游纪 4','9.99','120.38.234.105','1',NULL,NULL,0,1,41560,''),('20250526180806760',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 18:08:06',NULL,'购买短剧播放:男得有情郎 第一季','9.99','120.38.234.105','1',NULL,NULL,0,1,31426,''),('20250526184100747',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 18:41:00',NULL,'购买短剧地址:灵探特莱丝','0.1','120.38.234.105','826f93d2b27c7cfcd18df98ab7598390',NULL,NULL,0,1,41563,''),('20250526184131467',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 18:41:31',NULL,'购买短剧播放:恐惧街：舞会王后','0.6','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526184147507',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 18:41:47',NULL,'购买短剧播放:恐惧街：舞会王后','0.6','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526184159331',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 18:41:59',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250526190327502',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:03:27',NULL,'购买短剧播放:漩涡','9.99','120.38.234.105','1',NULL,NULL,0,1,41559,''),('20250526191109239',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:11:09',NULL,'购买短剧播放:PL42霹雳皇朝之铡龑史','9.99','120.38.234.105','1',NULL,NULL,0,1,41542,''),('20250526191208463',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:12:08',NULL,'购买短剧播放:PL78霹雳朝灵阙','9.99','120.38.234.105','1',NULL,NULL,0,1,41536,''),('20250526193908670',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:39:08',NULL,'购买短剧播放:重生七零小辣媳','9.99','120.38.234.105','1',NULL,NULL,0,1,36455,''),('20250526193934828',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:39:34',NULL,'购买短剧播放:同盗一击','9.99','120.38.234.105','1',NULL,NULL,0,1,27445,''),('20250526194435166',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:44:35',NULL,'购买短剧播放:漩涡','0.6','120.38.234.105','1',NULL,NULL,0,1,41559,''),('20250526195147677',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:51:47',NULL,'购买短剧播放:暹罗决：九神战甲','9.99','120.38.234.105','1',NULL,NULL,0,1,41564,''),('20250526195843752',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 19:58:43',NULL,'购买短剧播放:灵探特莱丝','9.99','120.38.234.105','1',NULL,NULL,0,1,41563,''),('20250526201256485',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 20:12:56',NULL,'购买短剧播放:暹罗决：九神战甲','9.99','120.38.234.105','1',NULL,NULL,0,1,41564,''),('20250526215449127',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 21:54:49',NULL,'购买短剧播放:在地下城尋求邂逅是否搞錯了什麼 第五季','9.99','120.38.234.105','1',NULL,NULL,0,1,41558,''),('20250526222824530',NULL,NULL,NULL,1,-4,'1|点播|1',1,'2025-05-26 22:28:24',NULL,'购买短剧播放:踩界法庭','9.99','120.38.234.105','1',NULL,NULL,0,1,26923,''),('20250526230737961',NULL,NULL,NULL,1,-4,'41560|play|',1,'2025-05-26 23:07:37',NULL,'购买短剧播放:Thunderbolt Fantasy 东离剑游纪 4','0.6','120.38.234.105','1',NULL,NULL,0,1,41560,''),('20250526231501181',NULL,NULL,NULL,1,-4,'41549|play|',1,'2025-05-26 23:15:01',NULL,'购买短剧播放:霹雳英雄战纪之蝶龙之乱','9.99','120.38.234.105','1',NULL,NULL,0,1,41549,''),('20250526231959648',NULL,NULL,NULL,1,-4,'41562|play|',1,'2025-05-26 23:19:59',NULL,'购买短剧播放:离开','9.99','120.38.234.105','1',NULL,NULL,0,1,41562,''),('20250526232122832',NULL,NULL,NULL,1,-4,'41563|play|',1,'2025-05-26 23:21:22',NULL,'购买短剧播放:灵探特莱丝','9.99','120.38.234.105','ec5bdbd3e437df57b51fadf812b9bb09',NULL,NULL,0,1,41563,''),('20250526232217346',NULL,NULL,NULL,1,-4,'41554|url|',1,'2025-05-26 23:22:17',NULL,'购买短剧地址:PL81霹雳兵烽决之玄象裂变','0.1','120.38.234.105','1',NULL,NULL,0,1,41554,''),('20250527000308170',NULL,NULL,NULL,1,-4,'41564|play|',1,'2025-05-27 00:03:08',NULL,'购买短剧播放:暹罗决：九神战甲','9.99','120.38.234.105','1',NULL,NULL,0,1,41564,''),('20250527001729386',NULL,NULL,NULL,1,-4,'41566|play|',1,'2025-05-27 00:17:29',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250527002745339',NULL,NULL,NULL,1,-4,'41565|play|',1,'2025-05-27 00:27:45',NULL,'购买短剧播放:布鲁伊 第一季','9.99','120.38.234.105','1',NULL,NULL,0,1,41565,''),('20250527004425562',NULL,NULL,NULL,1,-4,'41566|play|',1,'2025-05-27 00:44:25',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250527012936202',NULL,NULL,NULL,1,-4,'41565|play|',1,'2025-05-27 01:29:36',NULL,'购买短剧播放:布鲁伊 第一季','0.6','120.38.234.105','1',NULL,NULL,0,1,41565,''),('20250527013357224',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 01:33:57',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527013935339',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 01:39:35',NULL,'购买短剧播放:Tokyo','9.99','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527013950371',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 01:39:50',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527014001235',NULL,NULL,NULL,1,-4,'41561|url|',1,'2025-05-27 01:40:01',NULL,'购买短剧地址:Tokyo','0.1','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527014502411',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 01:45:02',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527015046147',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 01:50:46',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527015101909',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 01:51:01',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527015210151',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 01:52:10',NULL,'购买短剧播放:Tokyo','9.99','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527015729900',NULL,NULL,NULL,1,-4,'26919|play|',1,'2025-05-27 01:57:29',NULL,'购买短剧播放:掌声满屋','0.6','120.38.234.105','1',NULL,NULL,0,1,26919,''),('20250527015825326',NULL,NULL,NULL,1,-4,'26916|play|',1,'2025-05-27 01:58:25',NULL,'购买短剧播放:Dear Jo : Series 第一季','0.6','120.38.234.105','1',NULL,NULL,0,1,26916,''),('20250527020031281',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:00:31',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527020050494',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:00:50',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527020113124',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:01:13',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527020136503',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:01:36',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527020230174',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:02:30',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527020555805',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:05:55',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527020737385',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:07:37',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527021144455',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:11:44',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527021436485',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:14:36',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527021507148',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:15:07',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527022206547',NULL,NULL,NULL,1,-4,'41561|play|',1,'2025-05-27 02:22:06',NULL,'购买短剧播放:Tokyo','0.6','120.38.234.105','1',NULL,NULL,0,1,41561,''),('20250527124644579',NULL,NULL,NULL,1,-4,'41559|play|',1,'2025-05-27 12:46:44',NULL,'购买短剧播放:漩涡','9.99','120.38.234.105','1',NULL,NULL,0,1,41559,''),('20250527125031305',NULL,NULL,NULL,1,-4,'41559|url|',1,'2025-05-27 12:50:31',NULL,'购买短剧地址:漩涡','0.1','120.38.234.105','1',NULL,NULL,0,1,41559,''),('20250527130006631',NULL,NULL,NULL,1,-4,'23513|play|',1,'2025-05-27 13:00:06',NULL,'购买短剧播放:一家团圆','0.6','120.38.234.105','1',NULL,NULL,0,1,23513,''),('20250527162220834',NULL,NULL,NULL,1,-4,'41563|play|',1,'2025-05-27 16:22:20',NULL,'购买短剧播放:灵探特莱丝','9.99','120.38.234.105','1',NULL,NULL,0,1,41563,''),('20250527162556725',NULL,NULL,NULL,1,-4,'41563|play|',1,'2025-05-27 16:25:56',NULL,'购买短剧播放:灵探特莱丝','9.99','120.38.234.105','1',NULL,NULL,0,1,41563,''),('20250527163102271',NULL,NULL,NULL,1,-4,'41562|play|',1,'2025-05-27 16:31:02',NULL,'购买短剧播放:离开','9.99','120.38.234.105','1',NULL,NULL,0,1,41562,''),('20250527163151381',NULL,NULL,NULL,1,-4,'41564|play|',1,'2025-05-27 16:31:51',NULL,'购买短剧播放:暹罗决：九神战甲','0.6','120.38.234.105','1',NULL,NULL,0,1,41564,''),('20250527163238939',NULL,NULL,NULL,1,-4,'41566|play|',1,'2025-05-27 16:32:38',NULL,'购买短剧播放:恐惧街：舞会王后','9.99','120.38.234.105','1',NULL,NULL,0,1,41566,''),('20250527163706973',NULL,NULL,NULL,1,-4,'11577|play|',1,'2025-05-27 16:37:06',NULL,'购买短剧播放:月嫂先生网络版','9.99','120.38.234.105','1',NULL,NULL,0,1,11577,''),('20250527163732589',NULL,NULL,NULL,1,-4,'10299|play|',1,'2025-05-27 16:37:32',NULL,'购买短剧播放:海热症','9.99','120.38.234.105','1',NULL,NULL,0,1,10299,''),('20250527165059229',NULL,NULL,NULL,1,-4,'41542|play|',1,'2025-05-27 16:50:59',NULL,'购买短剧播放:PL42霹雳皇朝之铡龑史','0.6','120.38.234.105','1',NULL,NULL,0,1,41542,''),('20250527171859211',NULL,NULL,NULL,1,-4,'26920|play|',1,'2025-05-27 17:18:59',NULL,'购买短剧播放:红皇后','9.99','120.38.234.105','1',NULL,NULL,0,1,26920,''),('20250527172524263',NULL,NULL,NULL,1,-4,'41557|play|',1,'2025-05-27 17:25:24',NULL,'购买短剧播放:拉撒路','9.99','120.38.234.105','1',NULL,NULL,0,1,41557,''),('20250527173022544',NULL,NULL,NULL,1,-4,'41555|play|',1,'2025-05-27 17:30:22',NULL,'购买短剧播放:老夫子之魔界梦战记','9.99','120.38.234.105','1',NULL,NULL,0,1,41555,''),('20250527173727715',NULL,NULL,NULL,1,-4,'41553|play|',1,'2025-05-27 17:37:27',NULL,'购买短剧播放:PL82霹雳战冥曲','9.99','120.38.234.105','1',NULL,NULL,0,1,41553,''),('20250527174225766',NULL,NULL,NULL,1,-4,'41565|play|',1,'2025-05-27 17:42:25',NULL,'购买短剧播放:布鲁伊 第一季','9.99','120.38.234.105','1',NULL,NULL,0,1,41565,''),('20250527174605724',NULL,NULL,NULL,1,-4,'31426|play|',1,'2025-05-27 17:46:05',NULL,'购买短剧播放:男得有情郎 第一季','9.99','120.38.234.105','1',NULL,NULL,0,1,31426,''),('20250527174650133',NULL,NULL,NULL,1,-4,'31425|play|',1,'2025-05-27 17:46:50',NULL,'购买短剧播放:璀璨迪拜','9.99','120.38.234.105','1',NULL,NULL,0,1,31425,''),('20250527174950195',NULL,NULL,NULL,1,-4,'41527|play|',1,'2025-05-27 17:49:50',NULL,'购买短剧播放:星辰变 第五季','9.99','120.38.234.105','1',NULL,NULL,0,1,41527,''),('20250527175604353',NULL,NULL,NULL,1,-4,'30962|play|',1,'2025-05-27 17:56:04',NULL,'购买短剧播放:Biong Biong地球游戏厅','9.99','120.38.234.105','1',NULL,NULL,0,1,30962,''),('20250527180722686',NULL,NULL,NULL,1,-4,'41549|play|',1,'2025-05-27 18:07:22',NULL,'购买短剧播放:霹雳英雄战纪之蝶龙之乱','0.6','120.38.234.105','1',NULL,NULL,0,1,41549,''),('20250527180751844',NULL,NULL,NULL,1,-4,'36448|play|',1,'2025-05-27 18:07:51',NULL,'购买短剧播放:应龙','0.6','120.38.234.105','1',NULL,NULL,0,1,36448,''),('20250527182055776',NULL,'rmb',NULL,1,-4,'867200',1,'2025-05-27 18:20:55','2025-05-27 18:20:56','购买短剧播放:离开','0.6','120.38.234.105','1',NULL,NULL,0,1,41562,''),('20250527182124878',NULL,'rmb',NULL,1,-4,'',1,'2025-05-27 18:21:24','2025-05-27 18:21:25','购买短剧播放:应龙','9.99','120.38.234.105','1',NULL,NULL,0,1,36448,''),('20250527182137679',NULL,'rmb',NULL,1,-4,'753930',1,'2025-05-27 18:21:37','2025-05-27 18:21:38','购买短剧播放:应龙','0.6','120.38.234.105','1',NULL,NULL,0,1,36448,''),('20250527182203129',NULL,'rmb',NULL,1,-4,'753930',1,'2025-05-27 18:22:03','2025-05-27 18:22:04','购买短剧播放:应龙','0.6','120.38.234.105','1',NULL,NULL,0,1,36448,''),('20250527182623927',NULL,NULL,NULL,1,-4,'36456|play|',1,'2025-05-27 18:26:23',NULL,'购买短剧播放:嗜血军团，少帅夫人不好惹','9.99','120.38.234.105','1',NULL,NULL,0,1,36456,''),('20250527192322304',NULL,'rmb',NULL,1,-4,'30176|play|2',1,'2025-05-27 19:23:22','2025-05-27 19:23:23','购买短剧播放:来吧！营业中','0.1','120.38.234.105','1',NULL,NULL,0,1,30176,''),('20250527192340870',NULL,'rmb',NULL,1,-4,'30176|play|2',1,'2025-05-27 19:23:40','2025-05-27 19:23:42','购买短剧播放:来吧！营业中','0.1','120.38.234.105','1',NULL,NULL,0,1,30176,''),('20250527192405427',NULL,'rmb',NULL,1,-4,'30176|play|2',1,'2025-05-27 19:24:05','2025-05-27 19:24:07','购买短剧播放:来吧！营业中','0.1','120.38.234.105','1',NULL,NULL,0,1,30176,''),('20250527192421794',NULL,'rmb',NULL,1,-4,'30176|play|1',1,'2025-05-27 19:24:21','2025-05-27 19:24:22','购买短剧播放:来吧！营业中','0.1','120.38.234.105','1',NULL,NULL,0,1,30176,''),('20250527192450692',NULL,'rmb',NULL,1,-4,'30176|play|2',1,'2025-05-27 19:24:50','2025-05-27 19:24:51','购买短剧播放:来吧！营业中','0.1','120.38.234.105','1',NULL,NULL,0,1,30176,''),('20250527192918483',NULL,'rmb',NULL,1,-4,'10138|play|1',1,'2025-05-27 19:29:18','2025-05-27 19:29:19','购买短剧第1集:哈利波特与魔法石','0.6','120.38.234.105','1',NULL,NULL,0,1,10138,''),('20250527192934667',NULL,'rmb',NULL,1,-4,'10138|play|all',1,'2025-05-27 19:29:34','2025-05-27 19:29:35','购买短剧全集:哈利波特与魔法石','0.1','120.38.234.105','1',NULL,NULL,0,1,10138,''),('20250527192953140',NULL,'rmb',NULL,1,-4,'10138|url|all',1,'2025-05-27 19:29:53','2025-05-27 19:29:54','购买短剧网盘:哈利波特与魔法石','0.1','120.38.234.105','1',NULL,NULL,0,1,10138,''),('20250527193709791',NULL,'rmb',NULL,1,-4,'41526|play|1',1,'2025-05-27 19:37:09','2025-05-27 19:37:11','购买短剧第1集:妖神记 第五季','0.6','120.38.234.105','1',NULL,NULL,0,1,41526,''),('20250527194212466',NULL,'rmb',NULL,1,-4,'26968|play|1',1,'2025-05-27 19:42:12','2025-05-27 19:42:14','购买短剧第1集:热情洋溢','0.6','120.38.234.105','1',NULL,NULL,0,1,26968,''),('20250527205106619',NULL,'rmb',NULL,1,-4,'26923|play|all',1,'2025-05-27 20:51:06','2025-05-27 20:51:07','购买短剧全集:踩界法庭','0.1','120.38.234.105','1',NULL,NULL,0,1,26923,''),('20250527205136904',NULL,'rmb',NULL,1,-4,'26923|play|1',1,'2025-05-27 20:51:36','2025-05-27 20:51:37','购买短剧第1集:踩界法庭','0.6','120.38.234.105','1',NULL,NULL,0,1,26923,''),('20250527205701723',NULL,'rmb',NULL,1,-4,'41555|play|1',1,'2025-05-27 20:57:01','2025-05-27 20:57:02','购买短剧第1集:老夫子之魔界梦战记','0.6','120.38.234.105','1',NULL,NULL,0,1,41555,''),('20250527205718831',NULL,'rmb',NULL,1,-4,'41555|play|1',1,'2025-05-27 20:57:18','2025-05-27 20:57:19','购买短剧第1集:老夫子之魔界梦战记','0.6','120.38.234.105','1',NULL,NULL,0,1,41555,''),('20250527205735630',NULL,'rmb',NULL,1,-4,'41555|play|1',1,'2025-05-27 20:57:35','2025-05-27 20:57:36','购买短剧第1集:老夫子之魔界梦战记','0.6','120.38.234.105','1',NULL,NULL,0,1,41555,''),('20250527205804784',NULL,'rmb',NULL,1,-4,'41555|play|all',1,'2025-05-27 20:58:04','2025-05-27 20:58:05','购买短剧全集:老夫子之魔界梦战记','0.1','120.38.234.105','1',NULL,NULL,0,1,41555,''),('20250527210215306',NULL,'rmb',NULL,1,-4,'23182|play|1',1,'2025-05-27 21:02:15','2025-05-27 21:02:16','购买短剧第1集:黑帮少爷爱上我','0.6','120.38.234.105','1',NULL,NULL,0,1,23182,''),('20250527211233486',NULL,'rmb',NULL,1,-4,'31427|play|1',1,'2025-05-27 21:12:33','2025-05-27 21:12:34','购买短剧第1集:欲罢不能：巴西篇','0.6','120.38.234.105','1',NULL,NULL,0,1,31427,''),('20250527211355266',NULL,'rmb',NULL,1,-4,'41565|play|1',1,'2025-05-27 21:13:55','2025-05-27 21:13:56','购买短剧第1集:布鲁伊 第一季','0.6','120.38.234.105','1',NULL,NULL,0,1,41565,''),('20250527211753908',NULL,'rmb',NULL,1,-4,'4941|play|1',1,'2025-05-27 21:17:53','2025-05-27 21:17:55','购买短剧第1集:人生大事','0.6','120.38.234.105','1',NULL,NULL,0,1,4941,''),('20250527213306324',NULL,'rmb',NULL,2,-4,'41566|play|1',1,'2025-05-27 21:33:06','2025-05-27 21:33:07','购买短剧第1集:恐惧街：舞会王后','0.6','120.38.234.105','2',NULL,NULL,0,1,41566,''),('20250527213322807',NULL,'rmb',NULL,2,-4,'41566|play|1',1,'2025-05-27 21:33:22','2025-05-27 21:33:24','购买短剧第1集:恐惧街：舞会王后','0.6','120.38.234.105','2',NULL,NULL,0,1,41566,''),('20250527214035450',NULL,'rmb',NULL,2,-4,'26924|play|1',1,'2025-05-27 21:40:35','2025-05-27 21:40:37','购买短剧第1集:血与水 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,26924,''),('20250527214243423',NULL,'rmb',NULL,2,-4,'26924|play|1',1,'2025-05-27 21:42:43','2025-05-27 21:42:45','购买短剧第1集:血与水 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,26924,''),('20250527215155805',NULL,'rmb',NULL,2,-4,'26924|play|1',1,'2025-05-27 21:51:55','2025-05-27 21:51:57','购买短剧第1集:血与水 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,26924,''),('20250527215922360',NULL,'rmb',NULL,2,-4,'41566|play|1',1,'2025-05-27 21:59:22','2025-05-27 21:59:23','购买短剧第1集:恐惧街：舞会王后','0.6','120.38.234.105','2',NULL,NULL,0,1,41566,''),('20250527220619704',NULL,'rmb',NULL,2,-4,'41565|play|1',1,'2025-05-27 22:06:19','2025-05-27 22:06:20','购买短剧第1集:布鲁伊 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250527222202558',NULL,'rmb',NULL,2,-4,'26922|play|1',1,'2025-05-27 22:22:02','2025-05-27 22:22:03','购买短剧第1集:复仇女神 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,26922,''),('20250527222254975',NULL,'rmb',NULL,2,-4,'26922|play|1',1,'2025-05-27 22:22:54','2025-05-27 22:22:55','购买短剧第1集:复仇女神 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,26922,''),('20250527222431567',NULL,'rmb',NULL,2,-4,'26922|play|1',1,'2025-05-27 22:24:31','2025-05-27 22:24:32','购买短剧第1集:复仇女神 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,26922,''),('20250527223350937',NULL,'rmb',NULL,2,-4,'31427|play|all',1,'2025-05-27 22:33:50','2025-05-27 22:33:52','购买短剧播放欲罢不能：巴西篇','9.99','120.38.234.105','2',NULL,NULL,0,1,31427,''),('20250527223411559',NULL,'rmb',NULL,2,-4,'31427|play|1',1,'2025-05-27 22:34:11','2025-05-27 22:34:12','购买短剧播放欲罢不能：巴西篇','9.99','120.38.234.105','2',NULL,NULL,0,1,31427,''),('20250527223445181',NULL,'rmb',NULL,2,-4,'31427|url|',1,'2025-05-27 22:34:45','2025-05-27 22:34:47','购买短剧地址欲罢不能：巴西篇','9.99','120.38.234.105','2',NULL,NULL,0,1,31427,''),('20250527224004188',NULL,'rmb',NULL,2,-4,'31427|play|1',1,'2025-05-27 22:40:04','2025-05-27 22:40:06','购买短剧播放欲罢不能：巴西篇','9.99','120.38.234.105','2',NULL,NULL,0,1,31427,''),('20250527224403717',NULL,'rmb',NULL,2,-4,'31427|play|all',1,'2025-05-27 22:44:03','2025-05-27 22:44:04','购买短剧播放欲罢不能：巴西篇','9.99','120.38.234.105','2',NULL,NULL,0,1,31427,''),('20250527225413840',NULL,'rmb',NULL,2,-4,'31427|play|1',1,'2025-05-27 22:54:13','2025-05-27 22:54:15','购买短剧播放欲罢不能：巴西篇','9.99','120.38.234.105','2',NULL,NULL,0,1,31427,''),('20250527225438887',NULL,'rmb',NULL,2,-4,'31427|play|all',1,'2025-05-27 22:54:38','2025-05-27 22:54:39','购买短剧播放欲罢不能：巴西篇','9.99','120.38.234.105','2',NULL,NULL,0,1,31427,''),('20250527225633412',NULL,'rmb',NULL,2,-4,'41563|play|1',1,'2025-05-27 22:56:33','2025-05-27 22:56:33','购买短剧播放灵探特莱丝','9.99','120.38.234.105','2',NULL,NULL,0,1,41563,''),('20250527232329137',NULL,'rmb',NULL,2,-4,'40392|play|1',1,'2025-05-27 23:23:29','2025-05-27 23:23:30','购买短剧播放电锯人','9.99','120.38.234.105','2',NULL,NULL,0,1,40392,''),('20250527232355705',NULL,'rmb',NULL,2,-4,'40392|play|all',1,'2025-05-27 23:23:55','2025-05-27 23:23:57','购买短剧播放电锯人','9.99','120.38.234.105','2',NULL,NULL,0,1,40392,''),('20250527232601361',NULL,'rmb',NULL,2,-4,'40392|play|1',1,'2025-05-27 23:26:01','2025-05-27 23:26:02','购买短剧播放电锯人','9.99','120.38.234.105','2',NULL,NULL,0,1,40392,''),('20250527232839228',NULL,'rmb',NULL,2,-4,'40392|play|1',1,'2025-05-27 23:28:39','2025-05-27 23:28:40','购买短剧播放电锯人','9.99','120.38.234.105','2',NULL,NULL,0,1,40392,''),('20250527233450162',NULL,'rmb',NULL,2,-4,'41565|play|1',1,'2025-05-27 23:34:50','2025-05-27 23:34:51','购买短剧播放布鲁伊 第一季','9.99','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250527233518728',NULL,'rmb',NULL,2,-4,'41565|play|1',1,'2025-05-27 23:35:18','2025-05-27 23:35:19','购买短剧播放布鲁伊 第一季','9.99','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250527233623595',NULL,'rmb',NULL,2,-4,'41565|play|all',1,'2025-05-27 23:36:23','2025-05-27 23:36:24','购买短剧播放布鲁伊 第一季','9.99','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250527234020188',NULL,'rmb',NULL,2,-4,'41565|play|1',1,'2025-05-27 23:40:20','2025-05-27 23:40:22','购买短剧播放布鲁伊 第一季','9.99','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250527234048382',NULL,'rmb',NULL,2,-4,'41565|play|all',1,'2025-05-27 23:40:48','2025-05-27 23:40:49','购买短剧播放布鲁伊 第一季','9.99','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250527235524354',NULL,'rmb',NULL,2,-4,'41561|play|1',1,'2025-05-27 23:55:24','2025-05-27 23:55:25','购买短剧播放Tokyo','9.99','120.38.234.105','2',NULL,NULL,0,1,41561,''),('20250527235550542',NULL,'rmb',NULL,2,-4,'41561|play|all',1,'2025-05-27 23:55:50','2025-05-27 23:55:51','购买短剧播放Tokyo','9.99','120.38.234.105','2',NULL,NULL,0,1,41561,''),('20250527235807873',NULL,'rmb',NULL,2,-4,'41547|play|1',1,'2025-05-27 23:58:07','2025-05-27 23:58:08','购买短剧播放幸福路上','9.99','120.38.234.105','2',NULL,NULL,0,1,41547,''),('20250528000342204',NULL,'rmb',NULL,2,-4,'41566|play|1',1,'2025-05-28 00:03:42','2025-05-28 00:03:43','购买短剧播放恐惧街：舞会王后','9.99','120.38.234.105','2',NULL,NULL,0,1,41566,''),('20250528000645931',NULL,'rmb',NULL,2,-4,'41564|play|1',1,'2025-05-28 00:06:45','2025-05-28 00:06:46','购买短剧播放暹罗决：九神战甲','9.99','120.38.234.105','2',NULL,NULL,0,1,41564,''),('20250528001115852',NULL,'rmb',NULL,2,-4,'41564|play|1',1,'2025-05-28 00:11:15','2025-05-28 00:11:16','购买短剧播放暹罗决：九神战甲','9.99','120.38.234.105','2',NULL,NULL,0,1,41564,''),('20250528001140375',NULL,'rmb',NULL,2,-4,'41564|play|all',1,'2025-05-28 00:11:40','2025-05-28 00:11:41','购买短剧播放暹罗决：九神战甲','9.99','120.38.234.105','2',NULL,NULL,0,1,41564,''),('20250528001154937',NULL,'rmb',NULL,2,-4,'41564|play|1',1,'2025-05-28 00:11:54','2025-05-28 00:11:55','购买短剧播放暹罗决：九神战甲','9.99','120.38.234.105','2',NULL,NULL,0,1,41564,''),('20250528003139643',NULL,'rmb',NULL,2,-4,'41561|play|1',1,'2025-05-28 00:31:39','2025-05-28 00:31:40','购买短剧播放Tokyo','9.99','120.38.234.105','2',NULL,NULL,0,1,41561,''),('20250528003201235',NULL,'rmb',NULL,2,-4,'41561|play|all',1,'2025-05-28 00:32:01','2025-05-28 00:32:02','购买短剧播放Tokyo','9.99','120.38.234.105','2',NULL,NULL,0,1,41561,''),('20250528005759853',NULL,'rmb',NULL,2,-4,'41527|play|all',1,'2025-05-28 00:57:59','2025-05-28 00:58:00','购买短剧播放星辰变 第五季','9.99','120.38.234.105','2',NULL,NULL,0,1,41527,''),('20250528005827554',NULL,'rmb',NULL,2,-4,'41527|play|1',1,'2025-05-28 00:58:27','2025-05-28 00:58:29','购买短剧播放星辰变 第五季','9.99','120.38.234.105','2',NULL,NULL,0,1,41527,''),('20250528010744779',NULL,'rmb',NULL,2,-4,'36455|play|2',1,'2025-05-28 01:07:44','2025-05-28 01:07:46','购买短剧播放重生七零小辣媳','0.1','120.38.234.105','2',NULL,NULL,0,1,36455,''),('20250528010805402',NULL,'rmb',NULL,2,-4,'36455|play|all',1,'2025-05-28 01:08:05','2025-05-28 01:08:06','购买短剧播放重生七零小辣媳','9.99','120.38.234.105','2',NULL,NULL,0,1,36455,''),('20250528011527394',NULL,'rmb',NULL,2,-4,'41551|play|1',1,'2025-05-28 01:15:27','2025-05-28 01:15:28','购买短剧播放PL80霹雳战魔策','0.1','120.38.234.105','2',NULL,NULL,0,1,41551,''),('20250528011547512',NULL,'rmb',NULL,2,-4,'41551|play|all',1,'2025-05-28 01:15:47','2025-05-28 01:15:48','购买短剧播放PL80霹雳战魔策','9.99','120.38.234.105','2',NULL,NULL,0,1,41551,''),('20250528172809674',NULL,'rmb',NULL,2,-4,'41555|play|1',1,'2025-05-28 17:28:09','2025-05-28 17:28:10','购买短剧播放老夫子之魔界梦战记','0.1','120.38.234.105','2',NULL,NULL,0,1,41555,''),('20250528172832353',NULL,'rmb',NULL,2,-4,'41555|play|2',1,'2025-05-28 17:28:32','2025-05-28 17:28:33','购买短剧播放老夫子之魔界梦战记','0.1','120.38.234.105','2',NULL,NULL,0,1,41555,''),('20250528172931904',NULL,'rmb',NULL,2,-4,'41555|play|',1,'2025-05-28 17:29:31','2025-05-28 17:29:32','购买短剧播放老夫子之魔界梦战记','0.1','120.38.234.105','2',NULL,NULL,0,1,41555,''),('20250528173004557',NULL,'rmb',NULL,2,-4,'41555|play|4',1,'2025-05-28 17:30:04','2025-05-28 17:30:07','购买短剧播放老夫子之魔界梦战记','0.1','120.38.234.105','2',NULL,NULL,0,1,41555,''),('20250528180343266',NULL,'rmb',NULL,2,-4,'41555|play|6',1,'2025-05-28 18:03:43','2025-05-28 18:03:44','购买短剧播放老夫子之魔界梦战记','0.6','120.38.234.105','2',NULL,NULL,0,1,41555,''),('20250528180432782',NULL,'rmb',NULL,2,-4,'41555|play|',1,'2025-05-28 18:04:32','2025-05-28 18:04:35','购买短剧播放老夫子之魔界梦战记','0.1','120.38.234.105','2',NULL,NULL,0,1,41555,''),('20250528180525502',NULL,'rmb',NULL,2,-4,'41555|url|',1,'2025-05-28 18:05:25','2025-05-28 18:05:26','购买短剧地址老夫子之魔界梦战记','0.1','120.38.234.105','2',NULL,NULL,0,1,41555,''),('20250528180709858',NULL,'rmb',NULL,2,-4,'41524|play|1',1,'2025-05-28 18:07:09','2025-05-28 18:07:10','购买短剧播放永生','0.6','120.38.234.105','2',NULL,NULL,0,1,41524,''),('20250528180748330',NULL,'rmb',NULL,2,-4,'41524|play|2',1,'2025-05-28 18:07:48','2025-05-28 18:07:50','购买短剧播放永生','0.6','120.38.234.105','2',NULL,NULL,0,1,41524,''),('20250528180832552',NULL,'rmb',NULL,2,-4,'41524|play|6',1,'2025-05-28 18:08:32','2025-05-28 18:08:34','购买短剧播放永生','0.6','120.38.234.105','2',NULL,NULL,0,1,41524,''),('20250528181011756',NULL,'rmb',NULL,2,-4,'41524|play|',1,'2025-05-28 18:10:11','2025-05-28 18:10:13','购买短剧播放永生','0.1','120.38.234.105','2',NULL,NULL,0,1,41524,''),('20250528181614283',NULL,'rmb',NULL,2,-4,'30962|play|1',1,'2025-05-28 18:16:14','2025-05-28 18:16:15','购买短剧播放Biong Biong地球游戏厅','0.6','120.38.234.105','2',NULL,NULL,0,1,30962,''),('20250528181707134',NULL,'rmb',NULL,2,-4,'30962|play|2',1,'2025-05-28 18:17:07','2025-05-28 18:17:09','购买短剧播放Biong Biong地球游戏厅','0.6','120.38.234.105','2',NULL,NULL,0,1,30962,''),('20250528181755850',NULL,'rmb',NULL,2,-4,'30962|play|',1,'2025-05-28 18:17:55','2025-05-28 18:17:57','购买短剧播放Biong Biong地球游戏厅','0.1','120.38.234.105','2',NULL,NULL,0,1,30962,''),('20250528181813763',NULL,'rmb',NULL,2,-4,'30962|play|all',1,'2025-05-28 18:18:13','2025-05-28 18:18:15','购买短剧播放Biong Biong地球游戏厅','9.99','120.38.234.105','2',NULL,NULL,0,1,30962,''),('20250528182258451',NULL,'rmb',NULL,2,-4,'26532|play|1',1,'2025-05-28 18:22:58','2025-05-28 18:22:59','购买短剧播放最后生还者','0.6','120.38.234.105','2',NULL,NULL,0,1,26532,''),('20250528182349917',NULL,'rmb',NULL,2,-4,'26532|play|all',1,'2025-05-28 18:23:49','2025-05-28 18:23:51','购买短剧播放最后生还者','9.99','120.38.234.105','2',NULL,NULL,0,1,26532,''),('20250528182454976',NULL,'rmb',NULL,2,-4,'26532|url|all',1,'2025-05-28 18:24:54','2025-05-28 18:24:55','购买短剧地址最后生还者','0.1','120.38.234.105','2',NULL,NULL,0,1,26532,''),('20250528182940259',NULL,'rmb',NULL,2,-4,'26612|play|1',1,'2025-05-28 18:29:40','2025-05-28 18:29:41','购买短剧播放骑着鱼的猫','0.6','120.38.234.105','2',NULL,NULL,0,1,26612,''),('20250528183011670',NULL,'rmb',NULL,2,-4,'26612|play|3',1,'2025-05-28 18:30:11','2025-05-28 18:30:12','购买短剧播放骑着鱼的猫','0.6','120.38.234.105','2',NULL,NULL,0,1,26612,''),('20250528183031189',NULL,'rmb',NULL,2,-4,'26612|play|all',1,'2025-05-28 18:30:31','2025-05-28 18:30:32','购买短剧播放骑着鱼的猫','9.99','120.38.234.105','2',NULL,NULL,0,1,26612,''),('20250528183557844',NULL,'rmb',NULL,2,-4,'23181|play|1',1,'2025-05-28 18:35:57','2025-05-28 18:35:58','购买短剧播放爱在空气中','0.6','120.38.234.105','2',NULL,NULL,0,1,23181,''),('20250528184039889',NULL,'rmb',NULL,2,-4,'23179|play|1',1,'2025-05-28 18:40:39','2025-05-28 18:40:40','购买短剧播放泰版流星花园','0.6','120.38.234.105','2',NULL,NULL,0,1,23179,''),('20250528184258865',NULL,'rmb',NULL,2,-4,'23179|play|6',1,'2025-05-28 18:42:58','2025-05-28 18:42:59','购买短剧播放泰版流星花园','0.1','120.38.234.105','2',NULL,NULL,0,1,23179,''),('20250528184334761',NULL,'rmb',NULL,2,-4,'41535|play|1',1,'2025-05-28 18:43:34','2025-05-28 18:43:35','购买短剧播放麦兜故事','0.1','120.38.234.105','2',NULL,NULL,0,1,41535,''),('20250528184455715',NULL,'rmb',NULL,2,-4,'31043|play|1',1,'2025-05-28 18:44:55','2025-05-28 18:44:55','购买短剧播放有点心机又如何','0.6','120.38.234.105','2',NULL,NULL,0,1,31043,''),('20250528184536873',NULL,'rmb',NULL,2,-4,'31043|play|2',1,'2025-05-28 18:45:36','2025-05-28 18:45:36','购买短剧播放有点心机又如何','0.6','120.38.234.105','2',NULL,NULL,0,1,31043,''),('20250528185844197',NULL,'rmb',NULL,2,-4,'26922|play|2',1,'2025-05-28 18:58:44','2025-05-28 18:58:45','购买短剧播放复仇女神 第一季','0.6','120.38.234.105','2',NULL,NULL,0,1,26922,''),('20250528190346269',NULL,'rmb',NULL,2,-4,'27444|play|1',1,'2025-05-28 19:03:46','2025-05-28 19:03:48','购买短剧播放不死咒怨3','0.6','120.38.234.105','2',NULL,NULL,0,1,27444,''),('20250528191202422',NULL,'rmb',NULL,2,-4,'41540|play|1',1,'2025-05-28 19:12:02','2025-05-28 19:12:03','购买短剧播放麦兜·我和我妈妈','0.6','120.38.234.105','2',NULL,NULL,0,1,41540,''),('20250528191707477',NULL,'rmb',NULL,2,-4,'31423|play|1',1,'2025-05-28 19:17:07','2025-05-28 19:17:08','购买短剧播放悉尼豪宅 第二季','0.6','120.38.234.105','2',NULL,NULL,0,1,31423,''),('20250528192323624',NULL,'rmb',NULL,2,-4,'36452|play|1',1,'2025-05-28 19:23:23','2025-05-28 19:23:24','购买短剧播放囚龙岛','0.6','120.38.234.105','2',NULL,NULL,0,1,36452,''),('20250528192432982',NULL,'rmb',NULL,2,-4,'36452|play|2',1,'2025-05-28 19:24:32','2025-05-28 19:24:33','购买短剧播放囚龙岛','0.6','120.38.234.105','2',NULL,NULL,0,1,36452,''),('20250528192449439',NULL,'rmb',NULL,2,-4,'36452|play|all',1,'2025-05-28 19:24:49','2025-05-28 19:24:51','购买短剧播放囚龙岛','9.99','120.38.234.105','2',NULL,NULL,0,1,36452,''),('20250528192535150',NULL,'rmb',NULL,2,-2,'update|2|1|xzgg7ka.wamsg.cn|123456|2026-05-28 19:25:35',1,'2025-05-28 19:25:35','2025-05-28 19:25:36','自助开通站点','10','120.38.234.105','c6c361bf94d7dc0d7aabac0f94f21f01',NULL,NULL,0,1,0,''),('20250528204014462',NULL,'rmb',NULL,2,-4,'41550|play|1',1,'2025-05-28 20:40:14','2025-05-28 20:40:15','购买短剧播放风云决','0.6','120.38.234.105','2',NULL,NULL,0,1,41550,''),('20250528204855225',NULL,'rmb',NULL,2,-4,'36453|play|1',1,'2025-05-28 20:48:55','2025-05-28 20:48:56','购买短剧播放国医圣手','0.6','120.38.234.105','2',NULL,NULL,0,1,36453,''),('20250528204919371',NULL,'rmb',NULL,2,-4,'36453|play|all',1,'2025-05-28 20:49:19','2025-05-28 20:49:21','购买短剧播放国医圣手','9.99','120.38.234.105','2',NULL,NULL,0,1,36453,''),('20250528204938467',NULL,'rmb',NULL,2,-4,'36453|url|all',1,'2025-05-28 20:49:38','2025-05-28 20:49:39','购买短剧地址国医圣手','0.1','120.38.234.105','2',NULL,NULL,0,1,36453,''),('20250528210812983',NULL,'rmb',NULL,3,-4,'41566|play|1',1,'2025-05-28 21:08:12','2025-05-28 21:08:19','购买短剧播放恐惧街：舞会王后','0.6','120.38.234.105','3',NULL,NULL,0,1,41566,''),('20250528211000809',NULL,'rmb',NULL,2,-4,'31425|play|all',1,'2025-05-28 21:10:00','2025-05-28 21:10:01','购买短剧播放璀璨迪拜','9.99','120.38.234.105','2',NULL,NULL,0,1,31425,''),('20250528222830885',NULL,'rmb',NULL,2,-4,'40394|play|1',1,'2025-05-28 22:28:30','2025-05-28 22:28:31','购买短剧播放血型君4','0.6','120.38.234.105','2',NULL,NULL,0,1,40394,''),('20250528222855518',NULL,'rmb',NULL,2,-4,'40394|play|all',1,'2025-05-28 22:28:55','2025-05-28 22:28:56','购买短剧播放血型君4','9.99','120.38.234.105','2',NULL,NULL,0,1,40394,''),('20250528225748556',NULL,'rmb',NULL,2,-4,'41554|play|1',1,'2025-05-28 22:57:48','2025-05-28 22:57:49','购买短剧播放PL81霹雳兵烽决之玄象裂变','0.2','120.38.234.105','2',NULL,NULL,0,1,41554,''),('20250528225806632',NULL,'rmb',NULL,2,-4,'41554|play|2',1,'2025-05-28 22:58:06','2025-05-28 22:58:11','购买短剧播放PL81霹雳兵烽决之玄象裂变','0.2','120.38.234.105','2',NULL,NULL,0,1,41554,''),('20250528232407869',NULL,'rmb',NULL,2,-4,'41544|play|all',1,'2025-05-28 23:24:07','2025-05-28 23:24:09','购买短剧播放PL43霹雳开疆纪','2','120.38.234.105','2',NULL,NULL,0,1,41544,''),('20250528232557664',NULL,'rmb',NULL,2,-4,'31397|play|all',1,'2025-05-28 23:25:57','2025-05-28 23:25:59','购买短剧播放创造营亚洲','2','120.38.234.105','2',NULL,NULL,0,1,31397,''),('20250529000818802',NULL,'rmb',NULL,2,-4,'30177|play|all',1,'2025-05-29 00:08:18','2025-05-29 00:08:19','购买短剧播放开心无敌奖门人','2','120.38.234.105','2',NULL,NULL,0,1,30177,''),('20250529000936926',NULL,'rmb',NULL,2,-4,'41552|play|1',1,'2025-05-29 00:09:36','2025-05-29 00:09:37','购买短剧播放PL41霹雳皇龙纪','0.2','120.38.234.105','2',NULL,NULL,0,1,41552,''),('20250529004939527',NULL,'rmb',NULL,2,-4,'41556|play|1',1,'2025-05-29 00:49:39','2025-05-29 00:49:40','购买短剧播放PL84霹雳天机','0.2','120.38.234.105','2',NULL,NULL,0,1,41556,''),('20250529005329982',NULL,'rmb',NULL,2,-4,'41556|play|all',1,'2025-05-29 00:53:29','2025-05-29 00:53:32','购买短剧播放PL84霹雳天机','2','120.38.234.105','2',NULL,NULL,0,1,41556,''),('20250529010449580',NULL,'rmb',NULL,2,-4,'41556|play|all',1,'2025-05-29 01:04:49','2025-05-29 01:04:51','购买短剧播放PL84霹雳天机','2','120.38.234.105','2',NULL,NULL,0,1,41556,''),('20250529011211682',NULL,'rmb',NULL,3,-4,'41530|play|1',1,'2025-05-29 01:12:11','2025-05-29 01:12:12','购买短剧播放机甲英雄 机斗勇者','0.6','120.38.234.105','3',NULL,NULL,0,1,41530,''),('20250529012717488',NULL,'rmb',NULL,2,-4,'31419|play|all',1,'2025-05-29 01:27:17','2025-05-29 01:27:19','购买短剧播放情场深伪术 第一季','2','120.38.234.105','2',NULL,NULL,0,1,31419,''),('20250529012740775',NULL,'rmb',NULL,2,-4,'31421|play|1',1,'2025-05-29 01:27:40','2025-05-29 01:27:41','购买短剧播放爱情盲选：巴西篇 第二季','0.2','120.38.234.105','2',NULL,NULL,0,1,31421,''),('20250529013158832',NULL,'rmb',NULL,2,-4,'26914|play|1',1,'2025-05-29 01:31:58','2025-05-29 01:31:59','购买短剧播放铁腕毒权','0.2','120.38.234.105','2',NULL,NULL,0,1,26914,''),('20250529013217120',NULL,'rmb',NULL,2,-4,'26914|play|all',1,'2025-05-29 01:32:17','2025-05-29 01:32:19','购买短剧播放铁腕毒权','2','120.38.234.105','2',NULL,NULL,0,1,26914,''),('20250529013611215',NULL,'rmb',NULL,2,-4,'7107|play|all',1,'2025-05-29 01:36:11','2025-05-29 01:36:13','购买短剧播放侏罗纪世界3','2','120.38.234.105','2',NULL,NULL,0,1,7107,''),('20250529013638521',NULL,'rmb',NULL,2,-4,'27443|play|1',1,'2025-05-29 01:36:38','2025-05-29 01:36:39','购买短剧播放异人之下','0.2','120.38.234.105','2',NULL,NULL,0,1,27443,''),('20250529013816991',NULL,'rmb',NULL,2,-4,'40384|play|all',1,'2025-05-29 01:38:16','2025-05-29 01:38:17','购买短剧播放无职转生Ⅱ ～到了异世界就拿出真本事～','2','120.38.234.105','2',NULL,NULL,0,1,40384,''),('20250529013840438',NULL,'rmb',NULL,2,-4,'36440|play|1',1,'2025-05-29 01:38:40','2025-05-29 01:38:41','购买短剧播放人间自由真情在','0.2','120.38.234.105','2',NULL,NULL,0,1,36440,''),('20250529014041981',NULL,'rmb',NULL,2,-4,'36440|play|2',1,'2025-05-29 01:40:41','2025-05-29 01:40:42','购买短剧播放人间自由真情在','0.2','120.38.234.105','2',NULL,NULL,0,1,36440,''),('20250529014059680',NULL,'rmb',NULL,2,-4,'36440|play|all',1,'2025-05-29 01:40:59','2025-05-29 01:41:01','购买短剧播放人间自由真情在','2','120.38.234.105','2',NULL,NULL,0,1,36440,''),('20250529014306940',NULL,'rmb',NULL,2,-4,'10138|play|1',1,'2025-05-29 01:43:06','2025-05-29 01:43:09','购买短剧播放哈利波特与魔法石','0.2','120.38.234.105','2',NULL,NULL,0,1,10138,''),('20250529014318837',NULL,'rmb',NULL,2,-4,'10138|play|all',1,'2025-05-29 01:43:18','2025-05-29 01:43:19','购买短剧播放哈利波特与魔法石','2','120.38.234.105','2',NULL,NULL,0,1,10138,''),('20250529014656571',NULL,'rmb',NULL,2,-4,'41552|play|2',1,'2025-05-29 01:46:56','2025-05-29 01:46:57','购买短剧播放PL41霹雳皇龙纪','0.2','120.38.234.105','2',NULL,NULL,0,1,41552,''),('20250529014705491',NULL,'rmb',NULL,2,-4,'41552|play|all',1,'2025-05-29 01:47:05','2025-05-29 01:47:07','购买短剧播放PL41霹雳皇龙纪','2','120.38.234.105','2',NULL,NULL,0,1,41552,''),('20250529014950603',NULL,'rmb',NULL,2,-4,'26525|play|1',1,'2025-05-29 01:49:50','2025-05-29 01:49:52','购买短剧播放海贼王 真人版','0.2','120.38.234.105','2',NULL,NULL,0,1,26525,''),('20250529015001524',NULL,'rmb',NULL,2,-4,'26525|play|all',1,'2025-05-29 01:50:01','2025-05-29 01:50:02','购买短剧播放海贼王 真人版','2','120.38.234.105','2',NULL,NULL,0,1,26525,''),('20250529015724388',NULL,'rmb',NULL,2,-4,'27438|play|1',1,'2025-05-29 01:57:24','2025-05-29 01:57:27','购买短剧播放打更人怪谈','0.2','120.38.234.105','2',NULL,NULL,0,1,27438,''),('20250529020253371',NULL,'rmb',NULL,2,-4,'27442|play|1',1,'2025-05-29 02:02:53','2025-05-29 02:02:54','购买短剧播放祝你幸福！','0.2','120.38.234.105','2',NULL,NULL,0,1,27442,''),('20250529020304577',NULL,'rmb',NULL,2,-4,'27442|play|all',1,'2025-05-29 02:03:04','2025-05-29 02:03:05','购买短剧播放祝你幸福！','2','120.38.234.105','2',NULL,NULL,0,1,27442,''),('20250529135945620',NULL,'rmb',NULL,3,-4,'41547|play|1',1,'2025-05-29 13:59:45','2025-05-29 13:59:46','购买短剧播放幸福路上','0.6','120.38.234.105','3',NULL,NULL,0,1,41547,''),('20250529140204629',NULL,'rmb',NULL,3,-4,'41530|play|2',1,'2025-05-29 14:02:04','2025-05-29 14:02:06','购买短剧播放机甲英雄 机斗勇者','0.6','120.38.234.105','3',NULL,NULL,0,1,41530,''),('20250529142034308',NULL,'rmb',NULL,2,-4,'41566|play|all',1,'2025-05-29 14:20:34','2025-05-29 14:20:35','购买短剧播放恐惧街：舞会王后','2','120.38.234.105','2',NULL,NULL,0,1,41566,''),('20250529143104807',NULL,'rmb',NULL,2,-4,'41549|play|all',1,'2025-05-29 14:31:04','2025-05-29 14:31:05','购买短剧播放霹雳英雄战纪之蝶龙之乱','2','120.38.234.105','2',NULL,NULL,0,1,41549,''),('20250529143238366',NULL,'rmb',NULL,2,-4,'41560|play|all',1,'2025-05-29 14:32:38','2025-05-29 14:32:39','购买短剧播放Thunderbolt Fantasy 东离剑游纪 4','2','120.38.234.105','2',NULL,NULL,0,1,41560,''),('20250529143711743',NULL,'rmb',NULL,2,-4,'41562|play|all',1,'2025-05-29 14:37:11','2025-05-29 14:37:12','购买短剧播放离开','2','120.38.234.105','2',NULL,NULL,0,1,41562,''),('20250529144158928',NULL,'rmb',NULL,2,-4,'41563|play|all',1,'2025-05-29 14:41:58','2025-05-29 14:41:59','购买短剧播放灵探特莱丝','2','120.38.234.105','2',NULL,NULL,0,1,41563,''),('20250529154818303',NULL,'rmb',NULL,2,-4,'41565|play|all',1,'2025-05-29 15:48:18','2025-05-29 15:48:20','购买短剧播放布鲁伊 第一季','2','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250529161508442',NULL,'rmb',NULL,2,-4,'41528|play|1',1,'2025-05-29 16:15:08','2025-05-29 16:15:10','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529162004963',NULL,'rmb',NULL,2,-4,'41528|play|2',1,'2025-05-29 16:20:04','2025-05-29 16:20:05','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529162247511',NULL,'rmb',NULL,2,-4,'41528|play|3',1,'2025-05-29 16:22:47','2025-05-29 16:22:48','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529163552875',NULL,'rmb',NULL,2,-4,'41528|play|4',1,'2025-05-29 16:35:52','2025-05-29 16:35:53','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529163817344',NULL,'rmb',NULL,2,-4,'41528|play|5',1,'2025-05-29 16:38:17','2025-05-29 16:38:18','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529164438139',NULL,'rmb',NULL,2,-4,'41528|play|6',1,'2025-05-29 16:44:38','2025-05-29 16:44:39','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529164515192',NULL,'rmb',NULL,3,-4,'41552|play|5',1,'2025-05-29 16:45:15','2025-05-29 16:45:16','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529164859552',NULL,'rmb',NULL,2,-4,'41528|play|7',1,'2025-05-29 16:48:59','2025-05-29 16:49:00','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529164922546',NULL,'rmb',NULL,2,-4,'41528|play|8',1,'2025-05-29 16:49:22','2025-05-29 16:49:24','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529165654758',NULL,'rmb',NULL,2,-4,'41528|play|9',1,'2025-05-29 16:56:55','2025-05-29 16:56:56','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529165714207',NULL,'rmb',NULL,2,-4,'41528|play|10',1,'2025-05-29 16:57:14','2025-05-29 16:57:15','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529165935281',NULL,'rmb',NULL,3,-4,'41552|play|6',1,'2025-05-29 16:59:35','2025-05-29 16:59:37','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529170028856',NULL,'rmb',NULL,3,-4,'41552|play|7',1,'2025-05-29 17:00:28','2025-05-29 17:00:33','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529170214115',NULL,'rmb',NULL,3,-4,'41552|play|8',1,'2025-05-29 17:02:14','2025-05-29 17:02:16','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529170254568',NULL,'rmb',NULL,3,-4,'41552|play|9',1,'2025-05-29 17:02:54','2025-05-29 17:02:56','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529171332761',NULL,'rmb',NULL,2,-4,'41528|play|11',1,'2025-05-29 17:13:32','2025-05-29 17:13:33','购买短剧播放PL79霹雳兵烽决之碧血玄黄','0.2','120.38.234.105','2',NULL,NULL,0,1,41528,''),('20250529171452509',NULL,'rmb',NULL,2,-4,'31420|play|1',1,'2025-05-29 17:14:52','2025-05-29 17:14:53','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529171817257',NULL,'rmb',NULL,2,-4,'31420|play|2',1,'2025-05-29 17:18:17','2025-05-29 17:18:18','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529171845983',NULL,'rmb',NULL,2,-4,'31420|play|3',1,'2025-05-29 17:18:45','2025-05-29 17:18:48','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529172522223',NULL,'rmb',NULL,2,-4,'36450|play|1',1,'2025-05-29 17:25:22','2025-05-29 17:25:23','购买短剧播放重新活一次','0.2','120.38.234.105','2',NULL,NULL,0,1,36450,''),('20250529172539170',NULL,'rmb',NULL,2,-4,'36450|play|2',1,'2025-05-29 17:25:39','2025-05-29 17:25:40','购买短剧播放重新活一次','0.2','120.38.234.105','2',NULL,NULL,0,1,36450,''),('20250529173029462',NULL,'rmb',NULL,2,-4,'31420|play|4',1,'2025-05-29 17:30:29','2025-05-29 17:30:30','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529173210976',NULL,'rmb',NULL,2,-4,'31420|play|5',1,'2025-05-29 17:32:10','2025-05-29 17:32:11','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529173500901',NULL,'rmb',NULL,2,-4,'31420|play|6',1,'2025-05-29 17:35:00','2025-05-29 17:35:01','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529174102710',NULL,'rmb',NULL,2,-4,'31420|play|7',1,'2025-05-29 17:41:02','2025-05-29 17:41:03','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529174255994',NULL,'rmb',NULL,2,-4,'31420|play|7',1,'2025-05-29 17:42:55','2025-05-29 17:42:56','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529174839974',NULL,'rmb',NULL,2,-4,'41558|play|1',1,'2025-05-29 17:48:39','2025-05-29 17:48:40','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529175248911',NULL,'rmb',NULL,2,-4,'41558|play|2',1,'2025-05-29 17:52:48','2025-05-29 17:52:50','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529180124880',NULL,'rmb',NULL,2,-4,'41558|play|3',1,'2025-05-29 18:01:24','2025-05-29 18:01:25','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529180653827',NULL,'rmb',NULL,2,-4,'41558|play|4',1,'2025-05-29 18:06:53','2025-05-29 18:06:54','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529180711636',NULL,'rmb',NULL,2,-4,'41558|play|5',1,'2025-05-29 18:07:11','2025-05-29 18:07:12','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529180919220',NULL,'rmb',NULL,2,-4,'41558|play|6',1,'2025-05-29 18:09:19','2025-05-29 18:09:20','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529181010430',NULL,'rmb',NULL,3,-4,'41552|play|1',1,'2025-05-29 18:10:10','2025-05-29 18:11:00','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529182406804',NULL,'rmb',NULL,3,-4,'41552|play|2',1,'2025-05-29 18:24:06','2025-05-29 18:24:08','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529182831349',NULL,'rmb',NULL,3,-4,'41552|play|3',1,'2025-05-29 18:28:31','2025-05-29 18:28:35','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529182900247',NULL,'rmb',NULL,2,-4,'41558|play|7',1,'2025-05-29 18:29:00','2025-05-29 18:29:01','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529183230672',NULL,'rmb',NULL,3,-4,'22464|play|1',1,'2025-05-29 18:32:30','2025-05-29 18:32:31','购买短剧播放蜘蛛侠：英雄无归','0.6','120.38.234.105','3',NULL,NULL,0,1,22464,''),('20250529183318309',NULL,'rmb',NULL,3,-4,'22464|play|2',1,'2025-05-29 18:33:18','2025-05-29 18:33:22','购买短剧播放蜘蛛侠：英雄无归','0.6','120.38.234.105','3',NULL,NULL,0,1,22464,''),('20250529183333941',NULL,'rmb',NULL,3,-4,'22464|play|3',1,'2025-05-29 18:33:33','2025-05-29 18:33:42','购买短剧播放蜘蛛侠：英雄无归','0.6','120.38.234.105','3',NULL,NULL,0,1,22464,''),('20250529183508142',NULL,'rmb',NULL,3,-4,'41552|play|10',1,'2025-05-29 18:35:08','2025-05-29 18:35:09','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529183548911',NULL,'rmb',NULL,3,-4,'27446|play|1',1,'2025-05-29 18:35:48','2025-05-29 18:35:50','购买短剧播放北逃','0.6','120.38.234.105','3',NULL,NULL,0,1,27446,''),('20250529183625878',NULL,'rmb',NULL,3,-4,'41552|play|4',1,'2025-05-29 18:36:25','2025-05-29 18:36:27','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529183818842',NULL,'rmb',NULL,3,-4,'41563|play|1',1,'2025-05-29 18:38:18','2025-05-29 18:38:20','购买短剧播放灵探特莱丝','0.6','120.38.234.105','3',NULL,NULL,0,1,41563,''),('20250529183839813',NULL,'rmb',NULL,3,-4,'41563|play|2',1,'2025-05-29 18:38:39','2025-05-29 18:38:40','购买短剧播放灵探特莱丝','0.6','120.38.234.105','3',NULL,NULL,0,1,41563,''),('20250529183859810',NULL,'rmb',NULL,3,-4,'41563|play|3',1,'2025-05-29 18:38:59','2025-05-29 18:39:00','购买短剧播放灵探特莱丝','0.6','120.38.234.105','3',NULL,NULL,0,1,41563,''),('20250529183917166',NULL,'rmb',NULL,3,-4,'41563|play|4',1,'2025-05-29 18:39:17','2025-05-29 18:39:19','购买短剧播放灵探特莱丝','0.6','120.38.234.105','3',NULL,NULL,0,1,41563,''),('20250529183945972',NULL,'rmb',NULL,3,-4,'41563|play|5',1,'2025-05-29 18:39:45','2025-05-29 18:39:47','购买短剧播放灵探特莱丝','0.6','120.38.234.105','3',NULL,NULL,0,1,41563,''),('20250529184020295',NULL,'rmb',NULL,3,-4,'41563|play|6',1,'2025-05-29 18:40:20','2025-05-29 18:40:22','购买短剧播放灵探特莱丝','0.6','120.38.234.105','3',NULL,NULL,0,1,41563,''),('20250529184136896',NULL,'rmb',NULL,3,-4,'41564|play|1',1,'2025-05-29 18:41:36','2025-05-29 18:41:38','购买短剧播放暹罗决：九神战甲','0.6','120.38.234.105','3',NULL,NULL,0,1,41564,''),('20250529184223790',NULL,'rmb',NULL,3,-4,'31427|play|1',1,'2025-05-29 18:42:23','2025-05-29 18:42:25','购买短剧播放欲罢不能：巴西篇','0.99','120.38.234.105','3',NULL,NULL,0,1,31427,''),('20250529184245861',NULL,'rmb',NULL,3,-4,'31427|play|2',1,'2025-05-29 18:42:45','2025-05-29 18:42:47','购买短剧播放欲罢不能：巴西篇','0.99','120.38.234.105','3',NULL,NULL,0,1,31427,''),('20250529184304853',NULL,'rmb',NULL,3,-4,'31427|play|3',1,'2025-05-29 18:43:04','2025-05-29 18:43:06','购买短剧播放欲罢不能：巴西篇','0.99','120.38.234.105','3',NULL,NULL,0,1,31427,''),('20250529184322181',NULL,'rmb',NULL,3,-4,'31427|play|4',1,'2025-05-29 18:43:22','2025-05-29 18:43:23','购买短剧播放欲罢不能：巴西篇','0.99','120.38.234.105','3',NULL,NULL,0,1,31427,''),('20250529184406270',NULL,'rmb',NULL,2,-4,'41558|play|8',1,'2025-05-29 18:44:06','2025-05-29 18:44:07','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529184450245',NULL,'rmb',NULL,2,-4,'41559|play|1',1,'2025-05-29 18:44:50','2025-05-29 18:44:52','购买短剧播放漩涡','0.2','120.38.234.105','2',NULL,NULL,0,1,41559,''),('20250529184531485',NULL,'rmb',NULL,2,-4,'41558|play|9',1,'2025-05-29 18:45:31','2025-05-29 18:45:33','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.2','120.38.234.105','2',NULL,NULL,0,1,41558,''),('20250529184722738',NULL,'rmb',NULL,2,-4,'31420|play|8',1,'2025-05-29 18:47:22','2025-05-29 18:47:23','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529184820911',NULL,'rmb',NULL,2,-4,'31420|play|9',1,'2025-05-29 18:48:20','2025-05-29 18:48:21','购买短剧播放爱情盲选：瑞典篇','0.2','120.38.234.105','2',NULL,NULL,0,1,31420,''),('20250529211011852',NULL,'rmb',NULL,2,-4,'27436|play|1',1,'2025-05-29 21:10:11','2025-05-29 21:10:12','购买短剧播放绝色保镖','0.2','120.38.234.105','2',NULL,NULL,0,1,27436,''),('20250529220401767',NULL,'rmb',NULL,2,-4,'41532|play|1',1,'2025-05-29 22:04:01','2025-05-29 22:04:02','购买短剧播放妈祖','0.2','120.38.234.105','2',NULL,NULL,0,1,41532,''),('20250529220507741',NULL,'rmb',NULL,2,-4,'41537|play|1',1,'2025-05-29 22:05:07','2025-05-29 22:05:09','购买短剧播放金光御九界之仙古狂涛','0.2','120.38.234.105','2',NULL,NULL,0,1,41537,''),('20250529220627537',NULL,'rmb',NULL,2,-4,'41537|play|2',1,'2025-05-29 22:06:27','2025-05-29 22:06:28','购买短剧播放金光御九界之仙古狂涛','0.2','120.38.234.105','2',NULL,NULL,0,1,41537,''),('20250529220849212',NULL,'rmb',NULL,2,-4,'41554|play|3',1,'2025-05-29 22:08:49','2025-05-29 22:09:02','购买短剧播放PL81霹雳兵烽决之玄象裂变','0.2','120.38.234.105','2',NULL,NULL,0,1,41554,''),('20250529221345375',NULL,'rmb',NULL,2,-4,'41554|play|4',1,'2025-05-29 22:13:45','2025-05-29 22:13:46','购买短剧播放PL81霹雳兵烽决之玄象裂变','0.2','120.38.234.105','2',NULL,NULL,0,1,41554,''),('20250529221430419',NULL,'rmb',NULL,2,-4,'41538|play|2',1,'2025-05-29 22:14:30','2025-05-29 22:14:31','购买短剧播放PL75霹雳侠峰','0.2','120.38.234.105','2',NULL,NULL,0,1,41538,''),('20250529222530596',NULL,'rmb',NULL,3,-4,'41552|play|11',1,'2025-05-29 22:25:30','2025-05-29 22:25:33','购买短剧播放PL41霹雳皇龙纪','0.6','120.38.234.105','3',NULL,NULL,0,1,41552,''),('20250529222953263',NULL,'rmb',NULL,3,-4,'41537|play|1',1,'2025-05-29 22:29:53','2025-05-29 22:29:54','购买短剧播放金光御九界之仙古狂涛','0.6','120.38.234.105','3',NULL,NULL,0,1,41537,''),('20250529223329550',NULL,'rmb',NULL,3,-4,'41558|play|1',1,'2025-05-29 22:33:29','2025-05-29 22:33:30','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.6','120.38.234.105','3',NULL,NULL,0,1,41558,''),('20250529223401945',NULL,'rmb',NULL,3,-4,'41558|play|2',1,'2025-05-29 22:34:01','2025-05-29 22:34:03','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','0.6','120.38.234.105','3',NULL,NULL,0,1,41558,''),('20250529223448439',NULL,'rmb',NULL,3,-4,'41558|play|all',1,'2025-05-29 22:34:48','2025-05-29 22:34:50','购买短剧播放在地下城尋求邂逅是否搞錯了什麼 第五季','9.99','120.38.234.105','3',NULL,NULL,0,1,41558,''),('20250529223504555',NULL,'rmb',NULL,3,-4,'41557|play|all',1,'2025-05-29 22:35:04','2025-05-29 22:35:06','购买短剧播放拉撒路','9.99','120.38.234.105','3',NULL,NULL,0,1,41557,''),('20250529223521431',NULL,'rmb',NULL,3,-4,'41561|url|all',1,'2025-05-29 22:35:21','2025-05-29 22:35:22','购买短剧地址Tokyo','0.1','120.38.234.105','3',NULL,NULL,0,1,41561,''),('20250529223613166',NULL,'rmb',NULL,3,-4,'26923|url|all',1,'2025-05-29 22:36:13','2025-05-29 22:36:14','购买短剧地址踩界法庭','0.1','120.38.234.105','3',NULL,NULL,0,1,26923,''),('20250529224757786',NULL,'rmb',NULL,2,-4,'31422|url|all',1,'2025-05-29 22:47:57','2025-05-29 22:47:58','购买短剧网盘顶级人生：第一季','0.1','120.38.234.105','2',NULL,NULL,0,1,31422,''),('20250529230431836',NULL,'rmb',NULL,2,-4,'41548|url|all',1,'2025-05-29 23:04:31','2025-05-29 23:04:32','购买短剧网盘霹雳侠影之轰霆剑海录','0.1','120.38.234.105','2',NULL,NULL,0,1,41548,''),('20250529230456432',NULL,'rmb',NULL,2,-4,'41557|url|all',1,'2025-05-29 23:04:56','2025-05-29 23:04:57','购买短剧网盘拉撒路','0.1','120.38.234.105','2',NULL,NULL,0,1,41557,''),('20250529232422762',NULL,'rmb',NULL,2,-4,'41527|url|all',1,'2025-05-29 23:24:22','2025-05-29 23:24:24','购买短剧网盘星辰变 第五季','0.1','120.38.234.105','2',NULL,NULL,0,1,41527,''),('20250529232443142',NULL,'rmb',NULL,2,-4,'40396|url|all',1,'2025-05-29 23:24:43','2025-05-29 23:24:44','购买短剧网盘奇奇怪怪：整容液','0.1','120.38.234.105','2',NULL,NULL,0,1,40396,''),('20250529232517855',NULL,'rmb',NULL,2,-4,'31395|url|all',1,'2025-05-29 23:25:17','2025-05-29 23:25:18','购买短剧网盘欲罢不能 第四季','0.1','120.38.234.105','2',NULL,NULL,0,1,31395,''),('20250529233435526',NULL,'rmb',NULL,2,-4,'40386|url|all',1,'2025-05-29 23:34:35','2025-05-29 23:34:36','购买短剧网盘死神 千年血战篇 第二季','0.1','120.38.234.105','2',NULL,NULL,0,1,40386,''),('20250529233533220',NULL,'rmb',NULL,2,-4,'41559|play|2',1,'2025-05-29 23:35:33','2025-05-29 23:35:35','购买短剧播放漩涡','0.2','120.38.234.105','2',NULL,NULL,0,1,41559,''),('20250529233826339',NULL,'rmb',NULL,2,-4,'41559|url|all',1,'2025-05-29 23:38:26','2025-05-29 23:38:28','购买短剧网盘漩涡','0.1','120.38.234.105','2',NULL,NULL,0,1,41559,''),('20250529234141280',NULL,'rmb',NULL,2,-4,'26918|url|all',1,'2025-05-29 23:41:41','2025-05-29 23:41:42','购买短剧网盘超性','0.1','120.38.234.105','2',NULL,NULL,0,1,26918,''),('20250529234820291',NULL,'rmb',NULL,2,-4,'27435|url|all',1,'2025-05-29 23:48:20','2025-05-29 23:48:22','购买短剧网盘一盘大棋','0.1','120.38.234.105','2',NULL,NULL,0,1,27435,''),('20250530000054246',NULL,'rmb',NULL,2,-4,'41521|url|all',1,'2025-05-30 00:00:54','2025-05-30 00:00:56','购买短剧网盘恶魔法则','0.1','120.38.234.105','2',NULL,NULL,0,1,41521,''),('20250530000149555',NULL,'rmb',NULL,2,-4,'26922|play|all',1,'2025-05-30 00:01:49','2025-05-30 00:01:50','购买短剧播放复仇女神 第一季','2','120.38.234.105','2',NULL,NULL,0,1,26922,''),('20250530000209653',NULL,'rmb',NULL,2,-4,'31423|url|all',1,'2025-05-30 00:02:09','2025-05-30 00:02:10','购买短剧网盘悉尼豪宅 第二季','0.1','120.38.234.105','2',NULL,NULL,0,1,31423,''),('20250530000233640',NULL,'rmb',NULL,2,-4,'31421|play|2',1,'2025-05-30 00:02:33','2025-05-30 00:02:35','购买短剧播放爱情盲选：巴西篇 第二季','0.2','120.38.234.105','2',NULL,NULL,0,1,31421,''),('20250530002017794',NULL,'rmb',NULL,2,-4,'31421|url|all',1,'2025-05-30 00:20:17','2025-05-30 00:20:18','购买短剧网盘爱情盲选：巴西篇 第二季','0.1','120.38.234.105','2',NULL,NULL,0,1,31421,''),('20250530002753892',NULL,'rmb',NULL,2,-4,'41565|url|all',1,'2025-05-30 00:27:53','2025-05-30 00:27:54','购买短剧网盘布鲁伊 第一季','0.1','120.38.234.105','2',NULL,NULL,0,1,41565,''),('20250530004626438',NULL,'rmb',NULL,2,-4,'41565|url|all',1,'2025-05-30 00:46:26','2025-05-30 00:46:28','购买短剧网盘布鲁伊 第一季','0.1','120.38.234.105','2',NULL,NULL,0,1,41565,'');
/*!40000 ALTER TABLE `shua_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_points`
--

DROP TABLE IF EXISTS `shua_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_points` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL,
  `point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bz` varchar(1024) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `orderid` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `fufei` int(1) DEFAULT NULL,
  `duanju` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `action` (`action`(191)),
  KEY `orderid` (`orderid`),
  KEY `idx_status_action` (`status`,`action`(191)),
  KEY `idx_orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_points`
--

LOCK TABLES `shua_points` WRITE;
/*!40000 ALTER TABLE `shua_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_price`
--

DROP TABLE IF EXISTS `shua_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_price` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `kind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 倍数 1 价格',
  `name` varchar(255) NOT NULL,
  `p_0` decimal(8,2) NOT NULL DEFAULT '0.00',
  `p_1` decimal(8,2) NOT NULL DEFAULT '0.00',
  `p_2` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_price`
--

LOCK TABLES `shua_price` WRITE;
/*!40000 ALTER TABLE `shua_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_qiandao`
--

DROP TABLE IF EXISTS `shua_qiandao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_qiandao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `qq` varchar(20) DEFAULT NULL,
  `reward` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date` date NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `continue` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `ip` (`ip`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_qiandao`
--

LOCK TABLES `shua_qiandao` WRITE;
/*!40000 ALTER TABLE `shua_qiandao` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_qiandao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_qiandao66`
--

DROP TABLE IF EXISTS `shua_qiandao66`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_qiandao66` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `qq` varchar(20) DEFAULT NULL,
  `reward` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date` date NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `continue` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `ip` (`ip`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_qiandao66`
--

LOCK TABLES `shua_qiandao66` WRITE;
/*!40000 ALTER TABLE `shua_qiandao66` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_qiandao66` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_read_history`
--

DROP TABLE IF EXISTS `shua_read_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_read_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `novel_id` int(11) NOT NULL COMMENT '小说ID',
  `chapter_id` int(11) NOT NULL COMMENT '章节ID',
  `read_time` datetime NOT NULL COMMENT '阅读时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_novel_id` (`novel_id`),
  KEY `idx_chapter_id` (`chapter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='阅读历史表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_read_history`
--

LOCK TABLES `shua_read_history` WRITE;
/*!40000 ALTER TABLE `shua_read_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_read_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_resource_category`
--

DROP TABLE IF EXISTS `shua_resource_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_resource_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(64) NOT NULL,
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_resource_category`
--

LOCK TABLES `shua_resource_category` WRITE;
/*!40000 ALTER TABLE `shua_resource_category` DISABLE KEYS */;
INSERT INTO `shua_resource_category` VALUES (1,'资源交易',0),(6,'111',0),(7,'111',0),(8,'111',0),(9,'111',0),(10,'111',0),(11,'111',0),(12,'阿里嘎多',0);
/*!40000 ALTER TABLE `shua_resource_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_seckilllog`
--

DROP TABLE IF EXISTS `shua_seckilllog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_seckilllog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `input` text NOT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `num` int(11) NOT NULL DEFAULT '1',
  `date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`),
  KEY `zid` (`zid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_seckilllog`
--

LOCK TABLES `shua_seckilllog` WRITE;
/*!40000 ALTER TABLE `shua_seckilllog` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_seckilllog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_seckillshop`
--

DROP TABLE IF EXISTS `shua_seckillshop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_seckillshop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `value` int(11) NOT NULL DEFAULT '1',
  `num` int(11) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '10',
  `starttime` datetime DEFAULT NULL,
  `endtime` datetime NOT NULL,
  `addtime` datetime NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_seckillshop`
--

LOCK TABLES `shua_seckillshop` WRITE;
/*!40000 ALTER TABLE `shua_seckillshop` DISABLE KEYS */;
INSERT INTO `shua_seckillshop` VALUES (2,92236,0.10,15,100,1,'2024-03-17 00:00:00','2024-03-17 08:00:00','2024-03-17 15:09:34',15,1),(4,81234,1.00,18,500,1,'2024-03-17 00:00:00','2024-03-17 08:00:00','2024-03-17 15:17:41',4,1);
/*!40000 ALTER TABLE `shua_seckillshop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_sendcode`
--

DROP TABLE IF EXISTS `shua_sendcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_sendcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0邮箱 1手机',
  `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0注册 1找回 2改绑',
  `code` varchar(32) NOT NULL,
  `to` varchar(32) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_sendcode`
--

LOCK TABLES `shua_sendcode` WRITE;
/*!40000 ALTER TABLE `shua_sendcode` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_sendcode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_settings`
--

DROP TABLE IF EXISTS `shua_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `virtual_order` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟订单开关',
  `order_display` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单显示开关',
  `my_balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '个人余额',
  `today_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '今日收入',
  `month_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本月收入',
  `total_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `yesterday_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '昨日收入',
  `today_detail_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '今日明细收入',
  `addtime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_settings`
--

LOCK TABLES `shua_settings` WRITE;
/*!40000 ALTER TABLE `shua_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_shequ`
--

DROP TABLE IF EXISTS `shua_shequ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_shequ` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `paypwd` varchar(255) DEFAULT NULL,
  `paytype` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `protocol` tinyint(1) NOT NULL DEFAULT '0',
  `monitor` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_shequ`
--

LOCK TABLES `shua_shequ` WRITE;
/*!40000 ALTER TABLE `shua_shequ` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_shequ` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_site`
--

DROP TABLE IF EXISTS `shua_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_site` (
  `zid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `upzid` int(11) unsigned NOT NULL DEFAULT '0',
  `utype` int(1) unsigned NOT NULL DEFAULT '0',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(50) DEFAULT NULL,
  `domain2` varchar(50) DEFAULT NULL,
  `user` varchar(20) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `qq_openid` varchar(64) DEFAULT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `faceimg` varchar(150) DEFAULT NULL,
  `rmb` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rmbtc` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int(11) NOT NULL DEFAULT '0',
  `pay_type` int(1) NOT NULL DEFAULT '0',
  `pay_account` varchar(50) DEFAULT NULL,
  `pay_name` varchar(50) DEFAULT NULL,
  `qq` varchar(12) DEFAULT NULL,
  `sitename` varchar(80) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `keywords` text,
  `description` text,
  `kfqq` varchar(12) DEFAULT NULL,
  `kfwx` varchar(20) DEFAULT NULL,
  `anounce` text,
  `bottom` text,
  `modal` text,
  `alert` text,
  `price` longtext,
  `iprice` longtext,
  `appurl` varchar(150) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `ktfz_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ktfz_price2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ktfz_domain` text,
  `addtime` datetime DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `template` varchar(10) DEFAULT NULL,
  `msgread` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `user_level` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `is_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '影视会员',
  `is_permanent_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '永久影视会员',
  `vip_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '影视会员推广价',
  `vip_min_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '影视会员底线价',
  `vip_profit_rate` decimal(5,2) NOT NULL DEFAULT '50.00' COMMENT '影视会员分成比例',
  `wx_openid` varchar(64) DEFAULT NULL,
  `name` varchar(20) DEFAULT '未设置昵称',
  `vprice` longtext,
  `vbprice` longtext,
  `vdbprice` longtext,
  `inbr` int(10) NOT NULL,
  `inbfb` decimal(10,2) NOT NULL,
  `ingd` decimal(10,2) NOT NULL,
  `inbr2` int(10) NOT NULL,
  `ingd2` decimal(10,2) NOT NULL,
  `inbfb2` decimal(10,2) NOT NULL,
  `intime` bigint(16) NOT NULL,
  `fukaig` int(2) NOT NULL,
  `xian` int(11) DEFAULT NULL,
  `defaultcid` varchar(99) DEFAULT NULL,
  `uu` int(11) NOT NULL,
  `jumoney` int(11) DEFAULT NULL,
  `finance_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '金融VIP',
  `finance_vip_expire` datetime DEFAULT NULL COMMENT '金融VIP到期时间',
  `vip_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员等级 0普通 1VIP 2SVIP',
  `vip_expire` datetime DEFAULT NULL COMMENT '会员到期时间',
  PRIMARY KEY (`zid`),
  UNIQUE KEY `user` (`user`),
  KEY `domain` (`domain`),
  KEY `domain2` (`domain2`),
  KEY `qq` (`qq`),
  KEY `qq_openid` (`qq_openid`),
  KEY `wx_openid` (`wx_openid`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_site`
--

LOCK TABLES `shua_site` WRITE;
/*!40000 ALTER TABLE `shua_site` DISABLE KEYS */;
INSERT INTO `shua_site` VALUES (1,0,0,2,'si17i6q.wamsg.cn',NULL,'123456','123456',NULL,NULL,NULL,NULL,NULL,1368.62,0.00,0,0,NULL,NULL,'123456','搜索页曝光 1000个','创业项目基地','互联网创业项目','专注移动互联网创业赚钱实战项目，不定期分享项目实战、项目思维、案例，带你一起走向致富之路。平台提供百万价值的实战培训资料，精准引流爆粉实战，实操网络知识项目，分享流量变现的实操方法与技巧',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,NULL,'2025-05-24 12:38:33','2025-05-30 17:55:02','2026-05-24 12:40:01',NULL,NULL,1,0,0,0,0.00,0.00,50.00,NULL,'未设置昵称',NULL,NULL,NULL,0,0.00,0.00,0,0.00,0.00,0,0,NULL,NULL,1,NULL,0,NULL,0,NULL),(2,0,0,2,'xzgg7ka.wamsg.cn',NULL,'12345678','1234567',NULL,NULL,NULL,NULL,NULL,1465.08,0.00,0,0,NULL,NULL,'123132132','123456','创业项目基地','互联网创业项目','专注移动互联网创业赚钱实战项目，不定期分享项目实战、项目思维、案例，带你一起走向致富之路。平台提供百万价值的实战培训资料，精准引流爆粉实战，实操网络知识项目，分享流量变现的实操方法与技巧',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,NULL,'2025-05-27 21:32:18','2025-05-27 23:57:47','2026-05-28 19:25:35',NULL,NULL,1,0,0,0,0.00,0.00,50.00,NULL,'未设置昵称',NULL,NULL,NULL,0,0.00,0.00,0,0.00,0.00,0,0,NULL,NULL,1,NULL,0,NULL,0,NULL),(3,0,0,0,NULL,NULL,'123456789','123456',NULL,NULL,NULL,NULL,NULL,1958.46,0.00,0,0,NULL,NULL,'132123132',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,NULL,'2025-05-28 21:05:52','2025-05-29 22:33:04',NULL,NULL,NULL,1,0,0,0,0.00,0.00,50.00,NULL,'未设置昵称',NULL,NULL,NULL,0,0.00,0.00,0,0.00,0.00,0,0,NULL,NULL,0,NULL,0,NULL,0,NULL),(4,0,0,0,NULL,NULL,'1234567','123456',NULL,NULL,NULL,NULL,NULL,2000.00,0.00,0,0,NULL,NULL,'121212',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,NULL,'2025-06-06 21:28:17','2025-06-07 00:40:35',NULL,NULL,NULL,1,0,0,0,0.00,0.00,50.00,NULL,'未设置昵称',NULL,NULL,NULL,0,0.00,0.00,0,0.00,0.00,0,0,NULL,NULL,0,NULL,0,NULL,0,NULL);
/*!40000 ALTER TABLE `shua_site` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_sscc`
--

DROP TABLE IF EXISTS `shua_sscc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_sscc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `name` varchar(22) DEFAULT NULL COMMENT '群名称',
  `img` varchar(100) NOT NULL COMMENT '群头像',
  `gg` varchar(100) NOT NULL DEFAULT '0',
  `money` decimal(22,2) NOT NULL COMMENT '进群门槛',
  `orsers` int(22) NOT NULL COMMENT '支付状态',
  `ename` varchar(22) NOT NULL COMMENT '副标题',
  `ename1` varchar(999) NOT NULL COMMENT '标题一',
  `ename2` varchar(999) NOT NULL COMMENT '标题二',
  `ename3` varchar(999) NOT NULL COMMENT '3',
  `ename4` varchar(999) NOT NULL COMMENT '4',
  `ename5` varchar(999) NOT NULL,
  `ename6` varchar(999) NOT NULL COMMENT '6',
  `ename7` varchar(999) NOT NULL COMMENT '7',
  `ename8` varchar(999) NOT NULL COMMENT '8',
  `ename9` varchar(999) NOT NULL COMMENT '9',
  `ename10` varchar(999) NOT NULL COMMENT '10',
  `active` int(11) NOT NULL,
  `addtime` datetime NOT NULL,
  `weixing` varchar(99) DEFAULT NULL COMMENT '微信号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_sscc`
--

LOCK TABLES `shua_sscc` WRITE;
/*!40000 ALTER TABLE `shua_sscc` DISABLE KEYS */;
INSERT INTO `shua_sscc` VALUES (1,12585,'美女写真（老司机你懂的😍😍）','','1',0.99,0,'老司机聚集地，上万老司机的选择😍，再也不用找','全网最全资源','进群后终身免费看，每天更新美女写真，有高清视频有高清图.......','付款后支持退款吗？','一旦付费，无论何种原因概不退款（包括被踢出群的），请谨慎选择，看好再买。群费不多不少，买个信任。做付费群，过滤人群，提升群质量。避免人多嘴杂，杜绝吃白食群众。付费入群更能让社群形成正向循环，是社群内容质量的保障。 2.用户协议（付款即代表已读）----本平台禁止以任何形式传播电信网络诈骗、兼职、刷单、网恋交友诈骗、淫秽、色情、赌博、暴力、凶杀、恐怖、谣言等违法行为，违规者所传播的信息相关的任何法律责任由违规者自行承担，与平台无关 。用户要自己有辨别意识，未成年人应当在监护人的指导下使用本平台服务，遇到违法违规行为第一时间向管理员举报！违规者平台将报警移交给相关公安机关处理！ ','老司机你懂的😍😍😍','','','受不了了我c----真值啊😍----看不过来了都😍----值得----真不错啊----真sao','','限时特价19.9元(马上恢复原价69.9)',1,'2025-04-18 01:09:59','nxqq10088');
/*!40000 ALTER TABLE `shua_sscc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_system_log`
--

DROP TABLE IF EXISTS `shua_system_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL COMMENT '日志类型',
  `content` text NOT NULL COMMENT '日志内容',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_add_time` (`add_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_system_log`
--

LOCK TABLES `shua_system_log` WRITE;
/*!40000 ALTER TABLE `shua_system_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_system_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_tixian`
--

DROP TABLE IF EXISTS `shua_tixian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_tixian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realmoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_type` int(1) NOT NULL DEFAULT '0',
  `pay_account` varchar(50) NOT NULL,
  `pay_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_tixian`
--

LOCK TABLES `shua_tixian` WRITE;
/*!40000 ALTER TABLE `shua_tixian` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_tixian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_tools`
--

DROP TABLE IF EXISTS `shua_tools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_tools` (
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `cid` int(11) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '10',
  `name` varchar(255) NOT NULL,
  `value` int(11) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prid` int(11) NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prices` varchar(100) DEFAULT NULL,
  `input` varchar(250) NOT NULL,
  `inputs` varchar(255) DEFAULT NULL,
  `desc` text,
  `alert` text,
  `shopimg` text,
  `validate` tinyint(1) NOT NULL DEFAULT '0',
  `valiserv` varchar(15) DEFAULT NULL,
  `min` int(11) NOT NULL DEFAULT '0',
  `max` int(11) NOT NULL DEFAULT '0',
  `is_curl` tinyint(1) NOT NULL DEFAULT '0',
  `curl` varchar(255) DEFAULT NULL,
  `repeat` tinyint(1) NOT NULL DEFAULT '0',
  `multi` tinyint(1) NOT NULL DEFAULT '0',
  `shequ` int(3) NOT NULL DEFAULT '0',
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `goods_type` int(11) NOT NULL DEFAULT '0',
  `goods_param` text,
  `close` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  `sales` int(11) NOT NULL DEFAULT '0',
  `stock` int(11) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `pro` int(11) NOT NULL,
  `wx_test` varchar(255) DEFAULT NULL,
  `tc` decimal(11,1) NOT NULL,
  `zg` text NOT NULL,
  `goods_parammm` int(11) DEFAULT NULL,
  `siteid` int(11) DEFAULT '0' COMMENT 'id分站下的商品',
  `descc` text,
  `goods_paramm` int(11) DEFAULT NULL,
  `proo` int(11) NOT NULL,
  `wx_tett` varchar(255) DEFAULT NULL,
  `zyurl` varchar(255) DEFAULT NULL,
  `daim` text,
  `top` int(10) NOT NULL,
  `toptime` datetime NOT NULL,
  `last` int(10) NOT NULL,
  `inzid` longtext,
  `goods_sid` int(99) NOT NULL,
  `sup_price` int(99) NOT NULL,
  `audit_status` int(99) NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `cid` (`cid`),
  KEY `price` (`price`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_tools`
--

LOCK TABLES `shua_tools` WRITE;
/*!40000 ALTER TABLE `shua_tools` DISABLE KEYS */;
INSERT INTO `shua_tools` VALUES (1,1,0,1,'sadfsafdas',0,9.99,0,6.00,4.00,'','','','asdfasdfasdfasdfasdf','','assets/img/Product/shop_11c90a019e15034400c8aa2c71cfa129.png',0,'vip',0,0,5,'',1,1,0,0,0,'sdfasdf',0,1,0,0,NULL,'2025-05-25 19:23:15',0,NULL,0.0,'',NULL,0,NULL,NULL,0,NULL,NULL,NULL,0,'0000-00-00 00:00:00',0,NULL,0,0,0),(2,1,1,2,'秒单真机完播90s 100个',0,9.99,0,6.00,4.00,'','','','的风格但是风格第三个第三方','','https://aa.wamsg.cn/assets/img/Product/shop_750a793195997d1265146fb2b1ca9e15.png',0,'vip',0,0,5,'',1,1,0,0,0,'但是风格第三个第三个',0,1,0,0,NULL,'2025-05-26 17:18:33',0,NULL,0.0,'',NULL,0,NULL,NULL,0,NULL,NULL,NULL,0,'0000-00-00 00:00:00',0,NULL,0,0,0);
/*!40000 ALTER TABLE `shua_tools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_user_favorite`
--

DROP TABLE IF EXISTS `shua_user_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_user_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `novel_id` int(11) NOT NULL COMMENT '小说ID',
  `add_time` datetime NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_novel` (`user_id`,`novel_id`),
  KEY `idx_novel_id` (`novel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户收藏表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_user_favorite`
--

LOCK TABLES `shua_user_favorite` WRITE;
/*!40000 ALTER TABLE `shua_user_favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_user_favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_video`
--

DROP TABLE IF EXISTS `shua_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `xingya_id` int(11) NOT NULL DEFAULT '0' COMMENT '星芽ID',
  `pid` int(11) NOT NULL COMMENT '短剧ID',
  `url` varchar(255) NOT NULL COMMENT '视频地址',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '集数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_video`
--

LOCK TABLES `shua_video` WRITE;
/*!40000 ALTER TABLE `shua_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_video_backup`
--

DROP TABLE IF EXISTS `shua_video_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_video_backup` (
  `id` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `xingya_id` int(11) NOT NULL DEFAULT '0' COMMENT '星芽ID',
  `pid` int(11) NOT NULL COMMENT '短剧ID',
  `url` varchar(255) NOT NULL COMMENT '视频地址',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '集数',
  `free_views` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_video_backup`
--

LOCK TABLES `shua_video_backup` WRITE;
/*!40000 ALTER TABLE `shua_video_backup` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_video_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_video_comment`
--

DROP TABLE IF EXISTS `shua_video_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_video_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `video_id` int(11) NOT NULL COMMENT '视频ID',
  `content` text NOT NULL COMMENT '评论内容',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态：0禁用 1启用',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_video_comment`
--

LOCK TABLES `shua_video_comment` WRITE;
/*!40000 ALTER TABLE `shua_video_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_video_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_video_danmaku`
--

DROP TABLE IF EXISTS `shua_video_danmaku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_video_danmaku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `video_id` int(11) NOT NULL COMMENT '视频ID',
  `content` varchar(255) NOT NULL COMMENT '弹幕内容',
  `time` decimal(10,2) NOT NULL COMMENT '弹幕时间',
  `color` varchar(7) DEFAULT '#ffffff' COMMENT '弹幕颜色',
  `type` tinyint(1) DEFAULT '0' COMMENT '弹幕类型：0滚动 1顶部 2底部',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_video_danmaku`
--

LOCK TABLES `shua_video_danmaku` WRITE;
/*!40000 ALTER TABLE `shua_video_danmaku` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_video_danmaku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_video_favorite`
--

DROP TABLE IF EXISTS `shua_video_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_video_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `video_id` int(11) NOT NULL COMMENT '视频ID',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_video` (`user_id`,`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_video_favorite`
--

LOCK TABLES `shua_video_favorite` WRITE;
/*!40000 ALTER TABLE `shua_video_favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_video_favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_video_history`
--

DROP TABLE IF EXISTS `shua_video_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_video_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `video_id` int(11) NOT NULL COMMENT '视频ID',
  `watch_time` int(11) DEFAULT '0' COMMENT '观看时长(秒)',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_video_history`
--

LOCK TABLES `shua_video_history` WRITE;
/*!40000 ALTER TABLE `shua_video_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_video_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_video_points`
--

DROP TABLE IF EXISTS `shua_video_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_video_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP,
  `uptime` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_video_points`
--

LOCK TABLES `shua_video_points` WRITE;
/*!40000 ALTER TABLE `shua_video_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_video_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_videolist`
--

DROP TABLE IF EXISTS `shua_videolist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_videolist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `desc` varchar(255) NOT NULL COMMENT '简介',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `xingya_id` int(11) NOT NULL DEFAULT '0' COMMENT '星芽视频ID',
  `prid` int(11) NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bfprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bfcost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bfcost2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `img` varchar(255) DEFAULT NULL,
  `download_url` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_hot` tinyint(1) NOT NULL DEFAULT '1',
  `addtime` datetime NOT NULL,
  `uptime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `api_host` varchar(255) DEFAULT NULL COMMENT '来源API地址',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_xingya_id` (`xingya_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_videolist`
--

LOCK TABLES `shua_videolist` WRITE;
/*!40000 ALTER TABLE `shua_videolist` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_videolist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_videotype`
--

DROP TABLE IF EXISTS `shua_videotype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_videotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父分类ID,0表示主分类',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '排序',
  `addtime` bigint(16) NOT NULL COMMENT '创建时间',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示,1显示,0隐藏',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name_parent` (`name`,`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=668 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_videotype`
--

LOCK TABLES `shua_videotype` WRITE;
/*!40000 ALTER TABLE `shua_videotype` DISABLE KEYS */;
INSERT INTO `shua_videotype` VALUES (1,'电影',0,1,1748013369,1),(2,'剧情片',1,1,1748013369,1),(3,'动作片',1,1,1748013369,1),(4,'冒险片',1,1,1748013369,1),(5,'同性片',1,1,1748013369,1),(6,'喜剧片',1,1,1748013369,1),(7,'奇幻片',1,1,1748013369,1),(8,'恐怖片',1,1,1748013369,1),(9,'悬疑片',1,1,1748013369,1),(10,'惊悚片',1,1,1748013369,1),(11,'灾难片',1,1,1748013369,1),(12,'爱情片',1,1,1748013369,1),(13,'犯罪片',1,1,1748013369,1),(14,'科幻片',1,1,1748013369,1),(15,'动画电影',1,1,1748013369,1),(16,'歌舞片',1,1,1748013369,1),(17,'战争片',1,1,1748013369,1),(18,'经典片',1,1,1748013369,1),(19,'网络电影',1,1,1748013369,1),(20,'其它片',1,1,1748013369,1),(21,'电视剧',0,1,1748013369,1),(22,'国产剧',21,1,1748013369,1),(23,'港剧',21,1,1748013369,1),(24,'韩剧',21,1,1748013369,1),(25,'日剧',21,1,1748013369,1),(26,'泰剧',21,1,1748013369,1),(27,'台剧',21,1,1748013369,1),(28,'欧美剧',21,1,1748013369,1),(29,'新马剧',21,1,1748013369,1),(30,'其他剧',21,1,1748013369,1),(31,'动漫',0,1,1748013369,1),(32,'欧美动漫',31,1,1748013369,1),(33,'日本动漫',31,1,1748013369,1),(34,'韩国动漫',31,1,1748013369,1),(35,'国产动漫',31,1,1748013369,1),(36,'港台动漫',31,1,1748013369,1),(37,'新马泰动漫',31,1,1748013369,1),(38,'其它动漫',31,1,1748013369,1),(39,'综艺',0,1,1748013369,1),(40,'国产综艺',39,1,1748013369,1),(41,'港台综艺',39,1,1748013369,1),(42,'韩国综艺',39,1,1748013370,1),(43,'日本综艺',39,1,1748013370,1),(44,'欧美综艺',39,1,1748013370,1),(45,'新马泰综艺',39,1,1748013370,1),(46,'其他综艺',39,1,1748013370,1),(47,'短剧',0,1,1748013370,1),(48,'古装短剧',47,1,1748013370,1),(49,'虐恋短剧',47,1,1748013370,1),(50,'逆袭短剧',47,1,1748013370,1),(51,'悬疑短剧',47,1,1748013370,1),(52,'神豪短剧',47,1,1748013370,1),(53,'重生短剧',47,1,1748013370,1),(54,'复仇短剧',47,1,1748013370,1),(55,'穿越短剧',47,1,1748013370,1),(56,'甜宠短剧',47,1,1748013370,1),(57,'强者短剧',47,1,1748013370,1),(58,'萌宝短剧',47,1,1748013370,1),(59,'其它短剧',47,1,1748013370,1),(60,'合集短剧',47,1,1748013370,1),(665,'地推千粉',0,1,1748157859,1),(667,'123456',665,1,1748504650,1);
/*!40000 ALTER TABLE `shua_videotype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_vip_level`
--

DROP TABLE IF EXISTS `shua_vip_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_vip_level` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '等级名称',
  `price` decimal(10,2) NOT NULL COMMENT '开通/续费价格',
  `duration` int(11) NOT NULL COMMENT '时长(天)',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_vip_level`
--

LOCK TABLES `shua_vip_level` WRITE;
/*!40000 ALTER TABLE `shua_vip_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_vip_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_vip_order`
--

DROP TABLE IF EXISTS `shua_vip_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_vip_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `add_time` datetime NOT NULL,
  `expire_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_vip_order`
--

LOCK TABLES `shua_vip_order` WRITE;
/*!40000 ALTER TABLE `shua_vip_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_vip_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_virtual_income`
--

DROP TABLE IF EXISTS `shua_virtual_income`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_virtual_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL COMMENT '站点ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0关闭 1开启',
  `my_balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '个人余额',
  `today_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '今日收益',
  `month_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本月收益',
  `total_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总收益',
  `yesterday_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '昨日收益',
  `today_detail_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '今日详细收益',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `zid` (`zid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='虚拟收益管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_virtual_income`
--

LOCK TABLES `shua_virtual_income` WRITE;
/*!40000 ALTER TABLE `shua_virtual_income` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_virtual_income` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_workorder`
--

DROP TABLE IF EXISTS `shua_workorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_workorder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `type` int(1) unsigned NOT NULL DEFAULT '0',
  `orderid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `picurl` varchar(150) DEFAULT NULL,
  `addtime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_workorder`
--

LOCK TABLES `shua_workorder` WRITE;
/*!40000 ALTER TABLE `shua_workorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `shua_workorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shua_yiji`
--

DROP TABLE IF EXISTS `shua_yiji`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shua_yiji` (
  `suid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` varchar(255) NOT NULL DEFAULT '1' COMMENT '下级id',
  `sort` int(11) NOT NULL DEFAULT '10',
  `name` varchar(255) NOT NULL COMMENT '分类名',
  `img` text NOT NULL COMMENT '分类图',
  `active` tinyint(4) NOT NULL COMMENT '开关',
  PRIMARY KEY (`suid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shua_yiji`
--

LOCK TABLES `shua_yiji` WRITE;
/*!40000 ALTER TABLE `shua_yiji` DISABLE KEYS */;
INSERT INTO `shua_yiji` VALUES (20,'1,2,3,9',2,'一级分类','',1),(24,'1,2,3,4,5,6',4,'一级分类2','',1),(25,'1,3,6',6,'一级分类3','',1);
/*!40000 ALTER TABLE `shua_yiji` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sup_config`
--

DROP TABLE IF EXISTS `sup_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sup_config` (
  `k` text NOT NULL,
  `v` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sup_config`
--

LOCK TABLES `sup_config` WRITE;
/*!40000 ALTER TABLE `sup_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `sup_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sup_pay`
--

DROP TABLE IF EXISTS `sup_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sup_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `name` text NOT NULL,
  `sup` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sup_pay`
--

LOCK TABLES `sup_pay` WRITE;
/*!40000 ALTER TABLE `sup_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `sup_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sup_tx`
--

DROP TABLE IF EXISTS `sup_tx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sup_tx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `money` float DEFAULT NULL,
  `realmoney` float DEFAULT NULL,
  `pay_type` int(11) DEFAULT '0',
  `pay_account` text,
  `pay_name` text,
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `user` text,
  `rmb` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sup_tx`
--

LOCK TABLES `sup_tx` WRITE;
/*!40000 ALTER TABLE `sup_tx` DISABLE KEYS */;
/*!40000 ALTER TABLE `sup_tx` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` text NOT NULL,
  `pwd` text NOT NULL,
  `qq` bigint(20) NOT NULL,
  `wx` text NOT NULL,
  `rmb` float DEFAULT '0',
  `pay_name` text,
  `pay_type` int(3) NOT NULL DEFAULT '0',
  `pay_account` text,
  `tixian_rate` int(11) NOT NULL DEFAULT '5',
  `tixian_min` int(11) NOT NULL DEFAULT '10',
  `status` int(11) NOT NULL DEFAULT '0',
  `margin` float NOT NULL DEFAULT '0',
  `adtime` datetime DEFAULT NULL,
  `email` text NOT NULL,
  `key` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier`
--

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
INSERT INTO `supplier` VALUES (1,'admin','$2y$10$9/0ITeL/xd136YopA8xVRu3M7GFE.4glMi.PuwIqPXux.IXgKJmd6',188988850,'188988850',0,'',0,'',5,10,0,0,NULL,'188988850@qq.com','');
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_types`
--

DROP TABLE IF EXISTS `video_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_types`
--

LOCK TABLES `video_types` WRITE;
/*!40000 ALTER TABLE `video_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database '11111'
--

--
-- Dumping routines for database '11111'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-09 15:43:52
