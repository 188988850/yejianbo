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
// 辅助日志
file_put_contents(__DIR__.'/../logs/monitor.log',
    date('Y-m-d H:i:s') . ' [goodsdetail.php] ' .
    'URI:' . $_SERVER['REQUEST_URI'] . ' ' .
    'SESSION:' . session_id() . ' ' . json_encode($_SESSION, JSON_UNESCAPED_UNICODE) . ' ' .
    'COOKIE:' . json_encode($_COOKIE, JSON_UNESCAPED_UNICODE) . ' ' .
    'POST:' . json_encode($_POST, JSON_UNESCAPED_UNICODE) . ' ' .
    'GET:' . json_encode($_GET, JSON_UNESCAPED_UNICODE) . PHP_EOL,
    FILE_APPEND
);

$DB = DB::getInstance($dbconfig);

// 获取当前登录用户
$user = null;
$zid = isset($_SESSION['zid']) ? intval($_SESSION['zid']) : 0;
if($zid > 0){
    $user = $DB->fetch("SELECT * FROM shua_site WHERE zid=?", [$zid]);
}

// 商品ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$goods = $DB->fetch("SELECT * FROM shua_goods WHERE id=?", [$id]);
if(!$goods) die('未找到商品');

$price = $goods['price'];

// 判断是否可购买
$can_buy = true;
$has_bought = false;
if($user){
    $current_uid = $user['zid'];
    // 是资源发布人或管理员不可购买
    if($goods['zid'] == $current_uid) $can_buy = false;
    if($user['power'] == 1) $can_buy = false;
    // 已购买不可再买
    $order = $DB->fetch("SELECT * FROM shua_orders WHERE zid=? AND tid=? AND status=1", [$current_uid, $goods['id']]);
    if($order) {
        $can_buy = false;
        $has_bought = true;
    }
}

// 读取底部导航
$nav = $DB->fetchAll("SELECT * FROM `{$dbconfig['dbqz']}_news_nav` WHERE status=1 ORDER BY sort DESC, id ASC");

// 判断是否可见隐藏内容
$can_view_hidden = false;
if($user){
    $current_uid = $user['zid'];
    // 1. 是资源发布人
    if($goods['zid'] == $current_uid) $can_view_hidden = true;
    // 2. 是管理员
    if($user['power'] == 1) $can_view_hidden = true;
    // 3. 已购买
    $order = $DB->fetch("SELECT * FROM shua_orders WHERE zid=? AND tid=? AND status=1", [$current_uid, $goods['id']]);
    if($order) $can_view_hidden = true;
}

