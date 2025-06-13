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
if(isset($_SESSION['zid'])) {
    $zid = $_SESSION['zid'];
    $user = $DB->fetch("SELECT * FROM shua_site WHERE zid=?", [$zid]);
}

if(!$user) die('请先登录');

// 资讯ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$news = $DB->fetch("SELECT * FROM shua_news WHERE id=?", [$id]);
if(!$news) die('未找到资讯');

$price = $news['price'];

if(
    isset($_SESSION['zid']) &&
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) && $_POST['action'] === 'buy'
) {
    $user_id = $_SESSION['zid'];
    // 下单
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, '/ajax.php?act=pay');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'tid' => 'news_'.$id, // 以news_前缀区分金融资讯
        'num' => 1,
        'inputvalue' => '金融资讯',
        'news_id' => $id,
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
            echo '<script>alert("购买成功！");location.href="detail.php?id='.$id.'";</script>';
            exit;
        }else{
            echo '<script>alert("'.$payres['msg'].'");</script>';
        }
    }else{
        echo '<script>alert("'.$res['msg'].'");</script>';
    }
}
exit; 