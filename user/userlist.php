<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--用户管理-->
    <?php include("../includes/common.php");$title='用户管理';?>
    <!---->
      <title>用户管理</title>
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
<body><link rel="stylesheet" href="../assets/user/css/work.css">

    <?php
    if($userrow['power']==0){
    	showmsg('您没有权限使用此功能！',3);
    }
    ?>
    
<style>
  .list-btn{
        position: absolute;
        top: 15%;
        right: 0;
        width: 10rem;

    }
    .list-btn-item{
        width: 100%;
        height: 3.15rem;
        text-align: center;
        line-height: 3.15rem;
        margin-bottom: 10px;
        color: #fff;
        border-radius: 50px 0 0 50px;
        font-size: 1.15rem;
        box-shadow: 1px 1px 5px #e2dfdf, 1px 1px 5px #dedede;
    }    
  .list-btn1{
        position: absolute;
        top: 55%;
        right: 0;
        width: 10rem;

    }
    .list-btn-item1{
        width: 100%;
        height: 3.15rem;
        text-align: center;
        line-height: 3.15rem;
        margin-bottom: 10px;
        color: #fff;
        border-radius: 50px 0 0 50px;
        font-size: 1.15rem;
        box-shadow: 1px 1px 5px #e2dfdf, 1px 1px 5px #dedede;
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
</style>

<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block " style="float: none; background-color:#fff;padding:0;max-width: 550px;">
    <div class="block  block-all">
        <div class="block-white">
            <div class="block-back display-row align-center justify-between" style="position: fixed;background-color: #fff;max-width: 550px;left: 50%;transform: translateX(-50%);z-index: 9999;">
                <div style="border-width: .5px;
    border-radius: 100px;
    border-color: #dadbde;
    background-color: #f2f2f2;
    padding: 3px 7px;
    opacity: .8;align-items: center;justify-content: space-between;display: flex; flex-direction: row;height: 30px;">
                <a href="./"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">用户管理</a></font>

            </div>
                </div>

<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/yonghutb.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>
                    <?php
                        if($userrow['power']<1)
                            {
                        	showmsg('你没有权限使用此功能！',3);
                            }
                           $my=isset($_GET['my'])?$_GET['my']:null;
                           $numrows=$DB->getColumn("SELECT count(*) FROM shua_site WHERE upzid='{$userrow['zid']}' AND power=0");
                        
                        if(isset($_GET['zid']))
                            {
                    	   $zid=intval($_GET['zid']);
                    	   $sql = " zid={$zid} AND upzid='{$userrow['zid']}' AND power=0";
                            }elseif(isset($_GET['kw'])){
	                       $kw=daddslashes($_GET['kw']);
	                       $sql = " (user='{$kw}' OR qq='{$kw}') AND upzid='{$userrow['zid']}' AND power=0";
                            }else
                            {
	                       $sql = " upzid='{$userrow['zid']}' AND power=0";
                            }
                           $con='你共有 <b>'.$numrows.'</b> 个下级普通用户<br/>';

                    echo '<div class="my-cell-title-r  display-row  align-center" style="color: #939393;font-size:1.3rem">';
                    echo $con;
                    echo '</div>';

                    ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
        <div class="max-width">
            <form action="userlist.php" method="GET">
                <div class="form-group" style="margin-top: 15px">
                    <div class="input-group">
                        <input type="text" class="form-control search-input" style="background: #fff;text-align: left;" name="kw" placeholder="请输入用户名或QQ">
                        <div class="input-group-addon" style="padding: 0 12px">
                            <input type="submit" style="border: 1px solid transparent; color: #0b9ff5" value="搜索">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php  if( $userrow['power']==2){?>
                <?php
                $pagesize=10;
                $pages=ceil($numrows/$pagesize);
                $page=isset($_GET['page'])?intval($_GET['page']):1;
                $offset=$pagesize*($page - 1);
                $rs=$DB->query("SELECT * FROM shua_site WHERE{$sql} ORDER BY zid DESC LIMIT $offset,$pagesize");
                while($res = $rs->fetch())
                {
                echo 
                
                '

<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/yonghu.png" style="width: 20%; margin-right: 20%;">
                    <a style="font-weight: 600; font-size: 12px; margin-right: 8px;"><span></span></a>
                    <div class="charge-bkk2"><a href="./upuser.php?my=edit&zid='.$res['zid'].'" style="color: #fff;">升级分站</a></div>
                    <div class="charge-bkk1"><a href="#" onclick="alert(\'普通用户升级合伙人请先将用户升级成分站站长，再去分站管理里升级合伙人\')" style="color: #fff;">升级合伙人</a></div></div>
                    <div class="content-item-bottom1">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">用户𝘜𝘐𝘋：'.$res['zid'].'<br></font>
                    <font color="#000">用户𝘘𝘘：'.$res['qq'].'<a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="'.$res['qq'].'">
                    <img style="width:15px;height:20px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a><br></font>
                    <font color="#000">注册时间：'.$res['addtime'].'<br></font>
                </div>
                </div>
            </div>
        </div>
';
                }
                ?>
<?php }?>

<?php  if( $userrow['power']==1){?>
                <?php
                $pagesize=10;
                $pages=ceil($numrows/$pagesize);
                $page=isset($_GET['page'])?intval($_GET['page']):1;
                $offset=$pagesize*($page - 1);
                
                $rs=$DB->query("SELECT * FROM shua_site WHERE{$sql} ORDER BY zid DESC LIMIT $offset,$pagesize");
                while($res = $rs->fetch())
                {
                echo 
                
                '
                
                <div class="list-item"style="margin-top: 15px">
                    <div class="list-item-top">
                        <div class="item-logo-2" style="width: auto;padding-right: 10px">
                         <div class="item-logo-img"style="width: auto;padding: 0 25px">UID:'.$res['zid'].'
                         </div>
                         <div class="item-operate"></div>
                    </div>
                </div>

                <div class="list-item-c">
                     <div class="item-c-txet">
                          <div class="item-c-title">用户ＱＱ</div>
                          <div class="item-c-data"> '.$res['qq'].'</div>
                 </div>    
                 
                 <div class="item-c-txet">
                     <div class="item-c-title">注册时间</div>
                     <div class="item-c-data">'.$res['addtime'].'</div>
                 </div>
                 

              </div>
                </div>';
                }
                ?>
<?php }?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
            </div>
        </div>
        </div>
            </form>
        </div> 
        <div class="text-center"><ul class="pagination" style="margin-left:1em">
<div align="center">
            <?php
echo'<ul class="pagination" style="margin-left:1em">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="userlist.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="userlist.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-3>1?$page-3:1;
$end=$page+3<$pages?$page+3:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="userlist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="userlist.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="userlist.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="userlist.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
?>
</div>
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