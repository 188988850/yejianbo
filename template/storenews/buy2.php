<?php
if (!defined('IN_CRONLITE')) die();
$tid=intval($_GET['tid']);


$tool=$DB->getRow("select * from shua_tools where tid='$tid' limit 1");
if(!$tool)sysmsg('没有找到商品熬！');
function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') { 
    $return = ''; 
    if (function_exists('mb_get_info')) { 
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) { 
            $str = mb_substr ( $string, $x, 1, $in_encoding ); 
            if (strlen ( $str ) > 1) { // 多字节字符 
                $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) ); 
            } else { 
                $return .= '%' . strtoupper ( bin2hex ( $str ) ); 
            } 
        } 
    } 
    return $return; 
}




$level = '<font color="">普通用户</font>';
if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
	if($userrow['power']==2){
		$level = '<font color="">顶级合伙人</font>';
	}elseif($userrow['power']==1){
		$level = '<font color="">分站站长</font>';
	}
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}
 
if(isset($price_obj)){
	$price_obj->setToolInfo($tool['tid'],$tool);
	if($price_obj->getToolDel($tool['tid'])==1)sysmsg('商品已下架');
	$price=$price_obj->getToolPrice($tool['tid']);
	$islogin3=$islogin2;
	unset($islogin2);
	$price_pt=$price_obj->getToolPrice($tool['tid']);
	$price_1=$price_obj->getToolCost($tool['tid']);
	$price_2=$price_obj->getToolCost2($tool['tid']);
	$islogin2=$islogin3;
}else{
   $price=$tool['price'];
   $price_pt=$tool['price'];
   $price_1=$tool['cost1'];
   $price_2=$tool['cost2'];
}


if($tool['is_curl']==4){
	$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='{$tool['tid']}' and orderid=0");
	$fakainput = getFakaInput();
	$tool['input']=$fakainput;
	$isfaka = 1;
	$stock = '<span class="stock" style="">剩余:<span class="quota">'.$count.'</span>份</span>';
}elseif($tool['stock']!==null){
	$count = $tool['stock'];
	$isfaka = 1;
	$stock = '<span class="stock" style="">剩余:<span class="quota">'.$count.'</span>份</span>';
}else{
	$isfaka = 0;
}

if($tool['prices']){
	$arr = explode(',',$tool['prices']);
	if($arr[0]){
		$arr = explode('|',$tool['prices']);
		$view_mall = '<font color="#bdbdbd" size="2">购买'.$arr[0].'个以上按批发价￥'.($price-$arr[1]).'计算</font>';
	}
}

?>





