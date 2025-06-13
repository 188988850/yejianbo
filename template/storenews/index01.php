<?php
if (!defined('IN_CRONLITE')) die();

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
 $wenzhangdizhi=$conf['wenzhangdizhi']
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
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css">
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">


    <?php echo str_replace('body','html',$background_css)?>
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
     height: 6rem;
    

    }

    .layui-flow-more {
        width: 100%;
        float: left;
    }

    .fui-goods-group .fui-goods-item .image img {
        border-radius: 10px;
    }

    .fui-goods-group .fui-goods-item .detail .minprice {
        font-size: .6rem;
    }

    .fui-goods-group .fui-goods-item .detail .name {
        height: 1.9rem;
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

    .swiper-pagination {
        position: unset;
    }

    .swiper-container {
        --swiper-theme-color: #ff6600; /* 设置Swiper风格 */
        --swiper-navigation-color: #007aff; /* 单独设置按钮颜色 */
        --swiper-navigation-size: 18px; /* 设置按钮大小 */
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
        font-size: 0.7rem;
        border-left: 1px solid #e7e7e7;
        color: #666;
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
        color: #1195da;
    }

    .goods_sort .item .sorting .icon {
        /*font-size: 11px;*/
        position: absolute;
        -webkit-transform: scale(0.6);
        -ms-transform: scale(0.6);
        transform: scale(0.6);
    }

    .goods_sort .item-price .sorting .icon-sanjiao1 {
        top: .15rem;
        left: 0;
    }

    .goods_sort .item-price .sorting .icon-sanjiao2 {
        top: -.15rem;
        left: 0;
    }

    .goods_sort .item-price.DESC .sorting .icon-sanjiao1 {
        color: #1195da
    }

    .goods_sort .item-price.ASC .sorting .icon-sanjiao2 {
        color: #1195da
    }

    .content-slide .shop_active .icon-title {
        color: #1195da;
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

    .tab_con > ul > li.layui-this {
        background: linear-gradient(to right, #73b891, #53bec5);
        color: #fff;
        border-radius: 6px;
        text-align: center;
    }

    .fui-notice:after {

        border: 0px solid #e2e2e2;

    }

    .fui-notice:before {

        border: 0px solid #e2e2e2;
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
        font-size: 0.65rem;

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
    padding: 0.8rem 0rem;
    font-size: 0.6rem;
    position: absolute;
    top: 2.0rem;
    left: 0;
    z-index: 99999;
    background: #fff;
    box-shadow: 0px 3px 5px #e2dfdf;
    }
    .tab-bottom-none{
        display:none;
    }
    .tab-bottom-item{
        padding: 0.2rem 0.5rem;
        background: #ebebeb;
        border-radius: 50px;
        margin-top: 5px;
        margin-left: 5px;

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
    .tab-top::before{

        content: "";
        position: absolute;
        width: 0;
        height: 0;
        top: -20px;
        left: 25%;
        border: 10px solid transparent;
        border-bottom-color: #dddddd;
        z-index: 9;
    }
    .tab-top::after {
        position: absolute;
        top: -19px;
        left: 25%;
        border: 10px solid transparent;
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
#audio-play #audio-btn{width: 44px;height: 44px; background-size: 100% 100%;position:fixed;bottom:5%;right:6px;z-index:111;}
#audio-play .on{background: url('assets/img/music_on.png') no-repeat 0 0;-webkit-animation: rotating 1.2s linear infinite;animation: rotating 1.2s linear infinite;}
#audio-play .off{background:url('assets/img/music_off.png') no-repeat 0 0}
@-webkit-keyframes rotating{from{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}to{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes rotating{from{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}to{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}
</style>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 550px;">
<div id="body">
    <div style="position: fixed;    z-index: 100;    top: 30px;    left: 20px;       color: white;    padding: 2px 8px;      background-color: rgba(0,0,0,0.4);    border-radius: 5px;display: none" id="xn_text">
    </div>
    <div class="fui-page-group " style="height: auto">
        <div class="fui-page  fui-page-current " style="height:auto; overflow: inherit">
            <div class="fui-content navbar" id="container" style="background-color: #fafafc;overflow: inherit">
                <div class="default-items">
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
                        <div class="fui-swipe-wrapper" style="transition-duration: 500ms;">
                            <?php
                            $banner = explode('|', $conf['banner']);
                            foreach ($banner as $v) {
                                $image_url = explode('*', $v);
                                echo '<a class="fui-swipe-item" href="' . $image_url[1] . '">
                                <img src="' . $image_url[0] . '" style="display: block; width: 100%; height: auto;" />
                            </a>';
                            }
                            ?>
                        </div>
                        <div class="fui-swipe-page right round" style="padding: 0 5px; bottom: 5px; ">
                        </div>
                    </div>
					

					
					    <div style="width:100%;background-color:#ffffff;display:flex;margin:4px 0;padding:1px 0 5px 0">
                        <a style="width:33.3%; display:flex; flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=19">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/image/2022-03-19_623540fac7655.png" />
                            <p style="font-size:12px;font-weight:600">网赚项目</p>
                        </a>
                        <div style="width:1px;background-color:#eeeeee;"></div>
                        <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=16" target="_blank" >
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/image/2022-03-19_623540e877457.png" />
                            <p style="font-size:12px;font-weight:600">稳定项目</p>
                        </a>
                        <div style="width:1px;background-color:#eeeeee;"></div>
                        <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>/user/regsite.php">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>assets/img/Product/jianshe.gif" />
                            <p style="font-size:12px;font-weight:600">开通分站</p>
                        </a>
						 <div style="width:1px;background-color:#eeeeee;"></div>
						 <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=43">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/image/icon10.png" />
                            <p style="font-size:12px;font-weight:600">电商运营</p>
                        </a>
						 <div style="width:1px;background-color:#eeeeee;"></div>
						 <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=2">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/image/icon11.png" />
                            <p style="font-size:12px;font-weight:600">新自媒体</p>
                        </a>
						 
                    </div>
                        <a style="position:fixed;right:10px;bottom:93%; z-index:1024;" href="https://yzf.qq.com/xv/web/static/chat/index.html?sign=37ef9b97862007972446c8b81db4e730f216232753cf523b0ac1a00963b3baf724405dab01bc31a79f548b86ecda8e70d68fa78e<?php echo $conf['appurl']; ?>" target="_blank">
                        <img style="width:50px;height:50px;border-radius:45px;border: 1px solid #f2f2f2;background-color:#fff" src="<?php echo $cdnserver?>template/storenews/img/yunkefu.png">
                        </a>

               

 



					
					 
                  <!--  <div class="fui-notice">
                        <div class="image">
                            <a href="JavaScript:void(0)" onclick="$('.tzgg').show()"><img src="assets/store/picture/1571065042489353.jpg"></a>
                        </div>
                        <div class="text" style="height: 1.2rem;line-height: 1.2rem">
                            <ul>
                                <li><a href="JavaScript:void(0)" onclick="$('.tzgg').show()">
                                        <marquee behavior="alternate">
                                            <span style="color:red">❤️诚邀各级大咖合作共赢-24小时自助下单-售后稳定❤️</span>
                                        </marquee>
                                    </a></li>
                            </ul>
                        </div>
                    </div>     -->
					
					                        </a>      
					
					<div style="width:100%;background-color:#ffffff;padding: 0rem 0.2rem; padding-top: 0.08rem; overflow: hidden;  font-size: 0.7rem; font-weight: bold; display:flex; align-content: center; justify-content: space-between">
                        <a href="/?cid=306" style="width: 37%;height: 2.8rem;border-radius:8px;position: relative;background: rgba(125, 132 , 387, 1.3);overflow: hidden">
                            <div style="position: absolute;top: 20%;left: 5%;z-index: 100">
                                <li style="color: #F7BA0B;">“热</li>
                                <li style="color: #cc3333;font-size: 0.52rem;font-weight:0;line-height: 0.9rem">全国官方游戏！</li>
                            </div>
                            <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="template/storenews/img/zhaunqian9.png">
                        </a>
						
                        <a href="./?mod=time" target="_blank" style="width: 23%; height: 2.8rem; border-radius: 8px; position: relative; background: rgba(255, 192, 203, 0.3); overflow: hidden; background-image: url('template/storenews/img/wukong02.gif'); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;">
                            <div style="color: #ec7276; text-align: center; line-height: 1.2rem; font-size: 0.65rem;padding-top: 0.4rem;">
                                  
                                 <li style="color: #080802; text-align: center; line-height: 2.7rem; font-size: 0.65rem;padding-top: 0.4rem;"></li>
                            </div>
                       </a>
					   
                        <a href=<?php echo $conf['appurl']; ?><?php echo $wenzhangdizhi?>" target="_blank" style="width: 37%;height: 2.8rem;border-radius:8px;position: relative;background: rgba(0 , 50 , 100,5.8);overflow: hidden">
                            <div style="position: absolute;top: 20%;left: 5%;z-index: 100">
                                <li style="color: #45d0f6;">应用</li>
                                <li style="color: #ffffff;font-size: 0.52rem;font-weight:0;line-height: 0.9rem">Android | IOS</li>
                            </div>

                            <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="template/storenews/img/APPXZ2.png">
                        </a>
                    </div>
                            

                        </a>      
                        </a>      
					    <div style="width:100%;background-color:#ffffff;display:flex;margin:0px 0;padding:0px 0 0px 0">
                        <a style="width:33.3%; display:flex; flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=291">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/img/icon-bkjd.png" />
                            <p style="font-size:12px;font-weight:600">信用卡</p>
                        </a>
                        <div style="width:1px;background-color:#eeeeee;"></div>
                        <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=292" target="_blank" >
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/img/icon-user-wdtd.png" />
                            <p style="font-size:12px;font-weight:600">个人信贷</p>
                        </a>
                        <div style="width:1px;background-color:#eeeeee;"></div>
                        <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=20">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/img/icon-grad-zuji1.gif" />
                            <p style="font-size:12px;font-weight:600">金融渠道</p>
                        </a>
						 <div style="width:1px;background-color:#eeeeee;"></div>
						 <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=293">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/img/icon-cpdb.png" />
                            <p style="font-size:12px;font-weight:600">企业贷</p>
                        </a>
						 <div style="width:1px;background-color:#eeeeee;"></div>
						 <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=21">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>template/storenews/img/icon-fhjl.png" />
                            <p style="font-size:12px;font-weight:600">金融教学</p>
                        </a>
						 <div style="width:1px;background-color:#eeeeee;"></div>
						 <a style="width:33.3%;display:flex;flex-direction: column;align-items: center;" href="<?php echo $cdnserver?>?cid=290">
                            <img style="width:40px;height:40px;" src="<?php echo $cdnserver?>https://cdn.jipaikeji.com/uploads/articles/20230320/6ff9b0e7fb54ffd645e3fe9266fad081.png" />
                            <p style="font-size:12px;font-weight:600">租机</p>
                        </a>
                    </div>
					<div style="width:100%;background-color:#ffffff;padding: 0rem 0.2rem; padding-top: 0.2rem; overflow: hidden;  font-size: 0.7rem; font-weight: bold; display:flex; align-content: center; justify-content: space-between">
                        <a href="/?cid=227" style="width: 49%;height: 2.8rem;border-radius:8px;position: relative;background: rgba(255, 232 , 187, 0.9);overflow: hidden">
                            <div style="position: absolute;top: 20%;left: 5%;z-index: 100">
                                <li style="color: #000000;">影视</li>
                                <li style="color: #208de6;font-size: 0.52rem;font-weight:0;line-height: 0.9rem">全国微剧找客服上新剧！</li>
                            </div>
                            <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="template/storenews/img/zhuanqian4.png">
                        </a>

                        <a href=https://yidegouw.com/<?php echo $wenzhangdizhi?>" target="_blank" style="width: 49%;height: 2.8rem;border-radius:8px;position: relative;background: rgba(183 , 230  , 253, 0.9);overflow: hidden">
                            <div style="position: absolute;top: 20%;left: 5%;z-index: 100">
                                <li style="color: #BE50F1;">创业</li>
                                <li style="color: #cf8036;font-size: 0.52rem;font-weight:0;line-height: 0.9rem">长期|实操|实地调查!</li>
                            </div>

                            <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="template/storenews/img/zhuanqian7.png">
                        </a>
                        <!--                        <a href="--><!--" style="width: 32%; height: 3.2rem;border-radius:5px;background-image:linear-gradient(to right, rgba(233, 95, 95, 1), rgba(239, 154, 183, 1));position: relative"  >-->
                        <!--                            <li style="position: absolute;top: 20%;left: 10%;color: #fff;"> 初级站长<br>升级</li>-->
                        <!--                            <img style="width:3.5rem;position: absolute;bottom:-20px;right: -20px;opacity: 0.7 " src="assets/store/index/shengji.png">-->
                        <!--                        </a>-->
                    </div>
					
					   <form action="" method="get" id="goods_search"><input type="hidden" value="yes" name="search">
                        <div class="fui-searchbar bar"
                             style="background-color: #ffffff;border-top: 0px solid #e7e7e7;padding: 0.1rem 0.5rem;">
                            <div class="searchbar center searchbar-active" style="height: 2.5rem" >

                                <div class="search-input" style="border:0px; border-radius:50px;  width:100%;  padding-left:0px;padding-right:0px; background-color: #efeff5;">

                                    <input type="text" style="background-color: rgba(0,0,0,0);height: 1.65rem;" class="search"
                                           value="" name="kw"
                                           placeholder="输入关键字" id="kw">
                                    </input>
                                </div>
                                <button type="submit"  class="searchbar-cancel searchbtn  " style="width:3.5rem;background-color:#1195da;height: 1.65rem; border-radius:0 50px 50px  0;color: #fff;"><i class="icon icon-search " style="font-size: 0.7rem;"></i></button>

                            </div>
                        </div>
                    </form>
					
					
                  <!--    <form action="" method="get" id="goods_search"><input type="hidden" value="yes" name="search">
                        <div class="fui-searchbar bar">
                            <div class="searchbar center searchbar-active" style="padding-right:2.5rem">
                                <input type="submit" class="searchbar-cancel searchbtn" value="搜索">
                                <div class="search-input" style="border: 0px;padding-left:0px;padding-right:0px;">
                                    <i class="icon icon-search"></i>
                                    <input type="text" class="search" value="<?php echo trim(daddslashes($_GET['kw']));?>" name="kw" placeholder="输入商品关键字..." id="kw">
                                </div>
                            </div>
                        </div>
                    </form>   -->
					
                  
<div class="device">
                        <div class="swiper-container">
                            <div class="swiper-wrapper"
                                 style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
                                <?php
                                $arry = 0;
                                $au = 1;
                                foreach ($ar_data as $v) {
                                    if (($arry / ($class_show_num*5)) == ($au - 1)) { //循环首
                                        echo '<div class="swiper-slide swiper-slide-visible swiper-slide-prev" data-swiper-slide-index="' . $au . '" style="margin: auto;margin-top: -3px;">
                                        <div class="content-slide">';
                                    }
                                    echo '<a data-cid="'.$v['cid'].'" data-name="'.$v['name'].'" class="get_cat"  style="width: 20%;padding-top;5px;">
                                               <div class="mbg">
                                                   <p class="ico"><img id="'.$v['cid'].'" src="' . $v['shopimg'] . '" onerror="this.src=\'assets/store/picture/1562225141902335.jpg\'" class="imgpro"></p>
                                                   <p class="icon-title" id="'.$v['cid'].'na">' . $v['name'] . '</p>
                                              </div>
                                          </a>';

                                    if ((($arry + 1) / ($class_show_num*5)) == ($au)) { //循环尾
                                        echo '</div>
                                        </div>';
                                        $au++;
                                    }
                                    $arry++;
                                }
                                if (floor((($arry) / ($class_show_num*5))) != (($arry) / ($class_show_num*5))) {
                                    echo '</div></div>';
                                }
                                ?>
                            </div>

                        </div>
                        
                    </div>
                    <script>

                    </script>

                          <div  class="hotxy"  style="background: #fff;"><div class="tab-top tips-content display-row justify-between align-center"><div><i class="icon icon-sanjiao3 tab-top-l-icon"></i><font id="tabtopl">暂未选择分类</font></div><div  id="tabtopr" data-state="none"><font class="tab-top-r">更多分类</font><i class="icon icon-right1 tab-top-r-icon"></i></div><style id="cidsl"></style></div>
                    
                    <div class="tab-bottom display-row flex-wrap" id="classtab">
                        

                    </div></div>
                    
                    <div style="height: 1px"></div>
                </div>
 <div style="height: 3px"></div>
                <section style="display:none;height: 1.5rem;line-height: 1.6rem; background: #f4f5fa; display: flex;justify-content: space-between; align-items: center;" class="show_class ">
                    <section style="display: inline-block;" class="">

                        <section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px; ">
                            <span style="color: #f79646;">
                                <strong>
                                    <?php $count8cc=$DB->getColumn("SELECT count(*) from shua_tools");?>
                                    <span style="color:#7d7c7a;font-size: 12px;">
                                        <span style="color: #f79646;"><strong><span style="font-size: 15px;">
                                         <span class="catname_show">正在获取数据...</span>
                                        <span class="catname_c" style="color: #7d7c7a;font-size: 12px;">正在获取数据...</span>
                                         <span class="catname_cc" style="color: #000000;font-size: 15px;font-weight:normal;">精品推荐<font style="color: #7d7c7a;font-size: 12px;">(<?php echo $count8cc?>个商品)</font></span>
                                        </span></strong></span>
                                    </span>
                                </strong>
                            </span>
                        </section>

                    </section>
                  
                    <span class="text" style="padding:0 20px">
                        <a href="javascript:;">
                            <i class="icon icon-list" id="listblock" data-state="list" style="font-size:20px;"></i>
                        </a>
                    </span>
                </section>
                <section style="text-align: center;display:none;height: 0.1rem;line-height: 1.6rem;" class="show_class">
                <section style="display: inline-block;" class="">

                <section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;text-align: center;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px;">
                	<style> .catname_show{color: #7d7c7a;font-size: 12px;}</style>
               
                </section>

                </section>
                </section>
                 <div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
                        <ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;">
                
                        </ul>
                </div>
                
                <div class="fui-goods-group block three" style="background: #f3f3f3;" id="goods-list-container">
                    <div class="flow_load"><div id="goods_list"></div></div>
                    <div class="footer" style="width:100%; margin-top:0.5rem;margin-bottom:2.5rem;display: block;">
                        <ul>
                            <li>© <?php echo $conf['sitename'] ?>. All rights reserved.</li>
                        </ul>
                        <p style="text-align: center"><?php echo $conf['footer']?></p>
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
        
        <div class="fui-navbar" style="bottom:-34px;background-color: white;max-width: 650px">
        </div>

         <style>
  .img1{

    width:1.1rem;
    height:1.1rem;
  }

  </style>
  

  

  
  
   <div class="fui-navbar" style="max-width: 550px;z-index: 100;">
            <a href="./" class="nav-item active"> <img src="<?php echo $cdnserver?>template/storenews/img/1.png" class="img1"><span class="label">首页</span>
            </a>
            
            <a href="/?mod=waplist" class="nav-item ">  <img src="<?php echo $cdnserver?>template/storenews/img/5.png" class="img1"><span class="label">分类</span> </a>
            
            <a href="./?mod=query" class="nav-item ">  <img src="<?php echo $cdnserver?>template/storenews/img/2.png" class="img1"><span class="label">订单</span> </a>
		
		    <a href="./?mod=cart" class="nav-item ">  <img src="<?php echo $cdnserver?>template/storenews/img/9.png" class="img1"><span class="label">购物车</span> </a>
		            
            <a href="./?mod=kf" class="nav-item "> <img src="<?php echo $cdnserver?>template/storenews/img/3.png" class="img1"> <span class="label">客服</span>
            </a>
            <a href="./user/" class="nav-item "> <img src="<?php echo $cdnserver?>template/storenews/img/4.png" class="img1"><span class="label">会员中心</span> </a>
        </div>
  
  
  
  
  


        <div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000"
             class="tzgg"></div>
        <div class="tzgg" type="text/html" style="display: none">
            <div class="account-layer" style="z-index: 100000000;">
                <div class="account-main" style="padding:0.8rem;height: auto">

                    <div class="account-title">系 统 公 告</div>

                    <div class="account-verify"
                         style="  display: block;    max-height: 15rem;    overflow: auto;margin-top: -10px">
                        <?php echo $conf['anounce'] ?>
                    </div>
                </div>
                <div class="account-btn" style="display: block" onclick="$('.tzgg').hide()">确认</div>
                
                <!--<div class="account-close">-->
                <!--<i class="icon icon-guanbi1"></i>-->
                <!--</div>-->
            </div>
        </div>

    </div>
</div>


  
<!--音乐代码-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--音乐代码-->
<script src="<?php echo $cdnpublic?>jquery/3.4.1/jquery.min.js"></script>  <script>  $(".catname_show").hide(); </script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/foxui.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/layui.flow.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/index.js?ver=<?php echo VERSION ?>"></script>
<script>
$(".catname_c").hide();
$("#classtab").hide();
$(".hotxy").hide();
	$("#tabtopr").click(function(){
	 $("#classtab").toggle();

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
       if(id==8){$("#cidsl").html(".tab-top::before{left:9%} .tab-top::after{left:9%}")}else  
       if(id==9){$("#cidsl").html(".tab-top::before{left:35%} .tab-top::after{left:35%}")}else  
       if(id==10){$("#cidsl").html(".tab-top::before{left:61%} .tab-top::after{left:61%}")}else  
       if(id==11){$("#cidsl").html(".tab-top::before{left:87%} .tab-top::after{left:87%}")}
  }
</script>


<script>//禁止右键

 

function click(e) {

 

if (document.all) {

 

if (event.button==2||event.button==3) { alert("欢迎光临寒舍，有什么需要帮忙的话，请与站长联系！谢谢您的合作！！！");

 

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

 

document.captureEvents(Event.MOUSEDOWN);

 

}

 

document.onmousedown=click;

 

document.oncontextmenu = new Function("return false;")

 

document.onkeydown =document.onkeyup = document.onkeypress=function(){ 

 

if(window.event.keyCode == 12) { 

 

window.event.returnValue=false;

 

return(false); 

 

} 

 

}

 

</script>

 

 

 

  <script>//禁止F12

 

function fuckyou(){

 

window.close(); //关闭当前窗口(防抽)

 

window.location="about:blank"; //将当前窗口跳转置空白页

 

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