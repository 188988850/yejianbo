<?php
session_start();
require_once('../config.php');
require_once dirname(__DIR__).'/includes/common.php';
$id = intval($_GET['id']);
$resource = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_goods WHERE id={$id}")->fetch();
if(!$resource) exit('资源不存在！');

// 获取当前登录用户
$user = null;
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_users WHERE id='{$user_id}'")->fetch();
}

// 判断是否登录
$is_bought = false;
if($islogin==1 && $user_id) {
    // 检查是否已购买（假设有orders表，user_id/resource_id）
    $order = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_orders WHERE user_id='{$user_id}' AND resource_id='{$id}' LIMIT 1")->fetch();
    if($order) $is_bought = true;
}

// 输出详情
?>
<h5><?php echo htmlspecialchars($resource['name']); ?></h5>
<div>分类：<?php echo htmlspecialchars($resource['class']); ?></div>
<div>价格：￥<?php echo htmlspecialchars($resource['price']); ?></div>
<div>详情：<?php echo nl2br(htmlspecialchars($resource['content'])); ?></div>
<?php if($is_bought): ?>
    <div style="color:green;">隐藏内容：<?php echo nl2br(htmlspecialchars($resource['hidden_content'])); ?></div>
    <?php if($resource['doc']): ?><div><a href="<?php echo $resource['doc']; ?>" target="_blank">下载文件</a></div><?php endif; ?>
<?php else: ?>
    <div style="color:#888;">购买后可见隐藏内容和下载文件</div>
    <button class="buy-btn" onclick="buyNow()">立即购买</button>
    <script>
    function buyNow() {
        var resourceId = <?php echo json_encode($id); ?>;
        var price = <?php echo json_encode($resource['price']); ?>;
        if(!resourceId || !price){
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
            tid: 'goods_' + resourceId, // 以goods_前缀区分资源
            num: 1,
            inputvalue: '资源',
            goods_id: resourceId
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
<?php endif; ?> 