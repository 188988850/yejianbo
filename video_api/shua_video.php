<?php
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
    die("连接失败: " . $conn->connect_error);
}

// 查询数据
$sql = "SELECT * FROM shua_video";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    // 输出每行数据
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 结果";
}

// 将数组转换为JSON格式并输出
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);

// 关闭连接
$conn->close();
?>