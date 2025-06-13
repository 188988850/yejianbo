<?php
if(!defined('IN_CRONLITE'))exit();
?>
<?php
/**
 * 商品销量排行榜
**/
include("../includes/common.php");
$title='商品销量排行榜';
?>
<?php
$thtime=date("Y-m-d",strtotime("-1 day")).' 00:00:00';

$lastday=date("Y-m-d",strtotime("-2 day")).' 00:00:00';
$Weeklysales=date("Y-m-d",strtotime("-7 day")).' 00:00:00';
$Monthlysales=date("Y-m-d",strtotime("-30 day")).' 00:00:00';
if($_GET['last']==0){
	$sql = "SELECT B.tid,B.name,count(A.id) num FROM shua_orders A LEFT JOIN shua_tools B ON A.tid=B.tid WHERE A.addtime>='$thtime' GROUP BY B.tid ORDER BY num DESC LIMIT 100";
}
if($_GET['last']==1){
	$sql = "SELECT B.tid,B.name,count(A.id) num FROM shua_orders A LEFT JOIN shua_tools B ON A.tid=B.tid WHERE A.addtime>='$lastday' AND A.addtime<'$thtime' GROUP BY B.tid ORDER BY num DESC LIMIT 100";
}
if($_GET['last']==2){
	$sql = "SELECT B.tid,B.name,count(A.id) num FROM shua_orders A LEFT JOIN shua_tools B ON A.tid=B.tid WHERE A.addtime>='$Weeklysales' AND A.addtime<'$thtime' GROUP BY B.tid ORDER BY num DESC LIMIT 100";
}
if($_GET['last']==3){
	$sql = "SELECT B.tid,B.name,count(A.id) num FROM shua_orders A LEFT JOIN shua_tools B ON A.tid=B.tid WHERE A.addtime>='$Monthlysales' AND A.addtime<'$thtime' GROUP BY B.tid ORDER BY num DESC LIMIT 100";
}
?>
<style type="text/css">
<!--
.STYLE3 {font-size: 14px}
-->
</style>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
  <title>商品销量排行榜</title>
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
                <a href="javascript:history.back()"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">商品销量排行榜</a></font>

            </div>
        </div>
    </div>
 <div style="padding-top: 60px;"></div>
<div class="main-content1">
<div class="block">

<ul class="nav nav-pills">
<li class="<?php echo $_GET['last']==0?'active':null;?>" style="width:24.5%"><a href="/?mod=paihang" style="font-size: 13px;"><center>24h销量<br>TOP100</center></a></li>
<li class="<?php echo $_GET['last']==1?'active':null;?>" style="width:24.5%"><a href="/?mod=paihang&last=1" style="font-size: 13px;"><center>48h销量<br>TOP100</center></a></li>
<li class="<?php echo $_GET['last']==2?'active':null;?>" style="width:24.5%"><a href="/?mod=paihang&last=2" style="font-size: 13px;"><center>7日销量<br>TOP100</center></a></li>
<li class="<?php echo $_GET['last']==3?'active':null;?>" style="width:24.5%"><a href="/?mod=paihang&last=3" style="font-size: 13px;"><center>30日销量<br>TOP100</center></a></li>
</ul>

      <div>

            <br>
          <tbody>
              <!--<br><br><br><br><div style="text-align:center;color: #838383;">- 暂未公布排行榜信息 -</div><br><br><br><br>-->

<?php
$rs=$DB->query($sql);
$i=1;
while($res = $rs->fetch())
{
    echo '<div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>NO.'.$i.'</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="/?mod=buy1&tid='.$res['tid'].'" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom1">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">'.$res['name'].'</font>
                </div>
            </div>
        </div>';
echo '</div>';
$i++;
}
?>

          </tbody>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
            </div>
        </div>
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