<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');
list($background_image, $background_css) = \lib\Template::getBackground();
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
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">
    <title>用户登录 - <?php echo $conf['sitename'];  ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/main.css">
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">
	<style>html{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
     </head>
<body>
<?php if(checkmobile()){ ?>
<style>
html body{
    max-width: 550px;
    margin: 0 auto;
}
a{ text-decoration:none;}
    .logosx{
            margin-top: 1.125rem;
    margin-left: 1.59375rem;
    width: 2rem;
    height: 2rem;
    align-self: flex-start;
    }
    .stitle{
            width: 100%;
        padding-left: 1.525rem;
    color: #<?php echo $conf['rgb01']; ?>;
    font-weight: 600;
    font-size: 18px;
    margin-top: .5rem;
    }
    .sinput{
            padding: 0.65625rem .80625rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 0.0625rem solid #D3D6DC;
    margin: 0px 30px;
    margin-top: 30px;
    height: 2.125rem;
    border-radius: 1.3125rem;
    }
    .sinput input{
        border:0px;
        width: 90%;
    }
    .loginbtn{
    height: 1.75rem;
    background: linear-gradient(90deg,#<?php echo $conf['rgb01']; ?> 0%,#<?php echo $conf['rgb01']; ?> 100%);
    border-radius: 1.53125rem;
    font-weight: 500;
    color: #fff;
    font-size: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0px 50px;
    margin-top: 1.59375rem;
    }
    .loginbtn1{
    height: 1.75rem;
    background: linear-gradient(90deg,#<?php echo $conf['rgb01']; ?> 0%,#<?php echo $conf['rgb01']; ?> 100%);
    border-radius: 1.53125rem;
    font-weight: 500;
    color: #fff;
    font-size: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0px 50px;
    margin-top: .79375rem;
    }
    .btnxs{
        margin: 10px 40px;
        display: flex;
        justify-content: space-between;
    }
    .btnxs a{
        color: #<?php echo $conf['rgb01']; ?>;
    }
    .foots{
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }
    .foots a{
        color: #<?php echo $conf['rgb01']; ?>;
    }
    .geetest_btn{
           margin: 10px 5%;
    width: 90% !important;
    }
    .geetest_radar_btn{border-radius:26px !important;
         
    }
        .hometitle{
       width: 100%;
    text-align: center;
    color: #fff;
   background: linear-gradient(90deg,#<?php echo $conf['rgb01']; ?> 0%,#<?php echo $conf['rgb01']; ?> 100%);
    height: 40px;
    line-height: 40px;
    font-size: 15px;
    font-weight: 550;
    border-radius: 0 0 30px 30px;
    }
    .label {
            color: #868686;

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

    <div class="hometitle"><a href="" style="color: #ffffff;"><?php echo $conf['sitename'];?></div></a>
    <div style="width: 2rem;height: 2rem;"></div>
    
    <div class="stitle">登录 <a style="color: #838383;font-size: 13px;font\-weight:lighter;" href="../">(返回首页)</a></div>
    <div class="sinput"><i class="fa fa-user" style="color: #<?php echo $conf['rgb01']; ?>;width:10%"></i>
    <input type="text" name="user" value="" style="font-size: 14px;" required="required" placeholder="请输入账号"/></div>
    
    <div class="sinput"><i class="fa fa-key" style="color: #<?php echo $conf['rgb01']; ?>;width:10%"></i>
    <input type="password" name="pass" style="font-size: 14px;" placeholder="请输入密码"/></div>
    
        <div class="btnxs">
        <a>
    </a>
        <a style="font-size: 13px;" href="findpwd.php"><b>忘记密码?</b></a>
    </div>
    <div class="loginbtn" id="submit_login">登录</div>
    <a href="reg.php"><div class="loginbtn1">注册</div></a>
</div>
<hr>
<div align="center">
    <?php if($conf['login_qq']>=1){?>
    <div onClick="javascript:connect('qq')" style="width: 42px;height:42px; border: 1px solid rgba(213, 213, 213, 1); border-radius: 21px;  margin: 15px ; margin-top: 10px;   background-image: url(../assets/img/logoqq.png);background-size: 100%;display: inline-block;"></div>
        <?php }?>
    <?php if($conf['login_wx']>=1){?>
    <div onClick="javascript:layer.msg('站点未开启微信快捷登录');" style=" width: 42px;height:42px; border: 1px solid rgba(213, 213, 213, 1); border-radius: 21px;  margin: 15px ; margin-top: 10px;   background-image: url(../assets/img/logowx.png);background-size: 100%;display: inline-block;"></div>
        <?php }?>
        </div>
        
<?php }else{ ?>

<style>
html body{
    max-width: 550px;
    margin: 0 auto;
}
a{ text-decoration:none;}
    .logosx{
            margin-top: 1.125rem;
    margin-left: 1.59375rem;
    width: 2.59375rem;
    height: 2.09375rem;
    align-self: flex-start;
    }
    .stitle{
            width: 100%;
        padding-left: 1.7225rem;
    color: #<?php echo $conf['rgb01']; ?>;
    font-weight: 600;
    font-size: .825rem;
    margin-top: .5rem;
    }
    .sinput{
            padding: 0.65625rem .80625rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 0.0625rem solid #D3D6DC;
    margin: 0px 50px;
    margin-top: 30px;
    height: 1.925rem;
    border-radius: 1.3125rem;
    }
    .sinput input{
        border:0px;
        width: 90%;
    }
    .loginbtn{
    height: 1.55rem;
    background: linear-gradient(90deg,#<?php echo $conf['rgb01']; ?> 0%,#<?php echo $conf['rgb01']; ?> 100%);
    border-radius: 1.53125rem;
    font-weight: 500;
    color: #fff;
    font-size: .5375rem;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0px 100px;
    margin-top: 1.59375rem;
    }
    .loginbtn1{
    height: 1.55rem;
    background: linear-gradient(90deg,#<?php echo $conf['rgb01']; ?> 0%,#<?php echo $conf['rgb01']; ?> 100%);
    border-radius: 1.53125rem;
    font-weight: 500;
    color: #fff;
    font-size: .5375rem;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0px 100px;
    margin-top: .79375rem;
    }
    .btnxs{
        margin: 10px 55px;
        display: flex;
        justify-content: space-between;
    }
    .btnxs a{
        color: #<?php echo $conf['rgb01']; ?>;
    }
    .foots{
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }
    .foots a{
        color: #<?php echo $conf['rgb01']; ?>;
    }
    .geetest_btn{
           margin: 0px 5%;
    width: 90% !important;
    }
    .geetest_radar_btn{border-radius:26px !important;
         
    }
        .hometitle{
       width: 100%;
    text-align: center;
    color: #fff;
   background: linear-gradient(90deg,#<?php echo $conf['rgb01']; ?> 0%,#<?php echo $conf['rgb01']; ?> 100%);
    height: 50px;
    line-height: 50px;
    font-size: 15px;
    font-weight: 550;
    border-radius: 0 0 30px 30px;
    }
    .label {
            color: #868686;

    }
</style>
    <div class="hometitle"><a href="" style="color: #ffffff;"><?php echo $conf['sitename'];?></div></a>
    <div style="width: 2rem;height: 2rem;"></div>
    
    <div class="stitle">登录 <a style="color: #838383;font-size: 13px;font\-weight:lighter;" href="../">(返回首页)</a></div>
    <div class="sinput"><i class="fa fa-user" style="font-size: 14px;color: #<?php echo $conf['rgb01']; ?>;width:10%"></i>
    <input type="text" name="user" value="" required="required" style="font-size: 14px;" placeholder="请输入账号"/></div>
    
    <div class="sinput"><i class="fa fa-key" style="font-size: 14px;color: #<?php echo $conf['rgb01']; ?>;width:10%"></i>
    <input type="password" name="pass" style="font-size: 14px;" placeholder="请输入密码"/></div>
    
        <div class="btnxs">
        <a>
    </a>
        <a style="font-size: 13px;" href="findpwd.php"><b>忘记密码?</b></a>
    </div>
    <div class="loginbtn" id="submit_login">登录</div>
    <a href="reg.php"><div class="loginbtn1">注册</div></a>
</div>
<hr>
<div align="center">
    <?php if($conf['login_qq']>=1){?>
    <div onClick="javascript:connect('qq')" style="width: 42px;height:42px; border: 1px solid rgba(213, 213, 213, 1); border-radius: 21px;  margin: 15px ; margin-top: 10px;   background-image: url(../assets/img/logoqq.png);background-size: 100%;display: inline-block;"></div>
        <?php }?>
    <?php if($conf['login_wx']>=1){?>
    <div onClick="javascript:layer.msg('站点未开启微信快捷登录');" style=" width: 42px;height:42px; border: 1px solid rgba(213, 213, 213, 1); border-radius: 21px;  margin: 15px ; margin-top: 10px;   background-image: url(../assets/img/logowx.png);background-size: 100%;display: inline-block;"></div>
        <?php }?>
        </div>
<?php } ?>
        <br>
        <div style="font-size: 13px;color: #999999;text-align: center">
            <p style="text-align: center;"><span style="color: rgb(148 146 146); " microsoft="" font-size:="" text-align:="" background-color:="">版权所有 © Copyright<br>© 2022-2024 <?php echo $conf['sitename'] ?><br>All rights reserved.</span></p>
        </div>

<?php if($conf['appurl']){?>
<button style="width: 132.48px; height: 39.744px; color: rgb(255, 255, 255); display: block; position: fixed; left: 50%; bottom: 52.992px; transform: translate(-50%); border: none; box-shadow: rgba(18, 18, 18, 0.25) 0px 2px 5px; background-color: rgb(255, 149, 82); font-size: 15.456px; border-radius: 19.872px; text-align: center; align-items: center; z-index: 1; font-family: Helvetica, sans-serif; text-decoration: none; user-select: none;"><a href="<?php echo $conf['appurl']; ?>" style="color: #fff;">前往下载APP</a></button>
<?php }?>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="../assets/js/login.js?ver=<?php echo VERSION ?>"></script>
<script>
function goback()
{
document.referrer === '' ?window.location.href = '/' :window.history.go(-1);
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