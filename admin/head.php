<?php
if (!function_exists('is_https')) {
    function is_https() {
        if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
            return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && (443 == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
}
if(!defined('IN_CRONLITE'))exit();
@header('Content-Type: text/html; charset=UTF-8');

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$scriptpath.'/';

$admin_cdnpublic = 1;
if($admin_cdnpublic==1){
	$cdnpublic = '//lib.baomitu.com/';
}elseif($admin_cdnpublic==2){
	$cdnpublic = 'https://cdn.bootcdn.net/ajax/libs/';
}elseif($admin_cdnpublic==4){
	$cdnpublic = '//lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/';
}else{
	$cdnpublic = '//cdn.staticfile.org/';
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="renderer" content="webkit"/>
  <meta name="force-rendering" content="webkit"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title><?php echo $title ?></title>
  <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/appui/css/main.css">
  <link rel="stylesheet" href="../assets/appui/css/themes.css">
  <link id="theme-link" rel="stylesheet" href="<?php echo $_COOKIE['optionThemeColor']?$_COOKIE['optionThemeColor']:'../assets/appui/css/themes/baise-2.4.css';?>">
  <script src="<?php echo $cdnpublic?>jquery/2.1.4/jquery.min.js"></script>
  <script src="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="../assets/appui/js/plugins.js"></script>
  <script src="../assets/appui/js/app2.js"></script>
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
    #finance-content { display:none; }
  </style>
</head>
<body>
<!-- Start: Header -->
    <div id="page-wrapper">
        <div id="page-container" class="header-fixed-top sidebar-visible-lg-full enable-cookies">
<div id="sidebar-alt" tabindex="-1" aria-hidden="true">
<a href="javascript:void(0)" id="sidebar-alt-close" onclick="App.sidebar('toggle-sidebar-alt');"><i class="fa fa-times"></i></a>
<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 888px;"><div id="sidebar-scroll-alt" style="overflow: hidden; width: auto; height: 888px;">
<div class="sidebar-content">
<div class="sidebar-section">
<style>
h4{font-family:"微软雅黑",Georgia,Serif;}
</style>
<h4 class="text-light">框架变色(New)</h4>
<br>
<ul class="sidebar-themes clearfix">
<li class="">
<a href="javascript:void(0)" class="themed-background-default" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/themes-2.2.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="" data-original-title="">
<span class="section-side themed-background-dark-default"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-classy" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/classy-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="" data-original-title="">
<span class="section-side themed-background-dark-classy"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-social" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/social-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="" data-original-title="">
<span class="section-side themed-background-dark-social"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-flat" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/flat-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="" data-original-title="">
<span class="section-side themed-background-dark-flat"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-amethyst" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/amethyst-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="" data-original-title="">
<span class="section-side themed-background-dark-amethyst"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-creme" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/creme-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="" data-original-title="">
<span class="section-side themed-background-dark-creme"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-passion" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/passion-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="" data-original-title="">
<span class="section-side themed-background-dark-passion"></span>
<span class="section-content"></span>
</a>
<br>
</li>
<li>
<a href="javascript:void(0)" class="themed-background-classy" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/classy-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="sidebar-light" data-original-title="">
<span class="section-side"></span>
<span class="section-content"></span>
</a>
</li>
<li>
<a href="javascript:void(0)" class="themed-background-social" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/social-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="sidebar-light" data-original-title="">
<span class="section-side"></span>
<span class="section-content"></span>
</a>
</li>
<li>
<a href="javascript:void(0)" class="themed-background-flat" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/flat-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="sidebar-light" data-original-title="">
<span class="section-side"></span>
<span class="section-content"></span>
</a>
</li>
<li>
<a href="javascript:void(0)" class="themed-background-amethyst" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/amethyst-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="sidebar-light" data-original-title="">
<span class="section-side"></span>
<span class="section-content"></span>
</a>
</li>
<li>
<a href="javascript:void(0)" class="themed-background-creme" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/creme-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="sidebar-light" data-original-title="">
<span class="section-side"></span>
<span class="section-content"></span>
</a>
</li>
<li>
<a href="javascript:void(0)" class="themed-background-passion" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/passion-2.4.css" data-theme-navbar="navbar-inverse" data-theme-sidebar="sidebar-light" data-original-title="">
<span class="section-side"></span>
<span class="section-content"></span>
</a>
</li>

<li class="">
<a href="javascript:void(0)" class="themed-background-classy" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/classy-2.4.css" data-theme-navbar="navbar-default" data-theme-sidebar="" data-original-title="">
<span class="section-header"></span>
<span class="section-side themed-background-dark-classy"></span>
<span class="section-content"></span>
</a>
<br>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-social" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/social-2.4.css" data-theme-navbar="navbar-default" data-theme-sidebar="" data-original-title="">
<span class="section-header"></span>
<span class="section-side themed-background-dark-social"></span>
<span class="section-content"></span>
</a>
</li>
<li>
<a href="javascript:void(0)" class="themed-background-flat" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/flat-2.4.css" data-theme-navbar="navbar-default" data-theme-sidebar="" data-original-title="">
<span class="section-header"></span>
<span class="section-side themed-background-dark-flat"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-amethyst" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/amethyst-2.4.css" data-theme-navbar="navbar-default" data-theme-sidebar="" data-original-title="">
<span class="section-header"></span>
<span class="section-side themed-background-dark-amethyst"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-creme" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/creme-2.4.css" data-theme-navbar="navbar-default" data-theme-sidebar="" data-original-title="">
<span class="section-header"></span>
<span class="section-side themed-background-dark-creme"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-passion" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/passion-2.4.css" data-theme-navbar="navbar-default" data-theme-sidebar="" data-original-title="">
<span class="section-header"></span>
<span class="section-side themed-background-dark-passion"></span>
<span class="section-content"></span>
</a>
</li>
<li class="">
<a href="javascript:void(0)" class="themed-background-baise" data-toggle="tooltip" title="" data-theme="../assets/appui/css/themes/baise-2.4.css" data-theme-navbar="navbar-default" data-theme-sidebar="" data-original-title="">
<span class="section-header"></span>
<span class="section-side themed-background-dark-baise"></span>
<span class="section-content"></span>
</a>
</li>
</ul>
</div>
</div>
</div><div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 888px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 1; z-index: 90; right: 1px;"></div></div>
</div>
            <div id="sidebar">
                <div id="sidebar-brand" class="themed-background">
				<a href="./" class="sidebar-title">
                    <i class="fa fa-windows"></i> <span class="sidebar-nav-mini-hide">后台管理系统</span>
                </a>
				</div>
                <div id="sidebar-scroll">
                    <div class="sidebar-content">
                        <ul class="sidebar-nav">
<br>
<li>
		<span class="sidebar-nav-mini-huise">&nbsp;&nbsp;后台主页</span>
</li>
<li>
	<a class="<?php echo checkIfActive('index,')?>" href="./">
		<i class="fa fa-home sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">后台首页</span>
	</a>
</li>
<br>
<li>
		<span class="sidebar-nav-mini-huise">&nbsp;&nbsp;数据管理</span>
</li>
<li>
	<a class="<?php echo checkIfActive('list,export')?>" href="./list.php">
		<i class="fa fa-list-ol sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">订单管理</span>
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('shoplist2')?>" href="./shoplist2.php?kw=1">
		<i class="fa fa-align-left sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">下架商品</span>
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('shoplist')?>" href="./shoplist.php">
		<i class="fa fa-align-left sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">商品列表</span>
	</a>
</li>
<li class="<?php echo checkIfActive('classlist,shoplist,shopedit,price,shoprank,cardlist,stock_notice,fakalist,fakakms,mailcon,shoprank')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-shopping-cart sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">商品管理</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("classlist") ?>" href="./classlist.php">
		分类列表
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("shoplist,shoprank") ?>" href="./shoplist.php">
		商品列表
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("shopedit") ?>" href="./shopedit.php?my=add&cid=">
		添加商品
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("price") ?>" href="./price.php">
		加价模板
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("fakalist") ?>" href="./fakalist.php">
		库存管理
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("fakakms") ?>" href="./fakakms.php?my=add">
		添加卡密
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("mailcon") ?>" href="./set.php?mod=mailcon">
		发信模板
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("cardlist") ?>" href="./cardlist.php?my=add">
		兑换卡密
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('stock_notice')?>" href="./stock_notice.php">库存不足提醒
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("shoprank") ?>" href="./shoprank.php">
		商品销量排行榜
	</a>
</li>
	</ul>
</li>


<li class="<?php echo checkIfActive('article,rewrite,message,gonggao,copygg')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-bullhorn sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">公告管理</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("gonggao,copygg") ?>" href="./set.php?mod=gonggao">
		公告配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('article,rewrite')?>" href="./article.php">
		文章管理
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("message") ?>" href="./message.php">
		站内通知
	</a>
</li>
	</ul>
</li>
<li class="<?php echo checkIfActive('sitelist,siteprice,userlist,tixian,rank,workorder')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-group sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">用户管理</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("sitelist,siteprice") ?>" href="./sitelist.php">
		分站列表
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("userlist") ?>" href="./userlist.php">
		用户列表
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("workorder") ?>" href="./workorder.php">
		工单列表
	</a>
</li>
<?php if($conf['fenzhan_tixian']==1){?>
<li>
	<a class="<?php echo checkIfActive("tixian") ?>" href="./tixian.php">
		余额提现
	</a>
</li>
<?php }?>
<li>
	<a class="<?php echo checkIfActive("rank") ?>" href="./rank.php">
		分站排行
	</a>
</li>
	</ul>
</li>
<li class="<?php echo checkIfActive('duanju,duanju1,fushouy')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-group sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">短剧管理</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("duanju") ?>" href="./duanju.php">
		网盘短剧列表
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("duanju1") ?>" href="./duanju1.php?my=add">
		添加网盘短剧
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('videotype')?>" href="./videotype.php">
		<i class="fa fa-align-left sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">短剧分类</span>
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('videolist')?>" href="./videolist.php">
		<i class="fa fa-align-left sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">短剧列表</span>
	</a>
</li>

	<a class="<?php echo checkIfActive("fushouy") ?>" href="./fushouy.php">
		网盘收益统计
	</a>
</li>
	</ul>
</li>
<li class="<?php echo checkIfActive('fufeulist,fuxg,zhezhi,fushouy')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-group sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">群聊管理</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("fuxg") ?>" href="./fuxg.php">
		付费群列表
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("fuxg") ?>" href="./fuxg.php?my=add">
		添加付费群
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("zhezhi") ?>" href="./zhezhi.php">
		分成配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("fushouy") ?>" href="./fushouy.php">
		收益统计
	</a>
</li>
	</ul>
</li>
<li class="<?php echo checkIfActive('cron,pricejk,orderjk')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-leaf sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">监控中心</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("cron") ?>" href="./set.php?mod=cron">
		计划任务设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("pricejk") ?>" href="./pricejk.php">
		商品价格监控 <i class="fa fa-eye"></i>
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("orderjk") ?>" href="./orderjk.php">
		订单状态监控 <i class="fa fa-eye"></i>
	</a>
</li>
	</ul>
</li>
<br>
<li>
		<span class="sidebar-nav-mini-huise">&nbsp;&nbsp;货源对接</span>
</li>
<li class="<?php echo checkIfActive('shequlist,log,clone,cloneset,shequ')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-gg-circle sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">对接设置</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("shequlist") ?>" href="./shequlist.php">
		对接站点管理
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("log") ?>" href="./log.php">
		对接日志
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("clone,cloneset") ?>" href="./clone.php">
		克隆站点
	</a>
</li>
	</ul>
</li>
<li class="<?php echo checkIfActive('batchgoods,batchgoods1,kakayun')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-cloud-upload sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">上架助手</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("batchgoods") ?>" href="./batchgoods.php">
		批量对接同系统
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("batchgoods1") ?>" href="./batchgoods1.php">
		批量对接多系统
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("kakayun") ?>" href="./kakayun.php">
		批量对接卡卡云
	</a>
</li>
	</ul>
</li>
<br>
<li>
		<span class="sidebar-nav-mini-huise">&nbsp;&nbsp;系统功能</span>
</li>
<li class="<?php echo checkIfActive('site,mail,pay,template,template2,upimg,upbgimg,clean,cleanbom,defend,proxy,mailtest,epay,captcha,fenzhan,oauth,dwz,appCreate,account,ips')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-cog sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">系统设置</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("site") ?>" href="./set.php?mod=site">
		网站信息配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("fenzhan") ?>" href="./set.php?mod=fenzhan">
		分站相关配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("pay,epay") ?>" href="./set.php?mod=pay">
		支付接口配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("mail") ?>" href="./set.php?mod=mail">
		邮箱与提醒配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("template,template2") ?>" href="./set.php?mod=template">
		首页模板设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("oauth") ?>" href="./set.php?mod=oauth">
		快捷登录配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("captcha") ?>" href="./set.php?mod=captcha">
		验证与IP配置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("upimg,upbgimg") ?>" href="./set.php?mod=upimg">
		Logo与背景设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("clean") ?>" href="./clean.php">
		系统数据清理
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("update") ?>" href="./update.php">
		检查版本更新
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("dwz") ?>" href="./set.php?mod=dwz">
		防红接口设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("appCreate") ?>" href="./appCreate.php">
		APP在线生成
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('account')?>" href="./account.php">
		员工管理
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('ips')?>" href="./ips.php">
		IP黑名单
	</a>
</li>
	</ul>
</li>

<li class="<?php echo checkIfActive('seckill,choujiang,invite,qiandao,invitelog,zsrwzq,fxsz,faquan,shopaudit,zsqtpz01,zsqtpz02')?>">
	<a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-cogs sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">附加功能</span></a>
	<ul>
<li>
	<a class="<?php echo checkIfActive("zsqtpz01.php?mod=sitee") ?>" href="zsqtpz01.php?mod=sitee">
		网站其他配置①
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("zsqtpz02.php?mod=sitee") ?>" href="zsqtpz02.php?mod=sitee">
		网站其他配置②
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("seckill") ?>" href="./seckill.php">
		限时秒杀设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("choujiang") ?>" href="./choujiang.php">
		在线抽奖设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("qiandao") ?>" href="./set.php?mod=qiandao">
		每日签到设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("invite,invitelog") ?>" href="./set.php?mod=invite">
		推广链接设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("zsrwzq/?mod=sitee") ?>" href="./zsrwzq.php?mod=sitee">
		任务赚钱设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("fxsz") ?>" href="./fxsz.php">
		分销设置
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive("faquan") ?>" href="./faquan.php">
		发圈素材
	</a>
</li>
<li>
	<a class="<?php echo checkIfActive('shopaudit')?>" href="./shopaudit.php">
		项目投稿
	</a>
</li>
	</ul>
</li>
<li class="<?php echo checkIfActive('newscategory,newsbanner,newsnav,newslist') ?>">
    <a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-bank sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">金融管理</span></a>
    <ul>
        <li>
            <a class="<?php echo checkIfActive('newscategory') ?>" href="./newscategory.php" target="main">分类管理</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('newsbanner') ?>" href="./newsbanner.php" target="main">Banner管理</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('newsnav') ?>" href="./newsnav.php" target="main">导航管理</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('newslist') ?>" href="./newslist.php" target="main">内容管理</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('finance') ?>" href="./finance.php" target="main">财务统计</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('finance_detail') ?>" href="./finance_detail.php" target="main">收支明细</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('withdraw') ?>" href="./withdraw.php" target="main">提现管理</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('refund') ?>" href="./refund.php" target="main">退款管理</a>
        </li>
        <li>
            <a class="<?php echo checkIfActive('reconciliation') ?>" href="./reconciliation.php" target="main">对账管理</a>
        </li>
    </ul>
</li>

                        </ul>
                    </div>
                </div>
                <div id="sidebar-extra-info" class="sidebar-content sidebar-nav-mini-hide">
<div class="progress progress-mini push-bit">
<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;background-color: #777777;"></div>
</div>
<div class="text-center">
<small><span id="year-copy">2024</span> © <a href="#"><?php echo $conf['sitename']?></a></small>
</div>
</div>
            </div>
            <div id="main-container">
                <header class="navbar navbar-inverse navbar-fixed-top">
 
<ul class="nav navbar-nav-custom">
 
<li>
<a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
<i class="fa fa-ellipsis-v fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
<i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>菜单
</a>
</li>
 <li>
<a href="./payorder.php" onclick="javascript:history.go(-1);">
<i class="fa fa-money fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
<i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>支付明细
</a>
</li>
<li>
<a href="./record.php" onclick="javascript:history.go(-1);">
<i class="fa fa-database fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
<i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>收支明细
</a>
</li>
<li>
<a href="javascript:void(0)" onclick="javascript:history.go(-1);">
<i class="fa fa-reply fa-fw animation-fadeInRight"></i> 返回
</a>
</li>
</ul>
 
 
<ul class="nav navbar-nav-custom pull-right">
<li>
<a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar-alt');this.blur();">
<i class="fa fa-wrench sidebar-nav-icon"></i>
</a>
</li>
<li class="dropdown">
<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
<img src="<?php echo ($conf['kfqq'])?'//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$conf['kfqq'].'&src_uin='.$conf['kfqq'].'&fid='.$conf['kfqq'].'&spec=100&url_enc=0&referer=bu_interface&term_type=PC':'../assets/img/user.png'?>" alt="avatar">
</a>
<ul class="dropdown-menu dropdown-menu-right">
<li class="dropdown-header text-center">
<strong>管理员用户</strong>
</li>
<li>
<a href="set.php?mod=bind">
<i class="fa fa-qrcode fa-fw pull-right"></i>
扫码登录
</a>
</li>
</li>
<li>
<a href="set.php?mod=account">
<i class="fa fa-pencil-square fa-fw pull-right"></i>
密码修改
</a>
</li>
<li>
<a href="../">
<i class="fa fa-home fa-fw pull-right"></i>
网站首页
</a>
</li>
<li class="divider">
</li>
<li>
<li>
<a href="login.php?logout">
<i class="fa fa-power-off fa-fw pull-right"></i>
退出登录
</a>
</li>
</ul>
</li>
</ul>
</header>
<div id="page-content">
    <div id="myDiv"></div>
    <div class="main pjaxmain">
        <div class="content-header">
            <div class="row">
                <div class="col-xs-12">
                    <div class="header-section">
                        <h1><?php echo $title ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
<?php /* } */ ?>
