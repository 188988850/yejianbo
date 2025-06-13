<?php
if (!defined('IN_CRONLITE')) die();
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./user/login.php';</script>");
$get = $_GET;
if(isset($get['id']) && !empty($get['id'])){
    $id = $get['id'];
    $videolist=$DB->getRow("SELECT * FROM `shua_videolist` WHERE id='$id' LIMIT 1");
    $price_obj = new \lib\Price($userrow['zid'],$userrow);
    $price_obj->setVideoInfo($id,$videolist);
    $price = $price_obj->getVideoPrice($id);
    $bfprice = $price_obj->getbfVideoPrice($id);
    $pay_url_log=$DB->getRow("SELECT * FROM `shua_orders` WHERE vid=".$id." AND input2='购买短剧地址' AND userid=".$userrow['zid']." AND status=1 LIMIT 1");
    
    $pay_play_log=$DB->getRow("SELECT * FROM `shua_orders` WHERE vid=".$id." AND input2='购买短剧播放' AND input3='all' AND userid=".$userrow['zid']." AND status=1 LIMIT 1");
    
    $pid = $videolist['id'];
    $video = $DB->getRow("SELECT * FROM `shua_video` WHERE pid='$pid' LIMIT 1");
    

}else{
    echo '参数错误！';
    exit;
}

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
    color: #000;  
    text-decoration: none;  
    font-weight: bold;  
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
        <a href="JavaScript:history.back();" class="back-to-theater">< 返回</a>  
    </div>  
    <div class="content-wrapper">  
        <div class="content">  
            <div class="drama-info">  
                <h3><?php echo $videolist['name'];?></h3>  
                <p><?php echo $videolist['desc'];?></p>  
            </div>  
            <div class="action-buttons">  
                <?php if(!empty($videolist['download_url']) && empty($pay_url_log)){?>
                    <button onclick="showOrderModal('url')">购买网盘下载地址</button> 
                <?php }?>
                
                <?php if(!empty($video) && empty($pay_play_log)){?>
                    <button onclick="showOrderModal('play')">购买全集播放权限</button> 
                <?php }?>
            </div>  
            <div class="episode-display">  
            <?php 
                    $query = "SELECT * FROM `shua_video` WHERE pid='$id' ORDER BY `num` ASC";
                    $result = $DB->query($query);
                    while ($row = $result->fetch()) {
                        echo '<div class="episode-item"> 
                              <a href="?mod=play&id='.$row['id'].'"> 
                              <img src="'.$videolist['img'].'" alt="第'.$row['num'].'集" >  
                              <p class="episode-number">第'.$row['num'].'集</p>  
                              </a>
                              </div>'; 
                    }
            ?>
                
            </div>  
        </div>  
    </div>
</div>    
<script src="assets/store/js/main.js?ver=<?php echo VERSION ?>"></script>
<script>  
function showOrderModal(type) {  
    var name = "<?php echo $videolist['name']?>"
    if(type=='play'){
        var goods_name = '购买' + name + '全集播放权限'; 
        var price = "<?php echo $bfprice?>";
    }else if(type=='url'){
        var goods_name = '购买' + name + '网盘下载地址'; 
        var price = "<?php echo $price?>";
    }
    
    // 创建一个模态对话框的HTML结构  
    var modalHTML = `  
        <div id="orderModal" class="modal">  
            <div class="modal-content">  
                <h3 class="modal-header">下单信息</h3>  
                <div class="modal-body">
                    <p>商品信息: <span>` + goods_name + `</span></p>  
                    <p>商品价格: ￥<span style="color:red" id="productPrice">` + price + `</span></p>  
                    <button class="buy-btn" onclick="confirmPurchase('` + type + `')">立即购买</button>  
                </div>  
            </div>  
        </div>  
    `;  
  
    // 将模态对话框添加到body中  
    document.body.innerHTML += modalHTML;  
  
    var modal = document.getElementById("orderModal");  
    modal.style.display = "block";  
  
}  
  
function confirmPurchase(type) {  
            var vid = <?php echo $id;?>;
    		$.ajax({
			type : "POST",
			url : "ajax.php?act=payvideo",
			data : {
			    vid:vid,
			    goods_type:type
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
					window.location.href='?buyok=1';
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
// 		});
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
</body>  
</html>