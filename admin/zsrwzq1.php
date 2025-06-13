<?php

/**
 * 游戏推广配置
 **/
include("../includes/common.php");
$title = '游戏推广配置';
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
<div class="block-title"><h3 class="panel-title">任务赚钱配置</h3>（<a style="color: #0000ff;" href="https://zmingcx.com/favorites/%E5%85%8D%E8%B4%B9%E5%9B%BE%E5%BA%8A/" target="_blank">图片上传</a>） 上传成功复制图片URL链接即可<ul class="nav nav-tabs">
  <li class="active"><a href="#fenzhan_1101" data-toggle="tab" aria-expanded="true">101-105号位</a></li>
  <li><a href="#fenzhan_1102" data-toggle="tab" aria-expanded="true">106-110号位</a></li>
  <li><a href="#fenzhan_1103" data-toggle="tab" aria-expanded="true">101-105号位</a></li>
  <li><a href="#fenzhan_1104" data-toggle="tab" aria-expanded="true">106-120号位</a></li>
</ul></div>
<div class="">
<div id="myTabContent" class="tab-content">

<!--fenzhan_1101开始-->
<div class="tab-pane fade active in" id="fenzhan_1101">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">
    
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_101</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_101" default="<?php echo $conf['rwkq_101']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_101" value="<?php echo $conf['rwkq_101']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_101" value="<?php echo $conf['rwkq_101']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_101" value="<?php echo $conf['rwkq_101']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_101" value="<?php echo $conf['rwtp_101']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_102（加红）</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_102" default="<?php echo $conf['rwkq_102']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_102" value="<?php echo $conf['rwkq_102']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_102" value="<?php echo $conf['rwkq_102']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_101" value="<?php echo $conf['rwkq_101']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_102" value="<?php echo $conf['rwtp_102']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_103</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_103" default="<?php echo $conf['rwkq_103']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_103" value="<?php echo $conf['rwkq_103']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_103" value="<?php echo $conf['rwkq_103']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_103" value="<?php echo $conf['rwkq_103']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_103" value="<?php echo $conf['rwtp_103']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_104</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_104" default="<?php echo $conf['rwkq_104']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_104" value="<?php echo $conf['rwkq_104']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_104" value="<?php echo $conf['rwkq_104']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_104" value="<?php echo $conf['rwkq_104']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_104" value="<?php echo $conf['rwtp_104']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_105（加红）</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_105" default="<?php echo $conf['rwkq_105']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_105" value="<?php echo $conf['rwkq_105']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_105" value="<?php echo $conf['rwkq_105']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_105" value="<?php echo $conf['rwkq_105']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_105" value="<?php echo $conf['rwtp_105']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					

	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1101结束-->

<!--fenzhan_1102开始-->
<div class="tab-pane fade in" id="fenzhan_1102">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">

					<div class="form-group">
						<label class="col-sm-2 control-label">分级_106</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_106" default="<?php echo $conf['rwkq_106']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_106" value="<?php echo $conf['rwkq_106']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_106" value="<?php echo $conf['rwkq_106']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_106" value="<?php echo $conf['rwkq_106']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_106" value="<?php echo $conf['rwtp_106']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_107</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_107" default="<?php echo $conf['rwkq_107']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_107" value="<?php echo $conf['rwkq_107']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_107" value="<?php echo $conf['rwkq_107']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_107" value="<?php echo $conf['rwkq_107']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_107" value="<?php echo $conf['rwtp_107']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_108（加红）</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_108" default="<?php echo $conf['rwkq_108']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_108" value="<?php echo $conf['rwkq_108']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_108" value="<?php echo $conf['rwkq_108']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_108" value="<?php echo $conf['rwkq_108']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_108" value="<?php echo $conf['rwtp_108']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_109</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_109" default="<?php echo $conf['rwkq_109']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_109" value="<?php echo $conf['rwkq_109']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_109" value="<?php echo $conf['rwkq_109']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_109" value="<?php echo $conf['rwkq_109']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_109" value="<?php echo $conf['rwtp_109']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_110</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_110" default="<?php echo $conf['rwkq_110']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_110" value="<?php echo $conf['rwkq_110']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_110" value="<?php echo $conf['rwkq_110']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_1010" value="<?php echo $conf['rwkq_110']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_10" value="<?php echo $conf['rwtp_110']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1102结束-->

