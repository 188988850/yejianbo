<?php
ob_start();
// 移除访问限制，允许直接访问
// if (!defined('IN_CRONLITE')) die();

// 引入配置文件
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

// 基础设置
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '18000M');
set_time_limit(0);
ignore_user_abort(true);
date_default_timezone_set('Asia/Shanghai');

// 获取系统根目录
$root_path = dirname(dirname(dirname(__FILE__)));

// 确保日志目录存在
$log_dir = $root_path . '/logs';
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}

// 配置文件
$config = array(
    // 数据库配置
    'db' => array(
        'host' => $dbconfig['host'],
        'port' => $dbconfig['port'],
        'username' => $dbconfig['user'],
        'password' => $dbconfig['pwd'],
        'database' => $dbconfig['dbname'],
        'prefix' => $dbconfig['dbqz']
    ),
    
    // API配置
    'api' => array(
        'hosts' => array(
            'https://json02.heimuer.xyz',
            'https://json.heimuer.xyz',
            'https://json.heimuer.tv'
        ),
        'timeout' => 30,
        'connect_timeout' => 10,
        'retry_times' => 3,
        'retry_delay' => 1000000,
        'sleep_time' => 100000,
        'rate_limit' => 10,
        'current_host_index' => 0,
        'last_request_time' => 0
    ),
    
    // 采集配置
    'collect' => array(
        'max_empty_pages' => 5,        // 连续空页数阈值
        'max_retry_pages' => 3,        // 单页最大重试次数
        'batch_size' => 100,           // 批量保存大小
        'checkpoint_interval' => 10,   // 检查点间隔（页数）
        'empty_page_threshold' => 0.8  // 空页阈值（80%跳过视为空页）
    )
);

// 状态文件
$status_file = $log_dir . '/collection_status.json';
$log_file = $log_dir . '/collection.log';
$checkpoint_file = $log_dir . '/collection_checkpoint.json';

// 日志函数
function writeLog($message, $type = 'info') {
    global $log_file;
    $time = date('Y-m-d H:i:s');
    $log = "[{$time}] [{$type}] {$message}\n";
    file_put_contents($log_file, $log, FILE_APPEND);
    echo $log;
    flush();
    if (ob_get_level() > 0) {
        ob_flush();
    }
}

// 更新状态
function updateStatus($data) {
    global $status_file;
    file_put_contents($status_file, json_encode($data, 320));
}

// 保存检查点
function saveCheckpoint($data) {
    global $checkpoint_file;
    file_put_contents($checkpoint_file, json_encode($data, 320));
}

// 加载检查点
function loadCheckpoint() {
    global $checkpoint_file;
    if (file_exists($checkpoint_file)) {
        return json_decode(file_get_contents($checkpoint_file), true);
    }
    return null;
}

// 获取当前API主机
function getCurrentHost() {
    global $config;
    return $config['api']['hosts'][$config['api']['current_host_index']];
}

// 切换到下一个API主机
function switchToNextHost() {
    global $config;
    $config['api']['current_host_index'] = ($config['api']['current_host_index'] + 1) % count($config['api']['hosts']);
    return getCurrentHost();
}

