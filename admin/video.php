<?php
/**
 * 秒杀商品列表
**/
include("../includes/common.php");
$pid = isset($_GET['pid'])?$_GET['pid']:'';
$videolist=$DB->getRow("SELECT * FROM `pre_videolist` WHERE id='$pid' LIMIT 1");
$title= isset($videolist['name']) ? $videolist['name'] : '短剧列表';
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


if($my=='add')
{
?>

<div class="block">
<div class="block-title"><h3 class="panel-title">添加剧集</h3></div>
<div class="">
  <form action="./video.php?pid=<?php echo $pid;?>&my=add_submit" method="post" class="form" role="form">
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			剧集数
		</span>
		<input type="number" id="number" name="number" value="1" class="form-control" placeholder="输入剧集数" required/>
	</div>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			销售价格
		</span>
		<input type="text" id="price" name="price" value="0" class="form-control" placeholder="输入销售价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			普及版价格
		</span>
		<input type="text" id="cost" name="cost" value="0" class="form-control" placeholder="输入普及版价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			专业版价格
		</span>
		<input type="text" id="cost2" name="cost2" value="0" class="form-control" placeholder="输入专业版价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			播放地址
		</span>
		<input type="text" id="url" name="url" value="" class="form-control" placeholder="输入播放地址" required/>
	</div>
  </div>
	<div class="form-group">
	  <input type="submit" name="submit" value="添加" class="btn btn-primary btn-block"/>
	</div>
  </form>
  <br/><a href="./video.php?pid=<?php echo $pid;?>">>>返回短剧列表</a>
</div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<?php
}
elseif($my=='edit')
{
$id=$_GET['id'];
$row=$DB->getRow("select * from pre_video where id='$id' limit 1");
?>
<div class="block">
<div class="block-title"><h3 class="panel-title">修改剧集</h3></div>
<div class="">
  <form action="./video.php?my=edit_submit&id=<?php echo $id; ?>" method="post" class="form" role="form">
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			剧集数
		</span>
		<input type="number" id="num" value="<?php echo $row['num']; ?>" class="form-control" disabled/>
	</div>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			销售价格
		</span>
		<input type="text" id="price" name="price" value="<?php echo $row['price']; ?>" class="form-control" placeholder="输入销售价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			普及版价格
		</span>
		<input type="text" id="cost" name="cost" value="<?php echo $row['cost']; ?>" class="form-control" placeholder="输入普及版价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			专业版价格
		</span>
		<input type="text" id="cost2" name="cost2" value="<?php echo $row['cost2']; ?>" class="form-control" placeholder="输入专业版价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			播放地址
		</span>
		<input type="text" id="url" name="url" value="<?php echo $row['url']; ?>" class="form-control" placeholder="输入播放地址" required/>
	</div>
  </div>
	<div class="form-group">
	  <input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/>
	</div>
  </form>
  <br/><a href="./video.php?pid=<?php echo $pid;?>">>>返回短剧列表</a>
</div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<?php
}
elseif($my=='price')
{
$pid=$_GET['pid'];

?>
<div class="block">
<div class="block-title"><h3 class="panel-title">批量设置价格</h3></div>
<div class="">
  <form action="./video.php?my=price_submit" method="post" class="form" role="form">
  <input type="hidden" name="pid" value="<?php echo $pid?>" class="form-control"/>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			剧集数
		</span>
		<input type="text" name="num" value="" class="form-control"/>
	</div>
	<font color="green">如要设置5到100集收费则输入 5-100</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			销售价格
		</span>
		<input type="text" id="price" name="price" value="<?php echo $row['price']; ?>" class="form-control" placeholder="输入销售价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			普及版价格
		</span>
		<input type="text" id="cost" name="cost" value="<?php echo $row['cost']; ?>" class="form-control" placeholder="输入普及版价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
  <div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			专业版价格
		</span>
		<input type="text" id="cost2" name="cost2" value="<?php echo $row['cost2']; ?>" class="form-control" placeholder="输入专业版价格" required/>
	</div>
	<font color="green">单位：元</font>
  </div>
	<div class="form-group">
	  <input type="submit" name="submit" value="设置" class="btn btn-primary btn-block"/>
	</div>
  </form>
  <br/><a href="./video.php?pid=<?php echo $pid;?>">>>返回短剧列表</a>
</div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<?php
}
elseif($my=='add_submit')
{
$pid=$pid;
$number = $_POST['number'];
$price=$_POST['price'];
$cost=$_POST['cost'];
$cost2=$_POST['cost2'];
$url=$_POST['url'];

$sql="insert into `pre_video` (`pid`,`price`,`cost`,`cost2`,`url`,`num`) values ('".$pid."','".$price."','".$cost."','".$cost2."','".$url."','".$number."')";

if($DB->exec($sql)!==false){
	showmsg('添加剧集成功！<br/><br/><a href="./video.php?pid='.$pid.'">>>返回剧集列表</a>',1);
}else
	showmsg('添加剧集商品失败！'.$DB->error(),4);

}
elseif($my=='edit_submit')
{
$id=$_GET['id'];
$rows=$DB->getRow("select * from pre_video where id='$id' limit 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
	
$price=$_POST['price'];
$cost=$_POST['cost'];
$cost2=$_POST['cost2'];
$url=$_POST['url'];


if($DB->exec("UPDATE `pre_video` SET `price`='".$price."',`cost`='".$cost."',`cost2`='".$cost2."',`url`='".$url."' WHERE `id`='".$id."'")!==false)
	showmsg('修改剧集成功！<br/><br/><a href="./video.php?pid='.$pid.'">>>返回剧集列表</a>',1);
else
	showmsg('修改剧集失败！'.$DB->error(),4);

}
elseif($my=='price_submit')
{
$pid=$_POST['pid'];
$num=$_POST['num'];	
$price=$_POST['price'];
$cost=$_POST['cost'];
$cost2=$_POST['cost2'];


    if(!empty($num)){
        $ids = '';
        //检查设置的集数格式 如果是包含-则获取
        if(strpos($num,'-')!==false){
            $episodes = explode('-', $num);
            if (is_numeric($episodes[0]) && is_numeric($episodes[1]) && $episodes[0] <= $episodes[1]) { 
                for($i=$episodes[0];$i<=$episodes[1];$i++){
                    if(empty($ids)){
                       $ids = $i; 
                    }else{
                       $ids .= ','.$i; 
                    }
                    
                }
                
            }else{
                showmsg('剧集数格式错误!', 3);  
                exit;  
            }
        }else{
           if (is_numeric($num)) {  
               $ids = $num;  
           } else {  
                showmsg('剧集数格式错误!', 3);  
                exit;  
           }   
        } 
    }

    if($DB->exec("UPDATE `pre_video` SET `price`=$price, `cost`=$cost, `cost2`=$cost2 WHERE `pid`=$pid AND `num` IN ($ids)")!==false)
	    showmsg('设置剧集价格成功！<br/><br/><a href="./video.php?pid='.$pid.'">>>返回剧集列表</a>',1);
    else
	    showmsg('设置剧集价格失败！'.$DB->error(),4);
    
   
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
  <a href="./video.php?pid=<?php echo $pid;?>&my=add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;添加剧集</a>
  <a href="./video.php?pid=<?php echo $pid;?>&my=price" class="btn btn-primary">&nbsp;批量设置价格</a>
</form>
<div id="listTable"></div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<script src="assets/js/video.js?ver=<?php echo VERSION ?>"></script>
<?php }?>
</body>
</html>