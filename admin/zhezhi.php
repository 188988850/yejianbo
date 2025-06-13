
<?php
/**
 * 发圈素材管理
**/
include("../includes/common.php");
$title='付费群设置';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");


?>



	<div class="block">
			<div class="block-title">
				<h3 class="panel-title">付费群订单分成配置-百分之10.填10即可</h3>
			</div>
			<div class="">
				<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">
				    
					<div class="form-group">
						<label class="col-sm-2 control-label">一级</label>
						<div class="col-sm-10"><input type="text" name="yyu" value="<?php echo $conf['yyu']; ?>" class="form-control" required /></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">二级</label>
						<div class="col-sm-10"><input type="text" name="yyu2" value="<?php echo $conf['yyu2']; ?>" class="form-control" /></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">三级</label>
						<div class="col-sm-10"><input type="text" name="yyu3" value="<?php echo $conf['yyu3']; ?>" class="form-control" /></div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary btn-block" />
						</div>
					</div>
				
				</form>
			</div>
		</div>
		<script src="<?php echo $cdnpublic ?>layer/3.1.1/layer.js"></script>
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

	function changeTemplate(template) {
		var ii = layer.load(2, {
			shade: [0.1, '#fff']
		});
		$.ajax({
			type: 'POST',
			url: 'ajax.php?act=set',
			data: {
				template: template,
				template_m: "0"
			},
			dataType: 'json',
			success: function(data) {
				layer.close(ii);
				if (data.code == 0) {
					layer.alert('更换模板成功！', {
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
	}

	function thirdloginbind(type) {
		var typename = type == 'qq' ? 'QQ' : '微信';
		layer.open({
			type: 2,
			title: '绑定' + typename + '登录',
			shadeClose: true,
			closeBtn: 2,
			scrollbar: false,
			area: ['310px', '450px'],
			content: './bind.php?type=' + type
		});
	}

	function thirdloginunbind(type) {
		var typename = type == 'qq' ? 'QQ' : '微信';
		var confirmobj = layer.confirm('确定要解绑' + typename + '吗？', {
			btn: ['确定', '取消']
		}, function() {
			$.post("ajax.php?act=thirdloginunbind", {
				type: type
			}, function(arr) {
				if (arr.code == 0) {
					layer.alert(typename + '解绑成功！', {
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
			}, 'json');
		}, function() {
			layer.close(confirmobj);
		});
	}
</script>