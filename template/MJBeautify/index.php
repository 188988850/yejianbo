<?php
if(!defined('IN_CRONLITE'))exit();
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/><!--禁止全屏缩放-->
<meta name="format-detection" content="telephone=no"/><!--不显示成手机号-->
<meta name="apple-mobile-web-app-capable" content="yes"/><!--删除默认的苹果工具栏和菜单栏-->
<meta name="wap-font-scale" content="no"/><!--解决UC手机字体变大的问题-->
<meta name="apple-mobile-web-app-status-bar-style" content="black"/><!--控制状态栏显示样式-->
  <title><?php echo $hometitle?></title>
  <meta name="keywords" content="<?php echo $conf['keywords']?>">
  <meta name="description" content="<?php echo $conf['description']?>">
  <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link href="<?php echo $cdnserver?>assets/css/nifty.min.css" rel="stylesheet">
  <link href="<?php echo $cdnserver?>assets/css/magic-check.min.css" rel="stylesheet">
  <link href="<?php echo $cdnserver?>assets/css/pace.min.css" rel="stylesheet">
  <link href="<?php echo $cdnserver?>assets/beautify/css/mj-beautify.css" rel="stylesheet">
  <link href="<?php echo $cdnserver?>assets/beautify/css/public-style.main.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css?ver=<?php echo VERSION ?>">
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<?php echo $background_css?>
<style>
body{ font-size:14px; padding:0;margin:0 auto; position:relative; }
</style>
</head>
<body>
<div class="menu-zzc"></div>
<!--顶部导航-->
<header>
	
	<font class="text"><?php echo $conf['sitename']?></font>
	<a class="fade-out" onclick="openNav()"><i class="glyphicon glyphicon-th"></i>更多</a>
	<a class="fade-in" href="javascript:void(0)" class="closebtn" onclick="closeNav()">
        <i class="fa fa-circle-o-notch" style="font-size:14px;"></i>关闭</a>
</header>
<!--隐藏菜单-->
<div id="hide-menu">
  <div class="hide-gdt">
    <div style="width:200px;height:100%;background: rgba(59, 51, 51, 0.2);">
      <div style="height:92px;padding-top:12px;">
        <div class="img-box">
          <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq']?>&site=qq&menu=yes" target="_blank">
            <img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq']?>&spec=100" alt="Avatar"></a>
        </div>
      </div>
      <div class="zzqq-text">站长QQ：
        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq']?>&site=qq&menu=yes" style="color:#0080ff;" target="_blank">
          <?php echo $conf[ 'kfqq']?></a>
        <img src="./assets/beautify/img/gg-rz.jpg" style="width:30px;height:13px;margin:-2px 0 0 5px;">
        <br>
        <font style="color:#ff8000;font-size:11px;">商务合作可以找我哦</font></div>
      <naw>
        <a class="a" href="./"><i class="fa fa-home fa-fw"></i> 网站首页</a>
		<a class="a" href="./?chadan=1"><i class="fa fa-search fa-fw"></i> 订单查询</a>
		<?php if($islogin2==1){?>
		<a class="a" href="./user/"><i class="fa fa-user fa-fw"></i> 用户中心</a>
		<?php }else{?>
		<a class="a" href="./user/login.php"><i class="fa fa-user fa-fw"></i> 用户登录</a>
		<a class="a" href="./user/reg.php"><i class="fa fa-plus-circle fa-fw"></i> 免费注册</a>
		<?php }?>
        <a class="a" href="./index.php?mod=gywm"><i class="fa fa-amazon fa-fw"></i> 关于我们</a>    
      </naw>
    </div>
  </div>
</div>

<div class="body-box" style="max-width: 800px;"><!-- auto-居中 -->

