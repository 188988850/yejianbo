<?php
/**
 * 卡密列表
**/
include("../includes/common.php");
$title='批量添加短剧';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

?>
<style>
td{overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width:360px;}
</style>
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
<div class="modal" align="left" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索卡密</h4>
      </div>
      <div class="modal-body">
      <form action="duanju1.php" method="GET">
<input type="hidden" name="tid" value="<?php echo @$_GET['tid']?>"><br/>
<input type="text" class="form-control" name="kw" placeholder="请输入卡号或密码"><br/>
<input type="submit" class="btn btn-primary btn-block" value="搜索"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php

adminpermission('faka', 1);

$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择商品分类</option>';
while($res = $rs->fetch()){
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}

$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add'){
if(isset($_GET['tid'])){
	$tid=intval($_GET['tid']);
	$row=$DB->getRow("select cid,name from pre_tools where tid='$tid' limit 1");
	$shopname = '<option value="'.$tid.'">'.$row['name'].'</option>';
	$cid = $row['cid'];
}else{
	$cid = 0;
}
?>
<div class="block">
<div class="block-title"><h3 class="panel-title">批量添加短剧</h3></div>
<div class="">
<form action="./duanju1.php?my=add_submit" method="POST" onsubmit="return checkAdd()">
<input type="hidden" name="backurl" value="<?php echo $_SERVER['HTTP_REFERER']?>"/>

<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			短剧
		</span>
		<textarea class="form-control" id="kms" name="kms" rows="8" placeholder="一行一个短剧"></textarea>
	</div>
</div>
<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			分隔符
		</span>
		<input type="text" name="split" value="" class="form-control" placeholder="可自定义短剧之间的分隔符，默认为空格"/>
	</div>
</div>
<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon"><label><input id="is_check_repeat" name="is_check_repeat" type="checkbox" value="1">是否检查重复</label></span>
	</div>
</div>
<div class="form-group">
	<button type="submit" class="btn btn-primary btn-block">确认提交</button>
	<button type="reset" class="btn btn-default btn-block">重新填写</button>
</div>
</form>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
注意：短剧格式：名称+空格+链接，一行一个，如：哈哈 www.cc<br/>

</div>
</div>
<a href="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'fakalist.php';?>" class="btn btn-default btn-block">>>返回短剧列表</a>
<?php
}

