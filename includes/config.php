<?php
// 缓存配置
$cache_config = array(
    'cache_time' => 300, // 缓存时间（秒）
    'cache_dir' => ROOT.'cache/', // 缓存目录
    'cache_prefix' => 'site_', // 缓存前缀
);

// 性能优化配置
$performance_config = array(
    'enable_gzip' => true, // 启用GZIP压缩
    'enable_cache' => true, // 启用页面缓存
    'enable_lazy_load' => true, // 启用图片懒加载
    'enable_preload' => true, // 启用资源预加载
    'minify_css' => true, // 压缩CSS
    'minify_js' => true, // 压缩JS
    'image_optimization' => true, // 图片优化
    'cdn_enabled' => true, // 启用CDN
    'cache_control' => true, // 启用缓存控制
);

// 数据库优化配置
$db_config = array(
    'query_cache' => true, // 启用查询缓存
    'query_cache_time' => 300, // 查询缓存时间
    'max_connections' => 100, // 最大连接数
    'connection_timeout' => 5, // 连接超时时间
);

// 资源加载配置
$resource_config = array(
    'jquery_version' => '3.6.0',
    'bootstrap_version' => '4.6.0',
    'fontawesome_version' => '5.15.4',
    'load_async' => true, // 异步加载JS
    'load_defer' => true, // 延迟加载JS
);

// 错误处理配置
$error_config = array(
    'display_errors' => false, // 生产环境关闭错误显示
    'log_errors' => true, // 记录错误日志
    'error_log' => ROOT.'logs/error.log', // 错误日志路径
);

// 安全配置
$security_config = array(
    'xss_protection' => true, // XSS防护
    'sql_injection_protection' => true, // SQL注入防护
    'csrf_protection' => true, // CSRF防护
    'rate_limit' => true, // 请求频率限制
    'rate_limit_time' => 60, // 限制时间（秒）
    'rate_limit_count' => 100, // 限制次数
); 