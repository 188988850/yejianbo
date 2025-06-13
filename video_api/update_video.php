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
    die(json_encode(['error' => '连接失败: ' . $conn->connect_error]));
}

// 获取PUT请求的数据
$data = json_decode(file_get_contents("php://input"), true);

// 检查是否提供了必要的参数
if (!isset($data['xingya_id']) || !isset($data['num']) || !isset($data['url'])) {
    die(json_encode(['error' => '缺少必要的参数']));
}

$xingya_id = $data['xingya_id'];
$num = $data['num'];
$url = $data['url'];

// 定义SQL语句
$sql = "UPDATE shua_video SET url = ? WHERE xingya_id = ? AND num = ?";

// 准备SQL语句
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => '准备SQL语句失败: ' . $conn->error]));
}

// 绑定参数
$stmt->bind_param("sss", $url, $xingya_id, $num);

// 执行SQL语句
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => '记录更新成功']);
    } else {
        echo json_encode(['info' => '没有找到匹配的记录']);
    }
} else {
    echo json_encode(['error' => '执行SQL语句失败: ' . $stmt->error]);
}

// 关闭语句和连接
$stmt->close();
$conn->close();
?>