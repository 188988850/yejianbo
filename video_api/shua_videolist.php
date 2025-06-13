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
    die("Connection failed: " . $conn->connect_error);
}



// 设置字符集
$conn->set_charset("utf8");

// 从POST请求中获取name参数
$name = isset($_POST['name']) ? $_POST['name'] : '';

// 检查name是否为空
if (empty($name)) {
    $data = array('error' => 'Name parameter is required');
} else {
    // 预处理SQL语句以防止SQL注入
    $stmt = $conn->prepare("SELECT id FROM shua_videolist WHERE name = ?");
    $stmt->bind_param("s", $name);

    // 执行预处理语句
    $stmt->execute();

    // 绑定结果变量
    $stmt->bind_result($id);

    // 获取查询结果
    $data = array();
    if ($stmt->fetch()) {
        $data['id'] = $id;
    } else {
        $data['error'] = "No results found for name: $name";
    }

    // 关闭语句
    $stmt->close();
}

// 关闭连接
$conn->close();

// 将数组转换为JSON格式并输出
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE); // 使用JSON_UNESCAPED_UNICODE来正确编码中文字符
?>