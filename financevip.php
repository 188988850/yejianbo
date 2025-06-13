<?php
require_once __DIR__.'/config.php';
session_start();
$DB = DB::getInstance($dbconfig);
$user = isset($_SESSION['user_id']) ? $DB->getRow("SELECT * FROM shua_site WHERE zid='".intval($_SESSION['user_id'])."' LIMIT 1") : null;
$vip_price = 99; // 金融会员价格
if(!$user) die('请先登录');
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($user['rmb'] < $vip_price) {
        echo '<script>alert("余额不足，请先充值");location.href="/user/center.php";</script>';
        exit;
    }
    $expire = date('Y-m-d H:i:s', strtotime('+1 year'));
    $DB->exec("UPDATE shua_site SET rmb=rmb-$vip_price, finance_vip=1, finance_vip_expire='$expire' WHERE zid='{$user['zid']}'");
    echo '<script>alert("开通成功！");location.href="finance.php";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>开通金融会员</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/news/style.css">
</head>
<body>
    <h1>开通金融会员</h1>
    <p>金融会员价格：<?php echo $vip_price; ?>元/年</p>
    <p>当前余额：<?php echo $user['rmb']; ?>元</p>
    <form method="post">
        <button type="submit">立即开通</button>
    </form>
    <a href="finance.php">返回列表</a>
</body>
</html> 