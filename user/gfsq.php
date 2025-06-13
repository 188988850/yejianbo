
  
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>官方社群</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
  <link rel="stylesheet" href="../assets/store/css/content.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
   <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<style>
    
    .layui-layer-title {
        padding: 0 80px 0 20px;
        height: 42px;
        line-height: 42px;
        border-bottom: 0px solid #fff1dc;
        font-size: 14px;
        color: #333;
        overflow: hidden;
        background-color: #fff1dc;
        border-radius: 2px !important;
    }
    .layui-layer-btn .layui-layer-btn0 {
            border-color: #fff1dc;
        background-color: #fff1dc;
        color: #333;
        font-size: 13px;
        border-radius: 10px !important;
    }
.order-hd{
    justify-content: space-between;
    padding: 16px 20px 5px;
    margin-top: 1px;
    font-size: 16px;
    color: #282828;
}
</style>

<body>
<?php
/**
 * 官方社群
**/
include("../includes/common.php");
$title='官方社群';

if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<style type="text/css">
<!--
.STYLE3 {font-size: 14px}
-->
</style>

<div class="wrapper">
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block " style="float: none; background-color:#fff;padding:0;max-width: 550px;">
    <div class="block  block-all">
        <div class="block-white">
            <div class="block-back display-row align-center justify-between" style="position: fixed;background-color: #fff;max-width: 550px;left: 50%;transform: translateX(-50%);z-index: 1;">
                <div style="border-width: .5px;
    border-radius: 100px;
    border-color: #dadbde;
    background-color: #f2f2f2;
    padding: 3px 7px;
    opacity: .8;align-items: center;justify-content: space-between;display: flex; flex-direction: row;height: 30px;">
                <a href="./"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">官方社群</a></font>

            </div>
        </div>
    </div>
 <div style="padding-top: 60px;"></div>
<?php  if($userrow['power']==0 || $userrow['power']==1){?>
<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/gfsq.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>官方社群说明</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="upgrade.php" style="color: #fff;">前往升级</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#999999">官方社群仅对顶级合伙人开放<br>
                    您当前权限不足请先升级<br>
                    升级后方可查看并加入社群<br></font>

                </div>
            </div>
        </div>
<?php } ?>

<?php  if( $userrow['power']==2){?>
<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/gfsq.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>官方社群说明</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#999999"><?php echo $conf['gfsq_wzsm']; ?></font>

                </div>
            </div>
        </div>
</div>
<?php if($conf['gfsq_qqmc01_car']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/guanfang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 14px; margin-right: 8px;color: #ff0000;"><span><?php echo $conf['gfsq_qqmc01']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['gfsq_qqqlj01']; ?>" target="_blank"style="color: #fff;">立即加入</a></div></div>
                    <div class="content-item-bottom">
                    <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000">QQ群号：<?php echo $conf['gfsq_qqq01']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
                
<?php if($conf['gfsq_qqmc02_car']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/guanfang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 14px; margin-right: 8px;"><span><?php echo $conf['gfsq_qqmc02']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['gfsq_qqqlj02']; ?>" target="_blank"style="color: #fff;">立即加入</a></div></div>
                    <div class="content-item-bottom">
                    <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#999999">QQ群号：<?php echo $conf['gfsq_qqq02']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($conf['gfsq_qqq03_car']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/guanfang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 14px; margin-right: 8px;"><span>QQ交流群</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['gfsq_qqq03']; ?>" target="_blank"style="color: #fff;">立即加入</a></div></div>
                    <div class="content-item-bottom">
                    <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#999999">QQ群号：<?php echo $conf['gfsq_qqq03']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($conf['gfsq_qqq03_car']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/guanfang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 14px; margin-right: 8px;"><span></span>微信交流群</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['gfsq_qqq04']; ?>" target="_blank"style="color: #fff;">立即加入</a></div></div>
                    <div class="content-item-bottom">
                    <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#999999"><?php echo $conf['gfsq_qqq04']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($conf['gfsq_kfqq01_car']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/guanfang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 14px; margin-right: 8px;"><span></span> QQ客服①</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn1"><a href="javascript:;" id="copy-btn" data-clipboard-text="<?php echo $conf['gfsq_kfqq01']; ?>" style="color: #fff;">立即复制</a></div></div>
                    <div class="content-item-bottom">
                    <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#999999">QQ号：<?php echo $conf['gfsq_kfqq01']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($conf['gfsq_kfqq02_car']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/guanfang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 14px; margin-right: 8px;"><span></span>QQ客服②</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn1"><a href="javascript:;" id="copy-btn" data-clipboard-text="<?php echo $conf['gfsq_kfqq02']; ?>" style="color: #fff;">立即复制</a></div></div>
                    <div class="content-item-bottom">
                    <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#999999">QQ号：<?php echo $conf['gfsq_kfqq02']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php }?>
            </div>
        </div>


<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
    document.documentElement.addEventListener('touchstart', function (event) {
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    }, {
        passive: false
    });

    // 禁用双击放大
    var lastTouchEnd = 0;
    document.documentElement.addEventListener('touchend', function (event) {
        var now = Date.now();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, {
        passive: false
    });
$(document).ready(function(){
var clipboard = new Clipboard('#copy-btn');
clipboard.on('success', function (e) {
	layer.msg('复制成功');
});
clipboard.on('error', function (e) {
	layer.msg('复制失败，请长按链接后手动复制');
});

$("#recreate_url").click(function(){
	var self = $(this);
	if (self.attr("data-lock") === "true") return;
	else self.attr("data-lock", "true");
	var ii = layer.load(1, {shade: [0.1, '#fff']});
	$.get("ajax_user.php?act=create_url&force=1", function(data) {
		layer.close(ii);
		if(data.code == 0){
			layer.msg('生成链接成功');
			$("#copy-btn").html(data.url);
			$("#copy-btn").attr('data-clipboard-text',data.url);
		}else{
			layer.alert(data.msg);
		}
		self.attr("data-lock", "false");
	}, 'json');
});
});
</script>
  <script>
function fuckyou(){
window.close(); 
window.location="about:blank"; 
}
function click(e) {
if (document.all) {
  if (event.button==2||event.button==3) { 
alert("欢迎光临寒舍，有什么需要帮忙的话，请与站长联系！谢谢您的合作！！！");
oncontextmenu='return false';
}
}
if (document.layers) {
if (e.which == 3) {
oncontextmenu='return false';
}
}
}
if (document.layers) {
fuckyou();
document.captureEvents(Event.MOUSEDOWN);
}
document.onmousedown=click;
document.oncontextmenu = new Function("return false;")
document.onkeydown =document.onkeyup = document.onkeypress=function(){ 
if(window.event.keyCode == 123) { 
fuckyou();
window.event.returnValue=false;
return(false); 
} 
}
</script>
</body>
</html>