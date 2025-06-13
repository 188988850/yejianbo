<?php
if (!defined('IN_CRONLITE')) die();

// 在这里引入您的数据库连接文件
require '../config.php'; // 请替换为您的数据库连接文件路径

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // 获取订单 ID

// 验证订单 ID 是否有效
if ($order_id <= 0) {
    echo "<p>无效的订单 ID。</p>";
    exit;
}

// 查询订单的详细信息
$sql = "SELECT A.*, B.`name` FROM `shua_orders` A LEFT JOIN `shua_tools` B ON A.`tid`=B.`tid` WHERE A.`id` = '{$order_id}'";
$order_detail = $DB->query($sql)->fetch();

// 检查订单是否存在
if ($order_detail) {
    // 输出详细信息
    echo "<p>订单号: {$order_detail['tradeno']}</p>";
    echo "<p>商品名称: {$order_detail['name']}</p>";
    echo "<p>订单状态: " . display_zt($order_detail['status']) . "</p>"; // 使用已定义的状态显示函数
    echo "<p>下单时间: {$order_detail['addtime']}</p>";
    // 如果有其他需要显示的详细信息，可以添加在这里
} else {
    echo "<p>未找到订单详细信息。</p>";
}
?>