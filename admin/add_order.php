<?php
// 数据库连接
$host = "localhost"; // 比如localhost
$db = "xinfaka"; // 数据库名称
$user = "xinfaka"; // 数据库用户
$pass = "zBfzpFbsecd7SfjG"; // 数据库密码

try {
    // 使用 PDO 连接数据库
    $DB = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 忽略的参数，实际使用时应通过 POST 请求传入
    $userid = 1; // 示例用户ID
    $goodsTitle = "购买《示例商品》全集"; // 商品名称
    $goodsImg = "example_image.jpg"; // 商品图片
    $money = 100; // 订单金额
    $input = "购入账号123"; // 相关输入
    $input2 = "购买全集"; // 订单类型描述

    // 执行插入订单的 SQL 语句
    $sql = "INSERT INTO `shua_orders` (userid, name, shopimg, money, input, input2, status, addtime) 
            VALUES (:userid, :name, :shopimg, :money, :input, :input2, :status, NOW())";
    
    $stmt = $DB->prepare($sql);
    
    // 设置参数
    $stmt->bindParam(':userid', $userid);
    $stmt->bindParam(':name', $goodsTitle);
    $stmt->bindParam(':shopimg', $goodsImg);
    $stmt->bindParam(':money', $money);
    $stmt->bindParam(':input', $input);
    $stmt->bindParam(':input2', $input2);
    
    // 订单状态，假设1代表"已完成"状态
    $status = 1; 
    $stmt->bindParam(':status', $status);
    
    // 执行插入
    $stmt->execute();

    echo "订单添加成功！";
} catch (PDOException $e) {
    echo "错误: " . $e->getMessage();
}

// 关闭数据库连接
$DB = null;
?>