<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $conf['sitename'] .($conf['title']==''?'':' - '.$conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="assets/store/css/style(1).css">
    <link rel="stylesheet" type="text/css" href="assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="assets/store/css/detail.css">
    <link href="//cdn.staticfile.org/limonte-sweetalert2/7.33.1/sweetalert2.min.css" rel="stylesheet">
    <link href="//cdn.staticfile.org/animate.css/3.7.2/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.staticfile.org/layui/2.5.7/css/layui.css"/>
    <link href="//cdn.staticfile.org/Swiper/4.5.1/css/swiper.min.css" rel="stylesheet">
    <link href="assets/user/css/my.css" rel="stylesheet">
    <style>html{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>
     </head>

<style>
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
</style>

<style>
    select {
        /*Chrome和Firefox里面的边框是不一样的，所以复写了一下*/
        border: solid 1px #000;
        /*很关键：将默认的select选择框样式清除*/
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        /*将背景改为红色*/
        background: none;
        /*加padding防止文字覆盖*/
        padding-right: 14px;
    }

    /*清除ie的默认选择框样式清除，隐藏下拉箭头*/
    select::-ms-expand {
        display: none;
    }

    .onclick{cursor: pointer;touch-action: manipulation;}

    .fui-page,
    .fui-page-group {
        -webkit-overflow-scrolling: auto;
    }

    .fui-cell-group .fui-cell .fui-input {
        display: inline-block;
        width: 70%;
        height: 32px;
        line-height: 1.5;
        margin: 0 auto;
        padding: 2px 7px;
        font-size: 12px;
        border: 1px solid #dcdee2;
        border-radius: 4px;
        color: #515a6e;
        background-color: #fff;
        background-image: none;
        cursor: text;
        transition: border .2s ease-in-out, background .2s ease-in-out, box-shadow .2s ease-in-out;
    }

    .btnee {
        width: 20%;
        float: right;
        margin-top: -2.8em;
    }

    .btnee_left {
        width: 20%;
        float: lef;
        margin-top: -2.8em;
    }

    .complaint {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        color: #3c3d3e;
        width: 30%;
        height: 2rem;
        line-height: 2rem;
        justify-content: center;
        background: #e8e8e8;
        border-radius: 6px;
        margin: .5rem auto;
        
    }

    .fui-cell-group .fui-cell .fui-cell-label1 {
        padding: 0 0.4rem;
        line-height: 0.7rem;
    }

    .fui-cell-group .fui-cell.must .fui-cell-label:after {
        top: 40%;
    }

    /*支付方式*/
    .payment-method {
        position: fixed;
        bottom: 0;
        background: white;
        width: 100%;
        padding: 0.75rem 0.7rem;
        z-index: 1000 !important;
    }

    .payment-method .title {
        font-size: 0.75rem;
        text-align: center;
        color: #333333;
        line-height: 0.75rem;
        margin-bottom: 1rem;
    }

    .payment-method .title span {
        height: 0.75rem;
        position: absolute;
        right: 0.3rem;
        width: 2rem;
    }

    .payment-method .title .close:before {
        font-family: 'iconfont';
        content: '\e654';
        display: inline-block;
        transform: scale(1.0);
        color: #0e0e0d;

    }

    .payment-method .payment {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        padding: 0.7rem 0;
    }

    .payment-method .payment .icon-weixin1 {
        color: #5ee467;
        font-size: 1.3rem;
        margin-right: 0.4rem;
    }

    .payment-method .payment .icon-zhifubao1 {
        color: #0b9ff5;
        font-size: 1.5rem;
        margin-right: 0.4rem;
    }

    .icon-zhifubao1::before {
        margin-left: 1px;
    }

    .payment-method .payment .paychoose {
        font-size: 1.2rem;
    }

    .payment-method .payment .icon-xuanzhong4 {
        color: #2e8cf0;
    }

    .payment-method .payment .icon-option_off {
        color: #ddd;
    }

    .payment-method .payment .paytext {
        flex: 1;
        font-size: 0.8rem;
        color: #333;
    }

    .payment-method button {

        background: #2e8cf0;
        color: white;
        letter-spacing: 1px;
        font-size: 0.7rem;
        border: none;
        outline: none;
    }

    .input_select {
        flex: 1;
        height: 1.5rem;
        border-radius: 2px;
        border: none;
        border-bottom: 1px solid #eee;
        outline: none;
        margin-left: 0.4rem;
    }    

</style>
<style>
    html {
        font-size: 14px;
        color: #000;

    }

    a, a:hover {
        text-decoration: none;
    }

    pre {

    }

    .box {
        padding: 20px;
        background-color: #fff;
        margin: 50px 100px;
        border-radius: 5px;
    }

    .box a {
        padding-right: 15px;
    }

    #about_hide {
        display: none
    }

    .layer_text {
        background-color: #fff;
        padding: 20px;
    }

    .layer_text p {
        margin-bottom: 10px;
        text-indent: 2em;
        line-height: 23px;
    }

    .button {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        line-height: 30px;
        padding: 0 20px;
        background-color: #56B4DC;
        color: #fff;
        font-size: 14px;
        border-radius: 3px;
        cursor: pointer;
        font-weight: normal;
    }

    .photos-demo img {
        width: 200px;
    }

    .layui-layer-content {
        margin: auto;
    }

    * {
        -webkit-overflow-scrolling: touch;
    }

    .pro_content {
        background-image: linear-gradient(130deg, #00F5B2, #1FC3FF, #00dbde);
        height: 120px;
        position: relative;
        margin-bottom: 4rem;
        background-size: 300%;
        animation: bganimation 10s infinite;
    }

    @keyframes bganimation {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    #picture {
        padding-top: 1em;
    }

    #picture div {
        text-align: center;
    }

    #picture img {
        width: auto;
        max-height: 38vh;
        margin: auto;
    }
    .hd_intro img{ max-width: 100%; }
    .block-back a{
        color: #505050;
        height: 1.5rem;
        line-height: 1.5rem;
        font-size:0.65rem;
    }
    .ellipsis2{

        display: -webkit-box;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-all;
        -webkit-box-orient: vertical;
        /* -webkit-line-clamp: 2;*/



    }
    .ellipsis1{
        display: -webkit-box;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-all;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }

    .form-control[disabled]{
        background-color:transparent;
        color: #6e6e6e;
    }

    .form-control{
        text-align: right;
    }
    input::placeholder{
        text-align: right;
    }
    .form-control{
        border: 1px solid transparent;
        -webkit-box-shadow: inset 0 1px 1px transparent;
        box-shadow: inset 0 1px 1px transparent;
    }
        .fui-goods-item {
    /* position: relative; */
    height: auto;
    padding: .28rem;
    border-bottom: 1px solid #e7e7e7;
    background: #fff;
    overflow: hidden;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}