<!-- 首页公告 -->
<div class="main-panel">
  <div class="title">
    <font class="text">
      <i class="glyphicon glyphicon-fire"></i> 网站公告栏</font>
    <div class="notice-imgbox">
      <a data-toggle="modal" href="#kftc">
        <img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq']?>&spec=100" alt="Avatar" class="fa-spin" style="width:39px;height:39px;border:1px solid #5cadad;border-radius:50%;">
        <div class="tb">
          <span class="glyphicon glyphicon-plus"></span>
        </div>
    </div>
    </a>
   </div>
      <div style="padding:12px;border-top:#ddd solid 1px;">
<img style="width:100%;" src="./assets/beautify/img/bj-sygg.jpg">
</div>

<ul class="but-naw" style="margin:0">
	<li><a data-toggle="modal" href="#anounce">
	<span class="glyphicon glyphicon-file" style="font-size:12px; margin-right:3px;"></span>系统公告</a>
	</li>
	<li><a href="./user/upgrade.php" target="_blank">
	<span class="fa fa-qq" style="font-size:12px; margin-right:3px;"></span>开通分站</a>
	</li>
	<?php if(!empty($conf['appurl'])){?>
	<li><a href="<?php echo $conf['appurl']?>">
	<span class="glyphicon glyphicon-cloud-download" style="font-size:12px; margin-right:3px;"></span>APP下载</a>
	</li>
	<?php }else{?>
	<li><a href="./user/">
	<span class="glyphicon glyphicon-user" style="font-size:12px; margin-right:3px;"></span>管理后台</a>
	</li>
	<?php }?>
</ul>
</div>
<!-- end -->

<!-- 下单区域 -->
<div class="tab-content">
<div id="demo-tabs-box-1" class="tab-pane fade active in">
<div class="main-panel" >
    <div class="zzxd-title">
    <font class="text"><i class="fa fa-shopping-cart"></i> 自助下单栏 </font>
    <span class="pull-right" style="margin:7px 12px 0 0;" >
    <a href="./user/" class="btn btn-warning btn-rounded">
    <i class="fa fa-user"></i> 用户中心</a></span>
    </div>
<!-- 自助下单导航  -->
<ul class="nav nav-tabs zzxd-naw" data-toggle="tabs" style="background:#f3f3f3;">
    <li class="active"><a href="#onlinebuy" data-toggle="tab" style="border:none;">
            <div><span class="fa fa-shopping-cart fa-fw"></span></div>下 单</a>
    </li>
    <li ><a href="#query" data-toggle="tab" style="border:none;" id="tab-query">
            <div><i class="fa fa-search fa-fw"></i></div>查 单</a>
    </li>
    <li><a href="#fenzhan" data-toggle="tab" style="border:none;color:#f00;">
            <div><i class="fa fa-usd fa-fw fa-spin"></i></div>赚 钱</a>
    </li>
    <li align="center" <?php if(!$conf['articlenum']){?>class="hide"<?php }?>>
            <a href="#article" data-toggle="tab" style="border:none;"><div class="zzxd-naw-circular"><i class="fa fa-newspaper-o fa-fw"></i></div>文 章</a>
    </li>
    <li style="width: 20%;" align="center" <?php if($conf['fenzhan_buy']==1&&$conf['gift_open']==1){?>class="hide"<?php }?>><a href="./user/" style="border:none;">
            <div><i class="fa fa-user fa-fw"></i></div>登 录</a>
    </li>
    <li><a href="#more" data-toggle="tab" style="border:none;">
            <div><i class="fa fa-th-large fa-fw" style="font-size:13px; margin-left:1px;"></i></div>更 多</a>
    </li>
</ul>
<div class="modal-body">
<!--自助下单区域-->
		<div id="myTabContent" class="tab-content" >
		<div class="tab-pane fade in active" id="onlinebuy" style="padding:5px 0 10px 0;" >
<?php include TEMPLATE_ROOT.'default/shop.inc.php'; ?>
		</div>


<!--查单-->
<div class="tab-pane fade in" id="query" style="padding:8px 0;">
<div class="qqkf-card-title">
	<font class="tw1" style="color:#fff;font-size:12px;"><b>本站订单客服QQ</b></font>
	<i class="glyphicon glyphicon-asterisk right" style="color:#7ecef4;"></i>
	<img class="right" src="./assets/beautify/img/gg-pmd.jpg" style="width:70px;height:10px;margin:2px 10px;">
