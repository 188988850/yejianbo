<?php
/**
 * 在线充值余额
**/
$is_defend=true;
include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if($islogin2==1 && $userrow['power']>0){
}
$title='在线充值余额';
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
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>在线充值余额</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
    <link rel="stylesheet" type="text/css" href="../assets/store/css/style(1).css">
   <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body><style>
    img.logo {
        width: 1.8rem;


    }
    .money-input{

        width: 90%;
        background:#f2f2f2;
        border-radius: 50px;

    }
    .pay_list{
        font-size: 1.4rem;
        width: 100%;
        height: 3rem;
        border-radius: 40px;
        background: #fff;
        border:  1px solid #b0b0b0;
        margin-right: 0%;
    }
    .pay-button{
        width: 25%;
        height: 80%;
        border-radius: 50px;

        background: #fff;
        margin-left: 10px;
        font-size: 1.2rem;
    }
    /*偶数even 奇数 odd*/
    .mx-list .mx-list-item:nth-child(odd){
        background: #fafafa;
    }
    .li-list-item{
        padding:3px 0;
        justify-content: start;

    }
    .li-list-item .item-title{
        padding-right: 10px;
        color: #999999;
        font-size: 1.3rem;
        width: auto;
    }
    .li-list-item .item-info{
        flex-grow:2;
        width: auto;
        color: #0a0c0d;
        font-size: 1.3rem;
    }
    .li-list-item .item-right{
        flex-grow:2;
        width: auto;
        text-align: right;

    }
    .left-title{

    }
    .left-title::before{
        height: 100%;
        width: 5px;
        border-radius: 15px;
        background: #f99144;
    }
    .layerdemo{
        border-radius: 10px;
        color:black;
        overflow: hidden;
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
    .layui-layer{
       border-radius: 9px !important;
    }
    .layui-layer-title{
        border-radius: 9px !important;
    }
    .experience-icon {
    position: relative;
    width: 18.125rem;
    height: 0.2525rem;
    background: linear-gradient(
    270deg,#FFB95E 0%,#FFD4A7 100%);
    border-radius: 0.15625rem;
    z-index: 1;
}
</style>
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
                <a href="javascript:history.back()"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">在线充值余额</a></font>
            </div>
                </div>
        </div>
         <div style="padding-top: 60px;"></div>
        <div class="my-cell" style="background: #f5f5f5;margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:14px;color:#454e59;font-weight:400;">当前余额</div>
                <div class="my-cell-title-r  display-row  align-center">
                    <span style="color: #939393;font-size:14px"><?php echo $userrow['rmb']?>元</span>
                </div>
            </div></div>
<div class="aui-introduce"> 
	<div class="aui-tab" data-ydui-tab="">
    <!--人工充值-->
    <div class="tab-panel">
        
        <div id="spxq_1" class="tab-panel-item tab-active">
            <div class="tab-item">
                <div class="content_friends">
                    <div class="hd_intro" style="word-break: break-all;">
                        
            </div>
                   <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon" style="font-size: 14px;">
                                充值金额
                            </div>
                            <input type="number" name="value" value="" class="form-control" autocomplete="off" placeholder="请输入充值金额，最低充值<?php echo $conf['recharge_min']?>元">
                        </div>
                    </div>
                <?php if($conf['fenzhan_jiakuanka']){?>
                   <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon" style="font-size: 14px;">
                                卡密充值
                            </div>
                        <div style="height:3rem;width: 100%;margin: 2px auto; border-radius: 50px; background: #fff; font-size: 1.1rem;" class="display-row align-center">
                        <input class="form-control" name="km" autocomplete="off" placeholder="请输入加款卡密" required style="height: 100%;width: 50%; border:  0px solid #ebebeb;flex-grow:2;" value="" />
                        <div style="width: 30%;height: 100%;padding: 5px;flex-grow:1;" class="display-row-reverse align-center flex-nowrap">
                            <button id="usekm" style="border-radius: 50px;padding:0 15px;height: 100%;border:0px solid #ebebeb; color: black">使用</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>
                    
            <div align="center" style="padding: 12px 20px;background: #f5f5f5;border-radius: 5px;width: 100%;font-size: 14px">- 充值方式 -
            </div>
                <?php
if($conf['wxpay_api'])echo '
                    <div class="input-group" style="width:100%">
                        <div class="form-group form-group-transparent form-group-border-bottom">
                            <div class="input-group-addon" id="buy_wxpay">
                                <img src="../assets/img/wxpay.png" class="logo">微信
                            </div>
                        </div>
                    </div>';
if($conf['alipay_api'])echo '
                    <div class="input-group" style="width:100%;">
                        <div class="form-group form-group-transparent form-group-border-bottom">
                            <div class="input-group-addon" id="buy_alipay">
                                <img src="../assets/img/alipay.png" class="logo">支付宝
                            </div>
                        </div>
                    </div>';
if($conf['qqpay_api'])echo '
                    <div class="input-group" style="width:100%">
                        <div class="form-group form-group-transparent form-group-border-bottom">
                            <div class="input-group-addon" id="buy_qqpay">
                                <img src="../assets/img/qqpay.png" class="logo">QQ钱包
                            </div>
                        </div>
                    </div>';
                    
?>
                <?php if($conf['fenzhan_jiakuanka']){?>
                    <div class="input-group" style="width:100%;">
                        <div class="form-group form-group-transparent form-group-border-bottom">
                            <div class="input-group-addon" id="">
                                <img src="../assets/img/payd.png" class="logo">购买加款卡密（暂未开放）
                            </div>
                        </div>
                    </div>
                <?php }?>
        </div>
    </div>
            <div style="padding: 12px 20px;border-radius: 5px;width: 100%;font-size: 13px">
                <small style="color:#980000;">充值的余额永不过期，下单多的用户可一次性多充点方便下单使用，充值的余额只能用于消费，无法提现</small>
                <?php echo $conf['czsm1']; ?>
                                <!--<a>
               支付时出现支付异常，可进行分批小额充值，也可选择下面的转账充值，支持微信/支付宝/花呗/信用卡。<br><br>
               <div align="center" style="font-size: 1.3rem">如果无法微信支付<br>
               可以在微信公众号搜QQ钱包<br>
               然后把微信余额转到QQ里<br>
               <a href="./wxzy.php" style="color: #980000;">点我看详细微信余额转QQ方法教程</a></div></a>-->
            </div>
             <div align="center" style="padding: 12px 20px;background: #f5f5f5;border-radius: 5px;width: 100%;font-size: 14px">- 充值记录 -
            </div>
<?php
$flag=false;
$rs=$DB->query("SELECT * FROM shua_points WHERE zid='{$userrow['zid']}' AND action='充值' ORDER BY id DESC LIMIT 10");
while($res = $rs->fetch())
{
$flag=true;
echo '

<div class="mx-list" style="width: 100%;">
            <div class="mx-list-item" style="width: 100%;font-size: 1.4rem;background: #ffffff;">
                         <div style="width: 100%;padding: 12px 10px;position: relative">
                            <div class="li-list-item">
                                <div class="item-title">充值金额</div>
                                <div class="item-info">
                                   '.$res['point'].'
                                </div>
    
                               <div class="item-right"> '.$res['addtime'].'</div>
                            </div>
                            
                            <div class="li-list-item">
                                <div class="item-title">充值状态</div>
                                <div class="item-info">
                                    已完成
                                </div>
    
                                <div class="item-right"></div>
                            </div>
                         </div>
                    </div>     </div>';
        }
    ?>
</div>
                
        </div>

        
<?php if(!empty($conf['fenzhan_gift'])){
$rules = explode('|',$conf['fenzhan_gift']);
//$i=0;
?>
<div align="center" style="padding: 12px 20px;background: #f5f5f5;border-radius: 5px;width: 100%;font-size: 14px">- 充值活动 -</div>
<?php foreach($rules as $rule){
$arr = explode(':',$rule);
echo $i.'<div style="padding: 5px 5px;border-radius: 2px;width: 100%;font-size: 1.4rem">
                    <div align="center">
                     <div class="li-list-item">
                        <div class="item-info ellipsis1">充值平台余额</div>
                        <div class="item-info ellipsis1">≥￥'.$arr[0].'元</div>
                        <div class="item-info ellipsis1">单笔赠送'.$arr[1].'%</div>
                    </div>
                </div>
            </div>';
}
?>
<?php }?>

    </div>
</div>

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
    function dopay(type) {
        var value = $("input[name='value']").val();
        if (value == '' || value == 0) {
            layer.alert('充值金额不能为空');
            return false;
        }
        $.get("ajax_user.php?act=recharge&type=" + type + "&value=" + value, function (data) {
            if (data.code == 0) {
                window.location.href = '../other/submit.php?type=' + type + '&orderid=' + data.trade_no;
            } else {
                layer.alert(data.msg);
            }
        }, 'json');
    }

    $(document).ready(function () {
        $("#buy_alipay").click(function () {
            dopay('alipay')
        });
        $("#buy_qqpay").click(function () {
            dopay('qqpay')
        });
        $("#buy_wxpay").click(function () {
            dopay('wxpay')
        });
        $("#usekm").click(function () {
            var km = $("input[name='km']").val();
            $.ajax({
                type: "POST",
                url: "ajax_user.php?act=usekm",
                data: {km: km},
                dataType: 'json',
                async: true,
                success: function (data) {
                    if (data.code == 0) {
                        layer.alert(data.msg, {icon: 1}, function () {
                            window.location.reload()
                        });
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            });
        });
    })
</script>

<?php include './foot.php';?>

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
<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
</body>
</html>