<!--fenzhan_1103开始-->
<div class="tab-pane fade in" id="fenzhan_1103">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">

					<div class="form-group">
						<label class="col-sm-2 control-label">分级_111</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_110" default="<?php echo $conf['rwkq_110']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_111" value="<?php echo $conf['rwkq_111']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_111" value="<?php echo $conf['rwkq_111']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_111" value="<?php echo $conf['rwkq_111']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_111" value="<?php echo $conf['rwtp_111']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_112</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_112" default="<?php echo $conf['rwkq_112']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_112" value="<?php echo $conf['rwkq_112']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_112" value="<?php echo $conf['rwkq_112']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_112" value="<?php echo $conf['rwkq_112']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_12" value="<?php echo $conf['rwtp_112']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_113</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_113" default="<?php echo $conf['rwkq_113']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_113" value="<?php echo $conf['rwkq_113']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_113" value="<?php echo $conf['rwkq_113']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_113" value="<?php echo $conf['rwkq_113']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_13" value="<?php echo $conf['rwtp_113']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_114</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_114" default="<?php echo $conf['rwkq_114']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_114" value="<?php echo $conf['rwkq_114']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_114" value="<?php echo $conf['rwkq_114']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_114" value="<?php echo $conf['rwkq_114']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_14" value="<?php echo $conf['rwtp_114']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_115（加红）</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_115" default="<?php echo $conf['rwkq_115']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_115" value="<?php echo $conf['rwkq_115']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_115" value="<?php echo $conf['rwkq_115']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_115" value="<?php echo $conf['rwkq_115']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_15" value="<?php echo $conf['rwtp_115']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					

	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1103结束-->

<!--fenzhan_1104开始-->
<div class="tab-pane fade in" id="fenzhan_1104">
<form onsubmit="return saveSetting(this)" method="post" class="form-horizontal form-bordered" role="form">

					<div class="form-group">
						<label class="col-sm-2 control-label">分级_116</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_116" default="<?php echo $conf['rwkq_116']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_116" value="<?php echo $conf['rwkq_116']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_116" value="<?php echo $conf['rwkq_116']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_116" value="<?php echo $conf['rwkq_116']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_16" value="<?php echo $conf['rwtp_116']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_117</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_117" default="<?php echo $conf['rwkq_117']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_117" value="<?php echo $conf['rwkq_117']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_117" value="<?php echo $conf['rwkq_117']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_117" value="<?php echo $conf['rwkq_117']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_17" value="<?php echo $conf['rwtp_117']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_118</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_118" default="<?php echo $conf['rwkq_118']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_118" value="<?php echo $conf['rwkq_118']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_118" value="<?php echo $conf['rwkq_118']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_118" value="<?php echo $conf['rwkq_118']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_18" value="<?php echo $conf['rwtp_118']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_119</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_119" default="<?php echo $conf['rwkq_119']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_119" value="<?php echo $conf['rwkq_119']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_119" value="<?php echo $conf['rwkq_119']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_119" value="<?php echo $conf['rwkq_119']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_19" value="<?php echo $conf['rwtp_119']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">分级_1120</label>
						<div class="col-sm-10"><select class="form-control" name="rwkq_120" default="<?php echo $conf['rwkq_120']; ?>">
						<option value="0">关闭</option>
						<option value="1">开启</option>
						</select></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题</label>
						<div class="col-sm-10"><input type="text" name="rwkq_120" value="<?php echo $conf['rwkq_120']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10"><input type="text" name="rwkq_120" value="<?php echo $conf['rwkq_120']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">链接</label>
						<div class="col-sm-10"><input type="text" name="rwkq_120" value="<?php echo $conf['rwkq_120']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图片地址</label>
						<div class="col-sm-10"><input type="text" name="rwtp_20" value="<?php echo $conf['rwtp_120']; ?>" class="form-control" placeholder="没啥好介绍的，自己看着填"/></div>
					</div>
					
					
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/>
	 </div>
	</div>
  </form>
</div>
<!--fenzhan_1104结束-->

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