</div>
<div class="qqkf-card" style="margin-bottom:8px;">
	<div class="left-box"><img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq']?>&spec=100"></div>
	<div class="right-box tw1">
		<div>
			<b style="color:#f00">有啥问题要联系客服哦</b><img class="right" src="./assets/beautify/img/gg-rz.jpg" style="width:30px;height:15px;">
		</div>
		<b>QQ</b>：<a data-toggle="modal" href="#kftc" style="color:#0080ff"><?php echo $conf['kfqq']?></a>
	</div>
</div>
     <div class="cnt tw1" style="padding:10px 0;color:#a84200;font-size:13px;">
         <p><span class="label-yc" style="background:#f89b3c;">待处理</span> 说明正在努力提交到服务器 !</p>
         <p><span class="label-yc" style="background:#4b98e4;">已完成</span> 已经提交到服务器正在处理 !</p>
         <p><span class="label-yc" style="background:#fe5d5c;">有异常</span> 下单信息有误请联系客服哦 !</p>
     </div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-btn">
						<select class="form-control" id="searchtype" style="padding: 6px 4px;width:90px"><option value="0">下单账号</option><option value="1">订单号</option></select>
					</div>
				<input type="text" name="qq" id="qq3" value="<?php echo $qq?>" class="form-control" placeholder="请输入要查询的内容（留空则显示最新订单）" onkeydown="if(event.keyCode==13){submit_query.click()}" required/>
				<span class="input-group-btn"><a tabindex="0" class="btn btn-default" role="button" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" title="查询内容是什么？" data-content="请输入您下单时，在第一个输入框内填写的信息。如果您不知道下单账号是什么，可以不填写，直接点击查询，则会根据浏览器缓存查询！"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
			</div></div>
			<input type="submit" id="submit_query" class="btn btn-primary btn-block" value="立即查询">
			<div id="result2" class="form-group" style="display:none;">
				<table class="table table-striped">
				<thead><tr><th>下单账号</th><th>商品名称</th><th>数量</th><th class="hidden-xs">购买时间</th><th>状态</th><th>操作</th></tr></thead>
				<tbody id="list">
				</tbody>
				</table>
</div>
</div><!--end-->

<!--赚钱-->
<div class="tab-pane fade in" id="fenzhan" style="font-size:13px;letter-spacing:1px;">
	<div id="zzdj" class="tabcontent2">
		<div class="zq-card">
			<img src="./assets/beautify/img/fenzhan.jpg">
			<span>免费加盟！一键搭建与我们一样的商城网站，赚点零花钱完全不是问题哦 ~</span>
			<div style="line-height:45px;padding:0 12px;">
				<font color="#f00"><b><i class="fa fa-star-half-o fa-fw"></i> 分站搭建</b></font>
				<div class="right">
					<a type="button" class="btn btn-default btn-sm" href="./user/upgrade.php">立即搭建</a>&nbsp;
					<a type="button" class="btn btn-default btn-sm" href="./index.php?mod=fzjs">了解详情</a>
		</div></div></div>
	</div>
</div>
<!-- end-->

<!--文章列表-->
<?php if($conf['articlenum']>0){
$limit = intval($conf['articlenum']);
$rs=$DB->query("SELECT id,title FROM shua_article WHERE active=1 ORDER BY top DESC,id DESC LIMIT {$limit}");
$msgrow=array();
while($res = $rs->fetch()){
	$msgrow[]=$res;
}
$class_arr = ['danger','warning','primary','success','info'];
$i=0;
?>
<div class="tab-pane fade in" id="article">
	<?php foreach($msgrow as $row){
	echo '<a target="_blank" class="list-group-item" href="'.article_url($row['id']).'"><span class="btn btn-'.$class_arr[($i++)%5].' btn-xs">'.$i.'</span>&nbsp;'.$row['title'].'</a>';
	}?>
	<a href="<?php echo article_url()?>" title="查看全部文章" class="btn-default btn btn-block" target="_blank">查看全部文章</a>
</div>
<?php }?>

