<?php
/* * 
 * 功能：彩虹易支付页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */

require_once("./incc.php");

$out_trade_no = isset($_GET['out_trade_no'])?daddslashes($_GET['out_trade_no']):exit('error');
$srow=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no='{$out_trade_no}' LIMIT 1");
$pay_config = get_pay_api($srow['channel']);

require_once(SYSTEM_ROOT."epay/epay.config.php");
require_once(SYSTEM_ROOT."epay/epay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result && ($conf['alipay_api']==2 || $conf['qqpay_api']==2 || $conf['wxpay_api']==2 || $conf['qqpay_api']==8 || $conf['wxpay_api']==8 || $conf['wxpay_api']==9) && !empty($pay_config['pid']) && !empty($pay_config['key'])) {

	//支付宝交易号

	$trade_no = daddslashes($_GET['trade_no']);

	//交易状态
	$trade_status = $_GET['trade_status'];

	//金额
	$money = $_GET['money'];
  
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		if($srow['status']==0 && round($srow['money'],2)==round($money,2)){
			if($DB->exec("UPDATE `shua_pay` SET `status` ='1' WHERE `trade_no`='{$out_trade_no}'")){
				$DB->exec("UPDATE `shua_pay` SET `endtime` ='$date',`api_trade_no` ='$trade_no' WHERE `trade_no`='{$out_trade_no}'");
				$payorder=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no='{$out_trade_no}' LIMIT 1");
				if($payorder['name']=='自助开通站点' || $payorder['name']=='自助开通站点'){
			 processOrder2($srow);   
			}else{
		     processOrder($srow);
			} 
			
			}
			showalert('您所购买的商品已付款成功，感谢购买！',1,$out_trade_no,$srow['tid']);
		}else{
		    // 假设$out_trade_no的原始值为字符串
                 
		  
             $tc2= $srow['tid'];
		    $row = $DB->getRow("SELECT zid FROM pre_sscc WHERE id = '$tc2' LIMIT 1");
		   
		    $tc1= $row['zid'];
		     $timestamp = date('YmdHis'); 
		       $zid =$srow['zid'];
		       $name =$srow['name'];
		       $sbid =$srow['sbid'];
		      $rrm = $money *  $conf['yyu']  / 100;
		    
		    
		    
	
		 $existingTfff = $DB->getRow("SELECT * FROM shua_points WHERE orderid = '$out_trade_no' AND fufei = 1 ");
		  if (!$existingTfff) {
         
      
        
        
 $sql_updff = "UPDATE pre_sscc SET gg = gg + 1 WHERE id = $tc2 ";
                $DB->query($sql_updff);
        
        
 $sql_insert = "INSERT INTO shua_points (addtime, fufei, zid, action, bz, point, status, orderid) 
                   VALUES ('$timestamp', '1', '$zid', '消费', '在线支付购买$name', '$money', 0, '$out_trade_no')"; 
    $DB->query($sql_insert); 
    
     $sql_upffsss = "UPDATE shua_site SET rmb = rmb + $rrm WHERE zid = $tc1";
       $DB->query($sql_upffsss);
      $sql_upffs = "UPDATE shua_site SET rmbtc = rmbtc + $rrm WHERE zid = $tc1";
      fx($money,$tc1);
		 $sql_updfggg = "INSERT INTO shua_points (addtime, fufei,zid, action, bz, point, status) 
                       VALUES ('$timestamp','1', '$tc1', '提成', '有人购买你的群聊$name,获得{$rrm}元提成', '$rrm', 0)";
                       
        $DB->query($sql_updfggg);
		      $DB->query($sql_upffs);
		      $sql_delete = "DELETE FROM shua_orders WHERE tradeno = '$out_trade_no'";
          $DB->query($sql_delete);
    
    }
		     
			showalert('您所购买的商品已付款成功，感谢购买！',1,$out_trade_no,$srow['tid']);
		}
    }
    
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
}


else {
    //验证失败
	showalert('验证失败！',4,'shop');
}

function fx($money,$tc1){
    global $DB;
    $row=$DB->getRow("SELECT * FROM shua_fxbl WHERE id=1 LIMIT 1"); 	  ///取出比例
		/*第一次寻找上级*/
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$tc1");
		$userid = $userid_up['zid'];  //给值到userid  下面开始循环第二层
        for ($i=0; $i<=20; $i++){
		if($userid > 0){
		$fxbl_lv = $row["lv$i"] / 100; //循环等级取出佣金比例	
		$fxbl_money = $money*0.1 *  $fxbl_lv;
		$fxbl_money=round($fxbl_money, 2);
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid"); //查询当前用户的信息
		if($userid_up['power'] == 2){  //判断是否有上级且是否为合伙人
		changeUserMoney($userid, $fxbl_money ,true,'提成', '你的团队有人卖出群聊,获得'.$fxbl_money.'元提成');	
		}
		$userid = $userid_up['upzid'];
		if(!$userid){
		 break;   
		}
    }

  }
}
?>