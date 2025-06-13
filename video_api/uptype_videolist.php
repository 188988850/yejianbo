<?php
// 包含数据库配置文件
require '../config.php'; // 假设config.php位于上一级目录中

// 使用配置文件中的配置信息
$servername = $dbconfig['host'];
$username = $dbconfig['user'];
$password = $dbconfig['pwd'];
$dbname = $dbconfig['dbname'];

try {
    // 创建PDO实例
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    
    // 设置PDO错误模式为异常
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 从POST请求中获取$xingya_id和$new_type
    $xingya_id = $_POST['xingya_id'];
    $new_type = $_POST['type'];

    // 验证$xingya_id和$new_type是否为空
    if (empty($xingya_id) || empty($new_type)) {
        throw new Exception("缺少必要的参数");
    }

    // 准备SQL语句
    $sql = "UPDATE shua_videolist SET type = :new_type WHERE xingya_id = :xingya_id";
    
    // 使用prepare方法准备SQL语句
    $stmt = $pdo->prepare($sql);

    // 绑定参数
    $stmt->bindParam(':xingya_id', $xingya_id, PDO::PARAM_INT);
    $stmt->bindParam(':new_type', $new_type, PDO::PARAM_STR);

    // 执行SQL语句
    if ($stmt->execute()) {
        echo "记录已成功更新";
    } else {
        echo "更新失败";
    }
} catch (PDOException $e) {
    // 输出错误信息
    echo "数据库错误: " . $e->getMessage();
} catch (Exception $e) {
    // 输出错误信息
    echo "错误: " . $e->getMessage();
}
?>