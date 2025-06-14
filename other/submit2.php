<?php
require 'incc.php';
@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>正在为您跳转到支付页面，请稍候...</title>
    <style type="text/css">
body{margin:0;padding:0}
p{position:absolute;left:50%;top:50%;height:35px;margin:-35px 0 0 -160px;padding:20px;font:bold 16px/30px "宋体",Arial;background:#f9fafc url(./assets/img/loading.gif) no-repeat 20px 20px;text-indent:40px;border:1px solid #c5d0dc}
#waiting{font-family:Arial}
    </style>
</head>
<body>
<?php

// 检查是否已经存在设备ID的Cookie
if (!isset($_COOKIE['device_id'])) {
    // 生成唯一的设备ID
    $deviceId = uniqid();
    
    // 设置设备ID的Cookie，有效期为30天
    setcookie('device_id', $deviceId, time() + (30 * 24 * 60 * 60), '/');
   
} else {
    $deviceId = $_COOKIE['device_id'];
   
}
$id = $_GET['id'];
$year = date('Y');
$randomNumber = rand(1000, 9999); // 生成 1000 到 9999 之间的随机数
$id = $_GET['id'];
 $year = date('Y'); 
$trade_no = $year. $id.$randomNumber. $cookiesid; 
  $cookiesid = $userrow['zid'];
$timestamp = date('YmdHis');
 $rowS=$DB->getRow("SELECT * FROM pre_sscc WHERE id=:id LIMIT 1", [':id'=>$id]);
 $money=$rowS['money'];
 $name=$rowS['name'];
 
$sql = "INSERT INTO shua_pay (addtime, zid, tid, trade_no, money, status, name, sbid) VALUES ('$timestamp', '$cookiesid', '$id', '$trade_no', '$money', 0, '$name',  '$deviceId')";
$DB->query($sql);
$DB->query($sql);
                $DB->query($sql);
                
$type=isset($_GET['type'])?$_GET['type']:exit('No type!');
$orderid=$trade_no;


// 检查订单号是否符合要求（支持英文字母和数字）
if (!preg_match('/^[A-Za-z0-9]+$/', $orderid)) {
    exit('订单号不符合要求!');
}

$row=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no=:trade_no LIMIT 1", [':trade_no'=>$orderid]);
if(!$row['trade_no'])exit('该订单号不存在，请返回来源地重新发起请求！');
if($row['money']=='0' || !preg_match('/^[0-9.]+$/', $row['money']))exit('订单金额不合法');
if($row['status'] >= 1) {
    echo '<script>alert("订单已支付完成，点击确认查看二维码"); window.location.href = "../?mod=dingddu&id=' . $id . '";</script>';
    exit();
}

if($type=='alipay'&&$conf['alipay_api']==5 || $type=='qqpay'&&$conf['qqpay_api']==5 || $type=='wxpay'&&$conf['wxpay_api']==5){ //码支付
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>'codepay', ':trade_no'=>$orderid]);
	echo "<script>window.location.href='./codepay.php?type={$type}&trade_no={$orderid}';</script>";

}elseif($type=='alipay'&&$conf['alipay_api']==2 || $type=='qqpay'&&$conf['qqpay_api']==2 || $type=='wxpay'&&$conf['wxpay_api']==2 || $type=='qqpay'&&$conf['qqpay_api']==8 || $type=='wxpay'&&$conf['wxpay_api']==8 || $type=='wxpay'&&$conf['wxpay_api']==9){ //易支付
	$pay_config = get_pay_api($type);
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>$pay_config['channel'], ':trade_no'=>$orderid]);
	require_once(SYSTEM_ROOT."epay/epay.config.php");
	require_once(SYSTEM_ROOT."epay/epay_submit.class.php");
	$parameter = array(
		"pid" => trim($alipay_config['partner']),
		"type" => $type,
		"notify_url"	=> $siteurl.'epay_notify.php',
		"return_url"	=> $siteurl.'epay_return2.php',
		"out_trade_no"	=> $orderid,
		//发起商品名称
		"name"	=> '自助购物',
		"money"	=> $row['money'],
		"sitename"	=> $conf['sitename']
	);
	//建立请求
	$alipaySubmit = new AlipaySubmit($alipay_config);
	if(is_https() && substr($alipay_config['apiurl'],0,7)=='http://'){
		$jump_url = $alipaySubmit->buildRequestUrl($parameter);
		echo "<script>window.location.replace('{$jump_url}');</script>";
	}else{
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'POST', "正在跳转");
		echo $html_text;
	}

}