.product-item {
    display: flex;
    background: ;
    margin: .5rem;
}
.product-info {
    flex-grow: 1;
    margin-left: 10px;
}
</style>
<style>
.ant-modal-content {
    position: relative;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 0;
    border-radius: 4px;
    box-shadow: 0 6px 16px 0 rgb(0 0 0 / 8%), 0 3px 6px -4px rgb(0 0 0 / 12%), 0 9px 28px 8px rgb(0 0 0 / 5%);
    pointer-events: auto;
    padding: 20px 24px;
}
.dx2-min-price-price-box[data-v-870d6034] {
    border: 1px solid #EB560E;
    display: inline-block;
    border-radius: 5px;
    padding: 3px 6px;
    background-color: #f5e5e6;
}
.dx2-min-price-main-item[data-v-870d6034] {
    margin-top: 12px;
    background-color: #fff4f0;
    padding: 12px;
    border-radius: 5px;
}
.ant-modal-title {
    margin: 0;
    color: #515a6e;
    font-weight: 600;
    font-size: 16px;
    line-height: 1.5;
    word-wrap: break-word;
}
.ant-modal-header {
    color: #515a6e;
    background: #ffffff;
    border-radius: 4px 4px 0 0;
    margin-bottom: 8px;
}
.ant-modal-body {
    font-size: 14px;
    line-height: 1.5714285714285714;
    word-wrap: break-word;
}
.dx2-min-price-price-box-name[data-v-870d6034] {
    color: #515a6e;
}
.ant-row-space-between {
    justify-content: space-between;
}
.ant-tag-z {
    color: #cf1322;
    background: #fff1f0;
    border-color: #ffa39e;
    padding: 1px 7px;
    border-radius: 3px !important;
}
.ant-tag-h {
    color: #674ea7;
    background: #d9d2e9;
    border-color: #9900ff;
    padding: 1px 7px;
    border-radius: 3px !important;
}
.ant-tag-b {
    color: #000000;
    background: #d9d9d9;
    border-color: #9900ff;
    padding: 1px 7px;
    border-radius: 3px !important;
}
.experience-icon {
    position: relative;
    width: 8.125rem;
    height: 0.1525rem;
    background: linear-gradient(
270deg,#FFB95E 0%,#FFD4A7 100%);
    border-radius: 0.15625rem;
}
.title {
    color: #8e8d8d;
    font-size: 14px;
    margin-right: 16px;
    line-height: 24px;
}
</style>
<style>
.upgrade-tip {
    color: #faead7;
    border: .02rem solid #4c4c4c;
    background: #605e5e;
    border-radius: .5rem;
}
.header-tip {
    line-height: 0.8rem;
    font-size: .6rem;
    padding: .15rem .2rem;
    position: relative;
    display: block;
}
.ff {
    display: inline-block;
    line-height: 1;
    font-weight: normal;
    font-variant: normal;
    font-style: normal;
    font-family: "iconfont",serif;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.go {
    border-radius: 0.5rem;
    background: #faead7;
    color: #4c4c4c;
    padding: 0 0.4rem 0 0.2rem;
    /* font-size: .24rem; */
    /* line-height: .1rem; */
    position: absolute;
    right: 0.2rem;
    top: .15rem;
    text-align: center;
}
</style>

<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 550px;margin: auto;">
<div class="fui-page-group statusbar" style="max-width: 550px;left: auto;">
    <div class="fui-page  fui-page-current " style="overflow: inherit">
        <div id="container" class="fui-content "
             style="background-color: rgb(255, 255, 255); padding-bottom: 60px;">
            
            <div class="block-back display-row align-center justify-between" style="position: fixed;background-color: #fff;max-width: 550px;left: 50%;transform: translateX(-50%);z-index: 1;">
                <div style="border-width: .5px;
    border-radius: 100px;
    border-color: #dadbde;
    background-color: #f2f2f2;
    padding: 3px 7px;
    opacity: .8;align-items: center;justify-content: space-between;display: flex; flex-direction: row;height: 30px;">
                <a onclick="ifclose();" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 0.6rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="./" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 0.9rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <font><a href="" style="font-size: 16px;color: #303133;">商品详情</a><font>
            <a onclick="share();" style="font-size: 16px;color: #303133;margin-left: 10px;">分享商品</a>
            </div>

 <div  style="background: #f2f2f2; height: 1px;"></div>
<div style="padding-top: 60px;">
        <img class="lazy" src="<?php echo $tool['shopimg']?>" border="0" opacity="" style="padding: 3px; border-radius: 10px;height: auto !important; width: 100% !important;" width="100%" height="auto" mapurl="" data-width="100%" data-ratio="1.8285714285714285" data-op="change" data-w="1050">
            <div class="my-cell display-row align-center justify-between">
                <div style="width: 100%;height: 100%;display: flex;flex-direction: column;justify-content:space-between">
                    <div style="width: 100%">
                        <span  class="list_item_title ellipsis2" style="font-size: 16px; color: rgb(37, 43, 58); font-weight: 600; line-height: 16px; font-family: PingFangHK-Medium, PingFangHK;" id="gootsp"><?php echo $tool['name']?></span>
                    </div>
                 </div>
            </div> </div>
                        <div style="background-color: rgb(246, 248, 250); margin: 10px 10px; padding: 10px; border-radius: 10px;">
                            <div style="line-height: 25px;"><span class="title">商品售价</span>
                            <span style="color: rgb(255, 84, 30);"><span style="font-size: 18px;"><?php echo $price?>
                            <span style="font-size: 12px; color: rgb(248, 57, 62);">元</span></span></span></div>
                            
                            <!--<?php if($conf['template_showprice']==1){?>
                            <?php  if( $userrow['power']==0){?>
                            <a href="./user/upgrade.php" class="upgrade-tip header-tip">&nbsp;
                            <i class="ff">⚠️</i> 升级合伙人，购买价仅需￥<?php echo $price_2?>元
                            <div class="go">立即升级 ></div>
                            </a>
                            <?php } ?>
                            <?php  if( $userrow['power']==1){?>
                            <a href="./user/upgrade.php" class="upgrade-tip header-tip">&nbsp;
                            <i class="ff">⚠️</i> 升级合伙人，购买价仅需￥<?php echo $price_2?>元
                            <div class="go">立即升级 ></div>
                            </a>
                            <?php } ?>
                            <?php } ?>
                            -->
                            
                            <?php if($isfaka == 1){?>
                            <div style="line-height: 25px;"><span class="title">商品库存</span>
                            <span class="content"><span><?php if($isfaka == 1){echo "<span>".$count."份</span>";} ?></span></span></div>
                            <?php } ?>
                            
                            <?php if($conf['spzs_sales']==1){?>
                            <div style="line-height: 25px;"><span class="title">商品月销</span>
                            <span class="content"><span><?php echo $tool['sales']?>件</span></span></div>
                            <?php }?>
                            
                            <div style="line-height: 25px;"><span class="title">商品编号</span>
                            <span class="content"><span><?php echo $tool['tid']?></span></span></div>
                            
                            <div style="line-height: 25px;"><span class="title">上架时间</span>
                            <span class="content"><span><?php echo $tool['addtime']?></span></span></div>
                            
                            <?php if($conf['template_showprice']==1){?>
                            <div style="line-height: 25px;"><span class="title">当前等级</span>
                            <span class="content"><span><?php echo $level?></span></span></div>
                            
                            <div style="line-height: 25px;"><span class="title">会员价格</span>
                            <span class="content"><span>
                            <a class="show_daili_price">查看价格表</a></span></span></div>
                            <?php } ?>
                            
                            <div style="line-height: 25px;"><span class="title">站长提醒</span>
                            <span class="content"><span>请先充值余额再进行下单</span></span></div>
                            
                      </div>
                
            
<?php if($conf['spzs_car']==1){?>
<!--商品推荐展示开始-->
        <!--<section style="display:none;height: 35px;line-height: 1.6rem; background: #fff; display: flex;justify-content: space-between; align-items: center;" class="show_class ">
            <div style="width:20%"><img src="./assets/img/new3.png" style="height:22px"></div>
                <section style="display: inline-block;" class="">
                        <!--section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;color:#333;border-radius: 6px;padding:0px .5em;letter-spacing: 1.5px; ">
                            <span style="color: #f79646;">
                                <strong>
                                                                        <span style="color:#7d7c7a;font-size: 12px;">
                                        <span style="color: #767676;"><strong><span style="font-size: 15px;">
                                         <span class="catname_show">正在获取...</span></span>
                                        </span></strong></span>
                                    </span>
                                </strong>
                            </span>
                        </section
                    </section>
                  
            <span class="text" style="font-size: 13px;padding:0 10px">
                <span id="total"><?php echo $conf['lswz'] ?> <img src="./assets/img/logo_22332.png" style="width: 15px;"></span>
                    </span>
                </section>
                    <div  style="background: #f2f2f2;width: 100%; height: 1px"></div>-->
                    
            <div style="background: #f2f2f2; height: 5px"></div>
                        <div align="center">
					    <div style="width:97.5%;background-color:#ffffff;display:flex;margin:0.5px 0;padding:10px 0 10px 0">
                            <div style="background: -webkit-linear-gradient(left, #f10707, #b92b2b);color:#FFFFFF;width: 10%;font-size:.5rem; padding:5px 0; border-radius: 10px 0 15px 0;position: absolute;">推荐中</div>
                        <a style="width:33.3%;display:flex;flex-direction:column;align-items:center;" href="<?php echo $conf['sptjzslj01'] ?>">
                            <img style="width:60px;height:60px;border-radius: 10px !important;" src="<?php echo $conf['sptjzstp01'] ?>" />
                            <p style="font-size:10px;color:#666666;"><?php echo $conf['sptjzswz01'] ?></p>
                            <p style="font-size:12px;color:#cc4125;"><?php echo $conf['sptjzsjg01'] ?></p>
                        </a>
                        
                        <div style="width:0.5px;background-color:#eeeeee;"></div>
                        <a style="width:33.3%;display:flex;flex-direction:column;align-items:center;" href="../?mod=buy&tid=<?php echo $conf['sptjzslj02'] ?>">
                            <img style="width:60px;height:60px;border-radius: 10px !important;" src="<?php echo $conf['sptjzstp02'] ?>" />
                            <p style="font-size:10px;color:#666666;"><?php echo $conf['sptjzswz02'] ?></p>
                            <p style="font-size:12px;color:#cc4125;"><?php echo $conf['sptjzsjg02'] ?></p>
                        </a>
                        
                        <div style="width:0.5px;background-color:#eeeeee;">
                            <div style="background: -webkit-linear-gradient(left, #f10707, #b92b2b);color:#FFFFFF;width: 10%;font-size:.5rem; padding:5px 0; border-radius: 10px 0 15px 0;position: absolute;">热门中</div></div>
                        <a style="width:33.3%;display:flex;flex-direction:column;align-items:center;" href="<?php echo $conf['sptjzslj03'] ?>">
                            <img style="width:60px;height:60px;border-radius: 10px !important;" src="<?php echo $conf['sptjzstp03'] ?>" />
                            <p style="font-size:10px;color:#666666;"><?php echo $conf['sptjzswz03'] ?></p>
                            <p style="font-size:12px;color:#cc4125;"><?php echo $conf['sptjzsjg03'] ?></p>
                        </a>
                        </div>
                    </div>
<!--商品推荐展示结束-->
        <?php }?>

            
<div class="aui-introduce"> 
	<div class="aui-tab" data-ydui-tab="">
	    		<div align="center"><a style="color: #8b8b8b;font-size: 11.5px;">（业务下单前请详细查看业务说明↘再进行下单）</div>
		<ul class="tab-nav"> 
			<li id="spxq" class="tab-nav-item tab-active">
			    <a href="javascript:">商品详情</a> 
			</li>
		<li id="wtdy" class="tab-nav-item">
			<a href="javascript:">业务说明</a>
			</li> 
		</ul>
		<div align="center"><div class="experience-icon"></div></div>
		
    <!--业务说明-->
	<div class="tab-panel">
		<div id="spxq_1" class="tab-panel-item tab-active">
            <div class="tab-item">
                <div class="content_friends"><div class="hd_intro" style="word-break: break-all;"><?php echo $tool['desc']?></div></div>
			 </div></div>
    <!--商品详情-->
	    <div id="wtdy_1" class="tab-panel-item"> 
			<div class="tab-item"> 
                <div class="content_friends"><div class="hd_intro" style="word-break: break-all;">
<?php echo $conf['ywsm1']; ?>
</div></div>
</div></div>

<br>

<div class="assemble-footer footer display-row justify-between align-center" style="padding: 0 20px;height: 2.9rem;max-width: 550px">
    
                     <?php
                    if($tool['active'] == 0){
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">商品已下架</p>';
                        $msg_style = "red";
                        $msg_fun = "layer.alert('当前商品已下架，停止下单！');";
                    }else if($tool['close'] == 1){
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">商品维护中</p>';
                        $msg_style = "red";
                        $msg_fun = "layer.alert('当前商品缺货或维护中，停止下单！');";
                    }else if($isfaka == 1 && $count==0){
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">商品缺货中</p>';
                        $msg_style = "red";
                        $msg_fun = "layer.alert('当前商品已售空，请联系站长补货！');";
                    }else{
                        $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">购买商品</p>';
                        $msg_style = "#528ff0";
                        $msg_fun = "$('#paymentmethod').show();";
                    }
                ?>

                <a onclick="ifclose();" class="display-row align-center justify-center" style="background-image: linear-gradient(145deg,#ffa64c,#ffb100 77%); font-size: 11.5px;width:50%;height: 2rem;border-radius: 5px;color: #fff;text-align: center;border-radius: 5px 0px 0px 5px;margin-right:0px;font-weight: 00;">
                    <p style="font-size: 15px;font-weight: 600;opacity: 0.8;">返回浏览</p>
                </a>

                <a href="javascript:$('#paymentmethod').show();" class="display-row align-center justify-center" style="background-image: linear-gradient(145deg,#ff5e00,#ff5000 77%); font-size: 11.5px;width:50%;height: 2rem;border-radius: 5px;color: #fff;text-align: center;border-radius: 0px 5px 5px 0px;margin-right:0px;font-weight: 00;">
                    <p style="font-size: 12px;font-weight: 400;opacity: 0.8;">￥<span style="font-size: 20px;font-weight: 600;"><?php echo $price?></span><br><span id="submit_buys" style="font-size: 12px;font-weight: 400;">购买商品</span></p>
                </a>

            </div>
        </div>
    </div>
</div>
</div>

<style>
.experience-icon1 {
    position: relative;
    width: 2rem;
    height: 0.1525rem;
    background: linear-gradient(
270deg,#FFB95E 0%,#FFD4A7 100%);
    border-radius: 0.1rem;
    z-index: 1;
    margin-bottom: 0.5rem;
}
</style>

<div id="form1">

<input type="hidden" id="tid" value="<?php echo $tid?>" cid="<?php echo $tool['cid']?>" price="<?php echo $price;?>" alert="<?php echo escape($tool['alert'])?>" inputname="<?php echo $tool['input']?>" inputsname="<?php echo $tool['inputs']?>" multi="<?php echo $tool['multi']?>" isfaka="<?php echo $isfaka?>" count="<?php echo $tool['value']?>" close="<?php echo $tool['close']?>" prices="<?php echo $tool['prices']?>" max="<?php echo $tool['max']?>" min="<?php echo $tool['min']?>">
<input type="hidden" id="leftcount" value="<?php echo $isfaka?$count:100?>">
<input type="hidden" id="price" name="price" value="<?php echo $price?>">
    <div id="paymentmethod" class="common-mask" style="display:none;max-width: 550px">
        <div class="payment-method" style="position: absolute;max-height:75vh;">
            <div class="title" id="gid" data-tid="<?php echo $_GET['gid'] ?>" style="font-size: 0.7rem;font-family: PingFangHK-Medium, PingFangHK;">
                下单信息
                <span class="close" onclick="$('#paymentmethod').hide()"></span>
                <div align="center"><div class="experience-icon1" style="margin: 10px;"></div></div>
            </div>
            
                    
                    
            <div style="height: 45vh;overflow:hidden;overflow-y: auto">
<?php if (!$islogin2) { ?>
<style>
.header-tip {
    line-height: 0.8rem;
    font-size: .65rem;
    padding: .7rem .8rem;
    position: relative;
    display: block;
}
.upgrade-tip {
    color: #fff;
    border-radius: .5rem;
}
.ff {
    display: inline-block;
    line-height: 1;
    font-weight: normal;
    font-variant: normal;
    font-style: normal;
    font-family: "iconfont",serif;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
}
.go {
    border-radius: 0.5rem;
    background: #1c66ff;
    color: #ffffff;
    padding: .2rem 0.8rem .1rem 0.8rem;
    /* font-size: .24rem; */
    /* line-height: .1rem; */
    position: absolute;
    right: 0.8rem;
    top: .5rem;
    text-align: center;
}
</style>
        <a href="./user/login.php" class="upgrade-tip header-tip">&nbsp;
            <i class="ff"><img src="../assets/img/icon/yonghutb.png" style="width: 20px; margin-right: 10px;"></i> 您当前未登录平台,请先登录
                <div class="go">登录 / 注册</div>
            </a>
<?php } ?>
                
                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: 100%;text-align: left;padding:10px 0px 0px 0px;font-family: PingFangHK-Medium, PingFangHK;">当前账户余额：<?php echo $userrow['rmb'] ?? '请先注册登录后查看'; ?><a href="./user/recharge.php" style="color: #3c78d8;">（前往充值）</a></label>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: 100%;text-align: left;padding:0;font-family: PingFangHK-Medium, PingFangHK;">商品价格：<a id="submit_cart_shop" style="color: #3c78d8;">（加入购物车）</a></label>
                    <div class="layui-input-">
                        <input type="text" id="need" disabled class="layui-input"  value="<?php echo $price?> 元">
                    </div>
                </div>
                <?php if($tool['input']){ ?>
                <div class="layui-form-item" style="margin-bottom:18px;">
                    <label class="layui-form-label" style="width:100%;text-align:left;font-weight:600;color:#333;padding-bottom:4px;font-size:15px;background:#fff;"> <?php echo $tool['input']; ?> </label>
                    <div class="layui-input-block" style="margin-left:0;">
                        <input type="text" name="inputvalue" id="inputvalue" class="layui-input" required autocomplete="off" placeholder="请输入<?php echo $tool['input']; ?>" style="border-radius:8px;border:1.5px solid #e5e5e5;font-size:15px;padding:10px 12px;color:#222;background:#fff;box-shadow:0 1px 2px #f0f1f2;">
                    </div>
                </div>
                <?php } ?>
                <?php if($tool['inputs']){ $inputs_arr=explode('|',$tool['inputs']);foreach($inputs_arr as $k=>$v){ ?>
                <div class="layui-form-item" style="margin-bottom:18px;">
                    <label class="layui-form-label" style="width:100%;text-align:left;font-weight:600;color:#333;padding-bottom:4px;font-size:15px;background:#fff;"> <?php echo $v; ?> </label>
                    <div class="layui-input-block" style="margin-left:0;">
                        <input type="text" name="inputvalue<?php echo $k+2; ?>" id="inputvalue<?php echo $k+2; ?>" class="layui-input" required autocomplete="off" placeholder="请输入<?php echo $v; ?>" style="border-radius:8px;border:1.5px solid #e5e5e5;font-size:15px;padding:10px 12px;color:#222;background:#fff;box-shadow:0 1px 2px #f0f1f2;">
                    </div>
                </div>
                <?php }} ?>
                <div class="layui-form-item" <?php echo $tool['multi']==0?'style="display: none"':null;?>>
                    <label class="layui-form-label" style="width: 100%;text-align: left;padding:0;font-family: PingFangHK-Medium, PingFangHK;">下单份数：<?php if($isfaka == 1){echo "<span style='float:right;font-family: PingFangHK-Medium, PingFangHK;'>剩余：<font color='red'>".$count."</font>份</span>";} ?></label>
                    <div class="input-group">
                        <div class="input-group-addon" id="num_min" style="background-color: #FBFBFB;border-radius: 2px 0 0 2px;cursor: pointer;">-</div>
                        <input id="num" name="num" class="layui-input" type="number" value="1" placeholder="请输入购买数量" required min="1" <?php echo $isfaka==1?'max="'.$count.'"':null?>>
                        <div class="input-group-addon" id="num_add" style="background-color: #FBFBFB;border-radius: 2px 0 0 2px;cursor: pointer;">+</div>
                    </div>
                </div>
                <div align="center"  style="font-size: 12px;">当前商品最小下单数:<?php echo $tool['min']?>份,最大下单数:<?php echo $tool['max']?>份<br>若显示0份说明不限制,任意下单即可</div>
                <div id="matching_msg"
                     style="display:none;box-shadow: -3px 3px 16px #eee;margin-bottom: 0em;padding: 1em;text-align: center"></div>
            </div>
            <hr>
            <div style="text-align: center">
                <button type="button"  style="margin:auto;text-align: center;background-image: linear-gradient(145deg,#ff5e00,#ff5000 77%); color: white;letter-spacing: 1px;font-size: 17px;border: none;outline: none;width: 90%;"
                        id="submit_buy" class="btn  btn-block">
                    立即购买
                </button>
            </div>
        </div>
    </div>
</div>





<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic ?>Swiper/4.5.1/js/swiper.min.js"></script>
<script src="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script>
function ifclose(){
    window.parent.postMessage('closeIframe', '*');

}
$(".show_daili_price").on("click",function(){
     layer.open({
          type: 1,
          title: false,
          area: '15rem',
          shade: 0.5,
          closeBtn: 0,
          btnAlign: 'c',
          content: $('#show_daili_price_html'),
          
          
          <?php if($islogin2 && $userrow['power'] == 2) {?>
          btn: ['关闭'],
          <?php }else{ ?>
          
          
          <?php  if($userrow['power']==1){?>
          btn: ['提升级别', '关闭'],
          yes: function(index, layero){
             window.location.href = "./user/upgrade.php";
          },
          <?php } ?>


          <?php  if($userrow['power']==0){?>
          btn: ['提升级别', '关闭'],
          yes: function(index, layero){
             window.location.href = "./user/upgrade.php";
          },
          <?php } ?>
          <?php } ?>
     });
});
</script>


<style>
.layui-layer-btn .layui-layer-btn0 {
    border-color: #dedede;
    background-color: #fff1dc;
    color: #333;
}
</style>
    <div id="show_daili_price_html" style="display:none;">
        <div class="ant-modal-content">
            <div class="ant-modal-header">
                <div align="center">
                    <div class="ant-modal-title" id="vcDialogTitle0">各等级价格表</div>
                </div>
            </div>
                <div class="ant-modal-body">
                        <div data-v-870d6034="" class="dx2-min-price-main">
                            <div data-v-870d6034="" class="dx2-min-price-main-item">
                                <div data-v-870d6034="" class="ant-row ant-row-space-between">
                                <div data-v-870d6034="" class="ant-col">
                                    <div align="center">
                                        <li class="fa fa-vimeo"></li> <span>普通用户购买价 = <?php echo $price_pt?>元</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <div data-v-870d6034="" class="dx2-min-price-main">
                            <div data-v-870d6034="" class="dx2-min-price-main-item">
                                <div data-v-870d6034="" class="ant-row ant-row-space-between">
                                <div data-v-870d6034="" class="ant-col">
                                    <div align="center">
                                        <li class="fa fa-vimeo"></li> <span>分站站长购买价 = <?php echo $price_1?>元</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <div data-v-870d6034="" class="dx2-min-price-main">
                            <div data-v-870d6034="" class="dx2-min-price-main-item">
                                <div data-v-870d6034="" class="ant-row ant-row-space-between">
                                <div data-v-870d6034="" class="ant-col">
                                    <div align="center">
                                        <li class="fa fa-vimeo"></li> <span>顶级合伙人购买价 = <?php echo $price_2?>元</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 7 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
});
var hashsalt=<?php echo $addsalt_js?>;
function goback()
{
    document.referrer === '' ?window.location.href = './' :window.history.go(-1);
}
</script>
<script>
function generateShortUrl(longUrl) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "./ajax.php?act=shorturl",
            data: {url: longUrl},
            dataType: 'json',
            success: function(data) {
                if(data.code == 1) {
                    resolve(data.shorturl);
                } else {
                    reject(data.msg);
                }
            },
            error: function() {
                reject('生成短链接失败');
            }
        });
    });
}

