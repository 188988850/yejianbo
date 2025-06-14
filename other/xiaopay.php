<?php
require 'inc.php';

@header('Content-Type: text/html; charset=UTF-8');

$trade_no=daddslashes($_GET['trade_no']);
$row=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no='{$trade_no}' LIMIT 1");
if(!$row)exit('该订单号不存在，请返回来源地重新发起请求！');

$money=$row['money']*100;
// 创建请求builder，设置请求参数
$url="http://pay.xiaomfzs.com/wechat/parse/split/outMerchantOrder?orderId=".$trade_no."&amount=".$money."&merchantId=7430";

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-cn">
<meta name="renderer" content="webkit">
<title>微信/支付宝安全支付 - <?php echo $conf['sitename']?></title>
<link href="assets/css/alipay_pay.css?v=2" rel="stylesheet" media="screen">

</head>
<body>
<div class="body">
<h1 class="mod-title">
<span class="text">微信/支付宝安全支付</span>
</h1>
<div class="mod-ct">
<div class="order">
    <div align="center"><img style="display: inline-block; width: 70%; max-width: 70%; height: auto;" src="/assets/img/1182.png" /></div>

</div>
<div class="amount">￥<?php echo $row['money']?></div>
<div class="qr-image" id="qrcode">
</div>
<div class="open_app" style="display: none;">
    <a onclick="openAlipay()" class="btn-open-app">打开支付宝或微信继续付款</a><br/><br/><br/>
	<a onclick="checkresult()" class="btn-check">我已付款，返回查看订单</a>
</div>
<div class="detail" id="orderDetail">
<dl class="detail-ct" style="display: none;">
<dt>购买物品</dt>
<dd id="productName"><?php echo $row['name']?></dd>
<dt>商户订单号</dt>
<dd id="billId"><?php echo $row['trade_no']?></dd>
<dt>创建时间</dt>
<dd id="createTime"><?php echo $row['addtime']?></dd>
</dl>
<a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
</div>
<div class="tip">
<span class="dec dec-left"></span>
<span class="dec dec-right"></span>
<div class="ico-scan"></div>
<div class="tip-text">
<p>请使用支付宝或微信扫一扫</p>
<p>扫描二维码完成支付</p>
</div>
</div>
<div class="tip-text">
</div>
</div>
<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script src="//cdn.staticfile.org/layer/3.1.1/layer.min.js"></script>
<script>
	var code_url = '<?php echo $url?>';
	var url_scheme = 'alipays://platformapi/startapp?appId=20000067&url=' + encodeURIComponent(code_url);
    $('#qrcode').qrcode({
        text: code_url,
        width: 230,
        height: 230,
        foreground: "#000000",
        background: "#ffffff",
        typeNumber: -1
    });
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
            data: {type: "xiaopay", trade_no: "<?php echo $row['trade_no']?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
					layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.1,time: 15000});
					setTimeout(window.location.href=data.backurl, 1000);
                }else{
                    setTimeout("loadmsg()", 3000);
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
	function checkresult() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "alipay", trade_no: "<?php echo $row['trade_no']?>"},
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
                    layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.1,time: 15000});
					setTimeout(window.location.href=data.backurl, 1000);
                }else{
					layer.msg('您还未完成付款，请继续付款', {shade: 0,time: 1500});
				}
            }
        });
    }
	var isMobile = function (){
		var ua = navigator.userAgent;
		var ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
		isIphone =!ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
		isAndroid = ua.match(/(Android)\s+([\d.]+)/);
		return isIphone || isAndroid;
	}
	function openAlipay(){
		window.location.href = url_scheme;
		layer.msg('正在打开支付宝...', {shade: 0,time: 1000});
	}
	window.onload = function(){
		if(isMobile()){
			$('.open_app').show();
			window.location.href = url_scheme;
		}
		setTimeout("loadmsg()", 2000);
	}
	
 // 主动查询
    function query() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "xiaopay_notify.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {trade_no: "<?php echo $row['trade_no']?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
				// 	layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.1,time: 15000});
				// 	setTimeout(window.location.href=data.backurl, 1000);
                }else{
                    setTimeout("query()", 1000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("query()", 1000);
                } else { //异常
                    setTimeout("query()", 4000);
                }
            }
        });
    }	
setTimeout("query()", 1000);	
</script>
</body>
</html>