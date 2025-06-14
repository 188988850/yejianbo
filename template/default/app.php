<?php
if(!defined('IN_CRONLITE'))exit();

if(strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
	header('Content-type:text/html;charset=utf-8');
	include ROOT.'template/default/jump.php';
	exit;
}

$id=intval($_GET['id']);
$row=$DB->getRow("SELECT * FROM shua_apps WHERE id='$id' LIMIT 1");
if(!$row)exit("<script language='javascript'>alert('当前APP不存在！');history.go(-1);</script>");
if($row['status']==0)exit("<script language='javascript'>alert('当前APP无法使用！');history.go(-1);</script>");
if($row['status']==2 && $row['taskid']>0){
	if(!$conf['appcreate_key'])exit("<script language='javascript'>alert('未配置APP生成平台密钥');history.go(-1);</script>");
	$app = new \lib\AppCreate($conf['appcreate_key']);
	$res=$app->querytask($row['taskid']);
	if($res && is_array($res) && $res['status']==1){
		$android_url = $res['lanzou_url']?$res['lanzou_url']:$res['android_url'];
		$ios_url = $res['ios_url'];
		$DB->update('apps',['taskid'=>$res['id'], 'name'=>$res['name'], 'package'=>$res['package'], 'android_url'=>$android_url, 'ios_url'=>$ios_url, 'icon'=>$res['icon'], 'addtime'=>$res['created_at'], 'status'=>1], ['id'=>$row['id']]);
		$row['android_url'] = $android_url;
		$row['ios_url'] = $ios_url;
	}
}

if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')!==false && !empty($row['ios_url'])){
	$download_url = $row['ios_url'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <title><?php echo $row['name']?> - 下载APP</title>
    <meta id="viewport" name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link rel="stylesheet" type="text/css" href="./assets/app/css/ios-download.css"/>
</head>
<body class="mi-ui">
<div id="content">
    <header class="page_header_upice">
        <div class="logo_upice">
            <img src="./assets/img/appxz2.png" width='100'/></div>
        <p class="title_upice"> <?php echo $row['name']?></p>
    </header>
    <div class="page_content downloadNow">
        <a href="javascript:;" id="onekey" class="qui-button-main qui-button-primary weak weak"
           style="color: rgb(255, 255, 255); background-color: rgb(18, 183, 245); display: block;"> 立即安装 </a>
    </div>
    <div class="page_content">
        <div class="authorized_form_list hidecontrol" style="padding-top:50;">
            <ul id="api_list" class="api_list">

                <li id="item_1010" value="1010" state="" class="selected disabled" checked="checked" _dis="1"
                    tabindex="7">
                    <span class="ico_authorize"></span>
                    <span>安装流程：立即安装-允许-安装，如未跳到安装页面，请打开设置->通用->描述文件->安装</span></li>
                <li id="item_1010" value="1010" state="" class="selected disabled" checked="checked" _dis="1"
                    tabindex="7">
                    <span class="ico_authorize"></span>
                    <span>重要提示：请使用手机自带的【Safari浏览器】进行安装下载，其他的浏览器均不可用</span></li>
            </ul>
            
            <div class="control" tabindex="9">
                <span class="show_all"></span>
                <span id="controlText">查看全部</span>
            </div>
        </div>
        
        
        <div style="font-size:12px;margin-top:50px;text-align:center;">
            Copyright <?php echo date("Y")?> &nbsp; <?php echo $row['name']?>
            <BR>All Rights Reserved.
        </div>
    </div>
</body>
</html>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script>
    function is_weixin() {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger" || (ua.indexOf(' qq') != -1 && ua.indexOf('qbwebviewtype') != -1)) {
            return true;
        } else {
            return false;
        }
    }

    if (is_weixin()) {
        $("body").empty();
        $("body").append('<div class="wx_tip" style="position:absolute;top:0px;height:1000px;background-color:#4a6685;width:100%;"><img src="https://ae01.alicdn.com/kf/HTB1w4fYXbr1gK0jSZFDq6z9yVXaS.jpg" width="100%"></div>');
    }

    $(".downloadNow a,.downloadNow button").click(function () {
        window.location.href = "<?php echo $download_url?>";
        $(".downloadNow").html("<a class='qui-button-main qui-button-primary weak weak downbutton' style='color: rgb(255, 255, 255); background-color: rgb(18, 183, 245); display: block;color:#FFFF00;'>下载中...</a>");
        setTimeout("chanageLink()", 1500)

    });

    function chanageLink() {
        $(".downloadNow").html("<a href='javascript:;' class='app_xr qui-button-main qui-button-primary weak weak downbutton' style='color: rgb(255, 255, 255); background-color: rgb(18, 183, 245); display: block;'>请再次点击我去信任并安装</a>");
    }

    $("body").on("click", ".app_xr", function (data) {
        location.href = "./assets/app/embedded.mobileprovision";
    });
