<?php
/**
 * 群聊管理
**/
include("../includes/common.php");
$title='付费群';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
<?php

adminpermission('fuxg', 1);

$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add')
{
?>
<div class="block">
<div class="block-title"><h3 class="panel-title">添加群聊</h3></div>
<div class="">
  <form action="./fuxg.php?my=add_submit" method="post" class="form-horizontal" role="form">
   
<div class="form-group">
	  <label class="col-sm-2 control-label">群名称</label>
	  <div class="col-sm-10"><input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">副标题</label>
	  <div class="col-sm-10"><input type="text" name="ename" value="<?php echo $row['ename']; ?>" class="form-control" placeholder=""/></div>
	</div>
	
	<div class="form-group">
	  <label class="col-sm-2 control-label">入群费用</label>
	  <div class="col-sm-10"><input type="text" name="money" value="<?php echo $row['money']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题1</label>
	  <div class="col-sm-10"><input type="text" name="ename1" value="<?php echo $row['ename1']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题2</label>
	  <div class="col-sm-10"><input type="text" name="ename2" value="<?php echo $row['ename2']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题3</label>
	  <div class="col-sm-10"><input type="text" name="ename3" value="<?php echo $row['ename3']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group" style="height: 80px;">
    <label class="col-sm-2 control-label">标题4</label>
    <div class="col-sm-10"><input type="text" name="ename4" value="1.付费后支持退款吗？----一旦付费，无论何种原因概不退款（包括被踢出群的），请谨慎选择，看好再买。群费不多不少，买个信任。做付费群，过滤人群，提升群质量。避免人多嘴杂，杜绝吃白食群众。付费入群更能让社群形成正向循环，是社群内容质量的保障。 *2.用户协议（付款即代表已读）----本平台禁止以任何形式传播电信网络诈骗、兼职、刷单、网恋交友诈骗、淫秽、色情、赌博、暴力、凶杀、恐怖、谣言等违法行为，违规者所传播的信息相关的任何法律责任由违规者自行承担，与平台无关 。用户要自己有辨别意识，未成年人应当在监护人的指导下使用本平台服务，遇到违法违规行为第一时间向管理员举报！违规者平台将报警移交给相关公安机关处理！ *3.免责声明(付款即代表已读)----用户使用本平台网络服务所存在的风险将完全由用户自己承担;因用户使用本平台网络服务而产生的一切后果也由用户自己承担，本平台对用户不承担任何责任。由于用户需求的多样化，本平台无法担保其提供的网络服务一定能满足您的要求。本平台亦无法对网络服务的及时性、安全性和准确性作出保证或承诺。" class="form-control" placeholder=""/><p> 标题4格式(1.标题----内容*2.标题----内容*3.标题----内容)</p></div>
</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题5</label>
	  <div class="col-sm-10"><input type="text" name="ename5" value="<?php echo $row['ename5']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">图片url</label>
	  <div class="col-sm-10"><input type="text" name="ename6" value="<?php echo $row['ename6']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">群聊头像</label>
	  <div class="col-sm-10"><input type="text" name="ename7" value="<?php echo $row['ename7']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">评论</label>
	  <div class="col-sm-10"><input type="text" name="ename8" value="厉害1----厉害2----厉害3----厉害4----厉害5----厉害6----厉害7----厉害8----" class="form-control" placeholder=""/><p> 评论格式(内容----内容----内容----)</p></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">微信二维码url</label>
	  <div class="col-sm-10"><input type="text" name="ename9" value="<?php echo $row['ename9']; ?>" class="form-control" placeholder=""/></div>
	</div>
		<div class="form-group">
	  <label class="col-sm-2 control-label">按键名称</label>
	  <div class="col-sm-10"><input type="text" name="ename10" value="<?php echo $row['ename10']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="发布" class="btn btn-primary btn-block"/><br/>
	 </div>
	</div>
  </form>
  <br/><a href="./fuxg.php">>>返回群聊列表</a>
</div>
</div>
<script charset="utf-8" src="../assets/kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="../assets/kindeditor/zh-CN.js"></script>
<script>
        KindEditor.ready(function(K) {
                window.editor = K.create('#editor_id', {
					resizeType : 1,
					allowUpload : false,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat','formatblock','hr', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link','unlink', 'code', '|','fullscreen','source','preview']
				});
        });