<!--抽奖-->
<?php if($conf['gift_open']==1){?>
<div class="tab-pane fade in" id="gift">
  <div class="panel-body text-center">
    <div id="roll">
      <img style="width:28px;height:28px;margin:-3px 5px 0 0;display:inline-block;" src="./assets/beautify/img/gg-ax.jpg">点击下方按钮开始抽奖</div>
      <hr><p>
      <a class="btn btn-info" id="start" style="display:block;">开 始 抽 奖</a>
      <a class="btn btn-danger" id="stop" style="display:none;">停 止</a></p>
      <div id="result"></div>
          <div class="giftlist" style="display:none;"><strong>最近中奖记录</strong>
             <ul id="pst_1"></ul>
          </div>
    </div>
</div>
<?php }?>

<!--更多-->
<div class="tab-pane fade fade-right" id="more">
  <div style="margin:8px -15px -17px -15px;">
    <ul id="more-naw">
      <?php if($conf['gift_open']==1){?><li>
            <a href="#gift" data-toggle="tab" style="background:#ee5757;">
            <div class="more-title-text"><i class="fa fa-gift"></i></div>抽奖</a>
      </li><?php }?>
      <?php if(!empty($conf['invite_tid'])){?><li>
            <a href="./?mod=invite" target="_blank" style="background:#f164f1;">
            <div class="more-title-text"><i class="glyphicon glyphicon-hand-right"></i></div>免费领赞</a>
      </li><?php }?>
      <li>
            <a href="./user/" target="_blank" style="background:#51b1ee;">
            <div class="more-title-text"><i class="glyphicon glyphicon-user"></i></div>用户中心</a>
      </li>
      <?php if($conf['fenzhan_buy']==1){?><li>
            <a href="./user/upgrade.php" target="_blank" style="background:#51b1ee;">
            <div class="more-title-text"><i class="fa fa-shield"></i></div>免费加盟</a>
      </li><?php }?>
      <?php if(!empty($conf['daiguaurl'])){?><li>
            <a href="./?mod=daigua" style="background:#79eded;">
            <div class="more-title-text"><i class="fa fa-qq"></i></div>QQ等级代挂</a>
      </li><?php }?>
      <?php if(!empty($conf['appurl'])){?><li>
            <a href="<?php echo $conf['appurl']; ?>" target="_blank" style="background:#f164f1;">
            <div class="more-title-text"><i class="fa fa-paper-plane-o"></i></div>APP下载</a>
      </li><?php }?>
    </ul>
  </div>
</div>
<!--end-->
</div></div></div></div><!--自助下单区域--end-->

<!--end-->

<!--查单说明开始-->
<div class="modal fade" align="left" id="cxsm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">查询内容是什么？该输入什么？</h4>
      </div>
      	<li class="list-group-item"><font color="red">请在右侧的输入框内输入您下单时，在第一个输入框内填写的信息</font></li>
      	<li class="list-group-item">例如您购买的是QQ类商品，输入下单的QQ账号即可查询订单</li>
      	<li class="list-group-item">例如您购买的是邮箱类商品，需要输入您的邮箱号，输入QQ号是查询不到的</li>
      	<li class="list-group-item">例如您购买的是短视频类商品，输入视频链接即可查询，不要带其他中文字符</li>
      	<li class="list-group-item"><font color="red">如果您不知道下单账号是什么，可以不填写，直接点击查询，则会根据浏览器缓存查询</font></li>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<!--查单说明结束-->

<!--站点信息-->
<div class="main-panel">
    <div class="title">
    <font class="text"><i class="fa fa-heartbeat"></i> 站点信息栏</font>
    <div class="right" style="line-height:50px;letter-spacing:2px;">
