<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../config.php');

if (isset($_POST['action']) && $_POST['action'] === 'replace') {
    $url = isset($_POST['url']) ? trim($_POST['url']) : '';
    if ($url === '') {
        echo json_encode(['code'=>1, 'message'=>'网盘URL不能为空']); exit;
    }
    $conn = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
    if ($conn->connect_error) {
        echo json_encode(['code'=>1, 'message'=>'数据库连接失败']); exit;
    }
    $conn->set_charset('utf8mb4');
    // 假设网盘地址字段为 disk_url，表为 {$dbconfig['dbqz']}_videolist
    $sql = "UPDATE {$dbconfig['dbqz']}_videolist SET disk_url='$url'";
    $conn->query($sql);
    echo json_encode(['code'=>0, 'message'=>'网盘地址已批量替换']); exit;
}
echo json_encode(['code'=>1, 'message'=>'未知操作']); exit; 