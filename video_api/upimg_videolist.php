<?php
header('Content-Type: application/json');

// 包含数据库配置文件
require '../config.php'; // 假设config.php位于上一级目录中

// 使用配置文件中的配置信息
$servername = $dbconfig['host'];
$username = $dbconfig['user'];
$password = $dbconfig['pwd'];
$dbname = $dbconfig['dbname'];
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => "Connection failed: " . $conn->connect_error]));
}

// 设置字符集
$conn->set_charset("utf8");

// 检查是否为POST请求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取POST数据
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // 验证必填字段
    if (!isset($data['xingya_id']) || !isset($data['img'])) {
        die(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
    }

    // 准备SQL语句
    $stmt = $conn->prepare("UPDATE shua_videolist SET img = ? WHERE xingya_id = ?");

    if ($stmt === false) {
        die(json_encode(['status' => 'error', 'message' => 'Prepare statement failed']));
    }

    // 绑定参数
    $stmt->bind_param("si", $data['img'], $data['xingya_id']);

    // 执行SQL语句
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Image URL updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
    }

    // 关闭语句
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// 关闭连接
$conn->close();
?>