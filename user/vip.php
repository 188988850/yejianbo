<?php
require_once '../includes/common.php';
$user = get_login_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price = 10; // VIP价格
    if ($user['money'] >= $price) {
        $DB->query("UPDATE `{$dbconfig['dbqz']}_user` SET money=money-$price, vip_expire=DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id='{$user['id']}'");
        echo "<script>alert('开通成功！');location.href='center.php';</script>";
        exit;
    } else {
        echo "<script>alert('余额不足，请先充值！');history.back();</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>开通VIP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/news/style.css">
</head>
<body>
    <h1>开通VIP</h1>
    <p>VIP价格：10元/30天</p>
    <form method="post">
        <button type="submit">立即开通</button>
    </form>
    <a href="center.php">返回会员中心</a>
</body>
</html>