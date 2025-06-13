-- 用户表
CREATE TABLE IF NOT EXISTS `58_users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(100) NOT NULL,
    `role` enum('admin','user') NOT NULL DEFAULT 'user',
    `status` tinyint(1) NOT NULL DEFAULT '1',
    `last_login` datetime DEFAULT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 会话表
CREATE TABLE IF NOT EXISTS `58_sessions` (
    `id` varchar(255) NOT NULL,
    `user_id` int(11) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `user_agent` varchar(255) NOT NULL,
    `last_activity` datetime NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `58_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 日志表
CREATE TABLE IF NOT EXISTS `58_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `level` enum('DEBUG','INFO','WARNING','ERROR','CRITICAL') NOT NULL,
    `message` text NOT NULL,
    `context` text,
    `ip_address` varchar(45) NOT NULL,
    `user_agent` varchar(255) DEFAULT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `level` (`level`),
    KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 缓存表
CREATE TABLE IF NOT EXISTS `58_cache` (
    `id` varchar(255) NOT NULL,
    `value` text NOT NULL,
    `expires_at` datetime NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 设置表
CREATE TABLE IF NOT EXISTS `58_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `key` varchar(50) NOT NULL,
    `value` text NOT NULL,
    `description` varchar(255) DEFAULT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 插入默认管理员账户
INSERT INTO `58_users` (`username`, `password`, `email`, `role`, `status`) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'admin', 1);

-- 插入默认设置
INSERT INTO `58_settings` (`key`, `value`, `description`) VALUES
('site_name', '58同城', '网站名称'),
('site_description', '58同城分类信息网', '网站描述'),
('site_keywords', '58同城,分类信息,生活服务', '网站关键词'),
('admin_email', 'admin@example.com', '管理员邮箱'),
('items_per_page', '20', '每页显示数量'),
('cache_enabled', '1', '是否启用缓存'),
('cache_lifetime', '3600', '缓存生命周期（秒）'); 