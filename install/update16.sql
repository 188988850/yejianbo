DROP TABLE IF EXISTS `shua_invite`;
CREATE TABLE `shua_invite`(
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nid` int(11) UNSIGNED NOT NULL,
  `tid` int(11) UNSIGNED NOT NULL,
  `qq` VARCHAR(20) NOT NULL,
  `input` text NOT NULL,
  `key` VARCHAR(30) NOT NULL UNIQUE,
  `ip` VARCHAR(25) DEFAULT NULL,
  `plan` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `click` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `count` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `qq` (`qq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `shua_inviteshop`;
CREATE TABLE `shua_inviteshop`(
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tid` int(11) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `times` tinyint(1) NOT NULL DEFAULT 0,
  `value` decimal(10,2) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 10,
  `addtime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `shua_invitelog`;
CREATE TABLE `shua_invitelog`(
  `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `iid` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date` datetime DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `orderid` int(11) unsigned DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `iid` (`iid`,`status`),
  KEY `iidip` (`iid`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `shua_config` VALUES ('invite_content', '特价名片赞0.1元起刷，免费领名片赞，免费拉圈圈99+，空间人气、QQ钻、大会员、名片赞、说说赞、空间访问、全民K歌，链接：[url] (请复制链接到浏览器打开)');

ALTER TABLE `shua_kms`
ADD COLUMN `tid` int(11) unsigned NOT NULL DEFAULT 0,
ADD COLUMN `type` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN `orderid` int(11) unsigned DEFAULT 0;

ALTER TABLE `shua_kms`
ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 0;