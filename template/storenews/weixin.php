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
  </style>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title><?php echo $hometitle?>-群聊列表</title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/class.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/store/css/content.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
   <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
     </head>
<style>
    body{
        max-width:550px;
        margin:0 auto;
      overflow: auto;height: auto !important;
    }
    .container {
        margin:10px 0px;
  width: 80%;
  text-align: center;
}

    
    .top{
      background-color: #<?php echo $conf['rgb01']; ?>;
      width: 100%;
      max-width: 550px;
      }
    .top1{
      background-color: #<?php echo $conf['rgb01']; ?>;
      width: 100%;
      max-width: 550px;0
      padding-bottom:10px;
      position: fixed;
      }
      .home{
              text-align: center;
    line-height: 25px;
        height: 25px;
        width: 25px;
        border-radius: 50%;
        background-color: #fff;
        position: fixed;
        top: 18px;
        margin-left: 18px;
      }
      .toptitle{
      font-weight:600;
      color:#fff;
      text-align: center;
      height: 60px;
      line-height: 65px;
      }
      .article-content img {
      max-width: 100% !important;
      }
      .main[data-v-cc2d9c72] {
      width: 93%;
      }
      .main {
      margin: 0 auto;
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
.fui-goods-item {
    position: relative;
    height: auto;
    padding: .5rem;
    border-bottom: 1px solid #e7e7e7;
    background: #fff;
    overflow: hidden;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}
.fui-goods-item .detail .price {
    position: relative;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    /* display: flex; */
    height: 0.5rem;
    -webkit-align-items: flex-end;
    -ms-flex-align: center;
    /* align-items: flex-end; */
    font-size: .7rem;
}
</style>
<style>
.privatePolicy {
    padding: 1rem .7rem .5rem .7rem;
    height: 100%;
}
.policy {
    width: 100%;
}
.text-div {
    padding: 0rem;
}
.sub-title {
    color: #111;
    font-size: 16px;
    padding: .2rem 0rem;
    font-weight: bold;
}
.sub-text-div {
    background: #fff;
    border-radius: .2rem;
    padding: .5rem;
}
.sub-text {
    color: #666;
    font-size: 13px;
    vertical-align: middle;
    font-family: PingFang SC;
    padding: .04rem .1rem .04rem;
}
.headbox {
    height: 30px;
    width: 96%;
    font-size: 15px;
    margin: 10px 2%;
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
</style>
<body ontouchstart="" style="overflow: auto;height: 9rem; !important;max-width: 550px; margin:auto;">
    <div id="body">
    <div class="fui-page-group statusbar" style="max-width: 550px;left: auto;overflow: hidden;">
    <div class="top">
        <a href=""><div class="toptitle" style="font-size: 15px;">
            群聊列表
        </div></a>
            </div>
      <div align="center"><img src="./assets/img/shequn.png" style="width: 100%; max-width: 100%;<?php if(checkmobile()){ ?>padding: 15px 30px 15px 30px;<?php }else{ ?>padding: 15px 50px 15px 50px;<?php }?>" /></div>
         <?php  if($userrow['power']==2){?>
            <a style="position:fixed;right:10px;bottom:20%; z-index:20;" href="./user/fufeiqun.php">
                <img style="width:50px;height:50px;border-radius:50px;border: 2px solid #53a2f3;background-color:#fff" src="/assets/img/xtb/fabu.png"/>
            </a>
            <?php }?>
    
            <a style="position:fixed;right:10px;bottom:10%; z-index:20;" href="./user/promotion2.php">
                <img style="width:50px;height:50px;border-radius:50px;border: 2px solid #e77777;background-color:#fff" src="/assets/img/xtb/fufei.png"/>
            </a>

   <tbody>
   <?php  
if($siterow['power'] == 2) {
    $current_zid = $siterow['zid'];
} else {
    $uu = $siterow['zid'];
    $rs = $DB->query("SELECT upzid FROM shua_site WHERE zid='{$uu}'");
    $row = $rs->fetch();
    
    if($row['upzid']) {
        // 上级存在
        do {
            $rs = $DB->query("SELECT power FROM shua_site WHERE zid='{$uu}'");
            $row = $rs->fetch();
            if($row['power']!= 2) {
                $rs = $DB->query("SELECT upzid FROM shua_site WHERE zid='{$uu}'");
                $row = $rs->fetch();
                $uu = $row['upzid'];
            }
        } while($row['power']!= 2);
    
        $current_zid = $uu;
    } else {
        // 上级不存在
        $current_zid = $siterow['zid'];
    }
}

$rs = $DB->query("SELECT * FROM shua_sscc WHERE zid='{$current_zid}'");
 
  if (!$rs->rowCount()) {
    echo "<div class='fui-content navbar order-list'>
    <div class='fui-content-inner'>
        <div class='content-empty' style=''>
        		            <p style='color: #999;font-size: .7rem;padding: 15rem 0rem 0rem 0rem;'>- 抱歉，当前站点未发布群聊 -</p>


        </div>
    </div>
</div>	";
  } else {
    while ($res = $rs->fetch()) {?> 
        <!-- 你现有的 HTML 代码 -->

        <div align="center"><a class="fui-goods-item" style="margin-top: .3rem;width: 90%;max-width: 90%;border-radius: 15px;box-shadow: 10px 10px 10px 1px #e2dfdf;border: 1px solid #f2f2f2;" title="<?php echo $res['name']?>" href="./?mod=cce&amp;id=<?php echo $res['id']?>"><div class="image" style="margin-top: .2rem;height: 2.3rem;
    width: 2.3rem;"><img class="lazy" src="<?php echo $res['ename7']?>" ></div><div class="detail" style="margin-top: .2rem;height:unset;"><div class="name" style="font-size: 13px;color: #000000;text-align: left;font-family: PingFangHK-Medium, PingFangHK;"><?php echo $res['name']?></div><span style="margin-top: .2rem;font-size: 13px;color: #BEBEBE;text-align: left;font-family: PingFangHK-Medium, PingFangHK;">
    <?php
    $title = $res['ename'];
    if (mb_strlen($title, 'UTF-8') > 10) {
        $title = mb_substr($title, 0, 10, 'UTF-8') . '...';
    }
    echo $title;
    ?>
</span><div class="price">
      <div class="rob_st" style="
    width: 94px;
    padding: 5px;
    font-size: 14px;
    <?php if(checkmobile()){ ?>top: -330%;<?php }else{ ?>top: -280%;<?php }?>
    color: #fff;
    text-align: center;
    margin-left: auto;
    background-image: linear-gradient(121deg, #90bcff, #2059fa);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    ">加群</div></div></div></a></div>
    </tr><?php }
}?>  
                       </tbody>
                       
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
  <a href="./?mod=latest"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>最新上架</a>
  <?php }?>
  <a href="./?mod=query"><i class="fa fa-list-ol" style="font-size: 17px;"></i><br>订单</a>
  <a href="./user/"><i class="fa fa-github-alt" style="font-size: 17px;"></i><br>会员中心</a>
</div>
<!--导航结束-->