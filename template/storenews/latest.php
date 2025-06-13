
<?php
if (!defined('IN_CRONLITE')) die();


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

    $arr=$DB->getRow("select * from shua_ip where id=1 limit 1");
    $res=explode(',',$arr['ips']);
foreach($res as $k=>$v){
    $ips=explode('-',$v);
    if(in_ip_range($ip, $ips[0], $ips[1])) {
   header('Location:/404.html');
 } 
}
 

function in_ip_range($ip, $ip_one, $ip_two = false) {
 if(!$ip_two) {
  return $ip_one === $ip;
 }
 return ip2long($ip_one) * -1 >= ip2long($ip) * -1 && ip2long($ip_two) * -1 <= ip2long($ip) * -1;
}

if($_GET['buyok']==1||$_GET['chadan']==1){include_once TEMPLATE_ROOT.'storenews/query.php';exit;}
if(isset($_GET['tid']) && !empty($_GET['tid']))
{
	$tid=intval($_GET['tid']);
    $tool=$DB->getRow("select tid from shua_tools where tid='$tid' limit 1");
    if($tool)
    {
		exit("<script language='javascript'>window.location.href='./?mod=buy&tid=".$tool['tid']."';</script>");
    }
}

$cid = intval($_GET['cid']);
if(!$cid && !empty($conf['defaultcid']) && $conf['defaultcid']!=='0'){
	$cid = intval($conf['defaultcid']);
}
$ar_data = [];
$classhide = explode(',',$siterow['class']);
$re = $DB->query("SELECT * FROM `shua_class` WHERE `active` = 1 AND cidr=0 ORDER BY `sort` ASC ");

$qcid = "";
$cat_name = "";
while ($res = $re->fetch()) {
    if($is_fenzhan && in_array($res['cid'], $classhide))continue;
    if($res['cid'] == $cid){
    	$cat_name=$res['name'];
    	$qcid = $cid;
    }
    $ar_data[] = $res;
}


$class_show_num = intval($conf['index_class_num_style'])?intval($conf['index_class_num_style']):2; //分类展示几组


	$url=$_SERVER['HTTP_HOST'];
	$approw = $DB->find('apps','*',['domain'=>$url]);
	$id = $approw['id'];
	$appurl = '/?mod=app&id='.$id;
?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 102.4px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title>最新上架</title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>template/storenews/user/yangshi/foxui1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/class.css">
    <link rel="stylesheet" type="text/css" href="//cdn.staticfile.org/layui/2.5.7/css/layui.css">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">


    <style>html{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>
    </head>
<style type="text/css">
    body {
        position: absolute;;

        margin: auto;
    }
    .fui-page.fui-page-from-center-to-left,
    .fui-page-group.fui-page-from-center-to-left,
    .fui-page.fui-page-from-center-to-right,
    .fui-page-group.fui-page-from-center-to-right,
    .fui-page.fui-page-from-right-to-center,
    .fui-page-group.fui-page-from-right-to-center,
    .fui-page.fui-page-from-left-to-center,
    .fui-page-group.fui-page-from-left-to-center {
        -webkit-animation: pageFromCenterToRight 0ms forwards;
        animation: pageFromCenterToRight 0ms forwards;
    }
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
    .fui-goods-item .detail .price .buy {
        color: #fff;
        background: #1492fb;
        border-radius: 3px;
        line-height: 1.1rem;
    }
    .fui-goods-item .detail .sale {
        height: 1.7rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: 0.65rem;
        line-height: 0.9rem;
    }
     @media only screen and (max-width : 375px) {
        .hotxy::-webkit-scrollbar {
            display: none !important;
        }
    }
    .goods-category {
        display: flex;
        background: #fff;
        flex-wrap: wrap;
    }

    .goods-category li {
        width: 25%;
        list-style: none;
        margin: 0.4rem 0;
        color: #666;
        font-size: 0.65rem;

    }

    .goods-category li.active p {
        background: #1492fb;
        color: #fff;
    }

    body {
        padding-bottom: constant(safe-area-inset-bottom);
        padding-bottom: env(safe-area-inset-bottom);
    }

    .goods-category li p {
        width: 4rem;
        height: 2rem;
        text-align: center;
        line-height: 2rem;
        border: 1px solid #ededed;
        margin: 0 auto;
        -webkit-border-radius: 0.1rem;
        -moz-border-radius: 0.1rem;
        border-radius: 0.1rem;
    }
    .footer ul {
        display: flex;
        width: 100%;
        margin: 0 auto;
    }

    .footer ul li {
        list-style: none;
        flex: 1;
        text-align: center;
        position: relative;
        line-height: 2rem;
    }

    .footer ul li:after {
        content: '';
        position: absolute;
        right: 0;
        top: .8rem;
        height: 10px;
        border-right: 1px solid #999;


    }
 .hotxy {
        position: relative;
    }
    .hotxy .hotxy-item{
        font-size: 0.62rem;
        display: inline-block;
        width: 20%;
        text-align: center;
        margin-bottom: 10px;

    }
    .hotxy .hotxy-item-index{
        border-bottom: 3px solid #ff8000;
        font-weight: 700;

    }
    .footer ul li:nth-last-of-type(1):after {
        display: none;
    }

    .footer ul li a {
        color: #999;
        display: block;
        font-size: .6rem;
    }
.fui-goods-group.block .fui-goods-item .image {
     width: 100%; 
     margin: unset; 
     padding-bottom: unset; 
     <?php if(checkmobile()){ ?>
        height:5.2rem;
     <?php }else{ ?>
        height:5.2rem;
     <?php } ?>
     

}
.ico img {
    filter: saturate(100%) !important;
    border-radius: 50%;
    height: 35px;
    width: 35px;
    border: 1px solid #fff;
    box-shadow: 2px 2px 2px #fec6a1, -2px -1px 2px #fff1dc;
}
.layui-flow-more{
        width: 100%;
    float: left;
}
.fui-goods-group .fui-goods-item .image img{
    border-radius:10px;    
}
.fui-goods-group .fui-goods-item .detail .minprice {
    font-size: .6rem;
}
.fui-goods-group .fui-goods-item .detail .name{
    height: 2.5rem;
    font-weight: 600;
}

.swiper-pagination-bullet {
  width: 20px;
  height: 20px;
  text-align: center;
  line-height: 20px;
  font-size: 12px;
  color: #000;
  opacity: 1;
  background: rgba(0, 0, 0, 0.2);
}

.swiper-pagination-bullet-active {
  color: #fff;
  background: #ed414a;
}
.swiper-pagination{
    position: unset;
}
.swiper-container{
    --swiper-theme-color: #ff6600;/* 设置Swiper风格 */
    --swiper-navigation-color: #007aff;/* 单独设置按钮颜色 */
    --swiper-navigation-size: 18px;/* 设置按钮大小 */
}
.goods_sort {
    position: relative;
    width: 100%;

    -webkit-box-align: center;
    padding: .4rem 0;
    background: #fff;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
}

.goods_sort:after {
    content: " ";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    border-bottom: 1px solid #e7e7e7;
}

.goods_sort .item {
    position: relative;
    width: 1%;
    display: table-cell;
    text-align: center;
    font-size: 0.6rem;
    border-left: 1px solid #e7e7e7;
    color: #766e58;
}
.goods_sort .item .sorting {
    width: .2rem;
    height: .2rem;
    position: relative;
}
.goods_sort .item:first-child {
    border: 0;
}

.goods_sort .item.on .text {
    color: #766e58;
}
.goods_sort .item .sorting .icon {
    /*font-size: 11px;*/
    position: absolute;
    -webkit-transform: scale(0.5);
    -ms-transform: scale(0.5);
    transform: scale(0.5);
    color: #cccccc;
}

.goods_sort .item-price .sorting .icon-sanjiao1 {
    top: .13rem;
    left: 2;
}

.goods_sort .item-price .sorting .icon-sanjiao2 {
    top: -.13rem;
    left: 2;
}

    .goods_sort .item-price.DESC .sorting .icon-sanjiao1 {
        color: #766e58
    }

    .goods_sort .item-price.ASC .sorting .icon-sanjiao2 {
        color: #766e58
    }

    .content-slide .shop_active .icon-title {
        color: #fc7032;
    }
    .content-slide .shop_active .mbg {
        background-color: #eff5fd;
    }

    .content-slide .shop_active img {
        filter: saturate(100%);
    }

.xz {
    background-color: #3399ff;
    color: white !important;
    border-radius: 5px;
}
.tab_con > ul > li.layui-this{
    background: linear-gradient(to right, #73b891, #53bec5);
    color: #fff;
    border-radius: 6px;
    text-align: center;
}

    .fui-notice:after {

        border: 0px solid #fc7032;

    }

    .fui-notice:before {

        border: 0px solid #fc7032;
    }

    #audio-play #audio-btn {
        width: 44px;
        height: 44px;
        background-size: 100% 100%;
        position: fixed;
        bottom: 5%;
        right: 6px;
        z-index: 111;
    }

    #audio-play .on {
        background: url('assets/img/music_on.png') no-repeat 0 0;
        -webkit-animation: rotating 1.2s linear infinite;
        animation: rotating 1.2s linear infinite;
    }

    #audio-play .off {
        background: url('assets/img/music_off.png') no-repeat 0 0
    }

    @-webkit-keyframes rotating {
        from {
            -webkit-transform: rotate(0);
            -moz-transform: rotate(0);
            -ms-transform: rotate(0);
            -o-transform: rotate(0);
            transform: rotate(0)
        }
        to {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg)
        }
    }

    @keyframes rotating {
        from {
            -webkit-transform: rotate(0);
            -moz-transform: rotate(0);
            -ms-transform: rotate(0);
            -o-transform: rotate(0);
            transform: rotate(0)
        }
        to {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg)
        }
    }


    @media only screen and (max-width : 375px) {
        .hotxy::-webkit-scrollbar {
            display: none !important;
        }
    }
    ::-webkit-scrollbar-thumb {

        background-color: #b0b0b0;
    }


    .search{
        text-align: left;
    }
    .search[placeholder]{
        font-size: 0.55rem;
    }

    .display-row {
        display: flex;
        flex-direction: row;
    }

    .display-column {
        display: flex;
        flex-direction: column;
    }
    
<?php if(checkmobile()){ ?>
    .icon-title {
        font-size: 0.55rem;
        margin: 3px 0 5px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #222;
    }
<?php }else{ ?>
    .icon-title {
        font-size: 0.52rem;
        margin: 5px 0 5px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #222;
    }
<?php }?>
    .justify-center {
        justify-content: center;
    }

    .justify-between {
        justify-content: space-between;
    }
    .justify-around {
        justify-content: space-around;
    }

    .flex-wrap {
        flex-wrap: wrap;

    }
    .flex-nowrap{
        flex-wrap: nowrap;
    }
    .align-center {
        align-items: center;
    }
    .hotxy {
        position: relative;
    }
    .hotxy .hotxy-item{
        font-size: 0.62rem;
        display: inline-block;
        width: 20%;
        text-align: center;
        margin-bottom: 10px;

    }
    .hotxy .hotxy-item-index{
        border-bottom: 3px solid #ff8000;
        font-weight: 700;

    }

    .tab-top font{
        font-size: 0.6rem;

    }
    .tab-top-l-icon{
        color: #f4b538;
        font-size: 0.65rem
    }
    .tab-top-r-icon{
        color: #000;
        font-size: 0.65rem;
        font-weight: 800;
    }
    .tab-top-r{
        border-left: 1px solid #dddddd;
        padding-left: 10px;
    }
    .tab-bottom{
        width: 100%;

        padding: 0.3rem 0rem;
        font-size: 0.6rem;
        position: absolute;
        top: 2.0rem;
        left: 0;
        z-index: 1025;
        background: #fff;
        box-shadow: 0px 3px 5px #e2dfdf;
    }
    .tab-bottom-none{
        display:none;
    }
    .tab-bottom-item{
        padding: 0.2rem 0.6rem;
        box-shadow: 5px 5px 5px #e1e1e1;
        border-radius: 50px;
        margin-top: 5px;
        margin-left: 5px;
        cursor: pointer;
    }
    .tab-bottom-item-index{

        background: #1195da;
        color: #fff;

    }
    .tab-top {

        position: relative;
        height: 1.8rem;
        width: calc(100% - 0.8rem);
        padding: 0  0.5rem;
        margin: 0 auto;

        border: 1px solid #dddddd;
        background: #ffffff;
        /*filter: drop-shadow(0 0 5px rgba(0, 0, 0, .1));*/
        overflow: visible;
        border-radius: 10px;

    }
    
