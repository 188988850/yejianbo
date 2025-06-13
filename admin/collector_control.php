<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../config.php');

$conn = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($conn->connect_error) {
    $response = array('code' => -1, 'message' => '数据库连接失败');
    echo json_encode($response); exit;
}
$conn->set_charset("utf8mb4");

// 采集脚本路径
$collector_script = __DIR__ . '/../template/storenews/collector.php';
$status_file = __DIR__ . '/../logs/collection_status.json';
$log_file = __DIR__ . '/../logs/collection.log';
$checkpoint_file = __DIR__ . '/../logs/collection_checkpoint.json';

// 确保日志目录存在
$log_dir = dirname($log_file);
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}

// 获取当前状态
function getStatus() {
    global $status_file;
    if (file_exists($status_file)) {
        return json_decode(file_get_contents($status_file), true);
    }
    return array('running' => false);
}

// 更新状态
function updateStatus($data) {
    global $status_file;
    file_put_contents($status_file, json_encode($data, 320));
}

// 启动采集脚本
function startCollector() {
    global $collector_script, $log_file;
    $status = getStatus();
    if ($status['running']) {
        $response = array('code' => -1, 'message' => '采集脚本已在运行中');
        echo json_encode($response); exit;
    }
    $php_path = '/www/server/php/74/bin/php'; // 服务器php路径
    $command = $php_path . ' ' . escapeshellarg($collector_script) . ' > ' . escapeshellarg($log_file) . ' 2>&1 &';
    exec($command);
    file_put_contents(__DIR__.'/start_debug.log', date('Y-m-d H:i:s')."\n$command\n", FILE_APPEND);
    updateStatus(array(
        'running' => true,
        'current_category' => '',
        'current_page' => 0,
        'total_pages' => 0,
        'processed_videos' => 0,
        'total_videos' => 0,
        'start_time' => date('Y-m-d H:i:s'),
        'last_update' => date('Y-m-d H:i:s')
    ));
    $response = array('code' => 0, 'message' => '采集脚本已启动');
    echo json_encode($response); exit;
}

// 停止采集脚本
function stopCollector() {
    global $log_file;
    $status = getStatus();
    if (!$status['running']) {
        $response = array('code' => -1, 'message' => '采集脚本未在运行');
        echo json_encode($response); exit;
    }
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        exec("taskkill /F /IM php.exe");
    } else {
        exec("pkill -f collector.php");
    }
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
    $response = array('code' => 0, 'message' => '采集脚本已停止');
    echo json_encode($response); exit;
}

// 多任务定时管理相关函数
function getCronStatus($asApi = false) {
    $file = __DIR__ . '/../logs/cronjob_status.json';
    $arr = [];
    if (file_exists($file)) {
        $arr = json_decode(file_get_contents($file), true);
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if (!is_array($v) || !isset($v['id'])) unset($arr[$k]);
            }
        }
    }
    if ($asApi) {
        echo json_encode(['code'=>0, 'data'=>array_values($arr)]); exit;
    }
    return $arr;
}

function getCronJobs() {
    $file = __DIR__ . '/../logs/cronjob.json';
    if (file_exists($file)) {
        $arr = json_decode(file_get_contents($file), true);
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if (!is_array($v) || !isset($v['id'])) unset($arr[$k]);
            }
            return $arr;
        }
    }
    return [];
}

function saveCronJobs($jobs) {
    $file = __DIR__ . '/../logs/cronjob.json';
    $ok = file_put_contents($file, json_encode($jobs, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    if ($ok === false) {
        file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." saveCronJobs failed\n", FILE_APPEND);
        echo json_encode(['code'=>1, 'message'=>'无法写入定时任务文件，请检查logs目录权限']); exit;
    } else {
        file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." saveCronJobs success\n", FILE_APPEND);
    }
}

function saveCronStatuses($statuses) {
    $file = __DIR__ . '/../logs/cronjob_status.json';
    $ok = file_put_contents($file, json_encode($statuses, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    if ($ok === false) {
        file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." saveCronStatuses failed\n", FILE_APPEND);
        echo json_encode(['code'=>1, 'message'=>'无法写入定时任务状态文件，请检查logs目录权限']); exit;
    } else {
        file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." saveCronStatuses success\n", FILE_APPEND);
    }
}

function uuid() {
    return bin2hex(random_bytes(8));
}

