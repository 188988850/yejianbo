<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>项目投稿</title>
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
<body>    <link rel="stylesheet" href="../assets/user/css/work.css">
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
    </style>
<?php
/**
 * 
**/
$is_defend=true;
include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if($islogin2==1 && $userrow['power']>0){
}
?>
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
            <font><a href="">项目投稿</a></font>

            </div>
                </div>
<style>
.main-content {
    width: 100%;
    height: 100%;
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
.content-item1 {
    width: 90%;
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
.content-item-bottom {
    padding: 16px;
}
</style>
<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>投稿说明</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <?php  if($userrow['power']==0 || $userrow['power']==1){?>
                    <div class="charge-btn"><a href="javascript:layer.alert('您当前等级不够无法进行投稿，请先升级到顶级合伙人再来！', function() {$('#switch1').attr('checked','checked');layer.closeAll();})" style="color: #fff;">前往投稿</a></div></div>
                    <?php }?>
                    <?php  if( $userrow['power']==2){?>
                    <div class="charge-btn"><a href="contributes.php?my=add" style="color: #fff;">前往投稿</a></div></div>
                    <?php }?>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#868686" style="font-size: 12px;line-height:2rem">
                        1.投稿功能只能用作网创脚本卡密类业务，其它课程业务不接受投稿，网盘里需要有详细操作教程视频/详细介绍软件功能及使用方法以及脚本软件和卡密(数量100~500不等)，录制视频需内容清晰明了，留下能加的微信联系方式，若该业务通过，客服会加你微信对您进行后续联系<br>
                        2.投稿分成佣金1.5元，每卖出一份即可获得一次分成佣金<br>
                        3.投稿项目审核时间72小时内，请勿进行催促<br>
                        4.若在网盘或私下发现此项目进行收取用户任何费用以及诈骗虚假宣传，情况属实的平台有权取消合伙人身份并进行封号处理<br>
                        5.最终解释权归平台所有
                        
                    <br><br>
                    <a style="color: #868686;font-size: 12px;">若发现投稿的项目被删除，说明此项目未通过审核<br>
                    未通过原因：<br>
                    1.不是脚本类业务<br>
                    2.平台已存在多个这种类型项目<br>
                    3.出现需要付费<br>
                    4.出现二次引流<br>
                    5.跟平台其它业务冲突
                    </a>
                    </font>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/tougao.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>已投项目</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"></a>
                    </div>
                <div class="flowlist"><div id="list"></div></div>
        </div>
    </div>
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
            </div>
        </div>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script charset="utf-8" src="../assets/user/js/work.js"></script>
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
        var type = 'contribute';
        tap_tab(type);
        openmsg();
    })
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
