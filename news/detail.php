<?php
session_start();
include '../includes/common.php';

$id = intval($_GET['id']);
if(!$id) {
    header('Location: index.php');
    exit;
}

// 获取资讯信息
$news = $DB->getRow("SELECT * FROM shua_news WHERE id='$id' AND status=1 LIMIT 1");
if(!$news) {
    echo '<script>alert("资讯不存在或已下架");history.back();</script>';
    exit;
}

// 获取用户信息
$user = null;
$is_finance_vip = false;
$has_bought = false;

if(isset($_SESSION['zid'])){
    $user = $DB->getRow("SELECT * FROM shua_site WHERE zid='{$_SESSION['zid']}' LIMIT 1");
    if($user){
        // 检查金融会员状态
        $is_finance_vip = ($user['finance_vip_level'] > 0 && 
                          ($user['finance_vip_expire'] >= date('Y-m-d') || 
                           $user['finance_vip_expire_type'] == 'forever'));
        
        // 检查是否已购买该资讯
        $check_order = $DB->getRow("SELECT * FROM shua_finance_order WHERE zid='{$user['zid']}' AND news_id='$id' AND status=1 LIMIT 1");
        $has_bought = ($check_order ? true : false);
    }
}

$price = floatval($news['price']);
$can_view_full = ($price == 0 || $has_bought || $is_finance_vip);

// 增加阅读次数
$DB->exec("UPDATE shua_news SET read_count = read_count + 1 WHERE id = '$id'");

$title = $news['title'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - 金融资讯</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0"><?php echo htmlspecialchars($news['title']); ?></h1>
                        <div class="text-muted mt-2">
                            <small>
                                <i class="fas fa-eye"></i> 阅读 <?php echo $news['read_count']; ?> 次
                                <span class="mx-2">|</span>
                                <i class="fas fa-clock"></i> <?php echo $news['add_time']; ?>
                                <?php if($price > 0): ?>
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-tag"></i> ¥<?php echo number_format($price, 2); ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if($news['cover_url']): ?>
                            <img src="<?php echo htmlspecialchars($news['cover_url']); ?>" class="img-fluid mb-3" alt="封面图">
                        <?php endif; ?>
                        
                        <?php if($news['desc']): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($news['desc']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- 公开内容 -->
                        <?php if($news['public_content']): ?>
                            <div class="content-section">
                                <?php echo $news['public_content']; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- VIP/付费内容 -->
                        <?php if($news['vip_content'] && $price > 0): ?>
                            <?php if($can_view_full): ?>
                                <div class="content-section mt-4 p-3 border-start border-primary border-3" style="background-color: #f8f9fa;">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-crown"></i> 
                                        <?php if($is_finance_vip): ?>
                                            VIP专享内容
                                        <?php else: ?>
                                            付费内容
                                        <?php endif; ?>
                                    </h5>
                                    <?php echo $news['vip_content']; ?>
                                </div>
                            <?php else: ?>
                                <div class="content-section mt-4 p-4 text-center border rounded" style="background-color: #f8f9fa;">
                                    <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                                    <h5>此部分为付费内容</h5>
                                    <p class="text-muted">购买后即可查看完整内容</p>
                                    <div class="mt-3">
                                        <?php if($user): ?>
                                            <button type="button" class="btn btn-primary" onclick="buyNow()">
                                                <i class="fas fa-shopping-cart"></i> 购买查看 (¥<?php echo number_format($price, 2); ?>)
                                            </button>
                                            <div class="text-muted mt-2">
                                                <small>当前余额：¥<?php echo number_format($user['rmb'], 2); ?></small>
                                            </div>
                                        <?php else: ?>
                                            <a href="/user/login.php?return_url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> 登录后购买
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- 完整内容（免费资讯或已购买/VIP用户） -->
                        <?php if($can_view_full && $news['content'] && $price == 0): ?>
                            <div class="content-section mt-4">
                                <?php echo $news['content']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php if($is_finance_vip): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-crown"></i> 金融VIP会员
                                    </span>
                                <?php endif; ?>
                                <?php if($has_bought): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> 已购买
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> 返回列表
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
        
        // 使用主系统的下单接口
        $.post('/ajax.php?act=pay', {
            tid: 'finance_' + newsId,
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