// 修改API请求函数，增加重试和错误处理
function makeRequest($url, $retry = 0, $type_name = '', $page = 0, $total_pages = 0, $total_processed = 0, $total_videos = 0) {
    global $config;
    
    // 限流控制
    $now = microtime(true);
    $time_diff = $now - $config['api']['last_request_time'];
    if ($time_diff < (1 / $config['api']['rate_limit'])) {
        usleep((1 / $config['api']['rate_limit'] - $time_diff) * 1000000);
    }
    $config['api']['last_request_time'] = microtime(true);
    
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => $config['api']['timeout'],
        CURLOPT_CONNECTTIMEOUT => $config['api']['connect_timeout'],
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        CURLOPT_ENCODING => 'gzip,deflate',
        CURLOPT_HTTPHEADER => array('Accept: application/json'),
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5
    ));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($response === false || $http_code != 200) {
        writeLog("请求失败: HTTP {$http_code}, 错误: {$error}, URL: {$url}", 'error');
        if ($retry < $config['api']['retry_times']) {
            $retry_count = $retry + 1;
            writeLog("正在重试 ({$retry_count}/{$config['api']['retry_times']})", 'warning');
            usleep($config['api']['retry_delay']);
            
            // 如果当前API失败，尝试切换到下一个
            if ($retry_count % 2 == 0) {
                $new_host = switchToNextHost();
                writeLog("切换到新API: {$new_host}", 'info');
                $url = str_replace(getCurrentHost(), $new_host, $url);
            }
            
            return makeRequest($url, $retry_count, $type_name, $page, $total_pages, $total_processed, $total_videos);
        }
        writeLog("重试{$config['api']['retry_times']}次后仍失败，采集进程退出", 'error');
        updateStatus(array(
            'running' => false,
            'current_category' => $type_name,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'processed_videos' => $total_processed,
            'total_videos' => $total_videos,
            'start_time' => date('Y-m-d H:i:s'),
            'last_update' => date('Y-m-d H:i:s')
        ));
        exit(1);
    }
    
    return $response;
}

// 验证API响应
function validateAPIResponse($response, $type = 'list') {
    if (!$response) {
        return false;
    }
    
    $data = json_decode($response, true);
    if (!$data) {
        return false;
    }
    
    switch ($type) {
        case 'class':
            return isset($data['class']) && is_array($data['class']);
            
        case 'list':
            return isset($data['list']) && is_array($data['list']) && 
                   isset($data['total']) && isset($data['pagecount']) &&
                   !empty($data['list']);
            
        default:
            return false;
    }
}

// 修改批量保存视频函数，增加事务和错误处理
function batchSaveVideos($conn, $videos, $type_id) {
    global $config, $log_file;
    
    if (empty($videos)) {
        return array('success' => 0, 'skipped' => 0, 'error' => 0);
    }
    
    $success = 0;
    $skipped = 0;
    $error = 0;
    
    try {
        $conn->autocommit(false);
        
        foreach ($videos as $video) {
            try {
                // 检查视频是否已存在
                $check_sql = "SELECT id FROM shua_videolist WHERE xingya_id = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("i", $video['vod_id']);
                $check_stmt->execute();
                $result = $check_stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $skipped++;
                    continue;
                }
                
                // 插入视频信息
                $sql = "INSERT INTO shua_videolist (
                    `type`, `name`, `desc`, `price`, `xingya_id`, `prid`, 
                    `cost`, `cost2`, `bfprice`, `bfcost`, `bfcost2`, 
                    `img`, `download_url`, `sort`, `is_hot`, `addtime`, `active`
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
                
                $stmt = $conn->prepare($sql);
                $type = $type_id;
                $name = $video['vod_name'];
                $desc = isset($video['vod_content']) ? $video['vod_content'] : '';
                $price = 0.1;
                $cost = 0.1;
                $cost2 = 0.1;
                $bfprice = 9.99;
                $bfcost = 4.00;
                $bfcost2 = 2.00;
                $xingya_id = $video['vod_id'];
                $prid = 0;
                $img = isset($video['vod_pic']) ? $video['vod_pic'] : '';
                $download_url = 'https://wx.wamsg.cn/';
                $sort = 0;
                $is_hot = 1;
                $addtime = date('Y-m-d H:i:s');
                $active = 1;
                
                $stmt->bind_param(
                    "sssdiiddddsssiisi",
                    $type, $name, $desc, $price, $xingya_id, $prid,
                    $cost, $cost2, $bfprice, $bfcost, $bfcost2,
                    $img, $download_url, $sort, $is_hot, $addtime, $active
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("保存视频信息失败: " . $stmt->error);
                }
                
                $video_id = $conn->insert_id;
                
                // 保存视频集数
                if (isset($video['vod_play_url'])) {
                    $episodes = explode('#', $video['vod_play_url']);
                    foreach ($episodes as $index => $episode) {
                        if (preg_match('/(https?:\/\/[^\s$]+)/', $episode, $matches)) {
                            $play_url = $matches[1];
                            
                            $sql = "INSERT INTO shua_video (
                                `xingya_id`, `pid`, `url`, `num`, `price`, `cost`, `cost2`
                            ) VALUES (
                                ?, ?, ?, ?, ?, ?, ?
                            )";
                            
                            $stmt = $conn->prepare($sql);
                            $episode_num = $index + 1;
                            $episode_price = 0.6;
                            $episode_cost = 0.4;
                            $episode_cost2 = 0.2;
                            
                            $stmt->bind_param("iisdsss",
                                $xingya_id,
                                $video_id,
                                $play_url,
                                $episode_num,
                                $episode_price,
                                $episode_cost,
                                $episode_cost2
                            );
                            
                            if (!$stmt->execute()) {
                                throw new Exception("保存视频集数失败: " . $stmt->error);
                            }
                        }
                    }
                }
                
                $success++;
                
            } catch (Exception $e) {
                writeLog("处理视频失败: {$video['vod_name']} - " . $e->getMessage(), 'error');
                $error++;
            }
        }
        
        $conn->commit();
        
    } catch (Exception $e) {
        $conn->rollback();
        writeLog("批量保存失败: " . $e->getMessage(), 'error');
        throw $e;
    }
    
    return array(
        'success' => $success,
        'skipped' => $skipped,
        'error' => $error
    );
}

