<?php
/**
 * 登录
**/
$verifycode = 1;//验证码开关

if(!function_exists("imagecreate") || !file_exists('code.php'))$verifycode=0;
include("../includes/common.php");
if(isset($_POST['user']) && isset($_POST['pass'])){
	if($conf['thirdlogin_closepwd']==1 && $conf['thirdlogin_open']==1){
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('已关闭密码登录，请使用快捷登录！');history.go(-1);</script>");
	}
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	$code=daddslashes($_POST['code']);
	if ($verifycode==1 && (!$code || strtolower($code) != $_SESSION['vc_code'])) {
		unset($_SESSION['vc_code']);
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('验证码错误！');history.go(-1);</script>");
	}elseif($user===$conf['admin_user'] && $pass===$conf['admin_pwd']) {
		unset($_SESSION['vc_code']);
		$session=md5($user.$pass.$password_hash);
		$token=authcode("0\t{$user}\t{$session}", 'ENCODE', SYS_KEY);
		setcookie("admin_token", $token, time() + 604800);
		saveSetting('adminlogin',$date);
		log_result('后台登录', 'IP:'.$clientip, null, 1);
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('登陆管理中心成功！');window.location.href='./';</script>");
	}else {
		$userrow=$DB->getRow("SELECT * FROM shua_account WHERE username='$user' limit 1");
		if($userrow && $user===$userrow['username'] && $pass===$userrow['password']) {
			if($userrow['active']==0){
				@header('Content-Type: text/html; charset=UTF-8');
				exit("<script language='javascript'>alert('您的账号未激活！');history.go(-1);</script>");
			}
			unset($_SESSION['vc_code']);
			$session=md5($user.$pass.$password_hash);
			$token=authcode("1\t{$userrow['id']}\t{$session}", 'ENCODE', SYS_KEY);
			setcookie("admin_token", $token, time() + 604800);
			$DB->exec("update shua_account set lasttime='$date' where id='{$userrow['id']}'");
			log_result('后台登录', 'User:'.$user.' IP:'.$clientip, null, 1);
			@header('Content-Type: text/html; charset=UTF-8');
			exit("<script language='javascript'>alert('登陆管理中心成功！');window.location.href='./';</script>");
		}
		unset($_SESSION['vc_code']);
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('用户名或密码不正确！');history.go(-1);</script>");
	}
}elseif(isset($_GET['act']) && $_GET['act']=='qrlogin'){
	if(!checkRefererHost())exit();
	if(!$_SESSION['thirdlogin_type']||!$_SESSION['thirdlogin_uin'])exit('{"code":-4,"msg":"校验失败，请重新登录"}');
	$type = $_GET['type'];
	$uin = $_SESSION['thirdlogin_uin'];
	if($islogin==1){
		adminpermission('set', 2);
		if($type == 'qq'){
			saveSetting('thirdlogin_qq', $uin);
			$typename = 'QQ';
		}else{
			saveSetting('thirdlogin_wx', $uin);
			$typename = '微信';
		}
		$CACHE->clear();
		unset($_SESSION['thirdlogin_type']);
		unset($_SESSION['thirdlogin_uin']);
		exit('{"code":1,"msg":"'.$typename.'绑定成功！","url":"reload"}');
	}else{
		if(!$conf['thirdlogin_open'])exit('{"code":-4,"msg":"未开启快捷登录"}');
		$typename = $type == 'qq' ? 'QQ' : '微信';
		if(!empty($conf['thirdlogin_qq']) && $uin == $conf['thirdlogin_qq'] || !empty($conf['thirdlogin_wx']) && $uin == $conf['thirdlogin_wx']){
			unset($_SESSION['thirdlogin_type']);
			unset($_SESSION['thirdlogin_uin']);
			$session=md5($conf['admin_user'].$conf['admin_pwd'].$password_hash);
			$token=authcode("0\t{$conf['admin_user']}\t{$session}", 'ENCODE', SYS_KEY);
			setcookie("admin_token", $token, time() + 604800);
			saveSetting('adminlogin',$date);
			log_result('后台登录', 'IP:'.$clientip, null, 1);
			exit('{"code":1,"msg":"登陆管理中心成功！","url":"./"}');
		}else{
			exit('{"code":-1,"msg":"登录失败，该QQ/微信未绑定！"}');
		}
	}
}elseif(isset($_GET['logout'])){
	if(!checkRefererHost())exit();
	setcookie("admin_token", "", time() - 604800);
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./login.php';</script>");
}elseif($islogin==1){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
$title='用户登录';
if($conf['thirdlogin_open'] == 1 && $conf['thirdlogin_closepwd'] == 1){
	$mode = 3;
}elseif($conf['thirdlogin_open'] == 1){
	$mode = 2;
}else{
	$mode = 1;
}
?>
<div id="login-container">
	<h1 class="h2 text-light text-center push-top-bottom animation-slideDown">
	<i class="fa fa-cube"></i><strong><?php echo $conf['sitename']?></strong>
	</h1>

			<div align="center" style="font-size: 18px;">管理员后台登录</div><br>
<?php if($mode==2){?>
<?php } if($mode>1){?>
<?php } if($mode==2){?>
<?php } if($mode<3){?>
		<form id="form-login" action="login.php" method="post" class="form-horizontal">
			<div class="form-group">
				<div class="col-xs-12">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
						<input type="text" id="user" name="user" class="form-control" placeholder="用户名" required>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-12">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
						<input type="password" id="pass" name="pass" class="form-control" placeholder="密码" required>
					</div>
				</div>
			</div>
			<?php if($verifycode==1){?>
			<div class="form-group">
				<div class="col-xs-12">
					<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-adjust"></span></span>
					<input type="text" id="code" name="code" class="form-control input-lg" placeholder="输入验证码" autocomplete="off" required>
					<span class="input-group-addon" style="padding: 0">
						<img id="codeimg" src="./code.php?r=<?php echo time();?>" height="43" onclick="this.src='./code.php?r='+Math.random();" title="点击更换验证码">
					</span>
					</div>
				</div>
			</div>
			<?php }?>
			<div class="form-group form-actions">
				<div class="col-xs-12">
					<button type="submit" class="btn btn-effect-ripple btn-block btn-primary"><i class="fa fa-check"></i>登录</button>
				</div>
			</div>
		</form>
<?php } if($mode==2){?>
	</div>
</div>
<?php }?>
<?php if($mode>1){?>
<script>var isbind = false;</script>
<script src="//cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script src="./assets/js/qrlogin.js"></script>
<script 
disable-devtool-auto 
url="about:blank'" 
src=" https://cdn.jsdelivr.net/npm/disable-devtool@latest">
</script>
<?php }?>
</body>
</html>