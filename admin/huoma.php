
  
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>推广链接</title>
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
 * 推广链接
**/
include("../includes/common.php");
$title='推广链接';

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
                <a href="./"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">推广链接</a></font>

            </div>
        </div>
    </div>

<div class="main-content1">
   <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/anquan.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>我的域名</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="cdomain.php" style="color: #fff;">去更换</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 5px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#980000">我的域名：<br></font>
                    <font color="#000">如遇网站链接打不开，可进行更换新的后缀域名，多个后缀域名任你选择。</font><br><br>
                    <font color="#980000">当前域名： (点击可访问)<br></font>
                    <font color="#000"><a href="http://<?php echo $userrow['domain']?>/" target="_blank" rel="noreferrer"><?php echo $userrow['domain']?></a></font>
                    <a style="width: 100%;" href="javascript:;" id="copy-btn1" data-clipboard-text="<?php echo $userrow['domain']?>">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    <br><br>
                    <font color="#ff0000">注意：（必看）<br></font>
                    <font color="#ff0000">更换域名后下面活码链接不会变，可以随意更换~</font>
                </div>
                </div>
            </div>
        </div>
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/anquan.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>直链活码链接</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="tuiguang.php" style="color: #fff;">生成推广码</a>
                    </div></div>
                    <div class="content-item-bottom">
                <div style="padding: 5px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#980000">活码链接：<br></font>
                    <font color="#000">使用本链接可以做到永久推广，无需更换，后台自适应最新地址！<br>
                    本链接采用最新技术，实时同步跳转您的【最新主域名】<br>
                    一次推广，终身<font color="red">不流失客户</font>，链接永久不变！
                    </font><br><br>
                    <font color="#980000">当前链接： (点击可访问)<br></font>
                    <font color="#000"><a href="<?php echo $conf['cos01'] ?>?zid=<?php echo $zid?>" target="_blank" rel="noreferrer"><?php echo $conf['cos01'] ?>?zid=<?php echo $zid?></a></font>
                    <a style="width: 100%;" href="javascript:;" id="copy-btn1" data-clipboard-text="<?php echo $conf['cos01'] ?>?zid=<?php echo $zid?>">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    <br><br>
                    <font color="#980000">小提示：<br></font>
                    <font color="#000">链接过长可使用缩短链接来进行推广，<a style="color: #4a86e8;" href="https://www.985.so/" target="_blank">点我进入缩短链接网站</a>（选择2号生成即可）</font><br><br>
                </div>
                </div>
            </div>
        </div>


        <?php if($conf['fanghong_api']){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/anquan.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>防红链接</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a id="recreate_url" style="color: #fff;">重新生成</a></div></div>
                    <div class="content-item-bottom">
            <li style="font-weight:bold;overflow: hidden;" class="list-group-item">
                <a href="javascript:;" id="copy-btn" data-clipboard-text="" >Loading...</a>&nbsp;&nbsp;&nbsp;
            </li>
                <div style="padding: 20px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#980000">防红链接：<br></font>
                    <font color="#000">该链接可以在QQ,微信进行推广。</font><br><br>
                    <!--<font color="#ff0000">注意：（必看）<br></font>
                    <font color="#ff0000">若你是第一次使用请在右边点重新生成，生成后先复制链接去浏览器访问检查下网址是否正确再进行推广，出现访问不正确就多生成几次即可，重新生成不要点太快，建议5秒一次。</font><br><br>-->
                    <font color="#980000">Tips：<br></font>
                    <font color="#000">点击网址即可复制~</font><br><br>
                    <font color="#980000">小提示：<br></font>
                    <font color="#000">在上面更换了域名 那之前的防红链接就不可用了，要在此重新生成防红链接才行。</font>
                </div>
                </div>
            </div>
        </div>

<!--
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/anquan.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>推广海报</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="tuiguang.php" style="color: #fff;">去生成</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 5px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#980000">推广海报二维码：（必看）<br></font>
                    <font color="#000"><font color="#ff0000">务必先在上面进行生成防红链接再进行生成二维码</font>，如果上面链接没问题但二维码访问不了的就是缓存问题，换个浏览器登录保存二维码就行。</font><br><br>
                    <font color="#980000">注意：<br></font>
                    <font color="#000">海报二维码就是上面链接转换的二维码~</font><br><br>
                    <font color="#980000">Tips：<br></font>
                    <font color="#000">都访问不了的建议切换网络重试~</font>
                </div>
                </div>
            </div>
        </div>-->
			<?php }?>
			
 
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
            </div>
        </div>

<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
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
if(window.location.hash=='#chongzhi'){
	$("#userjs").modal('show');
}
	$.ajax({
		type : "GET",
		url : "ajax_user.php?act=create_url",
		dataType : 'json',
		async: true,
		success : function(data) {
			if(data.code == 0){
				$("#copy-btn").html(data.url);
				$("#copy-btn").attr('data-clipboard-text',data.url);
			}else{
				$("#copy-btn").html(data.msg);
			}
		}
	});
});
</script>
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
var clipboard = new Clipboard('#copy-btn1');
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