// 修改API请求函数，增强主子分类捆绑和资源采集逻辑
function processCategories($conn, $host) {
    global $config, $log_file;

    // 获取分类列表
    $class_url = $host . "/api.php/provide/vod/?ac=class";
    $response = makeRequest($class_url);
    if (!$response || !validateAPIResponse($response, 'class')) {
        throw new Exception("获取分类列表失败");
    }
    $data = json_decode($response, true);
    $classList = $data['class'];

    // 统一父ID字段
    foreach ($classList as &$class) {
        if (isset($class['parent_id'])) {
            $class['pid'] = $class['parent_id'];
        } elseif (isset($class['type_pid'])) {
            $class['pid'] = $class['type_pid'];
        } elseif (isset($class['fid'])) {
            $class['pid'] = $class['fid'];
        } else {
            $class['pid'] = 0;
        }
    }
    unset($class);

    // 建立 type_id => class 映射
    $classMap = [];
    foreach ($classList as $class) {
        $classMap[$class['type_id']] = $class;
    }

    // 递归插入分类
    $dbIdMap = []; // type_id => 本地id

    function insertCategory($class, $classMap, &$dbIdMap, $conn) {
        $type_id = $class['type_id'];
        $name = $class['type_name'];
        $sort = isset($class['sort']) ? $class['sort'] : 1;
        $addtime = time();
        $pid = $class['pid'];
        $parent_db_id = 0;
        if ($pid > 0 && isset($dbIdMap[$pid])) {
            $parent_db_id = $dbIdMap[$pid];
        }
        // 插入或更新
        $sql = "INSERT INTO shua_videotype (name, parent_id, sort, addtime, is_show) VALUES (?, ?, ?, ?, 1) ON DUPLICATE KEY UPDATE name=VALUES(name), parent_id=VALUES(parent_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siii", $name, $parent_db_id, $sort, $addtime);
        $stmt->execute();
        $db_id = $conn->insert_id;
        if (!$db_id) {
            $row = $conn->query("SELECT id FROM shua_videotype WHERE name='".$conn->real_escape_string($name)."' AND parent_id='$parent_db_id'")->fetch_assoc();
            $db_id = $row['id'];
        }
        $dbIdMap[$type_id] = $db_id;
        file_put_contents($GLOBALS['log_file'], "[分类] $name (API:$type_id, DB:$db_id, parent:$parent_db_id)\n", FILE_APPEND);
        // 递归插入子分类
        foreach ($classMap as $sub) {
            if ($sub['pid'] == $type_id) {
                insertCategory($sub, $classMap, $dbIdMap, $conn);
            }
        }
    }

    // 先插入所有主分类（pid=0）
    foreach ($classList as $class) {
        if ($class['pid'] == 0) {
            insertCategory($class, $classMap, $dbIdMap, $conn);
        }
    }

    // 采集资源（只采集叶子分类，即没有子分类的分类）
    $leafTypeIds = [];
    foreach ($classMap as $type_id => $class) {
        $is_leaf = true;
        foreach ($classMap as $sub) {
            if ($sub['pid'] == $type_id) {
                $is_leaf = false;
                break;
            }
        }
        if ($is_leaf) $leafTypeIds[] = $type_id;
    }
    foreach ($leafTypeIds as $type_id) {
        $db_id = $dbIdMap[$type_id];
        $name = $classMap[$type_id]['type_name'];
        processVideos($conn, $host, $type_id, $db_id, $name);
    }

    syncCategories($conn, $classList);
}

