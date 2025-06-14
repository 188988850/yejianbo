<?php
//一键安装脚本
//请事先填写好config.php
//默认用户名密码为：admin/123456
error_reporting(0);
session_start();
@header('Content-Type: text/html; charset=UTF-8');
if(file_exists('install.lock')){
	exit('您已经安装过，如需重新安装请删除 install/install.lock 文件后再安装！');
}


function clearpack() {
	$array=glob('../daishua_release_*');
	foreach($array as $dir){
		unlink($dir);
	}
	$array=glob('../daishua_update_*');
	foreach($array as $dir){
		unlink($dir);
	}
}

function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

include_once '../config.php';
if(!$dbconfig['user']||!$dbconfig['pwd']||!$dbconfig['dbname']) {
	exit('请先填写好config.php数据库并保存后再安装！');
} else {
	if(!$con=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'],$dbconfig['dbname'],$dbconfig['port'])){
		if(mysqli_connect_errno()==2002)
			exit('连接数据库失败，数据库地址填写错误！');
		elseif(mysqli_connect_errno()==1045)
			exit('连接数据库失败，数据库用户名或密码填写错误！');
		elseif(mysqli_connect_errno()==1049)
			exit('连接数据库失败，数据库名不存在！');
		else
			exit('连接数据库失败，['.mysqli_connect_errno().']'.mysqli_connect_error());
	}else{
		if(mysqli_query($con, "select * from ".$dbconfig['dbqz']."_config where 1")!=FALSE)
			exit('系统检测到你已安装过彩虹社区自助下单系统，请先清空数据库');
	}
}
date_default_timezone_set("PRC");
$date = date("Y-m-d");
$sql=file_get_contents("install.sql");
$sql=explode(';',$sql);
$sql[] = "INSERT INTO `shua_config` VALUES ('build', '".$date."')";
$sql[] = "INSERT INTO `shua_config` VALUES ('cronkey', '".rand(100000,999999)."')";
$sql[] = "INSERT INTO `shua_config` VALUES ('syskey', '".random(32)."')";
mysqli_query($con, "set sql_mode = ''");
mysqli_query($con, "set names utf8");
$t=0; $e=0; $error='';
for($i=0;$i<count($sql);$i++) {
	if (trim($sql[$i])=='')continue;
	if(mysqli_query($con, str_replace('pre_',$dbconfig['dbqz'].'_',$sql[$i]))) {
		++$t;
	} else {
		++$e;
		$error.=mysqli_error($con).'<br/>';
	}
}
if($e==0) {
	echo '安装成功！<br/>SQL成功'.$t.'句/失败'.$e.'句<br/><a href="../">>>网站首页</a>｜<a href="../admin/">>>后台管理</a>';
	@file_put_contents("install.lock",'安装锁');
	clearpack();
	if(function_exists("opcache_reset"))@opcache_reset();
} else {
	echo '安装失败<br/>SQL成功'.$t.'句/失败'.$e.'句<br/>错误信息：'.$error.'';
}
