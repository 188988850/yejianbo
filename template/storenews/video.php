<?php
if (!defined('IN_CRONLITE')) die();
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./user/login.php';</script>");
$get = $_GET;
if(isset($get['id']) && !empty($get['id'])){
    $id = $get['id'];
    $videolist=$DB->getRow("SELECT * FROM `shua_videolist` WHERE id='$id' LIMIT 1");
    $price_obj = new \lib\Price($userrow['zid'],$userrow);
    $price_obj->setVideoInfo($id,$videolist);
    // 价格分级逻辑
    $level = isset($userrow['power']) ? intval($userrow['power']) : 0;
    // 网盘价【网盘价-基础/普及/专业】
    $cloudprice = $videolist['price']; //【网盘价-基础】
    if($level == 2 && $videolist['price3']>0) $cloudprice = $videolist['price3']; //【网盘价-专业】
    elseif($level == 1 && $videolist['price2']>0) $cloudprice = $videolist['price2']; //【网盘价-普及】
    // 全集价【全集价-基础/普及/专业】
    $bfprice = $videolist['bfprice']; //【全集价-基础】
    if($level == 2 && $videolist['bfprice3']>0) $bfprice = $videolist['bfprice3']; //【全集价-专业】
    elseif($level == 1 && $videolist['bfprice2']>0) $bfprice = $videolist['bfprice2']; //【全集价-普及】
    // 全集播放权限
    $pay_play_log = $DB->getRow("SELECT 1 FROM shua_orders WHERE tid=-4 AND userid='$user_zid' AND status=1 AND vid='$vid' AND input2='购买短剧播放' AND input3='all' LIMIT 1");
    if (!$pay_play_log) {
        $like1 = $vid.'|play|all';
        $pay_play_log = $DB->getRow("SELECT 1 FROM shua_pay WHERE tid=-4 AND userid='$user_zid' AND status=1 AND vid='$vid' AND input LIKE '%$like1%' LIMIT 1");
    }
    // 网盘权限
    $pay_url_log = $DB->getRow("SELECT 1 FROM shua_orders WHERE tid=-4 AND userid='$user_zid' AND status=1 AND vid='$vid' AND input2='购买短剧地址' LIMIT 1");
    if (!$pay_url_log) {
        $like2 = $vid.'|url|all';
        $pay_url_log = $DB->getRow("SELECT 1 FROM shua_pay WHERE tid=-4 AND userid='$user_zid' AND status=1 AND vid='$vid' AND input LIKE '%$like2%' LIMIT 1");
    }
    $video = $DB->getRow("SELECT * FROM `shua_video` WHERE id='$id' LIMIT 1");
    $pid = $video['pid'];
}else{
    echo '参数错误！';
    exit;
}

// ====== 调试输出，便于排查分级价格问题 ======
echo "<!-- debug: 当前身份power=" . (isset(
    $userrow['power']) ? $userrow['power'] : '未定义') .
    " price2=" . (isset($videolist['price2']) ? $videolist['price2'] : '未定义') .
    " price3=" . (isset($videolist['price3']) ? $videolist['price3'] : '未定义') .
    " bfprice2=" . (isset($videolist['bfprice2']) ? $videolist['bfprice2'] : '未定义') .
    " bfprice3=" . (isset($videolist['bfprice3']) ? $videolist['bfprice3'] : '未定义') .
    " -->\n";

?>
<!DOCTYPE html>  
<html lang="zh">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>短剧详情页</title>  
    <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
    <style>
body, html {  
    margin: 0;  
    padding: 0;  
    font-family: Arial, sans-serif;  
    color: #333;  
    overflow-x: hidden;  
}  
  
.header {  
    position: fixed;  
    top: 0;  
    margin: 0 auto;
    background-color: rgba(255, 255, 255, 0.8); 
    display: flex;  
    justify-content: flex-start;  
    align-items: center;  
    padding: 10px;  
    z-index: 1000;  
}  
  
.back-to-theater {  
    display: inline-block;
    padding: 8px 18px;
    background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
    color: #fff !important;
    border-radius: 25px;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(0,123,255,0.15);
    border: none;
    transition: background 0.3s, color 0.3s;
    margin-right: 10px;
}
.back-to-theater:hover {
    background: linear-gradient(90deg, #0056b3 0%, #007bff 100%);
    color: #fff !important;
    text-decoration: none;
}  
  
.content-wrapper {  
    position: relative;  
    min-height: 100vh; /* 视口高度 */  
    background-size: cover;  
    background-position: center;  
    padding-top: 60px; /* 根据header的高度调整 */  
}  
  
.content {  
    max-width: 1200px;  
    margin: 0 auto;  
    padding: 20px;  
    background-color: rgba(255, 255, 255, 0.9); /* 半透明白色背景 */  
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);  
    border-radius: 8px;  
}  
  
