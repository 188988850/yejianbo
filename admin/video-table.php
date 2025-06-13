<?php
/**
 * 短剧列表
**/
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('shop', 1);

$pid = intval($_GET['pid']);
$videolist=$DB->getRow("SELECT * FROM `pre_videolist` WHERE id='$pid' LIMIT 1");
if(isset($_GET['kw'])){
	$kw = trim(daddslashes($_GET['kw']));
	$sql="pid = $pid AND B.num = $kw";
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_video WHERE{$sql}");
	$con='包含 <b>'.$kw.'</b> 的共有 <b>'.$numrows.'</b> 个剧集';
	$link='&kw='.$kw;
}elseif(isset($_GET['id'])){
	$id = intval($_GET['id']);
	$numrows=$DB->getColumn("SELECT count(*) from pre_video where id='$id'");
	$sql=" id='$id'";
	$con='剧集列表';
	$link='&id='.$id;
}else{
    $numrows=$DB->getColumn("SELECT count(*) from pre_video where pid='$pid'");
	$sql=" pid=$pid";
	$con=$videolist['name'].'共有 <b>'.$numrows.'</b> 个剧集';
	
	
}
?>
	  <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>短剧名称</th><th>剧集数</th><th>销售价格</th><th>普及版价格</th><th>专业版价格</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=isset($_GET['num'])?intval($_GET['num']):30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_video WHERE{$sql} ORDER BY num ASC LIMIT $offset,$pagesize");

while($res = $rs->fetch())
{
echo '<tr><td>'.$res['id'].'</td><td>'.$videolist['name'].'</td><td>'.$res['num'].'</td><td>￥<font color="red">'.$res['price'].'</font>元</td><td>￥<font color="red">'.$res['cost'].'</font>元</td><td>￥<font color="red">'.$res['cost2'].'</font>元</td><td><a href="./video.php?my=edit&pid='.$pid.'&id='.$res['id'].'" class="btn btn-info btn-xs">编辑</a>&nbsp;<span class="btn btn-xs btn-danger" onclick="delvideo('.$res['id'].')">删除</span></td></tr>
';
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
?>
<script>
$("#blocktitle").html('<?php echo $con?>');
</script>