elseif($type=='alipay' && $conf['alipay_api']==7){ //卡易信
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
		include(SYSTEM_ROOT.'alipay/wxopen.php');
		exit;
	}
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>'kayixin', ':trade_no'=>$orderid]);
	require_once(SYSTEM_ROOT."epay/alipay.config.php");
	require_once(SYSTEM_ROOT."epay/alipay_submit.class.php");

	if(checkmobile()==true){
		$alipay_service = "alipay.wap";
	}else{
		$alipay_service = "alipay.pc";
	}
	$parameter = array(
		"service" => $alipay_service,
		"partner" => trim($alipay_config['partner']),
		"notify_url"	=> $siteurl.'alipay2_notify.php',
		"return_url"	=> $siteurl.'alipay2_return.php',
		"website_url"	=> $_SERVER['HTTP_HOST'],
		"out_trade_no"	=> $orderid,
		"subject"	=> $row['name'],
		"body"	=> $row['name'],
		"total_fee"	=> $row['money'],
		"_input_charset"	=> strtolower('utf-8')
	);
	$alipaySubmit = new AlipaySubmit($alipay_config);
	$html_text = $alipaySubmit->buildRequestForm($parameter, "POST", "正在跳转");
	echo $html_text;

}elseif($type=='alipay' && $conf['alipay_api']==3){ //当面付
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>'alipay', ':trade_no'=>$orderid]);
	echo "<script>window.location.href='./alipay.php?trade_no={$orderid}';</script>";

}elseif($type=='alipay' && $conf['alipay_api']==1){ //支付宝
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
		include(SYSTEM_ROOT.'alipay/wxopen.php');
		exit;
	}
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>'alipay', ':trade_no'=>$orderid]);
	$ordername = !empty($conf['ordername'])?ordername_replace($conf['ordername'],$row['name'],$trade_no):$row['name'];

	if(checkmobile()==true){
		require_once(SYSTEM_ROOT."alipay/model/builder/AlipayTradeWapPayContentBuilder.php");
		require_once(SYSTEM_ROOT."alipay/AlipayTradeService.php");

		//构造参数
		$payRequestBuilder = new AlipayTradeWapPayContentBuilder();
		$payRequestBuilder->setSubject($ordername);
		$payRequestBuilder->setTotalAmount($row['money']);
		$payRequestBuilder->setOutTradeNo($orderid);

		$aop = new AlipayTradeService($config);
		echo $aop->wapPay($payRequestBuilder);
	}else{
		require_once(SYSTEM_ROOT."alipay/model/builder/AlipayTradePagePayContentBuilder.php");
		require_once(SYSTEM_ROOT."alipay/AlipayTradeService.php");

		//构造参数
		$payRequestBuilder = new AlipayTradePagePayContentBuilder();
		$payRequestBuilder->setSubject($ordername);
		$payRequestBuilder->setTotalAmount($row['money']);
		$payRequestBuilder->setOutTradeNo($orderid);

		$aop = new AlipayTradeService($config);
		echo $aop->pagePay($payRequestBuilder);
	}
}elseif($type=='wxpay' && ($conf['wxpay_api']==1 || $conf['wxpay_api']==3)){ //微信
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>'wxpay', ':trade_no'=>$orderid]);
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
		echo "<script>window.location.replace('./wxjspay.php?trade_no={$orderid}&d=1');</script>";
	}elseif(checkmobile()==true){
		echo "<script>window.location.replace('./wxwappay.php?trade_no={$orderid}');</script>";
	}else{
		echo "<script>window.location.replace('./wxpay.php?trade_no={$orderid}');</script>";
	}

    
    
    
}elseif($type=='qqpay' && $conf['qqpay_api']==1){ //QQ
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>'qqpay', ':trade_no'=>$orderid]);
	if(checkmobile()==true){
		echo "<script>window.location.replace('./qqwappay.php?trade_no={$orderid}');</script>";
	}else{
		echo "<script>window.location.replace('./qqpay.php?trade_no={$orderid}');</script>";
	}
}elseif($type=='xiaopay'){ //QQ
	$DB->exec("UPDATE `shua_pay` SET `type`=:type,channel=:channel WHERE `trade_no`=:trade_no", [':type'=>$type, ':channel'=>'xiaopay', ':trade_no'=>$orderid]);

	echo "<script>window.location.replace('./xiaopay.php?trade_no={$orderid}');</script>";
	
}

else{
	exit('该支付方式已关闭');
}

?>
<p>正在为您跳转到支付页面，请稍候...</p>
</body>
</html>