</script>
<?php
}
elseif($my=='edit')
{
$id=$_GET['id'];
$row=$DB->getRow("select * from pre_sscc where id='$id' limit 1");
?>
<div class="block">
<div class="block-title"><h3 class="panel-title">修改群聊</h3></div>
<div class="">
  <form action="./fuxg.php?my=edit_submit&id=<?php echo $id ?>" method="post" class="form-horizontal" role="form">
    <div class="form-group">
	  <label class="col-sm-2 control-label">群名称</label>
	  <div class="col-sm-10"><input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">副标题</label>
	  <div class="col-sm-10"><input type="text" name="ename" value="<?php echo $row['ename']; ?>" class="form-control" placeholder=""/></div>
	</div>
	
	<div class="form-group">
	  <label class="col-sm-2 control-label">入群费用</label>
	  <div class="col-sm-10"><input type="text" name="money" value="<?php echo $row['money']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题1</label>
	  <div class="col-sm-10"><input type="text" name="ename1" value="<?php echo $row['ename1']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题2</label>
	  <div class="col-sm-10"><input type="text" name="ename2" value="<?php echo $row['ename2']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题3</label>
	  <div class="col-sm-10"><input type="text" name="ename3" value="<?php echo $row['ename3']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group" style="height: 80px;">
    <label class="col-sm-2 control-label">标题4</label>
    <div class="col-sm-10"><input type="text" name="ename4" value="<?php echo $row['ename4'];?>" class="form-control" placeholder=""/><p> 标题4格式(1.标题----内容*2.标题----内容*3.标题----内容)</p></div>
</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">标题5</label>
	  <div class="col-sm-10"><input type="text" name="ename5" value="<?php echo $row['ename5']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">图片url</label>
	  <div class="col-sm-10"><input type="text" name="ename6" value="<?php echo $row['ename6']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">群聊头像</label>
	  <div class="col-sm-10"><input type="text" name="ename7" value="<?php echo $row['ename7']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">评论</label>
	  <div class="col-sm-10"><input type="text" name="ename8" value="<?php echo $row['ename8']; ?>" class="form-control" placeholder=""/><p> 评论格式(内容----内容----内容----)</p></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">微信二维码url</label>
	  <div class="col-sm-10"><input type="text" name="ename9" value="<?php echo $row['ename9']; ?>" class="form-control" placeholder=""/></div>
	</div>
		<div class="form-group">
	  <label class="col-sm-2 control-label">按键名称</label>
	  <div class="col-sm-10"><input type="text" name="ename10" value="<?php echo $row['ename10']; ?>" class="form-control" placeholder=""/></div>
	</div>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="发布" class="btn btn-primary btn-block"/><br/>
	 </div>
	</div>
  </form>
  <br/><a href="./fuxg.php">>>返回群聊列表</a>
</div>
</div>
<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
</script>
<script charset="utf-8" src="../assets/kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="../assets/kindeditor/zh-CN.js"></script>
<script>
        KindEditor.ready(function(K) {
                window.editor = K.create('#editor_id', {
					resizeType : 1,
					allowUpload : false,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat','formatblock','hr', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link','unlink', 'code', '|','fullscreen','source','preview']
				});
        });
