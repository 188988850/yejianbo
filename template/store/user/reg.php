<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');
$addsalt=md5(mt_rand(0,999).time());
$_SESSION['addsalt']=$addsalt;
$x = new \lib\hieroglyphy();
$addsalt_js = $x->hieroglyphyString($addsalt);
list($background_image, $background_css) = \lib\Template::getBackground();
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no;"/>
    <title>注册账号 - <?php echo $conf['sitename'];  ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
  
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/main.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">
    
      <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/style1.css">
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<style>html{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>
<style>
body {
    width: 100%;
    max-width: 550px;
    margin: auto;
    background: #f3f3f3;
    line-height: 24px;
    font: 14px Helvetica Neue,Helvetica,PingFang SC,Tahoma,Arial,sans-serif;
}
.label{
    color: unset;
    line-height: 1.8;
}
.account-main{
    height: 100% !important;
}
a {
    text-decoration:none;
}
a:hover{
    text-decoration:none;
}
.fui-modal{z-index: 20;}
.fui-page-group-bg{
     background-image: url('../assets/img/88952.jpg');
    background-size: 100% 100%;
    background-repeat:no-repeat;
}
</style>
<body>
<div id="body">
    <div class="fui-page-group fui-page-group-bg  statusbar" style="max-width: 550px;left: auto;">
        <form id="form1">
            <div class="fui-modal popup-modal in">
                <div class="account-layer login" style="margin-top:1rem">
                    <div class="account-layer-title" style="margin-top:38px;">用户注册</div>
                        <div class="account-main">
                            <div class="account-back"><i class="icon icon-back"></i></div>
                           <div class="account-title">
                               <font class="account-title-l"> 注册</font>
                               <a href="login.php" id="tabnormal"  class="account-title-r">返回登录<i class="glyphicon glyphicon-user"></i></a>
                            </div>
                            <div class="account-group">
                                <div class="account-input-group">
                                    <span class="account-input-addon-icon fa fa-user"></span>
                                    
                                    <input type="text" name="user" value="" class="account-group-input" required="required" placeholder="输入登录用户名"/>
                                </div>
                            </div>

                            <div class="account-group">
                                <div class="account-input-group">
                                        <span class="account-input-addon-icon fa fa-lock"></span>
                               
                                    <input type="text" name="pwd" class="account-group-input" required="required" placeholder="输入6位以上密码"/>
                                </div>
                            </div>

                            <div class="account-group">
                                <div class="account-input-group">
                                        <span class="account-input-addon-icon fa fa-qq"></span>
                                
                                  <input type="text" name="qq" class="account-group-input" required="required" placeholder="输入QQ号，用于找回密码"/>
                                </div>
                            </div>
                            <?php if($conf['captcha_open']>=1 && $conf['captcha_open_reg'] == 1){?>
                                <input type="hidden" name="captcha_type" value="<?php echo $conf['captcha_open']?>"/>
                            <?php if($conf['captcha_open']>=2){?><input type="hidden" name="appid" value="<?php echo $conf['captcha_id']?>"/><?php }?>
                            <div id="captcha" style="margin: auto;">
                                <div id="captcha_text">
                                    正在加载验证码
                                </div>
                                <div id="captcha_wait">
                                    <div class="loading">
                                        <div class="loading-dot"></div>
                                        <div class="loading-dot"></div>
                                        <div class="loading-dot"></div>
                                        <div class="loading-dot"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="captchaform"></div>
                            <br/>
                            <?php }else{?>
                            <div class="account-group">
                                <div class="account-input-group">
                                    <span class="account-input-addon-icon fa fa-adjust"></span>
                                    <input type="text" name="code" value="" class="account-group-input-code" required="required" placeholder="输入验证码"/>
                                    <span class="input-group-addon" style="padding: 0">
                                        <img id="codeimg" src="./code.php?r=<?php echo time();?>" height="35"  onclick="this.src='./code.php?r='+Math.random();" title="点击更换验证码">
                                    </span>
                                </div>
                            </div>
                            <?php }?>
                   
                        <div class="account-btn" id="submit_reg">注册</div>
                        
                       </div>
                        <div class="account-red" style="text-align:center">
                            已有账号？<a href="login.php" style="color:#3d89c9;">点此登录</a>
                        </div>
                         <hr style="border-top: 1px solid rgba(0,0,0,.1);">
                        <div style="text-align: center;margin-bottom: 5px;color:#999;margin-top: 15px;">
                        <?php if($conf['login_qq']==1){?>
                         <hr style="border-top: 1px solid rgba(0,0,0,.1);">   <div style="color:#999;position: relative;top: -12px;width: 100px;background-color: white;margin: auto">            第三方登录</div>
                        <div onclick="javascript:connect('qq')" style="    width: 42px;height:42px;     border: 1px solid rgba(213, 213, 213, 1); border-radius: 21px;  margin: 15px auto ; margin-top: 10px;   background-image: url(../assets/img/logo2.png);background-size: 100%">
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
     <div class="fui-navbar" style="z-index: 100000;max-width: 550px;">
        <a href="../" class="nav-item  "> <img src="../assets/user/img/home.png" style="24px;width: 24px;"> <span class="label">首页</span> </a>
        <a href="../?mod=query" class="nav-item "> <img src="../assets/user/img/tab_order.png"style="24px;width: 24px;"> <span class="label">订单</span> </a>
        <a href="../?mod=cart" class="nav-item " style="display:none"> <span class="icon icon-cart2"></span> <span class="label">购物车</span> </a>
        <a href="../?mod=kf" class="nav-item "> <img src="../assets/user/img/tab_kefu.png"style="24px;width: 24px;"> <span class="label">客服</span> </a>
        <a href="./" class="nav-item active"> <img src="../assets/user/img/tab_my_active.png"style="24px;width: 24px;">  <span class="label">会员中心</span> </a>
    </div>
</div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="../assets/js/reguser.js?ver=<?php echo VERSION ?>"></script>
<script>
function goback()
{
    document.referrer === '' ?window.location.href = '/' :window.history.go(-1);
}
var hashsalt=<?php echo $addsalt_js?>;
</script>
</body>
</html>