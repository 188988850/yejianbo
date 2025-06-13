<?php
set_error_handler(function(
    $errno, $errstr, $errfile, $errline
){
    echo "<pre>PHP ERROR: $errstr in $errfile on line $errline</pre>";
    exit;
});
set_exception_handler(function($e){
    echo "<pre>EXCEPTION: ".$e->getMessage()."\n".$e->getTraceAsString()."</pre>";
    exit;
});
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 只引入配置和数据库
require_once dirname(__DIR__).'/config.php';
require_once dirname(__DIR__).'/includes/db.class.php';

session_start();

$DB = DB::getInstance($dbconfig);

// 用户识别
$user = null;
$is_vip = false;
$vip_expire = '';
if(isset($_SESSION['zid'])) {
    $zid = $_SESSION['zid'];
    $user = $DB->fetch("SELECT * FROM shua_site WHERE zid=?", [$zid]);
    if($user && ($user['finance_vip_level'] == 2 || $user['finance_vip_level'] == 9)) {
        $is_vip = true;
        $vip_expire = $user['finance_vip_expire'];
    }
}

// 会员套餐
$vip_plans = $DB->fetchAll("SELECT * FROM shua_finance_vip WHERE status=1 ORDER BY sort DESC, id ASC");

// 读取底部导航
$nav = $DB->fetchAll("SELECT * FROM `{$dbconfig['dbqz']}_news_nav` WHERE status=1 ORDER BY sort DESC, id ASC");

$vip_price = 99; // 金融会员价格
if(!$user) die('请先登录');
if(
    isset($_SESSION['zid']) &&
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) && $_POST['action'] === 'openvip'
) {
    $user_id = $_SESSION['zid'];
    $vip_price = 99; // 金融会员价格
    // 下单
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, '/ajax.php?act=pay');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'tid' => 'financevip', // 以financevip区分金融会员
        'num' => 1,
        'inputvalue' => '金融会员',
        'user_id' => $user_id
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
            echo '<script>alert("会员开通成功！");location.reload();</script>';
            exit;
        }else{
            echo '<script>alert("'.$payres['msg'].'");</script>';
        }
    }else{
        echo '<script>alert("'.$res['msg'].'");</script>';
    }
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
        <input type="hidden" name="action" value="openvip">
        <button type="submit">立即开通</button>
    </form>
    <a href="finance.php">返回列表</a>
</body>
</html> 