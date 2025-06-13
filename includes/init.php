<?php
// 设置错误报告级别
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 设置字符集
header('Content-Type: text/html; charset=utf-8');

// 自动加载类
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . strtolower($class) . '.class.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 加载配置文件
require_once __DIR__ . '/config.php';

// 初始化日志系统
$logger = Logger::getInstance();
$logger->setLogLevel('INFO');

// 初始化错误处理
$errorHandler = ErrorHandler::getInstance();

// 初始化数据库连接
try {
    $db = Database::getInstance();
} catch (PDOException $e) {
    $logger->critical('数据库连接失败: ' . $e->getMessage());
    die('系统维护中，请稍后再试。');
}

// 初始化性能监控
$performance = Performance::getInstance();

// 记录系统启动
$logger->info('系统启动', [
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
    'memory_limit' => ini_get('memory_limit')
]);

// 安全检查
if (!isset($_SESSION)) {
    session_start();
}

// 防止会话固定攻击
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// 防止XSS攻击
function clean($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean($value);
        }
    } else {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    return $data;
}

// 清理输入数据
$_GET = clean($_GET);
$_POST = clean($_POST);
$_COOKIE = clean($_COOKIE);

// 记录请求
$logger->debug('请求开始', [
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'ip' => $_SERVER['REMOTE_ADDR']
]);

// 性能监控开始
$performance->startRequest();

// 注册关闭函数
register_shutdown_function(function() use ($logger, $performance) {
    // 记录请求结束
    $logger->debug('请求结束', [
        'execution_time' => $performance->getMetrics()['execution_time'],
        'memory_usage' => $performance->getMetrics()['memory_usage']
    ]);
    
    // 检查是否有优化建议
    $reports = $performance->getOptimizationReports();
    if (!empty($reports)) {
        $logger->warning('性能优化建议', ['reports' => $reports]);
    }
}); 