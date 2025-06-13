
  
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>佣金提现</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
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
 * 佣金提现
**/
include("../includes/common.php");
$title='佣金提现';

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
            <font><a href="">佣金提现</a></font>

            </div>
        </div>
    </div>

<style>
.main-content {
    width: 100%;
    height: 90vh;
    background-color: #97c6ff;
}
.content-item {
    <?php if(checkmobile()){ ?>
    width: 90%;
    <?php }else{ ?>
    width: 90%;
    <?php }?>
    background-color: #fff;
    border-radius: 11px;
    padding-top: 11px;
    flex-direction: column;
    display: flex;
}
.content-item-top {
    display: flex;
    height: 16px;
    justify-content: flex-start;
    align-items: center;
    padding: 16px;
}
.content-item .content-item-top .charge-btn {
    width: 94px;
    padding: 5px;
    font-size: 14px;
    color: #fff;
    text-align: center;
    margin-left: auto;
    background-image: linear-gradient(121deg,#ff9445,#ff5b21);
    border-radius: 15px;
}
.charge-btn2 {
    width: 94px;
    padding: 5px;
    font-size: 14px;
    color: #fff;
    text-align: center;
    margin-left: auto;
    background-image: linear-gradient(121deg,#aaaaaa,#868686);
    border-radius: 15px;
}
.content-item-bottom {
    padding: 16px;
}
</style>
<div class="main-content">
    
<?php  if($userrow['power']==1 || $userrow['power']==2){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/jinbi088.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>佣金提现</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span>1~3天到账</span></a>
                    <div class="charge-btn"><a href="tixian.php" style="color: #fff;">去提现</a></div></div>
                    <div class="content-item-bottom">
                    <a href="tixian.php"><img src="../assets/img/yyytt2.png" style="width: 100%; height: 124px;"></a>
                </div>
            </div>
        </div>
<?php }?>

<?php  if( $userrow['power']==0){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/jinbi088.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>佣金提现</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span>无提现资格</span></a>
                    <div class="charge-btn2"><a href="javascript:layer.alert('普通用户无提现资格<br>仅支持推广获得的佣金可进行提现<br>充值的余额不支持，仅用作消费使用')" style="color: #fff;">无权限</a></div></div>
                    <div class="content-item-bottom">
                    <a href="javascript:layer.alert('普通用户无提现资格<br>仅支持推广获得的佣金可进行提现<br>充值的余额不支持，仅用作消费使用')"><img src="../assets/img/yyytt2.png" style="width: 100%; height: 124px;"></a>
                </div>
            </div>
        </div>
<?php }?>

<?php if($conf['ccczye_tixian']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/jinbi088.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>充值提现</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span>详细查看介绍</span></a>
                    <div class="charge-btn"><a href="../?mod=buy2&tid=29758.php" style="color: #fff;" target="_blank">去提现</a></div></div>
                    <div class="content-item-bottom">
                    <a href="../?mod=buy2&tid=29758.php" target="_blank"><img src="../assets/img/yyytt1.png" style="width: 100%; height: 124px;"></a>
                </div>
            </div>
        </div>
<?php }?>

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
	layer.msg('复制成功，请打开浏览器访问，建议收藏！');
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
layer.open({
    type:1,
    title: false,
    area: '33rem',
    shade: 0.5,
    skin: "layerdemo",
    shadeClose: false,
    closeBtn: 0,
    offset: '35%',
    content:
        '<div class="display-column  align-center"  style="position: relative" >'+
        '<div class="form-group-border-bottom text-center" style="height: 4.5rem;line-height: 4.5rem;font-weight: 550;width:100%;"><b>温馨提示</b></div>'+
        '<img onclick="layer.closeAll();"  style="height: 2rem;position: absolute;top:1rem;right:1rem" src="../assets/user/img/close.png">'+
        '<div style="padding:10px 15px;font-size: 1.3rem;color: #ff0000;line-height:2rem;letter-spacing:.1rem;">仅支持推广获得的佣金可进行提现<br>充值的余额不支持，仅用作消费使用</p>'+
        '<div align="center"><a onclick="layer.closeAll();" style="height:4.5rem;width: 100%;line-height: 4.5rem;font-size: 1.6rem;color:#378bd3;font-weight: 510">我知道了</a></div>' +
        '</div>'+
        '</div>',

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