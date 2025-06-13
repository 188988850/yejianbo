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
if(!$cid && !empty($conf['defaultcid']) && $conf['defaultcid']!=='0'){
	$cid = intval($conf['defaultcid']);
}
$ar_data = [];
$classhide = explode(',',$siterow['class']);
$re = $DB->query("SELECT * FROM `pre_class` WHERE `active` = 1 AND cidr=0 ORDER BY `sort` ASC ");

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
    <title><?php echo $hometitle?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    
    <!-- 预加载关键CSS -->
    <link rel="preload" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css" as="style">
    <link rel="preload" href="<?php echo $cdnserver; ?>assets/store/css/style.css" as="style">
    
    <!-- 合并主要CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    
    <!-- 异步加载非关键CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>template/storenews/user/yangshi/foxui1.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index1.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/class.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic?>layui/2.5.7/css/layui.css" media="print" onload="this.media='all'">
    
    <!-- 使用本地Font Awesome替代外部CDN -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/font-awesome/css/font-awesome.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" media="print" onload="this.media='all'">
    
    <!-- 添加noscript回退 -->
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>template/storenews/user/yangshi/foxui1.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index1.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/class.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css">
    </noscript>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>template/storenews/user/yangshi/foxui1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/class.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic?>layui/2.5.7/css/layui.css">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">
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
    height: 38px;
    width: 38px;
    border: 0px solid #fff;
    box-shadow: 2px 2px 2px #d0e0e3, -2px -1px 2px #87b4e8;
}
.layui-flow-more{
        width: 100%;
    float: left;
}
.fui-goods-group .fui-goods-item .image img{
    border-radius:10px 10px 0px 0px;    
}
.fui-goods-group .fui-goods-item .detail .minprice {
    font-size: .6rem;
}
.fui-goods-group .fui-goods-item .detail .name {
    height: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    font-size: .6rem;
    color: #262626;
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
        color: #4b93e7;
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

        border: 0px solid #4b93e7;

    }

    .fui-notice:before {

        border: 0px solid #4b93e7;
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
        font-size: 0.65rem;
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
    color: #4b93e7;
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
    height: 3rem;
    padding-top: .0rem;
    text-align: center;
    font-size: .7rem;
    line-height: 1.1rem;
    font-weight: 600;
}
.account-layer .account-next, .account-layer .account-btn {
    height: 1.5rem;
    background: #4b93e7;
    margin: 0 0.9rem .2rem 1rem;
    font-size: .6rem;
    text-align: center;
    color: #fff;
    border-radius: .25rem;
    line-height: 1.5rem;
    display: none;
}
.border {
    width: 5px;
    height: 15px;
    background-color: #4b93e7;
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
            padding: 5px; /* 在小屏幕上增加内边距 */
        }
        .swiper-slide {
            /* ... 在小屏幕上的样式调整 ... */
        }
    }
.bom-btn {
    /* display: flex; */
    /* align-items: center; */
    /* justify-content: center; */
    /* width: 90%; */
    /* height: 23px; */
    border-radius: 7px;
    /* color: #fff; */
    /* font-size: 12px; */
    background-color: #7cafea;
    padding: 0em 0.5em;
    /* box-sizing: border-box; */
    font-weight: 600;
}

    .fui-icon-group .fui-icon-col .text {
    font-size: .55rem;
    line-height: 1.05rem;
    padding-top: 0.1rem;
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
    <?php if(checkmobile()){ ?>
    margin:0 80px 0px 0px;
    <?php }else{ ?>
    margin:0 150px 0px 0px;
    <?php }?>
    height: 1.825rem;
    background: #FFFFFF;
    border-radius: 1rem;
    padding: 0 0.5125rem 0 0.84375rem;
    font-size: .9375rem;
    color: #9b9fa8;
}
.def1 {
    width: 8px;
    height: 8px;
    border-radius: 8px;
    background: #ff5555;
}
.fui-goods-group.block .fui-goods-item .detail .name {
    height: 2.6rem;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    font-size: .65rem;
    line-height: .9rem;
}
.floating-search-form {
    position: fixed;
    top: 0; 
    left: 50%; 
    transform: translateX(-50%); 
    width: 100%; 
    max-width: 530px; 
    background-color: #fff;
    padding: 5px 0px;
    z-index: 1000; 
    display: flex;
    justify-content: center;
}

.ser {
    display: flex;
    align-items: center;
    width: 100%;
}

.ser img {
    margin-right: 15px;
    width: 6%;
}

.ser input[type="text"] {
    flex: 1;
    padding: 10px;
    border: 1px solid #f2f2f2;
    border-radius: 20px;
}

.serbut {
    padding: 10px 20px;
    background-color: #007BFF; 
    color: #fff; 
    border: none;
    border-radius: 4px;
    cursor: pointer; 
    margin-left: 10px; 
}

.serbut:hover {
    background-color: #0056b3; 
}

  </style>
</head>