.drama-info h1 {  
    color: #000;  
}  
  
.action-buttons {  
    display: flex;  
    flex-direction: column;  
    gap: 10px; /* 按钮间距 */  
    margin-top: 20px;  
}  
  
.action-buttons button {  
    padding: 10px 20px;  
    font-size: 16px;  
    cursor: pointer;  
    border: none;  
    border-radius: 5px;  
    background-color: #007BFF;  
    color: white;  
}  
  
.episode-display {  
    display: flex;  
    flex-wrap: wrap;  
    gap: 15px; /* 剧集间距 */  
    margin-top: 20px;  
}  
  
.episode-item {  
    width: calc(33.333% - 10px); /* 减去间距 */  
    position: relative;  
    overflow: hidden;  
}  
  
.episode-item img {  
    width: 100%;  
    height: auto;  
    display: block; /* 去除图片底部间隙 */  
}  
  
.episode-number {  
    position: absolute;  
    bottom: 0;  
    left: 0;  
    width: 100%;  
    background-color: rgba(0, 0, 0, 0.5);  
    color: white;  
    text-align: center;  
    padding: 5px 0;  
    margin: 0;  
    font-size: 14px;  
}
/* 模态对话框的样式 */  
.modal {  
    display: none; /* 默认隐藏 */  
    position: fixed; /* 固定定位 */  
    z-index: 1; /* 置于顶层 */  
    left: 0;  
    width: 100%; /* 宽度占满屏幕 */  
    height: 100%; /* 高度占满屏幕 */  
    overflow: auto; /* 如果内容超出则显示滚动条 */  
    background-color: rgb(0,0,0); /* 背景颜色 */  
    background-color: rgba(0,0,0,0.4); /* 黑色背景，带透明度 */  
}  
  
.modal-content {  
    background-color: #fefefe;  
    margin: 15% auto; /* 15% 顶部距离，水平居中 */  
    padding: 20px;  
    border: 1px solid #888;  
    width: 80%; /* 宽度 */  
    max-width: 700px; /* 最大宽度 */  
}  
  
.modal-header {  
    text-align: center;  
    font-size: 24px;  
}  
  
.modal-body {  
    text-align: center;  
    padding: 10px;  
} 

.buy-btn {  
    /* 基础样式 */  
    display: inline-block; /* 允许按钮和其他元素在同一行显示 */  
    padding: 10px 20px; /* 内边距，影响按钮的大小 */  
    font-size: 16px; /* 字体大小 */  
    color: #fff; /* 字体颜色 */  
    background-color: #007bff; /* 背景颜色，这里使用了蓝色，常用于按钮 */  
    border: none; /* 移除边框 */  
    border-radius: 5px; /* 边框圆角 */  
    cursor: pointer; /* 鼠标悬停时显示手指形状，表明这是一个可点击的元素 */  
      
    /* 过渡效果，让按钮在被点击时有一点动画效果 */  
    transition: background-color 0.3s ease;  
      
}
.buy-btn:hover {  
    background-color: #0056b3;  
}  
  
