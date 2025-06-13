<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>API对接</title>
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
<?php
$is_defend=true;
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$title = 'API对接';

?>

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
            <font><a href="">API对接</a></font>

            </div>
        </div>
    </div>

<div class="main-content1">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/api.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>对接信息</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#980000">请使用平台提供的站点域名对接！<br>本站没有克隆文件，不要找我要！<br><br></font>
                    <div class="li-list-item">
                        <div class="item-title" style="width:50%">对接域名①：</div>
                        <div class="item-info">
                            <p><?php echo $conf['api01'] ?>
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="<?php echo $conf['api01'] ?>">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                            </p>
                        </div>
                    </div>
                    <div class="li-list-item">
                        <div class="item-title" style="width:50%">对接域名②：</div>
                        <div class="item-info">
                            <p><?php echo $conf['api02'] ?>
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="<?php echo $conf['api02'] ?>">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                            </p>
                        </div>
                    </div>
                    <div class="li-list-item">
                        <div class="item-title" style="width:50%">本站系统：</div>
                        <div class="item-info"><p>彩虹</p></div>
                    </div>
                    <div class="li-list-item">
                        <div class="item-title" style="width:50%">对接密钥：</div>
                        <div class="item-info"><p>你当然登录的账号及密码</p></div>
                    </div>
                    <div class="li-list-item">
                        <div class="item-title" style="width:50%;color: #ff0000;">0元对接知识课程：</div>
                        <div class="item-info" href="./djzskc.php"><p><a href="./djzskc.php" style="color: #2894FF;">立即查看</a></p></div>
                    </div>
                </div>
                <div style="padding: 9px 0;font-size: 1.1rem;color: #858585;">
                    *上方对接地址均为 <font color="red">http</font> 开头！<br>
                    *如遇到无法对接情况请及时更换分站域名，或者【提交工单】获取帮助。
                </div>
                    <div style="width:100%;  display:flex;" >
                <a  style="width: 100%;" href="https://yepyzl6rxo.k.topthink.com/@9421m4l2gb/guanyuAPIduijiejiaocheng.html" target="_blank">
                    <div class="submit_btn" style="width: 90%;height: 3.7rem;margin:5px auto;text-align: center;line-height: 3.7rem;background-image:linear-gradient(to right, rgb(255, 153, 0), rgb(245, 207, 150));">API对接图片教程</div>
                </a>
                <a  style="width: 100%;" href="/?mod=doc" target="_blank">
                    <div class="submit_btn" style="width: 90%;height: 3.7rem;margin:5px auto;text-align: center;line-height: 3.7rem;background-image:linear-gradient(to right, rgb(107, 134, 221), rgb(221, 188, 243));">API对接接口文档</div>
                </a>
                </div>
                <a  style="width: 100%;" href="../user/workorder.php" target="_blank">
                    <div class="submit_btn" style="width: 95%;height: 3.7rem;margin:24px auto;text-align: center;line-height: 3.7rem;background-image:linear-gradient(to right, rgb(0, 224, 255), rgb(13, 95, 255));">【遇到问题？提交工单】</div>
                </a>
            </div>
        </div>
    </div>
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/jiaocheng.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>对接教程</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 5px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#980000">彩虹对接教程：<br></font>
                    <font color="#000"><p>在【彩虹管理后台】->【对接设置】->【对接站点管理】->【添加一个对接站点】</p>
                    <p>对接站点类型选择【同系统对接】</p>
                    <p>根据本站对接要求选择<font color="red">https</font>还是<font color="red">http</font>，然后在后面输入本站的对接地址即可！</p>
                    <p>找到【对接设置】->【批量对接商品】->【选择需要对接的站点】->【选择分类后全选对接】即可</p>
                    <p>记得在本站<font color="red">充值适当余额</font>，否则会导致用户下单后不出卡密！</p></font><br>
                    <font color="#980000">其他系统：<br></font>
                    <font color="#000"><p>记得在本站<font color="red">充值适当余额</font>，否则会导致用户下单后不出卡密！</p></font>
                </div>
                </div>
            </div>
        </div>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
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