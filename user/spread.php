
  
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>邀请好友</title>
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
<style>
.invite{
    overflow-x: hidden;
    background-color: #fe6342;
    padding-bottom: 80px;
}
.banner{
    position: relative;
}
.bannerBg{
    width: 100%;
    height: 300px;
}
.myInCome{
    width: 90%;
    height: 165px;
    margin: 20px auto 0;
    background-color: #fff;
    border-radius: 8px;
    box-sizing: border-box;
}
.invite .titleBox{
    position: relative;
    text-align: center;
    font-family: PingFang SC-Medium,PingFang SC;
    font-weight: 500;
    color: #fff;
    font-size: 15px;
}
.invite-tips{
    width: 100%;
    height: 150px;
    background: hsla(0,0%,100%,.96);
    border-radius: 11px;
    border: 1px solid #fff;
    -webkit-backdrop-filter: blur(12px);
    backdrop-filter: blur(12px);
    margin-bottom: 11px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.item{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 7px;
    position: relative;
}
.text{
    font-size: 13px;
    width: 90px;
    white-space: pre-wrap;
    text-align: center;
}
.desc{
    color: #bbb;
    font-size: 15px;
    line-height: 22px;
    font-weight: 400;
    text-align: center;
    margin: 15px 0 0px;
}
.my-cellc {
    width: 100%;
    background: #fe6342;
    margin-bottom: 10px;
    border-radius: .75rem;
    padding: 10px;
}
</style>

<?php
$is_defend=true;
include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if($islogin2==1 && $userrow['power']>0){
}
?>
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block " style="float: none; background-color:#fff;padding:0;max-width: 550px;">
    <div class="block  block-all">
        <div class="block-white">
            <div class="block-back display-row align-center justify-between">
                <div style="border-width: .5px;
    border-radius: 100px;
    border-color: #dadbde;
    background-color: #f2f2f2;
    padding: 3px 7px;
    opacity: .8;align-items: center;justify-content: space-between;display: flex; flex-direction: row;height: 30px;">
                <a href="javascript:history.back()"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">邀请好友</a></font>

            </div>
                </div>

<img src="../assets/img/bannerBg.png" style="width: 100%;height: 100%;">
<div class="invite">
    <div class="banner">
        <div class="bannerBg">

        <div class="my-cellc" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <a href="tuiguang.php" style="color: #fff;"><div class="text-center" style="padding: 2px 5px;background: #FEB794;border-radius:10px;box-shadow: inset 0 0 0.9375rem 0.3125rem rgba(255,255,255,.86);margin:10px 17%">
                <div type="button" class="btn btn-block" style="width: 100%;display: inline-block;border-radius: 5px;padding: 10px 0;background: linear-gradient(263deg,#FFA17E 0%,#FF554B 100%);color:#fff;box-shadow: inset 0 0 0.19375rem 0.09125rem #fa6d45;" >
                    <span style="<?php if(checkmobile()){ ?>font-size:14px;<?php }else{ ?>font-size:14px;<?php }?>">立即获取海报二维码</span></a>
                </div>
            </div>
        </div>

<div class="myInCome">
    <div class="titleBox">
                    <div align="center">
                        <div align="center" style="padding: 8px 10px;background: #ffa625;border-radius: 0px;width: 50%;font-size: 14px">- 邀请攻略 -
                    </div>
                </div>
            </div>
            <div class="invite-tips">
            <div class="item">
                <div style="background-image: url(../assets/store/svg/icon_fenxiang.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;">
            <img src="../assets/store/svg/icon_fenxiang.svg" style="width: 100px; height: 50px;"></div>
                <div  class="text">发送海报二维码给新用户</div>
            </div>
            
            <a style="color: #ff7687;">>>></a>
            <div class="item">
                <div style="background-image: url(../assets/store/svg/icon_erweima.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;">
            <img src="../assets/store/svg/icon_erweima.svg" style="width: 100px; height: 50px;"></div>
                <div  class="text">新用户注册账号并下单</div>
            </div>
            
            <a style="color: #ff7687;">>>></a>
            <div class="item">
                <div style="background-image: url(../assets/store/svg/icon_yongjin.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;">
            <img src="../assets/store/svg/icon_yongjin.svg" style="width: 100px; height: 50px;"></div>
                <div  class="text">下单成功后佣金到账</div>
            </div>
            
        </div>
    </div>


</div>
</div>
</div>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
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