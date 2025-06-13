
<?php
$is_defend=true;
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$title = '推广赚钱';

?>

<?php
if($userrow['power']==0){
	showmsg('你没有权限使用此功能！',3);
}
if(!$userrow['domain'])showmsg('当前分站还未绑定域名',3);
$scriptpath = str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$url = 'http://'.$userrow['domain'].$scriptpath.'/';
if($conf['fanghong_api']>0){
	$turl = fanghongdwz($url);
	if($turl == $url){
		showmsg('此功能临时维护，当前生成失败，请晚点再试！',3);
	}elseif(strpos($turl,'/')===false){
		showmsg('防红地址生成失败:'.$turl,3);
	}
}else{
	$turl = $url;
}
?>
<html lang="zh-cn" style="" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers no-applicationcache svg inlinesvg smil svgclippaths"><head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>推广海报</title>
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
<body>
<style>
    .layui-carousel, .layui-carousel>[carousel-item]>* {
        background-color:transparent;
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
                <a href="javascript:history.back()"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">推广海报</a></font>

            </div>
                </div>
                

<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>邀请规则</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">1.分享二维码海报/链接给好友<br>
                    2.好友用微信/QQ/浏览器进入平台<br>
                    3.好友通过您的分享注册将成为您的下级，他们产生的订单都会给您带来收益</font>
                </div>
                </div>
            </div>
        </div>

        <div class="layui-carousel" id="test1" lay-filter="test1" style="margin: 25px auto; width: 95%; height: 50vh;" lay-anim="" lay-indicator="outside" lay-arrow="always">
            <div carousel-item="" class="text-center">
                <div class="layui-this">
                    <img style=" height: 50vh" id="img_1" src="./timg/timg.php?id=1&url=<?php echo $turl?>" alt="推广图1"></div>
                <div>
                    <img style=" height: 50vh" id="img_2" src="./timg/timg.php?id=2&url=<?php echo $turl?>" alt="推广图2"></div>
                <div>
                    <img style=" height: 50vh" id="img_3" src="./timg/timg.php?id=3&url=<?php echo $turl?>" alt="推广图3"></div>
                <div>
                    <img style=" height: 50vh" id="img_4" src="./timg/timg.php?id=4&url=<?php echo $turl?>" alt="推广图4"></div>
                <div>
                    <img style=" height: 50vh" id="img_5" src="./timg/timg.php?id=5&url=<?php echo $turl?>" alt="推广图5"></div>
                <div>
                    <img style=" height: 50vh" id="img_6" src="./timg/timg.php?id=6&url=<?php echo $turl?>" alt="推广图6"></div>
                <div>
                    
            </div>
        </div>
    </div>
        <div class="my-cellc" style="margin-bottom: 0px;padding: 20px 30px;border-radius: 0">
            <a type="submit" style="color: #fff;" onclick="CunTips()"><div class="text-center" style="padding: 2px 5px;background: #8dc4fd;border-radius:10px;box-shadow: inset 0 0 0.9375rem 0.3125rem rgba(255,255,255,.86);margin:10px 17%">
                <div type="button" class="btn btn-block" style="width: 100%;display: inline-block;border-radius: 5px;padding: 10px 0;background: linear-gradient(263deg,#3485ff 0%,#a1c0e7 100%);color: #fff;box-shadow: inset 0 0 0.19375rem 0.09125rem #d6ddf5;" >
                    <span style="<?php if(checkmobile()){ ?>font-size:14px;<?php }else{ ?>font-size:14px;<?php }?>">保存海报</span></a>
                </div>
            </div>
        </div>
</div>


<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
<script type="text/javascript">
    var ins;
    var ins_index = 1;
    layui.use('carousel', function(){
        var carousel = layui.carousel;
        //建造实例
        ins =  carousel.render({
            elem: '#test1'
            ,width: '95%' //设置容器宽度
            ,height:'50vh'
           // ,anim:'fade'
            ,arrow: 'always' //始终显示箭头
            ,autoplay:false
            ,indicator:'outside'
        });
        carousel.on('change(test1)', function(obj){ //test1来源于对应HTML容器的 lay-filter="test1" 属性值
            ins_index = obj.index + 1
        });
    });
    $("#test1").on("touchstart", function (e) {
        var startX = e.originalEvent.targetTouches[0].pageX;//开始坐标X
        $(this).on('touchmove', function (e) {
            arguments[0].preventDefault();//阻止手机浏览器默认事件
        });
        $(this).on('touchend', function (e) {
            var endX = e.originalEvent.changedTouches[0].pageX;//结束坐标X
            e.stopPropagation();//停止DOM事件逐层往上传播
            if (endX - startX > 30) {
                ins.slide("sub");
            }
            else if (startX - endX > 30) {
                ins.slide("add");
            }
            $(this).off('touchmove touchend');
        });
    });

function CunTips() {
    var url = $('#img_'+ ins_index )[0].src;
    if (this.isIE()) { // IE
      window.open(url, '_blank')
    } else {
      let a = document.createElement('a') // 创建a标签
      let e = document.createEvent('MouseEvents') // 创建鼠标事件对象
      e.initEvent('click', false, false) // 初始化事件对象
      a.href = url // 设置下载地址
      a.download ='img_'+ ins_index // 设置下载文件名
      a.dispatchEvent(e)
    }
}
function isIE () {
      if (!!window.ActiveXObject || 'ActiveXObject' in window) {
        return true
      } else {
        return false
      }
}
</script>
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