.buy-btn:disabled {  
    background-color: #ccc;  
    color: #666;  
    cursor: not-allowed;  
}
.container {  
    max-width: 600px; 
    margin: 0 auto; /* 在PC端居中 */  
}  
</style>  
</style>
</head>  
<body>  
<div class="container">
    <div class="header">  
        <a href="/?mod=ysduanju" class="back-to-theater">返回首页</a>  
    </div>  
    <div class="content-wrapper">  
        <div class="content">  
            <div class="drama-info">  
                <h3><?php echo $videolist['name'];?></h3>  
                <p><?php echo $videolist['desc'];?></p>  
            </div>  
            <div class="action-buttons">  
                <?php if(!empty($videolist['download_url']) && empty($pay_url_log)){?>
                    <button onclick="showOrderModal('url')">购买网盘下载地址（￥<?php echo $cloudprice;?>）</button> 
                <?php }?>
                
                <?php if(!empty($video) && empty($pay_play_log)){?>
                    <button onclick="confirmPurchase('play','all')">购买全集播放权限（￥<?php echo $price_obj->getbfVideoPrice($id);?>）</button> 
                <?php }?>
            </div>  
            <div class="episode-display">  
            <?php 
                    $query = "SELECT * FROM `shua_video` WHERE pid='$id' ORDER BY `num` ASC";
                    $result = $DB->query($query);
                    while ($row = $result->fetch()) {
                        $price_obj->setdbVideoInfo($row['id'], $row);
                        // 判断是否已购买
                        $is_bought = false;
                        $user_zid = $userrow['zid'];
                        $vid = $videolist['id'];
                        $video_num = $row['num'];
                        // 查shua_orders
                        $is_bought = $DB->getRow("SELECT 1 FROM shua_orders WHERE tid=-4 AND userid='$user_zid' AND status=1 AND vid='$vid' AND ((input2='购买短剧播放' AND (input3='all' OR input3='$video_num')) OR (input2='购买短剧地址')) LIMIT 1");
                        // 查shua_pay
                        if (!$is_bought) {
                            $like1 = $vid.'|play|all';
                            $like2 = $vid.'|play|'.$video_num;
                            $like3 = $vid.'|url|all';
                            $is_bought = $DB->getRow("SELECT 1 FROM shua_pay WHERE tid=-4 AND userid='$user_zid' AND status=1 AND vid='$vid' AND (input LIKE '%$like1%' OR input LIKE '%$like2%' OR input LIKE '%$like3%') LIMIT 1");
                        }
                        $mark = $is_bought ? '<span style="color:green">已买</span>' : '';
                        echo '<div class="episode-item"> <a href="?mod=play&id='.$row['id'].'"> <img src="'.$videolist['img'].'" alt="第'.$row['num'].'集" >  <p class="episode-number">第'.$row['num'].'集 '.$mark.'</p>  </a></div>';
                    }
            ?>
                
            </div>  
        </div>  
    </div>
</div>    
<script src="assets/store/js/main.js?ver=<?php echo VERSION ?>"></script>
<script>  
function showOrderModal(type, episodes) {  
    var name = "<?php echo $videolist['name']?>"
    var price = '';
    var goods_name = '';
    // 修复弹窗BUG：每次点击都弹出最新内容
    var oldModal = document.getElementById('orderModal');
    if(oldModal) oldModal.remove();
    if(type=='play' && episodes=='all'){
        goods_name = '购买' + name + '全集播放权限';
        price = "<?php echo $price_obj->getbfVideoPrice($id)?>";
    }else if(type=='url'){
        goods_name = '购买' + name + '网盘下载地址';
        price = "<?php echo $cloudprice?>";
    }
    // 创建一个模态对话框的HTML结构  
    var modalHTML = `  
        <div id="orderModal" class="modal">  
            <div class="modal-content">  
                <h3 class="modal-header">下单信息</h3>  
                <div class="modal-body">
                    <p>商品信息: <span>` + goods_name + `</span></p>  
                    <p>商品价格: ￥<span style="color:red" id="productPrice">` + price + `</span></p>  
                    <button class="buy-btn" onclick="confirmPurchase('` + type + `','` + (episodes||'all') + `')">立即购买</button>  
                </div>  
            </div>  
        </div>  
    `;  
    document.body.innerHTML += modalHTML;  
    var modal = document.getElementById("orderModal");  
    modal.style.display = "block";  
}  
  