<img style="height:30px; width:27px; margin-right:5px;" src="./assets/beautify/img/gg-ax.jpg" >
<i><font color="#FF0000">我</font><font color="#DF0020">们</font><font color="#BF0040">一</font><font color="#9F0060">直</font><font color="#7F0080">用</font><font color="#5F00A0"><span class="glyphicon glyphicon glyphicon-heart-empty" style="margin-left:4px;"></span></font><font color="#3F00C0">在</font><font color="#1F00E0">做</div></i></font>
    </div>
    <table class="zdxx-form">
    <tbody><tr>
    <td><div class="left-box"><i class="fa fa-shopping-cart fa-fw"></i></div>
    <div class="right-box"><div class="text">订单总数</div>
9999+ 条</div></td>
    <td style="border-right:none"><div class="left-box"><i class="fa fa-firefox fa-fw" style="font-size:28px; "></i></div>
    <div class="right-box"><div class="text">旗下分站</div>
9999+ 个</div></td>
    </tr><tr>
    <td><div class="left-box"><i class="fa fa-usd fa-fw"></i></div>
    <div class="right-box"><div class="text">累计交易</div>
9999+ 元</div></td>
    <td style="border-right:none"><div class="left-box"><i class="fa fa-anchor fa-fw" style="font-size:28px; "></i></div>
    <div class="right-box"><div class="text">分站提成</div>
    9999+ 元</div></td>
    </tr></tbody>
    </table>
<div style="padding:8px 0; text-align:center;"><a href="./user/upgrade.php">
<img style="width:80px; margin-right:20px;" src="./assets/beautify/img/gg-hyff.jpg" >
<img style="width:80px; margin-right:20px;" src="./assets/beautify/img/gg-cyjm.jpg" >
<img style="width:80px; " src="./assets/beautify/img/gg-gtzf.jpg" ></a>
</div>
</div>
<!-- end -->

<!-- 站点导航 -->
<div class="main-panel" <?php if($conf['bottom']==''){?>style="display:none;"<?php }?>> 
<div class="title">
<font class="text"><i class="fa fa-paper-plane-o"></i> 站点导航栏</font>
    <span class="pull-right" style="margin-top:7px;" >
    <a href="./index.php?mod=gywm" class="btn btn-info rdu-5x">关于我们</a></span>
</div>
<?php echo $conf['bottom']?>
</div>

</div><!-- auto-居中-end -->

<footer><!-- 页脚 -->
<div id="footer-naw-bj">
	<div id="footer-naw-center">
		<ul id="footer-naw">
			<li><a style="border:none;" data-toggle="modal" href="#kftc">联系客服</a></li>
			<li><a class="dibudh-a" href="javascript:window.scrollTo(0,0)" class="top" title="回顶部">回到顶部</a></li>
		</ul>
	</div>
</div>
<div id="footer-img-box">
	<img style="height:25px;margin-right:12px;" src="./assets/beautify/img/gg-bzzx.jpg">
	<img style="height:25px;margin-right:12px;" src="./assets/beautify/img/gg-cxwz.jpg">
	<img style="height:25px;margin-right:12px;" src="./assets/beautify/img/gg-txrz.jpg"></a>
</div>
<div id="footer-text">
        版权所有@ [ <a href="./"><?php echo $conf['sitename']?></a> ]<br/>All Right Reserved.<br/><?php echo $conf['footer']?>
</div>
</footer>

<div class="set-top"><!-- 置顶按钮 -->
  <span class="fa fa-chevron-up" style="font-size:10px;"></span>
</div>

<!-- 首页弹出公告 -->
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog popup-bj" style="margin-right:2px;margin-left:12px;max-width:520px;">
    <div class="popup-title">
      <div class="popup-title-text"><i class="glyphicon glyphicon-send"></i> <?php echo $conf['sitename']?></div>
      <a type="button" class="popup-gb-button" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove" style=" font-size:20px; "></span>
      </a>
    </div>
    <div class="modal-body" style="padding:10px;color:#272727;color:#272727;font-size:13px;"><?php echo $conf['modal']?></div>
    <div class="modal-footer" style="border-top-color:#399;">
      <button type="button" class="btn btn-default" data-dismiss="modal">知道啦</button>
    </div>
  </div>
