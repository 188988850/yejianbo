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

// 处理请求
$action = isset($_POST['action']) ? $_POST['action'] : '';
$response = array('code' => 0, 'message' => '操作成功');

if ($action === 'get_stats') {
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
    
    // 获取当日采集统计
    $stats = array(
        'collected' => 0,
        'api_updated' => 0,
        'by_category' => array()
    );
    
    // 统计已采集数量
    $sql = "SELECT COUNT(*) as count FROM {$dbconfig['dbqz']}_videolist WHERE DATE(addtime) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stats['collected'] = $row['count'];
    }
    
    // 按分类统计
    $sql = "SELECT t.name, COUNT(v.id) as count 
            FROM {$dbconfig['dbqz']}_videolist v 
            LEFT JOIN {$dbconfig['dbqz']}_videotype t ON v.type = t.id 
            WHERE DATE(v.addtime) = ? 
            GROUP BY v.type";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $stats['by_category'][$row['name']] = $row['count'];
    }
    
    // 获取API更新数量（需要从日志中解析）
    $log_file = __DIR__ . '/../logs/collection.log';
    if (file_exists($log_file)) {
        $content = file_get_contents($log_file);
        if (preg_match_all('/API更新资源数: (\d+)/', $content, $matches)) {
            $stats['api_updated'] = array_sum($matches[1]);
        }
    }
    
    $response = array(
        'code' => 0,
        'message' => '获取统计成功',
        'data' => $stats
    );
    echo json_encode($response); exit;
} else {
    $response = array('code' => -1, 'message' => '未知操作');
    echo json_encode($response); exit;
} 