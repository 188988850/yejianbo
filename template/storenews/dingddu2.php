<?php if($islogin2==1){
if($userrow['status']==0){
	sysmsg('你的账号已被封禁！',true);exit;
}elseif($userrow['power']>0 && $conf['fenzhan_expiry']>0 && $userrow['endtime']<$date){
	sysmsg('你的账号已到期，请联系管理员续费！',true);exit;
}
}
if($islogin2==1){
    // 如果 $islogin2 的值等于 1
    $cookiesid = $userrow['zid'];
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
                $rs = $DB->query("SELECT upzid FROM shua_site WHERE zid='{$uu}' ");
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
    $powereee = $current_zid;
    
    
$id = $_GET['id'];
$existingTransaction = $DB->getRow("SELECT * FROM shua_pay WHERE zid = '$cookiesid' AND status = 1 AND tid = '$id'");

$zidValue = $cookiesid;


if (!$existingTransaction) {
    echo "<script>alert('没有此订单1111'); window.location.href = '/?mod=weixin';</script>";
}}    
    
    
    





else {
   if(isset($_GET['orderid'])) {
    $orderid = $_GET['orderid'];
    $id = isset($_GET['id'])? $_GET['id'] : '';  // 增加对 $id 的获取和处理，防止未获取到值
    $existingTfff = $DB->getRow("SELECT * FROM shua_pay WHERE trade_no = '$orderid' AND status = 1 AND tid = '$id'");
    if (empty($existingTfff)) {  // 将!$existingTfff 改为 empty($existingTfff)，更准确判断结果是否为空
        echo "<script>alert('没有此订单2222'); window.location.href = '/?mod=weixin';</script>";
    }
}else {
  
}}
    // 如果 $islogin2 的值不等于 1，弹出提示并跳转到 baidu.com
   








?>
<?php

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $row = $DB->getRow("SELECT * FROM shua_sscc WHERE id = '$id' LIMIT 1");
    if($row) {
      $existingTransaction = $DB->getRow("SELECT * FROM shua_pay WHERE zid = '$cookiesid' AND status = 1 AND tid = '$id'");
    }
} else {
    echo "未获取到 ID 参数";
}


?>

<html><head>
		<meta charset="utf-8">
		<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width,height=device-height, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="format-detection" content="telephone=no">
		<title>订单信息</title>
		<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>
		<link type="text/css" rel="stylesheet" href="/template/group/index/css.css">
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
  <link rel="stylesheet" href="../assets/store/css/content.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
   <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>

	</head>
	<body>
	<style>
	body{background:#f6f6f6;}
.linksssss{
	background:#FFF;
 width: 100%;
 height:200px;
}	


.linksssss .titlesss{
	width:100%;
	height:30px;
	line-height:30px;
	font-size:14px;
	padding-top:20px;
	padding-left:20px;
	margin-bottom:10px;
	font-weight:bolder;
}

.linksssss .titlesss_1{
	width:100%;
	height:30px;
	line-height:30px;
	font-size:14px;
	font-weight:bolder;
	margin-top:10px;
	text-align:center;
}


.linksssss .kukukuku{
	width:94%;
	height:90px;
	line-height:30px;
	font-size:14px;
	margin-left:3%;
	box-shadow: 1px 1px 1px 1px #CCC;
	border-radius: 15px;
}

.linksssss .kukukuku .kukukuku_img{
	margin-left:10px;
	margin-top:10px;
	margin-right:10px;
	width:70px;
	height:70px;
	float:left;
}


.linksssss .kukukuku .kukukuku_img img{
	width:70px;
	height:70px;
}


.linksssss .kukukuku .kukukuku_text{
	width:calc(100% - 90px - 80px);
	height:70px;
	float:left;
	margin-top:10px;
}

.linksssss .kukukuku .kukukuku_text .kukukuku_text_1{
	width::100%;
	height:20px;
	line-height:20px;
	font-weight:bolder;
	margin-bottom:5px;
}

.linksssss .kukukuku .kukukuku_text .kukukuku_text_2{
	width:100%:
	height:40px;
	line-height:20px;
	font-size:12px;
	color:#999;
}

.linksssss .kukukuku .kukukuku_but{
	width:60px;
	text-align:center;
	float:right;
	margin-top:30px;
	font-size:13px;
	margin-right:10px;
	border:1px solid #F00;
	color:#F00;
	border-radius: 25px;
}

.linksssss .kukukuku .kukukuku_but a{
	color:#F00;
}
	
.box{
	border-radius: 4%;

	width: 95%;
	margin: 20px auto;
}
.img-box{
 width: 80%;
}
.img-box img{
 width:80%;
 height:auto;
}
	</style>
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
.order-hd{
    justify-content: space-between;
    padding: 16px 20px 5px;
    margin-top: 1px;
    font-size: 16px;
    color: #282828;
}
</style>
<body>
    
<div class="wrapper">
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
                <a href="./mod=weixin"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href=""><?php echo $row['name'];?></a></font>

            </div>
        </div>
    </div>
 <div style="padding-top: 60px;"></div>
 <div  style="background: #f2f2f2; height: 3px"></div>
        <div class="linksssss">
    	<div class="titlesss">如遇入群问题请联系下方客服</div>
    	<div class="titlesss">客服微信：<?php echo $row['weixing'];?><a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="<?php echo $row['weixing'];?>">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a></div>
        <br>
        <div class="titlesss_1">请添加下方二维码入群</div>
   
        
<div align="center"><div class="box">
 <div class="img-box" style="width: 90%;display: inline-block;border-radius: 5px;padding: 10px 0;box-shadow: 0px 0px 5px 1px #e2dfdf;border:  1px solid #f2f2f2;"
><img src="<?php echo $row['ename9'];?>"></div>
</div>
</div>
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
        // var titles = "聊天交友搭子";
        // var citycode =returnCitySN.cname;
        // titles = titles.replace("【本地】",citycode)
        // $("#quntit").html(titles);
        // $("title").html(titles);
    </script>
</body></html>