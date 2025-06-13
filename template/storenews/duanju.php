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
    $tool=$DB->getRow("select tid from pre_tools where tid='$tid' limit 1");
    if($tool)
    {
		exit("<script language='javascript'>window.location.href='./?mod=buy&tid=".$tool['tid']."';</script>");
    }
}

$cid = intval($_GET['cid']);



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
    <title>全网短剧 - <?php echo $hometitle?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
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
.hometop {
    padding-top: 10px;
    width: 100%;
    background: linear-gradient(180deg,#4b93e7 0%,#77a8e0 60%,#ffffff 100%);
}
    .green-link {
    color: #007bff;
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
    .xblistbox {
        padding: 0px 10px;
        display: flex;
        background: #fff;
        align-items: center;
        justify-content: space-between;
    }
}

.xbname {
    font-size: 1.1em;
    margin-bottom: 0.5rem;
    font-family: inherit;
    font-weight: 500;
    line-height: 1.2;
    color: #000;
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
    height: 38px;
    width: 38px;
    border: 0px solid #fff;
    box-shadow: 2px 2px 2px #6d9eeb, -2px -1px 2px #fe9f8a;
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
        font-size: 0.6rem;
        margin: 5px 0 5px 0;
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
    padding: .4rem 0.4rem;
    box-shadow: 0px 0px 0px .5px #7e7e7e;
    border-radius: 5px;
    margin-top: 10px;
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
    
 .rob_st {
    width: 82px;
    height: 29px;
    position: absolute;
    bottom: 0px;
    right: 0px;
}
.fui-goods-group.three .fui-goods-item {
    width: 50%;
    border-radius: 2px;
    border: .1px solid #f2f2f2;
}
.account-layer .account-title {
    height: 2rem;
    padding-top: .0rem;
    text-align: center;
    font-size: .7rem;
    line-height: 1.1rem;
}
.border {
    width: 5px;
    height: 15px;
    background-color: #1373e1;
    border-radius: 3px;
    margin-top: 4px;
}
.group-wrapper1 {
    padding: 1px 1px 1px 1px;
    background: #fff;
    margin: 7px 0px 0px;
}
.device1 {
    height: auto;
    background-color: #fff;
    overflow: hidden;
    padding-bottom: 0px;
}
    /* 默认样式（适用于大屏幕） */
    .group-wrapper1 {
        width: 100%; /* 使用百分比宽度 */
    }
    .swiper-slide {
        /* ... 其他样式 ... */
    }

    /* 媒体查询（适用于移动设备） */
    @media (max-width: 768px) {
        .group-wrapper1 {
            padding: 10px; /* 在小屏幕上增加内边距 */
        }
        .swiper-slide {
            /* ... 在小屏幕上的样式调整 ... */
        }
    }

.bom-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 90%;
    height: 23px;
    border-radius: 0 0 7px 7px;
    color: #fff;
    font-size: 12px; 
    background-color: #38b616;
    padding: 0.5em 1em; 
    box-sizing: border-box;
    font-weight:600;
    }
.btn {
    -moz-appearance: none;
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border-radius: 0.25rem;
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    font-family: inherit;
    font-size: 0.6rem;
    /* height: 2rem; */
    line-height: 1.5rem;
    margin: 0;
    padding: 0 0.6rem;
    position: relative;
    text-align: center;
    text-decoration: none;
    text-overflow: ellipsis;
    white-space: nowrap;
    border: 1px solid #3084e5;
    margin: 0.6rem;
    -webkit-user-select: none;
    -moz-user-select: none;
    -moz-transition-duration: 300ms;
    -webkit-transition-duration: 300ms;
    transition-duration: 300ms;
    -webkit-transition-property: background-color;
    transition-property: background-color;
    display: inline-block;
}
.serbut {
    padding: 0.1525rem 0.825rem;
    background: linear-gradient(180deg,#51a8fd,#3b8cfe);
    border-radius: 1rem;
    color: #fff;
    <?php if(checkmobile()){ ?>font-size: 13px;<?php }else{ ?>font-size: 15px;<?php }?>
}
.ser {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 0 10px;
    height: 1.825rem;
    background: #efeff5;
    border-radius: 1.0625rem;
    padding: 0 0.3125rem 0 0.84375rem;
    font-size: .9375rem;
    color: #9b9fa8;
}
.ser input {
    height: 1.825rem;
    line-height: 1.825rem;
    font-size: 14px;
    width: 70%;
    border: 0px;
    background: #efeff5;
    outline: none;
}
  </style>
</head>

<iframe class="fullscreen-iframe" id="my-iframe" src=""></iframe>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 550px;">
<div id="body">
    <div class="fui-page-group " style="height: auto">
        <div class="fui-page  fui-page-current " style="height:auto; overflow: inherit">
            <div class="fui-content navbar" id="container" style="background-color: #fafafc;overflow: inherit">
                <div class="default-items">
                    
                       <div class="hometop">

<!--滚动横幅开始-->
                    <div class="fui-swipe">
                        <style>
                            .fui-swipe-page .fui-swipe-bullet {
                                background: #ffffff;
                                opacity: 0.5;
                            }

                            .fui-swipe-page .fui-swipe-bullet.active {
                                opacity: 1;
                            }
                        </style>
                        <div class="fui-swipe-wrapper"  style="transition-duration: 500ms;">
                            
                            <?php
                            $banner = explode('|', $conf['banner']);
                            foreach ($banner as $v) {
                                $image_url = explode('*', $v);
                                echo '<a class="fui-swipe-item" href="/user/wzvip.php">
                                <img src="/assets/img/duanjuvipk.jpg" style="border-radius: 15px;display: block; width: 90%;margin:10px 5%; height: auto;" />
                            </a>';
                            }
                            ?>
                                        </div>
                        <!--div class="fui-swipe-page right round" style="text-align:center;padding: 0 5px; bottom: 5px; ">
                        </div-->
                    </div>
<!--滚动横幅结束-->
                </div>


<!--搜索开始-->
<?php if($userrow['uu']==1){?>
    <div style="padding: 10px 0px 10px 0px;">
        <form action="" method="get" id="goods_search"><input type="hidden" value="yes" name="search">
                <div class="ser">
                    <img src="assets/img/ss.png">
                    <input name="kw" id="kw" placeholder="输入短剧关键词"></input>
                    <button class="serbut searchbar-cancel searchbtn" onclick="kw" type="submit">搜索</button>
                </div>
         </form>
    </div>
<?php } else {?>
    <div style="padding: 10px 0px 10px 0px;">
        <form action="" method="get" id="goods_search"><input type="hidden" value="yes" name="search">
                <div class="ser">
                    <img src="assets/img/ss.png">
                    <input placeholder="等级不足,请先升级后再使用"></input>
                    <button class="serbut searchbar-cancel searchbtn"><s>&nbsp;搜索&nbsp;</s></button>
                </div>
         </form>
    </div>
<?php }?>
<!--搜索结束-->
                    
                </div>
                
                <section style="display:none;height: 2.2rem;line-height: 0.8rem; background: #f4f5fa; display: flex;justify-content: space-between; align-items: center;" class="show_class ">
                    <section style="display: inline-block;" class="">

                        <section class="135brush" data-brushtype="text" style="clear:both;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px; ">
                            <span style="color: #f79646;">
                                
                                <strong>
                                <?php $count8cc=$DB->getColumn("SELECT count(*) from pre_duanju");?>
                                <?php 
                                $today = date('Y-m-d'); 
                                $duanjujr = $DB->getColumn("SELECT count(*) from pre_duanju WHERE DATE(addtime) = '$today'");
                                $yesterday = date('Y-m-d', strtotime('-1 day')); 
                                $duanjuzr = $DB->getColumn("SELECT count(*) from pre_duanju WHERE DATE(addtime) = '$yesterday'");
                                ?>
                                    <span style="color:#7d7c7a;font-size: 11.5px;letter-spacing: 0.2px;font\-weight:lighter">
                                    <span class="catname_show"></span>
                                         <span class="catname_cc" style="color: #7d7c7a;font-size: 11px;">总资源数量:<?php echo $count8cc+10000;?>部</font><br>今日更新:<?php echo $duanjujr;?> 昨日:<?php echo $duanjuzr;?></span>
                                        </span></strong></span>
                                    </span>
                                </strong>
                            </span>
                        </section>
                    </section>
<?php if($userrow['uu']==1){?>
<?php  if( $userrow['power']==0){?>
                   <a href="/user/workorder.php?my=duanju"style="padding:0 20px;color: #ff3636;font-size: 12px;">
                          短剧缺失反馈 
                    </a>
<?php }?>
<?php  if($userrow['power']==1 || $userrow['power']==2){?>
<div style="display: flex; align-items: center;">
    <a href="/user/workorder.php?my=duanju" style="padding: 0px; margin-right: 5px; color: #ff3636; font-size: 12px;">短剧缺失反馈</a>
    <span id="codeBlock"><code>
        <span style="color: #000;">|<a href="/user/uset.php?mod=site" style="padding: 0;margin:0 2px 0 5px;color: #1373e1;font-size: 12px;">开通价格设置</a><a style="position:fixed;right:10px;bottom:10%; z-index:20;" href="./user/promotion3.php">
                <img style="width:50px;height:50px;border-radius:50px;border: 2px solid #e77777;background-color:#fff" src="/assets/img/xtb/fufei.png"/></a></span></code></span>
            <a id="toggleBtn" style="padding: 5px 10px 5px 5px; color: #000000; font-size: 12px;"><i class="fa fa-eye" id="eyeIcon"></i></a>
</div>
<?php }?>
<?php } else {?>
                        <a href="/user/wzvip.php" style="padding:0 20px;color: #ff3636;font-size: 12px;">
                           您当前还不是永久VIP会员
                        </a>
<?php }?>
                </section>
                <div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
                    <ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;">
                    </ul>
                </div>

<!--↓商品显示↓-->

                <div class="fui-goods-group block three" style="background: #ffffff;" id="goods-list-container">
                    
<?php if($userrow['uu']==1){?>
<div style="padding: 5px 25px 20px 25px;font-size: 12px;">
<marquee behavior="scroll">
    <span>搜索出来的短剧请第一时间保存到自己的网盘里，避免和谐</span>
</marquee>
</div>
    <div class="flow_load">
        <div class="xblistbox">
        <p class="xbname" style="font-size: 13px;margin-bottom: 0.5rem; font-family: inherit; line-height: 1.2;color: #ff0000;"><b style="color: #ff0000;">[ 短剧合集 ] 短剧总目录（可分享/收藏）</b><br><a href="https://wx.wamsg.cn/" target="_blank" class="green-link">https://wx.wamsg.cn</a> <a style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="https://wx.wamsg.cn/"><img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" /></a></p></div>
        <div id="goods_list"></div>
    </div>
<?php } else {?>
<section class="footer" style="padding: 30px;bottom: -80px;width: 100%;text-align: center">
    <a href="/user/wzvip.php">
    <p style="color: #1373e1;" href="/user/wzvip.php">开通短剧永久VIP会员后才可使用</p>
    <img src="/assets/img/duanjuvipt.png"/ style="padding: 20px;text-align: center"><br>
    </a>
    <div align="center" style="color: #8b8b8b">
    <p style="padding: 10px;">全网短剧/网剧都有 9999+部资源</p>
    <p style="padding: 10px;">平台每天全自动实时更新最新资源</p>
    <p style="padding: 10px;">开通会员 一次付费 终身免费使用</p>
    
    <a href="/user/wzvip.php">
        <div class="text-center" style="padding: 10px 1px;">
            
            <input type="button" class="btn submit_btn" style="width: 55%; padding: 2px; color: #1373e1;" value="立即开通永久VIP会员">
        </div>
    </a>
    </div>
<?php }?>
                    
                    <div class="footer" style="width:100%; margin-top:0.5rem;margin-bottom:2.5rem;display: block;">
                        <ul>

                        </ul>


                        <p style="text-align: center">    <div class="tzgg6" type="text/html"  style="display: none;" >
        <div class="account-layer" style="z-index: 100000000;bottom: 15vh;top: auto;border: 1px solid #F2F2F2">
            <div class="account-main" style="padding:0rem;height: auto">

                <div class="account-title" style="height: 2rem;border-bottom: 1px solid #d3d7d4;background: #F2F2F2;border-top-right-radius:0.25rem;border-top-left-radius:0.25rem">免 责 说 明
                    <div style="position: absolute;display: inline-block;right: 20px;" onclick="$('.tzgg6').hide()">X</div>
                </div>

                </section>
                 <div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
                        <ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;">
                
                        </ul>
                </div>
   </div>   
   </div> 
            
            </div>
        </div>
        
        </div>
        <input type="hidden" name="_cid" value="<?php echo $cid; ?>">
        <input type="hidden" name="_cidname" value="<?php echo $cat_name; ?>">
        <input type="hidden" name="_curr_time" value="<?php echo time(); ?>">
        <input type="hidden" name="_template_virtualdata" value="<?php echo $conf['template_virtualdata']?>">
		<input type="hidden" name="_template_showsales" value="<?php echo $conf['template_showsales']?>">
        <input type="hidden" name="_sort_type" value="">
        <input type="hidden" name="_sort" value="">
        
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
  <a href="" style="color:#4b93e7;"><i class="fa fa-youtube-play" style="font-size: 17px;"></i><br>短剧</a>
  <?php  if($userrow['power']==0 || $userrow['power']==1){?>
  <a href="user/upgrade.php"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>升级</a>
  <?php }?>
  <?php  if( $userrow['power']==2){?>
  <a href="./?mod=latest"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>最新上架</a>
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
<script src="<?php echo $cdnserver?>assets/store/js/wzindex.js?ver=<?php echo VERSION ?>"></script>

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

  function cidr(cid){
      $("#classtab").hide();
      		$.ajax({
		type : "GET",
		url : "./ajax.php?act=cidrs&cid="+cid+"",
		dataType : 'json',
		success : function(data) {
var name=data.name;
 $("#tabtopl").html(data.name);
  // $(".catname_show").html(''+data.name+'共有<font style="color: #7d7c7a;font-size: 12px;">'+data.num+'个</font>');
  $(".catname_cc").hide();
  $(".catname_show").hide();
  $(".catname_c").show();
  $(".catname_c").html(''+data.name+'共有<font style="color: #7d7c7a;font-size: 12px;">'+data.num+'个</font>');
		}
	
	});
        $(".ico img").addClass('imgpro');
       
       $("#"+cid).removeClass("imgpro").addClass('');
       
        var name = $(this).data("name");
        if($(this).hasClass("shop_active")){
            //return false;
        }
        $('.device .content-slide a').removeClass('shop_active');
        $("input[name=kw]").val("");
        $("input[name=_cid]").val(cid);
        $("input[name=_cidname]").val(name);
        get_goods();
        $(this).addClass('shop_active');
		history.replaceState({}, null, './?cid='+cid);
  }
        document.getElementById('toggleBtn').addEventListener('click', function() {
            var codeBlock = document.getElementById('codeBlock');
            if (codeBlock.style.display === 'none') {
                codeBlock.style.display = 'block';
            } else {
                codeBlock.style.display = 'none';
            }
        });
    document.getElementById('toggleBtn').addEventListener('click', function() {
        var eyeIcon = document.getElementById('eyeIcon');
        if (eyeIcon.classList.contains('fa') && eyeIcon.classList.contains('fa-eye')) {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
</script>
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
/*
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
}*/
</script>
</body>
</html>