// 处理视频资源，type_id为API分类ID，sub_id为本地子分类ID
function processVideos($conn, $host, $type_id, $sub_id, $type_name) {
    global $config;
    $page = 1;
    $total_pages = 1;
    $total_processed = 0;
    $start_time = date('Y-m-d H:i:s');
    do {
        $list_url = $host . "/api.php/provide/vod/?ac=videolist&t={$type_id}&pg={$page}";
        $list_response = makeRequest($list_url);
        if (!$list_response || !validateAPIResponse($list_response, 'list')) {
            writeLog("获取视频列表失败: {$type_name} 第 {$page} 页", 'error');
            break;
        }
        $list_data = json_decode($list_response, true);
        $total_pages = intval($list_data['pagecount']);
        // 批量保存视频，type_id参数传本地子分类ID
        $result = batchSaveVideos($conn, $list_data['list'], $sub_id);
        $total_processed += $result['success'];
        writeLog("处理完成: {$type_name} 第 {$page}/{$total_pages} 页, 新增 {$result['success']} 个视频", 'info');
        // 实时写入状态
        updateStatus(array(
            'running' => true,
            'current_category' => $type_name,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'processed_videos' => $total_processed,
            'total_videos' => $total_processed, // 可根据实际需求调整
            'start_time' => $start_time,
            'last_update' => date('Y-m-d H:i:s')
        ));
        $page++;
        usleep($config['api']['sleep_time']);
    } while ($page <= $total_pages);
}

