<?php
/**
 * 秒杀商品列表
**/
include("../includes/common.php");
$title='短剧列表';
include './head.php';

if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
<link rel="stylesheet" href="https://www.jq22.com/demo/TGTool202007201551/TGTool.css">
<script src="https://www.jq22.com/demo/TGTool202007201551/TGTool.js"></script>
<div class="col-md-12 center-block" style="float: none;">
        
<?php

adminpermission('shop', 1);

$my=isset($_GET['my'])?$_GET['my']:null;
$type=$DB->getAll("select * from pre_videotype");

if($my=='add')
{
?>

<div class="block">
<div class="block-title"><h3 class="panel-title">添加短剧</h3></div>
<div class="">
  <input type="file" onchange="tximg()" style="display: none;" id="tx">  
  <form action="./videolist.php?my=add_submit" method="post" class="form" role="form">
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			短剧名称
		</span>
		<input type="text" id="name" name="name" value="" class="form-control" placeholder="输入短剧名称" required/>
	</div>
  </div>
  <div class="form-group">
      <div class="input-group">
        <span class="input-group-addon">短剧分类</span>
            <select  id="type" data-rule="required" class="form-control selectpicker" name="type">
                <?php foreach ($type as $k=>$v){?>
                    <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                <?php }?>
            </select>
        </div>    
    </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			短剧简介
		</span>
		<input type="text" id="desc" name="desc" value="" class="form-control" placeholder="输入短剧简介" required/>
	</div>
  </div>
  <div class="form-group">
     <label>图片:</label><br>
     <input type="text" class="form-control" id="img" name="img" value="" required="">
     <button class="btn btn-default" onclick="$('#tx').click();" style="float:right;margin-top:-35px;">上传图片</button>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			网盘销售价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="price" name="price" value="0" class="form-control" placeholder="输入销售价格" required/>
		</div>
		<span class="input-group-addon">
			网盘普及版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="cost" name="cost" value="0" class="form-control" placeholder="输入普及版价格" required/>
		</div>
		<span class="input-group-addon">
			网盘专业版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="cost2" name="cost2" value="0" class="form-control" placeholder="输入专业版价格" required/>
		</div>     
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			整部销售价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="bfprice" name="bfprice" value="0" class="form-control" placeholder="输入销售价格" required/>
		</div>
		<span class="input-group-addon">
			整部普及版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="bfcost" name="bfcost" value="0" class="form-control" placeholder="输入普及版价格" required/>
		</div>
		<span class="input-group-addon">
			整部专业版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="bfcost2" name="bfcost2" value="0" class="form-control" placeholder="输入专业版价格" required/>
		</div>     
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			网盘地址
		</span>
		<input type="text" id="download_url" name="download_url" value="" class="form-control" placeholder="输入网盘地址" required/>
	</div>
  </div>
   <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			是否热门
		</span>
		<input type="radio" name="is_hot" value="1" checked>是
		<input type="radio" name="is_hot" value="0" >否
	</div>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			排序
		</span>
		<input type="number" id="sort" name="sort" value="1" class="form-control" required/>
	</div>
  </div>
	<div class="form-group">
	  <input type="submit" name="submit" value="添加" class="btn btn-primary btn-block"/>
	</div>
  </form>
  <br/><a href="./videolist.php">>>返回短剧列表</a>
</div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<?php
}
elseif($my=='edit')
{
$id=$_GET['id'];
$row=$DB->getRow("select * from pre_videolist where id='$id' limit 1");
?>
<div class="block">
<div class="block-title"><h3 class="panel-title">修改短剧</h3></div>
<div class="">
  <input type="file" onchange="tximg1()" style="display: none;" id="tx1">  
  <form action="./videolist.php?my=edit_submit&id=<?php echo $id; ?>" method="post" class="form" role="form">
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			短剧名称
		</span>
		<input type="text" id="name" value="<?php echo $row['name']; ?>" class="form-control" disabled/>
	</div>
  </div>
  <div class="form-group">
      <div class="input-group">
        <span class="input-group-addon">短剧分类</span>
            <select  id="type" data-rule="required" class="form-control selectpicker" name="type">
                <?php foreach ($type as $k=>$v){?>
                    <option value="<?php echo $v['id']?>" <?php if($v['id']==$row['type']){ echo 'selected';}?>><?php echo $v['name']?></option>
                <?php }?>
            </select>
        </div>    
    </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			短剧简介
		</span>
		<input type="text" id="desc" name="desc" value="<?php echo $row['desc']; ?>" class="form-control" placeholder="输入短剧简介" required/>
	</div>
  </div>
  <div class="form-group">
     <label>图片:</label><br>
     <input type="text" class="form-control" id="img1" name="img" value="<?php echo $row['img']; ?>" required="">
     <button class="btn btn-default" onclick="$('#tx1').click();" style="float:right;margin-top:-35px;">上传图片</button>
  </div>
  
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			网盘销售价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="price" name="price" value="<?php echo $row['price']; ?>" class="form-control" placeholder="输入销售价格" required/>
		</div>
		<span class="input-group-addon">
			网盘普及版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="cost" name="cost" value="<?php echo $row['cost']; ?>" class="form-control" placeholder="输入普及版价格" required/>
		</div>
		<span class="input-group-addon">
			网盘专业版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="cost2" name="cost2" value="<?php echo $row['cost2']; ?>" class="form-control" placeholder="输入专业版价格" required/>
		</div>     
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			整部销售价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="bfprice" name="bfprice" value="<?php echo $row['bfprice']; ?>" class="form-control" placeholder="输入销售价格" required/>
		</div>
		<span class="input-group-addon">
			整部普及版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="bfcost" name="bfcost" value="<?php echo $row['bfcost']; ?>" class="form-control" placeholder="输入普及版价格" required/>
		</div>
		<span class="input-group-addon">
			整部专业版价格
		</span>
		<div class="col-xs-12 col-sm-6">
		     <input type="text" id="bfcost2" name="bfcost2" value="<?php echo $row['bfcost2']; ?>" class="form-control" placeholder="输入专业版价格" required/>
		</div>     
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			网盘地址
		</span>
		<input type="text" id="download_url" name="download_url" value="<?php echo $row['download_url']; ?>" class="form-control" placeholder="输入网盘地址" required/>
	</div>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			是否热门
		</span>
		<input type="radio" name="is_hot" value="1" <?php if($row['is_hot']==1){echo 'checked';} ?>>是
		<input type="radio" name="is_hot" value="0" <?php if($row['is_hot']==0){echo 'checked';}?> >否
	</div>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			排序
		</span>
		<input type="number" id="sort" name="sort" value="<?php echo $row['sort']; ?>" class="form-control" required/>
	</div>
  </div>
	<div class="form-group">
	  <input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/>
	</div>
  </form>
  <br/><a href="./videolist.php">>>返回短剧列表</a>
</div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<?php
}
elseif($my=='add_submit')
{
$name=$_POST['name'];
$ty=intval($_POST['type']);
$price=$_POST['price'];
$cost=$_POST['cost'];
$cost2=$_POST['cost2'];
$bfprice=$_POST['bfprice'];
$bfcost=$_POST['bfcost'];
$bfcost2=$_POST['bfcost2'];
$desc=$_POST['desc'];
$img=$_POST['img'];
$download_url=$_POST['download_url'];
$is_hot=intval($_POST['is_hot']);
$sort = intval($_POST['sort']);
$addtime = date('Y-m-d H:i:s');

$sql="insert into `pre_videolist` (`name`,`type`,`price`,`cost`,`cost2`,`bfprice`,`bfcost`,`bfcost2`,`desc`,`img`,`download_url`,`sort`,`is_hot`,`addtime`) values ('".$name."','".$ty."','".$price."','".$cost."','".$cost2."','".$bfprice."','".$bfcost."','".$bfcost2."','".$desc."','".$img."','".$download_url."','".$sort."','".$is_hot."','".$addtime."')";
if($DB->exec($sql)!==false){
    showmsg('添加短剧成功！<br/><br/><a href="./videolist.php">>>返回短剧列表</a>',1);
}else
    showmsg('添加短剧商品失败！'.$DB->error(),4);
}
elseif($my=='edit_submit')
{
$id=intval($_GET['id']);
$rows=$DB->getRow("select * from pre_videolist where id='$id' limit 1");
if(!$rows)
    showmsg('当前记录不存在！',3);

$price=$_POST['price'];
$ty=intval($_POST['type']);
$cost=$_POST['cost'];
$cost2=$_POST['cost2'];
$bfprice=$_POST['bfprice'];
$bfcost=$_POST['bfcost'];
$bfcost2=$_POST['bfcost2'];
$desc=$_POST['desc'];
$img=$_POST['img'];
$download_url=$_POST['download_url'];
$is_hot=intval($_POST['is_hot']);
$sort = intval($_POST['sort']);
$addtime = date('Y-m-d H:i:s');

if($DB->exec("UPDATE `pre_videolist` SET `price`='".$price."',`type`='".$ty."',`cost`='".$cost."',`cost2`='".$cost2."',`bfprice`='".$bfprice."',`bfcost`='".$bfcost."',`bfcost2`='".$bfcost2."',`desc`='".$desc."',`img`='".$img."',`download_url`='".$download_url."',`sort`='".$sort."',`is_hot`='".$is_hot."',`addtime`='".$addtime."' WHERE `id`='".$id."'")!==false)
    showmsg('修改短剧成功！<br/><br/><a href="./videolist.php">>>返回短剧列表</a>',1);
else
    showmsg('修改短剧失败！'.$DB->error(),4);
}
else
{
?>
<div class="block">
<div class="block-title clearfix">
<h2 id="blocktitle"></h2>
<span class="pull-right"><select id="pagesize" class="form-control"><option value="30">30</option><option value="50">50</option><option value="60">60</option><option value="80">80</option><option value="100">100</option></select><span>
</span></span>
</div>
  <form onsubmit="return searchItem()" method="GET" class="form-inline">
  <a href="./videolist.php?my=add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;添加短剧</a>
  <div class="form-group">
    <input type="text" class="form-control" name="kw" placeholder="请输入短剧名称">
  </div>
  <button type="submit" class="btn btn-info">搜索</button>&nbsp;
  <a href="javascript:listTable('start')" class="btn btn-default" title="刷新短剧列表"><i class="fa fa-refresh"></i></a>
</form>
<div id="listTable"></div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<script src="assets/js/videolist.js?ver=<?php echo VERSION ?>"></script>
<?php }?>
<script>
   function tximg(){
        var formData = new FormData();
        formData.append('tx', $('#tx')[0].files[0]);
        $.ajax({
            url: 'fqajax.php?act=videoimg',
            type: 'POST',
            cache: false,
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false
        }).done(function(mag) {
            var tg = TGTool();
     if(mag.code==1){
        tg.error(mag.msg);
    }else if(mag.code==0){
        tg.success(mag.msg);
       // $("#shopimgdd").attr('src',mag.imgurl); 
        $("#img").val(mag.imgurl); 
    }
        }).fail(function(mag) {
      
        });
    } 
    
    function tximg1(){
        var formData = new FormData();
        formData.append('tx', $('#tx1')[0].files[0]);
        $.ajax({
            url: 'fqajax.php?act=videoimg',
            type: 'POST',
            cache: false,
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false
        }).done(function(mag) {
            var tg = TGTool();
     if(mag.code==1){
        tg.error(mag.msg);
    }else if(mag.code==0){
        tg.success(mag.msg);
       // $("#shopimgdd").attr('src',mag.imgurl); 
        $("#img1").val(mag.imgurl); 
    }
        }).fail(function(mag) {
      
        });
    } 
</script>


</body>
</html>