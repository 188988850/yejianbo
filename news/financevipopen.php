<?php
// 会员开通/续费接口
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../config.php');

if(isset($_POST['level'])){
    $userid = isset($_SESSION['zid']) ? intval($_SESSION['zid']) : 0;
    $level = intval($_POST['level']);
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 30;
    if(!$userid || !$level){
        exit(json_encode(['code'=>-1, 'msg'=>'参数错误']));
    }
    // 下单
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, '/ajax.php?act=pay');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'tid' => 'financevip_'.$level, // 区分不同等级
        'num' => 1,
        'inputvalue' => '金融会员',
        'user_id' => $userid,
        'duration' => $duration
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($res, true);
    if($res && $res['code'] === 0 && $res['trade_no']){
        // 余额支付
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, '/ajax.php?act=payrmb');
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query(['orderid' => $res['trade_no']]));
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $payres = curl_exec($ch2);
        curl_close($ch2);
        $payres = json_decode($payres, true);
        if($payres && $payres['code'] === 1){
            exit(json_encode(['code'=>0, 'msg'=>'会员开通成功']));
        }else{
            exit(json_encode(['code'=>-1, 'msg'=>$payres['msg']??'余额支付失败']));
        }
    }else{
        exit(json_encode(['code'=>-1, 'msg'=>$res['msg']??'下单失败']));
    }
}

$userid = isset($_SESSION['zid']) ? intval($_SESSION['zid']) : (isset($_POST['userid']) ? intval($_POST['userid']) : 0);
$level = isset($_POST['level']) ? intval($_POST['level']) : 1; // 1=VIP, 2=SVIP
$duration = isset($_POST['duration']) ? intval($_POST['duration']) : 30; // 默认30天

if(!$userid || !$level){
    exit(json_encode(['code'=>-1, 'msg'=>'参数错误']));
}

// 查询会员等级配置（如无配置表，直接写死价格）
$vip_price = 30; // 30元/30天
if($level==2) $vip_price = 88; // SVIP价格

// 数据库连接
$host = $dbconfig['host'];
$dbname = $dbconfig['dbname'];
$user = $dbconfig['user'];
$pass = $dbconfig['pwd'];
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $DB = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit(json_encode(['code'=>-1, 'msg'=>'数据库连接失败: '.$e->getMessage()]));
}
// 获取用户信息
$stmt = $DB->prepare("SELECT * FROM shua_site WHERE zid=? LIMIT 1");
$stmt->execute([$userid]);
$userrow = $stmt->fetch();
if(!$userrow){
    exit(json_encode(['code'=>-1, 'msg'=>'用户信息获取失败']));
}
if($userrow['rmb'] < $vip_price){
    exit(json_encode(['code'=>-1, 'msg'=>'余额不足']));
}
// 计算新到期时间
$now = time();
$old_expire = strtotime($userrow['vip_expire'] ?? '');
$new_expire = $old_expire && $old_expire > $now ? $old_expire + $duration*86400 : $now + $duration*86400;
$new_expire_str = date('Y-m-d H:i:s', $new_expire);
// 获取当前登录用户
$user = null;
if(isset($_SESSION['zid'])) {
    $user_id = $_SESSION['zid'];
    $user = $pdo->query("SELECT * FROM shua_site WHERE zid='{$user_id}'")->fetch();
}
// 会员判断
$is_vip = $user && $user['vip'] == 1 && strtotime($user['vip_expire']) > time();
// 开通会员
$DB->beginTransaction();
try {
    $DB->prepare("UPDATE shua_site SET vip_level=?, vip_expire=?, rmb=rmb-? WHERE zid=?")
        ->execute([$level, $new_expire_str, $vip_price, $userid]);
    $DB->prepare("INSERT INTO shua_vip_order (user_id, level, price, add_time, expire_time, status) VALUES (?, ?, ?, NOW(), ?, 1)")
        ->execute([$userid, $level, $vip_price, $new_expire_str]);
    $DB->commit();
    exit(json_encode(['code'=>0, 'msg'=>'会员开通成功','vip_level'=>$level,'vip_expire'=>$new_expire_str]));
} catch(Exception $e) {
    $DB->rollBack();
    exit(json_encode(['code'=>-1, 'msg'=>'数据库写入失败: '.$e->getMessage()]));
} 