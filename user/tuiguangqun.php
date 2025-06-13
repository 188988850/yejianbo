
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>群聊列表推广</title>
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
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
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
<?php
/**
 * 群聊列表推广
**/
include("../includes/common.php");
$title='群聊列表推广';
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $row = $DB->getRow("SELECT * FROM shua_sscc WHERE id = '$id' LIMIT 1");
    if($row) {
      
    }
}
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

?>
<style type="text/css">
<!--
.STYLE3 {font-size: 14px}
-->
</style>

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
                <a href="javascript:history.back()" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">群聊列表推广</a></font>

            </div>
        </div>
    </div>
<?php  if($userrow['power']==0 || $userrow['power']==1){?>
<div class="main-content1">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>邀请规则</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                        <div align="center">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000">您当前级别不够无法查看并参与分佣<br>
                    请先升级到顶级合伙人再来
                    </font>
                </div>
                <a href="upgrade.php">
            <div class="text-center" style="padding: 10px 0;">
                <input type="submit" name="submit" class="btn submit_btn" style="width: 70%;padding:5px;" value="前往升级">
            </div></a>
            
                </div>
                </div>
            </div>
        </div>
        
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/jinbi088.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>分佣介绍</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                        <div align="center">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000">您当前级别不够无法查看并参与分佣<br>
                    请先升级到顶级合伙人再来
                    </font>
                </div>
                <a href="upgrade.php">
            <div class="text-center" style="padding: 10px 0;">
                <input type="submit" name="submit" class="btn submit_btn" style="width: 70%;padding:5px;" value="前往升级">
            </div></a>
            
                </div>
                </div>
            </div>
        </div>
        
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/wangzhi.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>群聊推广链接</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                        <div align="center">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000">您当前级别不够无法查看并参与分佣<br>
                    请先升级到顶级合伙人再来
                    </font>
                </div>
                <a href="upgrade.php">
            <div class="text-center" style="padding: 10px 0;">
                <input type="submit" name="submit" class="btn submit_btn" style="width: 70%;padding:5px;" value="前往升级">
            </div></a>
            
                </div>
                </div>
            </div>
        </div>
        
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/erweima.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>群聊推广二维码</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                        <div align="center">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000">您当前级别不够无法查看并参与分佣<br>
                    请先升级到顶级合伙人再来
                    </font>
                </div>
                <a href="upgrade.php">
            <div class="text-center" style="padding: 10px 0;">
                <input type="submit" name="submit" class="btn submit_btn" style="width: 70%;padding:5px;" value="前往升级">
            </div></a>
            
                </div>
                </div>
            </div>
        </div>
<?php }?>

<?php  if( $userrow['power']==2){?>
<div class="main-content1">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>邀请规则</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">仅自己发布的群聊分享给用户购买了才有佣金拿，不然你就是在帮别人推广
                </div>
                </div>
            </div>
        </div>
        
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/jinbi088.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>分佣介绍</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">仅顶级合伙人级别才能享受分佣，分站站长和普通用户不参与<br>
                    推广享受80%佣金分佣<br>
                    比如你邀请的用户支付10元加入你发布的某群聊，你将获得8元
                    </font><br>
                </div>
                </div>
            </div>
        </div>
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/wangzhi.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>群聊推广链接</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 5px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#980000">活码推广链接：<br></font>
                    <font color="#000">使用本链接可以做到永久推广，无需更换，后台自适应最新地址！<br>
                    本链接采用最新技术，实时同步跳转您的【最新主域名】<br>
                    一次推广，终身<font color="red">不流失客户</font>，链接永久不变！
                    </font><br><br>
                    <font color="#980000">当前链接： (点击可访问)<br></font>
                    <font color="#000"><a href="<?php echo $conf['coslb01'] ?>?zid=<?php echo $zid?>" target="_blank" rel="noreferrer"><?php echo $conf['coslb01'] ?>?zid=<?php echo $zid?></a></font>
                    <a style="width: 100%;" href="javascript:;" id="copy-btn1" data-clipboard-text="<?php echo $conf['coslb01'] ?>?zid=<?php echo $zid?>">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    <br><br>
                    <font color="#980000">小提示：<br></font>
                    <font color="#000">链接过长可把链接缩短方便推广，<a style="color: #4a86e8;" href="https://www.985.so/" target="_blank">点我进入缩短链接网站</a>（选择2号生成即可）</font><br>
                    
            <!--<div class="text-center" style="padding: 20px 0;">
                <a href=""><input type="submit" name="submit" class="btn submit_btn" style="width: 70%;padding:5px;" value="生成推广二维码"></a>
            </div>-->
                </div>
                </div>
            </div>
        </div>
        
        

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/erweima.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>群聊推广二维码</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a type="submit" onclick="CunTips()" style="color: #fff;">保存海报</a></div></div>
        <div class="layui-carousel" id="test1" lay-filter="test1" style="margin: 25px auto; width: 95%; height: 50vh;" lay-anim="" lay-indicator="outside" lay-arrow="always">
            <div carousel-item="" class="text-center">
                <div class="layui-this">
                    <img style=" height: 50vh" id="img_1" src="./timg/qunliao.php?id=1&url=<?php echo $conf['coslb01'] ?>?zid=<?php echo $zid?>" alt="推广图1"></div>
        </div>
    </div>
       </div>
    </div>
<?php }?>
    
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
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
if(window.location.hash=='#chongzhi'){
	$("#userjs").modal('show');
}
	$.ajax({
		type : "GET",
		url : "ajax_user.php?act=create_url",
		dataType : 'json',
		async: true,
		success : function(data) {
			if(data.code == 0){
				$("#copy-btn").html(data.url);
				$("#copy-btn").attr('data-clipboard-text',data.url);
			}else{
				$("#copy-btn").html(data.msg);
			}
		}
	});
});
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
var clipboard = new Clipboard('#copy-btn1');
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