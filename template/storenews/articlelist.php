<?php
if(!defined('IN_CRONLITE'))exit();
$kw=!empty($_GET['kw'])?trim(daddslashes($_GET['kw'])):null;
if($kw){
	$sql=" title LIKE '%$kw%'";
	$link="&kw=".$kw;
}else{
	$sql=" 1";
}
$msgcount=$DB->getColumn("SELECT count(*) FROM shua_article WHERE active=1");
$pagesize=10;
$pages=ceil($msgcount/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);
$rs=$DB->query("SELECT id,title,content,addtime FROM shua_article WHERE{$sql} AND active=1 ORDER BY top DESC,id DESC LIMIT $offset,$pagesize");
$msgrow=array();
while($res = $rs->fetch()){
	$msgrow[]=$res;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>文章列表</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
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
<body>
<style>
    .layui-carousel, .layui-carousel>[carousel-item]>* {
        background-color:transparent;
    }
    .onclick{cursor: pointer;touch-action: manipulation;}
    #msglist a:hover, #msglist a:active, #msglist a {text-decoration: none;display: block;color: #337ab7;}    
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
                <a href="./user"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">文章列表</a></font>

            </div>
                </div>
                 <div style="padding-top: 60px;"></div>
<style>
.main-content {
    width: 100%;
    height: 100%;
    background-color: #97c6ff;
}
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
.content-item1 {
    width: 380px;
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
.content-item .content-item-top .charge-btn1 {
    width: 94px;
    padding: 5px;
    font-size: 14px;
    color: #fff;
    text-align: center;
    margin-left: auto;
    background-image: linear-gradient(121deg,#aaaaaa,#868686);
    border-radius: 15px;
}
.content-item-bottom {
    padding: 16px;
}
.content-item-pake {
    padding: 5px;
}
</style>
<div class="main-content">
<?php
foreach($msgrow as $row){
	 $content = strip_tags($row['content']);
	 $addtime = strip_tags($row['addtime']);
	 if (mb_strlen($content) > 80)
		 $content = mb_substr($content, 0, 0, 'utf-8') . '';
    echo '<div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/wenzhang.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span></span></a>
                    <a style="font-size: 12px; color: #868686; padding-left: 11px;"><span>'.$addtime.'</span></a>
                    <div class="charge-btn"><a href="'.article_url($row['id']).'" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">'.strip_tags($row['title']).'</font>
                </div>
            </div>
        </div>';
echo '</div>';
}
if($msgcount==0){
	echo '<tr><td class="text-center"><font color="grey">文章列表空空如也</font></td></tr>';
}
?>			
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 50%;">
        <div class="content-item1">
            </div>
        </div>
			</tbody>
        
		<?php if($msgcount>$pagesize){
		if($page>1){
			echo '<a href="'.article_url(0, 'page='.($page-1).$link).'" class="btn btn-default">上一页</a>';
		}
		if($page<$pages){
			echo '<a href="'.article_url(0, 'page='.($page+1).$link).'" class="btn btn-default pull-right">下一页</a>';
		}
		}?>
        </div>
      </div>
    </div>
  </div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script>
var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();
$(document).ready(function(){
if($_GET['kw']){
	$("input[name='kw']").val(decodeURIComponent($_GET['kw']))
}
$("#doSearch").click(function () {
	var kw = $("input[name='kw']").val();
	window.location.href="./?mod=articlelist&kw="+encodeURIComponent(kw);
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