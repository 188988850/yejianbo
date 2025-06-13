<?php

/**
 * 网站其他配置
 **/
include("../includes/common.php");
$title = '网站其他配置②';
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
<div class="block-title"><h3 class="panel-title">网站其他配置②</h3><ul class="nav nav-tabs">
  <li class="active"><a href="#fenzhan_1001" data-toggle="tab" aria-expanded="true">官方社群信息</a></li>
  <li><a href="#fenzhan_1002" data-toggle="tab" aria-expanded="true">分站设置相关</a></li>
  <li><a href="#fenzhan_1003" data-toggle="tab" aria-expanded="true">COS活码配置</a></li>
  <li><a href="#fenzhan_1004" data-toggle="tab" aria-expanded="true">待添加</a></li>
</ul></div>
<div class="">
<div id="myTabContent" class="tab-content">

<!--fenzhan_1001开始-->
<div class="tab-pane fade active in" id="fenzhan_1001">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">

					<div class="form-group">
						<label class="col-sm-2 control-label">官方社群说明自定义文字</label>
						<div class="col-sm-10"><textarea name="gfsq_wzsm" style="width:100%;height:100px" /><?php echo $conf['gfsq_wzsm']; ?></textarea><font color="green">html代码，随便在发布个新通知里编辑好然后复制HTML代码即可</font></div>
					</div>
					<hr>

					<div class="form-group">
						<label class="col-sm-2 control-label">是否开启QQ1群显示</label>
						<div class="col-sm-2"><select class="form-control" name="gfsq_qqmc01_car" default="<?php echo $conf['gfsq_qqmc01_car']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">QQ1群自定义名称</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqmc01" value="<?php echo $conf['gfsq_qqmc01']; ?>" class="form-control" /><font color="green">可填：ＱＱ通知1群(已满) 或 ＱＱ通知1群(未满) 或 其他自定义信息</font></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">QQ1群号</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqq01" value="<?php echo $conf['gfsq_qqq01']; ?>" class="form-control" /><font color="green">没有可填：暂未开放 或 其他自定义信息</font></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">QQ1群加群链接</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqqlj01" value="<?php echo $conf['gfsq_qqqlj01']; ?>" class="form-control" /><font color="green">不填可留空</font></div>
					</div>
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">是否开启QQ2群显示</label>
						<div class="col-sm-2"><select class="form-control" name="gfsq_qqmc02_car" default="<?php echo $conf['gfsq_qqmc02_car']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">QQ2群自定义名称</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqmc02" value="<?php echo $conf['gfsq_qqmc02']; ?>" class="form-control" /><font color="green">可填：ＱＱ通知2群(已满) 或 ＱＱ通知2群(未满) 或 其他自定义信息</font></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">QQ2群号</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqq02" value="<?php echo $conf['gfsq_qqq02']; ?>" class="form-control" /><font color="green">没有可填：暂未开放 或 其它自定义信息</font></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">QQ2群加群链接</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqqlj02" value="<?php echo $conf['gfsq_qqqlj02']; ?>" class="form-control" /><font color="green">不填可留空</font></div>
					</div>
					<hr>

					<div class="form-group">
						<label class="col-sm-2 control-label">是否开启QQ交流群显示</label>
						<div class="col-sm-2"><select class="form-control" name="gfsq_qqq03_car" default="<?php echo $conf['gfsq_qqq03_car']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">QQ交流群号</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqq03" value="<?php echo $conf['gfsq_qqq03']; ?>" class="form-control" /><font color="green">没有可填：暂未开放 或 其它自定义信息</font></div>
					</div>
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">是否开启WX交流群显示</label>
						<div class="col-sm-2"><select class="form-control" name="gfsq_qqq04_car" default="<?php echo $conf['gfsq_qqq04_car']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">WX交流群号</label>
						<div class="col-sm-10"><input type="text" name="gfsq_qqq04" value="<?php echo $conf['gfsq_qqq04']; ?>" class="form-control" /><font color="green">没有可填：暂未开放 或 其它自定义信息</font></div>
					</div>
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">是否开启客服QQ号1显示</label>
						<div class="col-sm-2"><select class="form-control" name="gfsq_kfqq01_car" default="<?php echo $conf['gfsq_kfqq01_car']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">客服QQ号1</label>
						<div class="col-sm-10"><input type="text" name="gfsq_kfqq01" value="<?php echo $conf['gfsq_kfqq01']; ?>" class="form-control" /><font color="green">没有可填：暂未开放 或 其它自定义信息</font></div>
					</div>
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">是否开启客服QQ号2显示</label>
						<div class="col-sm-2"><select class="form-control" name="gfsq_kfqq02_car" default="<?php echo $conf['gfsq_kfqq02_car']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">客服QQ号2</label>
						<div class="col-sm-10"><input type="text" name="gfsq_kfqq02" value="<?php echo $conf['gfsq_kfqq02']; ?>" class="form-control" /><font color="green">没有可填：暂未开放 或 其它自定义信息</font></div>
					</div>
					<hr>
					
					
					
					<div class="form-group">
						<label class="col-sm-2 control-label">存储文字（无用）</label>
						<div class="col-sm-10"><textarea name="wywz11" style="width:100%;height:75px" /><?php echo $conf['wywz11']; ?></textarea><font color="green"></font></div>
					</div>
					
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存/修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1001结束-->

