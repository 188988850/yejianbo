<?php
// 金融资源购买接口，极致测试友好：无参数自动用默认值
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once dirname(__DIR__).'/config.php';

// 默认测试参数（请用你数据库里真实存在的ID和价格）
$default_userid = 1;
$default_newsid = 1;
$default_price = 1;

// 兼容GET/POST参数，没传就用默认
$userid = isset($_POST['userid']) ? intval($_POST['userid']) : (isset($_GET['userid']) ? intval($_GET['userid']) : $default_userid);
$newsid = isset($_POST['newsid']) ? intval($_POST['newsid']) : (isset($_GET['newsid']) ? intval($_GET['newsid']) : $default_newsid);
$price = isset($_POST['price']) ? floatval($_POST['price']) : (isset($_GET['price']) ? floatval($_GET['price']) : $default_price);

// 调试输出收到的参数（上线前可注释）
echo '<pre>收到参数: ';
var_dump(['userid'=>$userid, 'newsid'=>$newsid, 'price'=>$price]);
echo '</pre>';

if(!$userid){
    exit('缺少或错误的用户ID(userid)！');
}
if(!$newsid){
    exit('缺少或错误的资源ID(newsid)！');
}
if(!$price || $price<=0){
    exit('缺少或错误的价格(price)！');
}

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
    exit('数据库连接失败: ' . $e->getMessage());
}
// 获取用户信息
try {
    $stmt = $DB->prepare("SELECT * FROM shua_site WHERE zid=? LIMIT 1");
    $stmt->execute([$userid]);
    $userrow = $stmt->fetch();
    if(!$userrow){
        exit('用户信息获取失败！请检查userid是否正确。');
    }
    // 检查是否已购买
    $stmt = $DB->prepare("SELECT * FROM shua_news_order WHERE user_id=? AND news_id=?");
    $stmt->execute([$userid, $newsid]);
    if($stmt->fetch()){
        exit('您已购买过该资源，无需重复购买！');
    }
    // 检查余额
    if($userrow['rmb'] < $price){
        exit('余额不足，请先充值！');
    }
    // 扣款并写订单
    $DB->beginTransaction();
    try {
        $update = $DB->prepare("UPDATE shua_site SET rmb=rmb-? WHERE zid=?");
        $update->execute([$price, $userid]);
        $insert = $DB->prepare("INSERT INTO shua_news_order (user_id, news_id, price, add_time, status) VALUES (?, ?, ?, NOW(), 1)");
        $insert->execute([$userid, $newsid, $price]);
        $DB->commit();
        exit('购买成功！');
    } catch(Exception $e) {
        $DB->rollBack();
        exit('数据库写入失败：'.$e->getMessage());
    }
} catch(Exception $e) {
    exit('数据库操作异常：'.$e->getMessage());
}
?> 