function setCronJob($data) {
    file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." setCronJob: ".json_encode($data).PHP_EOL, FILE_APPEND);
    $jobs = getCronJobs();
    $statuses = getCronStatus();
    $id = isset($data['id']) ? $data['id'] : uuid();
    $cron_expression = '';
    
    // 计算下次运行时间
    switch($data['mode']) {
        case 'daily':
            list($hour, $minute) = explode(':', $data['time']);
            $cron_expression = "$minute $hour * * *";
            $next_time = strtotime(date('Y-m-d') . ' ' . $data['time']);
            if ($next_time <= time()) $next_time = strtotime('+1 day', $next_time);
            break;
        case 'interval':
            $days = intval($data['days']);
            if($days < 1) $days = 1;
            list($hour, $minute) = explode(':', $data['time']);
            $cron_expression = "$minute $hour */$days * *";
            $next_time = strtotime(date('Y-m-d') . ' ' . $data['time']);
            if ($next_time <= time()) $next_time = strtotime("+{$days} day", $next_time);
            break;
        case 'weekly':
            if(empty($data['weekdays'])) throw new Exception('请至少选择一个星期');
            $weekdays = $data['weekdays'];
            list($hour, $minute) = explode(':', $data['time']);
            $today = date('w');
            $min_diff = 8;
            foreach ($weekdays as $w) {
                $diff = ($w - $today + 7) % 7;
                if ($diff == 0 && strtotime(date('Y-m-d') . ' ' . $data['time']) <= time()) $diff = 7;
                if ($diff < $min_diff) $min_diff = $diff;
            }
            $next_time = strtotime("+{$min_diff} day " . $data['time']);
            $cron_expression = "$minute $hour * * " . implode(',', $weekdays);
            break;
        case 'monthly':
            $day = intval($data['day']);
            if($day < 1 || $day > 31) throw new Exception('每月日期必须在1-31之间');
            list($hour, $minute) = explode(':', $data['time']);
            $cron_expression = "$minute $hour $day * *";
            $cur_month = date('Y-m');
            $next_time = strtotime("$cur_month-$day $data[time]");
            if ($next_time <= time()) $next_time = strtotime("+1 month", $next_time);
            break;
        default:
            throw new Exception('不支持的定时模式');
    }
    
    // 构建任务配置
    $config = array_merge($data, [
        'id' => $id,
        'cron_expression' => $cron_expression,
        'next_time' => $next_time,
        'next_time_str' => date('Y-m-d H:i:s', $next_time),
        'status' => 1, // 保持任务状态为启用
        'last_run_time' => isset($jobs[$id]['last_run_time']) ? $jobs[$id]['last_run_time'] : null,
        'last_run_time_str' => isset($jobs[$id]['last_run_time_str']) ? $jobs[$id]['last_run_time_str'] : null
    ]);
    
    // 如果任务已存在，只更新状态，不覆盖
    if(isset($jobs[$id])) {
        $jobs[$id]['next_time'] = $next_time;
        $jobs[$id]['next_time_str'] = date('Y-m-d H:i:s', $next_time);
        $jobs[$id]['status'] = 1; // 保持任务状态为启用
    } else {
        $jobs[$id] = $config;
    }
    
    saveCronJobs($jobs);
    
    // 更新状态
    if (!isset($statuses[$id])) {
        $statuses[$id] = [
            'id' => $id,
            'status' => 'scheduled',
            'next_time' => $next_time,
            'next_time_str' => date('Y-m-d H:i:s', $next_time),
            'last_run_time' => null,
            'last_run_time_str' => null,
            'last_result' => null,
            'params' => $config
        ];
    } else {
        $statuses[$id]['next_time'] = $next_time;
        $statuses[$id]['next_time_str'] = date('Y-m-d H:i:s', $next_time);
        $statuses[$id]['params'] = $config;
        $statuses[$id]['status'] = 'scheduled'; // 重置状态为已调度
    }
    saveCronStatuses($statuses);
    
    // 设置延迟启动
    $delay = $next_time - time();
    if ($delay > 0) {
        $php_path = '/www/server/php/74/bin/php';
        exec("$php_path " . __DIR__ . "/delayed_start.php $delay $id > /dev/null 2>&1 &");
        file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." exec delayed_start: $delay $id\n", FILE_APPEND);
    }
    
    $response = array('code' => 0, 'message' => '定时任务已保存', 'id' => $id);
    file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." setCronJob done: ".json_encode($response).PHP_EOL, FILE_APPEND);
    echo json_encode($response); exit;
}

function deleteCronJob($id) {
    $jobs = getCronJobs();
    $statuses = getCronStatus();
    
    // 只有手动删除时，才真正删除任务
    if(isset($jobs[$id])) {
        unset($jobs[$id]);
        unset($statuses[$id]);
        saveCronJobs($jobs);
        saveCronStatuses($statuses);
        echo json_encode(['code'=>0,'message'=>'定时任务已删除']); exit;
    }
    
    echo json_encode(['code'=>1,'message'=>'定时任务不存在']); exit;
}

