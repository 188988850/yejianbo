
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>加盟站长/合伙人</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
  <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
  <link rel="stylesheet" href="../assets/user/css/work.css">
  <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
  <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>

<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body>
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
.open_substation{
    width: 100%;
    min-height: 100%;
    background-size: 550px;
    background-repeat: no-repeat;
    background-color: #fdefee;
    display: flex;
    align-items: center;
    flex-direction: column;
    box-sizing: border-box!important;
}
.tips{
    width: 100%;
    margin-top: 22px;
    padding-left: 27px;
}
.title1{
    display: inline-block;
    width: auto;
    font-family: PingFangSC-Semibold,PingFang SC;
    font-weight: 600;
    color: #662e07;
    font-size: 20px;
    position: relative;
    margin-top: 10px;
}
.title2 {
    margin-top: 8px;
    width: 100%;
    text-align: center;
    color: #662e07;
    font-weight: 600;
    font-family: PingFangSC-Semibold,PingFang SC;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
}
.crown{
    position: absolute;
    width: 24px;
    height: 18px;
    top: 0;
    right: 0;
    -webkit-transform: translate(50%,-50%);
    transform: translate(50%,-50%);
}
.logo-text{
    font-weight: 400;
    color: #662e07;
    font-family: PingFangSC-Regular,PingFang SC;
    font-size: 16px;
    margin-bottom: 22px;
    margin-top: 5px;
}
.join-show{
    width: 94%;
    height: 490px;
    background: linear-gradient(180deg,#fff,#fffbf9);
    border-radius: 11px;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 15px;
}
.gird-box{
    display: grid;
    margin-top: 5px;
    grid-template-columns: repeat(4,auto);
    width: 100%;
    gap: 20px 0;
}
.item{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
}
.img{
    width: 49px;
    height: 49px;
}
.name{
    font-size: 13px;
    font-family: PingFangSC-Regular,PingFang SC;
    font-weight: 400;
    color: #9b9fa8;
}
.btn-box{
    bottom: 0;
    width: 100%;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fdefee;
}
.btn-box .submit-btn{
    width: 353px;
    height: 51px;
    background: linear-gradient(135deg,#ffeec9,#f3d89c);
    color: #6f5632;
    border-radius: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-family: PingFangSC-Medium;
    font-weight: 500;
}
</style>

<?php
/**
 * 加盟站长/合伙人
**/
include("../includes/common.php");
?>

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
                <font><a href="">加盟站长/合伙人</a></font>
            </div>
        </div>
 <div style="padding-top: 60px;"></div>
<div class="open_substation" style="background-image: url(../assets/img/img_bg.png);">
<div class="tips">
    <?php  if( $userrow['power']==0){?><div class="title1">成为站长/合伙人<?php }?>
    <?php  if( $userrow['power']==1){?><div class="title1">成为顶级合伙人<?php }?>
    <?php  if( $userrow['power']==2){?><div class="title1">您当前已是最高等级<?php }?>
        <img class="crown" src="../assets/img/img_huangguan.png"></div>
            <div class="logo-text">自购省钱 拓客赚钱</div>
        </div>
    <div class="join-show">
        <div class="title2"><img  class="img" src="../assets/store/svg/img_left.svg"></image>专属权益<img  class="img" src="../assets/store/svg/img_right.svg"></image></div>
            <div class="gird-box">

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_10.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_10.svg"></image>
                            <div class="name">专享低价货源</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_1.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_1.svg"></image>
                            <div class="name">免费搭建分站</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_2.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_2.svg"></image>
                            <div class="name">专属官方社群</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_9.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_9.svg"></image>
                            <div class="name">专属后台管理</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_8.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_8.svg"></image>
                            <div class="name">自定义网站名</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_11.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_11.svg"></image>
                            <div class="name">自定义网站域名</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_3.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_3.svg"></image>
                            <div class="name">站点用户管理</div></div>

                <div class="item">
                    <div style="background-image: url(../assets; background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_13.svg"></image>
                            <div class="name">下级分站管理</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_14.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_14.svg"></image>
                            <div class="name">开通商品改价</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_5.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_5.svg"></image>
                            <div class="name">赚取用户差价</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_12.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_12.svg"></image>
                            <div class="name">赚取分站差价</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_5.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_5.svg"></image>
                            <div class="name">赚取分站提成</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_12.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_12.svg"></image>
                            <div class="name">赚取下级返利</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_14.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_14.svg"></image>
                            <div class="name">赚取团队收益</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_2.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_2.svg"></image>
                            <div class="name">专属引流教程</div></div>

                <div class="item">
                    <div style="background-image: url(../assets/store/svg/icon_7.svg); background-position: center center; background-size: contain; background-repeat: no-repeat;"></div>
                        <img  class="img" src="../assets/store/svg/icon_7.svg"></image>
                            <div class="name">其它众多功能</div></div>

            </div>
        </div>
        
        <?php  if( $userrow['power']==0){?>
            <div class="btn-box">
                <a href="regsite.php"><div class="submit-btn">立即开通</div></a>
            </div>
        <?php }?>
        <?php  if( $userrow['power']==1){?>
            <div class="btn-box">
                <a href="upsite.php"><div class="submit-btn">立即升级</div></a>
            </div>
        <?php }?>
        <?php  if( $userrow['power']==2){?>
            <div class="btn-box">
                <div class="submit-btn">您当前已是最高等级,无需再次升级</div>
            </div>
        <?php }?>
        
        </div>
    </div>

        <div align="center" style="color: rgb(148 146 146);margin-top: 20px;">© 版权所有 <?php echo $conf['sitename'] ?></div>
        <div align="center" style="color: rgb(148 146 146);margin-top: 50px;">&nbsp;&nbsp;</div>
<!--导航开始-->
<style>
  .bottom-nav {
    max-width: 550px;
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #fff;
    color: #333;
    text-align: center;
    padding: 5px 0;
    z-index: 100;
    /* 添加一些额外的样式来优化小屏幕设备的显示 */
    display: flex;
    justify-content: space-around; /* 导航项均匀分布 */
    flex-wrap: wrap; /* 允许换行 */
  }

  .bottom-nav a {
    color: #333;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    padding: 5px 15px;
    /* 可以在这里添加更小的字体和间距，以适应更小的屏幕 */
  }

  .bottom-nav a:hover {
    background-color: #ddd;
    color: black;
  }

  /* 媒体查询开始，针对手机等小屏幕设备 */
  @media (max-width: 768px) {
    .bottom-nav a {
      font-size: 12px; /* 减小字体大小 */
      padding: 5px 10px; /* 减小内边距 */
    }
    
    .bottom-nav i {
      font-size: 14px; /* 减小图标大小 */
    }

    /* 你可以根据需要添加更多的样式调整 */
  }

  /* 如果需要，可以添加更多媒体查询来适应不同屏幕尺寸 */
</style>

<div class="bottom-nav" style="border-radius: 0px;box-shadow: 1px 1px 1px 1px #e2dfdf;border: 1px solid #f2f2f2;">
  <a href="../"><i class="fa fa-windows" style="font-size: 17px;"></i><br>首页</a>
  <a href="../?mod=fenlei"><i class="fa fa-table" style="font-size: 17px;"></i><br>分类</a>
  <a href="./?mod=duanju"><i class="fa fa-youtube-play" style="font-size: 17px;"></i><br>短剧</a>
  <?php  if($userrow['power']==0 || $userrow['power']==1){?>
  <a href="" style="color:#ff7c33;"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>升级</a>
  <?php }?>
  <?php  if( $userrow['power']==2){?>
  <a href="../?mod=latest"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>最新上架</a>
  <?php }?>
  <a href="../?mod=query"><i class="fa fa-list-ol" style="font-size: 17px;"></i><br>订单</a>
  <a href="./"><i class="fa fa-github-alt" style="font-size: 17px;"></i><br>会员中心</a>
</div>
<!--导航结束-->

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