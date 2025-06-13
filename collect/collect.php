<?php
require_once(__DIR__ . '/includes/functions.php');

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 检查必要的扩展
if (!extension_loaded('curl')) {
    die('请安装curl扩展');
}
if (!extension_loaded('pdo')) {
    die('请安装pdo扩展');
}
if (!extension_loaded('pdo_mysql')) {
    die('请安装pdo_mysql扩展');
}

// 创建必要的目录
$dirs = array(
    $GLOBALS['config']['log_path'],
    $GLOBALS['config']['image_path'],
    $GLOBALS['config']['resource_path']
);

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 初始化采集器
$collector = new ResourceCollector();

// 登录验证
$username = '18888955100';
$password = 'wangji520';

$user = $collector->login($username, $password);
if (!$user) {
    die('登录失败，请检查用户名和密码');
}

Logger::log("用户 {$username} 登录成功");

// 开始采集
try {
    // 获取资源列表
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM {$GLOBALS['dbconfig']['dbqz']}_tools WHERE active=1 ORDER BY tid ASC");
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $total = count($resources);
    $current = 0;
    
    foreach ($resources as $resource) {
        $current++;
        Logger::log("开始采集资源: {$resource['name']} ({$current}/{$total})");
        
        // 采集资源
        $result = $collector->collectResource($resource['url']);
        
        if ($result) {
            // 保存处理后的资源
            $save_path = $GLOBALS['config']['resource_path'] . $resource['tid'] . '.json';
            file_put_contents($save_path, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            Logger::log("资源保存成功: {$save_path}");
        } else {
            Logger::log("资源采集失败: {$resource['name']}", 'ERROR');
        }
        
        // 采集间隔
        sleep($GLOBALS['config']['collect_interval']);
    }
    
    Logger::log("采集完成，共处理 {$total} 个资源");
} catch (Exception $e) {
    Logger::log("采集出错: " . $e->getMessage(), 'ERROR');
    die("采集出错: " . $e->getMessage());
}
?> 