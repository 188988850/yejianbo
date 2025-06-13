
  
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>推广信息中转站</title>
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
 * 推广信息中转站
**/
include("../includes/common.php");
$title='推广信息中转站';

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
            <font><a href="">推广信息中转站（付费群列表）</a></font>

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
.charge-btn1 {
    width: 94px;
    padding: 5px;
    font-size: 14px;
    color: #fff;
    text-align: center;
    margin-left: auto;
    background-image: linear-gradient(121deg,#d6d6d6,#868686);
    border-radius: 15px;
}
.content-item-bottom {
    padding: 16px;
}
</style>
<?php
// 生成随机数
$Num1 = rand(); 
//输出
print_r(""); 
//在一个范围内生成随机数
$Num2 = rand(1,4); 
//输出
?>
<div class="main-content">

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>中转站说明</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span>当前共4组链接</span></a>
                    <div class="charge-btn"><a href="" style="color: #fff;">刷新</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">点下面查看即可进入获取推广链接和推广二维码<br>
                        若发现二维码在微信报红，可刷新后再次点下面查看，将随机重新获取新的一组链接（有概率会随机到同一组链接，重新刷新即可）</font>
                </div>
                </div>
            </div>
        </div>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/chaxun.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>推广信息获取</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="bsljqllie0<?php echo $Num2?>.php" style="color: #fff;">查看</a></div></div>
                    <div class="content-item-bottom">
                    <a href="bsljqllie0<?php echo $Num2?>.php"><img src="../assets/img/paging/tuiguang0<?php echo $Num2?>.jpg" style="width: 100%; height: 145px;"></a>
                </div>
            </div>
        </div>


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