</script>
<?php
}
elseif($my=='add_submit')
{
$name=daddslashes($_POST['name']);
$ename=daddslashes($_POST['ename']);

$money=daddslashes($_POST['money']);
$ename1=daddslashes($_POST['ename1']);
$ename2=daddslashes($_POST['ename2']);
$ename3=daddslashes($_POST['ename3']);
$ename4=daddslashes($_POST['ename4']);
$ename5=daddslashes($_POST['ename5']);
$ename6=daddslashes($_POST['ename6']);
$ename7=daddslashes($_POST['ename7']);
$ename8=daddslashes($_POST['ename8']);
$ename9=daddslashes($_POST['ename9']);
$ename10=daddslashes($_POST['ename10']);
if($name==NULL or $ename==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
$rows=$DB->getRow("select * from pre_sscc where type='$type' and title='$title' limit 1");
if($rows)
	showmsg('群聊标题已存在！',3);
$sql="insert into `pre_sscc` (`name`,`ename`,`money`,`ename1`,`ename2`,`ename3`,`ename4`,`ename5`,`ename6`,`ename7`,`ename8`,`ename9`,`ename10`)  values  ('".$name."','".$ename."','".$money."','".$ename1."','".$ename2."','".$ename3."','".$ename4."','".$ename5."','".$ename6."','".$ename7."','".$ename8."','".$ename9."','".$ename10."')";
if($DB->exec($sql)!==false){
	showmsg('添加群聊成功！<br/><br/><a href="./fuxg.php">>>返回群聊列表</a>',1);
}else
	showmsg('添加群聊失败！'.$DB->error(),4);
}
}
elseif($my=='edit_submit')
{
$id=$_GET['id'];
$rows=$DB->getRow("select * from pre_sscc where id='$id' limit 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
$name=daddslashes($_POST['name']);
$ename=daddslashes($_POST['ename']);

$money=daddslashes($_POST['money']);
$ename1=daddslashes($_POST['ename1']);
$ename2=daddslashes($_POST['ename2']);
$ename3=daddslashes($_POST['ename3']);
$ename4=daddslashes($_POST['ename4']);
$ename5=daddslashes($_POST['ename5']);
$ename6=daddslashes($_POST['ename6']);
$ename7=daddslashes($_POST['ename7']);
$ename8=daddslashes($_POST['ename8']);
$ename9=daddslashes($_POST['ename9']);
$ename10=daddslashes($_POST['ename10']);

if($name==NULL or $ename==NULL){
    showmsg('保存错误,请确保每项都不为空!',3);
} else {
    $update_query = "UPDATE `pre_sscc` SET `name`='$name',`ename`='$ename',`money`='$money',`ename1`='$ename1',`ename2`='$ename2',`ename3`='$ename3',`ename4`='$ename4',`ename5`='$ename5',`ename6`='$ename6',`ename7`='$ename7',`ename8`='$ename8',`ename9`='$ename9',`ename10`='$ename10' WHERE `id`='$id'";
    
    if($DB->exec($update_query)!==false) {
        showmsg('修改群聊成功！<br/><br/>返回群聊列表', 1);
    } else {
        showmsg('修改群聊失败！'.$DB->error(), 4);
    }
}
}
elseif($my=='delete')
{
$id=$_GET['id'];
$sql="DELETE FROM pre_sscc WHERE id='$id'";
if($DB->exec($sql)!==false)
	showmsg('删除成功！<br/><br/><a href="./fuxg.php">>>返回群聊列表</a>',1);
else
	showmsg('删除失败！'.$DB->error(),4);
}
else
{
if(isset($_GET['kw'])){
	$kw = trim(daddslashes($_GET['kw']));
	$sql=" title LIKE '%$kw%'";
	$numrows=$DB->getColumn("SELECT count(*) from pre_sscc where{$sql}");
	$con='包含 <b>'.$kw.'</b> 的共有 <b>'.$numrows.'</b> 个群聊';
	$link='&kw='.$kw;
}else{
$sql=' 1';
$numrows=$DB->getColumn("SELECT count(*) from pre_sscc");
$con='系统共有 <b>'.$numrows.'</b> 个群聊';
}
?>
<div class="block">
<div class="block-title clearfix">
<h2><?php echo $con?></h2>
</div>
<form action="fuxg.php" method="GET" class="form-inline">
 <a href="./fuxg.php?my=add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;添加群聊</a>


</form>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>站长ID</th><th>群聊标题</th><th>订单数</th><th>费用</th><th>发布时间</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=999;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_sscc WHERE{$sql} order by id desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['id'].'</b></td><td><b><a href="sitelist.php?zid='.$res['zid'].'" target="_blank">'.$res['zid'].'</a></b></td><td>'.$res['name'].'</td><td>'.$res['gg'].'</td><td>'.$res['money'].'</td><td>'.$res['addtime'].'</td><td><a class="btn btn-xs btn-success" href="../?mod=cce&id='.$res['id'].'" target="_blank">查看</a>&nbsp;<a href="./fuxg.php?my=edit&id='.$res['id'].'" class="btn btn-info btn-xs">编辑</a>&nbsp;<a href="./fuxg.php?my=delete&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此记录吗？\');">删除</a></td></tr>';
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
echo '<li><a href="fuxg.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="fuxg.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="fuxg.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="fuxg.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="fuxg.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="fuxg.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
<?php }?>
    </div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="assets/js/fuxg.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>