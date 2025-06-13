<?php
require_once __DIR__.'/config.php';
session_start();
$DB = DB::getInstance($dbconfig);
$user = isset($_SESSION['user_id']) ? $DB->getRow("SELECT * FROM shua_site WHERE zid='".intval($_SESSION['user_id'])."' LIMIT 1") : null;
if(!$user) die('请先登录');
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$resource = $DB->getRow("SELECT * FROM shua_finance_content WHERE id='$id' AND status=1 LIMIT 1");
if(!$resource) die('资源不存在');
$price = $resource['price'];
if($user['rmb'] < $price) {
    echo '<script>alert("余额不足，请先充值");location.href="/user/center.php";</script>';
    exit;
}
$DB->exec("UPDATE shua_site SET rmb=rmb-$price WHERE zid='{$user['zid']}'");
$DB->exec("INSERT INTO shua_finance_order (zid, resource_id, price, status, addtime) VALUES ('{$user['zid']}', '$id', '$price', 1, NOW())");
echo '<script>alert("购买成功！");location.href="financedetail.php?id='.$id.'";</script>';
exit; 