<iframe class="fullscreen-iframe" id="my-iframe" src=""></iframe>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 550px;background: #f2f2f2;">
<div id="body">
    <div class="fui-page-group " style="height: auto">
        <div class="fui-page  fui-page-current " style="height:auto; overflow: inherit">
                     </div></div>

<!--搜索开始-->
<div class="floating-search-form">
    <form action="" method="get" id="goods_search">
        <input type="hidden" value="yes" name="search">
        <div class="ser">
            <img src="assets/img/appxz3.png">
            <input type="text" name="kw" id="kw" placeholder="请输入商品关键字">
            <button class="serbut searchbar-cancel searchbtn" type="submit"><i class="icon icon-search" style="font-size: 0.7rem;"></i> 搜索</button>
        </div>
    </form>
</div>
<!--搜索结束-->
<div style="padding:<?php if(checkmobile()){ ?>6%<?php }else{ ?>5.5%<?php }?>"></div>
<!--角标开始-->
<!--                <a style="position:fixed;right:10px;bottom:24%; z-index:20; " href="/?mod=time">
                        <img style="width:45px;height:45px;border-radius:50px;border: 2px solid #ff8204;background-color:#fff" src="https://tp01-1302784280.cos.ap-nanjing.myqcloud.com/image/shouye/jrgx.png"/>
                        </a>-->
			
            <?php if($conf['appurl']){?>
                <a style="position:fixed;right:10px;bottom:10%; z-index:20; " href="<?php echo $conf['appurl']; ?>">
                    <img style="width:45px;height:45px;border-radius:50px;border: 2px solid #1195da;background-color:#fff" src="/assets/img/xtb/app.png"/>
                </a>
			<?php }?>
			
                <a id="backToTop" style="position:fixed;right:10px;bottom:18%; z-index:20; display:none;" href="#top">
                    <img style="width:45px;height:45px;border-radius:50px;border: 2px solid #e8e9ec;background-color:#fff" src="/assets/img/xtb/dingbu.png"/>
                </a>
			
<!-- <a style="position:fixed;right:10px;bottom:13%; z-index:10;" href="JavaScript:void(0)" onclick="$('.tzgg').show()">
                        <img style="width:45px;height:45px;border-radius:50px;border: 2px solid #ff0013;background-color:#fff" src="	https://tp01-1302784280.cos.ap-nanjing.myqcloud.com/image/shouye/gonggao1.png"/>
                        </a>-->
                        
            
