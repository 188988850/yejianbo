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

// 获取POST请求的数据
$data = json_decode(file_get_contents("php://input"), true);

// 检查数据是否完整
if (!isset($data['xingya_id']) || !isset($data['num'])) {
    die(json_encode(['error' => '缺少必要的参数']));
}

// 定义SQL查询语句
$sql_check = "SELECT id FROM shua_video WHERE xingya_id = ? AND num = ?";

// 准备SQL查询语句
$stmt_check = $conn->prepare($sql_check);
if (!$stmt_check) {
    die(json_encode(['error' => '准备SQL语句失败: ' . $conn->error]));
}

// 绑定参数
$stmt_check->bind_param("ss", $data['xingya_id'], $data['num']);

// 执行查询
$stmt_check->execute();

// 获取结果
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // 记录存在，更新URL
    $sql_update = "UPDATE shua_video SET url = ? WHERE xingya_id = ? AND num = ?";
    
    // 准备更新SQL语句
    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        die(json_encode(['error' => '准备SQL语句失败: ' . $conn->error]));
    }

    // 绑定参数
    $stmt_update->bind_param("sss", $data['url'], $data['xingya_id'], $data['num']);

    // 执行更新
    if ($stmt_update->execute()) {
        echo json_encode(['success' => 'URL更新成功']);
    } else {
        echo json_encode(['error' => '更新URL失败: ' . $stmt_update->error]);
    }

    // 关闭更新语句
    $stmt_update->close();
} else {
    // 记录不存在，插入新记录
    $sql_insert = "INSERT INTO shua_video (id, price, cost, cost2, xingya_id, pid, url, num)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // 准备插入SQL语句
    $stmt_insert = $conn->prepare($sql_insert);
    if (!$stmt_insert) {
        die(json_encode(['error' => '准备SQL语句失败: ' . $conn->error]));
    }

    // 绑定参数
    $stmt_insert->bind_param("isssisss", $data['id'], $data['price'], $data['cost'], $data['cost2'], $data['xingya_id'], $data['pid'], $data['url'], $data['num']);

    // 执行插入
    if ($stmt_insert->execute()) {
        echo json_encode(['success' => '记录添加成功']);
    } else {
        echo json_encode(['error' => '执行SQL语句失败: ' . $stmt_insert->error]);
    }

    // 关闭插入语句
    $stmt_insert->close();
}

// 关闭查询语句和连接
$stmt_check->close();
$conn->close();
?>