function share(){
    if ((navigator.userAgent.match(/(iPhone|iPod|Android|ios|iOS|iPad|Backerry|WebOS|Symbian|Windows Phone|Phone)/i))) {
        var area = '60%';
    }else{
        var area = '406px';
    }
    
    var ii = layer.msg('正在生成分享图片...', {icon: 16, time: 9999999});
    $.ajax({
        type: "GET",
        url: "./code/index.php?tid="+$_GET['tid']+"&url=<?php echo $_SERVER['HTTP_HOST']?>",
        dataType: 'json',
        success: function(data) {
            layer.close(ii);
            if(data.code == 1){
                var imgru='./code/file/cg_'+$_GET['tid']+'_'+data.price+'_<?php echo $_SERVER['HTTP_HOST']?>.jpg';
                var currentUrl = window.location.href;
                var qrcodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + encodeURIComponent(currentUrl);
                
                var imgHtml = "<div style='text-align: center;'>" +
                    "<div style='position:relative;'>" +
                    "<a href='javascript:void(0);' onclick='layer.closeAll();' style='position:absolute;right:10px;top:10px;color:#999;'><i class='layui-icon layui-icon-close' style='font-size:20px;'></i></a>" +
                    "<img id='shopjj' style='width: 100%;' src='"+imgru+"'>" +
                    "</div>" +
                    "<div style='margin: 10px;'>" +
                    "<img src='"+qrcodeUrl+"' style='width: 150px; height: 150px;'>" +
                    "<p style='margin-top: 10px;'>扫描二维码查看商品</p>" +
                    "<p style='margin-top: 5px; font-size: 12px; color: #666;'>商品链接: " + currentUrl + "</p>" +
                    "</div>" +
                    "<p style='padding:10px'>" +
                    "<a class='btn btn-default btn-sm' href='javascript:susu();'>保存图片</a>" +
                    "<a class='btn btn-default btn-sm' style='margin-left:10px' href='javascript:copyLink();'>复制链接</a>" +
                    "<a class='btn btn-default btn-sm' style='margin-left:10px' href='javascript:layer.closeAll();'>关闭窗口</a>" +
                    "</p></div>";
                
                layer.open({  
                    type: 1,  
                    shade: false,
                    skin: 'clear_style',
                    title: false,
                    area: area,
                    shade: 0.3,
                    offset: '30%',
                    content: imgHtml,
                    cancel: function () {  
                    }  
                });
            }else{
                layer.alert(data.msg);
            }
        } 
    });
}