function confirmPurchase(type, episodes) {  
    var vid = <?php echo $id;?>;
    if(type === 'play' && (episodes === undefined || episodes === null)) episodes = 'all';
    $.ajax({
        type : "POST",
        url : "ajax.php?act=payvideo",
        data : {
            vid:vid,
            goods_type:type,
            episodes:episodes
        },
        dataType : 'json',
        success : function(data) {
            if(data.code == 0){
                var paymsg = '';
                if(data.pay_alipay>0){
                    paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'alipay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/alipay.png" style="width:30px" class="logo">支付宝</button>';
                }
                if(data.pay_qqpay>0){
                    paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'qqpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/qqpay.png" style="width:30px" class="logo">QQ钱包</button>';
                }
                if(data.pay_wxpay>0){
                    paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'wxpay\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/wxpay.png" style="width:30px" class="logo">微信支付</button>';
                }
                if (data.pay_rmb>0) {
                    paymsg+='<button class="btn btn-default btn-block" onclick="dopay(\'rmb\',\''+data.trade_no+'\')" style="margin-top:10px;"><img src="assets/img/rmb.png" style="width:30px" class="logo">余额支付<span class="text-muted">（剩'+data.user_rmb+'元）</span></button>';
                }
                if(data.paymsg!=null)paymsg+=data.paymsg;
                layer.alert('<center><h2>￥ '+data.need+'</h2><hr>'+paymsg+'<hr><a class="btn btn-default btn-block" onclick="cancel(\''+data.trade_no+'\')">取消订单</a></center>',{
                    btn:[],
                    title:'提交订单成功',
                    closeBtn: false
                });
            }else if(data.code == 1){
                $('#alert_frame').hide();
                if($('#inputname').html()=='你的邮箱'){
                    $.cookie('email', inputvalue);
                }
                alert('领取成功！');
                console.log('支付成功类型type:', type, typeof type);
                if(typeof type === 'string' && type.trim().toLowerCase() === 'play'){
                    window.location.reload();
                    return;
                }
            }else if(data.code == 2){
                if(data.type == 1){
                    layer.open({
                      type: 1,
                      title: '完成验证',
                      skin: 'layui-layer-rim',
                      area: ['320px', '100px'],
                      content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div><div id="captcha_wait"><div class="loading"><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div></div></div></div>',
                      success: function(){
                        $.getScript("//static.geetest.com/static/tools/gt.js", function() {
                            $.ajax({
                                url: "ajax.php?act=captcha&t=" + (new Date()).getTime(),
                                type: "get",
                                dataType: "json",
                                success: function (data) {
                                    $('#captcha_text').hide();
                                    $('#captcha_wait').show();
                                    initGeetest({
                                        gt: data.gt,
                                        challenge: data.challenge,
                                        new_captcha: data.new_captcha,
                                        product: "popup",
                                        width: "100%",
                                        offline: !data.success
                                    }, handlerEmbed);
                                }
                            });
                        });
                      }
                    });
                }else if(data.type == 2){
                    layer.open({
                      type: 1,
                      title: '完成验证',
                      skin: 'layui-layer-rim',
                      area: ['320px', '260px'],
                      content: '<div id="captcha" style="margin: auto;"><div id="captcha_text">正在加载验证码</div></div>',
                      success: function(){
                        $.getScript("//cdn.dingxiang-inc.com/ctu-group/captcha-ui/index.js", function() {
                            var myCaptcha = _dx.Captcha(document.getElementById('captcha'), {
                                appId: data.appid,
                                type: 'basic',
                                style: 'embed',
                                success: handlerEmbed2
                            })
                            myCaptcha.on('ready', function () {
                                $('#captcha_text').hide();
                            })
                        });
                      }
                    });
                }else if(data.type == 3){
                    layer.open({
                      type: 1,
                      title: '完成验证',
                      skin: 'layui-layer-rim',
                      area: ['320px', '231px'],
                      content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div></div>',
                      success: function(){
                        $.getScript("//v.vaptcha.com/v3.js", function() {
                            vaptcha({
                                vid: data.appid,
                                type: 'embed',
                                container: '#captcha',
                                offline_server: 'https://management.vaptcha.com/api/v3/demo/offline'
                            }).then(handlerEmbed3);
                        });
                      }
                    });
                }
            }else if(data.code == 3){
                layer.alert(data.msg, {
                    closeBtn: false
                }, function(){
                    window.location.reload();
                });
            }else if(data.code == 4){
                var confirmobj = layer.confirm('请登录后再购买，是否现在登录？', {
                  btn: ['登录','注册','取消']
                }, function(){
                    window.location.href='./user/login.php';
                }, function(){
                    window.location.href='./user/reg.php';
                }, function(){
                    layer.close(confirmobj);
                });
            }else{
                layer.alert(data.msg);
            }
        } 
    });
  
    // 关闭模态对话框  
    var modal = document.getElementById("orderModal");  
    modal.style.display = "none";  
}  
  
 
document.addEventListener("DOMContentLoaded", function() {  
    var modals = document.getElementsByClassName("modal");  
    for (var i = 0; i < modals.length; i++) {  
        modals[i].style.display = "none"; // 默认隐藏所有模态对话框  
  
        // 点击模态对话框外部时关闭它  
        modals[i].addEventListener('click', function(e) {  
            if (e.target == this) {  
                this.style.display = "none";  
            }  
        });  
  
        // 防止点击模态对话框内部元素时关闭它  
        var spans = this.getElementsByClassName("modal-content");  
        for (var j = 0; j < spans.length; j++) {  
            spans[j].addEventListener('click', function(e) {  
                e.stopPropagation();  
            });  
        }  
    }  
});  
</script>
<script>
// 拦截 ?buyok=1 跳转
(function(){
    var oldAssign = window.location.assign;
    window.location.assign = function(url){
        if(url && url.indexOf('?buyok=1')>-1){
            return;
        }
        return oldAssign.apply(this, arguments);
    };
    var oldHref = window.location.href;
    Object.defineProperty(window.location, 'href', {
        set: function(url){
            if(url && url.indexOf('?buyok=1')>-1){
                return;
            }
            window.location = url;
        }
    });
})();
</script>
</body>  
</html>