function syncCategories($conn, $classList) {
    // 先插主分类
    foreach ($classList as $class) {
        if (empty($class['parent_id']) || $class['parent_id'] == 0) {
            $type_id = $class['type_id'];
            $type_name = $class['type_name'];
            $parent_id = 0;
            $sql = "SELECT id FROM shua_videotype WHERE id=? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $type_id);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            if (!$exists) {
                $sql = "INSERT INTO shua_videotype (id, name, parent_id, sort, addtime, is_show) VALUES (?, ?, 0, 1, ?, 1)";
                $stmt = $conn->prepare($sql);
                $now = time();
                $stmt->bind_param('isi', $type_id, $type_name, $now);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    // 再插子分类
    foreach ($classList as $class) {
        if (!empty($class['parent_id']) && $class['parent_id'] != 0) {
            $type_id = $class['type_id'];
            $type_name = $class['type_name'];
            $parent_id = $class['parent_id'];
            $sql = "SELECT id FROM shua_videotype WHERE id=? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $type_id);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            if (!$exists) {
                $sql = "INSERT INTO shua_videotype (id, name, parent_id, sort, addtime, is_show) VALUES (?, ?, ?, 1, ?, 1)";
                $stmt = $conn->prepare($sql);
                $now = time();
                $stmt->bind_param('isii', $type_id, $type_name, $parent_id, $now);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// 只采集叶子分类（没有子分类的分类），资源type字段用本地子分类ID
function getLeafTypeIds($classList) {
    $typeIds = array_column($classList, 'type_id');
    $parentIds = array_column($classList, 'parent_id');
    $leafTypeIds = [];
    foreach ($classList as $class) {
        if (!in_array($class['type_id'], $parentIds) && $class['parent_id'] != 0) {
            $leafTypeIds[] = $class['type_id'];
        }
    }
    return $leafTypeIds;
}

// 采集完成后自动清理缓存垃圾
function autoClearCache() {
    $dirs = [
        dirname(__DIR__, 2) . '/logs',
        dirname(__DIR__, 2) . '/tmp',
    ];
    foreach ($dirs as $dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                $path = $dir . '/' . $file;
                if (is_file($path)) {
                    @unlink($path);
                }
            }
        }
    }
    // 额外清理采集脚本产生的临时大文件（如 .tmp .cache .log）
    $extra_patterns = [
        __DIR__ . '/*.tmp',
        __DIR__ . '/*.cache',
        __DIR__ . '/*.log',
    ];
    foreach ($extra_patterns as $pattern) {
        foreach (glob($pattern) as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}

// 在文件开头注册采集失败写入
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $cronjob_status_file = dirname(__DIR__, 2) . '/logs/cronjob_status.json';
        if (file_exists($cronjob_status_file)) {
            $status = json_decode(file_get_contents($cronjob_status_file), true);
        } else {
            $status = [];
        }
        $status['status'] = 'failed';
        $status['last_result'] = 'failed';
        $status['last_finish_time'] = time();
        $status['last_finish_time_str'] = date('Y-m-d H:i:s');
        $status['fail_reason'] = $error['message'];
        file_put_contents($cronjob_status_file, json_encode($status, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
});

// 修改主程序
try {
    // 连接数据库
    $conn = new mysqli(
        $config['db']['host'],
        $config['db']['username'],
        $config['db']['password'],
        $config['db']['database'],
        $config['db']['port']
    );
    
    if ($conn->connect_error) {
        throw new Exception("数据库连接失败: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    $conn->query("SET SESSION wait_timeout=31536000");
    $conn->query("SET SESSION interactive_timeout=31536000");
    
    writeLog("开始检查API...", 'info');
    
    // 测试API
    $host = getCurrentHost();
    $class_url = $host . "/api.php/provide/vod/?ac=class";
    $response = makeRequest($class_url);
    
    if (!$response) {
        throw new Exception("API {$host} 无法访问");
    }
    
    if (!validateAPIResponse($response, 'class')) {
        throw new Exception("API返回数据格式错误");
    }
    
    writeLog("API连接成功，开始采集分类和资源", 'success');
    
    // 处理分类和资源
    processCategories($conn, $host);
    
    writeLog("采集完成", 'success');
    
    // 更新最终状态
    updateStatus(array(
        'running' => false,
        'current_category' => '',
        'current_page' => 0,
        'total_pages' => 0,
        'processed_videos' => 0,
        'total_videos' => 0,
        'start_time' => date('Y-m-d H:i:s'),
        'last_update' => date('Y-m-d H:i:s')
    ));
    
    // 删除检查点文件
    if (file_exists($checkpoint_file)) {
        unlink($checkpoint_file);
    }
    
    // 采集完成后自动清理缓存垃圾
    autoClearCache();
    
    $conn->close();
    
    // 在采集脚本结尾写入采集完成状态
    $cronjob_status_file = dirname(__DIR__, 2) . '/logs/cronjob_status.json';
    if (file_exists($cronjob_status_file)) {
        $status = json_decode(file_get_contents($cronjob_status_file), true);
    } else {
        $status = [];
    }
    $status['status'] = 'finished';
    $status['last_result'] = 'success';
    $status['last_finish_time'] = time();
    $status['last_finish_time_str'] = date('Y-m-d H:i:s');
    file_put_contents($cronjob_status_file, json_encode($status, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    
} catch (Exception $e) {
    writeLog("错误: " . $e->getMessage(), 'error');
    updateStatus(array(
        'running' => false,
        'current_category' => '',
        'current_page' => 0,
        'total_pages' => 0,
        'processed_videos' => 0,
        'total_videos' => 0,
        'start_time' => date('Y-m-d H:i:s'),
        'last_update' => date('Y-m-d H:i:s')
    ));
    if (isset($conn)) {
        $conn->close();
    }
    exit(1);
}

file_put_contents(__DIR__.'/start_debug.log', date('Y-m-d H:i:s')." collector.php started\n", FILE_APPEND);
?> 