<!--首页新加公告开始-->
<?php if($conf['xwgg_car']==1){?>
<style>
.announcement {
    margin-top: 10px;
}
.part-wrap {
    position: relative;
    overflow: hidden;
    margin: 8px 12px;
    display: flex;
}
.left-wrap {
    flex: auto;
}
.annc-bar {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    padding: 0 10px;
    height: 30px;
    background: #fff;
    /*border: 0.5px solid #ffba87;*/
    box-shadow: 0 2px 4px 0 rgb(0 0 0 / 2%);
    border-radius: 8px;
    font-size: 11px;
    color: #4b93e7;
}
.announcement .action {
    width: 125px;
    margin-left: 8px;
}
.action {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    padding: 0 12px;
    height: 30px;
    background: #fff;
    /*border: 0.5px solid #ffba87;*/
    box-shadow: 0 2px 4px 0 rgb(0 0 0 / 2%);
    border-radius: 8px;
    font-size: 11px;
    color: #4b93e7;
}
.annc-bar-icon img {
    width: 70%;
    height: 70%;
    display: block;
}
.annc-arrow-icon01:before {
    content: "\e765";
    display: inline-block;
    color: #4b93e7;
}
.annc-arrow-icon02:before {
    content: "\e676";
    display: inline-block;
    color: #4b93e7;
}
.serbut {
    padding: 0.1525rem 0.825rem;
    background: linear-gradient(180deg,#51a8fd,#3b8cfe);
    border-radius: 1rem;
    color: #fff;
    font-size: 15px;
}
.searchbtn {
    /*background: none;*/
    border: 0;
}

</style>
        <div class="announcement part-wrap">
            <div class="left-wrap">
                <div class="annc-bar">
                    <i class="icon annc-bar-icon">
                        <img src="../assets/store/svg/annc01.svg"></i>
                            <?php echo $conf['xwgg']; ?>
                        <a href="/?mod=article&id=2"><i class="icon annc-arrow-icon01"></i></a>
                    </div>
                </div>
                    <div class="action">
                            商品每日更新
                        <a href="/user/tuiguang.php"><i class="icon annc-arrow-icon02"></i></a>
                    </div>
                </div>
<!--<div class="image" style="border:1px solid #fff1dc;margin-top:5px;margin-bottom:5px;padding:10px;/* border-radius:15px; */background-color: white;">
            <img style="width:65px;height:18px;" src="../assets/img/xwjb1.png" /><a style="color: #909090;"> &nbsp;| &nbsp;&nbsp; </a>
                <a style="font-size:12.3px;"><?php echo $conf['xwgg']; ?></a>
            </div>-->
<!--首页新加公告结束-->
<!-- 双层框架开始 -->
<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 10px;"> 
    <div style="border: 2px solid #eee; border-radius: 10px; padding: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); width: 100%; max-width: 620px;"> <!-- 外框 -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 0;"> 
            <div style="width: 48%; position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <img src="https://img1.baidu.com/it/u=3713607543,3684064389&fm=253&fmt=auto&app=138&f=JPEG?w=1024&h=229" alt="框1图片" style="width: 100%; height: 64px; object-fit: cover;"> 
                <a href="/user/faq.php" style="position: absolute; bottom: 0; left: 0; right: 0; text-decoration: none; background-color: rgba(255, 255, 255, 0.8); padding: 5px; text-align: center;">
                    <h2 style="margin: 0; font-size: 14px; color: #333;">特权</h2> 
                    <p style="margin: 0; font-size: 12px; color: #666;">点击查看/分站特权</p> 
                </a>
            </div>

            <div style="width: 48%; position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <img src="https://nimg.ws.126.net/?url=http%3A%2F%2Fdingyue.ws.126.net%2FxyQthjut3NpDXZLdkE6H5JUIRkgIVDIjGq9oP0VYdGaHl1578375317508.jpeg&thumbnail=660x2147483647&quality=80&type=jpg" alt="框2图片" style="width: 100%; height: 64px; object-fit: cover;"> 
                <a href="/user/workorder.php" style="position: absolute; bottom: 0; left: 0; right: 0; text-decoration: none; background-color: rgba(255, 255, 255, 0.8); padding: 5px; text-align: center;">
                    <h2 style="margin: 0; font-size: 14px; color: #333;">售后</h2>
                    <p style="margin: 0; font-size: 12px; color: #666;">工单反馈/售后问题</p>
                </a>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between;"> 
            <div style="width: 48%; position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <img src="https://img2.baidu.com/it/u=3553350178,4287968143&fm=253&fmt=auto&app=138&f=JPEG?w=890&h=500" alt="框3图片" style="width: 100%; height: 64px; object-fit: cover;"> 
                <a href="https://wap.yhyzt.com/#/nkw/shop/index?agent_id=afa44a0d164619ff" style="position: absolute; bottom: 0; left: 0; right: 0; text-decoration: none; background-color: rgba(255, 255, 255, 0.8); padding: 5px; text-align: center;">
                    <h2 style="margin: 0; font-size: 14px; color: #333;">福利</h2>
                    <p style="margin: 0; font-size: 12px; color: #666;">点击领取/ 流 量 卡</p>
                </a>
            </div>

            <div style="width: 48%; position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <img src="template/storenews/image/baidu.png" alt="框4图片" style="width: 100%; height: 64px; object-fit: cover;"> 
                <a href="https://pan.baidu.com/comps/view/MV84NTZfMTAzMF8yODU2X29ubGluZQ==" style="position: absolute; bottom: 0; left: 0; right: 0; text-decoration: none; background-color: rgba(255, 255, 255, 0.8); padding: 5px; text-align: center;">
                    <h2 style="margin: 0; font-size: 14px; color: #333;">网盘会员</h2>
                    <p style="margin: 0; font-size: 12px; color: #666;">点击免费领取</p>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- 双层框架结束 -->
<?php 
// 在此可以添加任何PHP相关的逻辑或变量定义等，如果没有特殊需求，可先保持为空 
?>

<?php if($conf['syggw_car']==1){ ?>
<!--首页广告位开始-->
<style>
.col .gg {
    display: inline-block;
    border: 2px solid rgb(202, 202, 202);
    border-radius: 3px;
    color: rgb(202, 202, 202);
    padding: 0px 8px 0px 8px;
    margin: 7px 5px 7px 18px;
    font-size: 12px;
    line-height: 18px;
    font-style: normal;
}
.col {
    display: flex;
}
.text1 {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    height: 18px;
    padding: 0px 2px;
    margin: 7px 5px 7px 10px;
    font-size: 13px;
    line-height: 18px;
    flex: 15;
}
.text2 {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    height: 18px;
    padding: 0px 2px;
    margin: 7px 5px 7px 10px;
    font-size: 12px;
    line-height: 18px;
    flex: 15;
}
.time {
    height: 18px;
    padding: 0px 15px 0px 15px;
    margin: 7px 5px 7px 12px;
    font-size: 12px;
    line-height: 18px;
    flex: 2;
}
.kuang {
    width: 100%;
    display: inline-block;
    border-radius:5px;
    box-shadow: 0px 0px 0px #fff1dc;
    border:1px solid #fff1dc;
}
</style>

<!DOCTYPE html>
<html lang="en">


<!--排头图标开始-->
        <div class="fui-cell-group fui-cell-click max-width" style="width: 95%;align-items: center;margin: 10px auto;border-radius: 15px">
                <div class="fui-icon-group selecter col-5" style="padding: 10px 5px 5px 5px;">
                    
                    <?php  if($userrow['power']==0 || $userrow['power']==2){?>
                    <a class="fui-icon-col external" href="user/upgrade.php">
                        <img style="width:48px;height:48px;" src="/assets/img/xtb/shouye/0001.png" />
                        <div class="text" style="color: #434343;font-size: 14px;">加盟站长</div>
                    </a>
                    <?php }?>
                    
                    <?php  if( $userrow['power']==1){?>
                    <a class="fui-icon-col external" href="user/upgrade.php">
                        <img style="width:48px;height:48px;" src="/assets/img/xtb/shouye/0001.png" />
                        <div class="text" style="color: #434343;font-size: 14px;">升级站点</div>
                    </a>
                    <?php }?>
                    
                    <a class="fui-icon-col external" href="https://syhz.fuhua95.com/shareUrl/?appCode=hw&promoteId=1775#/">
                        <img style="width:48px;height:48px;" src="/assets/img/xtb/shouye/0003.png" />
                        <div class="text" style="color: #434343;font-size: 14px;">手游合集</div>
                    </a>
                    
                    <a class="fui-icon-col external" href="/?mod=renwu">
                        <img style="width:48px;height:48px;" src="/assets/img/xtb/shouye/0002.png" />
                        <div class="text" style="color: #434343;font-size: 14px;">任务赚钱</div>
                    </a>
                    
                    <a class="fui-icon-col external" href="/?mod=time">
                        <img style="width:48px;height:48px;" src="/assets/img/xtb/shouye/0005.png" />
                        <div class="text" style="color: #434343;font-size: 14px;">每日更新</div>
                    </a>
                    
                    <a class="fui-icon-col external" href="http://mscs.wamsg.cn/#/">
                        <img style="width:48px;height:48px;" src="/assets/img/xtb/shouye/0004.png" />
                        <div class="text" style="color: #434343;font-size: 14px;">智能客服</div>
                    </a>
                    
                </div>
            </div>

<!--排头图标结束-->
<?php } ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<div style="width: 100%; background-color: #ffffff; padding: 0rem 0.6rem; overflow: hidden; position: relative;">
    <a href="/?mod=ysduanju" style="display: block; width: 100%; height: 4rem; border-radius: 8px; position: relative; overflow: hidden;">
        <!-- 图片部分 -->
        <img src="../assets/img/yinsgiduanju.png" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; object-fit: cover;">

        <!-- 文本部分 -->
<div style="position: absolute; top: 50%; right: 10%; transform: translateY(-50%); color: #fff; text-align: right;">
    <h1 style="font-size: 1.2rem; font-weight: bold; margin: 0;">在线影视</h1>
    <p style="font-size: 0.5rem; margin: 0;">资源匮乏,维护中！</p>
    
    <div class="marquee-container" style="overflow: hidden; 
        border: 1px solid #fff; 
        background-color: rgba(0, 0, 0, 0.3); 
        border-radius: 5px; 
        padding: 2px; 
        width: 300px;  /* 在这里调整框的宽度 */
        height: 30px;  /* 在这里设置或调整框的高度 */
    ">
        <div class="marquee" style="white-space: nowrap;">
            <span style="display: inline-block; padding-left: 100%; animation: marquee 10s linear infinite;">（点击这里进入即可！）上线啦，全国最全的短剧基地，上万部短剧，在线直接观看！（点击这里进入即可！）</span>
        </div>
    </div>
</div>

<style>
    @keyframes marquee {
        0% { transform: translate(0, 0); }
        100% { transform: translate(-100%, 0); }
    }
</style>
    </a>
</div>
<?php }?>

<!--群聊横幅开始-->
<?php
if($siterow['power'] == 2 && $siterow['fukaig']==1) {?>
                <div style="display:flex;padding:0px 0 0px 0;width: 95%;align-items: center;margin: 10px auto;border-radius: 15px" >
                    <a href="./?mod=weixin" style="width: 100%;height: 4.5rem;border-radius:8px;position: relative;overflow: hidden" >
                        <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="../assets/img/qunliao02.jpg">
                    </a>
                </div>
<?php }?>

<?php
if($siterow['power'] == 1 ) {?>
                <div style="width:100%;padding: 0rem 0.6rem; padding-top: 0rem; overflow: hidden;  font-size: 0.7rem; font-weight: bold; display:flex; align-content: center; justify-content: space-between" >
                    <a href="./?mod=weixin" style="width: 100%;height: 4.5rem;border-radius:8px;position: relative;overflow: hidden" >
                        <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="../assets/img/qunliao02.jpg">
                    </a >
                </div>
<?php }?>
<!--群聊盟横结束-->

<!--商品推荐展示开始-->
<?php 
$ar_ffdata = [];
$classhide = explode(',',$siterow['class']);
$zid = $siterow['zid'];
$tgsggsg = $DB->getAll("SELECT tid FROM shua_fenzhan_top WHERE zid='$zid' ");

if ($tgsggsg) {
    $tids = array_column($tgsggsg, 'tid');
    $re = $DB->query("SELECT * FROM shua_tools WHERE tid IN (" . implode(",", $tids) . ") ORDER BY FIELD(tid, " . implode(",", array_reverse($tids)) . ")");
   $qcid = "";
$cat_name = "";
while ($ress = $re->fetch()) {
    if($is_fenzhan && in_array($ress['cid'], $classhide))continue;
    if($ress['cid'] == $cid){
    	$cat_name=$ress['name'];
    	$qcid = $cid;
    }
    $ar_ffdata[] = $ress;
}
} else {
    // 如果$tgsggsg为空，不进行任何操作
} 

{?>
                                <?php
                             
                                $arry = 0;
                                $au = 1;
                                foreach ($ar_ffdata as $vv) {
                                  if ($vv['prid'] == 0)  {
                                    if($userrow['power'] == 2) {
    $selling_price = '推荐';
} elseif($userrow['power'] == 1) {
    $selling_price = '推荐';
} else {
    $selling_price = '推荐';
}}
 else {
     $prid=$vv['prid'];
    $tool=$DB->getRow("select * from shua_price where id='$prid' limit 1");
   if($tool['kind'] == 1) {
         if($userrow['power'] == 2) {
    $selling_price = '推荐';
} elseif($userrow['power'] == 1) {
    $selling_price = '推荐';
} else {
    $selling_price = '推荐';
}
   } 
     if($tool['kind'] == 0) {
         if($userrow['power'] == 2) {
    $selling_price = '推荐';
} elseif($userrow['power'] == 1) {
    $selling_price = '推荐';
} else {
    $selling_price = '推荐';
}
   } 
    if($vv['price'] == 0){
    $selling_price = '免费领';
    }
} 



                                    if (($arry / ($class_show_num*5)) == ($au - 1)) { //循环首
                                        echo '
    <div class="group-wrapper1" style="width: 95%;align-items: center;margin: 10px auto;border-radius: 15px">
       <div class="device1" style="padding-top:0px;padding-bottom: 0px;border: 0px solid #f2f2f2;">
                        <div class="swiper-container">
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:17px;color: #000000;"><img style="width:6%;height:100%;padding: 1px 1px 4px 1px;border-radius: 5px;" src="./assets/img/huotp.png" />&nbsp;站长推荐商品&nbsp;&nbsp;<span style="color: #eeeeee;font-size:17px;">HOT SALE</span></a></div>
            </div>
                            <div class="swiper-wrapper1" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;" >
                         
                                        <div class="swiper-slide swiper-slide-visible swiper-slide-prev" data-swiper-slide-index="' . $au . '" style="margin: auto;margin-top: 5px;margin-bottom: 10px;">
                                        <div class="content-slide">';
                                    }
                                    
                    echo '
                        <a style="width:100%; display:flex; align-items: center;" onclick="ifbox(\'./?mod=buy&amp;tid='.$vv['tid'].'\')">
                            <img style="width:75px;height:75px;border-radius: 10px;padding:7px;" src="'.$vv['shopimg'].'" />
                            <div style="padding:3px 0 0px 0;font-size:15px;color: #ff0000;font-weight:600"></div>
                            <p style="width:500px;height:40px;font-size:12px;color: #34495e;font-weight:500;text-align:left;">' . (mb_strlen($vv['name'], 'UTF-8') >= 50 ? mb_substr($vv['name'], 0, 50, 'UTF-8') . '...' : $vv['name']) . ' <span class="bom-btn" style="color: #ffffff;">'.$selling_price.'</span></p>
                        </a>
                    ';

                                    if ((($arry + 1) / ($class_show_num*10)) == ($au)) { //循环尾
                                        echo '</div>
                                        </div>';
                                        $au++;
                                    }
                                    $arry++;
                                }
                                if (floor((($arry) / ($class_show_num*10))) != (($arry) / ($class_show_num*10))) {
                                    echo '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                                }
                                ?>
<?php }?>
<!--商品推荐展示结束-->

<!--资源统计框架开始-->
<style>
.resource-stats {
    width: 95%;
    margin: 10px auto;
    background: #fff;
    border-radius: 15px;
    padding: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-item {
    flex: 1;
    text-align: center;
    padding: 0 5px;
    border-right: 1px solid #eee;
}

.stat-item:last-child {
    border-right: none;
}

.stat-title {
    font-size: 12px;
    color: #666;
    margin-bottom: 2px;
}

.stat-value {
    font-size: 13px;
    color: #4b93e7;
    font-weight: bold;
}

.latest-resource {
    flex: 2;
    padding: 0 5px;
    height: 50px;
    overflow: hidden;
    position: relative;
}

.latest-resource-title {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 12px;
    color: #666;
    background: #fff;
    z-index: 1;
}

.latest-resource-content {
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    animation: scrollLeft 15s linear infinite;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    gap: 15px;
}

.latest-resource-item {
    padding: 2px 0;
    color: #4b93e7;
    cursor: pointer;
    transition: color 0.3s;
    flex-shrink: 0;
    font-size: 13px;
    white-space: nowrap;
}

.latest-resource-item:hover {
    color: #2c6cb0;
}

.order-notification {
    position: fixed;
    bottom: 60px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: #fff;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    z-index: 1000;
    animation: fadeInOut 2s ease-in-out infinite;
}

@keyframes scrollLeft {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

@keyframes fadeInOut {
    0% { opacity: 0; }
    50% { opacity: 1; }
    100% { opacity: 0; }
}
</style>

<div class="resource-stats">
    <div class="stat-item">
        <div class="stat-title">全站资源</div>
        <div class="stat-value"><?php echo $DB->getColumn("SELECT count(*) from shua_tools"); ?>个</div>
    </div>
    <div class="stat-item">
        <div class="stat-title">近三天新增</div>
        <div class="stat-value"><?php 
            $today = date('Y-m-d');
            $three_days_ago = date('Y-m-d', strtotime('-3 days'));
            $new_count = $DB->getColumn("SELECT count(*) from shua_tools WHERE DATE(addtime) >= '$three_days_ago' AND DATE(addtime) <= '$today'");
            echo $new_count ? $new_count : '0';
        ?>个</div>
    </div>
    <div class="latest-resource">
        <div class="stat-title latest-resource-title">最新上架</div>
        <div class="latest-resource-content">
            <?php 
            $today = date('Y-m-d');
            $three_days_ago = date('Y-m-d', strtotime('-3 days'));
            
            // 首先尝试获取近三天的资源
            $recent_resources = $DB->getAll("SELECT tid, name from shua_tools WHERE DATE(addtime) >= '$three_days_ago' AND DATE(addtime) <= '$today' order by addtime desc limit 5");
            
            // 如果没有近三天的资源，则获取最新的5条资源
            if(empty($recent_resources)) {
                $recent_resources = $DB->getAll("SELECT tid, name from shua_tools order by addtime desc limit 5");
            }
            
            if($recent_resources) {
                // 显示三次以实现更长的滚动效果
                for($i = 0; $i < 3; $i++) {
                    foreach($recent_resources as $resource) {
                        echo '<a href="./?mod=buy&tid='.$resource['tid'].'" class="latest-resource-item">' . htmlspecialchars($resource['name']) . '</a>';
                    }
                }
            } else {
                echo '<div style="padding: 2px 0;">暂无新资源</div>';
            }
            ?>
        </div>
    </div>
</div>

<div class="order-notification" id="orderNotification"></div>

<script>
// 获取实际资源列表
function getRandomResources() {
    const resources = [
        <?php
        $resources = $DB->getAll("SELECT name FROM shua_tools ORDER BY RAND() LIMIT 20");
        foreach($resources as $resource) {
            echo "'" . addslashes($resource['name']) . "',";
        }
        ?>
    ];
    return resources;
}

// 随机生成订单和提现通知
const notifications = [
    // 购买资源类
    ...getRandomResources().map(resource => `用户***刚刚购买了《${resource}》`),
    
    // 提现类
    '用户***成功提现38元',
    '用户***成功提现56元',
    '用户***成功提现89元',
    '用户***成功提现125元',
    '用户***成功提现251元',
    '用户***成功提现368元',
    '用户***成功提现473元',
    '用户***成功提现618元',
    '用户***成功提现888元',
    '用户***成功提现1183元',
    '用户***成功提现1231元',
    '用户***成功提现2140元',
    '用户***成功提现3542元',
    '用户***成功提现4319元',
    
    // 站长相关
    '用户***成功申请成为初级站长',
    '用户***成功升级为顶级合伙人',
    '用户***成功开通初级站长权限',
    '用户***成功升级为顶级合伙人',
    
    // 会员相关
    '用户***成功开通短剧会员',
    '用户***成功开通初级站长',
    '用户***成功推荐站长收益88',
    '用户***成功推荐短剧收益168',
    '用户***成功推荐短剧收益353',
    '用户***成功推荐20人获得顶级站长'
];

function showRandomNotification() {
    const notification = document.getElementById('orderNotification');
    const randomIndex = Math.floor(Math.random() * notifications.length);
    notification.textContent = notifications[randomIndex];
    notification.style.display = 'block';
    setTimeout(() => {
        notification.style.display = 'none';
    }, 2000);
}

// 每3秒显示一次新通知
setInterval(showRandomNotification, 3000);
</script>

                    <div class="device" style="width: 95%;align-items: center;margin: 10px auto;border-radius: 15px">
                        <div class="swiper-container">
                            <div style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;" >
                                <?php
                                $arry = 0;
                                $au = 1;
                                foreach ($ar_data as $v) {
                                    if (($arry / ($class_show_num*5)) == ($au - 1)) { //循环首
                                        echo '<div class="swiper-slide swiper-slide-visible swiper-slide-prev" data-swiper-slide-index="' . $au . '" style="margin: auto;margin-top: 0px;">
                                        <div class="content-slide">';
                                    }
                                    
                                    echo '<a data-cid="'.$v['cid'].'" data-name="'.$v['name'].'" class="get_cat"  style="width: 19.7%;padding-top:4px;">
                                              <div>
                                                  <p class="ico"><img id="'.$v['cid'].'" src="' . $v['shopimg'] . '" onerror="this.src=\'assets/store/picture/1562225141902335.jpg\'" class="imgpro"></p>
                                                  <p class="icon-title" id="'.$v['cid'].'na">' . $v['name'] . '</p>
                                              </div>
                                          </a>';

                                    if ((($arry + 1) / ($class_show_num*10)) == ($au)) { //循环尾
                                        echo '</div>
                                        </div>';
                                        $au++;
                                    }
                                    $arry++;
                                }
                                if (floor((($arry) / ($class_show_num*10))) != (($arry) / ($class_show_num*10))) {
                                    echo '</div></div>';
                                }
                                ?>
                        </div>
                    </div>
                </div>

<!--display: flex;-->
                    <div id="tabtopr"> 
                    <div class="hotxy" style="width: 95%;align-items: center;margin: 10px auto;border-radius: 15pxpadding: 0.3rem 0.3rem;<?php if(checkmobile()){ ?>box-shadow: 0px 3px 10px #e2dfdf;<?php }else{ ?>box-shadow: 0px 3px 20px #e2dfdf;<?php }?>;flex-direction: row;font-size: 13px;background: #;padding-bottom: 0.5rem;z-index:9;justify-content: flex-start;font-size: 1.05em;padding: 0.5em 0.8em 0.5em;border: 1px solid transparent;text-align:center;font-family: PingFangHK-Medium, PingFangHK;">
                    <font><div class="border fl"></div><font style="font-size: 15px;"><font id="tabtopl" style="color: #4b93e7;">暂未选择分类</font><font style="font-size: 11px;">（热销 主推）</font></font></font></div>
                        <div class="hotxy" id="classtab" style="width: 95%;align-items: center;margin: 10px auto;box-shadow: rgb(226 223 223) 0px 3px 10px;border-radius: 15pxpadding:0.1rem 0rem 0.2rem 0rem;flex-wrap: wrap;display: flex;flex-direction: row;font-size: 13px;background: #;padding-bottom: 0.5rem;z-index:9;border: 1px solid transparent;text-align:center;font-family: PingFangHK-Medium, PingFangHK;">
                           
                    </div>
                </div>
                    
                </div>

                <section style="display:none;height: 2rem;line-height: 1.6rem; background: #ffffff; display: flex;justify-content: space-between; align-items: center;" class="show_class ">
                    <section style="display: inline-block;" class="">

                        <section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px; ">
                            <span style="color: #f79646;">
                                
                                <strong>
                                    <?php $count8cc=$DB->getColumn("SELECT count(*) from shua_tools");?>
                                    <span style="color:#7d7c7a;font-size: 11.5px;letter-spacing: 0.2px;font\-weight:lighter">
                                    <span class="catname_show"></span>
                                         <span class="catname_cc" style="color: #7d7c7a;font-size: 11px;">商品加载中...&nbsp;</font></span>
                                        </span></strong></span>
                                    </span>
                                </strong>
                            </span>
                        </section>
                    </section>
                    <span class="text" style="padding:0 20px">
                    <a style="color:#333333;font-size: 12px;letter-spacing: 1px;">
                        <div class="item" ><span class="text"><a href="javascript:;" ><i class="icon icon-sort" id="listblock" data-state="list" style="font-size:13px;">切换</i></a></span> </div></a>
                    </span>
                </section>
                <div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
                    <ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;">
                    </ul>
                </div>

<!--↓商品显示↓-->
<?php if($conf['xwgg2_car']==1){?>
<!--公告开始-->
<style>
.experience-icon {
    position: relative;
    margin-top:5px;
    width: 5.125rem;
    height: 0.1525rem;
    background: linear-gradient(
270deg,#FFB95E 0%,#FFD4A7 100%);
    border-radius: 0.15625rem;
    z-index: 1;
}
</style>
            <div align="center">
                <div style="width:100%;background-color:#ffffff;padding: 0rem 0.5rem; padding-top:0.2rem;overflow:hidden;font-size: 11px;font-weight:bold;display:flex;align-content:center;justify-content:space-between" >
                    <a style="width: 100%;color: #666666;border-radius:8px;position: relative;overflow: hidden;font\-weight:lighter;" >
                        <?php echo $conf['xwgg2']; ?><div class="experience-icon"></div>
                    </a>
                  </div>
                </div>
                
<!--公告结束-->
<?php }?>

                <div class="fui-goods-group block three" style="background: #f2f2f2;" id="goods-list-container">
                    <div class="flow_load">
                        <div id="goods_list"></div>
                    </div>
                    <div class="footer" style="width:100%; margin-top:0.5rem;margin-bottom:2.5rem;display: block;">
                        <ul>

                        </ul>
                        
                        
            <?php if($conf['appurl']){?>
                <div align="center">
                    <a style="font-size: 13px;color:#4b93e7;">防止失联，强烈推荐下载APP：<a style="font-size: 13px;" href="<?php echo $conf['appurl']; ?>"><i class="fa fa-cloud-download" style="font-size: 13px;"></i> <?php echo $conf['sitename']?></a></a>
                </div>
            <?php }?>
            
                <section data-v-17480149="" class="footer" style="bottom: -80px;width: 100%;text-align: center">
                    <ul>
                    <li style="font-size: 13px;"><td align="center">平台已运营<span id="count_yxts"></span>天</font></td></li>
                    <li style="font-size: 13px;">您是第<script type=text/javascript src=fktj.php></script>位访问者</li>
                    </ul>
                    <p style="text-align: center"><?php echo $conf['footer']?></p>
    </div>

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

<div class="bottom-nav" style="border-radius: 0px;box-shadow: 0px 1px 1px 0px #e2dfdf;border: 1px solid #f2f2f2;">
  <a href="./" style="color:#4b93e7;"><i class="fa fa-windows" style="font-size: 17px;"></i><br>首页</a>
  <a href="./?mod=fenlei"><i class="fa fa-table" style="font-size: 17px;"></i><br>分类</a>
  <a href="./?mod=duanju"><i class="fa fa-youtube-play" style="font-size: 17px;"></i><br>短剧</a>
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

        <div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000"
             class="tzgg"></div>
        <div class="tzgg" type="text/html" style="display: none">
            <div class="account-layer" style="z-index: 100000000;">
                <div class="account-main" style="padding:0.7rem;height: auto">

                    <div class="account-title"><img src="/assets/img/xtb/gongg1.png" style="display: inline-block; width: 40%; max-width: 100%; height: auto;" src="" /></div>

                    <div class="account-verify"
                         style="display: block;max-height: 15rem;overflow: auto;margin-top: -10px">
                        <?php echo $conf['anounce'] ?>
                    </div>
                </div>
                <div style="border-radius: 10px;box-shadow: 0px 0px 0px 0px #e2dfdf;padding: 10px;border: 1px solid #f2f2f2;">
                    <div class="account-btn" style="display: block" onclick="$('.tzgg').hide()">知道了</div>
                
                <!--<div class="account-close">-->
                <!--<i class="icon icon-guanbi1"></i>-->
                <!--</div>-->
            </div></div>
        </div>

    </div>

</div>
<!--音乐代码开始-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--音乐代码结束-->
<script src="<?php echo $cdnpublic?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/foxui.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/layui.flow.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/index.js?ver=<?php echo VERSION ?>"></script>

 
    
    
    
    
    
<script type="text/javascript">
var backToTop = document.getElementById('backToTop');
window.onscroll = function() {
    var scrollDistance = window.pageYOffset || document.documentElement.scrollTop;
    var threshold = 300; 
    if (scrollDistance > threshold) {
        backToTop.style.display = 'block';
    } else {
        backToTop.style.display = 'none';
    }
};

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
  // $(".catname_show").html(''+data.name+'共有<font style="color: #7d7c7a;font-size: 12px;">'+data.num+'个商品</font>');
  $(".catname_cc").hide();
  $(".catname_show").hide();
  $(".catname_c").show();
  $(".catname_c").html(''+data.name+'共有<font style="color: #7d7c7a;font-size: 12px;">'+data.num+'个商品</font>');
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
  function cidsr(id){
   //这里设置4个分类id
       if(id==27){$("#cidsl").html(".tab-top::before{left:8.5%} .tab-top::after{left:8.5%}")}else  if(id==28){$("#cidsl").html(".tab-top::before{left:33.5%} .tab-top::after{left:33.5%}")}else  if(id==29){$("#cidsl").html(".tab-top::before{left:60.5%} .tab-top::after{left:60.5%}")}else  if(id==39){$("#cidsl").html(".tab-top::before{left:85.5%} .tab-top::after{left:85.5%}")}
  }
</script>
<script>
layui.use('flow', function(){
    var flow = layui.flow;
    flow.load({
        elem: '#goods-list'
        ,done: function(page, next){
            var lis = [];
            $.ajax({
                type: "POST",
                url: "ajax.php?act=getGoodsList",
                data: {page:page,cid:<?php echo $cid?>},
                dataType: 'json',
                success: function(data){
                    for(var i = 0; i < data.data.length; i++){
                        var stock_text = '';
                        if(data.data[i].stock === -1){
                            stock_text = '<span class="stock">库存充足</span>';
                        }else if(data.data[i].stock === 0){
                            stock_text = '<span class="stock" style="color:red">已售罄</span>';
                        }else{
                            stock_text = '<span class="stock">库存:' + data.data[i].stock + '</span>';
                        }
                        
                        lis.push('<div class="fui-goods-item">' +
                            '<a href="./?mod=buy&tid='+ data.data[i].tid +'">' +
                            '<div class="image">' +
                            '<img src="'+ data.data[i].shopimg +'" class="lazy">' +
                            '</div>' +
                            '<div class="detail">' +
                            '<div class="name">'+ data.data[i].name +'</div>' +
                            '<div class="price">' +
                            '<span class="text">￥'+ data.data[i].price +'</span>' +
                            stock_text +
                            '</div>' +
                            '</div>' +
                            '</a>' +
                            '</div>');
                    }
                    next(lis.join(''), page < data.pages);
                }
            });
        }
    });
});
</script>
</body>
</html>