</script>
</body>
</html>
<?php
}else{
	$download_url = $row['android_url'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <html lang="zh-cn">
    <head>
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
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
        <title><?php echo $row['name']?> - 下载APP</title>
        <link href="assets/app/css/download.css" rel="stylesheet">
		<link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
        <style type="text/css">.wechat_tip,.wechat_tip>i{position:absolute;right:10px}
.wechat_tip{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;background:#3ab2a7;color:#fff;font-size:14px;font-weight:500;width:135px;height:60px;border-radius:10px;top:15px}
.wechat_tip>i{top:-10px;width:0;height:0;border-left:6px solid transparent;border-right:6px solid transparent;border-bottom:12px solid #3ab2a7}
.mask img{max-width:100%;height:auto}
.main>header .actions p {
    line-height: .5;
    padding: 12px;
    color: #3ab2a7;
    font-weight: 500;
    font-size: 14px;
}
.main>header .actions button {
    display: inline-block;
    padding: 5px 10px;
    min-width: 200px;
    border: 1px solid #32b2a7;
    border-radius: 40px;
    font-size: 14px;
    background: #32b2a7;
    color: #fff;
}
</style>
    </head>
<body>
<span class="pattern left"><img src="assets/app/images/left.png"></span>
<span class="pattern right"><img src="assets/app/images/right.png"></span>
<div class="out-container">
    <div class="main">
        <header>
            <div class="table-container">
                <div class="cell-container">
                    <div class="app-brief">
                        <div class="icon-container wrapper">
                            <i class="icon-icon_path bg-path"></i>
                           
                            <!--<div class="release-info"></div>-->
                            
                            <span class="icon"><div id="qrcode_icon"></div></span>
                            <!--<img id="qrcode"></img>-->
                            <!--<span class="qrcode"><div id="qrcode"></div></span>-->
                        </div>
                        <h1 class="name wrapper"><span class="icon-warp" style="margin-left:0px"><i class="icon-android"></i><?php echo $row['name']?></span>
                        </h1>
                        <p class="scan-tips" style="margin-left:170px">扫描二维码下载<br>或用手机浏览器输入这个网址：<span
                                    class="text-black"><?php echo $siteurl?></span>
                        </p>
                        <div class="release-info">
                            
                            <!--                                <p>1.0（Build 1）</p>-->
                            <p>更新于：<?php echo $row['addtime']?></p>
                        </div>
                    </div>
                    <div class="actions android" style="display: none;">
                        <p>复制链接到浏览器访问下载</p>
                        <p>点击下面文字即可复制</p><br>
                        <a style="width: 85%;display: inline-block;border-radius: 5px;box-shadow: 1px 1px 2px #e2dfdf;border:  1px solid #f2f2f2;" href="javascript:;" id="copy-btn1" data-clipboard-text="<?php echo $download_url?>">点我复制链接
                        <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    </div>
                    <div class="actions android" style="display: none;">
                        <a href="javascript:history.back()">
                            <button type="button">返回</button>
                        </a>
                    </div>
                </div>
            </div>
        </header>
    </div>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
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
    var url = "<?php echo $download_url?>";
    $('#qrcode').qrcode({width: 100, height: 100, text: url});
    $('#qrcode_icon').qrcode({width: 100, height: 100, text: url});
    $(function () {
        //判断是不是安卓访问
        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; // Android
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); // ios
        if (isIOS) {
            //判断是不是在safari
            $(".ios").show();
            $(".android").hide();
            if (!u.match(/safari/i)) {
                $("#iosdownload").html("请使用 safari 浏览器打开");
            }
        } else {
            $(".android").show();
            $(".ios").hide();
        }
    })
</script>
</body>
</html>
<?php }?>