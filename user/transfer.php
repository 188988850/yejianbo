<?php
if($conf['cdnpublic']==1){
	$cdnpublic = '//lib.baomitu.com/';
}elseif($conf['cdnpublic']==2){
	$cdnpublic = 'https://cdn.bootcdn.net/ajax/libs/';
}elseif($conf['cdnpublic']==4){
	$cdnpublic = '//lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/';
}else{
	$cdnpublic = '//cdn.staticfile.org/';
}
if(!empty($conf['staticurl'])){
	$cdnserver = '//'.$conf['staticurl'].'/';
}else{
	$cdnserver = '../';
}
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$title='余额转账';
if($conf['fenzhan_cost2']<=0)$conf['fenzhan_cost2']=$conf['fenzhan_price2'];
?>

<html lang="zh-cn" style="" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers no-applicationcache svg inlinesvg smil svgclippaths"><head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>余额转账</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
  <link rel="stylesheet" href="../assets/store/css/content.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet">
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
   <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script><link id="layuicss-laydate" rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/modules/laydate/default/laydate.css?v=5.0.9" media="all"><link id="layuicss-layer" rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/modules/layer/default/layer.css?v=3.1.1" media="all"><link id="layuicss-skincodecss" rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/modules/code.css" media="all">
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body><style>
    .form-control[disabled]{
        background-color:transparent;
        color: #999999;
    }

    form-control1::placeholder{
        text-align: left;
    }
    .form-control{
        text-align: right;
    }
    input::placeholder{
        text-align: right;
        font-size: 1.3rem;
    }
    .del_icon{
        display:none;
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
<body><style>
    .form-control[disabled]{
        background-color:transparent;
        color: #999999;
    }

    form-control1::placeholder{
        text-align: left;
    }
    .form-control{
        text-align: right;
    }
    input::placeholder{
        text-align: right;
        font-size: 1.3rem;
    }
    .del_icon{
        display:none;
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
                <a href="./"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">余额转账</a></font>

            </div>
                </div>

<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>转账说明</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">
                1.此功能方便下级充值不了余额的，可使用这个给下级转账<br>
                2.仅存在上下级关系才能进行转账<br>
                3.转账的余额只能用于消费，不能提现<br>
                4.填写的收款账号是下级的登录账号，不是UID<br>
                5.若转账方出现诈骗行为可向平台提交反馈进行举报，情况属实永久封号处理</font>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/zhuanzhang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>收款信息</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"></a>
                    <div class="charge-btn"><a href="javascript:;" onclick="submit()" style="color: #fff;">确定转账</a></div></div>
                    <!--<div class="charge-btn2"><a style="color: #fff;">临时维护</a></div></div>-->

                    <div class="content-item-bottom">
                    <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">
                                收款账号
                            </div>
                            <input type="text" id="account" name ='account' value="" class="form-control" placeholder="请填写对方账号">
                        </div>
                    </div>
                   <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">
                                转账金额
                            </div>
                            <input type="number" id="money" name="money" value="" class="form-control" placeholder="请填写转账金额">
                        </div>
                    </div>
                </div>
            </div>
            
<script>
function submit(){
    
    var account = $('#account').val();
    var money = $('#money').val();
    $.post('ajax_user.php?act=transfer', {
                    account:account,
                    money: money
                },
                    function (data) {
                        if (data.code == 0) {
                            layer.msg(data.msg);
                            setTimeout(function() {
                              location.reload();
                            }, 2000);
                            
                        } else {
                            layer.msg(data.msg);
                            
                        }
                    });
}
   
</script>
</body>
</html>