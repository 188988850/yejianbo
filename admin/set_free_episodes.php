<?php
// 数据库连接信息
$servername = "localhost"; // 服务器名
$username = "xinfaka"; // 数据库用户名
$password = "82514a97de548852"; // 数据库密码
$dbname = "xinfaka"; // 数据库名

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 检查是否提交了表单
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取范围输入
    $start = intval($_POST['start']);
    $end = intval($_POST['end']);

    // 驳回不在 1 到 20 之间的输入
    if ($start < 1 || $end > 20 || $start > $end) {
        echo "请输入有效的集数范围（1到20）!";
    } else {
        // SQL 更新语句
        $sql = "UPDATE shua_video SET price = '0.00' WHERE num >= ? AND num <= ?";

        // 准备和绑定
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $start, $end);

        // 执行语句
        if ($stmt->execute()) {
            echo "成功更新集数 " . $start . " 到 " . $end . " 为免费！";
        } else {
            echo "更新失败: " . $conn->error;
        }

        // 关闭语句
        $stmt->close();
    }
}

// 关闭连接
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>设置免费集数</title>
</head>
<body>
    <h1>设置免费集数</h1>
    <form method="POST">
        <label for="start">开始集数:</label>
        <input type="number" id="start" name="start" required min="1" max="20">
        <br>
        <label for="end">结束集数:</label>
        <input type="number" id="end" name="end" required min="1" max="20">
        <br>
        <input type="submit" value="设置为免费">
    </form>
</body>
</html>