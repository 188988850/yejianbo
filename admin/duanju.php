<?php
/**
 * 短剧管理
**/
include("../includes/common.php");
$title='短剧管理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");


?>
    <div class="col-md-12 center-block" style="float: none;">
<?php
adminpermission('shop', 1);

$rs=$DB->query("SELECT * FROM pre_price order by id asc");
$priceselect='<option value="0">不使用加价模板</option>';
$price_class[0]='不加价';
while($res = $rs->fetch()){
	$kind = $res['kind']==1?'元':'倍';
	$priceselect.='<option value="'.$res['id'].'" kind="'.$res['kind'].'" p_2="'.$res['p_2'].'" p_1="'.$res['p_1'].'" p_0="'.$res['p_0'].'" >'.$res['name'].'('.$res['p_2'].$kind.'|'.$res['p_1'].$kind.'|'.$res['p_0'].$kind.')</option>';
	$price_class[$res['id']]=$res['name'];
}
$_SESSION['priceselect']=$priceselect;
$_SESSION['price_class']=$price_class;

$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='qk2')
{
$sql="TRUNCATE TABLE `pre_tools`";
if($DB->exec($sql)!==false)
	exit("<script language='javascript'>alert('清空成功！');window.location.href='shoplist.php';</script>");
else
	exit("<script language='javascript'>alert('清空失败！".$DB->error()."');history.go(-1);</script>");
}
else
{

$cid = isset($_GET['cid'])?intval($_GET['cid']):0;
?>
<div class="block">
<div class="block-title clearfix">
<h2 id="blocktitle"></h2>
<span class="pull-right"><select id="pagesize" class="form-control" title="每页显示"><option value="30">30</option><option value="50">50</option><option value="60">60</option><option value="80">80</option><option value="100">100</option></select><span>
</span></span>
</div>
  <form onsubmit="return searchItem()" method="GET" class="form-inline">
  <a href="./duanju1.php?my=add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;添加短剧</a>&nbsp;
  <div class="form-group">
    <input type="text" class="form-control" name="kw" placeholder="请输入短剧名称">
  </div>
  <button type="submit" class="btn btn-success">搜索</button>&nbsp;<div class="btn-group">
 
 
</div>&nbsp;
  <a href="javascript:listTable('start')" class="btn btn-default" title="刷新短剧列表"><i class="fa fa-refresh"></i></a>
</form>

<div id="listTable"></div>
<?php }?>
    </div>
<?php if(!isset($_GET['cid']))echo '<font color="grey">提示：查看单个分类的短剧列表可进行商品排序操作';?>
  </div>
      
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="assets/js/duanju.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>