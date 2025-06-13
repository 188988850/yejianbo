<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // 允许跨域请求
header('Access-Control-Allow-Methods: POST'); // 设置允许的HTTP方法
header('Access-Control-Max-Age: 3600'); // 预检请求的有效期
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// 包含数据库配置文件
require '../config.php'; // 假设config.php位于上一级目录中

// 使用配置文件中的配置信息
$servername = $dbconfig['host'];
$username = $dbconfig['user'];
$password = $dbconfig['pwd'];
$dbname = $dbconfig['dbname'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取JSON数据
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    // 检查必要的字段是否存在
    $requiredFields = ['xingya_id'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("$field is required.");
        }
    }

    // 准备SQL语句查询xingya_id是否已存在
    $checkQuery = "SELECT xingya_id FROM shua_videolist WHERE xingya_id = :xingya_id";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->bindParam(':xingya_id', $data['xingya_id']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $response = ['status' => 'error', 'message' => '已经存在，无法添加新记录。'];
    } else {
        // 准备SQL语句插入新数据
        $insertQuery = "INSERT INTO shua_videolist (
            `id`, `type`, `name`, `desc`, `price`, `xingya_id`, `prid`, `cost`, `cost2`, `bfprice`, `bfcost`, `bfcost2`, `img`, `download_url`, `sort`, `is_hot`, `addtime`, `uptime`, `active`
        ) VALUES (
            :id, :type, :name, :desc, :price, :xingya_id, :prid, :cost, :cost2, :bfprice, :bfcost, :bfcost2, :img, :download_url, :sort, :is_hot, :addtime, :uptime, :active
        )";

        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute($data);
        $response = ['status' => 'success', 'message' => '数据插入成功。'];
    }
} catch (PDOException $e) {
    $response = ['status' => 'error', 'message' => '数据库连接失败: ' . $e->getMessage()];
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
?>