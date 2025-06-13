-- 添加用户金融会员相关字段
ALTER TABLE `shua_site` 
ADD COLUMN `finance_vip_level` INT(1) DEFAULT 0 COMMENT '金融会员等级：0=未开通，1=普通VIP，2=超级会员，9=永久VIP',
ADD COLUMN `finance_vip_expire_type` VARCHAR(20) DEFAULT '' COMMENT '会员到期类型：month,season,year,forever',
ADD COLUMN `finance_vip_expire` DATE NULL COMMENT '金融会员到期时间';

-- 创建金融订单表（如果不存在）
CREATE TABLE IF NOT EXISTS `shua_finance_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL COMMENT '用户ID',
  `news_id` int(11) DEFAULT NULL COMMENT '资讯ID',
  `resource_id` int(11) DEFAULT NULL COMMENT '资源ID',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态：0=待处理，1=已完成',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `news_id` (`news_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='金融订单表';

-- 确保shua_news表存在且有正确的字段
CREATE TABLE IF NOT EXISTS `shua_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `desc` text COMMENT '描述',
  `content` longtext COMMENT '内容',
  `cover_url` varchar(500) DEFAULT '' COMMENT '封面图片',
  `img` varchar(500) DEFAULT '' COMMENT '图片',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `category_id` int(11) DEFAULT '0' COMMENT '分类ID',
  `public_content` text COMMENT '公开内容',
  `vip_content` text COMMENT 'VIP内容',
  `vip_only` int(1) DEFAULT '0' COMMENT '是否仅VIP可见',
  `read_count` int(11) DEFAULT '0' COMMENT '阅读次数',
  `status` int(1) DEFAULT '1' COMMENT '状态：0=禁用，1=启用',
  `add_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='金融资讯表';

-- 确保shua_goods表存在且有正确的字段
CREATE TABLE IF NOT EXISTS `shua_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `content` text COMMENT '商品详情',
  `class` varchar(100) DEFAULT '' COMMENT '分类',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `image` varchar(500) DEFAULT '' COMMENT '图片',
  `doc` varchar(500) DEFAULT '' COMMENT '文档链接',
  `hidden_content` text COMMENT '隐藏内容（付费后可见）',
  `is_show` int(1) DEFAULT '1' COMMENT '是否显示',
  `status` int(1) DEFAULT '1' COMMENT '状态：0=禁用，1=启用',
  `zid` int(11) DEFAULT '1' COMMENT '发布者用户ID',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `status` (`status`),
  KEY `zid` (`zid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='资源商品表';

-- 创建用户金融资讯查看记录表
CREATE TABLE IF NOT EXISTS `shua_news_viewlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL COMMENT '用户ID',
  `news_id` int(11) NOT NULL COMMENT '资讯ID',
  `date` date NOT NULL COMMENT '查看日期',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '查看时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_news_date` (`zid`,`news_id`,`date`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户资讯查看记录表';

-- 插入示例金融资讯数据
INSERT IGNORE INTO `shua_news` (`title`, `desc`, `content`, `price`, `public_content`, `vip_content`, `status`) VALUES
('股市投资技巧详解', '专业股市投资策略和技巧分享', '<p>这是一篇关于股市投资的专业文章...</p>', 9.90, '本文将为您详细介绍股市投资的基础知识...', '高级投资策略包括：1.技术分析要点 2.基本面分析方法 3.风险控制策略...', 1),
('基金理财入门指南', '新手必看的基金投资指南', '<p>基金投资是理财的重要方式...</p>', 19.90, '基金投资适合新手投资者，风险相对较低...', '专业基金配置建议：1.股票型基金占比30% 2.债券型基金占比50% 3.货币基金占比20%...', 1);

-- 插入示例资源商品数据
INSERT IGNORE INTO `shua_goods` (`name`, `content`, `class`, `price`, `hidden_content`, `status`) VALUES
('股票分析软件', '专业的股票技术分析工具，支持多种指标分析', '投资工具', 99.00, '软件下载链接：https://example.com/download 激活码：ABC123456', 1),
('投资理财电子书', '包含多本经典投资理财书籍的电子版合集', '学习资料', 29.90, '下载链接：https://example.com/books 提取码：BOOK2024', 1);