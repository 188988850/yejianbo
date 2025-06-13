<?php
if (!defined('IN_CRONLITE')) die();
$qqlink = 'https://wpa.qq.com/msgrd?v=3&uin='.$conf['kfqq'].'&site=qq&menu=yes';
if($is_fenzhan && !empty($conf['kfwx']) && file_exists(ROOT.'assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png')){
	$qrcodeimg = './assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png';
	$qrcodename = '微信';
}elseif(!empty($conf['kfwx']) && file_exists(ROOT.'assets/img/wxqrcode.png')){
	$qrcodeimg = './assets/img/wxqrcode.png';
	$qrcodename = '微信';
}else{
	$qrcodeimg = '//api.qrserver.com/v1/create-qr-code/?size=250x250&margin=10&data='.urlencode($qqlink);
	$qrcodename = 'QQ';
}
//获取用户真实IP
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";

    $ips=$DB->getRow("select * from shua_ip where id=1 limit 1");
    
if(strpos($ips['ips'],$ip) !== false){ 
 header("location:/404.html");
}
?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title><?php echo $conf['sitename'] .($conf['title']==''?'':' - '.$conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <!-- Vendor styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
     </head>
<style>
    body{
        max-width:550px;
        margin:0 auto;
      overflow: auto;height: auto !important;
    }
    .container {
        margin:10px 0px;
  width: 80%;
  text-align: center;
}

    
    .top{
      background-color: #<?php echo $conf['rgb01']; ?>;
      width: 100%;
      max-width: 550px;
      }
    .top1{
      background-color: #<?php echo $conf['rgb01']; ?>;
      width: 100%;
      max-width: 550px;0
      padding-bottom:10px;
      position: fixed;
      }
      .home{
              text-align: center;
    line-height: 25px;
        height: 25px;
        width: 25px;
        border-radius: 50%;
        background-color: #fff;
        position: fixed;
        top: 18px;
        margin-left: 18px;
      }
      .toptitle{
      font-weight:600;
      color:#fff;
      text-align: center;
      height: 60px;
      line-height: 65px;
      }
      .article-content img {
      max-width: 100% !important;
      }
      .main[data-v-cc2d9c72] {
      width: 93%;
      }
      .main {
      margin: 0 auto;
      }
          
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
.privatePolicy {
    padding: 1rem .7rem .5rem .7rem;
    height: 100%;
}
.policy {
    width: 100%;
}
.text-div {
    padding: 0rem;
}
.sub-title {
    color: #111;
    font-size: 16px;
    padding: .2rem 0rem;
    font-weight: bold;
}
.sub-text-div {
    background: #fff;
    border-radius: .2rem;
    padding: .5rem;
}
.sub-text {
    color: #666;
    font-size: 13px;
    vertical-align: middle;
    font-family: PingFang SC;
    padding: .04rem .1rem .04rem;
}
.headbox {
    height: 30px;
    width: 96%;
    font-size: 15px;
    margin: 10px 2%;
    /* border-radius: 5px; */
    /* padding: 2px; */
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    align-content: center;
    flex-wrap: nowrap;
    flex-direction: row;
}
</style>
<body ontouchstart="" style="overflow: auto;height: 9rem; !important;max-width: 550px; margin:auto;">
    <div id="body">
    <div class="fui-page-group statusbar" style="max-width: 550px;left: auto;overflow: hidden;">
    <div class="top">
        <div class="toptitle" style="font-size: 15px;">
            客服中心
        </div>
            </div>
            
            <div class="privatePolicy">
                <div class="policy">
                    <div class="text-div">
                        <span class="sub-title">站点公告：</span>
                            <div class="sub-text-div">
                                <span class="sub-text"><?php echo $conf['modal'] ?> </span>
                        </div>
                    </div>
                </div>
                <hr>
                
            <div class="headbox">客服QQ<span style="line-height:2;">
                <font style="font-size: 15px;color:#000"><?php echo $conf['kfqq'] ?></font>
                    <a href="javascript:;" class="wx_hao" data-clipboard-text="<?php echo $conf['kfqq'] ?>">
                        <img style="width:30px;height:30px;padding-left:10px" src="<?php echo $cdnserver?>template/storenews/image/user/fuzhi.svg" />
                    </a>
                </span>
            </div>
                
            <div class="headbox">客服微信<span style="line-height:2;">
                <font style="font-size: 15px;color:#000"><?php echo $conf['kfwx']; ?></font>
                    <a href="javascript:;" class="wx_hao" data-clipboard-text="<?php echo $conf['kfwx']; ?>">
                        <img style="width:30px;height:30px;padding-left:10px" src="<?php echo $cdnserver?>template/storenews/image/user/fuzhi.svg" />
                    </a>
                </span>
            </div>
            
            <div class="headbox">工作时间<span style="line-height:2;">
                <font style="font-size: 15px;color:#000">10:00~22:00</font>
                    <a style="width:30px;height:30px;padding-left:10px"></a>
                </span>
            </div>
            
        <div style="width:100%;text-align:center;font-size:0.65rem;color:#9a9696;font-size: 14px;">- 如有疑问请咨询客服, 24小时竭诚为你服务 -</div>

            <div class="text-center" style="padding: 10px 1px;">
                <input type="button" class="btn submit_btn" style="width: 70%; padding: 2px;" value="返回" onclick="history.back();">
            </div>
                
</div></div></div></div>
    
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.wx_hao');
	clipboard.on('success', function (e) {
        layer.msg('复制成功');
    });
    clipboard.on('error', function (e) {
        layer.msg('复制失败');
    });
    function openwx(){
         locatUrl = "weixin://";
         if(/ipad|iphone|mac/i.test(navigator.userAgent)) {
            var ifr =document.createElement("iframe");
            ifr.src = locatUrl;
            ifr.style.display = "none";
            document.body.appendChild(ifr);
         }else{
            window.location.href = locatUrl;
         }
    }
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