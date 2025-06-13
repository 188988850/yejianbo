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
 $wenzhangdizhi=$conf['wenzhangdizhi'];
session_start();

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
    <link rel="shortcut icon" href="<?php echo $cdnserver; ?>favicon.ico">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <?php if($conf['template_paixu'] == 1){ ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style_paixu3.css">
    <?php }else{ ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/class.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="//cdn.staticfile.org/layui/2.5.7/css/layui.css">
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <?php echo str_replace('body','html',$background_css)?>
</head>
<?php if($_SESSION['views']>1){
if (strlen($_SESSION['name']) >= 14){

$namex= substr($_SESSION['name'], 0, 12)."》"; 
}else{$namex=$_SESSION['name'];}
if($_SESSION['vtid']!=$_SESSION['views']){
?>

      <!--<div style="position:fixed;bottom:13%;right:0px;background-color:#23bdeb;color:#fff;height:30px;z-index:999999;text-align:center;font-size:15px;line-height:30px;border-radius:5px 0px 0px 5px;padding:0px 5px;" onclick="jxll(<?php echo $_SESSION['views']?>)">继续《<?php echo $namex?>浏览</div><?php }?>-->
      
      <!-- <div style="position:fixed;bottom:8%;right:0px;background-color:#23bdeb;color:#fff;height:30px;z-index:999999;text-align:center;font-size:15px;line-height:30px;border-radius:5px 0px 0px 5px;padding:0px 5px;" onclick="cxjz()">加载所有商品</div>-->
      <script>function jxll(tid){
          	$.ajax({
			type : "POST",
			url : "ajax.php?act=jxll",
			data : {tid:tid},
			dataType : 'json',
			success : function(data) {
	  	
			}
		});
		 window.location.replace('/');
      }
      function cxjz(){
          	$.ajax({
			type : "POST",
			url : "ajax.php?act=cxjz",
			dataType : 'json',
			success : function(data) {
			
			}
		});
		 window.location.replace('/');
      }
      </script>
      <?php }?>
<style type="text/css">
    body {
        position: absolute;;

        margin: auto;
    }
    .get_cat{
        margin: 10px 0px;
    }
        .ico img {
            filter: saturate(100%) !important;
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
        height: 4.5rem;
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
    height: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    font-size: .6rem;
    color: #262626;
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
        padding: 0.3rem 0.8rem;
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
 <style>
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
     .rob_st {
    width: 82px;
    height: 29px;
    position: absolute;
    bottom: 0px;
    right: 0px;
}
  </style>
<iframe class="fullscreen-iframe" id="my-iframe" src=""></iframe>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 550px;">
<div id="body">
    <div style="position: fixed;    z-index: 100;    top: 30px;    left: 20px;       color: white;    padding: 2px 8px;      background-color: rgba(0,0,0,0.4);    border-radius: 5px;display: none" id="xn_text">
    </div>
    
                    <a id="backToTop" style="position:fixed;right:10px;bottom:10%; z-index:20; display:none;" href="#top">
                    <img style="width:45px;height:45px;border-radius:50px;border: 2px solid #e8e9ec;background-color:#fff" src="/assets/img/xtb/dingbu.png"/>
                </a>
    <script>
    $(function () {
$(window).scroll(function () {
        var scroHei = $(window).scrollTop();//滚动的高度
        if (scroHei > 400) {
            $(".toTop").fadeIn(500);
        }
        else {
            $(".toTop").fadeOut(500);
        }
    });
});
</script>
<style>
  .toTop {
    width: 27px;
    height: 38px;
    color: #fff;
    font-size: 18px;
    /* background: url(/content/img/top_bg2.png); */
    line-height: 38px;
    text-align: center;
    font-weight: 600;
    cursor: pointer;
    position: fixed;
    top: 10px;
    margin-left: 20px;
    z-index: 10000;
    border-width: .5px;
    border-radius: 100px;
    border-color: #ffffff;
    background-color: #ffffff;
    padding: 3px 7px;
    opacity: .8;
    align-items: center;
    justify-content: space-between;
    display: flex;
    flex-direction: row;
    height: 27px;
    }
.headbox {
    padding: 10px;
    height: 30px;
    width: 96%;
    margin: 10px 0%;
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
.return-link {
    display: inline-flex;
    align-items: center;
    font-size: 14px; /* 稍微增大字体大小 */
    padding: 4px 12px; /* 增大内边距 */
    color: #fc7032; /* 保持原颜色 */
    border: 1px solid #ddd; /* 添加边框 */
    border-radius: 4px; /* 添加圆角 */
    text-decoration: none; /* 移除下划线 */
    transition: all 0.2s ease; /* 添加悬停效果过渡 */
}

.return-link .return-icon {
    font-size: 16px; /* 增大图标字体大小 */
    margin-right: 4px; /* 图标和文本之间的间距 */
}

.return-link:hover {
    background-color: #f2f2f2; /* 悬停时改变背景色 */
    color: #333; /* 悬停时改变文本颜色 */
}
</style>

    <div class="fui-page-group " style="height: auto">
        <div class="fui-page  fui-page-current " style="height:auto; overflow: inherit">
            <div class="fui-content navbar" id="container" style="background-color: #fafafc;overflow: inherit">
                <div class="default-items">

        <div class="headbox" style="position: fixed;background-color: #fff;max-width: 550px;left: 50%;transform: translateX(-50%);z-index: 1;">
            <a href="./?mod=fenlei" class="return-link"><span class="return-icon">↩</span>返回</a>

        </div>
<div style="padding-top: 60px;">
                    <?php if($conf['appurl'] == null){ ?>
                    <?php }else{ ?>
                        <!--<a style="position:fixed;right:20px;bottom:15%; z-index:1024;" href="<?php echo $conf['appurl']; ?>" target="_blank">-->
                        <!--    <img style="width:55px;height:55px;border-radius:50px;border: 2px solid #1195da;background-color:#fff" src="<?php echo $cdnserver; ?>assets/store/index/app.png">-->
                        <!--</a>-->
                    <?php } ?>
                    <?php if($conf['template_sousuo'] == 1){ ?>
                    <div class="fui-notice">
                        <div class="image">
                            <a href="JavaScript:void(0)" onclick="$('.tzgg').show()"><img src="<?php echo $cdnserver; ?>assets/store/index/gg.svg"></a>
                        </div>
                        <div class="text" style="height: 1.2rem;line-height: 1.2rem">
                            <ul>
                                <li><a href="JavaScript:void(0)" onclick="$('.tzgg').show()">
                                        <marquee behavior="scroll" scrollamount="5">
                                            <span style="color:red"><?php echo $conf['anounce'] ?></span>
                                        </marquee>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
 <div style="height: 3px"></div>
                <section style="display:none;height: 1.5rem;line-height: 1.6rem; background: #fff; display: flex;justify-content: space-between; align-items: center;" class="show_class ">
                    <section style="display: inline-block;" class="">

                        <section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px; ">
                            <span style="color: #f79646;">
                                <strong>
                                    <?php $count8cc=$DB->getColumn("SELECT count(*) from shua_tools");?>
                                    <span style="color:#7d7c7a;font-size: 12px;">
                                        <span style="color: #f79646;"><strong><span style="font-size: 15px;">
                                         <span class="catname_show">正在获取数据...</span>
                                        <span class="catname_c" style="color: #7d7c7a;font-size: 12px;"></span>
                                        </span></strong></span>
                                    </span>
                                </strong>
                            </span>
                        </section>

                    </section>
                  
                    <span class="text" style="padding:0 20px">
                    <a style="color:#333333;font-size: 12px;letter-spacing: 1px;">
                        <div class="item" ><span class="text"><a href="javascript:;" ><i class="icon icon-app" id="listblock" data-state="gongge" style="font-size:13px;">切换</i></a></span> </div></a>
                    </span>
                </section>
                <section style="text-align: center;display:none;height: 0.1rem;line-height: 1.6rem;" class="show_class">
                <section style="display: inline-block;" class="">

                <section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;text-align: center;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px;">
                	<style> .catname_show{
                	color: #7d7c7a;
                	font-size: 11px;
                	}
                	</style>
                </section>

                </section>
                </section>
                 <div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
                        <ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;">
                
                        </ul>
                </div>
                
                <div class="fui-goods-group" style="background: #f2f2f2;" id="goods-list-container">
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


    </div>
</div>
<!--音乐代码-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--音乐代码-->
<script src="<?php echo $cdnpublic?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/foxui.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/layui.flow.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/type.js"></script>

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



</body>
</html>