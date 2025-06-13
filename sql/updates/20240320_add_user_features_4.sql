-- 用户统计表
CREATE TABLE IF NOT EXISTS `user_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action_type` (`action_type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 系统状态表
CREATE TABLE IF NOT EXISTS `system_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 请求日志表
CREATE TABLE IF NOT EXISTS `request_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method` varchar(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `response_time` float DEFAULT NULL,
  `status_code` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `method` (`method`),
  KEY `status_code` (`status_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 错误日志表
CREATE TABLE IF NOT EXISTS `error_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `trace` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 