try {
} catch(Exception $e) {
    file_put_contents(__DIR__.'/../logs/monitor.log', date('Y-m-d H:i:s') . ' [goodsdetail.php] fatal: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo '<pre>FATAL ERROR: ' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <title><?php echo htmlspecialchars($goods['name']); ?> - 资源详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <style>
    body { background: #f8f9fa; }
    .market-header { background: linear-gradient(90deg, #4a90e2 0%, #2980b9 100%); color: #fff; padding: 2rem 0 1rem 0; margin-bottom: 2rem; }
    .market-header h1 { font-size: 2rem; font-weight: 700; }
    .detail-card { border-radius: 1.2rem; box-shadow: 0 2px 12px #e0e0e0; margin-bottom: 2rem; background: #fff; transition: box-shadow .2s; }
    .detail-card:hover { box-shadow: 0 6px 24px #b0c4de; }
    .detail-img { width: 100%; height: 220px; object-fit: cover; border-top-left-radius: 1.2rem; border-top-right-radius: 1.2rem; }
    .detail-title { font-size: 1.5rem; font-weight: 700; margin: 1rem 0 0.5rem 0; color: #333; }
    .detail-meta { font-size: 1rem; color: #888; margin-bottom: 0.5rem; }
    .detail-price { color: #e74c3c; font-weight: 700; font-size: 1.2rem; }
    .detail-content { color: #444; font-size: 1.05rem; margin: 1rem 0; min-height: 2.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; }
    .detail-hidden { background: #f6f9fc; border-radius: 0.7rem; padding: 1rem; margin: 1rem 0; color: #1a7f37; font-size: 1.08rem; }
    .detail-actions { margin-top: 1.2rem; }
    .detail-actions .btn { border-radius: 1.5rem; font-size: 1.05rem; }
    .download-btn { margin-top: 1rem; }
    @media (max-width: 767px) {
        .detail-img { height: 120px; }
        .market-header { padding: 1.2rem 0 0.7rem 0; }
    }
    .bottom-nav {
        position: fixed;
        left: 16px; right: 16px; bottom: 20px;
        height: 74px;
        background: linear-gradient(180deg,#fff 80%,#f7f7fa 100%);
        border-top: 1.5px solid #e0e0e0;
        display: flex;
        z-index: 200;
        box-shadow: 0 8px 32px 0 rgba(0,0,0,0.13);
        padding-bottom: 8px; padding-top: 4px;
        border-radius: 22px;
        transition: box-shadow 0.2s;
        align-items: flex-end;
    }
    .bottom-nav a {
        flex: 1;
        text-align: center;
        color: #222;
        font-size: 14px;
        text-decoration: none;
        padding-top: 10px;
        transition: color 0.2s, font-size 0.2s;
        user-select: none;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        overflow: hidden;
        font-family: 'PingFang SC','Microsoft YaHei','Heiti SC','SimHei',Arial,sans-serif;
    }
    .bottom-nav a.active,.bottom-nav a:active{color:#ff6600;font-weight:bold;}
    .bottom-nav .icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2px;
        width: 38px; height: 38px;
    }
    .bottom-nav .icon-wrap img {
        width: 38px; height: 38px;
        object-fit: contain;
        border-radius: 50%;
        display: block;
    }
    .bottom-nav span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        font-weight: bold;
        margin-top: 2px;
    }
    .bottom-nav .center-btn {
        position: absolute;
        left: 50%;
        top: -28px;
        transform: translateX(-50%);
        width: 72px;
        height: 72px;
        z-index: 10;
        background: transparent;
        border-radius: 50%;
        box-shadow: 0 2px 12px #ff660044;
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        overflow: visible;
    }
    .bottom-nav .center-btn .rebate-bg {
        position: absolute;
        left: 0; top: 0; width: 100%; height: 100%;
        background: url('https://cdn.aimallol.com/statics/weapp/icon/icon-rebate-bg.png') no-repeat center/cover;
        z-index: 1;
        border-radius: 50%;
        pointer-events: none;
    }
    .bottom-nav .center-btn .rebate-img1,
    .bottom-nav .center-btn .rebate-img2 {
        position: absolute;
        left: 50%; top: 50%;
        width: 48px; height: 48px;
        border-radius: 50%;
        z-index: 2;
        object-fit: contain;
        transition: opacity 0.2s;
        box-shadow: 0 2px 8px #ff660022;
        pointer-events: none;
        transform: translate(-50%, -50%);
    }
    .bottom-nav .center-btn .rebate-img1 { opacity: 1; }
    .bottom-nav .center-btn .rebate-img2 { opacity: 0; }
    @media (max-width:600px){
        .bottom-nav{left:6px;right:6px;bottom:8px;height:60px;padding-bottom:2px;border-radius:14px;}
        .bottom-nav .icon-wrap {width: 30px; height: 30px;}
        .bottom-nav .icon-wrap img{width:30px;height:30px;}
        .bottom-nav span{font-size:12px;}
        .bottom-nav .center-btn { width: 54px; height: 54px; top: -16px; }
        .bottom-nav .center-btn img,
        .bottom-nav .center-btn .rebate-bg { width: 54px; height: 54px; }
        .bottom-nav .center-btn span { font-size: 12px; bottom: -16px; }
    }
    </style>
</head>
<body>
    <div class="market-header text-center">
        <h1><i class="fas fa-store mr-2"></i>资源详情</h1>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="detail-card mb-4">
                    <img class="detail-img" src="<?php echo !empty($goods['image']) ? $goods['image'] : '/assets/img/default-resource.jpg'; ?>" alt="资源图片">
                    <div class="p-4">
                        <div class="detail-title"><?php echo htmlspecialchars($goods['name']); ?></div>
                        <div class="detail-meta">分类：<?php echo htmlspecialchars($goods['class']); ?> &nbsp; <span class="detail-price"><i class="fas fa-yen-sign mr-1"></i><?php echo htmlspecialchars($goods['price']); ?></span></div>
                        <div class="detail-content">
                            <?php echo nl2br(htmlspecialchars($goods['content'])); ?>
                        </div>
                            <?php if(!empty($goods['hidden_content'])): ?>
                                <div class="detail-hidden">
                                    <?php if($can_view_hidden): ?>
                                        <?php echo nl2br(htmlspecialchars($goods['hidden_content'])); ?>
                                    <?php else: ?>
                                        <span style="color:#888;">（付费购买后可见隐藏内容）</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($goods['doc']): ?>
                                <a href="<?php echo $goods['doc']; ?>" class="btn btn-outline-info download-btn" target="_blank"><i class="fas fa-file-download mr-1"></i>下载文档</a>
                            <?php endif; ?>
                    </div>
                </div>
                <div class="detail-actions">
                    <?php if($has_bought): ?>
                        <button class="btn btn-success btn-block" disabled>已购买</button>
                    <?php elseif(!$can_buy): ?>
                        <button class="btn btn-secondary btn-block" disabled>不可购买</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary btn-block" onclick="return buyCheck() && processBuy();"><i class="fas fa-shopping-cart mr-1"></i>购买</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-nav">
        <?php
        $navCount = count($nav);
        foreach($nav as $i => $n):
            // 判断是否为个人中心按钮
            $isCenter = (strpos($n['url'], 'user') !== false || strpos($n['name'], '个人中心') !== false || $i === 3);
            $url = $isCenter ? '/user/index.php' : htmlspecialchars($n['url']);
        ?>
            <?php if($i === 2): ?>
                <a href="/rebate/index.php" class="center-btn rebate-btn">
                    <span class="rebate-bg"></span>
                    <img class="rebate-img1" src="https://cdn.aimallol.com/statics/weapp/icon/icon-rebate-img2.png" alt="返佣主图">
                    <img class="rebate-img2" src="https://cdn.aimallol.com/statics/weapp/icon/icon-rebate-img1.png" alt="返佣闪烁">
                </a>
            <?php endif; ?>
            <a href="<?php echo $url; ?>" class="<?php echo strpos($_SERVER['REQUEST_URI'], $n['url']) !== false ? 'active' : ''; ?>">
                <span class="icon-wrap"><img src="<?php echo htmlspecialchars($n['icon']); ?>" alt="<?php echo htmlspecialchars($n['name']); ?>"></span>
                <span><?php echo htmlspecialchars($n['name']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function buyCheck() {
        <?php if(!$user): ?>
        alert('请先登录后再购买！');
        location.href='/user/login.php';
        return false;
        <?php endif; ?>
        return true;
    }
    
    // 添加购买处理函数
    function processBuy() {
        var goodsId = <?php echo json_encode($id); ?>;
        
        // 使用主系统的下单接口
        $.post('/ajax.php?act=pay', {
            tid: 'goods_' + goodsId,
        }, function(res){
            if(typeof res === 'string') try{res=JSON.parse(res);}catch(e){}
            if(res.code === 0 && res.trade_no){
                // 余额支付
                $.post('/ajax.php?act=payrmb', {orderid: res.trade_no}, function(payres){
                    if(typeof payres === 'string') try{payres=JSON.parse(payres);}catch(e){}
                    if(payres.code === 1){
                        alert('购买成功！');
                        location.reload();
                    }else{
                        alert(payres.msg||'余额支付失败');
                    }
                });
            }else{
                alert(res.msg||'下单失败');
            }
        }, 'json');
    }
    </script>
</body>
</html> 