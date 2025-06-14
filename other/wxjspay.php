<?php
require 'inc.php';

/*微信公众号支付
开发步骤：https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_3
*/

@header('Content-Type: text/html; charset=UTF-8');
$trade_no=daddslashes($_GET['trade_no']);
if($conf['wxpay_api']!=1 && $conf['wxpay_api']!=3)exit('当前支付接口未开启');
$row=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no='{$trade_no}' LIMIT 1");
if(!$row)exit('该订单号不存在，请返回来源地重新发起请求！');

$ordername = !empty($conf['ordername'])?ordername_replace($conf['ordername'],$row['name'],$trade_no):$row['name'];

require_once SYSTEM_ROOT."wxpay/WxPay.Api.php";
require_once SYSTEM_ROOT."wxpay/WxPay.JsApiPay.php";
if($conf['wxpay_domain'] && $conf['wxpay_domain']!=$_SERVER['HTTP_HOST']){
	$DB->exec("UPDATE `shua_pay` SET `domain` ='{$_SERVER['HTTP_HOST']}' WHERE `trade_no`='{$trade_no}'");
	echo '<script>window.location.href=\'http://'.$conf['wxpay_domain'].'/other/wxjspay.php?trade_no='.$trade_no.'&d='.$_GET['d'].'\';</script>';
	exit;
}
//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();
if(!$openId)sysmsg('OpenId获取失败('.$tools->data['errmsg'].')');

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($ordername);
$input->SetOut_trade_no($trade_no);
$input->SetTotal_fee($row['money']*100);
$input->SetSpbill_create_ip($clientip);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetNotify_url($siteurl.'wxpay_notify.php');
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);

if($order["result_code"]=='SUCCESS'){
	$jsApiParameters = $tools->GetJsApiParameters($order);
}elseif(isset($result["err_code"])){
	sysmsg('微信支付下单失败！['.$result["err_code"].'] '.$result["err_code_des"]);
}else{
	sysmsg('微信支付下单失败！['.$result["return_code"].'] '.$result["return_msg"]);
}

if($_GET['d']==1){
	$redirect_url='data.backurl';
}else{
	$redirect_url='\'wxwap_ok.php\'';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="//cdn.staticfile.org/ionic/1.3.2/css/ionic.min.css" rel="stylesheet" />
</head>
<body>
<div class="bar bar-header bar-light" align-title="center">
	<h1 class="title">微信安全支付</h1>
</div>
<div class="has-header" style="padding: 5px;position: absolute;width: 100%;">
<div class="text-center" style="color: #a09ee5;">
<i class="icon ion-information-circled" style="font-size: 80px;"></i><br>
<span>正在跳转...</span>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/layer/3.1.1/layer.min.js"></script>
<script>
	document.body.addEventListener('touchmove', function (event) {
		event.preventDefault();
	},{ passive: false });
    //调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				if(res.err_msg == "get_brand_wcpay_request:ok" ) {
					loadmsg();
				}
				//WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
    // 订单详情
    $('#orderDetail .arrow').click(function (event) {
        if ($('#orderDetail').hasClass('detail-open')) {
            $('#orderDetail .detail-ct').slideUp(500, function () {
                $('#orderDetail').removeClass('detail-open');
            });
        } else {
            $('#orderDetail .detail-ct').slideDown(500, function () {
                $('#orderDetail').addClass('detail-open');
            });
        }
    });
    // 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?php echo $row['trade_no']?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
					layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.1,time: 15000});
					window.location.href=<?php echo $redirect_url?>;
                }else{
                    setTimeout("loadmsg()", 2000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 1000);
                } else { //异常
                    setTimeout("loadmsg()", 4000);
                }
            }
        });
    }
    window.onload = callpay();
</script>
</div>
</div>
</body>
</html>