<?php

/**
 * 空
 **/
include("../includes/common.php");
$title = '空';
include './head.php';
if ($islogin == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">

	<?php

	$mod = isset($_GET['mod']) ? $_GET['mod'] : null;


	if ($mod == 'cleanbom') {
		adminpermission('set', 1);
		$filename = ROOT . 'config.php';
		$contents = file_get_contents($filename);
		$charset[1] = substr($contents, 0, 1);
		$charset[2] = substr($contents, 1, 1);
		$charset[3] = substr($contents, 2, 1);
		if (ord($charset[1]) == 239 && ord($charset[2]) == 187) {
			$rest = substr($contents, 3);
			file_put_contents($filename, $rest);
			showmsg('找到BOM并已自动去除', 1);
		} else {
			showmsg('没有找到BOM', 2);
		}
	?>
	<?php
	} elseif ($mod == 'sitee') {
		adminpermission('set', 1);
	?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-18 center-block" style="float: none;">
<div class="block">
<div class="">
<div id="myTabContent" class="tab-content">

<!--fenzhan_1001开始-->
<div class="tab-pane fade active in" id="fenzhan_1001">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">

					<div class="form-group">
						<label class="col-sm-2 control-label">空</label>
						<div class="col-sm-2"><select class="form-control" name="kong_kong" default="<?php echo $conf['kong_kong']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存/修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1001结束-->

</div>
</div>
</div>



<?php } ?>
<script src="<?php echo $cdnpublic ?>layer/3.1.1/layer.js"></script>
<script src="//lib.baomitu.com/layer/2.3/layer.js"></script>
<script>
$("select[name='ui_bing']").change(function(){
	if($(this).val() == 0){
		$("#frame_set1").css("display","inherit");
		$("#frame_set2").css("display","none");
	}else if($(this).val() == 3){
		$("#frame_set1").css("display","none");
		$("#frame_set2").css("display","inherit");
	}else{
		$("#frame_set1").css("display","none");
		$("#frame_set2").css("display","none");
	}
});
$("select[name='faka_input']").change(function(){
	if($(this).val() == 5){
		$("#frame_set3").css("display","inherit");
	}else{
		$("#frame_set3").css("display","none");
	}
});
$('.input-colorpicker').colorpicker({format: 'hex'});
</script>
<script>
	var items = $("select[default]");
	for (i = 0; i < items.length; i++) {
		$(items[i]).val($(items[i]).attr("default") || 0);
	}
	function saveSetting(obj) {
		if ($("input[name='fenzhan_domain']").length > 0) {
			var fenzhan_domain = $("input[name='fenzhan_domain']").val();
			$("input[name='fenzhan_domain']").val(fenzhan_domain.replace("，", ","));
		}
		if ($("input[name='fenzhan_remain']").length > 0) {
			var fenzhan_remain = $("input[name='fenzhan_remain']").val();
			$("input[name='fenzhan_remain']").val(fenzhan_remain.replace("，", ","));
		}
		var ii = layer.load(2, {
			shade: [0.1, '#fff']
		});
		$.ajax({
			type: 'POST',
			url: 'ajax.php?act=set',
			data: $(obj).serialize(),
			dataType: 'json',
			success: function(data) {
				layer.close(ii);
				if (data.code == 0) {
					layer.alert('设置保存成功！', {
						icon: 1,
						closeBtn: false
					}, function() {
						window.location.reload()
					});
				} else {
					layer.alert(data.msg, {
						icon: 2
					})
				}
			},
			error: function(data) {
				layer.msg('服务器错误');
				return false;
			}
		});
		return false;
	}

</script>
</div>
</div>