#audio-play #audio-btn{width: 45px;height: 45px; background-size: 100% 100%;position:fixed;bottom:8%;right:20px;z-index:111;}
#audio-play .on{background: url('assets/img/music_on.png') no-repeat 0 0;-webkit-animation: rotating 1.2s linear infinite;animation: rotating 1.2s linear infinite;}
#audio-play .off{background:url('assets/img/music_off.png') no-repeat 0 0}
@-webkit-keyframes rotating{from{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}to{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes rotating{from{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}to{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}
</style>
 <style>
 body {
    width: 100%;
    max-width: 550px;
    margin: auto;
    background: #fff;
    line-height: 24px;
    font: 14px Helvetica Neue,Helvetica,PingFang SC,Tahoma,Arial,sans-serif;
 
}
    .fullscreen-iframe {
        z-index: 999999;
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: none;
    }
            .hometitle{
       width: 100%;
    text-align: center;
    color: #fff;
    font-size: 24px;
    font-weight: 550;
    border-radius: 0 0 30px 30px;
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
    a:hover {
    color: #ff8000;
    }
    
 .rob_st1 {
    width: 38%;
    height: 29px;
    position: absolute;
    bottom: 0px;
    right: 0px;
    color: #33ab3b;
}
 .rob_st2 {
    width: 38%;
    height: 29px;
    position: absolute;
    bottom: 0px;
    right: 0px;
    color: #ec3b4c;
}
 .rob_st3 {
    width: 38%;
    height: 29px;
    position: absolute;
    bottom: 0px;
    right: 0px;
    color: #828080;
}
 .rob_st4 {
    width: 38%;
    height: 29px;
    position: absolute;
    bottom: 0px;
    right: 0px;
    color: #828080;
}
.fui-goods-group.three .fui-goods-item {
    width: 33%;
    border-radius: 2px;
    border: .1px solid #f2f2f2;+
}
  </style>
</head>

<iframe class="fullscreen-iframe" id="my-iframe" src=""></iframe>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 550px;">
<div id="body">
	    <div style="position: fixed;    z-index: 100;    top: 30px;    left: 20px;       color: white;    padding: 2px 8px;      background-color: rgba(0,0,0,0.4);    border-radius: 5px;display: none" id="xn_text">
    </div>
    <div class="fui-page-group " style="height: auto">
        <div class="fui-page  fui-page-current " style="height:auto; overflow: inherit">
            <div class="fui-content navbar" id="container" style="background-color: #fafafc;overflow: inherit">

<!--↓商品显示↓-->

                <div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
                    <ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;">
                    </ul>
                </div>


                <div class="fui-goods-group block three" style="background: #ffffff;" id="goods-list-container">
                    <div class="flow_load">
                        <div id="goods_list"></div>
                    </div>
                    <div class="footer" style="width:100%; margin-top:0.5rem;margin-bottom:2.5rem;display: block;">
                        
        </div>
        
        </div>



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
  <a href="./"><i class="fa fa-windows" style="font-size: 17px;"></i><br>首页</a>
  <a href="./?mod=fenlei"><i class="fa fa-table" style="font-size: 17px;"></i><br>分类</a>
  <a href="./?mod=duanju"><i class="fa fa-youtube-play" style="font-size: 17px;"></i><br>短剧</a>
  <?php  if($userrow['power']==0 || $userrow['power']==1){?>
  <a href="user/upgrade.php"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>升级</a>
  <?php }?>
  <?php  if( $userrow['power']==2){?>
  <a href="" style="color:#ff7c33;"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>最新上架</a>
  <?php }?>
  <a href="./?mod=query"><i class="fa fa-list-ol" style="font-size: 17px;"></i><br>订单</a>
  <a href="./user/"><i class="fa fa-github-alt" style="font-size: 17px;"></i><br>会员中心</a>
</div>
<!--导航结束-->
    </div>
</div>

<script src="<?php echo $cdnpublic?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/foxui.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/layui.flow.js"></script>

<script type="text/javascript">
var isModal=<?php echo empty($conf[''])?'false':'true';?>;
var homepage=true;
var hashsalt=<?php echo $addsalt_js?>;
$(function() {
	$("img.lazy").lazyload({effect: "fadeIn"});
	$('a[data-toggle="popover"]').popover();
});
$(".catname_c").hide();
$("#classtab").hide();
$(".hotxy").hide();
	$("#tabtopr").click(function(){
	 $("#classtab").toggle();

	});
function ifbox(newUrl){
      
      $('.fullscreen-iframe').attr('src', newUrl); // 替换 iframe 的网址
      $('.fullscreen-iframe').css('display', 'block'); // 显示 iframe
}
window.addEventListener('message', function(event) {
  if (event.data === 'closeIframe') {
    // 执行关闭操作
   $('.fullscreen-iframe').css('display', 'none');
  }
});
</script>
  
  
<script type="text/javascript">
var _0xodv='jsjiami.com.v7';var _0x2d53c0=_0x57ff;if(function(_0x101a29,_0x44c8ea,_0x17bbc9,_0x4b9a8d,_0x5542d2,_0x287662,_0x473c38){return _0x101a29=_0x101a29>>0x6,_0x287662='hs',_0x473c38='hs',function(_0x41c6da,_0x106cff,_0x2b50a0,_0x653576,_0x5487fa){var _0x21077d=_0x57ff;_0x653576='tfi',_0x287662=_0x653576+_0x287662,_0x5487fa='up',_0x473c38+=_0x5487fa,_0x287662=_0x2b50a0(_0x287662),_0x473c38=_0x2b50a0(_0x473c38),_0x2b50a0=0x0;var _0x5ef996=_0x41c6da();while(!![]&&--_0x4b9a8d+_0x106cff){try{_0x653576=-parseInt(_0x21077d(0x452,'y3sf'))/0x1*(-parseInt(_0x21077d(0x222,'k6LO'))/0x2)+-parseInt(_0x21077d(0x49d,']SCf'))/0x3*(parseInt(_0x21077d(0x407,'NTao'))/0x4)+-parseInt(_0x21077d(0x373,'WuW7'))/0x5*(parseInt(_0x21077d(0x350,'7V2s'))/0x6)+-parseInt(_0x21077d(0x349,')L29'))/0x7*(-parseInt(_0x21077d(0x3a9,'Arm#'))/0x8)+parseInt(_0x21077d(0x39e,'CXPH'))/0x9+parseInt(_0x21077d(0x323,'CdHM'))/0xa*(-parseInt(_0x21077d(0x307,'$8RX'))/0xb)+parseInt(_0x21077d(0x43e,'T7pT'))/0xc;}catch(_0x427add){_0x653576=_0x2b50a0;}finally{_0x5487fa=_0x5ef996[_0x287662]();if(_0x101a29<=_0x4b9a8d)_0x2b50a0?_0x5542d2?_0x653576=_0x5487fa:_0x5542d2=_0x5487fa:_0x2b50a0=_0x5487fa;else{if(_0x2b50a0==_0x5542d2['replace'](/[UWPJNrGFSKeYkBLIq=]/g,'')){if(_0x653576===_0x106cff){_0x5ef996['un'+_0x287662](_0x5487fa);break;}_0x5ef996[_0x473c38](_0x5487fa);}}}}}(_0x17bbc9,_0x44c8ea,function(_0x217ec4,_0x285101,_0x330755,_0x3be68b,_0x346e8b,_0x326ab2,_0x531b66){return _0x285101='\x73\x70\x6c\x69\x74',_0x217ec4=arguments[0x0],_0x217ec4=_0x217ec4[_0x285101](''),_0x330755='\x72\x65\x76\x65\x72\x73\x65',_0x217ec4=_0x217ec4[_0x330755]('\x76'),_0x3be68b='\x6a\x6f\x69\x6e',(0x16e402,_0x217ec4[_0x3be68b](''));});}(0x3100,0xed4b4,_0x2894,0xc6),_0x2894){}var template_virtualdata=$('input[name=_template_virtualdata]')[_0x2d53c0(0x476,'K$RD')](),template_showsales=$(_0x2d53c0(0x1ee,'HNwL'))['val'](),curr_time=$('input[name=_curr_time]')['val']();$(function(){var _0x537c7b=_0x2d53c0,_0x48a443={'vfmpw':_0x537c7b(0x435,'SD[P'),'EyXWI':function(_0x52043f,_0x2fb991){return _0x52043f(_0x2fb991);},'VIGVd':_0x537c7b(0x3dc,'SD[P'),'bmSlj':function(_0x42a8f7,_0x2af58c){return _0x42a8f7(_0x2af58c);},'KSqzZ':function(_0x3c0497,_0xeabdce){return _0x3c0497==_0xeabdce;},'GdNfW':_0x537c7b(0x49f,'9VXc'),'OoZyA':_0x537c7b(0x47b,'I!5#'),'fwAZe':_0x537c7b(0x49a,'QwXV'),'xdNGy':_0x537c7b(0x28a,'k6LO'),'ieSBx':_0x537c7b(0x439,'CXPH'),'BKzHI':function(_0x5ad91e,_0x2b4ce3){return _0x5ad91e(_0x2b4ce3);},'iJAjV':_0x537c7b(0x3c2,'qfzX'),'VPfLI':function(_0x5d22eb){return _0x5d22eb();},'UmSbh':function(_0x421cad,_0x35a6cf){return _0x421cad+_0x35a6cf;},'SkOoM':function(_0x3114f5,_0x34e15d){return _0x3114f5+_0x34e15d;},'iZDBY':function(_0x272c29,_0x234cf0){return _0x272c29+_0x234cf0;},'UVZbm':'<span\x20class=\x22','ogUYx':_0x537c7b(0x20e,'$hk5'),'LzkbZ':_0x537c7b(0x3ed,'q7n('),'GYbxm':_0x537c7b(0x457,'SbPH'),'GIvFZ':_0x537c7b(0x43d,'#hOG'),'MoJKt':_0x537c7b(0x378,'WuW7'),'vQvsn':'height','wOGbr':function(_0x581a42,_0x176bf0){return _0x581a42*_0x176bf0;},'MVJWe':function(_0x356bb9,_0xa2d3ce){return _0x356bb9/_0xa2d3ce;},'JhJKR':function(_0x2e2df4,_0x7f1da){return _0x2e2df4!==_0x7f1da;},'KIvKY':_0x537c7b(0x1d9,'I!5#'),'AxWfD':_0x537c7b(0x254,'8AcX'),'eTEFR':function(_0x9923ee,_0x1ac443,_0x57d9cf){return _0x9923ee(_0x1ac443,_0x57d9cf);},'iPavL':_0x537c7b(0x2ac,'7V2s'),'hVFNY':_0x537c7b(0x249,'QwXV'),'kmwUo':function(_0x400469,_0xc1eaf5){return _0x400469+_0xc1eaf5;},'kiqFn':_0x537c7b(0x325,'#hOG'),'grUMJ':_0x537c7b(0x28b,'ybLF'),'GVjQz':_0x537c7b(0x313,'Qn(y'),'pbCum':function(_0x371238,_0x38ee8e){return _0x371238>=_0x38ee8e;},'FfQCE':function(_0x241044,_0x357663){return _0x241044===_0x357663;},'FvevB':'xsBBt','RTrmH':_0x537c7b(0x32d,'Fi0E'),'KOzVH':_0x537c7b(0x2f5,'k6LO'),'UztNJ':_0x537c7b(0x3cc,'vYLK'),'SxXkn':function(_0x1845e1,_0x4ca442){return _0x1845e1!==_0x4ca442;},'TBjvM':_0x537c7b(0x1dc,'y3sf'),'LlikN':_0x537c7b(0x3a0,'8AcX'),'CtYUW':function(_0x49f204,_0x33eaef){return _0x49f204(_0x33eaef);},'efphF':'name','JavNw':_0x537c7b(0x2e4,'$hk5'),'kidQY':_0x537c7b(0x3b2,')L29'),'FMmRw':function(_0x26f51e,_0x1eba33){return _0x26f51e+_0x1eba33;},'NugMa':function(_0x4d8c3b,_0x5cfced){return _0x4d8c3b+_0x5cfced;},'kxktj':_0x537c7b(0x1e7,'w5SR'),'effMP':_0x537c7b(0x455,'w5SR'),'brrSm':_0x537c7b(0x337,'f7v#'),'PGjqE':_0x537c7b(0x231,'I!5#'),'AwHBd':function(_0x2b36fd,_0x17b7f7){return _0x2b36fd+_0x17b7f7;},'PyQzE':function(_0x113fe7,_0x1207a2){return _0x113fe7(_0x1207a2);},'KjnhX':_0x537c7b(0x39d,'$hk5'),'npqvp':function(_0x5d5673,_0xc32db3){return _0x5d5673(_0xc32db3);},'fAKva':_0x537c7b(0x35b,'CXPH'),'wyEfL':_0x537c7b(0x2e8,'CXPH'),'DihjC':_0x537c7b(0x390,'Ddep'),'NWuff':function(_0x20261b,_0x425f5f){return _0x20261b(_0x425f5f);},'ZRmbK':function(_0x2063aa,_0x2a0369){return _0x2063aa+_0x2a0369;},'XEPLW':'./?cid=','Hcowy':_0x537c7b(0x227,'FnhG'),'JdfXr':_0x537c7b(0x272,'SD[P'),'omdIY':function(_0x3dad87,_0x25d688){return _0x3dad87(_0x25d688);},'iNUWp':_0x537c7b(0x419,'HNwL'),'QSVEa':function(_0x5df7b0,_0x3be2bc){return _0x5df7b0+_0x3be2bc;},'uaHoQ':function(_0x43e170,_0x3cc348){return _0x43e170(_0x3cc348);},'IHpEI':function(_0x582ecd,_0x4fd6d2){return _0x582ecd(_0x4fd6d2);},'Jgbxt':_0x537c7b(0x201,'I#$e'),'VvVub':function(_0x4df2d4,_0x3dc0f6){return _0x4df2d4(_0x3dc0f6);},'FqqNf':function(_0x20ea38,_0x366500){return _0x20ea38===_0x366500;},'dauVW':_0x537c7b(0x475,'0f%e'),'lxuqc':'8|7|2|0|6|4|3|5|1|9','Echsd':function(_0x31aead,_0xf5e901){return _0x31aead(_0xf5e901);},'ssRRb':_0x537c7b(0x2cd,'$hk5'),'PkxfY':function(_0x2f2fb9,_0x4dfc2f){return _0x2f2fb9(_0x4dfc2f);},'qEEcN':_0x537c7b(0x1cf,'Y%a&'),'teCTQ':'正在获取数据','RkHTH':_0x537c7b(0x411,'B4ke'),'lcvbs':_0x537c7b(0x420,'ybLF'),'nnfch':_0x537c7b(0x347,'OQ$p'),'ChNVy':function(_0x4b8543,_0x269d7e){return _0x4b8543(_0x269d7e);},'sthDk':function(_0x23862e,_0x406f61){return _0x23862e+_0x406f61;},'QFCmA':function(_0x46cb99,_0x3bcb58){return _0x46cb99+_0x3bcb58;},'WZoxG':_0x537c7b(0x24d,'Zq#V'),'mOtOX':'</font>共有','KKQJj':_0x537c7b(0x416,'T7pT'),'JXCFH':_0x537c7b(0x304,'vYLK'),'odXaQ':function(_0x4cb647,_0x52d23a){return _0x4cb647>_0x52d23a;},'xrXEA':'.swiper-wrapper\x20.content-slide','lUfEf':_0x537c7b(0x413,'$hk5'),'oiLte':_0x537c7b(0x47c,'$8RX'),'LLquN':function(_0x267cc8,_0x2ad1d4){return _0x267cc8(_0x2ad1d4);},'rscxh':_0x537c7b(0x2a1,'fgdg'),'mzFpd':function(_0xd7c9a4,_0x37ab43){return _0xd7c9a4(_0x37ab43);},'dzkOA':_0x537c7b(0x369,'9VXc'),'fIpYQ':function(_0x46e95c,_0x49cc81){return _0x46e95c(_0x49cc81);},'BiVnq':'#listblock','gaeSJ':function(_0x78b5f5,_0x2bea06){return _0x78b5f5===_0x2bea06;},'VXoyo':_0x537c7b(0x298,'f7v#'),'RDRDS':function(_0x1e382c,_0xf2686b){return _0x1e382c(_0xf2686b);},'mcwEB':function(_0x86e59d,_0x4f93e7){return _0x86e59d===_0x4f93e7;},'hwgrO':function(_0x5a7abb,_0x2eb8d4){return _0x5a7abb(_0x2eb8d4);},'siGeh':'iPhone','fBqEF':function(_0x2cabd1,_0x1bc885){return _0x2cabd1(_0x1bc885);},'GmoSS':'.fui-navbar','pdTAE':_0x537c7b(0x3d2,'8inc'),'keDRZ':function(_0x33c9e2,_0x3f770b){return _0x33c9e2(_0x3f770b);}};$(_0x537c7b(0x23b,'Vr0e'))['on'](_0x48a443[_0x537c7b(0x1ec,'Y%a&')],function(){var _0x56fa58=_0x537c7b,_0x1a36d8={'RXeRb':function(_0xb948d2,_0x374a26){return _0xb948d2+_0x374a26;},'jdGRO':_0x48a443['vfmpw']},_0x5e83e1=_0x48a443['EyXWI']($,this)[_0x56fa58(0x3cb,'OQ$p')](_0x48a443['VIGVd']);if(!_0x5e83e1)return![];var _0x988ca=_0x48a443[_0x56fa58(0x399,'ybLF')]($,this)[_0x56fa58(0x36a,'I#$e')]('sort');if(_0x48a443['KSqzZ'](_0x988ca,_0x48a443[_0x56fa58(0x406,'q7n(')]))var _0xc56ea0=_0x48a443[_0x56fa58(0x203,'QwXV')];else{if(_0x48a443['fwAZe']===_0x48a443['fwAZe'])var _0xc56ea0=_0x48a443['GdNfW'];else _0x19475f=_0x1a36d8[_0x56fa58(0x1dd,'7V2s')](_0x56fa58(0x20b,'WuW7'),_0x4900f1)+_0x1a36d8[_0x56fa58(0x402,'qfzX')];}_0x48a443[_0x56fa58(0x445,'t*7c')]($,_0x56fa58(0x45c,'#hOG'))[_0x56fa58(0x3df,'qfzX')](_0x48a443[_0x56fa58(0x389,']SCf')],_0x56fa58(0x33c,'w5SR')),_0x48a443['bmSlj']($,this)['addClass'](_0x988ca),_0x48a443['bmSlj']($,this)[_0x56fa58(0x479,'K$RD')](_0x48a443[_0x56fa58(0x473,'OQ$p')],_0xc56ea0),_0x48a443[_0x56fa58(0x22d,'Zq#V')]($,_0x56fa58(0x3fc,'$8RX'))[_0x56fa58(0x44d,'9VXc')]('on'),_0x48a443[_0x56fa58(0x312,'QwXV')]($,this)['addClass']('on'),_0x48a443[_0x56fa58(0x422,'WuW7')]($,'input[name=_sort_type]')[_0x56fa58(0x32f,'I!5#')](_0x5e83e1),_0x48a443[_0x56fa58(0x358,'I#$e')]($,_0x48a443[_0x56fa58(0x401,'qfzX')])['val'](_0x988ca),_0x48a443[_0x56fa58(0x442,'$hk5')](get_goods);});if(_0x48a443[_0x537c7b(0x21d,'Zq#V')](_0x48a443[_0x537c7b(0x2c2,'Zq#V')]($,_0x48a443['xrXEA'])[_0x537c7b(0x39f,'X6$#')],0x1)){var _0x3ab11d=new Swiper('.swiper-container',{'pagination':{'el':_0x537c7b(0x48b,'NTao'),'clickable':!![],'renderBullet':function(_0x2fefc2,_0x1d326c){var _0x44090d=_0x537c7b;return _0x48a443['UmSbh'](_0x48a443['UmSbh'](_0x48a443[_0x44090d(0x31f,'Vr0e')](_0x48a443[_0x44090d(0x47f,'ybLF')](_0x48a443[_0x44090d(0x48f,'Zq#V')],_0x1d326c),'\x22>'),_0x48a443[_0x44090d(0x238,'0f%e')](_0x2fefc2,0x1)),_0x48a443[_0x44090d(0x342,'@$OX')]);}},'navigation':{'nextEl':_0x48a443['lUfEf'],'prevEl':_0x48a443[_0x537c7b(0x3e4,'M!)h')]},'mousewheel':!![],'keyboard':!![]});_0x48a443[_0x537c7b(0x37d,'k6LO')]($,_0x48a443['lUfEf'])[_0x537c7b(0x3d0,'CXPH')](),_0x48a443[_0x537c7b(0x24e,'Qn(y')]($,_0x537c7b(0x30c,'T7pT'))[_0x537c7b(0x423,'NTao')]();}_0x48a443[_0x537c7b(0x2f8,'UyL*')](jQuery,function(_0x3b5771){var _0x31a4ee=_0x537c7b;_0x48a443[_0x31a4ee(0x276,'k6LO')]('RkEFt',_0x48a443[_0x31a4ee(0x266,'I!5#')])?_0x37d0c2():_0x48a443['EyXWI'](_0x3b5771,window)[_0x31a4ee(0x1e8,'OQ$p')](function(){var _0x37c207=_0x31a4ee,_0x4cf6ee=_0x48a443['bmSlj'](_0x3b5771,_0x48a443[_0x37c207(0x370,'Y%a&')])[_0x37c207(0x27a,'$8RX')]();_0x48a443[_0x37c207(0x36b,'WuW7')](_0x3b5771,_0x48a443['GYbxm'])[_0x37c207(0x368,'$hk5')](_0x48a443[_0x37c207(0x31a,'$8RX')],_0x4cf6ee),_0x48a443['BKzHI'](_0x3b5771,_0x48a443[_0x37c207(0x3c8,'NTao')])[_0x37c207(0x43a,'q7n(')](_0x48a443['vQvsn'],_0x48a443[_0x37c207(0x44b,'ybLF')](0xc8,_0x48a443[_0x37c207(0x341,'#hOG')](_0x4cf6ee,0x280)));})[_0x31a4ee(0x453,'I!5#')]();});_0x48a443['KSqzZ'](template_virtualdata,0x1)&&(_0x48a443[_0x537c7b(0x321,'SD[P')](_0x48a443['rscxh'],_0x48a443[_0x537c7b(0x1eb,'UyL*')])?_0x5a96f5['shopimg']=_0x48a443['AxWfD']:_0x48a443[_0x537c7b(0x372,'ybLF')](ka));get_goods(),_0x48a443[_0x537c7b(0x277,'QwXV')]($,'.get_cat')['on'](_0x48a443['JXCFH'],function(){var _0x245942=_0x537c7b,_0x2909a8={'fBmaN':function(_0xf1c6bd,_0x408cd6,_0x2e0e26){var _0x179766=_0x57ff;return _0x48a443[_0x179766(0x3ce,'Arm#')](_0xf1c6bd,_0x408cd6,_0x2e0e26);},'lYsYQ':_0x48a443[_0x245942(0x38c,'Arm#')],'mQZwl':function(_0x3f30b8,_0x14a7c5){return _0x3f30b8(_0x14a7c5);},'SJUOB':_0x48a443[_0x245942(0x2db,'QwXV')],'kMkQf':function(_0x3d0cd7,_0x5ac33f){var _0x1d5772=_0x245942;return _0x48a443[_0x1d5772(0x2bf,'ybLF')](_0x3d0cd7,_0x5ac33f);},'XAvtY':_0x245942(0x408,'y3sf'),'EruQh':_0x48a443[_0x245942(0x353,'@$OX')],'MsqMU':_0x48a443['grUMJ'],'MFlSf':_0x48a443[_0x245942(0x31b,'Y%a&')],'lHouo':function(_0x2acdc5,_0x5d90ae){var _0x1e2b03=_0x245942;return _0x48a443[_0x1e2b03(0x49c,'8AcX')](_0x2acdc5,_0x5d90ae);},'icIsD':'.hotxy','DnArn':function(_0x6b2852,_0x521125){return _0x6b2852!=_0x521125;},'maMat':function(_0x27f54b,_0x3ba70c){var _0x5a3035=_0x245942;return _0x48a443[_0x5a3035(0x391,'Fi0E')](_0x27f54b,_0x3ba70c);},'emNFX':_0x48a443['FvevB'],'ZWqEP':_0x48a443[_0x245942(0x459,'Qn(y')],'bxaZV':_0x48a443[_0x245942(0x433,'@$OX')],'jptcx':function(_0x2a0f30,_0x3d3987){var _0x24fc01=_0x245942;return _0x48a443[_0x24fc01(0x361,'Qn(y')](_0x2a0f30,_0x3d3987);},'zmOQI':_0x48a443[_0x245942(0x2d1,'HNwL')],'zCyhv':function(_0x38d8ec,_0x7a6ca3){var _0x1ac96f=_0x245942;return _0x48a443[_0x1ac96f(0x42a,'OQ$p')](_0x38d8ec,_0x7a6ca3);},'QVyuO':function(_0x2e25a7,_0x221445){return _0x2e25a7!=_0x221445;},'AfKAM':function(_0x4a3d93,_0x5d741c){return _0x4a3d93(_0x5d741c);}};if(_0x48a443[_0x245942(0x2cc,'I!5#')](_0x48a443[_0x245942(0x1f7,'CdHM')],_0x48a443['LlikN'])){var _0x480b20=$(this)[_0x245942(0x2c4,'Fi0E')](_0x245942(0x2ad,'X6$#')),_0x33b475=_0x48a443[_0x245942(0x386,'SD[P')]($,this)['data'](_0x48a443[_0x245942(0x305,'qfzX')]);_0x48a443['BKzHI']($,_0x48a443['JavNw'])[_0x245942(0x301,'SbPH')](_0x33b475),$['ajax']({'type':_0x48a443[_0x245942(0x288,'8AcX')],'url':_0x48a443[_0x245942(0x237,'Fi0E')](_0x48a443[_0x245942(0x2f0,'$hk5')](_0x48a443[_0x245942(0x3e2,'vYLK')],_0x480b20),''),'dataType':_0x48a443[_0x245942(0x233,'Arm#')],'success':function(_0x1141ea){var _0x5d9397=_0x245942,_0x319c08={'hBmRq':function(_0x161c80,_0x33e660){return _0x2909a8['mQZwl'](_0x161c80,_0x33e660);},'swJHV':_0x2909a8['SJUOB'],'BRpAp':'#classtab','sZOFH':function(_0x40ccf8,_0xd49994){return _0x40ccf8+_0xd49994;},'xdKeP':function(_0x3b637b,_0x4fc0aa){return _0x3b637b+_0x4fc0aa;},'zrnPi':function(_0x40860d,_0x2a3af7){return _0x2909a8['kMkQf'](_0x40860d,_0x2a3af7);},'vJysz':function(_0x53b79f,_0x52c8a8){var _0xf3dcbc=_0x57ff;return _0x2909a8[_0xf3dcbc(0x379,'qfzX')](_0x53b79f,_0x52c8a8);},'rfnck':function(_0x22af08,_0x18b6ab){var _0x397cc9=_0x57ff;return _0x2909a8[_0x397cc9(0x3d1,'Qn(y')](_0x22af08,_0x18b6ab);},'GhSAi':_0x2909a8[_0x5d9397(0x380,'$hk5')],'OKKBv':_0x5d9397(0x219,'qfzX'),'JJaBu':_0x2909a8[_0x5d9397(0x3ae,'Y%a&')],'lAqtB':_0x2909a8[_0x5d9397(0x27d,'CXPH')],'ewoLS':_0x2909a8[_0x5d9397(0x2cb,'OQ$p')]};if(_0x2909a8[_0x5d9397(0x283,'B4ke')](_0x1141ea[_0x5d9397(0x2f7,'NTao')],0x1))_0x5d9397(0x2e3,'WuW7')!==_0x5d9397(0x20c,']SCf')?_0x2909a8[_0x5d9397(0x3d5,'8inc')]($,_0x2909a8[_0x5d9397(0x343,']SCf')])[_0x5d9397(0x375,'HyWi')]():_0x2909a8[_0x5d9397(0x2f2,'B4ke')](_0x4d6934,_0x2909a8['lYsYQ'],0x1770);else{_0x2909a8[_0x5d9397(0x293,'w5SR')]($,_0x5d9397(0x2b0,'0f%e'))[_0x5d9397(0x37b,'OQ$p')]();if(_0x2909a8[_0x5d9397(0x2d4,'Arm#')](_0x480b20,_0x1141ea[_0x5d9397(0x3e9,'$8RX')])){}else _0x2909a8['maMat'](_0x2909a8[_0x5d9397(0x21b,'$hk5')],_0x2909a8['ZWqEP'])?_0x319c08['hBmRq'](_0x45f8b4,_0x5d9397(0x3b6,'k6LO'))[_0x5d9397(0x35f,']SCf')](_0x319c08['swJHV']):(_0x2909a8['mQZwl']($,_0x2909a8[_0x5d9397(0x34c,'Zq#V')])[_0x5d9397(0x324,'I#$e')](null),$['each'](_0x1141ea['data'],function(_0xa34a68,_0x1539a4){var _0x3d4f13=_0x5d9397;$(_0x319c08['BRpAp'])[_0x3d4f13(0x485,'t*7c')](_0x319c08['sZOFH'](_0x319c08[_0x3d4f13(0x357,'@$OX')](_0x319c08[_0x3d4f13(0x451,'I#$e')](_0x319c08[_0x3d4f13(0x220,'k6LO')](_0x319c08[_0x3d4f13(0x286,'8inc')](_0x319c08[_0x3d4f13(0x330,'8inc')](_0x319c08['GhSAi'],_0x1539a4[_0x3d4f13(0x3b5,'I!5#')]),_0x319c08['OKKBv']),_0x1539a4[_0x3d4f13(0x414,'T7pT')])+_0x319c08[_0x3d4f13(0x387,'NTao')],_0x1539a4['cid']),_0x319c08[_0x3d4f13(0x2ba,'HyWi')]),_0x1539a4[_0x3d4f13(0x1e4,'QwXV')])+_0x319c08[_0x3d4f13(0x3e7,'7V2s')]);}));}}}),_0x48a443[_0x245942(0x3db,'9VXc')]($,_0x48a443[_0x245942(0x311,'$8RX')])['addClass'](_0x48a443[_0x245942(0x3d9,'Fi0E')]),$(_0x48a443[_0x245942(0x275,'Ddep')]('#',_0x480b20))[_0x245942(0x434,'Vu^V')](_0x48a443[_0x245942(0x39c,'t*7c')])[_0x245942(0x1d4,'SD[P')]('');var _0x33b475=_0x48a443[_0x245942(0x2eb,'0f%e')]($,this)[_0x245942(0x479,'K$RD')](_0x48a443[_0x245942(0x478,'t*7c')]);if(_0x48a443['BKzHI']($,this)['hasClass'](_0x48a443[_0x245942(0x48c,'K$RD')])){}_0x48a443[_0x245942(0x29d,'qfzX')]($,_0x245942(0x49b,'@$OX'))[_0x245942(0x345,'OQ$p')](_0x48a443['KjnhX']),_0x48a443[_0x245942(0x38d,'Fi0E')]($,_0x48a443[_0x245942(0x29f,'CXPH')])[_0x245942(0x26d,'vYLK')](''),_0x48a443[_0x245942(0x4a1,'Zq#V')]($,_0x48a443[_0x245942(0x388,'Vr0e')])[_0x245942(0x216,'HNwL')](_0x480b20),$(_0x48a443[_0x245942(0x1e2,'Bnhe')])[_0x245942(0x25a,'ybLF')](_0x33b475),get_goods(),_0x48a443[_0x245942(0x49e,'vYLK')]($,this)[_0x245942(0x41c,'Arm#')](_0x48a443[_0x245942(0x269,'w5SR')]),history[_0x245942(0x279,'I!5#')]({},null,_0x48a443[_0x245942(0x499,'$hk5')](_0x48a443['XEPLW'],_0x480b20));}else{var _0x4c4ee8={'IQpee':function(_0x38ced6,_0x5a8863){return _0x2909a8['kMkQf'](_0x38ced6,_0x5a8863);},'pqccT':function(_0x48e11e,_0x1ee7b0){var _0xeab67c=_0x245942;return _0x2909a8[_0xeab67c(0x3ad,'$8RX')](_0x48e11e,_0x1ee7b0);},'YbGUA':function(_0x182baa,_0x4f567f){var _0x1eb348=_0x245942;return _0x2909a8[_0x1eb348(0x2ca,'fgdg')](_0x182baa,_0x4f567f);},'Nizkv':_0x2909a8[_0x245942(0x335,')L29')],'xIUKa':_0x2909a8[_0x245942(0x393,')L29')],'VQGeD':_0x2909a8['EruQh'],'AeNwc':_0x2909a8['MsqMU'],'OdQyj':_0x245942(0x48a,'SbPH')};_0x2909a8[_0x245942(0x2a9,'Zq#V')](_0x57cc18,_0x245942(0x2b0,'0f%e'))[_0x245942(0x2a6,'Ddep')]();if(_0x2909a8[_0x245942(0x355,'CXPH')](_0x32ed0e,_0x44661d[_0x245942(0x46f,'CXPH')])){}else _0x2909a8['AfKAM'](_0xd44a8d,'#classtab')[_0x245942(0x250,'8AcX')](null),_0x7a0462[_0x245942(0x22e,'K$RD')](_0x4d1b8a[_0x245942(0x2b7,'Qn(y')],function(_0x25d4db,_0x3b5a59){var _0x4a7715=_0x245942;_0x2de015('#classtab')['append'](_0x4c4ee8[_0x4a7715(0x1f9,'HNwL')](_0x4c4ee8[_0x4a7715(0x25b,'T7pT')](_0x4c4ee8['pqccT'](_0x4c4ee8[_0x4a7715(0x34b,'vYLK')](_0x4c4ee8[_0x4a7715(0x3f3,'X6$#')](_0x4c4ee8[_0x4a7715(0x448,'Qn(y')]+_0x3b5a59['cid'],_0x4c4ee8['xIUKa'])+_0x3b5a59[_0x4a7715(0x38a,'vYLK')],_0x4c4ee8[_0x4a7715(0x3a1,'t*7c')])+_0x3b5a59[_0x4a7715(0x1e9,'B4ke')],_0x4c4ee8[_0x4a7715(0x29b,'FnhG')]),_0x3b5a59['name']),_0x4c4ee8[_0x4a7715(0x24b,'7V2s')]));});}}),_0x48a443[_0x537c7b(0x463,'T7pT')]($,_0x537c7b(0x382,'HNwL'))['on']('click',function(){var _0x2bc990=_0x537c7b;if(_0x48a443['Hcowy']===_0x48a443['JdfXr'])var _0xf0d0c5=_0x48a443[_0x2bc990(0x3da,'SD[P')];else{var _0x36ae7c=_0x48a443[_0x2bc990(0x31e,'!L]3')]($,this)['data'](_0x48a443[_0x2bc990(0x221,'Ddep')]);_0x48a443[_0x2bc990(0x2c3,'UyL*')]($,_0x2bc990(0x430,'fgdg'))[_0x2bc990(0x42e,'CdHM')](_0x48a443[_0x2bc990(0x362,'HNwL')]),_0x48a443[_0x2bc990(0x2b4,'0f%e')]($,_0x48a443[_0x2bc990(0x2d7,'qfzX')]('#',_0x36ae7c))['removeClass'](_0x48a443['PGjqE'])[_0x2bc990(0x3ff,'Ddep')]('');var _0xd8e258=_0x48a443[_0x2bc990(0x27e,'7V2s')]($,this)[_0x2bc990(0x464,'B4ke')](_0x48a443[_0x2bc990(0x3b7,'CdHM')]);if(_0x48a443['IHpEI']($,this)[_0x2bc990(0x259,'HNwL')](_0x48a443[_0x2bc990(0x29c,'!L]3')])){}_0x48a443[_0x2bc990(0x3db,'9VXc')]($,_0x48a443['Jgbxt'])[_0x2bc990(0x23d,')L29')](_0x48a443[_0x2bc990(0x3c7,'Arm#')]),_0x48a443[_0x2bc990(0x1d6,'ybLF')]($,_0x48a443[_0x2bc990(0x290,'WuW7')])[_0x2bc990(0x421,'8AcX')](''),_0x48a443['BKzHI']($,_0x48a443[_0x2bc990(0x228,'f7v#')])[_0x2bc990(0x3bc,'$8RX')](_0x36ae7c),$('input[name=_cidname]')[_0x2bc990(0x2c5,'@$OX')](_0xd8e258),_0x48a443[_0x2bc990(0x281,'Fi0E')](get_goods),_0x48a443[_0x2bc990(0x295,']SCf')]($,this)[_0x2bc990(0x273,'f7v#')]('shop_active'),history['replaceState']({},null,_0x48a443[_0x2bc990(0x2c8,'WuW7')]('./?cid=',_0x36ae7c));}}),_0x48a443[_0x537c7b(0x34e,'0f%e')]($,_0x48a443[_0x537c7b(0x2ce,'QwXV')])[_0x537c7b(0x257,'$8RX')](function(_0x130bea){var _0x30cafe=_0x537c7b,_0x2a0f84={'KtvtT':function(_0x38158b,_0x10bca2){return _0x48a443['VvVub'](_0x38158b,_0x10bca2);},'cdcyB':_0x30cafe(0x3f2,'HyWi'),'WogrA':function(_0x370916,_0x122d08){return _0x370916+_0x122d08;},'ambMq':function(_0x50995b,_0x2dc8b7){var _0x198739=_0x30cafe;return _0x48a443[_0x198739(0x278,'8inc')](_0x50995b,_0x2dc8b7);},'QaVAx':function(_0x3c8be9,_0x29a6fe){var _0x3033b7=_0x30cafe;return _0x48a443[_0x3033b7(0x460,'#hOG')](_0x3c8be9,_0x29a6fe);},'YYjue':function(_0x3a8f62,_0x4ed0c4){var _0x3e7e46=_0x30cafe;return _0x48a443[_0x3e7e46(0x2ed,'q7n(')](_0x3a8f62,_0x4ed0c4);},'LswkC':function(_0x37a0e4,_0x414a59){return _0x48a443['kmwUo'](_0x37a0e4,_0x414a59);},'LpXOh':_0x30cafe(0x41f,'Vu^V'),'PTtBL':_0x30cafe(0x32c,'f7v#'),'AnkTX':_0x48a443['grUMJ']};if(_0x48a443[_0x30cafe(0x363,'T7pT')]('TEYRs',_0x48a443[_0x30cafe(0x374,'k6LO')])){var _0x55fa48=_0x48a443[_0x30cafe(0x36f,'$hk5')][_0x30cafe(0x32e,'QwXV')]('|'),_0x49d52a=0x0;while(!![]){switch(_0x55fa48[_0x49d52a++]){case'0':_0x48a443[_0x30cafe(0x2d9,'M!)h')]($,_0x30cafe(0x440,'ybLF'))[_0x30cafe(0x404,'Arm#')]('');continue;case'1':document[_0x30cafe(0x44a,'K$RD')]['blur']();continue;case'2':_0x48a443['npqvp']($,_0x48a443[_0x30cafe(0x22c,'Fi0E')])[_0x30cafe(0x40e,'HyWi')]('');continue;case'3':_0x48a443[_0x30cafe(0x291,'Vr0e')]($,'.device\x20.content-slide\x20a')[_0x30cafe(0x360,'UyL*')](_0x48a443[_0x30cafe(0x2b6,'q7n(')]);continue;case'4':_0x48a443['Echsd']($,_0x48a443['ssRRb'])['hide']();continue;case'5':get_goods();continue;case'6':_0x48a443[_0x30cafe(0x255,'Y%a&')]($,_0x48a443[_0x30cafe(0x209,'Qn(y')])[_0x30cafe(0x301,'SbPH')](_0x48a443[_0x30cafe(0x403,'Qn(y')]);continue;case'7':if(_0x523129=='')return layer[_0x30cafe(0x3b1,'SD[P')](_0x48a443[_0x30cafe(0x428,'t*7c')]),![];continue;case'8':var _0x523129=_0x48a443[_0x30cafe(0x3fe,'$8RX')]($,_0x48a443[_0x30cafe(0x32a,'M!)h')])[_0x30cafe(0x2b9,')L29')]();continue;case'9':return![];}break;}}else _0x2a0f84[_0x30cafe(0x346,'HNwL')](_0x469525,_0x2a0f84[_0x30cafe(0x3be,'X6$#')])['append'](_0x2a0f84[_0x30cafe(0x303,'k6LO')](_0x2a0f84[_0x30cafe(0x262,'@$OX')](_0x2a0f84['WogrA'](_0x2a0f84[_0x30cafe(0x35a,'0f%e')](_0x2a0f84[_0x30cafe(0x1fa,'CdHM')](_0x2a0f84['YYjue'](_0x2a0f84[_0x30cafe(0x469,'SD[P')]('\x20<a\x20data-cid=\x22',_0xaec0db[_0x30cafe(0x48d,'UyL*')]),_0x2a0f84[_0x30cafe(0x38f,'qfzX')]),_0x2629ca[_0x30cafe(0x282,'NTao')]),_0x2a0f84[_0x30cafe(0x3b0,'Vr0e')]),_0x1c10bd[_0x30cafe(0x3af,'vYLK')]),_0x2a0f84[_0x30cafe(0x468,'K$RD')]),_0x2fd190['name'])+_0x30cafe(0x36d,'y3sf'));}),_0x48a443[_0x537c7b(0x3d8,'0f%e')]($,_0x48a443[_0x537c7b(0x37e,'Y%a&')])['on'](_0x48a443['JXCFH'],function(){var _0x2166b5=_0x537c7b;if(_0x48a443['lcvbs']!==_0x48a443[_0x2166b5(0x43f,'Vr0e')])return![];else{var _0x5b0c43=new Date();_0x5b0c43[_0x2166b5(0x427,'HyWi')](_0x5b0c43[_0x2166b5(0x4a3,'y3sf')]()+0x15180),$[_0x2166b5(0x24f,'q7n(')](_0x48a443[_0x2166b5(0x32b,'t*7c')],attr,{'expires':_0x5b0c43}),layer[_0x2166b5(0x466,'WuW7')](index);}});!$[_0x537c7b(0x22b,'OQ$p')]('op')&&(_0x48a443[_0x537c7b(0x34f,'T7pT')](_0x48a443[_0x537c7b(0x243,'FnhG')],'zBjlN')?(_0x48a443['RDRDS']($,_0x537c7b(0x36c,'q7n('))[_0x537c7b(0x339,'0f%e')](),$['cookie']('op',![],{'expires':0x1})):(_0x49fd55('.catname_c')[_0x537c7b(0x1da,'0f%e')](),_0x48a443['ChNVy'](_0x6046c8,_0x48a443[_0x537c7b(0x3a8,'#hOG')])['show'](),_0x48a443[_0x537c7b(0x295,']SCf')](_0x3ea519,_0x537c7b(0x1f8,'CXPH'))[_0x537c7b(0x202,'q7n(')](),_0x48a443[_0x537c7b(0x213,'t*7c')](_0x164782,_0x537c7b(0x244,'SD[P'))[_0x537c7b(0x431,'OQ$p')](_0x48a443[_0x537c7b(0x493,'q7n(')](_0x48a443[_0x537c7b(0x489,'M!)h')](_0x48a443[_0x537c7b(0x3f9,'vYLK')](_0x48a443[_0x537c7b(0x45d,'vYLK')](_0x48a443[_0x537c7b(0x1fb,'8inc')],_0x32cfb4),_0x48a443[_0x537c7b(0x444,'HNwL')]),_0x44371a[_0x537c7b(0x33e,'f7v#')]),_0x48a443['KKQJj']))));var _0x16f5b6=window[_0x537c7b(0x480,'T7pT')]&&_0x48a443['gaeSJ'](window['devicePixelRatio'],0x3)&&_0x48a443[_0x537c7b(0x36e,'I#$e')](window['screen'][_0x537c7b(0x450,'M!)h')],0x177)&&_0x48a443[_0x537c7b(0x397,'k6LO')](testUA,_0x48a443['siGeh']);_0x16f5b6&&window[_0x537c7b(0x1f2,'$hk5')]['length']<=0x2?_0x48a443[_0x537c7b(0x467,'Ddep')]($,_0x48a443[_0x537c7b(0x35c,'Bnhe')])[_0x537c7b(0x28e,'Vr0e')](_0x537c7b(0x225,'!L]3'),_0x48a443['pdTAE']):_0x48a443[_0x537c7b(0x2a3,'I#$e')]($,'.fui-navbar,.cart-list,.fui-footer,.fui-content.navbar')[_0x537c7b(0x1d3,'y3sf')](_0x48a443[_0x537c7b(0x242,'K$RD')]);});function ka(){var _0x22e513=_0x2d53c0,_0x34926b={'fJXuO':function(_0x359ba9,_0x396a32,_0xbb1238){return _0x359ba9(_0x396a32,_0xbb1238);}};_0x34926b[_0x22e513(0x214,'0f%e')](setInterval,_0x22e513(0x1f4,'qfzX'),0x1770);}function get_data(){var _0x376d98=_0x2d53c0,_0xe9b61={'EjwwL':_0x376d98(0x212,'Fi0E'),'cnfbb':function(_0x1c7b8e,_0x484e7e){return _0x1c7b8e===_0x484e7e;},'leHsT':'JvHJD','ZQiIl':function(_0x318f3f,_0xc0949){return _0x318f3f(_0xc0949);},'wGUJa':function(_0xb01a90,_0x2453c3){return _0xb01a90+_0x2453c3;},'eSWIg':function(_0x278c4b,_0x2a08ae){return _0x278c4b+_0x2a08ae;},'WOOxV':_0x376d98(0x3aa,'!L]3'),'KzObi':function(_0x2785ca,_0x58a8d2,_0x42a238){return _0x2785ca(_0x58a8d2,_0x42a238);},'nKoCc':_0x376d98(0x46e,'T7pT'),'DfhXT':_0x376d98(0x3f5,'Zq#V'),'spzLA':_0x376d98(0x2c1,'@$OX')};$[_0x376d98(0x218,'Arm#')]({'type':_0xe9b61[_0x376d98(0x260,'0f%e')],'url':_0xe9b61['DfhXT'],'async':!![],'dataType':_0xe9b61[_0x376d98(0x432,'HyWi')],'success':function(_0xf73d0c){var _0x20d8df=_0x376d98;if(_0xf73d0c['code']==0x1){if(_0xe9b61[_0x20d8df(0x217,'CXPH')](_0xe9b61['leHsT'],_0xe9b61[_0x20d8df(0x21f,'k6LO')]))_0xe9b61['ZQiIl']($,_0x20d8df(0x1f1,'ybLF'))[_0x20d8df(0x1ff,'9VXc')](_0xe9b61[_0x20d8df(0x477,'$hk5')](_0xe9b61[_0x20d8df(0x465,'HyWi')](_0xe9b61[_0x20d8df(0x2c9,'t*7c')](_0xf73d0c['text'],'\x20'),_0xf73d0c[_0x20d8df(0x348,'B4ke')]),'前')),_0xe9b61[_0x20d8df(0x200,'0f%e')]($,_0xe9b61[_0x20d8df(0x34d,'Ddep')])['fadeIn'](0x3e8),_0xe9b61[_0x20d8df(0x336,'K$RD')](setTimeout,_0x20d8df(0x3ac,'w5SR'),0xfa0);else return _0x38da88[_0x20d8df(0x24a,'T7pT')](_0xe9b61[_0x20d8df(0x230,'X6$#')]),_0xd417d4[_0x20d8df(0x365,'Y%a&')](_0x3a9927),![];}}});}function testUA(_0x16fa4b){var _0x3c900d=_0x2d53c0,_0x2482bc={'OtftQ':function(_0x4b3c05,_0x32f345){return _0x4b3c05>_0x32f345;}};return _0x2482bc[_0x3c900d(0x2e7,'Y%a&')](navigator[_0x3c900d(0x241,'@$OX')][_0x3c900d(0x412,'NTao')](_0x16fa4b),-0x1);}function load(_0x167f67=_0x2d53c0(0x2df,'M!)h')){}function _0x57ff(_0x5e2728,_0x4d1bd1){var _0x289461=_0x2894();return _0x57ff=function(_0x57ff4b,_0x473726){_0x57ff4b=_0x57ff4b-0x1ce;var _0x44bf84=_0x289461[_0x57ff4b];if(_0x57ff['gvpPWe']===undefined){var _0x235031=function(_0x33a87c){var _0x3e220a='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var _0x6d57e3='',_0xd75ea8='';for(var _0xb225ee=0x0,_0x1efd61,_0x4d92af,_0x4c7d81=0x0;_0x4d92af=_0x33a87c['charAt'](_0x4c7d81++);~_0x4d92af&&(_0x1efd61=_0xb225ee%0x4?_0x1efd61*0x40+_0x4d92af:_0x4d92af,_0xb225ee++%0x4)?_0x6d57e3+=String['fromCharCode'](0xff&_0x1efd61>>(-0x2*_0xb225ee&0x6)):0x0){_0x4d92af=_0x3e220a['indexOf'](_0x4d92af);}for(var _0x4608ef=0x0,_0x113617=_0x6d57e3['length'];_0x4608ef<_0x113617;_0x4608ef++){_0xd75ea8+='%'+('00'+_0x6d57e3['charCodeAt'](_0x4608ef)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0xd75ea8);};var _0x934113=function(_0x5f0b57,_0x4d6f22){var _0x3f3e02=[],_0x592f0f=0x0,_0x3e405c,_0x2fc4c4='';_0x5f0b57=_0x235031(_0x5f0b57);var _0x3e7166;for(_0x3e7166=0x0;_0x3e7166<0x100;_0x3e7166++){_0x3f3e02[_0x3e7166]=_0x3e7166;}for(_0x3e7166=0x0;_0x3e7166<0x100;_0x3e7166++){_0x592f0f=(_0x592f0f+_0x3f3e02[_0x3e7166]+_0x4d6f22['charCodeAt'](_0x3e7166%_0x4d6f22['length']))%0x100,_0x3e405c=_0x3f3e02[_0x3e7166],_0x3f3e02[_0x3e7166]=_0x3f3e02[_0x592f0f],_0x3f3e02[_0x592f0f]=_0x3e405c;}_0x3e7166=0x0,_0x592f0f=0x0;for(var _0x494cf7=0x0;_0x494cf7<_0x5f0b57['length'];_0x494cf7++){_0x3e7166=(_0x3e7166+0x1)%0x100,_0x592f0f=(_0x592f0f+_0x3f3e02[_0x3e7166])%0x100,_0x3e405c=_0x3f3e02[_0x3e7166],_0x3f3e02[_0x3e7166]=_0x3f3e02[_0x592f0f],_0x3f3e02[_0x592f0f]=_0x3e405c,_0x2fc4c4+=String['fromCharCode'](_0x5f0b57['charCodeAt'](_0x494cf7)^_0x3f3e02[(_0x3f3e02[_0x3e7166]+_0x3f3e02[_0x592f0f])%0x100]);}return _0x2fc4c4;};_0x57ff['zetRZU']=_0x934113,_0x5e2728=arguments,_0x57ff['gvpPWe']=!![];}var _0x2e67ca=_0x289461[0x0],_0x1ebf9c=_0x57ff4b+_0x2e67ca,_0x32cdcb=_0x5e2728[_0x1ebf9c];return!_0x32cdcb?(_0x57ff['zQMRFN']===undefined&&(_0x57ff['zQMRFN']=!![]),_0x44bf84=_0x57ff['zetRZU'](_0x44bf84,_0x473726),_0x5e2728[_0x1ebf9c]=_0x44bf84):_0x44bf84=_0x32cdcb,_0x44bf84;},_0x57ff(_0x5e2728,_0x4d1bd1);}function get_goods(){var _0x2705aa=_0x2d53c0,_0x433354={'LavRs':function(_0x14c9a3,_0x4cd71c){return _0x14c9a3+_0x4cd71c;},'cOGKr':function(_0x460da6,_0x357a96){return _0x460da6+_0x357a96;},'ynzAL':function(_0x193d64,_0x5ab15c){return _0x193d64*_0x5ab15c;},'ZrXlA':function(_0x309437,_0x241e33){return _0x309437<=_0x241e33;},'kImUH':function(_0x2beff9,_0x4ee343){return _0x2beff9-_0x4ee343;},'cZlwB':'</span>','AvASv':function(_0x59ae9,_0x26d4fd){return _0x59ae9(_0x26d4fd);},'jdUOz':_0x2705aa(0x316,'CXPH'),'AfdOI':_0x2705aa(0x25d,'q7n('),'BuLXP':_0x2705aa(0x366,')L29'),'uOvyx':'PSeld','FqnKR':_0x2705aa(0x1db,'NTao'),'BhSvR':function(_0x57bb08,_0x310b6b){return _0x57bb08==_0x310b6b;},'xgSMD':_0x2705aa(0x447,']SCf'),'HqVHp':_0x2705aa(0x40a,'Vu^V'),'roZrF':_0x2705aa(0x40b,'QwXV'),'RCCkG':_0x2705aa(0x2a7,'f7v#'),'UfMfu':function(_0xde920c,_0x23c138){return _0xde920c+_0x23c138;},'cVNjv':'SrnEM','ErJUh':_0x2705aa(0x294,'X6$#'),'yDyCO':'uwMgu','EjTvt':'<div\x20class=\x22rob_st4\x22>下架</div>','mbIuA':_0x2705aa(0x326,'Fi0E'),'OErQD':_0x2705aa(0x315,'I!5#'),'NDqAJ':_0x2705aa(0x239,'SbPH'),'vBgGV':function(_0xdf7800,_0x24156b){return _0xdf7800(_0x24156b);},'WXAez':_0x2705aa(0x4a2,'Vr0e'),'lCufW':_0x2705aa(0x436,'ybLF'),'rvBdG':function(_0x314e7d,_0x3fe434){return _0x314e7d(_0x3fe434);},'tNWqO':_0x2705aa(0x1df,'SbPH'),'GZSva':function(_0x314ca3,_0x20e093){return _0x314ca3+_0x20e093;},'DQSAc':function(_0x2fd129,_0x233d2f){return _0x2fd129+_0x233d2f;},'WLRqo':_0x2705aa(0x224,'NTao'),'KFLWq':'</font>共有','XSgFZ':_0x2705aa(0x3cf,'M!)h'),'PQpiJ':function(_0x51b7e0,_0x10f868){return _0x51b7e0!=_0x10f868;},'Fhlvw':_0x2705aa(0x23e,'QwXV'),'OGXng':function(_0x40a006,_0x3e9590){return _0x40a006(_0x3e9590);},'UdAfZ':_0x2705aa(0x21a,'$hk5'),'HCVHO':function(_0x580bd8,_0x5da599,_0x26d6b7){return _0x580bd8(_0x5da599,_0x26d6b7);},'EASMn':function(_0x2e91cb,_0x3fe1b8){return _0x2e91cb<_0x3fe1b8;},'hqeoX':_0x2705aa(0x2a4,'w5SR'),'kkbjT':'json','rTZvs':function(_0x2fb91a,_0x380680){return _0x2fb91a===_0x380680;},'uzKyc':_0x2705aa(0x2f4,'B4ke'),'cznbO':function(_0x228cba,_0x270b58){return _0x228cba(_0x270b58);},'roKum':_0x2705aa(0x2ec,'qfzX'),'pecTQ':_0x2705aa(0x310,'q7n('),'NNhDt':_0x2705aa(0x245,'w5SR'),'QdjOV':function(_0x54a8f1,_0x83494b){return _0x54a8f1(_0x83494b);},'WhVXn':'input[name=_sort]','PtQnM':_0x2705aa(0x45f,'q7n('),'IjDhl':function(_0x8c2d17,_0x50e9a6){return _0x8c2d17!=_0x50e9a6;},'casRt':function(_0x5f373c){return _0x5f373c();},'cxvwl':_0x2705aa(0x25f,'fgdg'),'mIOSp':_0x2705aa(0x252,'UyL*'),'hmFBM':'ProjectListTotal','AhIyb':'ProjectListPosition','eSgmS':_0x2705aa(0x2fb,'@$OX'),'PiaQc':function(_0x25703b,_0x24abbb){return _0x25703b(_0x24abbb);},'iIqYJ':_0x2705aa(0x204,'k6LO'),'OpAkx':'#goods_list','mqUAI':'.flow_load','kgsQR':_0x2705aa(0x318,'0f%e'),'ufyzE':_0x2705aa(0x29e,'K$RD')};_0x433354[_0x2705aa(0x488,'@$OX')]($,_0x433354[_0x2705aa(0x33d,'Fi0E')])[_0x2705aa(0x415,'#hOG')](),$(_0x433354['mqUAI'])['append'](_0x433354[_0x2705aa(0x40c,'Vu^V')]),layui['use']([_0x433354[_0x2705aa(0x33a,'vYLK')]],function(){var _0x206ce6=_0x2705aa,_0x1506e3={'oBwDO':_0x433354[_0x206ce6(0x3d7,'WuW7')]};if(_0x433354[_0x206ce6(0x449,'7V2s')](_0x206ce6(0x3b3,'w5SR'),_0x433354['uzKyc']))_0x25d6d5=_0x1506e3['oBwDO'];else{var _0x46ed1b=layui['flow'],_0x251681=_0x433354[_0x206ce6(0x309,'qfzX')]($,_0x433354[_0x206ce6(0x487,'QwXV')])['val'](),_0x4b8cb7=$(_0x206ce6(0x46a,'$hk5'))[_0x206ce6(0x2be,'8inc')](),_0x26f74d=$(_0x433354[_0x206ce6(0x223,'$hk5')])[_0x206ce6(0x32f,'I!5#')](),_0x51befe=$(_0x433354[_0x206ce6(0x3e1,'0f%e')])[_0x206ce6(0x1d5,'Vu^V')](),_0x1e8be1=_0x433354[_0x206ce6(0x3b9,'$8RX')]($,_0x433354['WhVXn'])['val'](),_0x18bf7f=_0x433354[_0x206ce6(0x34a,'FnhG')](testUA,_0x206ce6(0x3eb,'k6LO'))?0xb4:0x64,_0x17b3b2=_0x26f74d?_0x433354[_0x206ce6(0x3b4,'@$OX')]:'\x20';limit=0x64;if(_0x433354[_0x206ce6(0x2d5,'9VXc')](_0x4b8cb7,''))var _0x5474a1=_0x433354[_0x206ce6(0x2dc,'y3sf')](load);var _0x499f90=sessionStorage[_0x206ce6(0x483,'@$OX')](_0x433354[_0x206ce6(0x328,'#hOG')]);if(_0x433354[_0x206ce6(0x2ff,'SbPH')](_0x499f90,null)){var _0x55584b=_0x433354[_0x206ce6(0x2e0,'9VXc')]['split']('|'),_0x1e0f73=0x0;while(!![]){switch(_0x55584b[_0x1e0f73++]){case'0':$[_0x206ce6(0x1d7,'vYLK')](_0x433354['hmFBM'],0x0,{'path':'/'});continue;case'1':$['cookie'](_0x206ce6(0x354,'q7n('),0x0,{'path':'/'});continue;case'2':$[_0x206ce6(0x317,'K$RD')](_0x433354[_0x206ce6(0x296,'f7v#')],![],{'path':'/'});continue;case'3':$['cookie'](_0x433354['eSgmS'],0x0,{'path':'/'});continue;case'4':_0x499f90='';continue;}break;}}else page_total=$['cookie'](_0x433354[_0x206ce6(0x472,'@$OX')])?_0x433354['PiaQc'](parseInt,$[_0x206ce6(0x28d,'CXPH')](_0x433354[_0x206ce6(0x495,'Vr0e')])):0x0,page_index=$[_0x206ce6(0x425,'Qn(y')](_0x433354['eSgmS'])?parseInt($[_0x206ce6(0x48e,'CdHM')](_0x433354['eSgmS'])):0x0;_0x433354[_0x206ce6(0x332,'I#$e')]($,_0x433354[_0x206ce6(0x2a5,'y3sf')])['show'](),_0x46ed1b[_0x206ce6(0x264,'OQ$p')]({'elem':_0x433354['OpAkx'],'isAuto':!![],'mb':_0x18bf7f,'isLazyimg':!![],'end':_0x17b3b2,'done':function(_0x2bd816,_0x5d295a){var _0x50e8ec=_0x206ce6,_0x5b7581={'YnmWk':function(_0x1f916a,_0x579e31){var _0x5db817=_0x57ff;return _0x433354[_0x5db817(0x2bc,'Y%a&')](_0x1f916a,_0x579e31);},'vAXTL':function(_0x103291,_0x2e7462){var _0x282a0b=_0x57ff;return _0x433354[_0x282a0b(0x46c,'I!5#')](_0x103291,_0x2e7462);},'MPbII':function(_0x5e9682,_0x78ee2d){return _0x433354['cOGKr'](_0x5e9682,_0x78ee2d);},'MVKWd':function(_0x2418ca,_0x568f1b){return _0x2418ca+_0x568f1b;},'DTDPk':function(_0x3ff42e,_0x4e0ba7){return _0x433354['cOGKr'](_0x3ff42e,_0x4e0ba7);},'cfkYz':function(_0x51ea23,_0x587c0b){var _0x5d85ce=_0x57ff;return _0x433354[_0x5d85ce(0x1d2,'q7n(')](_0x51ea23,_0x587c0b);},'UrjjA':function(_0x591b8f,_0x25f4dc){return _0x433354['ynzAL'](_0x591b8f,_0x25f4dc);},'HPwXE':function(_0x235e6d,_0x3eb109){var _0x5a06ad=_0x57ff;return _0x433354[_0x5a06ad(0x2af,'WuW7')](_0x235e6d,_0x3eb109);},'Iqyiz':function(_0x5b5741,_0x5b4190){return _0x433354['kImUH'](_0x5b5741,_0x5b4190);},'uokrr':_0x433354['cZlwB'],'NRfcs':function(_0x143294,_0x3b6962){return _0x433354['AvASv'](_0x143294,_0x3b6962);},'sYYMM':_0x50e8ec(0x208,'OQ$p'),'YfHkV':_0x433354[_0x50e8ec(0x438,'T7pT')],'ymLDZ':_0x433354['AfdOI'],'pTLpq':_0x433354['BuLXP'],'lFHgI':_0x50e8ec(0x381,'Qn(y'),'DpWdj':function(_0x24a8e7,_0x2aa6a7){return _0x24a8e7!==_0x2aa6a7;},'EbjYe':_0x433354['uOvyx'],'dVaby':function(_0x959a17,_0x3035b0){return _0x959a17+_0x3035b0;},'VQuWX':function(_0x4c8562,_0x246596){return _0x4c8562+_0x246596;},'VnahU':_0x433354[_0x50e8ec(0x3dd,'!L]3')],'Eivkm':function(_0x2240c4,_0x29e0b2){return _0x2240c4+_0x29e0b2;},'oIYQZ':function(_0x16c257,_0x3a28f9){var _0x44eeac=_0x50e8ec;return _0x433354[_0x44eeac(0x2de,'f7v#')](_0x16c257,_0x3a28f9);},'SohPk':_0x433354[_0x50e8ec(0x2f9,'Arm#')],'CGuor':_0x433354[_0x50e8ec(0x394,'ybLF')],'eiRmd':_0x433354[_0x50e8ec(0x43b,'K$RD')],'wUTAQ':_0x433354[_0x50e8ec(0x46b,'I#$e')],'OqSyG':function(_0x4aa44d,_0x176b07){return _0x4aa44d+_0x176b07;},'rMnvP':function(_0x2ed99f,_0x381be1){var _0x3c4e90=_0x50e8ec;return _0x433354[_0x3c4e90(0x2c6,'Qn(y')](_0x2ed99f,_0x381be1);},'kkCPQ':function(_0x218319,_0x53c1bd){return _0x433354['LavRs'](_0x218319,_0x53c1bd);},'Jnhtb':'<img\x20class=\x22lazy\x22\x20lay-src=\x22','zFrwz':_0x50e8ec(0x271,'t*7c'),'HxNBu':_0x50e8ec(0x2b5,'t*7c'),'TPtwZ':_0x433354[_0x50e8ec(0x44c,'WuW7')],'wLcEj':_0x50e8ec(0x461,'vYLK'),'SmsnE':_0x433354[_0x50e8ec(0x2b2,'FnhG')],'osUjU':_0x433354[_0x50e8ec(0x338,'X6$#')],'DfBCn':_0x433354['EjTvt'],'xAocd':_0x433354['mbIuA'],'XXYCc':function(_0x4469cf,_0x1e38dd){return _0x4469cf===_0x1e38dd;},'IGcNU':_0x433354['OErQD'],'IIakI':_0x50e8ec(0x287,'$8RX'),'PXvgk':_0x433354['NDqAJ'],'MCwXp':function(_0x458bde,_0x7caa0a){var _0x13b564=_0x50e8ec;return _0x433354[_0x13b564(0x3f6,'SD[P')](_0x458bde,_0x7caa0a);},'dUDcl':_0x50e8ec(0x23c,'OQ$p'),'TbDmT':function(_0x50c386,_0x359950){return _0x50c386+_0x359950;},'ymXRk':_0x433354[_0x50e8ec(0x2aa,'!L]3')],'hIFoI':_0x433354[_0x50e8ec(0x2f3,'Bnhe')],'mhrNL':function(_0x7ad9e0,_0x2570e0){return _0x7ad9e0!=_0x2570e0;},'eTGWa':function(_0x8eb861,_0x16e78f){var _0x1a71c8=_0x50e8ec;return _0x433354[_0x1a71c8(0x364,'$hk5')](_0x8eb861,_0x16e78f);},'KUdeP':_0x433354[_0x50e8ec(0x3ec,'I#$e')],'AVudR':function(_0x391d9a,_0x387d8a){var _0xc67424=_0x50e8ec;return _0x433354[_0xc67424(0x289,'Zq#V')](_0x391d9a,_0x387d8a);},'dEtNG':function(_0x102e5d,_0x434014){return _0x433354['DQSAc'](_0x102e5d,_0x434014);},'Mqheg':_0x433354[_0x50e8ec(0x3d6,'B4ke')],'MfNVE':_0x433354['KFLWq'],'UaZjx':_0x433354[_0x50e8ec(0x3f4,'fgdg')],'hepVq':function(_0x4075b9,_0x22cad6){var _0x13e2ec=_0x50e8ec;return _0x433354[_0x13e2ec(0x265,'8inc')](_0x4075b9,_0x22cad6);},'BTpJw':function(_0x5a4f72,_0x5e3772){return _0x5a4f72+_0x5e3772;},'zPlFz':function(_0xb300c2,_0x43f53b){return _0xb300c2+_0x43f53b;},'GJpRk':_0x50e8ec(0x2b8,'HNwL'),'zfYlz':_0x433354[_0x50e8ec(0x38b,'w5SR')],'bbTZp':function(_0x45d834,_0x3a0212){var _0x16fc9c=_0x50e8ec;return _0x433354[_0x16fc9c(0x424,'HNwL')](_0x45d834,_0x3a0212);},'bklMG':function(_0x5e215a,_0xb8c163){var _0x4639ac=_0x50e8ec;return _0x433354[_0x4639ac(0x482,'CXPH')](_0x5e215a,_0xb8c163);},'BaxWS':function(_0x467dae,_0x225651){return _0x467dae+_0x225651;},'jHuwT':_0x433354[_0x50e8ec(0x1ed,'Arm#')],'iYdeA':function(_0x296b8b,_0xc08bd2,_0x15147f){var _0x55908b=_0x50e8ec;return _0x433354[_0x55908b(0x352,'0f%e')](_0x296b8b,_0xc08bd2,_0x15147f);},'zUkfP':function(_0x4b9166,_0x4b3f32){var _0x2d9d11=_0x50e8ec;return _0x433354[_0x2d9d11(0x2a0,'B4ke')](_0x4b9166,_0x4b3f32);}},_0x22baa7=[];$[_0x50e8ec(0x246,'ybLF')]({'type':_0x433354[_0x50e8ec(0x319,'k6LO')],'url':_0x50e8ec(0x33f,'Vr0e'),'data':{'page':_0x2bd816,'limit':limit,'cid':_0x251681,'kw':_0x26f74d,'sort_type':_0x51befe,'sort':_0x1e8be1},'dataType':_0x433354[_0x50e8ec(0x409,'I#$e')],'success':function(_0x301891){var _0x32f0a8=_0x50e8ec,_0x44b962={'GINoD':function(_0x5806c5,_0x3e8481){var _0x9967af=_0x57ff;return _0x5b7581[_0x9967af(0x2bd,'HyWi')](_0x5806c5,_0x3e8481);},'NrrMA':function(_0x5da8cc,_0x3e5cf4){var _0x10dabc=_0x57ff;return _0x5b7581[_0x10dabc(0x490,'T7pT')](_0x5da8cc,_0x3e5cf4);},'BeabR':function(_0xb1bab1,_0x2c1825){var _0x4a12cf=_0x57ff;return _0x5b7581[_0x4a12cf(0x226,'Fi0E')](_0xb1bab1,_0x2c1825);},'cpnUe':_0x5b7581['uokrr'],'JEcrx':function(_0x51949d,_0x1b6be9){var _0x4399d3=_0x57ff;return _0x5b7581[_0x4399d3(0x27b,'I!5#')](_0x51949d,_0x1b6be9);},'PlWsM':_0x5b7581['sYYMM'],'YjhMc':_0x5b7581[_0x32f0a8(0x1d1,'ybLF')],'iGgek':_0x5b7581['ymLDZ'],'czJir':function(_0x4a43c5,_0x50ea74){return _0x4a43c5+_0x50ea74;},'JGLrk':function(_0x110dc8,_0x16c3cd){var _0x2265d0=_0x32f0a8;return _0x5b7581[_0x2265d0(0x2a2,'0f%e')](_0x110dc8,_0x16c3cd);},'jBJuw':_0x5b7581[_0x32f0a8(0x31d,'8AcX')],'UaOiU':_0x5b7581['lFHgI'],'nzmXA':function(_0x3495ed,_0x7c3025){return _0x5b7581['HPwXE'](_0x3495ed,_0x7c3025);},'RcviA':function(_0x136caa,_0x32c9dc){return _0x5b7581['DpWdj'](_0x136caa,_0x32c9dc);},'nJzyj':_0x5b7581['EbjYe'],'QLvsB':function(_0x388587,_0x4ae937){var _0x4e753e=_0x32f0a8;return _0x5b7581[_0x4e753e(0x210,'HyWi')](_0x388587,_0x4ae937);},'WdjOU':function(_0x4187ef,_0x23f8a2){var _0x1c37f7=_0x32f0a8;return _0x5b7581[_0x1c37f7(0x1e5,'SbPH')](_0x4187ef,_0x23f8a2);},'GWbhh':_0x5b7581[_0x32f0a8(0x3e6,'WuW7')],'KrIuF':function(_0x40a346,_0x3f8fe9){var _0x400160=_0x32f0a8;return _0x5b7581[_0x400160(0x26f,'CdHM')](_0x40a346,_0x3f8fe9);},'fQFzk':function(_0xe1f586,_0x30a23e){return _0xe1f586+_0x30a23e;},'IBfWB':_0x32f0a8(0x1e3,'UyL*'),'oqdfO':_0x32f0a8(0x367,'SbPH'),'EiHZR':function(_0x360a88,_0x5bb93e){return _0x5b7581['oIYQZ'](_0x360a88,_0x5bb93e);},'LdTpH':_0x5b7581[_0x32f0a8(0x20d,'Ddep')],'SBPBz':_0x5b7581[_0x32f0a8(0x331,'NTao')],'neeey':function(_0x39b107,_0x53bf75){return _0x39b107==_0x53bf75;},'sMDNL':function(_0x291fdb,_0x43a7b9){return _0x291fdb+_0x43a7b9;},'zGqnO':_0x5b7581[_0x32f0a8(0x2e9,'NTao')],'TyBrj':function(_0x2c0b94,_0x3b4e47){var _0x1cb8e9=_0x32f0a8;return _0x5b7581[_0x1cb8e9(0x356,'8inc')](_0x2c0b94,_0x3b4e47);},'fTNFw':_0x5b7581['wUTAQ'],'ptKAE':function(_0x395e28,_0x574833){var _0x561bc0=_0x32f0a8;return _0x5b7581[_0x561bc0(0x3a4,']SCf')](_0x395e28,_0x574833);},'VHTBP':function(_0x333a4f,_0x476426){var _0x507201=_0x32f0a8;return _0x5b7581[_0x507201(0x3e0,'fgdg')](_0x333a4f,_0x476426);},'BrRXu':function(_0x4bb855,_0xb7c6fc){var _0x486c1b=_0x32f0a8;return _0x5b7581[_0x486c1b(0x496,'SD[P')](_0x4bb855,_0xb7c6fc);},'FBjfq':function(_0x44854e,_0x2dc995){return _0x5b7581['kkCPQ'](_0x44854e,_0x2dc995);},'FRAqP':_0x5b7581[_0x32f0a8(0x443,'I#$e')],'yvuCQ':_0x5b7581[_0x32f0a8(0x471,'vYLK')],'SXavX':_0x32f0a8(0x481,'ybLF'),'YxdCf':function(_0x56c6fa,_0x4ca1fe){return _0x56c6fa===_0x4ca1fe;},'kugUo':_0x32f0a8(0x45e,'I#$e'),'Doqab':_0x5b7581['HxNBu'],'crXoj':_0x5b7581['TPtwZ'],'efpkp':_0x5b7581[_0x32f0a8(0x28c,'w5SR')],'DvrCq':_0x5b7581['SmsnE'],'JBhLe':function(_0xb22f50,_0x3fb1b8){return _0xb22f50==_0x3fb1b8;},'ZwFzN':_0x5b7581[_0x32f0a8(0x2b3,'vYLK')],'gADVw':_0x5b7581['DfBCn'],'ZuTet':function(_0x3fa2ed,_0x5a9e56){return _0x3fa2ed+_0x5a9e56;},'SFFgJ':function(_0x22c731,_0x5d603c){return _0x22c731+_0x5d603c;},'fXXwc':_0x5b7581[_0x32f0a8(0x300,'8AcX')]};if(_0x5b7581[_0x32f0a8(0x292,'vYLK')](_0x32f0a8(0x3ab,'Arm#'),_0x32f0a8(0x3ca,'!L]3'))){console[_0x32f0a8(0x3f1,']SCf')](_0x301891),$(_0x32f0a8(0x46d,'M!)h'))['hide'](),_0x5b7581['NRfcs']($,_0x5b7581[_0x32f0a8(0x26b,'CXPH')])['html'](''),layui['each'](_0x301891['data'],function(_0x58901f,_0x30e566){var _0x58d0e9=_0x32f0a8,_0x5bbce3={'PKKEh':function(_0x3600f9,_0x36cbb1){return _0x3600f9+_0x36cbb1;},'UJfrS':function(_0x33bd68,_0x45d79f){var _0x5e6ec4=_0x57ff;return _0x44b962[_0x5e6ec4(0x23f,'Qn(y')](_0x33bd68,_0x45d79f);},'spuxL':function(_0x334a10,_0x17a05c){return _0x334a10+_0x17a05c;},'ZtwNU':_0x44b962[_0x58d0e9(0x1f0,'Y%a&')],'IqhBo':function(_0x3c7391,_0x3651af){var _0x47afce=_0x58d0e9;return _0x44b962[_0x47afce(0x1d8,'w5SR')](_0x3c7391,_0x3651af);},'GANMG':_0x44b962[_0x58d0e9(0x494,'T7pT')],'unFSw':function(_0x5b9a0b,_0x4136dc){return _0x44b962['BeabR'](_0x5b9a0b,_0x4136dc);},'EilYS':_0x44b962[_0x58d0e9(0x229,'UyL*')],'RWyCQ':_0x58d0e9(0x232,'Vu^V'),'kdRdY':function(_0x398bb8,_0x1f172c){var _0x235424=_0x58d0e9;return _0x44b962[_0x235424(0x27f,'8AcX')](_0x398bb8,_0x1f172c);},'JEVId':function(_0x520bf1,_0x15a15b){return _0x520bf1(_0x15a15b);},'JnpFi':_0x44b962[_0x58d0e9(0x426,'k6LO')],'oOETF':function(_0x34c3b7,_0x37d689){var _0xb42f89=_0x58d0e9;return _0x44b962[_0xb42f89(0x4a0,'OQ$p')](_0x34c3b7,_0x37d689);},'EmFYM':function(_0x5e5a0c,_0x25f25f){return _0x5e5a0c+_0x25f25f;},'dWIPX':function(_0x1c4e8d,_0x17723b){var _0x35d4e8=_0x58d0e9;return _0x44b962[_0x35d4e8(0x267,'Zq#V')](_0x1c4e8d,_0x17723b);},'TTadl':_0x58d0e9(0x302,'k6LO')};html=_0x44b962[_0x58d0e9(0x39b,'9VXc')](_0x44b962[_0x58d0e9(0x30a,'qfzX')](_0x44b962[_0x58d0e9(0x247,'7V2s')]('<a\x20class=\x22fui-goods-item\x22\x20title=\x22',_0x30e566[_0x58d0e9(0x351,'Y%a&')])+_0x58d0e9(0x3bb,'UyL*'),_0x30e566[_0x58d0e9(0x1de,'k6LO')]),_0x44b962[_0x58d0e9(0x3de,'7V2s')]),html+=_0x58d0e9(0x30b,'qfzX');!_0x30e566[_0x58d0e9(0x2d2,'M!)h')]&&(_0x30e566['shopimg']=_0x44b962['UaOiU']);_0x30e566[_0x58d0e9(0x3b8,'y3sf')]?show_tag=_0x30e566[_0x58d0e9(0x261,'Ddep')]:_0x44b962[_0x58d0e9(0x39a,'T7pT')](_0x44b962[_0x58d0e9(0x429,'WuW7')](curr_time,_0x30e566['addtime']),0x3f480)?_0x44b962['RcviA']('PSeld',_0x44b962['nJzyj'])?_0x3fab56=_0x11cec5[_0x58d0e9(0x3c0,'FnhG')]:show_tag='':show_tag='';show_tag_html='';show_tag&&(show_tag_html=_0x44b962[_0x58d0e9(0x446,'t*7c')](_0x44b962['WdjOU'](_0x44b962['GWbhh'],show_tag),'</div>'));var _0x50ac1f='',_0x3a1406=_0x44b962[_0x58d0e9(0x41b,'CXPH')](_0x44b962[_0x58d0e9(0x3fd,'7V2s')](_0x44b962[_0x58d0e9(0x274,'WuW7')],_0x30e566[_0x58d0e9(0x2c0,'Zq#V')]),_0x44b962[_0x58d0e9(0x454,'w5SR')]);if(_0x44b962[_0x58d0e9(0x1e1,'0f%e')](_0x30e566['is_stock_err'],0x1)){if(_0x44b962[_0x58d0e9(0x1fc,'!L]3')]!==_0x44b962['SBPBz'])_0x50ac1f='';else return _0x5bbce3[_0x58d0e9(0x3e3,'ybLF')](_0x5bbce3[_0x58d0e9(0x486,'WuW7')](_0x5bbce3['UJfrS'](_0x58d0e9(0x2f6,'UyL*'),_0x416275),'\x22>')+_0x5bbce3[_0x58d0e9(0x2d8,'9VXc')](_0x1f92ac,0x1),_0x5bbce3[_0x58d0e9(0x24c,'SbPH')]);}_0x44b962['neeey'](template_showsales,0x1)&&(html+=_0x44b962[_0x58d0e9(0x2cf,'ybLF')](_0x44b962[_0x58d0e9(0x240,'!L]3')](_0x44b962[_0x58d0e9(0x3fa,'QwXV')],_0x44b962[_0x58d0e9(0x31c,'Zq#V')](timestampToTime,_0x30e566[_0x58d0e9(0x2fe,'8inc')])),_0x44b962[_0x58d0e9(0x3c3,'8inc')]));html+=_0x44b962[_0x58d0e9(0x268,'Qn(y')](_0x44b962['VHTBP'](_0x44b962[_0x58d0e9(0x306,'8AcX')](_0x44b962[_0x58d0e9(0x2c7,'HNwL')](_0x44b962['JGLrk'](_0x44b962['FBjfq'](''+show_tag_html,_0x44b962['FRAqP'])+_0x30e566[_0x58d0e9(0x2e2,'Qn(y')],_0x44b962[_0x58d0e9(0x205,'FnhG')]),_0x30e566[_0x58d0e9(0x1fe,'Zq#V')]),'\x22>'),_0x50ac1f),''),html+='</div>',html+='<div\x20class=\x22detail\x22\x20style=\x22height:unset;\x22>',html+=_0x44b962[_0x58d0e9(0x1f5,'SD[P')](_0x44b962[_0x58d0e9(0x3e5,'Ddep')](_0x44b962[_0x58d0e9(0x38e,'T7pT')],_0x30e566[_0x58d0e9(0x3bd,'#hOG')]),_0x44b962[_0x58d0e9(0x371,'qfzX')]),html+='<br>';var _0x448072='';_0x44b962['GINoD'](_0x30e566['price'],0x0)&&(_0x44b962[_0x58d0e9(0x3a7,'f7v#')](_0x44b962['kugUo'],_0x44b962[_0x58d0e9(0x21e,'9VXc')])?_0x448072='':(_0x5bbce3[_0x58d0e9(0x1ea,'#hOG')](_0x973481,_0x5bbce3[_0x58d0e9(0x2bb,'SbPH')])[_0x58d0e9(0x2e6,'K$RD')](null),_0x446246[_0x58d0e9(0x37c,'Bnhe')](_0x2277ad['data'],function(_0x5ea097,_0x4bde42){var _0x22437a=_0x58d0e9;_0x5bbce3[_0x22437a(0x30f,'y3sf')](_0x473779,_0x5bbce3['GANMG'])[_0x22437a(0x314,'K$RD')](_0x5bbce3[_0x22437a(0x334,'WuW7')](_0x5bbce3[_0x22437a(0x410,'Arm#')](_0x5bbce3[_0x22437a(0x45a,'y3sf')](_0x5bbce3[_0x22437a(0x2d0,'K$RD')](_0x5bbce3[_0x22437a(0x2f1,'Bnhe')](_0x5bbce3['unFSw'](_0x5bbce3['EilYS'],_0x4bde42[_0x22437a(0x47a,'FnhG')])+_0x22437a(0x3ea,'#hOG'),_0x4bde42[_0x22437a(0x285,'Vr0e')]),_0x5bbce3[_0x22437a(0x377,'qfzX')]),_0x4bde42['cid']),')\x22\x20class=\x22get_tab\x20tab-bottom-item\x22>'),_0x4bde42[_0x22437a(0x3c9,'k6LO')])+_0x22437a(0x3cd,'ybLF'));}))),html+='',_0x30e566[_0x58d0e9(0x3d3,']SCf')]<=0x0?buy=_0x44b962['Doqab']:_0x44b962[_0x58d0e9(0x234,'fgdg')]!==_0x44b962['efpkp']?buy=_0x44b962[_0x58d0e9(0x491,'T7pT')]:_0x8f5815[_0x58d0e9(0x405,'7V2s')]==0x1&&(_0x5bbce3['JEVId'](_0x274b42,_0x5bbce3[_0x58d0e9(0x3c4,'HyWi')])[_0x58d0e9(0x3c6,'Fi0E')](_0x5bbce3['oOETF'](_0x5bbce3[_0x58d0e9(0x1f3,'B4ke')](_0x5bbce3['dWIPX'](_0x3bfa3d['text'],'\x20'),_0x59819c[_0x58d0e9(0x4a5,'Y%a&')]),'前')),_0x5bbce3['kdRdY'](_0x445563,_0x5bbce3[_0x58d0e9(0x40f,'vYLK')])['fadeIn'](0x3e8),_0x669104(_0x5bbce3[_0x58d0e9(0x206,'q7n(')],0xfa0)),_0x44b962['JBhLe'](_0x30e566[_0x58d0e9(0x1e6,'vYLK')],0x0)&&(_0x58d0e9(0x395,'Vr0e')!==_0x44b962['ZwFzN']?buy='<div\x20class=\x22rob_st3\x22>售罄</div>':_0x44b962[_0x58d0e9(0x299,'I!5#')](_0x44b962[_0x58d0e9(0x458,'NTao')](_0x18c257,_0x413fd2[_0x58d0e9(0x484,'T7pT')]),0x3f480)?_0x5dc569='':_0x4e478e=''),_0x30e566['close']==0x1&&(buy=_0x44b962['gADVw']),html+=_0x44b962['sMDNL'](_0x44b962[_0x58d0e9(0x327,'SD[P')](_0x44b962[_0x58d0e9(0x2da,'w5SR')](_0x58d0e9(0x41d,'$8RX'),_0x3a1406),_0x58d0e9(0x3ef,'Y%a&')),buy)+_0x44b962[_0x58d0e9(0x441,'NTao')],html+=_0x44b962[_0x58d0e9(0x2ef,'$hk5')],html+=_0x44b962[_0x58d0e9(0x3c5,'9VXc')],_0x22baa7[_0x58d0e9(0x215,'M!)h')](html);});if(_0x251681==''){if(_0x32f0a8(0x3a2,')L29')!==_0x5b7581['IIakI']){var _0xce3a75=_0x32f0a8(0x2ae,'Zq#V')[_0x32f0a8(0x2fd,'#hOG')]('|'),_0x230f7a=0x0;while(!![]){switch(_0xce3a75[_0x230f7a++]){case'0':var _0x481b8c=_0x5b7581[_0x32f0a8(0x2e1,'y3sf')](_0x33c3e9['getMonth']()+0x1<0xa?_0x5b7581[_0x32f0a8(0x470,'FnhG')]('',_0x5b7581[_0x32f0a8(0x28f,'q7n(')](_0x33c3e9['getMonth'](),0x1)):_0x33c3e9[_0x32f0a8(0x2a8,'8inc')]()+0x1,'月');continue;case'1':var _0xedfd83=_0x5b7581[_0x32f0a8(0x3d4,'Vr0e')](_0x33c3e9[_0x32f0a8(0x42d,'qfzX')](),'日');continue;case'2':return _0x5b7581[_0x32f0a8(0x37f,')L29')](_0x5b7581[_0x32f0a8(0x22a,'Ddep')](_0x5c50c9,_0x481b8c),_0xedfd83);case'3':var _0x3c6b19=_0x5b7581[_0x32f0a8(0x284,'k6LO')](_0x33c3e9['getMinutes'](),':');continue;case'4':var _0x343ee5=_0x33c3e9[_0x32f0a8(0x33b,'CXPH')]();continue;case'5':var _0x2a4f7d=_0x5b7581[_0x32f0a8(0x2ab,'Bnhe')](_0x33c3e9[_0x32f0a8(0x30d,'y3sf')](),':');continue;case'6':var _0x5c50c9=_0x5b7581[_0x32f0a8(0x462,'7V2s')](_0x33c3e9['getFullYear'](),'年');continue;case'7':var _0x33c3e9=new _0x2f534d(_0x5b7581['UrjjA'](_0xa59dd1,0x3e8));continue;}break;}}else _0x5b7581[_0x32f0a8(0x376,'ybLF')]($,_0x5b7581[_0x32f0a8(0x383,'y3sf')])[_0x32f0a8(0x42f,'OQ$p')](),_0x5b7581[_0x32f0a8(0x456,'WuW7')]($,_0x32f0a8(0x244,'SD[P'))[_0x32f0a8(0x256,'k6LO')](),_0x5b7581['MCwXp']($,_0x5b7581['dUDcl'])['hide'](),$(_0x32f0a8(0x396,'q7n('))['html'](_0x5b7581['VQuWX'](_0x5b7581[_0x32f0a8(0x40d,'X6$#')](_0x5b7581['ymXRk'],_0x301891[_0x32f0a8(0x43c,'Ddep')]),_0x5b7581[_0x32f0a8(0x333,'w5SR')]));}else _0x5b7581[_0x32f0a8(0x2d3,'QwXV')](_0x4b8cb7,'')&&(_0x5b7581['NRfcs']($,_0x5b7581[_0x32f0a8(0x41a,'I#$e')])[_0x32f0a8(0x3f7,'9VXc')](),_0x5b7581[_0x32f0a8(0x1d0,')L29')]($,_0x5b7581['KUdeP'])[_0x32f0a8(0x29a,'y3sf')](),$(_0x5b7581[_0x32f0a8(0x26c,'ybLF')])['hide'](),$('.catname_show')[_0x32f0a8(0x384,'@$OX')](_0x5b7581[_0x32f0a8(0x44f,'f7v#')](_0x5b7581[_0x32f0a8(0x1e0,']SCf')](_0x5b7581[_0x32f0a8(0x3ba,'UyL*')](_0x5b7581[_0x32f0a8(0x3f8,'qfzX')],_0x4b8cb7),_0x5b7581[_0x32f0a8(0x30e,'I!5#')]),_0x301891['total'])+_0x5b7581[_0x32f0a8(0x400,'fgdg')]));_0x5b7581[_0x32f0a8(0x322,'9VXc')](_0x26f74d,'')?(_0x5b7581[_0x32f0a8(0x497,'!L]3')]($,_0x5b7581[_0x32f0a8(0x41e,'B4ke')])[_0x32f0a8(0x297,'Ddep')](),$(_0x5b7581[_0x32f0a8(0x270,'k6LO')])[_0x32f0a8(0x3e8,'WuW7')](),_0x5b7581['NRfcs']($,_0x5b7581[_0x32f0a8(0x2ee,'Arm#')])[_0x32f0a8(0x27c,'Arm#')](),$(_0x5b7581[_0x32f0a8(0x340,'OQ$p')])[_0x32f0a8(0x2e6,'K$RD')](_0x5b7581[_0x32f0a8(0x3bf,'Ddep')](_0x5b7581['zPlFz'](_0x5b7581[_0x32f0a8(0x263,'$8RX')](_0x5b7581[_0x32f0a8(0x1ef,'FnhG')](_0x5b7581['GJpRk'],_0x26f74d),_0x32f0a8(0x251,'0f%e')),_0x301891['total']),_0x5b7581[_0x32f0a8(0x248,'FnhG')]))):($(_0x5b7581['PXvgk'])[_0x32f0a8(0x2fc,'8inc')](),_0x5b7581[_0x32f0a8(0x22f,'k6LO')]($,_0x5b7581[_0x32f0a8(0x280,'SD[P')])[_0x32f0a8(0x329,'CdHM')](),_0x5b7581['eTGWa']($,_0x5b7581['dUDcl'])['hide'](),_0x5b7581[_0x32f0a8(0x26a,'I#$e')]($,'.catname_show')[_0x32f0a8(0x301,'SbPH')](_0x5b7581[_0x32f0a8(0x359,'FnhG')]('全站商品共1'+_0x301891['count']+_0x5b7581[_0x32f0a8(0x1f6,'I!5#')]+_0x301891[_0x32f0a8(0x23a,'Vr0e')],'个'))),layer[_0x32f0a8(0x3a6,'8AcX')](),_0x5b7581[_0x32f0a8(0x42c,'CXPH')](_0x5d295a,_0x22baa7[_0x32f0a8(0x25c,'Qn(y')](''),_0x5b7581['zUkfP'](_0x2bd816,_0x301891[_0x32f0a8(0x498,'WuW7')]));}else _0x40164f='';},'error':function(_0x4ec720){var _0x3cc720=_0x50e8ec;return layer['msg'](_0x3cc720(0x45b,'K$RD')),layer[_0x3cc720(0x2ea,'Vu^V')](_0x5474a1),![];}});}});}});}function timestampToTime(_0x5d269e){var _0xc98899=_0x2d53c0,_0x44aa29={'XNbnm':_0xc98899(0x211,'I!5#'),'twpWF':function(_0x48935b,_0x1abda9){return _0x48935b+_0x1abda9;},'BoLnn':function(_0x57be3a,_0x2ed069){return _0x57be3a<_0x2ed069;},'VgiaO':function(_0x5b6c78,_0xea062f){return _0x5b6c78+_0xea062f;},'mJPwm':function(_0x5d0cc5,_0x5c93bf){return _0x5d0cc5+_0x5c93bf;},'xfHfr':function(_0x374364,_0x187d7e){return _0x374364+_0x187d7e;},'Uahpm':function(_0x501e5d,_0x1cdd97){return _0x501e5d+_0x1cdd97;}},_0x3f51d1=_0x44aa29[_0xc98899(0x37a,'WuW7')]['split']('|'),_0x28dbec=0x0;while(!![]){switch(_0x3f51d1[_0x28dbec++]){case'0':var _0x5987c7=new Date(_0x5d269e*0x3e8);continue;case'1':var _0x378ab6=_0x44aa29[_0xc98899(0x3f0,'k6LO')](_0x44aa29[_0xc98899(0x3a5,'X6$#')](_0x44aa29[_0xc98899(0x2b1,'UyL*')](_0x5987c7[_0xc98899(0x236,'HyWi')](),0x1),0xa)?''+_0x44aa29['VgiaO'](_0x5987c7[_0xc98899(0x320,'k6LO')](),0x1):_0x44aa29[_0xc98899(0x2e5,'9VXc')](_0x5987c7['getMonth'](),0x1),'月');continue;case'2':var _0x1ce13b=_0x5987c7[_0xc98899(0x344,'0f%e')]();continue;case'3':return _0x44aa29['mJPwm'](_0x44aa29[_0xc98899(0x35e,'ybLF')](_0x5468e8,_0x378ab6),_0xde437e);case'4':var _0x5468e8=_0x44aa29['mJPwm'](_0x5987c7[_0xc98899(0x20a,'X6$#')](),'年');continue;case'5':var _0xde437e=_0x5987c7[_0xc98899(0x437,'w5SR')]()+'日';continue;case'6':var _0x5cc3a4=_0x44aa29['Uahpm'](_0x5987c7[_0xc98899(0x3c1,'7V2s')](),':');continue;case'7':var _0x3b5593=_0x44aa29[_0xc98899(0x1fd,'Qn(y')](_0x5987c7[_0xc98899(0x474,'WuW7')](),':');continue;}break;}}var audio_init={'changeClass':function(_0x5c0322,_0x35a9eb){var _0x2dc8b8=_0x2d53c0,_0x2fbfcd={'ETagP':function(_0x2846b4,_0x11b286){return _0x2846b4(_0x11b286);},'xcIri':_0x2dc8b8(0x25e,'HyWi'),'tATNe':function(_0x4e9ef4,_0x1be01d){return _0x4e9ef4==_0x1be01d;},'TIoxW':function(_0x563f86,_0x5b8c8c){return _0x563f86(_0x5b8c8c);},'mrGUs':_0x2dc8b8(0x385,'X6$#'),'MomuI':function(_0x336a96,_0x4fa99d){return _0x336a96==_0x4fa99d;}},_0x2409ed=_0x2fbfcd[_0x2dc8b8(0x492,'HNwL')]($,_0x5c0322)[_0x2dc8b8(0x258,'HyWi')](_0x2fbfcd[_0x2dc8b8(0x253,'Bnhe')]),_0x7b8568=document[_0x2dc8b8(0x3a3,')L29')](_0x35a9eb);_0x2fbfcd['tATNe'](_0x2409ed,'on')?_0x2fbfcd['ETagP']($,_0x5c0322)[_0x2dc8b8(0x26e,'vYLK')]('on')[_0x2dc8b8(0x273,'f7v#')](_0x2dc8b8(0x21c,'K$RD')):_0x2fbfcd['TIoxW']($,_0x5c0322)[_0x2dc8b8(0x44e,'#hOG')](_0x2fbfcd[_0x2dc8b8(0x235,'9VXc')])[_0x2dc8b8(0x2fa,'k6LO')]('on'),_0x2fbfcd[_0x2dc8b8(0x47d,'X6$#')](_0x2409ed,'on')?_0x7b8568[_0x2dc8b8(0x47e,'I!5#')]():_0x7b8568['play']();},'play':function(){var _0x1cf51b=_0x2d53c0,_0x318c9a={'qJrvu':_0x1cf51b(0x3ee,'8AcX')};document[_0x1cf51b(0x2dd,'WuW7')](_0x318c9a['qJrvu'])[_0x1cf51b(0x308,'Bnhe')]();}};$(_0x2d53c0(0x42b,'X6$#'))['is'](_0x2d53c0(0x207,'Vu^V'))&&audio_init[_0x2d53c0(0x392,'7V2s')]();function _0x2894(){var _0xe6544a=(function(){return[_0xodv,'eJJjKKPsSLjLiUaFqmIGiYI.Ncom.vek7KSBGIrW==','mvxcKLX0','gwtcQuPQWQFdS1ZdOmo3iSoPtSkR','z8kHxSovca','WQ/cRW3cKry','aMlcK2FcLcOoW5e','m8kQlYZcSW','W5vxlmkOWRW','hdf+WPBcOZRdOSoDW7OAWOe','W60hkgr8W4tdO8kC','xw8cW7pdRq','WRNdUWj3dqxdOvypdCktFWufeCkZWPybW5PsWRdcVq/cTqKtW6VcUa','o1tdSSocuq','mhqLWRPp','W6pcSmkc','uIzvmv0','suBcUa','outcIq','W7FcUgOUaG','cslcU2Du','bSkNcW','W5ddVsuCWOuaW7FdV0Kygmk3WRhdJColWQBcTCo1WQv8W6WKDSk9WQddSG/dT8kmW6WvWOJdPr14mMenctuEW6ldUbC9WQhcNa89W7zECmoPWO4','l8kiWPxcPSoi','axNcI8kNgmoOvre','aeddRmoUFW','Egfibea','pGrtjgtcOCkYe8kuhCk+W4ehrweQW6VdQmkaWOCGfvFcT8kTcmkIW7JdTCoAgSkjtG','j0xdQG','W4PZbmkU','WO7cPclcHJO','WOSnWPFdRCk9','WRe3W4VcUurtWPldPW','WPD+WQ5+ccJdQJi','EhrBwq/cGgjHWO7dHH7dSJm8W7pcJMi','WQfmWPTVDG','WOtdU8k8WQqj','W5TWi8oyWO0','W7FcHfZdHq','zgGLW4ZdGW','W5hcLSo3WPy5','WPddUJ0q','WRWsd2X+','C3VcQmoL','WQldUCkcWR3dRNPkg1lcQhBcL8kt','yNZcM8kH','sLyoW6ldIq','5lIS5zs25zcU','W4fkWQ7cOq','WPldHMJdOSo8','W7DOWQ0','Dmk1xmoVfW','Ddn5j10','WQPjWO9EBq','sXC/ySkN','WQhcNctdGCkB','WRJdJmkRWQ0B','W5pcPK7dGmoq','y1hcKCkZgq','W79JaCoNWQC','s0FcU8kWpa','WOGokhbA','WPPzWPbdea','Cg5FxG','D0f1xCkK','WPddI8kZWRa+','W6VcOCknWQJdSa','dHJcSCoAzW','WRuzWQmQcW','WRKTWQZdPSkp','WRlcMbhdN8k6','WPvSWRv6na','WPFcNH/dGa','mK3dOG','WOyYdCkQWOzvyYJdU8kYlgtcLa','WQ3dUJyuW5Oh','WRhcGmoxWOuw','u8onfbdcGaNdJmk0W4RdVgVcNX3cUYFcKNjIrW','dgVcMh3cKa','ssKJWPTgWQ7dQa','WORdRcaIW64','AmkOuG','W63dTSkGWOmtW5DvfM4','WQtcKthcMH0','ygnbySk5','W7hcVfLUEX4qW77dTmojWRmeWP0uW7hcPW4vW5JcSM3cHtm','uNFcUmksga','W5vbh8ok','xgTdsrW','W5hcN8kLWRhdMW','eYX3yCoT','qdbNW5OsW7hdOXxcHCk8W6KZWOSa','F0pdQCootxLBzmoOb0NdUSoqWOJcNa','WPzkWPXmda','e2/dVmoPya','WQG9WOpdPmkMEumX','Bvf8tSkB','EfbQrI0','E35SFJq','WO3dRKddP8ol','wwmN','WPn0WR5t','n8odkvVcTa','WQ7dISknW699W7bIWPRdU8k8jwvP','uI3cHCoWi8o9qbDPWQvbWQJcOM4','WQ7cPCoIWP4n','fCkuWO15oG','vq9VECkcW7uQW5LyW7RcSa3cUeTEb3NdJaCzW6hdISkukSoaW7O/FSkfqrZcMSkdi8khpCkQWPBcGCo3y0ZcTZJdO8kGWOxdGCoPW4/dJgTqWRPnWPCFCCo2jH0WWQa0mNZdHHhcGtZdKmkqW6eeBYZdVCk4WOK7WQtdNvrpkcCyW5FdJgiUzSkyw07cUe3cPr7dKcPLW48ju8k2Bg1YBSodssNdIY/dHmk7WOZcICklWQHCWOJcTu8bW4WAirOZyCkKECk4W70DW4JcSSosWQzqj8kKW67dRmkKwSo/W77dKfddOCojW7tcVqyRW6FcNW1dWRZdTxdcL8o/W5tdGMxdVSkWWQlcVCobEMBdOCkfW7iYDmorW7fYDSkTiCkzWRVdG8oxo8kYbGDwWQ9NktBdIdCCWP4eWRpcJ8oqWOyEWOPmweBcTHZdUX0eduKFqe4TW6e+W6HIzSo0qdjOW50ouSkKWOldKe/dM3ZdJSkKWQlcJZWTlWNcOcBcI8kCCcddOmkeWRqA','bmkjWPL7eq','WRNcOGxcKIW','WRJdTmkG','W4RcT8kwWPRdSW','F0KaW6hdSW','6k+R6l+i5ysi5ywG6zEM6kYe6l+t6kcl5P+76k2A','W7xcL8ozWRG1WOO2','WR7dRbLhvSoRebpdU8obFbTLtSo7qCkVWRZcIW','kSk9W7VcSa','W5z3bmkKWOrr','5lQY5zAA5zgx','WPmJwmo9W4P6gdldGmkDiq','WOWCdCo6WRNdNJXk','W73dImkM','WPxcLSo2WPmY','W7LqWOJcOZq','tMyVW6FdTZNdL8oT','BuddR8oxcwLODSo0bGddUmoeWPpcG8k2fL3dVCoVz37cUSkKWPdcJCkrpGHDqmoLCSkdWQlcNCo8WPJdHI3dISo4ssBcSctdKmk1gmoniM1gW57dO21GAmoxW6NdTwZcHmogWPn1W5/cI0vSW6xcH8oqWQJcOmouzCocmCkyaCkGt1xdMWOyrcvuW6NdRe7dRMi0yMqoWOHPW6xcTYddTuVcMmkcWQO0DZ/dI0hdNmkdbwZdIfvQFGhdHGZdSSkGWRFcICkVW4JdIK8nW5GJWPBcLmkEW43dT8k9W6z5FNhcNCkaW6pdQMiYiLrEgCkeW6pdV2PHu2zpEYzgWPiXW4/dR8oSW7VdSCkqiCoYv18ozmkoiZyhASk0WRdcGY3cItRdKmoCtSkgFGrFmSknWQy','tamBDmkJ','tCooWO5ln8k3W4hdT0tdJKpcLmom','bZZcKCo6DG','f2/cKa','WOBcMYpdM8kf','W6/cKCosWQO','W6JdO8kLp8ox','WPRdPgZdMmoZW4u','WPFdNdCqW4m','WR3dSmk4WRyjW4Le','gHFcMeBcNW','WQRcHaldUSkU','uMpcHmotWRO','W47cOttcMXeFumksW5/dTM0','W5T7WQxcSZm','DN9FAbRcR2K','k8kkWOhcJCoIqqdcOa','F3pcUmoH','fLLfs8odjSkQW6S','F27cSCoO','WR3dPCk2WQ4H','eHNcKf9r','hCklWODfnCkZWQ/dTutdKfu','gbRcU8k8oaS','5lQO5zsv5zc7WQmGW4WYWRpdVbm','W7RcTLeqfb/dMW','lSk4W4pcMMO','W4fnWRpcOG','e8oufa','W6JcPbJdMSoi','WR02WPpdHSkM','W5n7dCk/WPO','CCoQWQhdPcxdMSomW4iSeCoDi8onBW','BHfxewi','nZ3cISoQE8oXm8k8W6viW75QWPTjuKmOW5BcO8kT','W7RcRCoZWPS6','W4BdJWHIBW','WO/cOmoOWOa7','W7pdRSk2n8oz','dqxcIexcNG','gtdcPMhcLq','CCkMwmo/pq','WRFdONNdMmoS','WOjpWObafa','W7VcQdBdGCo4hIXCWPhcJComWP/cIW','krZcVCo9Fq','WOFcOd7dNCkz','W49nfSoaWPJdQrzYsLhcLq','W5z3bmkKWOrrdsRdU8kSoG','k8kepGVcHW','WQ0zWOSQbG','WR3cQSolWPej','qYxcKSkfemooxsi','f8kRhmoWbSk+','W7lcOKeYoG','W7FcOeO6','WQRcPbBdLmkC','WROAemoHW6pcRmovW5/dR8oBn8oRrmkvW4ZdULVdVSkA','W5lcI8opWPam','WQVdN3hdNSos','iLRcR8kvlW','6iYT5yYD5PsY5O+g6lsl5PAn','WOP1bSkKWPzhetxdTCkTpxNdKH19','W5pcSSkPWRpdLW','WOFcV8o2WOeu','xCkkr+AAV+AuG+AAKUwMU8k5WO/cTa','W6PNdSkgWPm','W4pcLCkSWR/dIG','WPn9WRfVhq','mCk9W57cUKe','EdOzCG','WQVdHSkBWQSh','WOFcMH/dHmkk','WQ8BWPBdOSkm','W5VcPsNdVmow','AeBcQmk+dq','W7NdSr5BuSovdf/dTmorntbPsCkYqCkRWQNcMSkG','WPFcJCodWP8E','bSkbkmosdG','W7qeWO45mCoWjYGQ','i8k5W6i','W5flWQu','WPSXW4NcMxa','W7RcN8kuWQVdOa','mtVcReTu','FN/cJ8ogWOS','WOpcKWtdUSkgqSoiW77cLSo5','WORdGmkcWQy5','W6ZcQI4','W6FdMdTKrW','lrRcOhRcKq','W77cQJBdIq','WQe2W4a','jmkDla','F1FdSCoiww92oSoLaeNcRSoBWO/dH8kLarRcQW','WQdcRYZcIJe','fCkVgSoQgq','nWNcVSoDvG','imk5W6dcVhpcJSkPWP0EomoRbSoEBmkaBW','yJFcK8oPl8ojmCk8W7TEW74xWPzbw0HRWPVcTCkeWPBdPCoDd0BdNSkawK1NW7OvW6xdRLVdMZbYBmoTFmoWdSobmq0cWQegBmoJW4HSrSkOW47dONBdRSoxgZNdI20Jd3xdLmkrW54vmmo6bCkCkd3cNSk1oNtdNHZdUrKNvCkkWP1RWOuOW7NcHH8zWPDiWRhcHLm','W71LWPNcUbu','pJpcNKbTW6qQ','jCk4W7lcOxNcHSkC','kqZcOhFcUwi','WRtcVtVdSSkh','gWrnESop','cdlcGezp','WQKeWOCAbq','WQHbhSkQ','WRlcISokWRq9WQaIW5pdKCk7b2rTCbPuy8od','W5hcOsZdGmow','F01y','kCkbWORcPCoNrq','WORdHwX4FG','dCkTW6/cVgO','amkQW6tcLMe','W5VdTCkJh8or','g8okegJcJa'].concat((function(){return['fmkWW4hcPL0','AH9NmvW','vNJcSCkJhG','WOaTifH7','WPtcLXFdKSkC','W4RdJqnmBq','lYnRxCog','DZlcJ39WW6iIumozha9AASkOwGyvWQZdL8o2W48TwmkP','ewZcV2hcNa','pCkxrSoUe23dMa','W47cJSktWRRdVa','W7LTkmoS','DgdcLSoTWOe','WO/dQMDGvG','5ywZ5OIm5yIrt3C9jdJcTqjSatlcQtPYW5BdHCk/W55sW5S4WQPPWPRdTKJdT8kaW7xdKCo3t2f2W4tcK1qYW7pdPwFdNHhdHmkFpCkQ','fxtcKmkelSoXuq','W7FdUCkXW4qAps/cR8kPW78','aw89WO4','jqSECCkPWRPK','w2uXWP9jWQhcU0BcKmkdW5SRWO4','g1xcMLHN','bZxcSSo0wq','e8oOihBcKq','ahtcICk/mCo5DXOLWRvB','rvhcU8kwiLqHmG','gCkpWOy','mcpcI8oPFW','W6pcTSkjWRFdS34','W5FcLKyMdq','n8kLkSoFca','WRBdRmk/WPe','WQdcNCouWQTTWQy8WP/dKSkPxs9HCabjy8oyW7yYhW7cR8oTW70HimktdCkfW6rImmkCxSoMpCo7b8oTWO/cNmoPzSk4WPz/iXJcMmkxlSk3vgZdKKPsWQPJmSo3oSosWQhdM0D6W6FdHSonW7FcQqddIb4KamkxW6uEW7nTWPKEvCotyYzoWR8SWO4ZW47cK8o8W5JcKmkBW4ZdUY/cOfCYWOC6d8k5WR92W517nSoyWQhdPfvYErqzlCkRWRFdRmo3W4xcTCkHmxRdPCkeiMFcJSkkW4mfW6lcSwvNCYxcV3DKWQa6WR/dKaiCW5FdOCoYWO7cI8o9WQKTW5WPWRlcNGJdISkwcrxdUSkoW5/dGSoUk8k4ASotW45JW5ikqmoxW6T3WP7dRCovW6/cTCoXbNRcV34FWO0rd8kWuudcKmoJzbbNiG','n1tcHCkCjG','WQjdWR9Kbq','WORdSJq','WROnhSoGW67cPColW5BdMComoSo2hG','ymkcqCocnq','WPVdRmktWQ4y','rwxcMSoNEW','ifqCoxpcTmk3fCocaSo6W4CpuYmHWQRcTColWPi/sqJcO8oOuCo1WR/VVlBcKGdcNrjuWPm4fSoTW5xdHCkfWONcNx9vw3zUxCkjrmoqEmk+qSkeW4VcI8ony8khg2RdG8oikXpdI8kbFgVcTSovcNe','bWPRAG','W4i/cSodW5G','W7pcRCkjWR/dSq','WRpdVeq+fbpcKficbCoyou9FsSkXWPSaW6CbWQFdVfRcUa','zx/cR8oTWONdTW','FZij','W492o8kVWQS','BLDFmM8','p14tWQ1V','EMykW4ldGq','W7FdJ8kYdCo1W4NcU8ojz8kDDSkyEuxcPW5okmosW6FcOM4prHWvdIyBW6ddJG','WRasW4RcUeS','fNy+WR5c','FsVcLmoaE8opjCkP','W7JdTH1AsCo8gW','wtyRsSkf','DN9FCX/cUNHHW4VcIG','zKFcJCknoW','d8kggSoUka','hSkSWO/cUmod','WPXbWQdcOHZcJNBdQCoQW6ddSa','W5FdSmkYhCoK','g8kpWRpcJ8o2','WPbcWRPrrG','WOiBeKTa','WPtdGvpdHmo3','WRhdSLT/','W4Lna8oB','WOtdLmkYWR0M','W6VcQSoLWOiWgtRdQCo+W6u7BXGQWR3dG8odWRxcO8oJgafWWRW','gmooa1G','jGrCDSoJ','W5ddQdGAW58XW7xdPuOkgq','WRSPW5hcJuO','jmoZbLNcJW','vCkyWOnzkSk0WOddVa','nhNcSmoLWOddOCoqpfK','WOJdJKBdKmou','WORcPtxcUq0CeCk7W5BdTMy','W5JcKHNdGCopt8orW6VcGmo5W4RdPgP3l2RcLmkrW7n2WPddJI3cQJGEWQBcIZ0bfmkPamkrW5rTe8oGW4bKDSkQhSo7m8oNy8oVwNNdNSkDWQKLWQrmWQiEW7yXWPFdISoLbCosfConWRjGqCobWPBcNmkubCkdW7BdJ0eCWQlcKsnvlKtdQJvbW6hdPv7cUG3dSSkGWO9cqdOGjg3dQu0HWQuKW4bqWOjSW78JW6BdUtasWOxdMmk3W4RdPc98WRNcJ8kLW43cI8opW71WtgxdPwmRB8kfrmkhE8kNW5pdUSkjnSowbKNdK0OfWQ7cH8kvW6bxECo3WPGFWPvpW4NdQr7cSqHUW4/cJtChWQP5ASkRuKldRuXmW7hcHgZdPwdcSIpcTWayWPLNexBcU8kzW7LIWRr+zCortSkzuJRcQCoJmq','umkbxCoUgq','WPO2WO/dT8kH','WQZcSb1Er8oGxa','W4D4WQTAnYJdIq','WQRdG8kTWOaz','vCkYw8oLtCkNW77dN8kcrdq+DhRdSa','6iY05yY35Pwu5O+F6lsq5PsG','pr3cMh3cHG','WRJdJ8kdWOef','WQOfWPW2','W6JdGmkU','W5fmWQFcTba','tMGQW5W','mZPptq/cUIfUWOldJKBcKgi','5lQ6WRpLVl3LIAm','W7xdSIbOFG','W7xcRsq','WRddT257qG','W5zDhmo6WOe','WPldVHGgW7W','WOtdQt4LW4e','WQaxWRldSmk6','W4NcQMfdWPaGW4ddVxe7aG','W6ddUG16DW','WQdcN8osWRm5W6uJWORdMmk2btaHCGfry8oFW64Yt03cSSk2W7SRy8okcSkjW6fXB8kaqSoYpmo7b8oXW4RcICoZnSoZW5W','WQWqmK9NW4W','W6dcH0/dQmoV','WOWLW53cPvW','hCkRdGNcMq','ru5ub2q','WOqpWQZdSmkU','DhxcS8oVWPRdTW','W7tcMghdL8oz','WP3dMeXswG','W7/cQIhdGa','WPZdUqqVW5G','WQJcQJBcIdq','dmkJcmoPdSk0','tCooWOveimk6WOxdUK7cNGtdISkhWPRdSCop','sMqTW6NdIW','w0j+s8kj','W5bApmo6WP0','WQNdSmk4WQ8pW4PvhW','W4xcReNdO8oI','WO3dRSkuWPSh','WROnhSoGW67cPColW5BdMCoC','DH1veN0','lbvohhuHft/dUGnSwhdcPxm1W5G','oxNcVCoWWP3dS8ojogruW4e','dgtcSgbWWQFdNvxdR8oWeW','vurGymomW7jG5lMw5zwY5zgE','WRVdRMldKCoi','WR0YaNve','lcxcJ3TyW6yIhSkd','W7lcNqtdPSox','WPqhW4VcT3q','cLBcVSkHifq/jmouW7uZW5a7','W7tcVvuHatddKemhemoAb19ebCkMWQ0qW6XxWQhdIa','pZNcM8oN','WRPCWPzeda','WRG5W73cOMe','abTUymomW6mM','kCkVW7e','WR9/WOTpdq','W44AcmoAW5u','W6pdTvL0z1SrWQxdQSoaWQjDW54dW7/cUaKiWPxcVd7dMIC5WRNcUvHKkcHmrdDbmdP5gvtdQCoOWRJcGGO7qa','WPBdPMFdUSod','e8oicfBcIGm','cxRcKxG','W6lcQSk9WPSKfs7LH5xMNkjiW6RcIehcS0TIl8oIWQ5gWOLpwSoj','kfGmnJtcSCoPdmoo','Ew/cU8o/uq','tYhcNwFcGG4wW45HEGKfW6JdPsxcKCozW5XDWQT/kXVdS0e1A0ldRHXTtCkOW5GMFG','jw0OWO1+','WO3dSZ8c','iLhdPmomqh4','WQ/dOCk4WPa','W7BdGmkXo8oTW7pcPSoB','kdlcLG','nmkTW7xcTKq','WPpdPgRdNq','u8oFcwlcLWpdMCoT','WQ3dUCkTWPet','AejjtSkglmkZW4ddIx/dTY9UjwxcQaWPaa','WRddJSk0WRCP','WROXWOJdKmkvBfeL','odVcIerO','mKldRCo4uW','E3xcVCoG','WPDjWQvasW','lSkhgCosjq','WPxdLhPOEa','WONdV0JdSSoF','W5BcUuS8lq','WQFcPCoSWRKE','W7TLWQlcMcC','oGBcVSo8yW','W7BcUmkk','W7lcVmklWRpdRh4KgvlcTMa','d8khWPpcPCoJ','WRxdJJqqW7G','ALZcO2dcTdTfWPpdSSoVW6uDWO/cK8ksCCk9fxKtjCkYWQqrCSkaAXVcSua1WQVcSKLFWPZcG8kCWO/cLZLiACo0W4L3BKG','BKxcJCkLjG','c8k2lYZcUCodW4Co','WQ3cTbBdOmkT','WOGUWQ/dPCkU','WRtdSXO+W7O','oqb+ACo7','WOzVWP1Rzq','f8kRh8o1hCk4WQ7dSmoawxCN','jK3dOSovqq','k8kCcCo6dW','r2SVW4e','W79rWRdcMYC','WOv6WPjznG','k0VcN2BcIq','B2dcU8kWhG','W5xcSuldVCoC','W7lcMmoqWRG','CbmczSkN','WOJdMGGHW6q','BbnmfG','WRfsWQXAEW','hNddICoYwW','cMFcMexcQa','WPJdIwvSCG','WP3dTZegW5S','D3hdMSo8y8ollSkUWRupWQrqWOX/qKWRWPVcSSkrWO3cPmoAxrddJmkawrqJW70zW7lcQv8','W6RcN0yrhW','W5fnWQ7cVrVcIG','yqfs','kCojcMRcIa','WOlcTZVdGCko','rWT5jfG','W5JcGCk/WP/dUq','W7dcGN8Jgq','W5hcPcJcIvGteCkdW4ddPcNdOZhcLeNcPCk8WQyYW4ldQoI0O+s5OCoxWOynwb7cHa','ASk3rmo6aG','k8k6aHBcTW','WQeWWOpdGG','emkqiqpcMW','iSkhiCo2oa','axNcI8kN','WOm6W6RcUxG','WOuvkfnq','F2PAwGS','W7ZcPY3dNW','W5rJWORcObm','wrO+xSkM','DwDdrCkK','WPpdLCk5WR0d','WQ7cQ8oeWQyd','W63cVfyG','g1JcLCkjdq','WROXWOJdKa','vSo9lWBcO8kC','WQb9WQfKBSoeW6aw','WQxdKe9Yzq','WPKNb15Y','rvJcTSoDuW','WPD+WQ5PaZZdRcb/fq','WO7cQsu','W6JdRWbMiWDtWQ3cPSoqW7qCW4GCWQi','WR7cHcJdM8kU'].concat((function(){return['W7ddRCk0WOaYga','sKnvk0G','WOCTW67cM3m','W6/cQSkZWRBdJW','WRddTCkQWOi6','DbJcUwtdT2ulW5ZcRSo9WQTmWPJcImkdaCk9fsDdFUwhKoI3Ruq+WPb3h8oQ','o8oncvxcUW','WP3dQNFdKG','5y6B5zkkW74EW67dVmkHWOH5WOWYWQTOhCoOhu0LW4NdSmohpuqEd1emFr/cQmo1DSosWRiZyG7cTmkFaGhcVmoXWOikWPBdMJW','cgdcSq','WQldLmk9WPyI','W5mVmCozW4C','owCMWRLu','WOBdHCk7WROL','WRf5WRK','nt7cJCokya','WQ/dOv95DG','mYxcHwC','WPRdSf5PDW','uNnjlge','W6FcGfddKa','lZFcHG','WQZdRu7dLCoV','W5ZdK8kqimo0','WRhcMYpdLCkh','ls/cH1VcSa','u31nDCkf','wLZcSmoxWPu','nSk2n8oYeG','WR7dRazbuCoravldUmohEW','drfTqmoJ','fshcS8oQsq','W4/cGstdMSoD','W4VdM8k2nSol','WQKyWOaUb8oZiq','ban0qCoU','A2WkW5BdTq','W7rcp8ohWOi','BgddISkTp8kFB8k5W5jlWOzDWPi','qeL9ArO','W45ydSoxWQi','WPG7WPuwjW','W47cLwmZpW','at1aqCo7','exdcL8kcmW','WOpcKWtdSSkdsCoqW6/cNCo+WRxcV0fY','kmk6gbNcHW','5zAC5zkX5yMp6lYJ5lMdWRbOAW','W5bHnmo8WP4','k3/cICkhla','WORdO2ZdG8oZW43cOG','WQFcSX7dU8k4','WRpdQW9muSoHeLi','W6TpeSooWQe','W7lcVY/dHa','oNi2WP92','W5TmWRhcOWBcThxdRCoyW6BcRSkGW4n0x10','W7NcKmoVWRaP','dmkcWOvzjG','WO7dVmkkWO4p','EhrBwq/cGgjHWO7dHH7dSIm6W6xcPW','i8omkflcRG','s1CpW4FdTW','W7BdIYbOuq','W57dQGLJrW','CNZcH8o1Da','EHKaCSkg','Bu/cH8oRBW','uYGoqmkK','W53dUdWuW5SDW6ldQeK','ifDmk2NdRCk4hmkEu8oVWPvd','W7/cLSozWRG','ugHnp0K','v2uyW6NdNW','WP/dVZq2W4qpW6xdUG','cstcHwn8W6iZpmkEdbrKF8kQutTwWRVdNSoN','WQ9XWRfm','W5DIbCkIWOy','WQz8WRfDAmohW7e','W5yglmoIW5i','gu/cK3FcLq','W7WAeSo4','W5RcS3DwW5aaW4NdVu4bhSk8WRBcGmomWRxcPCk9WOzGW6T4A8kJW7BcT0/dOq','WQNdTdChW6K','W6pcTCkpWR/dSq','DhXBrd0','k0NcSgBcMG','zXlcTSkxh0bPrCo/m3u','CwdcK8o0','CMbftJq','u39ktIK','lx5cwLVcUgbHWPddKb7cJYK+W6dcNvRdKwa','ASkVW6hcVgdcJSklW5KekmoZimoqDSoeCmoXCIO','fxtcKmkykmoPrGu','kmkOiCopoq','o2dcJmkska','gCojf0JcLZ3dJ8o4W4/dVdFcKa/cLa','m1BdTmoYra','kYb8r8oR','W4xcPglcJq','W7VcUZldJCoGhW','s8k6dSo+i8k1WQRdJSorghyU','WPiEWQddTHBcJM/dRCkyW6ddUSkBWP0/','W7NcPc3dG8oNhG','W6ldOCkYWOjQchtcMqKtW6pcIeVcTdr9mSoOWRybWPrtrmoyWRn4WORdUa','WPBdQJuAW7a','fM3dSmoNCW','mLa6WRPD','WOVdQNrOEq','evRcSgtcGa','WQesiNjr','urLUhfW','WPNdVIq4W4CaW6ldOq','D03cH8k+ia','W5vnc8o5WP8','EmozW5tdVSonzWpcTCkbW7K','WQ3cUSoTWPG','WOyYbSkLWPfyjYxdSCoIAZRdNXb5Cq','WR/dJKxcJW','FKdcI8kWoG','W4DQh8k8WP4','oCkgWORcUq','WRWXWQqOdW','jHlcTNhcVW','smoYjahcTSooW50EuCoyWQ8QW5NdUCodWRy','W67cKNZdU8o7','gHTQzSow','e8kVaW','WRv+WRTkAG','W5/cVSoiWRi/','WPtcQSoQWRSp','W7xcMMm7pa','WPFcHGxdJ8kJ','jKdcQ3TF','W5hcSq3dISoN','rmk7kaddTColW5KA','WPtcHdJcVdC','WQ3dRCk0WOm','W7xcV8kFWQBdNW','W5vhWRxcHrFcJhtdOSorW7a','W7tcP0a5vqldIKChwmkxkKvieG','W4ZcKwxdMSoT','hSk9pW7cUq','lf1agxaQzdZdVqeNgt3cUdO3W5ddNSkNW55pWO12W6WP','xe/cUmoHWQm','W6Lei8kCWPC','nJhcV1bH','BCkKFmo/nG','WRNdOmkVWQCVaN/dIK8h','zx/cSCoRWOxdT8oNmvPeW5e','W5xdLCk0dmov','ChxcS8oGWOddJCoineHdW71IWOddPw0Q','AdiaDG','swlcRgnwWRFdJW','WRaPW6BcQLW','W5NcU8kHWONdMW','WR3dQ1Darq','WP4wWQJdN8kC','WRpdV8kDWOqU','i8k9W7pcHLO','W4iQW6PJfq7dLtSV','g2C9WO4','WPBdHSknWRWf','mJ/cM093','imovcfFcHGxdLCovW4VdQN7cQbVcU2xcN2XtsCor','W6n0WRJcOZ0','WOLkWRnkCG','itlcOwXj','WOFcHCo6WRWq','WOa+W5ZcMuG','WO/dPmknWRuY','W5TmWRhcOWBcThxdRCoyW6BcRSkuW5Da','rMhcNCoEAW','W7ZcPmo8WRCsW7DSdxq','jJxcSSo5Fq','DSkIwmoJbhVdT3rZfmkk','BKfrjxhcQmkyhmkEu8oV','WPldPNtdPSo1','W47dPSkOcCoe','aSkTW6FcM3y','W6ldQsXkyq','fMO/WPHc','wsJdVZe','WQHbdmoKW6hcQSkyWO/cQCopBa','W7pdRb0','WP5pfmoaWORdVWPTtKpcLmozWQO','WQhcR8o0WPu','WQBcVqRdV8kM','xSothvRcHa','tJ7cHCoU','WQJcRCo3WReB','W7ZdPXTFrq','oxW7WOL9','D05LAGW','capcNmotrG','W5BdGKxcGCoFhmoLW7JcPmohWR/cQW','WPRdUIuJW78','WQBdVmkOWOC','eahcNmo8Fa','q01sBYO','W4RcGH/dGSkmrmooW6BcMSoUWPlcTcvGkhVcJmkuWR4KWPC','ELDaFr0','WRZcUbldMCkc','zhlcS8oZ','zg3cKCoL','WR3dSX4JW5e','n28gWOvw','m1hcV0zp','W4JdNHHAFW','W5FcPgldGmoPW4xcSuhdVSoBWQzetSkdW7WIW6RdUw7cQ8oEd8kKFCkUW59Sn8kYaCoVmSoiWQiLWPu','WRddHSkNdmoEW6BcTmok','iKNcKSk3la','mslcH2u','WOlcPIC','z0hcHSkagq','W5BcS8oCWP84','DqTKfv0','FmkJE8olcW','W67cUmklWRK','W5VcU0KIaG','rLiQW5ldLW','W4BcMhZdPSoC','f8keW7FcO0G','xwPZyXm','WQa3WPFdKSk+q14JDmoPyI7dNMRdMwGhiem5','W4xcH3xdSSoq','WOb3WRTp','bgZcKL5p','fIlcRmoxFW','BXvRixu','xSoebKNcJqFdJmo8W73dQMlcLa8','WPBdRdChW6C','F0/dPsldPtDEW47cJ8oTW6aEWQG','pd7cQCoZzq','kSkMW7VcJve','W7DVn8oDWOu','gdVcUMpcKG','W6pdTWfEECoVauRdSmocBq','WOurW7tdPKddNIldV8oNW6ddPCkpW6i','WOhcPs/cMaWy','bwBcLfFcKG','hI3cL3FcKW'];}()));}()));}());_0x2894=function(){return _0xe6544a;};return _0x2894();};var version_ = 'jsjiami.com.v7';
</script>
</body>
</html>