function copyLink() {
    var currentUrl = window.location.href;
    var input = document.createElement('input');
    input.value = currentUrl;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
    layer.msg('商品链接已复制到剪贴板', {icon: 1});
}

function susu(){
    var img = document.getElementById('shopjj');
    var url = img.src;
    var a = document.createElement('a');
    var event = new MouseEvent('click');
    a.download = '商品分享图';
    a.href = url;
    a.dispatchEvent(event);
}

history.pushState(null, document.title, location.href);
window.addEventListener('popstate', function(event) {
  history.pushState(null, document.title, location.href);
});

$('#spxq').click(function() {
    document.getElementById('spxq').className = 'tab-nav-item tab-active';
    document.getElementById('spxq_1').className = 'tab-panel-item tab-active';
    document.getElementById('wtdy').className = 'tab-nav-item';
    document.getElementById('wtdy_1').className = 'tab-panel-item';
});
$('#wtdy').click(function() {
    document.getElementById('wtdy').className = 'tab-nav-item tab-active';
    document.getElementById('wtdy_1').className = 'tab-panel-item tab-active';
    document.getElementById('spxq').className = 'tab-nav-item';
    document.getElementById('spxq_1').className = 'tab-panel-item';
});

</script>

<script src="assets/store/js/main.js?ver=<?php echo VERSION ?>"></script>
<!--<?php if (!$islogin2) { ?>
<style>
.content-item {
    <?php if(checkmobile()){ ?>
    width: 90%;
    <?php }else{ ?>
    width: 90%;
    <?php }?>
    background-color: #fff;
    border-radius: 11px;
    padding-top: 11px;
    flex-direction: column;
    display: flex;
}
.content-item-top {
    display: flex;
    height: 16px;
    justify-content: flex-start;
    align-items: center;
    padding: 16px;
}
.content-item .content-item-top .charge-btn {
    width: 94px;
    padding: 5px;
    font-size: 14px;
    color: #fff;
    text-align: center;
    margin-left: auto;
    background-image: linear-gradient(121deg,#ff9445,#ff5b21);
    border-radius: 15px;
}
.content-item-bottom {
    padding: 16px;
}
</style>
<script>
layer.open({
    type:1,
    title: false,
    area: '13rem',
    shade: 0.95,
    skin: "layerdemo",
    shadeClose: false,
    closeBtn: 0,
    offset: '35%',
    content:
        '<a onclick="ifclose();"><img style="height: 1rem;position: absolute;top:0.2rem;left:0.2rem" src="../assets/user/img/close.png"></a><div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 21px;"><div class="content-item"><div class="content-item-top"><img src="../assets/img/icon/tishi.png" style="width: 20px; margin-right: 10px;"><a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>温馨提示</span></a><a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a><div class="charge-btn"><a href="./user/login.php?back=index" style="color: #fff;">前往登录</a></div></div><div class="content-item-bottom"><div style="padding: 10px 0;font-size: 0.7rem;color: #858585;"><font color="#980000">您当前未登录，请先登录再查看商品详情哦~</font></div></div></div>',
});
</script>
<?php } ?>-->

</body>
</html>