elseif($my=='add_submit')
{
if(!checkRefererHost())exit();
$tid=intval($_POST['tid']);
$kms=$_POST['kms'];
$split=$_POST['split'];
$is_check_repeat=$_POST['is_check_repeat'];
if($kms==NULL){
	showmsg('请确保各项不能为空！',3);
} else {
	$kms = str_replace(array("\r\n", "\r", "\n"), "[br]", $kms);
	$match=explode("[br]",$kms);
	$c=0;
	foreach($match as $val)
	{	
		if(empty($split)){
			$km_arr=explode(' ',$val);
		}else{
			$km_arr=explode($split,$val);
		}
		$km=trim(daddslashes($km_arr[0]));
		$pw=trim(daddslashes($km_arr[1]));
		if($km=='')continue;
		if($is_check_repeat==1){
			if($DB->getRow("select * from pre_duanju where km='$km' limit 1"))continue;
		}
		$sql=$DB->exec("INSERT INTO `pre_duanju` (`tid`,`name`,`input`,`addtime`) VALUES ('".$tid."','".$km."','".$pw."',NOW())");
		if($sql)$c++;
		else showmsg('添加短剧失败！'.$DB->error());
	}
	showmsg('        <div class="block-white" style="padding:0 10px">
            <div class="form-group form-group-transparent" style="background: #f2f2f2; padding:2px 0px;padding-left: 10px;margin: 0 -10px;">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        成功添加<b>'.$c.'</b>个短剧
                        
                    </div>
                    <div style="color:#696969" class="form-control form-control-left"></div>
                </div>
            </div>
        </div>
        <div class="block-white" style="padding:0 10px">
            <div class="form-group form-group-transparent" style="background: #f2f2f2; padding:2px 0px;padding-left: 10px;margin: 0 -10px;">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        
                        <a href="duanju.php"><< 返回短剧列表</a>
                    </div>
                    <div style="color:#696969" class="form-control form-control-left"></div>
                </div>
            </div>
        </div>',1);
}
}

elseif($my=='del'){
if(!checkRefererHost())exit;
$id=intval($_GET['id']);
$sql=$DB->exec("DELETE FROM pre_faka WHERE kid='$id'");
exit("<script language='javascript'>history.go(-1);</script>");
}

elseif($my=='qk'){//清空卡密
if(!checkRefererHost())exit;
$tid=intval($_GET['tid']);
echo '<div class="block">
<div class="block-title"><h3 class="panel-title">清空短剧</h3></div>
<div class="box">
您确认要清空该商品的所有卡密吗？清空后无法恢复！<br><a href="./duanju1.php?my=qk2&tid='.$tid.'">确认</a> | <a href="javascript:history.back();">返回</a></div></div>';
}
elseif($my=='qk2'){//清空卡密结果
if(!checkRefererHost())exit;
$tid=intval($_GET['tid']);
echo '<div class="block">
<div class="block-title"><h3 class="panel-title">清空短剧</h3></div>
<div class="box">';
if($DB->exec("DELETE FROM pre_faka WHERE tid='$tid'")!==false){
echo '<div class="box">清空成功.</div>';
}else{
echo'<div class="box">清空失败.</div>';
}
echo '<hr/><a href="./duanju1.php?tid='.$tid.'">>>返回卡密列表</a></div></div>';
}
elseif($my=='qkuse'){//清空已使用卡密
if(!checkRefererHost())exit;
$tid=intval($_GET['tid']);
echo '<div class="block">
<div class="block-title"><h3 class="panel-title">清空卡密</h3></div>
<div class="box">
您确认要清空所有卡密吗？清空后无法恢复！<br><a href="./duanju1.php?my=qkuse2&tid='.$tid.'">确认</a> | <a href="javascript:history.back();">返回</a></div></div>';
}
elseif($my=='qkuse2'){//清空已使用卡密结果
if(!checkRefererHost())exit;
$tid=intval($_GET['tid']);
echo '<div class="block">
<div class="block-title"><h3 class="panel-title">清空卡密</h3></div>
<div class="box">';
if($DB->exec("DELETE FROM pre_faka WHERE tid='$tid' and orderid!=0")!==false){
echo '<div class="box">清空成功.</div>';
}else{
echo'<div class="box">清空失败.</div>';
}
echo '<hr/><a href="./duanju1.php?tid='.$tid.'">>>返回短剧列表</a></div></div>';
}
elseif($my=='del2')
{
if(!checkRefererHost())exit;
$checkbox=$_POST['checkbox'];
$i=0;
foreach($checkbox as $kid){
	$DB->exec("DELETE FROM pre_faka WHERE kid='$kid' limit 1");
	$i++;
}
exit("<script language='javascript'>alert('成功删除{$i}个短剧');history.go(-1);</script>");
}
else
{



if(isset($_GET['kw'])) {
	$tid=intval($_GET['tid']);
	$sql=" `tid`='{$tid}' and (`km`='{$_GET['kw']}' or `pw`='{$_GET['kw']}')";
	$link='&tid='.$tid.'&kw='.$_GET['kw'];
}elseif(isset($_GET['kid'])) {
	$sql=" `kid`='{$_GET['kid']}'";
	$link='&kid='.$_GET['kid'];
}elseif(isset($_GET['orderid'])) {
	$sql=" `orderid`='{$_GET['orderid']}'";
	$link='&orderid='.$_GET['orderid'];
}elseif(isset($_GET['tid'])){
	$tid=intval($_GET['tid']);
	$row=$DB->getRow("select * from pre_tools where tid='$tid' limit 1");
	if(!$row)showmsg('商品不存在',3);
	$sql=" `tid`='{$tid}'";
	$link='&tid='.$tid;
}else{
	showmsg('商品不存在',3);
}

$numrows=$DB->getColumn("SELECT count(*) from pre_faka WHERE{$sql}");
?>
<div class="modal" align="left" id="output" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">导出未使用的卡密</h4>
      </div>
      <div class="modal-body">
      <form action="download.php" method="GET">
<input type="hidden" name="act" value="kms">
<input type="hidden" name="tid" value="<?php echo $tid?>">
<input type="hidden" name="use" value="0">
<div class="form-group">
	<div class="input-group">
		<input type="number" class="form-control" name="num" placeholder="请输入要导出的数量">
		<span class="input-group-btn">
			<select name="isuse" class="form-control" style="width:140px">
				<option value="0">不改为已使用</option>
				<option value="1">同时改为已使用</option>
			</select>
		</span>
		
	</div>
</div>
<input type="submit" class="btn btn-primary btn-block" value="导出"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="block">
	<div class="block-title">
		<h2><?php echo $row['name']?> - 短剧列表</h2>
	</div>
	<div class="">
	<a href="duanju1.php?my=add&tid=<?php echo $tid?>" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;加卡</a>
  <a href="duanju1.php?my=qk&tid=<?php echo $tid?>" class="btn btn-danger">清空</a>
  <a href="duanju1.php?my=qkuse&tid=<?php echo $tid?>" class="btn btn-danger">清空已使用</a>
  <div class="btn-group">
  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    导出 <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="download.php?act=kms<?php echo $link?>&use=0">未使用</a></li>
    <li><a href="download.php?act=kms<?php echo $link?>&use=1">已使用</a></li>
    <li><a href="download.php?act=kms<?php echo $link?>">全部</a></li>
	<li><a href="#" data-toggle="modal" data-target="#output" id="output">指定数量</a></li>
  </ul>
</div>
  <a href="#" data-toggle="modal" data-target="#search" id="search" class="btn btn-primary">搜索</a>
  </div>
	<form name="form1" method="post" action="duanju1.php?my=del2">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>卡号</th><th>密码</th><th>状态</th><th>添加时间</th><th>使用时间</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_faka WHERE{$sql} order by kid desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
if($res['usetime']==null) {
	$isuse='<font color="green">未使用</font>';
} else {
	$isuse='<font color="red">已使用</font>(<a href="./list.php?id='.$res['orderid'].'" target="_blank">'.$res['orderid'].'</a>)';
}
echo '<tr><td onclick="showkms(this)"><input type="checkbox" name="checkbox[]" id="list1" value="'.$res['kid'].'" onClick="unselectall1()"><b>'.$res['km'].'</b></td><td>'.$res['pw'].'</td><td>'.$isuse.'</td><td>'.$res['addtime'].'</td><td>'.$res['usetime'].'</td><td><a href="./duanju1.php?my=del&id='.$res['kid'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此卡密吗？\');">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
<input name="chkAll1" type="checkbox" id="chkAll1" onClick="this.value=check1(this.form.list1)" value="checkbox">&nbsp;全选&nbsp;
<input type="submit" name="Submit" value="删除选中">
</div>
</form>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="duanju1.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="duanju1.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="duanju1.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="duanju1.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="duanju1.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="duanju1.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}
?>
    </div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="//lib.baomitu.com/layer/2.3/layer.js"></script>
<script src="assets/js/duanju1.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>