<!--fenzhan_1002开始-->
<div class="tab-pane fade in" id="fenzhan_1002">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">


								<div class="form-group">
									<label class="col-sm-2 control-label">顶级合伙人最高价格</label>
									<div class="col-sm-9"><input type="text" name="fenzhan_costgao" value="<?php echo $conf['fenzhan_costgao']; ?>" class="form-control" />
										<pre>注意：当前设置的是顶级合伙人设置下级开通价格的最高限制，填500既是最高不能超过500价格</pre>
									</div>
								</div>


					<div class="form-group">
						<label class="col-sm-2 control-label">存储文字（无用）</label>
						<div class="col-sm-10"><textarea name="wywz12" style="width:100%;height:75px" /><?php echo $conf['wywz12']; ?></textarea><font color="green"></font></div>
					</div>
					
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存/修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1002结束-->

<!--fenzhan_1003开始-->
<div class="tab-pane fade in" id="fenzhan_1003">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">

					<div class="form-group">
						<label class="col-sm-2 control-label">备注一</label>
						<div class="col-sm-10"><input type="text" name="cosbz01" value="<?php echo $conf['cosbz01']; ?>" class="form-control"/></div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">COS活码推广链接一（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="costg01" value="<?php echo $conf['costg01']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群群聊活码推广链接一（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosqun01" value="<?php echo $conf['cosqun01']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群列表活码推广链接一（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="coslb01" value="<?php echo $conf['coslb01']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">短剧列表活码推广链接一（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosdj01" value="<?php echo $conf['cosdj01']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>

                    <hr>
                    
					<div class="form-group">
						<label class="col-sm-2 control-label">备注二</label>
						<div class="col-sm-10"><input type="text" name="cosbz02" value="<?php echo $conf['cosbz02']; ?>" class="form-control"/></div>
					</div>
                    
					<div class="form-group">
						<label class="col-sm-2 control-label">COS活码推广链接二（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="costg02" value="<?php echo $conf['costg02']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群群聊推广链接二（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosqun02" value="<?php echo $conf['cosqun02']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群列表活码推广链接二（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="coslb02" value="<?php echo $conf['coslb02']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">短剧列表活码推广链接二（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosdj02" value="<?php echo $conf['cosdj02']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>

                    <hr>
                    
					<div class="form-group">
						<label class="col-sm-2 control-label">备注三</label>
						<div class="col-sm-10"><input type="text" name="cosbz03" value="<?php echo $conf['cosbz03']; ?>" class="form-control"/></div>
					</div>
                    
					<div class="form-group">
						<label class="col-sm-2 control-label">COS活码推广链接三（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="costg03" value="<?php echo $conf['costg03']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群群聊推广链接三（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosqun03" value="<?php echo $conf['cosqun03']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群列表活码推广链接三（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="coslb03" value="<?php echo $conf['coslb03']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">短剧列表活码推广链接三（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosdj03" value="<?php echo $conf['cosdj03']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>

                    <hr>
                    
					<div class="form-group">
						<label class="col-sm-2 control-label">备注四</label>
						<div class="col-sm-10"><input type="text" name="cosbz04" value="<?php echo $conf['cosbz04']; ?>" class="form-control"/></div>
					</div>
                    
					<div class="form-group">
						<label class="col-sm-2 control-label">COS活码推广链接四（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="costg04" value="<?php echo $conf['costg04']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群群聊推广链接四（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosqun04" value="<?php echo $conf['cosqun04']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">付费群列表活码推广链接四（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="coslb04" value="<?php echo $conf['coslb04']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">短剧列表活码推广链接四（小白勿动）</label>
						<div class="col-sm-10"><input type="text" name="cosdj04" value="<?php echo $conf['cosdj04']; ?>" class="form-control"/><font color="green">如：https://1-1302784280.cos-website.ap-nanjing.myqcloud.com，最后面不要带/即可</font></div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">存储文字（无用）</label>
						<div class="col-sm-10"><textarea name="wywz13" style="width:100%;height:75px" /><?php echo $conf['wywz13']; ?></textarea><font color="green"></font></div>
					</div>

                    <hr>
                    
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存/修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1003结束-->

<!--fenzhan_1004开始-->
<div class="tab-pane fade in" id="fenzhan_1004">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">




					<div class="form-group">
						<label class="col-sm-2 control-label">存储文字（无用）</label>
						<div class="col-sm-10"><textarea name="wywz14" style="width:100%;height:75px" /><?php echo $conf['wywz14']; ?></textarea><font color="green"></font></div>
					</div>
					
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存/修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1004结束-->

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