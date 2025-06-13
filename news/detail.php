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
    date('Y-m-d H:i:s') . ' [detail.php] ' .
    'URI:' . $_SERVER['REQUEST_URI'] . ' ' .
    'SESSION:' . session_id() . ' ' . json_encode($_SESSION, JSON_UNESCAPED_UNICODE) . ' ' .
    'COOKIE:' . json_encode($_COOKIE, JSON_UNESCAPED_UNICODE) . ' ' .
    'POST:' . json_encode($_POST, JSON_UNESCAPED_UNICODE) . ' ' .
    'GET:' . json_encode($_GET, JSON_UNESCAPED_UNICODE) . PHP_EOL,
    FILE_APPEND
);

try {
    $DB = DB::getInstance($dbconfig);

    // 只允许主账号登录和操作
    $user = null;
    $zid = isset($_SESSION['zid']) ? intval($_SESSION['zid']) : 0;
    if($zid > 0){
        $user = $DB->fetch("SELECT * FROM shua_site WHERE zid=?", [$zid]);
        if(!$user || $user['power'] != 1){
            unset($_SESSION['zid']);
            $user = null;
        }
    } else {
        $user = null;
    }

    $is_vip = false;
    $is_bought = false;
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if(isset($_SESSION['zid'])) {
        $zid = $_SESSION['zid'];
        $user = $DB->fetch("SELECT * FROM shua_site WHERE zid=?", [$zid]);
        if($user && ($user['finance_vip_level'] == 2 || $user['finance_vip_level'] == 9)) $is_vip = true;
        // 判断已购
        $order = $DB->fetch("SELECT * FROM shua_finance_order WHERE zid=? AND news_id=? AND status=1 LIMIT 1", [$user['zid'], $id]);
        if($order) $is_bought = true;
    }

    // 获取资源ID
    $resource = $DB->fetch("SELECT * FROM shua_finance_content WHERE id=?", [$id]);
    if(!$resource) die('未找到资源');

    // 判断是否可购买
    $can_buy = true;
    $has_bought = false;
    if($user){
        $current_uid = $user['zid'];
        // 是资源发布人或管理员不可购买
        if($resource['zid'] == $current_uid) $can_buy = false;
        if($user['power'] == 1) $can_buy = false;
        // 已购买不可再买
        $order = $DB->fetch("SELECT * FROM shua_finance_order WHERE zid=? AND resource_id=? AND status=1", [$current_uid, $resource['id']]);
        if($order) {
            $can_buy = false;
            $has_bought = true;
        }
    }

    // 判断是否可见隐藏内容
    $can_view_hidden = false;
    if($user){
        $current_uid = $user['zid'];
        // 1. 是资源发布人
        if($resource['zid'] == $current_uid) $can_view_hidden = true;
        // 2. 是管理员
        if($user['power'] == 1) $can_view_hidden = true;
        // 3. 已购买
        $order = $DB->fetch("SELECT * FROM shua_finance_order WHERE zid=? AND resource_id=? AND status=1", [$current_uid, $resource['id']]);
        if($order) $can_view_hidden = true;
    }

    // 资讯内容
    $title = '资讯详情';
    $img = '/assets/img/default-resource.jpg';
    $desc = '';
    $content = '';
    $price = '';
    $category_id = '';
    $add_time = '';
    $read_count = '';
    $public_content = '';
    $vip_content = '';
    $vip_only = 0;
    $status = 0;
    $has_data = false;
    if ($id > 0) {
        $row = $DB->fetch("SELECT * FROM shua_news WHERE id=?", [$id]);
        if ($row) {
            $has_data = true;
            $title = $row['title'];
            $img = $row['img'] ?: ($row['cover_url'] ?: $img);
            $desc = $row['desc'];
            $content = $row['content'];
            $price = $row['price'];
            $category_id = $row['category_id'];
            $add_time = $row['add_time'];
            $read_count = $row['read_count'];
            $public_content = $row['public_content'];
            $vip_content = $row['vip_content'];
            $vip_only = $row['vip_only'];
            $status = $row['status'];
        }
    }

    // 读取底部导航
    $nav = $DB->fetchAll("SELECT * FROM `{$dbconfig['dbqz']}_news_nav` WHERE status=1 ORDER BY sort DESC, id ASC");
} catch(Exception $e) {
    file_put_contents(__DIR__.'/../logs/monitor.log', date('Y-m-d H:i:s') . ' [detail.php] fatal: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo '<pre>FATAL ERROR: ' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($title); ?> - 资讯详情</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
    body { background: #f8f9fa; }
    .detail-card { border-radius: 1.2rem; box-shadow: 0 2px 12px #e0e0e0; margin-bottom: 2rem; background: #fff; }
    .detail-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.2rem 0.5rem 1.2rem; }
    .detail-header-left { display: flex; align-items: center; }
    .back-btn { border: none; background: #eaf4fb; font-size: 1.5rem; color: #2980b9; margin-right: 0.7rem; cursor: pointer; border-radius: 50%; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px #e0e0e0; transition: background .2s; }
    .back-btn:hover { background: #d0e6f7; }
    .detail-title { font-size: 2rem; font-weight: 700; color: #222; margin: 0; display: flex; align-items: center; }
    .share-btn { border: none; background: #eaf4fb; font-size: 1.3rem; color: #2980b9; margin-left: 0.7rem; cursor: pointer; border-radius: 50%; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px #e0e0e0; transition: background .2s; }
    .share-btn span { font-size: 1.3rem; }
    .share-btn:hover { background: #d0e6f7; }
    .share-label { margin-left: 6px; color: #2980b9; font-size: 1.05rem; font-weight: 500; }
    .detail-img-wrap { width: 100%; background: #f4f6fa; display: flex; align-items: center; justify-content: center; border-top-left-radius: 1.2rem; border-top-right-radius: 1.2rem; overflow: hidden; }
    .detail-img { width: 100%; max-width: 480px; height: 220px; object-fit: cover; display: block; margin: 0 auto; position: relative; }
    .img-save-btn { display: block; margin: 8px auto 0 auto; background: #eaf4fb; color: #2980b9; border: none; border-radius: 6px; padding: 2px 14px; font-size: 1rem; cursor: pointer; box-shadow: 0 2px 8px #e0e0e0; }
    .img-save-btn:hover { background: #d0e6f7; }
    .detail-meta { font-size: 1rem; color: #888; margin-bottom: 0.7rem; text-align: center; }
    .detail-badges { text-align: center; margin-bottom: 0.5em; }
    .detail-badges span { margin-right: 0.5em; }
    .detail-section { margin-bottom: 1.5rem; }
    .detail-section-title { font-weight: bold; color: #2980b9; margin-bottom: 0.5rem; font-size: 1.1rem; }
    .detail-content { color: #444; font-size: 1.08rem; margin: 1.2rem 0; line-height: 1.8; background: #f9f9fb; border-radius: 0.7rem; padding: 1.1rem; position: relative; }
    .content-actions { position: absolute; top: 10px; right: 10px; }
    .content-btn { font-size: 1.1rem; color: #2980b9; background: #eaf4fb; border: none; border-radius: 6px; padding: 2px 10px; margin-left: 6px; cursor: pointer; }
    .alert-vip { background: #f6f9fc; border-left: 4px solid #4a90e2; color: #1a7f37; }
    .alert-info { font-size: 1.02rem; }
    .detail-content img, .alert-info img, .alert-vip img { max-width: 100%; height: auto; display: block; margin: 12px auto; border-radius: 8px; box-shadow: 0 2px 8px #e0e0e0; position: relative; }
    @media (max-width: 767px) {
        .detail-header { flex-direction: column; align-items: flex-start; padding: 0.7rem 0.7rem 0.3rem 0.7rem; }
        .detail-title { font-size: 1.3rem; }
        .detail-img { height: 120px; max-width: 100%; }
        .detail-content { font-size: 0.98rem; padding: 0.7rem; }
    }
    .img-save-btn, .cover-save-btn {
        background: rgba(234,244,251,0.92);
        color: #2980b9;
        border: none;
        border-radius: 18px;
        padding: 4px 16px;
        font-size: 1rem;
        cursor: pointer;
        box-shadow: 0 2px 8px #e0e0e0;
        transition: background .2s;
            position: absolute;
        right: 12px;
        bottom: 12px;
            z-index: 2;
    }
    .img-save-btn:hover, .cover-save-btn:hover { background: #d0e6f7; }
    .content-btn.main-copy-btn, .content-btn.main-saveall-btn {
        background: #eaf4fb;
        color: #2980b9;
        border: none;
        border-radius: 12px;
        padding: 4px 18px;
        font-size: 1.08rem;
        margin-left: 8px;
        box-shadow: 0 2px 8px #e0e0e0;
        transition: background .2s;
    }
    .content-btn.main-copy-btn:hover, .content-btn.main-saveall-btn:hover { background: #d0e6f7; }
    .share-btn-fixed {
            position: fixed;
            top: 18px;
        right: 18px;
            z-index: 999;
        border: none;
        background: #eaf4fb;
        font-size: 1.3rem;
        color: #2980b9;
            border-radius: 50%;
        width: 48px;
        height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        box-shadow: 0 2px 8px #e0e0e0;
        transition: background .2s;
    }
    .share-btn-fixed .share-label { display:none; }
    @media (max-width: 767px) {
        .share-btn-fixed { width: 42px; height: 42px; font-size: 1.1rem; top: 10px; right: 10px; }
    }
    .top-btn {
        background: #eaf4fb;
        color: #2980b9;
        border: none;
        border-radius: 18px;
        padding: 6px 18px;
        font-size: 1.08rem;
        margin: 0 6px;
        box-shadow: 0 2px 8px #e0e0e0;
        display: flex;
        align-items: center;
        transition: background .2s;
        cursor: pointer;
    }
    .top-btn .icon { font-size: 1.2em; margin-right: 6px; }
    .top-btn.copy-title-btn { margin-left: 10px; }
    .top-btn:hover { background: #d0e6f7; }
    @media (max-width: 767px) {
        .detail-header { padding: 0.7rem 0.7rem 0.3rem 0.7rem; }
        .detail-title { font-size: 1.1rem; }
        .top-btn { font-size: 0.98rem; padding: 5px 12px; }
        .top-btn .icon { font-size: 1em; }
    }
    .detail-header-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0.7rem 0.3rem 0.7rem;
        background: #fff;
    }
    .top-btn {
        background: #eaf4fb;
        color: #2980b9;
        border: none;
        border-radius: 18px;
        padding: 6px 18px;
        font-size: 1.02rem;
        margin: 0;
        box-shadow: 0 2px 8px #e0e0e0;
        display: flex;
        align-items: center;
        height: 38px;
        transition: background .2s;
        cursor: pointer;
    }
    .top-btn .icon { font-size: 1.1em; margin-right: 4px; }
    .top-btn:hover { background: #d0e6f7; }
    .detail-title-wrap {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 0;
    }
    .detail-title {
        font-size: 1.08rem;
        font-weight: 600;
        color: #222;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        max-width: 60vw;
    }
    .copy-title-btn {
        background: #eaf4fb;
        color: #2980b9;
        border: none;
        border-radius: 12px;
        padding: 4px 12px;
        font-size: 0.98rem;
        margin-left: 10px;
        box-shadow: 0 2px 8px #e0e0e0;
        transition: background .2s;
        cursor: pointer;
        height: 32px;
            display: flex;
            align-items: center;
    }
    .copy-title-btn:hover { background: #d0e6f7; }
    .detail-img-circle-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 18px 0 10px 0;
    }
    .detail-img-circle {
        width: 92px;
        height: 92px;
            border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 12px #e0e0e0;
        border: 4px solid #fff;
        background: #f4f6fa;
    }
    @media (max-width: 767px) {
        .detail-header-flex { padding: 0.5rem 0.2rem 0.2rem 0.2rem; }
        .top-btn { font-size: 0.92rem; padding: 4px 10px; height: 32px; }
        .detail-title { font-size: 0.98rem; }
        .copy-title-btn { font-size: 0.92rem; padding: 3px 8px; height: 28px; }
        .detail-img-circle { width: 68px; height: 68px; }
    }
    .detail-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0.7rem 0.3rem 0.7rem;
        background: #fff;
    }
        .header-btn {
        background: #eaf4fb;
        color: #2980b9;
        border: none;
        border-radius: 18px;
        padding: 6px 18px;
        font-size: 1.02rem;
        margin: 0;
        box-shadow: 0 2px 8px #e0e0e0;
            display: flex;
            align-items: center;
        height: 38px;
        transition: background .2s;
            cursor: pointer;
        min-width: 72px;
        font-weight: 500;
    }
    .header-btn svg { margin-right: 6px; }
    .header-btn:hover { background: #d0e6f7; }
    .detail-title-wrap {
        flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        min-width: 0;
    }
    .detail-title {
        font-size: 1.12rem;
        font-weight: 600;
        color: #222;
        white-space: normal;
        word-break: break-all;
        overflow: visible;
        text-overflow: unset;
        max-width: 100%;
        text-align: center;
    }
    .copy-title-btn {
        background: #eaf4fb;
        color: #2980b9;
        border: none;
        border-radius: 12px;
        padding: 4px 12px;
        font-size: 0.98rem;
        margin-left: 10px;
        box-shadow: 0 2px 8px #e0e0e0;
        transition: background .2s;
            cursor: pointer;
        height: 32px;
        display: flex;
        align-items: center;
    }
    .copy-title-btn:hover { background: #d0e6f7; }
    .detail-img-banner-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 18px 0 10px 0;
    }
    .detail-img-banner {
        width: 100%;
        max-width: 480px;
        height: 200px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 2px 12px #e0e0e0;
        border: 2px solid #fff;
        background: #f4f6fa;
    }
    @media (max-width: 767px) {
        .detail-header-bar { padding: 0.5rem 0.2rem 0.2rem 0.2rem; }
        .header-btn { font-size: 0.92rem; padding: 4px 10px; height: 32px; min-width: 54px; }
        .detail-title { font-size: 0.98rem; }
        .copy-title-btn { font-size: 0.92rem; padding: 3px 8px; height: 28px; }
        .detail-img-banner { height: 120px; }
    }
    /* 顶部导航栏样式 */
    .top-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 44px;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        z-index: 1000;
    }
    
    .back-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .back-btn:hover {
        background: #e0e0e0;
        transform: scale(1.05);
    }
    
    .share-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .share-btn:hover {
        background: #e0e0e0;
        transform: scale(1.05);
    }

    /* 内容区域样式 */
    .content {
        margin-top: 54px; /* 为顶部导航栏留出空间 */
        padding: 15px;
    }
    /* 添加购买按钮样式 */
    .buy-btn {
        background: #2980b9;
            color: #fff;
        border: none;
        border-radius: 20px;
        padding: 8px 24px;
        font-size: 1.1rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(41,128,185,0.3);
        transition: all 0.3s ease;
        cursor: pointer;
        margin-left: 15px;
    }
    
    .buy-btn:hover {
        background: #3498db;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(41,128,185,0.4);
    }
    
    .buy-btn i {
        margin-right: 8px;
        font-size: 1.2em;
    }
    
    .price-badge {
        background: #27ae60;
        color: #fff;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 1.2rem;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(39,174,96,0.3);
    }
    
    .buy-section {
        text-align: center;
        margin: 20px 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
    }
    .fav-btn:hover #favIcon { color: #ff6600; transform: scale(1.15); }
    .fav-btn #favIcon.faved { color: #ff6600; }
    .fav-btn #favText { transition: color 0.2s; }
    </style>
</head>
<body>
    <!-- 顶部导航栏 -->
    <div class="top-nav">
        <a href="javascript:history.back();" class="back-btn">
            <i class="fas fa-chevron-left"></i>
        </a>
        <a href="javascript:void(0);" onclick="showQrModal()" class="share-btn" style="margin-left:auto;">
            <i class="fas fa-share-alt"></i>
        </a>
    </div>

    <!-- 内容区域 -->
    <div class="content">
        <div class="container mt-4">
            <div class="detail-card">
                <div class="detail-header-bar">
                    <div class="detail-title-wrap">
                        <span class="detail-title" id="detail-title-text"><?php echo htmlspecialchars($title); ?></span>
                        <button id="favBtn" class="fav-btn" style="margin-left:10px;display:flex;align-items:center;background:transparent;border:none;outline:none;cursor:pointer;font-size:1.3rem;" title="收藏/取消收藏">
                            <span id="favIcon" style="font-size:1.7rem;transition:color 0.2s;">&#9825;</span>
                            <span id="favText" style="font-size:1.02rem;margin-left:4px;">收藏</span>
                        </button>
                        <button class="copy-title-btn" onclick="copyTitle()">复制</button>
                    </div>
                </div>
                <div class="detail-img-banner-wrap">
                    <img class="detail-img-banner" src="<?php echo htmlspecialchars($img); ?>" alt="资讯图片" id="cover-img">
                </div>
                <div class="p-4">
                    <?php if($desc): ?>
                        <div class="detail-section"><div class="detail-content"><?php echo htmlspecialchars($desc); ?></div></div>
                    <?php endif; ?>
                    <div class="detail-section">
                        <div class="detail-content" id="main-content">
                            <?php echo $content; ?>
                        </div>
                        <div id="save-all-imgs-bar" style="margin-top:10px;text-align:right;"></div>
                    </div>
                    <?php if($public_content): ?>
                        <div class="alert alert-info detail-section">
                            <?php echo $public_content; ?>
                        </div>
                    <?php endif; ?>
                    <div class="buy-section">
                        <span class="price-badge">价格: <?php echo htmlspecialchars($price); ?></span>
                        <?php if(!$is_vip && !$is_bought && $has_data): ?>
                        <button class="buy-btn" onclick="buyNow()">
                            <i class="fas fa-shopping-cart"></i>立即购买
                        </button>
                        <?php elseif($is_bought): ?>
                        <span style="color: #27ae60;">已购买</span>
                        <?php elseif($is_vip): ?>
                        <span style="color: #f1c40f;">VIP会员免费</span>
                        <?php endif; ?>
                    </div>
                    <?php if($has_data && ($is_vip || $is_bought)): ?>
                        <div class="alert alert-vip detail-section">
                            <?php echo $vip_content; ?>
                        </div>
                    <?php endif; ?>
                    <?php if(!$has_data): ?>
                        <div class="alert alert-danger mt-4">未查到任何资源内容</div>
                    <?php endif; ?>
                    <div class="detail-section mt-4">
                        <div class="detail-content">
                            <ul style="margin-bottom:0;">
                                <li><b>普通用户：</b> 仅可购买单条资讯，无法免费看会员内容。</li>
                                <li><b>月度VIP：</b> 每日可免费看5条会员资讯。</li>
                                <li><b>季度VIP：</b> 每日可免费看10条会员资讯。</li>
                                <li><b>年度VIP：</b> 每日可免费看20条会员资讯。</li>
                                <li><b>永久VIP/超级会员：</b> 可无限制免费看所有会员内容，无需购买。</li>
                                <li><b>代理/分销：</b> 购买后可获得返佣，佣金比例请咨询平台。</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 分享二维码弹窗 -->
    <div id="qrModal" style="display:none;position:fixed;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;padding:2em 2em 1em 2em;border-radius:1.2em;box-shadow:0 2px 16px #888;max-width:90vw;max-height:90vh;display:flex;flex-direction:column;align-items:center;">
            <div style="font-size:1.2em;font-weight:bold;margin-bottom:1em;">扫码分享本页</div>
            <div id="qrcode"></div>
            <button class="btn btn-primary mt-3" onclick="document.getElementById('qrModal').style.display='none'">关闭</button>
        </div>
    </div>
    <!-- 收藏夹漂浮按钮 -->
    <button id="favFloatBtn" class="fav-float-btn" title="收藏夹">
        <span style="font-size:1.7rem;line-height:1;color:#ff6600;">&#10084;</span>
    </button>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs2@0.0.2/qrcode.min.js"></script>
    <script>
    // 页面状态管理
    let detailState = {
        initialized: false,
        fromIndex: false,
        returnUrl: null
    };

    // 初始化页面
    function initDetailPage() {
        if (detailState.initialized) return;
        
        // 检查是否从首页来
        const fromNewsId = localStorage.getItem('fromNewsId');
        if (fromNewsId) {
            detailState.fromIndex = true;
            detailState.returnUrl = '/news/index.php?fromId=' + fromNewsId;
            localStorage.removeItem('fromNewsId');
        } else {
            // 如果不是从首页来，尝试从 referrer 获取来源
            const referrer = document.referrer;
            if (referrer && referrer.includes('/news/index.php')) {
                detailState.fromIndex = true;
                detailState.returnUrl = '/news/index.php';
            }
        }
        
        // 初始化返回按钮
        initBackButton();
        // 初始化收藏功能
        initFavButton();
        // 初始化分享功能
        initShareButton();
        
        detailState.initialized = true;
    }

    // 初始化返回按钮
    function initBackButton() {
        const backBtn = document.querySelector('.back-btn');
        if (backBtn) {
            backBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (detailState.fromIndex && detailState.returnUrl) {
                    // 如果是从首页来的，直接跳转到首页
                    window.location.href = detailState.returnUrl;
                } else {
                    // 如果不是从首页来的，使用 history.back
                    window.history.back();
                }
            };
        }
    }

    // 初始化收藏按钮
    function initFavButton() {
        const favBtn = document.getElementById('favBtn');
        const favIcon = document.getElementById('favIcon');
        const favText = document.getElementById('favText');
        const newsId = <?php echo json_encode($id); ?>;
        
        if (favBtn && favIcon && favText) {
            function getFavList() {
                try { return JSON.parse(localStorage.getItem('favNewsList')||'[]'); } catch(e) { return []; }
            }
            
            function setFavList(list) {
                localStorage.setItem('favNewsList', JSON.stringify(list));
            }
            
            function isFaved(id) {
                return getFavList().some(function(item) { return item.id == id; });
            }
            
            function addFav(item) {
                var list = getFavList();
                if (!isFaved(item.id)) {
                    list.unshift(item);
                    setFavList(list);
                }
            }
            
            function removeFav(id) {
                var list = getFavList().filter(function(item) { return item.id != id; });
                setFavList(list);
            }
            
            function updateBtn() {
                if (isFaved(newsId)) {
                    favIcon.innerHTML = '&#10084;';
                    favIcon.classList.add('faved');
                    favText.innerText = '已收藏';
                    favText.style.color = '#ff6600';
                } else {
                    favIcon.innerHTML = '&#9825;';
                    favIcon.classList.remove('faved');
                    favText.innerText = '收藏';
                    favText.style.color = '#888';
                }
            }
            
            favBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (isFaved(newsId)) {
                    removeFav(newsId);
                    alert('已取消收藏');
                } else {
                    addFav({
                        id: newsId,
                        title: document.getElementById('detail-title-text').innerText,
                        img: document.getElementById('cover-img').src,
                        url: window.location.pathname + window.location.search
                    });
                    alert('已收藏');
                }
                updateBtn();
            };
            
            updateBtn();
        }
    }

    // 初始化分享按钮
    function initShareButton() {
        const shareBtn = document.querySelector('.share-btn');
        if (shareBtn) {
            shareBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                showQrModal();
            };
        }
    }

    // 显示二维码弹窗
    function showQrModal() {
        var shareUrl = window.location.href;
        document.getElementById('qrModal').style.display = 'flex';
        document.getElementById('qrcode').innerHTML = '';
        new QRCode(document.getElementById('qrcode'), {
            text: shareUrl,
            width: 180,
            height: 180
        });
    }

    // 复制文本
    function copyText(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => { alert('已复制'); });
        } else {
            var input = document.createElement('input');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('已复制');
        }
    }

    // 保存图片
    function saveImage(url) {
        var a = document.createElement('a');
        a.href = url;
        a.download = '';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // 复制标题
    function copyTitle() {
        var text = document.getElementById('detail-title-text').innerText;
        copyText(text);
    }

    // 购买功能
    function buyNow() {
        var newsId = <?php echo json_encode($id); ?>;
        var price = <?php echo json_encode($price); ?>;
        if(!newsId || !price){
            alert('资源信息有误，无法购买');
            return;
        }
        // 检查登录
        <?php if(!isset($_SESSION['zid'])): ?>
            alert('请先登录后再购买');
            window.location.href = '/user/login.php?return_url=' + encodeURIComponent(window.location.href);
            return;
        <?php endif; ?>
        // 下单
        $.post('/ajax.php?act=pay', {
            tid: 'news_'+newsId, // 以news_前缀区分金融资讯
            num: 1,
            inputvalue: '金融资讯',
            news_id: newsId,
            user_id: '<?php echo isset($_SESSION['zid']) ? $_SESSION['zid'] : ''; ?>'
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

    // 页面加载完成后初始化
    window.addEventListener('DOMContentLoaded', function() {
        initDetailPage();
        
        // 初始化图片保存按钮
        var imgs = document.querySelectorAll('#main-content img');
        imgs.forEach(function(img) {
            var btn = document.createElement('button');
            btn.className = 'img-save-btn';
            btn.innerText = '保存图片';
            btn.style.position = 'absolute';
            btn.style.right = '8px';
            btn.style.bottom = '8px';
            btn.style.zIndex = 2;
            btn.onclick = function(e) {
                e.stopPropagation();
                saveImage(img.src);
            };
            img.style.position = 'relative';
            img.parentNode.style.position = 'relative';
            img.parentNode.appendChild(btn);
        });
        
        // 添加一键保存全部图片按钮
        if (imgs.length > 0) {
            var bar = document.getElementById('save-all-imgs-bar');
            var btnAll = document.createElement('button');
            btnAll.className = 'content-btn main-saveall-btn';
            btnAll.innerText = '一键保存全部图片';
            btnAll.onclick = function() {
                imgs.forEach(function(img, i) {
                    setTimeout(function() { saveImage(img.src); }, i * 300);
                });
            };
            bar.appendChild(btnAll);
        }
    });

    // 添加页面卸载事件处理
    window.addEventListener('beforeunload', function() {
        if (detailState.fromIndex) {
            // 清除所有相关状态
            localStorage.removeItem('fromNewsId');
        }
    });
    </script>
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
    <style>
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
    .fav-float-btn {
        position: fixed;
        right: 22px;
        bottom: 100px;
        z-index: 99999 !important;
        background: linear-gradient(135deg,#fff 60%,#ffe7c2 100%);
        border-radius: 50%;
        box-shadow: 0 2px 8px #ff880022;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ff8800;
        cursor: pointer;
        font-size: 22px;
        color: #ff8800;
        opacity: 0.96;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .fav-float-btn:hover {
        box-shadow: 0 6px 24px #ff880044;
        opacity: 1;
        transform: translateY(-2px) scale(1.08);
    }
    @media (max-width:600px){
        .fav-float-btn { right: 12px; bottom: 74px; width: 38px; height: 38px; font-size: 18px; }
    }
    </style>
</body>
</html>