function startCollectorNow($id) {
    $jobs = getCronJobs();
    $statuses = getCronStatus();
    if (!isset($jobs[$id])) {
        echo json_encode(['code'=>1,'message'=>'定时任务不存在']); exit;
    }
    $job = $jobs[$id];
    global $collector_script, $log_file;
    $php_path = '/www/server/php/74/bin/php';
    $params = '';
    $command = $php_path . ' ' . escapeshellarg($collector_script) . ' > ' . escapeshellarg($log_file) . ' 2>&1 &';
    exec($command);
    $statuses[$id]['status'] = 'running';
    $statuses[$id]['last_run_time'] = time();
    $statuses[$id]['last_run_time_str'] = date('Y-m-d H:i:s');
    saveCronStatuses($statuses);
    file_put_contents(__DIR__.'/debug.log', date('Y-m-d H:i:s')." startCollectorNow: $id $command\n", FILE_APPEND);
    echo json_encode(['code'=>0,'message'=>'定时采集已立即启动']); exit;
}

function stopCollectorNow($id) {
    $statuses = getCronStatus();
    if (!isset($statuses[$id])) {
        echo json_encode(['code'=>1,'message'=>'定时任务不存在']); exit;
    }
    $statuses[$id]['status'] = 'stopped';
    saveCronStatuses($statuses);
    echo json_encode(['code'=>0,'message'=>'定时采集已停止']); exit;
}

function clearAllCronJobs() {
    saveCronJobs([]);
    saveCronStatuses([]);
    echo json_encode(['code'=>0,'message'=>'所有定时任务已清除']); exit;
}

// 处理请求
if(isset($_POST['action'])) {
    try {
        switch($_POST['action']) {
            case 'start':
                startCollector();
                break;
            case 'stop':
                stopCollector();
                break;
            case 'cron':
                $data = is_string($_POST['data']) ? json_decode($_POST['data'], true) : $_POST['data'];
                setCronJob($data);
                break;
            case 'delete_cron':
                deleteCronJob($_POST['id']);
                break;
            case 'get_cron_status':
                getCronStatus(true);
                break;
            case 'start_now':
                startCollectorNow($_POST['id']);
                break;
            case 'stop_now':
                stopCollectorNow($_POST['id']);
                break;
            case 'clear_all_cron':
                clearAllCronJobs();
                break;
            case 'clear_cache':
                $cache_dir = __DIR__ . '/../logs/';
                $count = 0;
                foreach (glob($cache_dir.'*') as $file) {
                    if (preg_match('/\.(tmp|cache)$/', $file)) {
                        @unlink($file); $count++;
                    }
                }
                echo json_encode(['code'=>0,'message'=>'缓存垃圾已清理('.$count.'个文件)']); exit;
            case 'clear_status_file':
                $file = __DIR__ . '/../logs/collection_status.json';
                file_put_contents($file, '');
                echo json_encode(['code'=>0,'message'=>'采集状态文件已清空']); exit;
            case 'clear_log_file':
                $file = __DIR__ . '/../logs/collection.log';
                file_put_contents($file, '');
                echo json_encode(['code'=>0,'message'=>'采集日志文件已清空']); exit;
            case 'clear_checkpoint_file':
                $file = __DIR__ . '/../logs/collection_checkpoint.json';
                file_put_contents($file, '');
                echo json_encode(['code'=>0,'message'=>'采集断点文件已清空']); exit;
            case 'clear_type_table':
                $conn->query("TRUNCATE TABLE {$dbconfig['dbqz']}_videotype");
                echo json_encode(['code'=>0,'message'=>'分类数据已清空']); exit;
            case 'clear_videolist_table':
                $conn->query("TRUNCATE TABLE {$dbconfig['dbqz']}_videolist");
                echo json_encode(['code'=>0,'message'=>'影视数据已清空']); exit;
            case 'clear_video_table':
                $conn->query("TRUNCATE TABLE {$dbconfig['dbqz']}_video");
                echo json_encode(['code'=>0,'message'=>'集数数据已清空']); exit;
            default:
                echo json_encode(['code'=>1,'message'=>'未知的操作类型']); exit;
        }
    } catch(Exception $e) {
        echo json_encode(['code'=>1,'message'=>$e->getMessage()]); exit;
    }
}

// 如果直接访问，返回错误
$response = array('code' => -1, 'message' => '无效请求');
echo json_encode($response); exit; 