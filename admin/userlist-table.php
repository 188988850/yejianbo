<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('site', 1);

$orderby = "zid desc";
if(isset($_GET['zid'])){
	$sql = " zid={$_GET['zid']} and power=0";
}elseif(isset($_GET['kw'])){
	$sql = " (user='{$_GET['kw']}' or qq='{$_GET['kw']}') and power=0";
	$link = '&kw='.$_GET['kw'];
}elseif(isset($_GET['sort'])){
	$sql = " power>0";
	if($_GET['sort'] == '0')$orderby = "rmb asc";
	elseif($_GET['sort'] == '1')$orderby = "rmb desc";
	$link = '&sort='.$_GET['sort'];
}else{
	$sql = " power=0";
}
$numrows=$DB->getColumn("SELECT count(*) from shua_site where{$sql}");
?>
	  <div class="table-responsive">
        <table class="table table-striped">
            【<a href="userlist.php">查看普通用户</a>】【<a href="sitelist.php">查看分站代理</a>】
          <thead><tr><th>用户ID</th><th>上级ID</th><th>短剧VIP</th><th>用户名</th><th>余额</th><th>注册时间</th><th>最后登录时间</th><th>QQ</th><th>状态</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM shua_site WHERE{$sql} order by {$orderby} limit $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr><td><b><a href="userlist.php?zid='.$res['zid'].'" target="_blank">'.$res['zid'].'</b></td><td><b><a href="sitelist.php?zid='.$res['upzid'].'" target="_blank">'.$res['upzid'].'</b></td><td><span class="btn btn-default btn-xs">'.($res['uu']==1?'<font color=#ff0000;>已开通</font>':'<font color=#000>未开通</font>').'</span></td><td>'.$res['user'].'</td><td><a href="javascript:showRecharge('.$res['zid'].')" title="点击充值">'.$res['rmb'].'</a></td><td>'.$res['addtime'].'<br/><a href="javascript:setEndtime('.$res['zid'].')" title="点击续期">'.$res['endtime'].'</a></td><td>'.$res['lasttime'].'</td><td>'.$res['qq'].'</td><td>'.($res['status']==1?'<span class="btn btn-xs btn-success" onclick="setActive('.$res['zid'].',0)">开启</span>':'<span class="btn btn-xs btn-warning" onclick="setActive('.$res['zid'].',1)">关闭</span>').'</td><td><a href="./sitelist.php?my=add2&zid='.$res['zid'].'" class="btn btn-default btn-xs">开分站</a>&nbsp;<a href="./userlist.php?my=edit&zid='.$res['zid'].'" class="btn btn-info btn-xs">编辑</a>&nbsp;<a href="./list.php?uid='.$res['zid'].'" class="btn btn-warning btn-xs">订单</a>&nbsp;<a href="./record.php?zid='.$res['zid'].'" class="btn btn-success btn-xs" target="_blank">明细</a>&nbsp;<a href="javascript:delUser('.$res['zid'].')" class="btn btn-xs btn-danger">删除</a>&nbsp;<a href="./sso.php?zid='.$res['zid'].'" class="btn btn-default btn-xs" target="_blank">登录</a></td></tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$first.$link.'\')">首页</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$prev.$link.'\')">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$next.$link.'\')">&raquo;</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$last.$link.'\')">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';