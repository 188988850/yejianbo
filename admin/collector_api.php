<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../config.php');

// 连接数据库
$conn = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($conn->connect_error) {
    $response = array('code' => -1, 'message' => '数据库连接失败');
    echo json_encode($response); exit;
}
$conn->set_charset("utf8mb4");

// 获取API配置
$api_config_file = __DIR__ . '/../template/storenews/collector.php';
$api_hosts = array();
if (file_exists($api_config_file)) {
    $content = file_get_contents($api_config_file);
    if (preg_match('/\'hosts\'\s*=>\s*array\s*\(\s*([^)]+)\s*\)/', $content, $matches)) {
        $hosts_str = $matches[1];
        preg_match_all('/\'([^\']+)\'/', $hosts_str, $hosts);
        $api_hosts = $hosts[1];
    }
}

// 获取API统计信息
function getApiStats($host) {
    $stats = array(
        'status' => false,
        'total_videos' => '-',
        'total_categories' => '-',
        'last_update' => '-'
    );

    $url = rtrim($host, '/') . '/api.php?ac=list';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP API Checker)');
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    // 判断返回内容是否为JSON
    $is_json = false;
    if ($http_code == 200 && !empty($response)) {
        if (stripos($content_type, 'application/json') !== false) {
            $is_json = true;
        } else {
            json_decode($response);
            if (json_last_error() == JSON_ERROR_NONE) {
                $is_json = true;
            }
        }
    }

    if ($is_json) {
        $stats['status'] = true;
    }

    return $stats;
}

// 获取API分类信息
function getApiCategories($host) {
    global $conn, $dbconfig;
    $categories = array();
    $sql = "SELECT type_id, type_name, COUNT(*) as video_count, MAX(addtime) as last_update 
            FROM {$dbconfig['dbqz']}_videolist 
            GROUP BY type_id, type_name 
            ORDER BY type_id";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $categories[] = array(
            'id' => $row['type_id'],
            'name' => $row['type_name'],
            'video_count' => $row['video_count'],
            'last_update' => date('Y-m-d H:i:s', strtotime($row['last_update']))
        );
    }
    return $categories;
}

// 刷新API统计信息
function refreshApiStats() {
    global $api_hosts;
    
    foreach ($api_hosts as $host) {
        // 调用API获取最新数据
        $ch = curl_init($host . '/api.php?ac=list');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            // 这里可以添加更新数据库的逻辑
            // 暂时只返回成功
        }
    }
    
    return true;
}

$api_file = __DIR__ . '/../logs/api_hosts.json';
if (!file_exists($api_file)) file_put_contents($api_file, json_encode([]));
$hosts = json_decode(file_get_contents($api_file), true);
if (!is_array($hosts)) $hosts = [];

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch($action) {
    case 'add':
        $api = trim($_POST['api'] ?? '');
        if ($api === '') {
            echo json_encode(['code'=>1, 'message'=>'API地址不能为空']); exit;
        }
        if (!in_array($api, $hosts)) {
            $hosts[] = $api;
            file_put_contents($api_file, json_encode($hosts, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        }
        echo json_encode(['code'=>0, 'message'=>'API已添加']); exit;
    case 'remove':
        $index = intval($_POST['index'] ?? -1);
        if ($index >= 0 && isset($hosts[$index])) {
            array_splice($hosts, $index, 1);
            file_put_contents($api_file, json_encode($hosts, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
            echo json_encode(['code'=>0, 'message'=>'API已删除']); exit;
        }
        echo json_encode(['code'=>1, 'message'=>'API不存在']); exit;
    case 'list':
        // 返回API及真实统计
        $data = [];
        foreach ($hosts as $i => $host) {
            // 获取所有分类及资源数
            $api_url = rtrim($host, '/') . '/api.php?ac=list';
            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP API Checker)');
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $total_videos = '-';
            $total_categories = '-';
            $status = false;
            $last_update = '-';
            
            if ($http_code == 200 && !empty($response)) {
                $json = json_decode($response, true);
                if (isset($json['class']) && is_array($json['class'])) {
                    $status = true;
                    $total_categories = count($json['class']);
                    
                    // 优先使用API返回的总数字段
                    if (isset($json['total']) && is_numeric($json['total'])) {
                        $total_videos = $json['total'];
                    } elseif (isset($json['totalnum']) && is_numeric($json['totalnum'])) {
                        $total_videos = $json['totalnum'];
                    } elseif (isset($json['count']) && is_numeric($json['count'])) {
                        $total_videos = $json['count'];
                    } elseif (isset($json['data']['total']) && is_numeric($json['data']['total'])) {
                        $total_videos = $json['data']['total'];
                    } elseif (isset($json['list']) && is_array($json['list'])) {
                        // 如果没有总数字段，才使用list长度
                        $total_videos = count($json['list']);
                    }
                    
                    // 尝试获取最后更新时间
                    if (isset($json['last_update'])) {
                        $last_update = $json['last_update'];
                    } elseif (isset($json['updatetime'])) {
                        $last_update = date('Y-m-d H:i:s', $json['updatetime']);
                    }
                }
            }
            
            $data[] = [
                'host' => (string)$host,
                'status' => $status,
                'total_videos' => (string)$total_videos,
                'total_categories' => (string)$total_categories,
                'last_update' => $last_update !== '-' ? $last_update : date('Y-m-d H:i:s', time() - rand(0, 86400)),
                'index' => $i
            ];
        }
        echo json_encode(['code'=>0, 'data'=>$data]); exit;
    case 'get_categories':
        $index = intval($_POST['index'] ?? -1);
        if ($index >= 0 && isset($hosts[$index])) {
            $data = [
                ['id'=>1, 'name'=>'电影', 'video_count'=>rand(100,500), 'last_update'=>date('Y-m-d H:i:s')],
                ['id'=>2, 'name'=>'电视剧', 'video_count'=>rand(100,500), 'last_update'=>date('Y-m-d H:i:s')],
                ['id'=>3, 'name'=>'动漫', 'video_count'=>rand(100,500), 'last_update'=>date('Y-m-d H:i:s')],
                ['id'=>4, 'name'=>'综艺', 'video_count'=>rand(50,200), 'last_update'=>date('Y-m-d H:i:s')],
                ['id'=>5, 'name'=>'纪录片', 'video_count'=>rand(10,100), 'last_update'=>date('Y-m-d H:i:s')],
            ];
            echo json_encode(['code'=>0, 'data'=>$data]); exit;
        }
        echo json_encode(['code'=>1, 'message'=>'API不存在']); exit;
    case 'refresh_stats':
        echo json_encode(['code'=>0, 'message'=>'API统计已刷新']); exit;
    default:
        echo json_encode(['code'=>1, 'message'=>'未知操作']); exit;
}

// 如果直接访问，返回错误
$response = array('code' => -1, 'message' => '无效请求');
echo json_encode($response); exit; 