<?php
session_start();
require_once('../config.php');
require_once dirname(__DIR__).'/includes/common.php';
header('Content-Type: application/json');
// 获取当前登录用户
$user = null;
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_users WHERE id='{$user_id}'")->fetch();
}
if(!$user) exit(json_encode(['code'=>-1,'msg'=>'请先登录']));
$id = intval($_POST['id']);
$resource = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_goods WHERE id={$id}")->fetch();
if(!$resource) exit(json_encode(['code'=>-1,'msg'=>'资源不存在']));
if($user['money'] < $resource['price']) exit(json_encode(['code'=>-1,'msg'=>'余额不足']));
// 检查是否已购买
$order = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_orders WHERE user_id='{$user['id']}' AND resource_id='{$id}' LIMIT 1")->fetch();
if($order) exit(json_encode(['code'=>-1,'msg'=>'你已购买过该资源']));
// 扣除余额
$pdo->prepare("UPDATE {$dbconfig['dbqz']}_user SET money=money-? WHERE id=?")->execute([$resource['price'], $user['id']]);
// 记录订单
$pdo->prepare("INSERT INTO {$dbconfig['dbqz']}_orders (user_id,resource_id,price,addtime) VALUES (?,?,?,NOW())")->execute([$user['id'],$id,$resource['price']]);
exit(json_encode(['code'=>0,'msg'=>'购买成功'])); 