</div>
<!-- end -->

<!-- 首页公告 -->
<div class="modal fade" align="left" id="anounce" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog popup-bj" style="margin-right:2px;margin-left:12px;max-width:520px;">
    <div class="popup-title">
      <div class="popup-title-text"><i class="glyphicon glyphicon-send"></i> 系统公告</div>
      <a type="button" class="popup-gb-button" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove" style=" font-size:20px; "></span>
      </a>
    </div>
    <div class="modal-body" style="padding:10px;color:#272727;color:#272727;font-size:13px;"><?php echo $conf['anounce']?></div>
    <div class="modal-footer" style="border-top-color:#399;">
      <button type="button" class="btn btn-default" data-dismiss="modal">知道啦</button>
    </div>
  </div>
</div>
<!-- end -->

<!-- 客服弹窗 -->
<div class="modal fade" align="left" id="kftc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog popup-bj" style="margin-right:2px;margin-left:12px;max-width:520px;">
		<div class="popup-title">
			<div class="popup-title-text">
				平台客服列表<i class="glyphicon glyphicon-chevron-right"></i>
			</div>
			<a type="button" class="popup-gb-button" data-dismiss="modal">
			<span class="glyphicon glyphicon-remove" style=" font-size:20px; "></span>
			</a>
		</div>
		<div class="modal-body" style="padding:0;color:#272727;font-size:13px;">
			<div style="line-height:55px;font-size:15px;border-bottom:#8efe94 inset 1px;text-align:center;">
				<img src="./assets/beautify/img/gg-sz.png" style="width:38px;height:38px;margin-right:1%;">
				<font style="font-weight:bold;letter-spacing:2px;">工作时间：</font>
				<font style="color:#f00;">9:00 ~ 22:00</font>┃☆★☆★
			</div>
			<div class="qqkf-card-two" style="margin:12px;">
				<div class="left-box">
					<img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq']?>&spec=100">
				</div>
				<div class="right-box tw1">
					<div class="tw2" style="padding:6px 0;color:#f00;font-size:14px;">
        <b>本站售后客服QQ ↓</b>
						<img class="right" src="./assets/beautify/img/gg-rz.jpg" style="width:30px;height:15px;">
					</div>
					<b>QQ</b>：<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq']?>&site=qq&menu=yes" target="_blank" style="color:#0080ff;"><?php echo $conf[ 'kfqq']?>
					</a>
					<div style="padding:4px 0;font-size:11px;color:#ff8000;">
一直用心在做 --- 
						<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq']?>&site=qq&menu=yes" target="_blank" class="btn btn-info btn-sm rdu-5x" style="padding:2px 6px; margin-left:2%;">联系客服</a>
					</div>
				</div>
			</div>
			<div style="padding:0 10px 12px 10px;">
				<font color="#ff000">友情提示</font>：为提高效率！联系客服时_请有事说事哦~ 无需问在不在的！切勿抖动_视频
			</div>
			<div class="modal-footer" style="border-top-color:#399;">
				<button type="button" class="btn btn-default" data-dismiss="modal">知道啦</button>
			</div>
		</div>
	</div>
</div><!-- end -->
<!--音乐代码-->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
  <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
    <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
  </div>
</div>
<!--音乐代码-->

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnserver?>assets/js/pace.min.js"></script>
<script src="<?php echo $cdnserver?>assets/beautify/js/beautify.js"></script>
<script type="text/javascript">
var isModal=<?php echo empty($conf['modal'])?'false':'true';?>;
var homepage=true;
var hashsalt=<?php echo $addsalt_js?>;
$(function() {
	$("img.lazy").lazyload({effect: "fadeIn"});
	$('a[data-toggle="popover"]').popover();
});
</script>
<script src="assets/js/main.js?ver=<?php echo VERSION ?>"></script>
<?php if($conf['classblock']==1 || $conf['classblock']==2 && checkmobile()==false)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>