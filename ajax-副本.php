<?php
include("./includes/common.php");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;
@header('Content-Type: application/json; charset=UTF-8');
if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
	$cookiesid = $userrow['zid'];
	if($userrow['power']>0)$siterow = $userrow;
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}
if ($conf['cjmsg'] != '') {
	$cjmsg = $conf['cjmsg'];
} else {
	$cjmsg = '您今天的抽奖次数已经达到上限！';
}
switch($act){
case 'seckill_create':
	if(!$islogin2)exit('{"code":4,"msg":"未登录"}');
	$sid = intval($_POST['sid']);
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$seckillshop=$DB->getRow("SELECT * FROM pre_seckillshop WHERE id='$sid' LIMIT 1");
	if(!$seckillshop || $seckillshop['active']==0){
		exit('{"code":-2,"msg":"该秒杀商品不存在！"}');
	}
	$starttime = explode(' ',$seckillshop['starttime']);
	$endtime = explode(' ',$seckillshop['endtime']);
	if($starttime[1]>date('H:i:s')){
		exit('{"code":-2,"msg":"该秒杀商品活动还未开始，秒杀开始时间:'.$seckillshop['starttime'].'"}');
	}
	if($endtime[1]<=date('H:i:s')){
		exit('{"code":-2,"msg":"该秒杀商品活动还已结束，秒杀结束时间:'.$seckillshop['endtime'].'"}');
	}
	$value=$seckillshop['value']-$seckillshop['count'];
	if($value<='0'){
		exit('{"code":-2,"msg":"该秒杀商品活动秒杀资格已抢完！"}');
	}
	$tid = $seckillshop['tid'];
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='$tid' LIMIT 1");
	if(!$tool || $tool['active']==0){
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
	if($tool['close']==1)exit('{"code":-1,"msg":"当前商品维护中，停止下单！"}');
	$seckilllog = $DB->getRow("SELECT * FROM `pre_seckilllog` WHERE `zid`='{$userrow['zid']}' AND `tid`='$tid' AND `status`='0' LIMIT 1");
	if ($seckilllog)
	{
		$arr = array('sid'=>$sid, 'tid'=>$tid,  'seckill_id'=>$seckilllog['id'], 'price'=>$seckilllog['price'], 'num'=>$seckilllog['num']);
	} else {
		if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
			exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
		}
		if($DB->exec("INSERT INTO `pre_seckilllog` (`sid`,`tid`,`zid`,`ip`,`price`,`num`,`date`,`status`) VALUES (:sid,:tid,:zid,:ip,:price,:num,NOW(),0)", [':sid'=>$sid, ':tid'=>$tid, ':zid'=>$userrow['zid'], ':ip'=>$clientip, ':price'=>$seckillshop['price'], ':num'=>$seckillshop['num']])){
			unset($_SESSION['addsalt']);
			$id = $DB->lastInsertId();
			$DB->exec("UPDATE `pre_seckillshop` SET `count`=`count`+1 WHERE `id`=:id", [':id'=>$sid]);
			$arr = array('sid'=>$sid, 'seckill_id'=>$id, 'tid'=>$tid, 'price'=>$seckillshop['price'], 'num'=>$seckillshop['num']);
		}else{
			exit('{"code":-1,"msg":"' . $DB->error() . '"}');
		}
	}
	$result = array('code'=>0, 'msg'=>'succ', 'arr'=>$arr);
	exit(json_encode($result));
	break;
case 'seckill_buy':
	//if(!$conf['seckill_open'])exit('{"code":-1,"msg":"未开启该功能"}');
	if(!$islogin2)exit('{"code":4,"msg":"未登录"}');
	$sid = intval($_POST['sid']);
	$seckill_id = intval($_POST['seckill_id']);
	$inputvalue=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue']))));
	$inputvalue2=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue2']))));
	$inputvalue3=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue3']))));
	$inputvalue4=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue4']))));
	$inputvalue5=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue5']))));
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	
	$seckillrow = $DB->getRow("SELECT * FROM `pre_seckilllog` WHERE `id`='$seckill_id' AND `tid`='$tid' LIMIT 1");
	if($seckillrow['status']==1)exit('{"code":-1,"msg":"该秒杀资格已经使用过了！"}');
	$seckillshop=$DB->getRow("SELECT * FROM pre_seckillshop WHERE id='$sid' LIMIT 1");
	if(!$seckillshop || $seckillshop['active']==0){
		exit('{"code":-2,"msg":"该秒杀商品不存在"}');
	}
	$tid = $seckillshop['tid'];
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='$tid' LIMIT 1");
	if(!$tool || $tool['active']==0){
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
	if($tool['close']==1)exit('{"code":-1,"msg":"当前商品维护中，停止下单！"}');
	if(in_array($inputvalue,explode("|",$conf['blacklist'])))exit('{"code":-1,"msg":"你的下单账号已被拉黑，无法下单！"}');
	$inputs=explode('|',$tool['inputs']);
	if($inputs[0] && empty($inputvalue2) || $inputs[1] && empty($inputvalue3) || $inputs[2] && empty($inputvalue4) || $inputs[3] && empty($inputvalue5)){
		exit('{"code":-1,"msg":"请确保各项不能为空"}');
	}
	if(!$inputs[0] && !empty($inputvalue2) || !$inputs[1] && !empty($inputvalue3) || !$inputs[2] && !empty($inputvalue4) || !$inputs[3] && !empty($inputvalue5)){
		exit('{"code":-1,"msg":"验证失败"}');
	}
	if($tool['validate']==1 && is_numeric($inputvalue)){
		if(validate_qzone($inputvalue)==false)
			exit('{"code":-1,"msg":"你的QQ空间设置了访问权限，无法下单！"}');
	}elseif(($tool['validate']==2 || $tool['validate']==3) && is_numeric($inputvalue)){
		$services = getservices($inputvalue);
		if($services['code']!=0)exit('{"code":-1,"msg":"'.$services['msg'].'"}');
		$qqservices = ['vip'=>'QQ会员','svip'=>'超级会员','bigqqvip'=>'大会员','red'=>'红钻贵族','green'=>'绿钻贵族','sgreen'=>'绿钻豪华版','yellow'=>'黄钻贵族','syellow'=>'豪华黄钻','hollywood'=>'腾讯视频VIP','qqmsey'=>'付费音乐包','qqmstw'=>'豪华付费音乐包','weiyun'=>'微云会员','sweiyun'=>'微云超级会员'];
		if(in_array($tool['valiserv'], $services['data'])){
			if($tool['validate']==2){
				exit('{"code":-1,"msg":"您的QQ已经开通了'.$qqservices[$tool['valiserv']].'，该商品无法购买！"}');
			}else{
				$blockdj=1;
			}
		}
	}
	$input=$inputvalue.($inputvalue2?'|'.$inputvalue2:null).($inputvalue3?'|'.$inputvalue3:null).($inputvalue4?'|'.$inputvalue4:null).($inputvalue5?'|'.$inputvalue5:null);
	$trade_no=date("YmdHis").rand(111,999);
	$trade_no='seckill'.$trade_no;
	$num = 1;
	$money = $seckillshop['price'];
	if($seckillrow['num']>1){
	    $num = $seckillrow['num'];
	    $money= $seckillshop['price']*$num;
	}
	if($userrow['rmb']<$money)exit('{"code":-1,"msg":"帐户余额不足，请充值！"}');
	$sql="INSERT INTO `shua_pay` (`trade_no`,`tid`,`zid`,`type`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`blockdj`,`status`) VALUES (:trade_no, :tid, :zid, :type, :input, :num, :name, :money, :ip, :userid, NOW(), :blockdj, 1)";
			$data = [':trade_no'=>$trade_no, ':tid'=>$tid, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':type'=>'rmb', ':input'=>$input, ':num'=>$num, ':name'=>$tool['name'], ':money'=>$money, ':ip'=>$clientip, ':userid'=>$cookiesid, ':blockdj'=>$blockdj?$blockdj:0];
	if($DB->exec($sql, $data)){
		unset($_SESSION['addsalt']);
		$DB->exec("UPDATE `pre_seckilllog` SET `input`='$input',`status`='1' WHERE `id`=:id", [':id'=>$seckill_id]);
		if($money>0)changeUserMoney($userrow['zid'], $money, false, '消费', '你参加秒杀活动下单了商品【'.$tool['name'].'】，消费'.$money.'元余额');
		
		$srow['tid']=$tid;
		$srow['input']=$input;
		$srow['num']=$num;
		$srow['zid']=$siterow['zid']?$siterow['zid']:1;
		$srow['userid']=$cookiesid;
		$srow['blockdj']=$blockdj?$blockdj:0;
		$srow['trade_no']=$trade_no;
		$srow['money']=$money;
		if($orderid=processOrder($srow)){
			exit('{"code":0,"msg":"下单成功！你可以在进度查询中查看订单进度","orderid":"'.$orderid.'"}');
		}else{
			exit('{"code":-1,"msg":"下单失败！'.$DB->error().'"}');
		}
	}
	
	break;
case 'payrmb':
	if(!$islogin2)exit('{"code":-4,"msg":"您还未登录"}');
	$orderid=isset($_POST['orderid'])?daddslashes($_POST['orderid']):exit('{"code":-1,"msg":"订单号未知"}');
	$srow=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no=:orderid LIMIT 1", [':orderid'=>$orderid]);
	if(!$srow['trade_no'] || $srow['tid']==-1)exit('{"code":-1,"msg":"订单号不存在！"}');
	if($srow['money']=='0')exit('{"code":-1,"msg":"当前商品为免费商品，不需要支付"}');
	if(!preg_match('/^[0-9.]+$/', $srow['money']))exit('{"code":-1,"msg":"订单金额不合法"}');
	if($srow['status']==0){
		if($srow['money']>$userrow['rmb'])exit('{"code":-3,"msg":"您的余额不足，请充值！"}');
		if($DB->exec("UPDATE `shua_site` SET `rmb`=`rmb`-'{$srow['money']}' WHERE `zid`='{$userrow['zid']}'") && $DB->exec("UPDATE `shua_pay` SET `type`='rmb',`status`='1',`endtime`=NOW() WHERE `trade_no`='{$orderid}'")){
			$srow['type'] = 'rmb';
			$payorder=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no='{$orderid}' LIMIT 1");
			if($payorder['name']=='自助开通分站' || $payorder['name']=='自助开通站点'){
			     $orderid=processOrder2($srow);   
			}elseif(strpos($payorder['name'],'购买短剧') !== false){
			     $orderid=processOrder2($srow);   
			}else{
			     $orderid=processOrder($srow);   
			}   
 
			if($orderid){
				addPointRecord($userrow['zid'], $srow['money'], '消费', '购买 '.$srow['name'].'', $orderid);
				if(strpos($srow['name'],'购买短剧') !== false){
				    if(strpos($srow['name'],'购买短剧播放') !== false){
				       exit('{"code":3,"msg":"您所购买的短剧已付款成功，感谢购买！","orderid":"'.$orderid.'","vid":"'.$srow['tid'].'"}'); 
				    }else{
				       exit('{"code":2,"msg":"您所购买的短剧已付款成功，感谢购买！","orderid":"'.$orderid.'","vid":"'.$srow['tid'].'"}'); 
				    }
				}else{
				    exit('{"code":1,"msg":"您所购买的商品已付款成功，感谢购买！","orderid":"'.$orderid.'"}');
				}
			}else{
				addPointRecord($userrow['zid'], $srow['money'], '消费', '购买 '.$srow['name']);
				exit('{"code":-1,"msg":"下单失败！'.$DB->error().'"}');
			}
		}else{
			exit('{"code":-1,"msg":"下单失败！'.$DB->error().'"}');
		}
	}else{
		exit('{"code":-2,"msg":"当前订单已付款过，请勿重复提交"}');
	}
	break;
	case 'cidr':
	     	$cid=intval($_GET['cid']);
	     $rs=$DB->query("SELECT * FROM shua_class WHERE cidr='$cid' AND active=1");
	     $count8=$DB->getColumn("SELECT count(*) from shua_class WHERE cidr='$cid'");

	if($count8>=1){
	    	$data = array();
	while($res = $rs->fetch()){
		$data[]=array('cid'=>$res['cid'],'name'=>$res['name']);
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data,"cid"=>$cid);
	}else{
	    	$result=array("code"=>1,"msg"=>"succ","data"=>null);
	}
	exit(json_encode($result));
	    	break;
	    		case 'cidrs':
	    		    	$cid=intval($_GET['cid']);
	    		    $rs=$DB->query("SELECT * FROM shua_class WHERE cid='$cid' AND active=1");
	    		    $res = $rs->fetch();
	    		     $count8=$DB->getColumn("SELECT count(*) from shua_tools WHERE cid='$cid'");
	    		     $result=array("name"=>$res['name'],"num"=>$count8,"cid"=>$cid);
	    		     	exit(json_encode($result));
	    		    	break;
case 'captcha':
	$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);
	$data = array(
		'user_id' => $cookiesid, # 网站用户id
		'client_type' => "web", # web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
		'ip_address' => $clientip # 请在此处传输用户请求验证时所携带的IP
	);
	$status = $GtSdk->pre_process($data, 1);
	$_SESSION['gtserver'] = $status;
	echo $GtSdk->get_response_str();
break;

case 'uploadimg':
	adminpermission('shop', 2);
	if($_POST['do']=='upload'){
		$type = $_POST['type'];
		$filename = $type.'_'.md5_file($_FILES['file']['tmp_name']).'.png';
		$fileurl = 'assets/img/Product/'.$filename;
		if(copy($_FILES['file']['tmp_name'], ROOT.'assets/img/Product/'.$filename)){
			exit('{"code":0,"msg":"succ","url":"'.$fileurl.'"}');
		}else{
			exit('{"code":-1,"msg":"上传失败，请确保有本地写入权限"}');
		}
	}
	exit('{"code":-1,"msg":"null"}');
break;
case 'getcount':
	$strtotime=strtotime($conf['build']);//获取开始统计的日期的时间戳
	$now=time();//当前的时间戳
	$yxts=ceil(($now-$strtotime)/86400);//取相差值然后除于24小时(86400秒)
	if($conf['hide_tongji']==1){
		$result=array("code"=>0,"yxts"=>$yxts,"orders"=>0,"orders1"=>0,"orders2"=>0,"money"=>0,"money1"=>0,"gift"=>$gift);
		exit(json_encode($result));
	}
	if($conf['tongji_time']>0){
		$tongji_cachetime = $DB->getColumn("SELECT v FROM shua_config WHERE k='tongji_cachetime' limit 1");
		$tongji_cache = $CACHE->read('tongji');
		if($tongji_cachetime+intval($conf['tongji_time'])>=time() && $tongji_cache){
			if($conf['shoppingcart']==1){
				$cart_count = $DB->getColumn("SELECT count(*) from shua_cart WHERE userid='$cookiesid' AND status<=1");
			}
			$array = unserialize($tongji_cache);
			$result=array("code"=>0,"yxts"=>$yxts,"orders"=>$array['orders'],"orders1"=>$array['orders1'],"orders2"=>$array['orders2'],"money"=>$array['money'],"money1"=>$array['money1'],"site"=>$array['site'],"gift"=>$array['gift'],"cart_count"=>$cart_count);
			exit(json_encode($result));
		}
	}
	if($conf['gift_log']==1 && $conf['gift_open']==1){
		$gift = array();
		$list=$DB->query("SELECT a.*,(SELECT b.name FROM shua_gift AS b WHERE a.gid=b.id) AS name FROM shua_giftlog AS a WHERE status=1 ORDER BY id DESC");
		while($cjlist=$list->fetch()){
			if(!$cjlist['input'])continue;
			$gift[$cjlist['input']] = $cjlist['name'];
		}
	}
	$time =date("Y-m-d").' 00:00:01';
	$count1=$DB->getColumn("SELECT count(*)FROM shua_orders");
	$count2=$DB->getColumn("SELECT count(*) FROM shua_orders WHERE status>=1");
	$count3=$DB->getColumn("SELECT sum(money) FROM shua_pay WHERE status=1");
	$count4=round($count3, 2);
	$count5=$DB->getColumn("SELECT count(*) FROM `shua_orders` WHERE  `addtime` > '$time'");
	$count6=$DB->getColumn("SELECT sum(money) FROM `shua_pay` WHERE `addtime` > '$time' AND `status` = 1");
	$count7=round($count6, 2);
	$count8=$DB->getColumn("SELECT count(*) from shua_site");
	if($conf['tongji_time']>0){
		saveSetting('tongji_cachetime',time());
		$CACHE->save('tongji',serialize(array("orders"=>$count1,"orders1"=>$count2,"orders2"=>$count5,"money"=>$count4,"money1"=>$count7,"site"=>$count8,"gift"=>$gift)));
	}
	if($conf['shoppingcart']==1){
		$cart_count = $DB->getColumn("SELECT count(*) FROM shua_cart WHERE userid='$cookiesid' AND status<=1");
	}

	$result=array("code"=>0,"yxts"=>$yxts,"orders"=>$count1,"orders1"=>$count2,"orders2"=>$count5,"money"=>$count4,"money1"=>$count7,"site"=>$count8,"gift"=>$gift,"cart_count"=>$cart_count);
	exit(json_encode($result));
	break;
case 'getclass':
	$classhide = explode(',',$siterow['class']);
	$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 ORDER BY sort ASC");
	$data = array();
	while($res = $rs->fetch(PDO::FETCH_ASSOC)){
		if($is_fenzhan && in_array($res['cid'], $classhide))continue;
		$data[]=$res;
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
	break;
case 'gettool':
	if(isset($_POST['kw'])){
		$kw=trim(daddslashes($_POST['kw']));
		if($kw=='random'){
			$rs=$DB->query("SELECT * FROM shua_tools WHERE active=1 ORDER BY rand() LIMIT 10");
		}else{
			$rs=$DB->query("SELECT * FROM shua_tools WHERE name LIKE '%{$kw}%' AND active=1 ORDER BY sort ASC");
		}
	}elseif(isset($_GET['cid'])){
		$cid=intval($_GET['cid']);
		$rs=$DB->query("SELECT * FROM shua_tools WHERE cid='$cid' AND active=1 ORDER BY sort ASC");
		if(isset($_GET['info']) && $_GET['info']==1){
			$info=$DB->getRow("SELECT * FROM shua_class WHERE cid=$cid");
		}
	}elseif(isset($_GET['tid'])){
		$tid=intval($_GET['tid']);
		$rs=$DB->query("SELECT * FROM shua_tools WHERE tid='$tid' AND active=1");
	}else{
		exit('{"code":-1,"msg":"参数错误"}');
	}
	$data = array();
	while($res = $rs->fetch(PDO::FETCH_ASSOC)){
		if(isset($_SESSION['gift_id']) && isset($_SESSION['gift_tid']) && $_SESSION['gift_tid']==$res['tid']){
			$price=$conf["cjmoney"]?$conf["cjmoney"]:0;
		}elseif(isset($price_obj)){
			$price_obj->setToolInfo($res['tid'],$res);
			if($price_obj->getToolDel($res['tid'])==1)continue;
			$price=$price_obj->getToolPrice($res['tid']);
		}else $price=$res['price'];
		if($res['is_curl']==4){
			$isfaka = 1;
			$res['input'] = getFakaInput();
		}else{
			$isfaka = 0;
		}
		$data[]=array('tid'=>$res['tid'],'cid'=>$res['cid'],'sort'=>$res['sort'],'name'=>$res['name'],'value'=>$res['value'],'price'=>$price,'input'=>$res['input'],'inputs'=>$res['inputs'],'desc'=>$res['desc'],'alert'=>$res['alert'],'shopimg'=>$res['shopimg'],'repeat'=>$res['repeat'],'multi'=>$res['multi'],'close'=>$res['close'],'prices'=>$res['prices'],'min'=>$res['min'],'max'=>$res['max'],'sales'=>$res['sales'],'pro'=>$res['pro'],'isfaka'=>$isfaka,'stock'=>$res['stock']);
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data,"info"=>$info);
	exit(json_encode($result));
	break;
case 'gettoolnew':
	$page = $_POST['page'] ? intval(trim(daddslashes($_POST['page']))) : 1;
	$limit = $_POST['limit'] ? intval(trim(daddslashes($_POST['limit']))) : 9;
	if($limit < 1) $limit = 9;
	if($limit > 18) $limit = 18;
	$page = ($page-1)*$limit;
	$kw = trim(daddslashes($_POST['kw']));
	$cid = intval($_POST['cid']);
	$sort_type = $_POST['sort_type'] ? trim(daddslashes($_POST['sort_type'])) : 'sort';
	$sort = $_POST['sort'] ? trim(daddslashes($_POST['sort'])) : 'ASC';
	if(!$cid && $sort_type == 'sort') $sort_type = 'tid';

	$sort_type_arr = ['ASC','price','sales'];
	$sort_arr = ['DESC','ASC'];
	$orderBy = "sort ASC";
	
	
	//商品排序需要恢复原有的删除下面代码即可（共二段，当前为第一段）
	if ($conf['template_store_sort_type'] == 1) {
            $orderBy = "sort ASC";
        } else {
            $orderBy = "tid DESC";
        }
	//删除到此结束
	
	if(in_array($sort_type,$sort_type_arr) && in_array($sort,$sort_arr)){
		$orderBy = "{$sort_type} {$sort}"; 
	}
	//商品排序需要恢复原有的删除下面代码即可（共二段，当前为第二段）
    file_put_contents(dirname(__FILE__)."/log1111.php",var_export([$sort_type,$sort,$orderBy],true));
	//删除到此结束
	$where = "active=1";
	if(!empty($kw)){
		$where .= " and name LIKE '%{$kw}%'";
	}
	$ic1=0;  $ic2=0;
	if($cid){
		$where .= " and cid='$cid'";
			$data = array();	$curr_time = time();
		$rs1=$DB->query("SELECT * FROM shua_class WHERE cidr='$cid'");
			while($res1 = $rs1->fetch()){
			    $cc=$res1['cid'];
			     $icc1=$DB->getColumn("SELECT count(tid) FROM shua_tools WHERE cid='$cc'");
			     $ic1=$ic1+$icc1;
	   $erjiid=$res1['cid'];
	   $rs3=$DB->query("SELECT * FROM shua_tools WHERE cid='$erjiid' AND active=1 ORDER BY $orderBy LIMIT $page,$limit");
	
	   while($res3 = $rs3->fetch(PDO::FETCH_ASSOC)){
	      
		if(isset($_SESSION['gift_id']) && isset($_SESSION['gift_tid']) && $_SESSION['gift_tid']==$res3['tid']){
			$price=$conf["cjmoney"]?$conf["cjmoney"]:0;
		}elseif(isset($price_obj)){
			$price_obj->setToolInfo($res3['tid'],$res3);
			if($price_obj->getToolDel($res3['tid'])==1)continue;
			$price=$price_obj->getToolPrice($res3['tid']);
		}else $price=$res3['price'];

		$is_stock_err = 0;
		if($res3['is_curl']==4){
			$isfaka = 1;
			$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='{$res3['tid']}' AND orderid=0");
			if($count == 0) $is_stock_err = 1;
			$res3['input'] = getFakaInput();
		}elseif($res3['stock']!==null){
			$isfaka = 0;
			$count = $res3['stock'];
			if($count == 0) $is_stock_err = 1;
		}else{
			$isfaka = 0;
			$count = null;
		}
  
		$data[]=array('tid'=>$res3['tid'],'cid'=>$res3['cid'],'sort'=>$res3['sort'],'name'=>$res3['name'],'value'=>$res3['value'],'price'=>$price,'input'=>$res3['input'],'inputs'=>$res3['inputs'],'desc'=>$res3['desc'],'alert'=>$res3['alert'],'shopimg'=>$res3['shopimg'],'repeat'=>$res3['repeat'],'multi'=>$res3['multi'],'close'=>$res3['close'],'prices'=>$res3['prices'],'min'=>$res3['min'],'max'=>$res3['max'],'sales'=>$res3['sales'],'stock'=>$count,'isfaka'=>$isfaka,'addtime'=>strtotime($res3['addtime']),'is_stock_err'=>$is_stock_err,'active'=>$res3['active']);
         
	}
	}$jg=true;
	}else{
	    	$data = array();
	}

	$num=$DB->getColumn("SELECT count(tid) FROM shua_tools WHERE $where");
	$rs=$DB->query("SELECT * FROM shua_tools WHERE $where ORDER BY $orderBy LIMIT $page,$limit");
	
	while($res = $rs->fetch(PDO::FETCH_ASSOC)){
	    $ic2++;
		if(isset($_SESSION['gift_id']) && isset($_SESSION['gift_tid']) && $_SESSION['gift_tid']==$res['tid']){
			$price=$conf["cjmoney"]?$conf["cjmoney"]:0;
		}elseif(isset($price_obj)){
			$price_obj->setToolInfo($res['tid'],$res);
			if($price_obj->getToolDel($res['tid'])==1)continue;
			$price=$price_obj->getToolPrice($res['tid']);
		}else $price=$res['price'];
		$is_stock_err = 0;
		if($res['is_curl']==4){
			$isfaka = 1;
			$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='{$res['tid']}' AND orderid=0");
			if($count == 0) $is_stock_err = 1;
			$res['input'] = getFakaInput();
		}elseif($res['stock']!==null){
			$isfaka = 0;
			$count = $res['stock'];
			if($count == 0) $is_stock_err = 1;
		}else{
			$isfaka = 0;
			$count = null;
		}
  
		$data[]=array('tid'=>$res['tid'],'cid'=>$res['cid'],'sort'=>$res['sort'],'name'=>$res['name'],'value'=>$res['value'],'price'=>$price,'input'=>$res['input'],'inputs'=>$res['inputs'],'desc'=>$res['desc'],'alert'=>$res['alert'],'shopimg'=>$res['shopimg'],'repeat'=>$res['repeat'],'multi'=>$res['multi'],'close'=>$res['close'],'prices'=>$res['prices'],'min'=>$res['min'],'max'=>$res['max'],'sales'=>$res['sales'],'stock'=>$count,'isfaka'=>$isfaka,'addtime'=>strtotime($res['addtime']),'is_stock_err'=>$is_stock_err,'active'=>$res['active']);
         
	}
	$count8c=$DB->getColumn("SELECT count(*) from shua_tools");
	  
	$num2=$ic1+$num;
	$pages = ceil($num2/$limit);
    $stores = array();
foreach ($data as $key => $row) {
    $stores[$key] = $row['tid'];
}
array_multisort($stores, SORT_DESC, $data);


	$result=array("code"=>0,"msg"=>"succ","data"=>$data,"info"=>$info,'pages'=>$pages,'total'=>intval($num2),'demo'=>$stores,'count'=>$count8c);
	exit(json_encode($result));
	break;
case 'getleftcount':
	$tid=intval($_POST['tid']);
	$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='$tid' AND orderid=0");
	if($conf['faka_showleft']==1)$count = $count>0?'充足':'缺货';
	$result=array("code"=>0,"count"=>$count);
	exit(json_encode($result));
	break;
case 'pay':
	$method=$_GET['method'];
	$inputvalue=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue']))));
	$inputvalue2=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue2']))));
	$inputvalue3=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue3']))));
	$inputvalue4=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue4']))));
	$inputvalue5=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue5']))));
	$num=isset($_POST['num'])?intval($_POST['num']):1;
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	if($method == 'cart_edit'){
		$shop_id=intval($_POST['shop_id']);
		$cart_item = $DB->getRow("SELECT * FROM `shua_cart` WHERE `id`='$shop_id' LIMIT 1");
		if(!$cart_item)exit('{"code":-1,"msg":"商品不存在！"}');
		if($cart_item['userid']!=$cookiesid || $cart_item['status']>1)exit('{"code":-1,"msg":"商品权限校验失败"}');
		$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='{$cart_item['tid']}' LIMIT 1");
	}else{
		$tid=intval($_POST['tid']);
		$tool=$DB->getRow("SELECT A.*,B.blockpay FROM shua_tools A LEFT JOIN shua_class B ON A.cid=B.cid WHERE tid='$tid' LIMIT 1");
	}
	if($tool && $tool['active']==1){
		if($tool['close']==1)exit('{"code":-1,"msg":"当前商品维护中，停止下单！"}');
		if(($conf['forcermb']==1 || $conf['forcelogin']==1) && !$islogin2)exit('{"code":4,"msg":"您还未登录"}');
		if(!empty($tool['blockpay']) && !$islogin2){
			$blockpay = explode(',',$tool['blockpay']);
			if(in_array('alipay',$blockpay) && in_array('qqpay',$blockpay) && in_array('wxpay',$blockpay))exit('{"code":4,"msg":"当前商品需要登录后才能下单"}');
		}
		if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
			exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
		}
		$inputs=explode('|',$tool['inputs']);
		if(empty($inputvalue) || $inputs[0] && empty($inputvalue2) || $inputs[1] && empty($inputvalue3) || $inputs[2] && empty($inputvalue4) || $inputs[3] && empty($inputvalue5)){
			exit('{"code":-1,"msg":"请确保各项不能为空"}');
		}
		if(!$inputs[0] && !empty($inputvalue2) || !$inputs[1] && !empty($inputvalue3) || !$inputs[2] && !empty($inputvalue4) || !$inputs[3] && !empty($inputvalue5)){
			exit('{"code":-1,"msg":"验证失败"}');
		}
		if(in_array($inputvalue,explode("|",$conf['blacklist'])))exit('{"code":-1,"msg":"您的下单账号已被拉黑，无法下单！"}');
		if($tool['is_curl']==4){
			if(!$islogin2 && $conf['faka_input']==0 && !checkEmail($inputvalue)){
				exit('{"code":-1,"msg":"邮箱格式不正确"}');
			}
			$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='$tid' AND orderid=0");
			$nums=($tool['value']>1?$tool['value']:1)*$num;
			if($count==0)exit('{"code":-1,"msg":"该商品库存卡密不足，请联系站长加卡！"}');
			if($nums>$count)exit('{"code":-1,"msg":"您所购买的数量超过库存数量！"}');
		}
		elseif($tool['stock']!==null){
			if($tool['stock']==0)exit('{"code":-1,"msg":"该商品库存不足，请联系站长增加库存！"}');
			if($num>$tool['stock'])exit('{"code":-1,"msg":"您所购买的数量超过库存数量！"}');
		}
		elseif($tool['repeat']==0){
			$thtime=date("Y-m-d").' 00:00:00';
			$row=$DB->getRow("SELECT id,input,status,addtime FROM shua_orders WHERE tid=:tid AND input=:input ORDER BY id DESC LIMIT 1", [':tid'=>$tid, ':input'=>$inputvalue]);
			if($row['input'] && $row['status']==0)
				exit('{"code":-1,"msg":"您今天添加的'.$tool['name'].'正在排队中，请勿重复提交！"}');
			elseif($row['addtime']>$thtime)
				exit('{"code":-1,"msg":"您今天已添加过'.$tool['name'].'，请勿重复提交！"}');
		}
		if($tool['validate']==1 && is_numeric($inputvalue)){
			if(validate_qzone($inputvalue)==false)
				exit('{"code":-1,"msg":"您的QQ空间设置了访问权限，无法下单！"}');
		}elseif(($tool['validate']==2 || $tool['validate']==3) && is_numeric($inputvalue)){
			$services = getservices($inputvalue);
			if($services['code']!=0)exit('{"code":-1,"msg":"'.$services['msg'].'"}');
			$qqservices = ['vip'=>'QQ会员','svip'=>'超级会员','bigqqvip'=>'大会员','red'=>'红钻贵族','green'=>'绿钻贵族','sgreen'=>'绿钻豪华版','yellow'=>'黄钻贵族','syellow'=>'豪华黄钻','hollywood'=>'腾讯视频VIP','qqmsey'=>'付费音乐包','qqmstw'=>'豪华付费音乐包','weiyun'=>'微云会员','sweiyun'=>'微云超级会员'];
			if(in_array($tool['valiserv'], $services['data'])){
				if($tool['validate']==2){
					exit('{"code":-1,"msg":"您的QQ已经开通了'.$qqservices[$tool['valiserv']].'，该商品无法购买！"}');
				}else{
					$blockdj=1;
				}
			}
		}
		if($tool['multi']==0 || $num<1)$num = 1;
		if($tool['multi']==1 && $tool['min']>0 && $num<$tool['min'])exit('{"code":-1,"msg":"当前商品最小下单数量为'.$tool['min'].'"}');
		if($tool['multi']==1 && $tool['max']>0 && $num>$tool['max'])exit('{"code":-1,"msg":"当前商品最大下单数量为'.$tool['max'].'"}');
		if(isset($_SESSION['gift_id']) && isset($_SESSION['gift_tid']) && $_SESSION['gift_tid']==$tid){
			$gift_id = intval($_SESSION['gift_id']);
			$giftlog=$DB->getColumn("SELECT status FROM shua_giftlog WHERE id='$gift_id' LIMIT 1");
			if($giftlog==1){
				unset($_SESSION['gift_id']);
				unset($_SESSION['gift_tid']);
				exit('{"code":-1,"msg":"当前奖品已经领取过了！"}');
			}
			$price=$conf["cjmoney"]?$conf["cjmoney"]:0;
			$num=1;
		}elseif($tool['price']==0){
			$price=0;
		}elseif(isset($price_obj)){
			$price_obj->setToolInfo($tid,$tool);
			$price=$price_obj->getToolPrice($tid);
			$price=$price_obj->getFinalPrice($price, $num);
			if(!$price)exit('{"code":-1,"msg":"当前商品批发价格优惠设置不正确"}');
		}else $price=$tool['price'];

		$i=2;
		$neednum = $num;
		foreach($inputs as $inputname){
			if(strpos($inputname,'[multi]')!==false && isset(${'inputvalue'.$i}) && is_numeric(${'inputvalue'.$i})){
				$val = intval(${'inputvalue'.$i});
				if($val>0){
					$neednum = $neednum * $val;
				}
			}
			$i++;
		}

		$need=round($price*$neednum, 2);
		if($need==0 && $tid!=$_SESSION['gift_tid']){
			if($method == 'cart_add' || $method == 'cart_edit')exit('{"code":-1,"msg":"免费商品请直接点击领取"}');
			$thtime=date("Y-m-d").' 00:00:00';
			if($_SESSION['blockfree']==true || $DB->getColumn("SELECT count(*) FROM `shua_pay` WHERE `money`=0 AND `ip`='$clientip' AND `status`=1 AND `addtime`>'$thtime'")>=50){
				exit('{"code":-1,"msg":"您今天购买免费商品的次数已达上限，请明天再来！"}');
			}
			if($conf['captcha_open_free']==1 && $conf['captcha_open']==1){
				if(isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
					if(!isset($_SESSION['gtserver']))exit('{"code":-1,"msg":"验证加载失败"}');

					$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);

					$data = array(
						'user_id' => $cookiesid,
						'client_type' => "web",
						'ip_address' => $clientip
					);

					if ($_SESSION['gtserver'] == 1) {   //服务器正常
						$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
						if ($result) {
							//echo '{"status":"success"}';
						} else{
							exit('{"code":-1,"msg":"验证失败，请重新验证"}');
						}
					}else{  //服务器宕机,走failback模式
						if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
							//echo '{"status":"success"}';
						}else{
							exit('{"code":-1,"msg":"验证失败，请重新验证"}');
						}
					}
				}else{
					exit('{"code":2,"type":1,"msg":"请先完成验证"}');
				}
			}elseif($conf['captcha_open_free']==1 && $conf['captcha_open']==2){
				if(isset($_POST['token'])){
					$client = new \lib\CaptchaClient($conf['captcha_id'], $conf['captcha_key']);
					$client->setTimeOut(2);
					$response = $client->verifyToken($_POST['token']);
					if($response->result){
						/**token验证通过，继续其他流程**/
					}else{
						/**token验证失败**/
						exit('{"code":-1,"msg":"验证失败，请重新验证"}');
					}
				}else{
					exit('{"code":2,"type":2,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
				}
			}elseif($conf['captcha_open_free']==1 && $conf['captcha_open']==3){
				if(isset($_POST['token'])){
					if(vaptcha_verify($conf['captcha_id'], $conf['captcha_key'], $_POST['token'], $clientip)){
						/**token验证通过，继续其他流程**/
					}else{
						/**token验证失败**/
						exit('{"code":-1,"msg":"验证失败，请重新验证"}');
					}
				}else{
					exit('{"code":2,"type":3,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
				}
			}
		}
		//下单对接预检查
		if($need>0 && $tool['shequ']>0 && $tool['is_curl']==2 && in_array($tool['cid'],explode(",",$conf['pricejk_cid'])) && time()-$tool['uptime']>=$conf['pricejk_time']){
			$shequ=$DB->getRow("select * from shua_shequ where id='{$tool['shequ']}' limit 1");
			$allowType = explode(',',$CACHE->read('pricejk_type2'));
			if($conf['pricejk_yile']==0 && in_array($shequ['type'],$allowType) && $tool['prid']>0){
				$num_change = third_call($shequ['type'], $shequ, 'pricejk_one', [$tool]);
				if($num_change>0){
					exit('{"code":3,"msg":"当前商品价格发生变化，请刷新页面重试","change":"'.$num_change.'"}');
				}
			}else{
				$apireturn = third_call($shequ['type'], $shequ, 'pre_check', [$tool, $num]);
				if($apireturn && $apireturn['code']==-1){
					exit('{"code":3,"msg":"'.$apireturn['msg'].'"}');
				}
			}
		}

		$trade_no=date("YmdHis").rand(111,999);
		$input=$inputvalue.($inputvalue2?'|'.$inputvalue2:null).($inputvalue3?'|'.$inputvalue3:null).($inputvalue4?'|'.$inputvalue4:null).($inputvalue5?'|'.$inputvalue5:null);
		if($method == 'cart_add'){
			$sql="INSERT INTO `shua_cart` (`userid`,`zid`,`tid`,`input`,`num`,`money`,`addtime`,`blockdj`,`status`) VALUES (:userid, :zid, :tid, :input, :num, :money, NOW(), :blockdj, 0)";
			$data = [':userid'=>$cookiesid, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':tid'=>$tid, ':input'=>$input, ':num'=>$num, ':money'=>$need, ':blockdj'=>$blockdj?$blockdj:0];
			if($DB->exec($sql, $data)){
				$cart_count = $DB->getColumn("SELECT count(*) FROM shua_cart WHERE userid='$cookiesid' AND status<=1");
				exit('{"code":0,"msg":"加入购物车成功！","need":"'.$need.'","cart_count":"'.$cart_count.'"}');
			}else{
				exit('{"code":-1,"msg":"加入购物车失败！'.$DB->error().'"}');
			}
		}elseif($method == 'cart_edit'){
			$sql="UPDATE `shua_cart` SET `input`=:input,`num`=:num,`money`=:money,`status`='0' WHERE id=:id";
			$data = [':input'=>$input, ':num'=>$num, ':money'=>$need, ':id'=>$shop_id];
			if($DB->exec($sql, $data)!==false){
				exit('{"code":0,"msg":"编辑订单成功！","need":"'.$need.'"}');
			}else{
				exit('{"code":-1,"msg":"编辑订单失败！'.$DB->error().'"}');
			}
		}elseif($need==0){
			$trade_no='free'.$trade_no;
			$num = 1;
			$sql="INSERT INTO `shua_pay` (`trade_no`,`tid`,`zid`,`type`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`blockdj`,`status`) VALUES (:trade_no, :tid, :zid, :type, :input, :num, :name, :money, :ip, :userid, NOW(), :blockdj, 1)";
			$data = [':trade_no'=>$trade_no, ':tid'=>$tid, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':type'=>'free', ':input'=>$input, ':num'=>$num, ':name'=>$tool['name'], ':money'=>$need, ':ip'=>$clientip, ':userid'=>$cookiesid, ':blockdj'=>$blockdj?$blockdj:0];
			if($DB->exec($sql, $data)){
				unset($_SESSION['addsalt']);
				if(isset($_SESSION['gift_id'])){
					$DB->exec("UPDATE `shua_giftlog` SET `status`=1,`tradeno`=:tradeno,`input`=:input WHERE `id`=:id", [':tradeno'=>$trade_no, ':input'=>$inputvalue, ':id'=>$gift_id]);
					unset($_SESSION['gift_id']);
					unset($_SESSION['gift_tid']);
					$_SESSION['blockfree']=true;
				}
				$srow['tid']=$tid;
				$srow['input']=$input;
				$srow['num']=$num;
				$srow['zid']=$siterow['zid']?$siterow['zid']:1;
				$srow['userid']=$cookiesid;
				$srow['trade_no']=$trade_no;
				$srow['money']=0;
				if($orderid=processOrder($srow)){
					exit('{"code":1,"msg":"下单成功！您可以在进度查询中查看订单进度","orderid":"'.$orderid.'"}');
				}else{
					exit('{"code":-1,"msg":"下单失败！'.$DB->error().'"}');
				}
			}
		}else{
			$sql="INSERT INTO `shua_pay` (`trade_no`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`inviteid`,`addtime`,`blockdj`,`status`) VALUES (:trade_no, :tid, :zid, :input, :num, :name, :money, :ip, :userid, :inviteid, NOW(), :blockdj, 0)";
			$data = [':trade_no'=>$trade_no, ':tid'=>$tid, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':input'=>$input, ':num'=>$num, ':name'=>$tool['name'], ':money'=>$need, ':ip'=>$clientip, ':userid'=>$cookiesid, ':inviteid'=>$invite_id, ':blockdj'=>$blockdj?$blockdj:0];
			if($DB->exec($sql, $data)){
				unset($_SESSION['addsalt']);
				if(isset($_SESSION['gift_id'])){
					$DB->exec("UPDATE `shua_giftlog` SET `status`=1,`tradeno`=:tradeno,`input`=:input WHERE `id`=:id", [':tradeno'=>$trade_no, ':input'=>$inputvalue, ':id'=>$gift_id]);
					unset($_SESSION['gift_id']);
					unset($_SESSION['gift_tid']);
				}
				if($conf['forcermb']==1){$conf['alipay_api']=0;$conf['wxpay_api']=0;$conf['qqpay_api']=0;}
				if(!empty($tool['blockpay'])){
					$blockpay = explode(',',$tool['blockpay']);
					if(in_array('alipay',$blockpay))$conf['alipay_api']=0;
					if(in_array('qqpay',$blockpay))$conf['qqpay_api']=0;
					if(in_array('wxpay',$blockpay))$conf['wxpay_api']=0;
					if(in_array('rmb',$blockpay))$islogin2=0;
				}
				$result = ['code'=>0, 'msg'=>'提交订单成功！', 'trade_no'=>$trade_no, 'need'=>$need, 'pay_alipay'=>$conf['alipay_api'], 'pay_wxpay'=>$conf['wxpay_api'], 'pay_qqpay'=>$conf['qqpay_api'], 'pay_rmb'=>$islogin2, 'user_rmb'=>$userrow['rmb'], 'paymsg'=>$conf['paymsg']];
				exit(json_encode($result));
			}else{
				exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
			}
		}
	}else{
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
	break;
	case 'payvideo':

	$vid=intval($_POST['vid']);
	$id=intval(isset($_POST['id']) ? $_POST['id'] : 0);
	$goods_type=$_POST['goods_type'];
	$tool=$DB->getRow("SELECT * FROM pre_videolist WHERE id='$vid' LIMIT 1");
	
	if($tool && $tool['active']==1){
		if(($conf['forcermb']==1 || $conf['forcelogin']==1) && !$islogin2)exit('{"code":4,"msg":"您还未登录"}');
		if(!empty($tool['blockpay']) && !$islogin2){
			$blockpay = explode(',',$tool['blockpay']);
			if(in_array('alipay',$blockpay) && in_array('qqpay',$blockpay) && in_array('wxpay',$blockpay))exit('{"code":4,"msg":"当前商品需要登录后才能下单"}');
		}
		if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
			exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
		}

        $input = '';
        
		if($tool['price']==0){
			$price=0;
		}elseif(isset($price_obj)){
			$price_obj->setVideoInfo($vid,$tool);
			if($goods_type=='url'){
			    $price=$price_obj->getVideoPrice($vid);
			    $name = '购买短剧地址:';
			    
			}else{
			    $name = '购买短剧播放:';
			    $price=$price_obj->getbfVideoPrice($vid);
			    if(!empty($id)){
			       $video = $DB->getRow("SELECT * FROM pre_video WHERE id='$id' LIMIT 1"); 
			       $price_obj->setdbVideoInfo($id,$video);
                   $price = $price_obj->getdbVideoPrice($id);
			       $input = $id; 
			    }
			    
			}
			
			if(!$price)exit('{"code":-1,"msg":"当前商品批发价格优惠设置不正确"}');
		}else $price=$tool['price'];
		
		$need = round($price, 2);
		
        
        $num = 1;
		$trade_no=date("YmdHis").rand(111,999);
		$sql="INSERT INTO `shua_pay` (`trade_no`,`tid`,`vid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`inviteid`,`addtime`,`blockdj`,`status`) VALUES (:trade_no, :tid, :vid, :zid, :input, :num, :name, :money, :ip, :userid, :inviteid, NOW(), :blockdj, 0)";
		
		$data = [':trade_no'=>$trade_no, ':tid'=>-4, ':vid'=>$vid, ':zid'=>$siterow['zid']?$siterow['zid']:$userrow['zid'], ':input'=>$input, ':num'=>$num, ':name'=>$name.$tool['name'], ':money'=>$need, ':ip'=>$clientip, ':userid'=>$cookiesid, ':inviteid'=>$invite_id, ':blockdj'=>$blockdj?$blockdj:0];
			
		if($DB->exec($sql, $data)){
			unset($_SESSION['addsalt']);
			if($conf['forcermb']==1){$conf['alipay_api']=0;$conf['wxpay_api']=0;$conf['qqpay_api']=0;}
			if(!empty($tool['blockpay'])){
				$blockpay = explode(',',$tool['blockpay']);
				if(in_array('alipay',$blockpay))$conf['alipay_api']=0;
				if(in_array('qqpay',$blockpay))$conf['qqpay_api']=0;
				if(in_array('wxpay',$blockpay))$conf['wxpay_api']=0;
				if(in_array('rmb',$blockpay))$islogin2=0;
			}
			$result = ['code'=>0, 'msg'=>'提交订单成功！', 'trade_no'=>$trade_no, 'need'=>$need, 'pay_alipay'=>$conf['alipay_api'], 'pay_wxpay'=>$conf['wxpay_api'], 'pay_qqpay'=>$conf['qqpay_api'], 'pay_rmb'=>$islogin2, 'user_rmb'=>$userrow['rmb'], 'paymsg'=>$conf['paymsg']];
				exit(json_encode($result));
		}else{
			exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
		}
		
	}else{
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
	break;
	
case 'pays':
	if(!$conf['openbatchorder'])exit('{"code":-1,"msg":"未开启批量下单功能"}');
	$inputvalues=$_POST['inputvalues'];
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$tid=intval($_POST['tid']);
	$num=isset($_POST['num'])?intval($_POST['num']):1;
	$tool=$DB->getRow("SELECT A.*,B.blockpay FROM shua_tools A LEFT JOIN shua_class B ON A.cid=B.cid WHERE tid='$tid' LIMIT 1");
	if($tool && $tool['active']==1){
		if($tool['close']==1)exit('{"code":-1,"msg":"当前商品维护中，停止下单！"}');
		if(($conf['forcermb']==1 || $conf['forcelogin']==1) && !$islogin2)exit('{"code":4,"msg":"您还未登录"}');
		if(!empty($tool['blockpay']) && !$islogin2){
			$blockpay = explode(',',$tool['blockpay']);
			if(in_array('alipay',$blockpay) && in_array('qqpay',$blockpay) && in_array('wxpay',$blockpay))exit('{"code":4,"msg":"当前商品需要登录后才能下单"}');
		}
		if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
			exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
		}
		$inputvalues = str_replace(array("\r\n", "\r", "\n"), "[br]", $inputvalues);
		$match = explode("[br]",$inputvalues);
		$count=0;
		$inputs=[];
		foreach($match as $val)
		{
			$inputvalue = htmlspecialchars(trim(strip_tags(daddslashes($val))));
			if($val=='')continue;
			$inputs[] = $inputvalue;
			$count++;
		}
		if($count==0)exit('{"code":-1,"msg":"下单账号不能为空"}');
		$totalnum = $count * $num;

		if($tool['is_curl']==4){
			$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='$tid' AND orderid=0");
			$nums=($tool['value']>1?$tool['value']:1)*$totalnum;
			if($count==0)exit('{"code":-1,"msg":"该商品库存卡密不足，请联系站长加卡！"}');
			if($nums>$count)exit('{"code":-1,"msg":"您所购买的数量超过库存数量！"}');
		}
		elseif($tool['stock']!==null){
			if($tool['stock']==0)exit('{"code":-1,"msg":"该商品库存不足，请联系站长增加库存！"}');
			if($totalnum>$tool['stock'])exit('{"code":-1,"msg":"您所购买的数量超过库存数量！"}');
		}
		if(isset($price_obj)){
			$price_obj->setToolInfo($tid,$tool);
			$price=$price_obj->getToolPrice($tid);
			$price=$price_obj->getFinalPrice($price, $totalnum);
			if(!$price)exit('{"code":-1,"msg":"当前商品批发价格优惠设置不正确"}');
		}else $price=$tool['price'];

		if($price==0){
			exit('{"code":-1,"msg":"免费商品不支持批量下单"}');
		}
		$need=round($price*$totalnum, 2);
		
		//下单对接预检查
		if($need>0 && $tool['shequ']>0 && $tool['is_curl']==2 && in_array($tool['cid'],explode(",",$conf['pricejk_cid'])) && time()-$tool['uptime']>=$conf['pricejk_time']){
			$shequ=$DB->getRow("select * from shua_shequ where id='{$tool['shequ']}' limit 1");
			$allowType = explode(',',$CACHE->read('pricejk_type2'));
			if($conf['pricejk_yile']==0 && in_array($shequ['type'],$allowType) && $tool['prid']>0){
				$num_change = third_call($shequ['type'], $shequ, 'pricejk_one', [$tool]);
				if($num_change>0){
					exit('{"code":3,"msg":"当前商品价格发生变化，请刷新页面重试","change":"'.$num_change.'"}');
				}
			}else{
				$apireturn = third_call($shequ['type'], $shequ, 'pre_check', [$tool, $totalnum]);
				if($apireturn && $apireturn['code']==-1){
					exit('{"code":3,"msg":"'.$apireturn['msg'].'"}');
				}
			}
		}

		$ids = array();
		foreach($inputs as $input){
			$need2=round($price*$num, 2);
			$sql="INSERT INTO `shua_cart` (`userid`,`zid`,`tid`,`input`,`num`,`money`,`addtime`,`blockdj`,`status`) VALUES (:userid, :zid, :tid, :input, :num, :money, NOW(), :blockdj, 1)";
			$data = [':userid'=>$cookiesid, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':tid'=>$tid, ':input'=>$input, ':num'=>$num, ':money'=>$need2, ':blockdj'=>0];
			$DB->exec($sql, $data);
			$ids[] = $DB->lastInsertId();
		}
		$input = implode('|',$ids);

		$trade_no=date("YmdHis").rand(111,999);
		$sql="INSERT INTO `shua_pay` (`trade_no`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`inviteid`,`addtime`,`status`) VALUES (:trade_no, :tid, :zid, :input, :num, :name, :money, :ip, :userid, :inviteid, NOW(), 0)";
		$data = [':trade_no'=>$trade_no, ':tid'=>-3, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':input'=>$input, ':num'=>count($ids), ':name'=>$tool['name'], ':money'=>$need, ':ip'=>$clientip, ':userid'=>$cookiesid, ':inviteid'=>$invite_id];
		if($DB->exec($sql, $data)){
			unset($_SESSION['addsalt']);
			if($conf['forcermb']==1){$conf['alipay_api']=0;$conf['wxpay_api']=0;$conf['qqpay_api']=0;}
			$result = ['code'=>0, 'msg'=>'提交订单成功！', 'trade_no'=>$trade_no, 'need'=>$need, 'num'=>$count, 'pay_alipay'=>$conf['alipay_api'], 'pay_wxpay'=>$conf['wxpay_api'], 'pay_qqpay'=>$conf['qqpay_api'], 'pay_rmb'=>$islogin2, 'user_rmb'=>$userrow['rmb'], 'paymsg'=>$conf['paymsg']];
			exit(json_encode($result));
		}else{
			exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
		}

	}else{
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
	break;
case 'cancel':
	$orderid=isset($_POST['orderid'])?trim($_POST['orderid']):exit('{"code":-1,"msg":"订单号未知"}');
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$srow=$DB->getRow("SELECT trade_no,userid FROM shua_pay WHERE trade_no=:orderid LIMIT 1", [':orderid'=>$orderid]);
	if(!$srow['trade_no'] || $srow['userid']!=$cookiesid)exit('{"code":-1,"msg":"订单号不存在！"}');
	if($srow['status']==0){
		//$DB->exec("DELETE FROM shua_pay WHERE trade_no=:orderid", [':orderid'=>$orderid]);
		if($conf['verify_open']==1){
			$_SESSION['addsalt'] = $hashsalt;
		}
	}
	exit('{"code":0,"msg":"ok"}');
	break;
case 'card_check':
	if($conf['iskami']==0)exit('{"code":-1,"msg":"当前站点未开启卡密下单"}');
	$km=trim(daddslashes($_POST['km']));
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$myrow=$DB->getRow("SELECT * FROM shua_kms WHERE km='$km' AND type=1 LIMIT 1");
	if(!$myrow) exit('{"code":-1,"msg":"此卡密不存在！"}');
	if($myrow['status']==1) exit('{"code":-1,"msg":"此卡密已被使用！"}');
	$res=$DB->getRow("SELECT * FROM shua_tools WHERE tid='{$myrow['tid']}' AND active=1 LIMIT 1");
	if(!$res)exit('{"code":-1,"msg":"当前卡密对应的商品不存在"}');
	if($res['is_curl']==4){
		$isfaka = 1;
		$res['input'] = getFakaInput();
	}else{
		$isfaka = 0;
	}
	$result=array("code"=>0,"num"=>$myrow['num'],"data"=>array('tid'=>$res['tid'],'cid'=>$res['cid'],'sort'=>$res['sort'],'name'=>$res['name'],'value'=>$res['value'],'price'=>$price,'input'=>$res['input'],'inputs'=>$res['inputs'],'desc'=>$res['desc'],'alert'=>$res['alert'],'shopimg'=>$res['shopimg'],'repeat'=>$res['repeat'],'multi'=>$res['multi'],'close'=>$res['close'],'prices'=>$res['prices'],'min'=>$res['min'],'max'=>$res['max'],'sales'=>$res['sales'],'isfaka'=>$isfaka,'stock'=>$res['stock']));
	exit(json_encode($result));
	break;
case 'card_pay':
	if($conf['iskami']==0)exit('{"code":-1,"msg":"当前站点未开启卡密下单"}');
	$km=trim(daddslashes($_POST['km']));
	$inputvalue=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue']))));
	$inputvalue2=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue2']))));
	$inputvalue3=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue3']))));
	$inputvalue4=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue4']))));
	$inputvalue5=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue5']))));
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$myrow=$DB->getRow("SELECT * FROM shua_kms WHERE km='$km' AND type=1 LIMIT 1");
	if(!$myrow) exit('{"code":-1,"msg":"此卡密不存在！"}');
	if($myrow['status']==1) exit('{"code":-1,"msg":"此卡密已被使用！"}');
	$num = $myrow['num']?$myrow['num']:1;
	$tid = $myrow['tid'];
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='$tid' LIMIT 1");
	if($tool && $tool['active']==1){
		if($tool['close']==1)exit('{"code":-1,"msg":"当前商品维护中，停止下单！"}');
		if($conf['forcelogin']==1 && !$islogin2)exit('{"code":4,"msg":"您还未登录"}');
		if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
			exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
		}
		$inputs=explode('|',$tool['inputs']);
		if(empty($inputvalue) || $inputs[0] && empty($inputvalue2) || $inputs[1] && empty($inputvalue3) || $inputs[2] && empty($inputvalue4) || $inputs[3] && empty($inputvalue5)){
			exit('{"code":-1,"msg":"请确保各项不能为空"}');
		}
		if(!$inputs[0] && !empty($inputvalue2) || !$inputs[1] && !empty($inputvalue3) || !$inputs[2] && !empty($inputvalue4) || !$inputs[3] && !empty($inputvalue5)){
			exit('{"code":-1,"msg":"验证失败"}');
		}
		if(in_array($inputvalue,explode("|",$conf['blacklist'])))exit('{"code":-1,"msg":"您的下单账号已被拉黑，无法下单！"}');
		if($tool['is_curl']==4){
			if(!$islogin2 && $conf['faka_input']==0 && !checkEmail($inputvalue)){
				exit('{"code":-1,"msg":"邮箱格式不正确"}');
			}
			$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='$tid' AND orderid=0");
			$nums=($tool['value']>1?$tool['value']:1)*$num;
			if($count==0)exit('{"code":-1,"msg":"该商品库存卡密不足，请联系站长加卡！"}');
			if($nums>$count)exit('{"code":-1,"msg":"您所购买的数量超过库存数量！"}');
		}
		elseif($tool['stock']!==null){
			if($tool['stock']==0)exit('{"code":-1,"msg":"该商品库存不足，请联系站长增加库存！"}');
			if($num>$tool['stock'])exit('{"code":-1,"msg":"您所购买的数量超过库存数量！"}');
		}
		elseif($tool['repeat']==0){
			$thtime=date("Y-m-d").' 00:00:00';
			$row=$DB->getRow("SELECT id,input,status,addtime FROM shua_orders WHERE tid=:tid AND input=:input ORDER BY id DESC LIMIT 1", [':tid'=>$tid, ':input'=>$inputvalue]);
			if($row['input'] && $row['status']==0)
				exit('{"code":-1,"msg":"您今天添加的'.$tool['name'].'正在排队中，请勿重复提交！"}');
			elseif($row['addtime']>$thtime)
				exit('{"code":-1,"msg":"您今天已添加过'.$tool['name'].'，请勿重复提交！"}');
		}
		if($tool['validate']==1 && is_numeric($inputvalue)){
			if(validate_qzone($inputvalue)==false)
				exit('{"code":-1,"msg":"您的QQ空间设置了访问权限，无法下单！"}');
		}elseif(($tool['validate']==2 || $tool['validate']==3) && is_numeric($inputvalue)){
			$services = getservices($inputvalue);
			if($services['code']!=0)exit('{"code":-1,"msg":"'.$services['msg'].'"}');
			$qqservices = ['vip'=>'QQ会员','svip'=>'超级会员','bigqqvip'=>'大会员','red'=>'红钻贵族','green'=>'绿钻贵族','sgreen'=>'绿钻豪华版','yellow'=>'黄钻贵族','syellow'=>'豪华黄钻','hollywood'=>'腾讯视频VIP','qqmsey'=>'付费音乐包','qqmstw'=>'豪华付费音乐包','weiyun'=>'微云会员','sweiyun'=>'微云超级会员'];
			if(in_array($tool['valiserv'], $services['data'])){
				if($tool['validate']==2){
					exit('{"code":-1,"msg":"您的QQ已经开通了'.$qqservices[$tool['valiserv']].'，该商品无法购买！"}');
				}else{
					$blockdj=1;
				}
			}
		}
		if($tool['multi']==0 || $num<1)$num = 1;
		if($tool['multi']==1 && $tool['min']>0 && $num<$tool['min'])exit('{"code":-1,"msg":"当前商品最小下单数量为'.$tool['min'].'"}');
		if($tool['multi']==1 && $tool['max']>0 && $num>$tool['max'])exit('{"code":-1,"msg":"当前商品最大下单数量为'.$tool['max'].'"}');

		$trade_no='kid:'.$myrow['kid'];
		$input=$inputvalue.($inputvalue2?'|'.$inputvalue2:null).($inputvalue3?'|'.$inputvalue3:null).($inputvalue4?'|'.$inputvalue4:null).($inputvalue5?'|'.$inputvalue5:null);
		$srow['tid']=$tid;
		$srow['input']=$input;
		$srow['num']=$num;
		$srow['zid']=$siterow['zid']?$siterow['zid']:1;
		$srow['userid']=$cookiesid;
		$srow['trade_no']=$trade_no;
		$srow['money']=0;
		if($orderid=processOrder($srow)){
			unset($_SESSION['addsalt']);
			$DB->query("UPDATE `shua_kms` SET `status`=1,`orderid`='$orderid',`usetime`=NOW() where `kid`='{$myrow['kid']}'");
			exit('{"code":1,"msg":"下单成功！您可以在进度查询中查看订单进度","orderid":"'.$orderid.'"}');
		}else{
			exit('{"code":-1,"msg":"下单失败！'.$DB->error().'"}');
		}
	}else{
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
	break;
case 'query':
	$type=intval($_POST['type']);
	$qq=trim(daddslashes($_POST['qq']));
	$page=isset($_POST['page'])?intval($_POST['page']):1;
	if($type==1 && !empty($qq)){
		if(strlen($qq)==17 && is_numeric($qq))$sql=" A.`tradeno`='{$qq}'";
		else if(is_numeric($qq))$sql=" A.`id`='{$qq}' AND A.`userid`='$cookiesid'";
		else exit('{"code":-1,"msg":"请输入正确的订单号"}');
	}
	elseif(!empty($qq)){
		$sql=" A.`input`='{$qq}'";
		if($conf['queryorderlimit']==1)$sql.=" AND A.`userid`='$cookiesid'";
	}
	else $sql=" A.`userid`='$cookiesid'";
	
	$limit = 10;
	$start = $limit * ($page-1);
	$sql = "SELECT A.*,B.`name` FROM `shua_orders` A LEFT JOIN `shua_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT {$start},{$limit}";
	$rs=$DB->query($sql);
	$data=array();
	$count = 0;
	while($res = $rs->fetch(PDO::FETCH_ASSOC)){
		$count++;
		$data[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'name'=>$res['name'],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status'],'skey'=>md5($res['id'].SYS_KEY.$res['id']));
	}
	if($page>1 && $count==0)exit('{"code":-1,"msg":"没有更多订单了"}');
	$result=array("code"=>0,"msg"=>"succ","content"=>$qq,"page"=>$page,"isnext"=>($count==$limit?true:false),"islast"=>($page>1?true:false),"data"=>$data);
	exit(json_encode($result));
	break;
case 'order': //订单进度查询
	$id=intval($_POST['id']);
	if(md5($id.SYS_KEY.$id)!==$_POST['skey'])exit('{"code":-1,"msg":"验证失败"}');
	$row=$DB->getRow("SELECT * FROM shua_orders WHERE id='$id' LIMIT 1");
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
		
	$download_url = '';	
	
	if($row['tid']>0){
	    
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='{$row['tid']}' LIMIT 1");
	if($tool['is_curl']==4 || $row['djzt']==3){
		$count = ($tool['value']>1?$tool['value']:1)*$row['value'];
		if($count>6){
			$kmdata='<center><a href="./?mod=faka&id='.$id.'&skey='.$_POST['skey'].'" target="_blank" class="btn btn-sm btn-primary">点此查看卡密</a></center>';
		}else{
			$rs=$DB->query("SELECT * FROM shua_faka WHERE tid='{$row['tid']}' AND orderid='$id' ORDER BY kid ASC LIMIT {$count}");
			$kmdata='';
			while($res = $rs->fetch(PDO::FETCH_ASSOC))
			{
				if(!empty($res['pw'])){
					$kmdata.='卡号：'.$res['km'].' 密码：'.$res['pw'].'<br/>';
				}else{
					$kmdata.=$res['km'].'<br/>';
				}
				if(strlen($res['km'].$res['pw'])>80){
					$kmdata='<center><a href="./?mod=faka&id='.$id.'&skey='.$_POST['skey'].'" target="_blank" class="btn btn-sm btn-primary">点此查看卡密</a></center>';
					break;
				}
			}
		}
	}elseif($tool['is_curl']==2){
		$shequ=$DB->getRow("SELECT * FROM shua_shequ WHERE id='{$tool['shequ']}' LIMIT 1");
		$list = third_call($shequ['type'], $shequ, 'query_order', [$row['djorder'], $tool['goods_id'], [$row['input'], $row['input2'], $row['input3'], $row['input4'], $row['input5']]]);
		if($list && is_array($list)){
			if(($list['order_state']=='已完成'||$list['order_state']=='订单已完成'||$list['订单状态']=='已完成'||$list['订单状态']=='已发货'||$list['订单状态']=='交易成功'||$list['订单状态']=='已支付') && $row['status']==2){
				$DB->exec("UPDATE `shua_orders` SET `status`=1 WHERE id='{$id}'");
				$row['status']=1;
			}
			if((strpos($list['order_state'],'异常')!==false||strpos($list['order_state'],'退单')!==false||strpos($list['order_state'],'退款')!==false||$list['订单状态']=='异常'||$list['订单状态']=='已退单') && $row['status']<3){
				$DB->exec("UPDATE `shua_orders` SET `status`=3 WHERE id='{$id}'");
				$row['status']=3;
			}
		}else{
			$list = false;
		}
	}elseif($tool['is_curl']==5 && empty($row['result'])){
		$row['result'] = $tool['goods_param'];
	}
	$input=$tool['input']?$tool['input']:'';
	if($tool['is_curl']==4)$input='';
	$inputs=explode('|',$tool['inputs']);
	$inputsdata=$input.''.$row['input'];
	$i=2;
	foreach($inputs as $input){
		if(!$input)continue;
		if(strpos($input,'{')!==false && strpos($input,'}')!==false){
			$input = substr($input,0,strpos($input,'{'));
		}
		if(strpos($input,'[')!==false && strpos($input,']')!==false){
			$input = substr($input,0,strpos($input,'['));
		}
		$inputsdata.='<br/>'.$input.'：'.(strpos($input,'密码')===false?$row['input'.$i]:'********');
		if($i==2 && strpos($input,'密码')!==false && $conf['show_changepwd']==1){
			$inputsdata.=' [<a href="javascript:changepwd('.$row['id'].',\''.md5($row['id'].SYS_KEY.$row['id']).'\')">修改密码</a>]';
		}
		$i++;
	}
	unset($list['退款金额']);
	
	}elseif($row['tid']==0 && $row['vid']>0){
	    $tool=$DB->getRow("SELECT * FROM pre_videolist WHERE id='{$row['vid']}' LIMIT 1");
	    $tool['alert'] = '';
	    $inputsdata = '';
	    
	    if(strpos($row['input2'],'购买短剧地址') !== false){
	         $download_url = $row['input'];
	    }elseif(strpos($srow['input2'],'购买短剧播放') !== false){
	         $download_url = '';
	         $inputsdata = '购买短剧'.$tool['name'].'播放权限';
	    }
	    
	   
	    $kmdata = '';
	    $list = false;
	}
	
	$spxq_xx=$DB->getRow("SELECT * FROM shua_config WHERE k='spxq_xx' LIMIT 1");
	$result=array('code'=>0,'msg'=>'succ','name'=>$tool['name'],'money'=>$row['money'],'date'=>$row['addtime'],'inputs'=>$inputsdata,'list'=>$list,'kminfo'=>$kmdata,'alert'=>$tool['alert'],'spxq_xx'=>$spxq_xx['v'],'desc'=>$tool['desc'],'status'=>$row['status'],'download_url'=>$download_url,'result'=>$row['result'],'complain'=>intval($conf['show_complain']),'islogin'=>$islogin2,'selfrefund'=>$conf['selfrefund']);
	exit(json_encode($result));
	break;
case 'apply_refund':
	if(!$conf['selfrefund'])exit('{"code":-1,"msg":"当前站点未开启自助申请退款"}');
	if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
	$id=intval($_POST['id']);
	if(md5($id.SYS_KEY.$id)!==$_POST['skey'])exit('{"code":-1,"msg":"验证失败"}');
	$DB->beginTransaction();
	$row=$DB->getRow("SELECT * FROM shua_orders WHERE id='$id' AND userid='{$userrow['zid']}' LIMIT 1 FOR UPDATE");
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	if($row['status']!=0 && $row['status']!=3) exit('{"code":-1,"msg":"只有未处理和异常的订单才支持退款"}');
	if($row['status']==4)exit('{"code":-1,"msg":"该订单已退款请勿重复提交"}');
	if(!rollbackPoint($id)){
		$DB->rollBack();
		exit('{"code":-1,"msg":"该订单扣除上级提成失败，无法自助申请退款"}');
	}
	changeUserMoney($userrow['zid'], $row['money'], true, '退款', '订单(ID'.$id.')已退款到余额');
	$DB->exec("update shua_orders set status='4' where id='{$id}'");
	$DB->commit();
	exit(json_encode(['code'=>0, 'msg'=>'succ', 'money'=>$row['money']]));
	break;
case 'changepwd':
	$orderid=daddslashes($_POST['id']);
	if(!$conf['show_changepwd'])exit('{"code":-1,"msg":"站点未开启修改订单密码"}');
	if(md5($orderid.SYS_KEY.$orderid)!==$_POST['skey'])exit('{"code":-1,"msg":"验证失败"}');
	$pwd=htmlspecialchars(trim(strip_tags(daddslashes($_POST['pwd']))));
	if(strlen($pwd)<5)exit('{"code":-1,"msg":"请输入正确的密码"}');
	$row=$DB->getRow("SELECT id,status FROM shua_orders WHERE id='$orderid' LIMIT 1");
	if($row['status']==1)exit('{"code":-1,"msg":"该订单已完成，无法修改密码"}');
	if($row){
		if($DB->exec("UPDATE `shua_orders` SET `input2` ='{$pwd}',status=0 WHERE `id`='{$orderid}'")!==false){
			$result=array("code"=>0,"msg"=>"已成功修改密码");
		}else{
			$result=array("code"=>0,"msg"=>"修改密码失败");
		}
	}else{
		$result=array("code"=>-1,"msg"=>"订单不存在");
	}
	exit(json_encode($result));
	break;
case 'fill':
	$orderid=intval($_POST['orderid']);
	if(md5($orderid.SYS_KEY.$orderid)!==$_POST['skey'])exit('{"code":-1,"msg":"验证失败"}');
	$row=$DB->getRow("SELECT id,status FROM shua_orders WHERE id='$orderid' LIMIT 1");
	if($row){
		if($row['status']==3){
			$DB->exec("UPDATE `shua_orders` SET `status` ='0',result=NULL WHERE `id`='{$orderid}'");
			$result=array("code"=>0,"msg"=>"已成功补交订单");
		}else{
			$result=array("code"=>0,"msg"=>"该订单不符合补交条件");
		}
	}else{
		$result=array("code"=>-1,"msg"=>"订单不存在");
	}
	exit(json_encode($result));
	break;
case 'checklogin':
	if($islogin2==1)exit('{"code":1}');
	else exit('{"code":0}');
	break;
case 'getshuoshuo':
	$uin=trim(daddslashes($_GET['uin']));
	$page=intval($_GET['page']);
	$hashsalt=isset($_GET['hashsalt'])?$_GET['hashsalt']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if(empty($uin))exit('{"code":-5,"msg":"QQ号不能为空"}');
	$result = getshuoshuo($uin,$page);
	exit(json_encode($result));
	break;
case 'getrizhi':
	$uin=trim(daddslashes($_GET['uin']));
	$page=intval($_GET['page']);
	$hashsalt=isset($_GET['hashsalt'])?$_GET['hashsalt']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if(empty($uin))exit('{"code":-5,"msg":"QQ号不能为空"}');
	$result = getrizhi($uin,$page);
	exit(json_encode($result));
	break;
case 'getshareid':
	$url=trim($_POST['url']);
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if(empty($url))exit('{"code":-5,"msg":"url不能为空"}');
	$result = getshareid($url);
	exit(json_encode($result));
	break;
case 'getshareids':
	$urls=$_POST['urls'];
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if(!is_array($urls) || count($urls)==0)exit('{"code":-5,"msg":"url不能为空"}');
	$list = [];
	foreach($urls as $url){
		$res = getshareid($url);
		if($res['code']==0) $list[] = $res['songid'];
	}
	$result = ['code'=>0,'data'=>$list];
	exit(json_encode($result));
	break;
case 'gift_start':
	$action = $_GET['action'];
	if ($action == '') {
		if(!$conf['gift_open'])exit('{"code":-2,"msg":"网站未开启抽奖功能"}');
		if(!$conf['cjcishu'])exit('{"code":-2,"msg":"站长未设置每日抽奖次数！"}');
		$thtime=date("Y-m-d").' 00:00:00';
		$cjcount = $DB->getColumn("SELECT count(*) FROM shua_giftlog WHERE (userid='$cookiesid' OR ip='$clientip') AND addtime>='$thtime'");
		if ($cjcount >= $conf['cjcishu']) {
			exit('{"code":-1,"msg":"' . $cjmsg . '"}');
		}
		$query = $DB->query("SELECT * FROM shua_gift WHERE ok=0");
		while ($row = $query->fetch()) {
			$arr[] = array("id" => $row["id"], "tid" => $row["tid"], "name" => $row["name"]);
		}
		$rateall = $DB->getColumn("SELECT sum(rate) FROM shua_gift WHERE ok=0");
		if($rateall<100)$arr[] = array("id" => 0, "tid" => 0, "name" => '未中奖');
		if (!$arr) {
			exit('{"code":-2,"msg":"站长未设置奖品"}');
		}
		$result=array("code"=>0,"data"=>$arr);
		exit(json_encode($result));
	} else {
		$token = md5($_GET['r'].SYS_KEY.$_GET['r']);
		exit('{"code":0,"token":"'.$token.'"}');
	}
	break;
case 'gift_stop':
	if(!$conf['gift_open'])exit('{"code":-2,"msg":"网站未开启抽奖功能"}');
	if(!$conf['cjcishu'])exit('{"code":-2,"msg":"站长未设置每日抽奖次数！"}');
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$token=isset($_POST['token'])?$_POST['token']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if(md5($_GET['r'].SYS_KEY.$_GET['r']) !== $token)exit('{"code":-1,"msg":"请勿重复提交请求"}');
	$thtime=date("Y-m-d").' 00:00:00';
	$cjcount = $DB->getColumn("SELECT count(*) FROM shua_giftlog WHERE (userid='$cookiesid' OR ip='$clientip') AND addtime>='$thtime'");
	if ($cjcount >= $conf['cjcishu']) {
		exit('{"code":-1,"msg":"' . $cjmsg . '"}');
	}
	$prize_arr = array();
	$query = $DB->query("SELECT * FROM shua_gift WHERE ok=0");
	$i = 1;
	$bre = $DB->getColumn("SELECT count(*) FROM shua_gift WHERE ok=0");
	while ($i <= $bre) {
		while ($row = $query->fetch()) {
			$prize_arr[] = array("id" => ($i = $i + 1) -1, "gid" => $row["id"], "tid" => $row["tid"], "name" => $row["name"], "rate" => $row["rate"], "not" => 0);
		}
	}
	if (!$prize_arr) {
		exit('{"code":-2,"msg":"站长未设置奖品"}');
	}
	$rateall = $DB->getColumn("SELECT sum(rate) FROM shua_gift WHERE ok=0");
	if($rateall<100)$prize_arr[] = array("id" => ($i = $i + 1) -1, "gid" => 0, "tid" => 0, "name" => '未中奖', "rate" => 100-$rateall, "not" => 1);
	foreach ($prize_arr as $key => $val) {
		$arr[$val["id"]] = $val["rate"];
	}
	$prize_id = get_rand($arr);
	$data['rate'] = $prize_arr[$prize_id - 1]['rate'];
	$data['id'] = $prize_arr[$prize_id - 1]['id'];
	$data['gid'] = $prize_arr[$prize_id - 1]['gid'];
	$data['name'] = $prize_arr[$prize_id - 1]['name'];
	$data['tid'] = $prize_arr[$prize_id - 1]['tid'];
	$data['not'] = $prize_arr[$prize_id - 1]['not'];

	$gift_id = $DB->exec("INSERT INTO `shua_giftlog`(`zid`,`tid`,`gid`,`userid`,`ip`,`addtime`,`status`) VALUES ('".($siterow['zid']?$siterow['zid']:1)."','".$data['tid']."','".$data['gid']."','".$cookiesid."','".$clientip."','".$date."',0)");
	if ($gift_id) {
		if ($data['not'] == 1) {
			exit('{"code":-1,"msg":"未中奖，谢谢参与！"}');
		}
		$_SESSION['gift_tid'] = $data['tid'];
		$_SESSION['gift_id'] = $DB->lastInsertId();
		unset($_SESSION['addsalt']);

		$cid = $DB->getColumn("SELECT cid FROM shua_tools WHERE tid='{$data['tid']}' LIMIT 1");
		$result = array("code" => 0, "msg" => "succ", "cid" => $cid, "tid" => $data['tid'], "name" => $data['name']);
		exit(json_encode($result));
	} else {
		exit('{"code":-3,"msg":"' . $DB->error() . '"}');
	}
	break;
case 'invite_create':
	if(!$conf['invite_tid'])exit('{"code":-1,"msg":"未开启该功能"}');
	$nid = intval($_POST['nid']);
	$query_qq=htmlspecialchars(trim(strip_tags(daddslashes($_POST['query_qq']))));
	$inputvalue=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue']))));
	$inputvalue2=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue2']))));
	$inputvalue3=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue3']))));
	$inputvalue4=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue4']))));
	$inputvalue5=htmlspecialchars(trim(strip_tags(daddslashes($_POST['inputvalue5']))));
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	
	if (!preg_match('/^[1-9][0-9]{4,9}$/i', $query_qq)) {
		exit('{"code":-1,"msg":"QQ号码格式不正确"}');
	}
	$inviteshop=$DB->getRow("SELECT * FROM shua_inviteshop WHERE id='$nid' LIMIT 1");
	if(!$inviteshop || $inviteshop['active']==0){
		exit('{"code":-2,"msg":"该推广商品不存在"}');
	}
	if($inviteshop['type']==1){
		$plan = $inviteshop['value'];
	}else{
		$plan = 0;
	}
	$tid = $inviteshop['tid'];
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='$tid' LIMIT 1");
	if(!$tool || $tool['active']==0){
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
	if($tool['close']==1)exit('{"code":-1,"msg":"当前商品维护中，停止下单！"}');
	if(in_array($inputvalue,explode("|",$conf['blacklist'])))exit('{"code":-1,"msg":"您的下单账号已被拉黑，无法下单！"}');
	$inputs=explode('|',$tool['inputs']);
	if($inputs[0] && empty($inputvalue2) || $inputs[1] && empty($inputvalue3) || $inputs[2] && empty($inputvalue4) || $inputs[3] && empty($inputvalue5)){
		exit('{"code":-1,"msg":"请确保各项不能为空"}');
	}
	if(!$inputs[0] && !empty($inputvalue2) || !$inputs[1] && !empty($inputvalue3) || !$inputs[2] && !empty($inputvalue4) || !$inputs[3] && !empty($inputvalue5)){
		exit('{"code":-1,"msg":"验证失败"}');
	}
	if($tool['validate']==1 && is_numeric($inputvalue)){
		if(validate_qzone($inputvalue)==false)
			exit('{"code":-1,"msg":"您的QQ空间设置了访问权限，无法下单！"}');
	}elseif(($tool['validate']==2 || $tool['validate']==3) && is_numeric($inputvalue)){
		$services = getservices($inputvalue);
		if($services['code']!=0)exit('{"code":-1,"msg":"'.$services['msg'].'"}');
		$qqservices = ['vip'=>'QQ会员','svip'=>'超级会员','bigqqvip'=>'大会员','red'=>'红钻贵族','green'=>'绿钻贵族','sgreen'=>'绿钻豪华版','yellow'=>'黄钻贵族','syellow'=>'豪华黄钻','hollywood'=>'腾讯视频VIP','qqmsey'=>'付费音乐包','qqmstw'=>'豪华付费音乐包','weiyun'=>'微云会员','sweiyun'=>'微云超级会员'];
		if(in_array($tool['valiserv'], $services['data'])){
			if($tool['validate']==2){
				exit('{"code":-1,"msg":"您的QQ已经开通了'.$qqservices[$tool['valiserv']].'，该商品无法购买！"}');
			}else{
				$blockdj=1;
			}
		}
	}
	$input=$inputvalue.($inputvalue2?'|'.$inputvalue2:null).($inputvalue3?'|'.$inputvalue3:null).($inputvalue4?'|'.$inputvalue4:null).($inputvalue5?'|'.$inputvalue5:null);

	$qqrow = $DB->getRow("SELECT * FROM `shua_invite` WHERE `qq`='$query_qq' AND tid='$tid' AND status=0 LIMIT 1");
	if ($qqrow)
	{
		if($qqrow['input']!=$input){
			$DB->exec("UPDATE `shua_invite` SET `input`=:input WHERE `id`=:id", [':input'=>$input, ':id'=>$qqrow['id']]);
		}
		$code = 2;
		$url = $siteurl . '?i=' .$qqrow['key'];
	} else {
		if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
			exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
		}
		$key = random(6);
		if($DB->exec("INSERT INTO `shua_invite` (`nid`,`tid`,`qq`,`input`,`key`,`ip`,`plan`,`date`,`status`) VALUES (:nid,:tid,:qq,:input,:key,:ip,:plan,NOW(),0)", [':nid'=>$nid, ':tid'=>$tid, ':qq'=>$query_qq, ':input'=>$input, ':key'=>$key, ':ip'=>$clientip, ':plan'=>$plan])){
			unset($_SESSION['addsalt']);
			$url = $siteurl . '?i=' . $key ;
		}else{
			exit('{"code":-1,"msg":"' . $DB->error() . '"}');
		}
	}
	if($conf['fanghong_api']>0)$url = fanghongdwz($url);
	$content = str_replace('[url]',$url,$conf['invite_content']);
	if(!$content)$content = $url;
	$result = array('code'=>0, 'msg'=>'succ', 'url'=>$url, 'content'=>$content);
	exit(json_encode($result));
break;
case 'invite_query':
	$qq = daddslashes($_POST['query_qq']);
	if (!preg_match('/^[1-9][0-9]{4,12}$/i', $qq)) {
		exit('{"code":-1,"msg":"QQ号码格式不正确"}');
	}
	$re = $DB->query("SELECT A.*,B.`name` FROM `shua_invite` A LEFT JOIN `shua_tools` B ON A.`tid`=B.`tid` WHERE A.`qq`='$qq' ORDER BY A.`id` DESC LIMIT 30");
	$ar_log = [];
	while ($res = $re->fetch()) {
		$input_arr = explode('|',$res['input']);
		$ar_log[] = [
			'id' => $res['id'],
			'nid' => $res['nid'],
			'tid' => $res['tid'],
			'input' => $input_arr[0],
			'name' => $res['name'],
			'count' => $res['count'],
			'plan' => $res['plan'],
			'click' => $res['click'],
			'key' => $res['key'],
			'addtime' => $res['date'],
			'status' => $res['status'],
		];
	};

	if (count($ar_log) == 0) exit(json_encode(['code' => -1, 'msg' => '无相关数据,请先去生成对应的推广链接再来查询！']));

	exit(json_encode(['code' => 0, 'msg' => 'succ', 'data' => $ar_log]));
	break;
case 'invite_verify':
	$key = isset($_POST['key'])?$_POST['key']:exit('{"code":-1,"msg":"key null"}');
	$code = isset($_POST['code'])?$_POST['code']:null;
	if($conf['captcha_open']==1){
		if(isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
			if(!isset($_SESSION['gtserver']))exit('{"code":-1,"msg":"验证加载失败"}');
			$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);

			$data = array(
				'user_id' => $cookiesid,
				'client_type' => "web",
				'ip_address' => $clientip
			);

			if ($_SESSION['gtserver'] == 1) {   //服务器正常
				$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
				if ($result) {
					//echo '{"status":"success"}';
				} else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}else{  //服务器宕机,走failback模式
				if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
					//echo '{"status":"success"}';
				}else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}
		}else{
			exit('{"code":2,"type":1,"msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open']==2){
		if(isset($_POST['token'])){
			$client = new \lib\CaptchaClient($conf['captcha_id'], $conf['captcha_key']);
			$client->setTimeOut(2);
			$response = $client->verifyToken($_POST['token']);
			if($response->result){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":2,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open']==3){
		if(isset($_POST['token'])){
			if(vaptcha_verify($conf['captcha_id'], $conf['captcha_key'], $_POST['token'], $clientip)){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":3,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif (!$code || strtolower($code) != $_SESSION['vc_code']) {
		unset($_SESSION['vc_code']);
		exit('{"code":2,"msg":"验证码错误！"}');
	}
	$isAddShop=false;
	$invite_row = $DB->getRow("SELECT * FROM `shua_invite` WHERE `key` = :key LIMIT 1", [':key'=>$key]);
	if($invite_row && $invite_row['status']==0){
		$shop = $DB->getRow("SELECT * FROM `shua_inviteshop` WHERE `id`=:id LIMIT 1", [':id'=>$invite_row['nid']]);
		if($shop && $shop['active']==1 && $shop['type']==1){
			//if($invite_row['click']/$shop['value']>=0.8)exit(json_encode(array('code' => 0, 'msg' => 'succ', 'key'=>$key)));
			if($DB->getColumn("SELECT count(*) FROM `shua_invitelog` WHERE `ip`=:ip", [':ip'=>$clientip])==0){
				$DB->exec("INSERT INTO `shua_invitelog`(`iid`,`type`,`date`,`ip`,`status`) VALUES (:iid, 1, NOW(), :ip, 0)", [':iid'=>$invite_row['id'], ':ip'=>$clientip]);
				$DB->exec("UPDATE `shua_invite` SET `click`=`click`+1 WHERE `id`=:id", [':id'=>$invite_row['id']]);
				if($invite_row['click']+1 >= $shop['value']){
					$isAddShop=true;
				}
			}
		}
	}
	if($isAddShop && $DB->exec("UPDATE `shua_invite` SET `status`=1 WHERE `id`=:id", [':id'=>$invite_row['id']])){
		$DB->exec("UPDATE `shua_invite` SET `count`=`count`+1 WHERE `id`=:id", [':id'=>$invite_row['id']]);
		$trade_no='invite'.date("YmdHis").rand(111,999);
		$cookiesid=md5(uniqid(mt_rand(), 1) . time());
		$sql="INSERT INTO `shua_pay` (`trade_no`,`tid`,`zid`,`type`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`blockdj`,`status`) VALUES (:trade_no, :tid, :zid, :type, :input, :num, :name, :money, :ip, :userid, NOW(), 0, 1)";
		$data = [':trade_no'=>$trade_no, ':tid'=>$shop['tid'], ':zid'=>$siterow['zid']?$siterow['zid']:1, ':type'=>'free', ':input'=>$invite_row['input'], ':num'=>1, ':name'=>'推广奖励商品', ':money'=>'0', ':ip'=>$invite_row['ip'], ':userid'=>$cookiesid];
		if($DB->exec($sql, $data)){
			$srow['tid']=$shop['tid'];
			$srow['input']=$invite_row['input'];
			$srow['num']=1;
			$srow['zid']=$siterow['zid']?$siterow['zid']:1;
			$srow['userid']=$cookiesid;
			$srow['trade_no']=$trade_no;
			$srow['money']=0;
			$orderid=processOrder($srow);
		}
	}
	$result = array('code' => 0, 'msg' => 'succ', 'key'=>$key);
	exit(json_encode($result));
	break;
case 'invite_content':
	$id = intval($_POST['id']);
	$qqrow = $DB->getRow("SELECT * FROM `shua_invite` WHERE `id`='$id' LIMIT 1");

	if ($qqrow) {
		if($qqrow['status']==1)exit('{"code":-1,"msg":"该推广订单已经完成，奖励已经到账，请重新创建推广订单进行推广！"}');
		$url = $siteurl . '?i=' . $qqrow['key'];

		if ($conf['fanghong_api'] > 0) $url = fanghongdwz($url);
		$content = str_replace('[url]',$url,$conf['invite_content']);
		if(!$content)$content = $url;
		$result = array('code' => 0, 'msg' => 'succ', 'url' => $url, 'content'=>$content);
		exit(json_encode($result));

	} else exit('{"code":-1,"msg":"获取失败！"}');
	break;
case 'cart_info':
	if($conf['shoppingcart']==1){
		$cart_count = $DB->getColumn("SELECT count(*) FROM shua_cart WHERE userid='$cookiesid' AND status<=1");
	}
	$result = array('code'=>0, 'msg'=>'succ', 'count'=>$cart_count);
	exit(json_encode($result));
break;
case 'cart_num':
	$shop_id = intval($_POST['id']);
	$num = intval($_POST['num']);
	$cart_item = $DB->getRow("SELECT * FROM `shua_cart` WHERE `id`='$shop_id' LIMIT 1");
	if(!$cart_item)exit('{"code":-1,"msg":"商品不存在！"}');
	if($cart_item['userid']!=$cookiesid || $cart_item['status']>1)exit('{"code":-1,"msg":"商品权限校验失败"}');
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='{$cart_item['tid']}' LIMIT 1");
	if($tool['multi']==0 || $num<1)$num = 1;
	if($tool['multi']==1 && $tool['min']>0 && $num<$tool['min'])exit('{"code":-1,"msg":"当前商品最小下单数量为'.$tool['min'].'"}');
	if($tool['multi']==1 && $tool['max']>0 && $num>$tool['max'])exit('{"code":-1,"msg":"当前商品最大下单数量为'.$tool['max'].'"}');
	if($tool['price']==0){
		$price=0;
	}elseif(isset($price_obj)){
		$price_obj->setToolInfo($tid,$tool);
		$price=$price_obj->getToolPrice($tid);
		$price=$price_obj->getFinalPrice($price, $num);
		if(!$price)exit('{"code":-1,"msg":"当前商品批发价格优惠设置不正确"}');
	}else $price=$tool['price'];

	$need=$price*$num;
	$sql="UPDATE `shua_cart` SET `num`=:num,`money`=:money,`status`='0' WHERE id=:id";
	$data = [':num'=>$num, ':money'=>$need, ':id'=>$shop_id];
	if($DB->exec($sql, $data)!==false){
		exit('{"code":0,"msg":"修改数量成功！","need":"'.$need.'"}');
	}else{
		exit('{"code":-1,"msg":"修改数量失败！'.$DB->error().'"}');
	}
break;
case 'cart_list':
	$cartids = $_GET['ids'];
	if($cartids && count($cartids)>0){
		$ids = implode(',',$cartids);
		$rs=$DB->query("SELECT a.*,b.name,b.input AS inputname,b.shopimg,b.multi,b.inputs,b.is_curl FROM shua_cart AS a LEFT JOIN shua_tools AS b ON a.tid=b.tid WHERE a.userid=:userid AND a.id IN (:ids) AND a.status<=1 ORDER BY a.id ASC", [':userid'=>$cookiesid, ':ids'=>$ids]);
	}else{
		$rs=$DB->query("SELECT a.*,b.name,b.input AS inputname,b.shopimg,b.multi,b.inputs,b.is_curl FROM shua_cart AS a LEFT JOIN shua_tools AS b ON a.tid=b.tid WHERE a.userid=:userid AND a.status<=1 ORDER BY a.id ASC", [':userid'=>$cookiesid]);
	}
	$data = array();
	while($res = $rs->fetch(PDO::FETCH_ASSOC))
	{
		$input=$res['inputname']?$res['inputname']:'下单账号';
		$inputs=explode('|',$res['inputs']);
		$inputsdata=explode('|',$res['input']);
		$show=$input.'：'.$inputsdata[0];
		$i=1;
		foreach($inputs as $input){
			if(!$input)continue;
			if(strpos($input,'{')!==false && strpos($input,'}')!==false){
				$input = substr($input,0,strpos($input,'{'));
			}
			if(strpos($input,'[')!==false && strpos($input,']')!==false){
				$input = substr($input,0,strpos($input,'['));
			}
			$show.='&nbsp;'.$input.'：'.(strpos($input,'密码')===false?$inputsdata[$i++]:'********');
		}
		$res['inputsdata']=$show;
		$data[] = $res;
	}
	$count = count($data);
	$result=array("code"=>0,"msg"=>"succ","count"=>$count,"data"=>$data,"sitename"=>$conf['sitename']);
	exit(json_encode($result));
break;
case 'cart_buy':
	$shop_ids = $_POST['shop_id'];
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	$allmoney = 0;
	$ids = array();
	foreach($shop_ids as $shop_id){
		$cart_item = $DB->getRow("SELECT * FROM `shua_cart` WHERE `id`='".intval($shop_id)."' LIMIT 1");
		if(!$cart_item)exit('{"code":-1,"msg":"商品不存在！"}');
		if($cart_item['userid']!=$cookiesid || $cart_item['status']>1)exit('{"code":-1,"msg":"商品权限校验失败"}');
		if($cart_item['money']=='0' || !preg_match('/^[0-9.]+$/', $cart_item['money']))exit('{"code":-1,"msg":"商品金额不合法"}');
		$ids[] = intval($shop_id);
		$allmoney += floatval($cart_item['money']);
		$DB->exec("UPDATE `shua_cart` SET `status`=1 WHERE `id`='{$cart_item['id']}'");
	}
	if(count($ids)==0)exit('{"code":-1,"msg":"您未在购物车添加任何商品"}');
	$toolname=$DB->getColumn("SELECT name FROM shua_tools WHERE tid='{$cart_item['tid']}' LIMIT 1");
	$toolname = $toolname.'等多件';
	$input = implode('|',$ids);
	$trade_no=date("YmdHis").rand(111,999);

	$sql="INSERT INTO `shua_pay` (`trade_no`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`inviteid`,`addtime`,`status`) VALUES (:trade_no, :tid, :zid, :input, :num, :name, :money, :ip, :userid, :inviteid, NOW(), 0)";
	$data = [':trade_no'=>$trade_no, ':tid'=>-3, ':zid'=>$siterow['zid']?$siterow['zid']:1, ':input'=>$input, ':num'=>count($ids), ':name'=>$toolname, ':money'=>$allmoney, ':ip'=>$clientip, ':userid'=>$cookiesid, ':inviteid'=>$invite_id];
	if($DB->exec($sql, $data)){
		unset($_SESSION['addsalt']);
		if($conf['forcermb']==1){$conf['alipay_api']=0;$conf['wxpay_api']=0;$conf['qqpay_api']=0;}
		$result = ['code'=>0, 'msg'=>'提交订单成功！', 'trade_no'=>$trade_no, 'need'=>$allmoney, 'pay_alipay'=>$conf['alipay_api'], 'pay_wxpay'=>$conf['wxpay_api'], 'pay_qqpay'=>$conf['qqpay_api'], 'pay_rmb'=>$islogin2, 'user_rmb'=>$userrow['rmb'], 'paymsg'=>$conf['paymsg']];
		exit(json_encode($result));
	}else{
		exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
	}
break;
case 'cart_cancel':
	$orderid=isset($_POST['orderid'])?daddslashes($_POST['orderid']):exit('{"code":-1,"msg":"订单号未知"}');
	$hashsalt=isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$srow=$DB->getRow("SELECT * FROM shua_pay WHERE trade_no='{$orderid}' LIMIT 1");
	if(!$srow['trade_no'] || $srow['userid']!=$cookiesid)exit('{"code":-1,"msg":"订单号不存在！"}');
	if($srow['status']==0){
		//$DB->exec("DELETE FROM shua_pay WHERE trade_no='{$orderid}'");
		$input=explode('|',$srow['input']);
		$ids = implode(',',$input);
		$DB->exec("UPDATE shua_cart SET status=0 WHERE id IN ($ids) AND status=1");
		if($conf['verify_open']==1){
			$_SESSION['addsalt'] = $hashsalt;
		}
	}
	exit('{"code":0,"msg":"ok"}');
break;
case 'cart_empty':
	if($DB->exec("DELETE FROM shua_cart WHERE userid='$cookiesid' AND (status=0 OR status=1)")!==false){
		exit('{"code":0,"msg":"清空购物车成功！"}');
	}else{
		exit('{"code":-1,"msg":"清空购物车失败！'.$DB->error().'"}');
	}
break;
case 'cart_shop_del':
	$id = intval($_POST['id']);
	$cart_item = $DB->getRow("SELECT * FROM `shua_cart` WHERE `id`='$id' LIMIT 1");
	if(!$cart_item)exit('{"code":-1,"msg":"商品不存在！"}');
	if($cart_item['userid']!=$cookiesid || $cart_item['status']>1)exit('{"code":-1,"msg":"商品权限校验失败"}');
	if($DB->exec("DELETE FROM shua_cart WHERE id='$id'")!==false){
		exit('{"code":0,"msg":"商品删除成功！"}');
	}else{
		exit('{"code":-1,"msg":"商品删除失败！'.$DB->error().'"}');
	}
break;
case 'cart_shop_item':
	$id = intval($_POST['id']);
	$cart_item = $DB->getRow("SELECT * FROM `shua_cart` WHERE `id`='$id' LIMIT 1");
	if(!$cart_item)exit('{"code":-1,"msg":"商品不存在！"}');
	if($cart_item['userid']!=$cookiesid || $cart_item['status']>1)exit('{"code":-1,"msg":"商品权限校验失败"}');
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='{$cart_item['tid']}' LIMIT 1");
	$input=$tool['input']?$tool['input']:'下单ＱＱ';
	$inputs=explode('|',$tool['inputs']);
	$inputvalue=explode('|',$cart_item['input']);
	$data = '<div class="panel-body">';
	if($tool['value']>1)$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon">下单数量</div><input type="text" id="shop_count" value="" class="form-control" disabled/></div></div>';
	$data .= '<input type="hidden" id="value" value="'.($tool['value']?$tool['value']:1).'"/><div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname">下单份数</div><input type="text" id="num" value="'.$cart_item['num'].'" class="form-control" required/></div>';
	if($tool['max']>1)$data .= '<small class="help-block"><i class="fa fa-info-circle"></i>&nbsp;该商品下单份数不能超过<b>'.$tool['max'].'</b>份</small></div>';
	else $data .= '</div>';
	$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname">'.$input.'</div><input type="text" id="inputvalue" value="'.$inputvalue[0].'" class="form-control" required/></div></div>';
	$i=2;
	foreach($inputs as $input){
		if(!$input)continue;
		if(strpos($input,'{')!==false && strpos($input,'}')!==false){
			$inputname = substr($input,0,strpos($input,'{'));
			$arr = explode(',',getSubstr($input,'{','}'));
			$select='<option value="'.$inputvalue[$i-1].'">'.$inputvalue[$i-1].'</option>';
			foreach($arr as $option){
				if(strpos($option,':')!==false){
					$select.='<option value="'.explode(':',$option)[0].'">'.$option.'</option>';
				}else{
					$select.='<option value="'.$option.'">'.$option.'</option>';
				}
			}
			$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname'.$i.'">'.$inputname.'</div><select id="inputvalue'.$i.'" class="form-control">'.$select.'</select></div></div>';
		}else{
			$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname'.$i.'">'.$input.'</div><input type="text" id="inputvalue'.$i.'" value="'.$inputvalue[$i-1].'" class="form-control" required/></div></div>';
		}
		$i++;
	}
	$data .= '<input type="submit" id="save" onclick="cart_shop_save('.$id.')" class="btn btn-primary btn-block" value="保存修改"></div>';
	$data .= '<script>$("#num").keyup(function () {	var i = parseInt($("#num").val()); if(isNaN(i))return false; if(i<1) $("#num").val(1); var count = parseInt($("#value").val()); count = count * i; $("#shop_count").val(count+"个");});	$("#num").keyup();</script>';
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'share_link':
	$tid = intval($_GET['tid']);
	if(!$tid)exit('{"code":-1,"msg":"参数不能为空"}');
	$tool=$DB->getRow("SELECT * FROM shua_tools WHERE tid='$tid' AND active=1 LIMIT 1");
	if(!$tool)exit('{"code":-1,"msg":"商品不存在！"}');
	if(file_exists(TEMPLATE_ROOT.$conf['template'].'/buy.php')){
		$url = $siteurl.'?mod=buy&cid='.$tool['cid'].'&tid='.$tid;
	}else{
		$url = $siteurl.'?cid='.$tool['cid'].'&tid='.$tid;
	}
	if(isset($price_obj)){
		$price_obj->setToolInfo($tool['tid'],$tool);
		$price=$price_obj->getToolPrice($tool['tid']);
	}else $price=$tool['price'];
	if($conf['fanghong_api']>0)$url = fanghongdwz($url);
	$content = '【'.$tool['name'].'】'.$price.'元 下单链接：'.$url;
	$result=array("code"=>0,"msg"=>"succ","link"=>$url,"content"=>$content);
	exit(json_encode($result));
break;
case 'gettoolday':
	$page = $_POST['page'] ? intval(trim(daddslashes($_POST['page']))) : 1;
	$limit = $_POST['limit'] ? intval(trim(daddslashes($_POST['limit']))) : 9;
	if($limit < 1) $limit = 9;
	if($limit > 18) $limit = 18;
	$page = ($page-1)*$limit;
	$kw = trim(daddslashes($_POST['kw']));
	$cid = intval($_POST['cid']);
	
	$sort_type = $_POST['sort_type'] ? trim(daddslashes($_POST['sort_type'])) : 'sort';
	$sort = $_POST['sort'] ? trim(daddslashes($_POST['sort'])) : 'ASC';
	if(!$cid && $sort_type == 'sort') $sort_type = 'tid';

	$sort_type_arr = ['ASC','price','sales'];
	$sort_arr = ['DESC','ASC'];
	$orderBy = "sort ASC";
	
	  if ($conf['template_store_sort_type'] == 1) {
            $orderBy = "sort ASC";
        } else {
            $orderBy = "tid DESC";
        }
	$where = "active=1";
	if(!empty($kw)){
		$where .= " and name LIKE '%{$kw}%'";
	}
		time();
	$today = date("Y/m/d");
	$yesterday = date("Y-m-d",strtotime("-1 day"));
	$daybeforeyesterday = date('Y-m-d', strtotime('-2 day'));
	$time = $_POST['time'];
	if($time == 'today'){
		$where.=" and addtime >= '$today 00:00:00'";
	}
	if($time == 'yesterday'){
		$where.=" and addtime >= '$yesterday 00:00:00' AND addtime <= '$yesterday 23:59:59'";
	}
	if($time == 'daybeforeyesterday'){
		$where.=" and addtime >= '$daybeforeyesterday 00:00:00' AND addtime <= '$daybeforeyesterday 23:59:59'";
	}
	
	if(in_array($sort_type,$sort_type_arr) && in_array($sort,$sort_arr)){
		$orderBy = "{$sort_type} {$sort}"; 
	}
	$ic1=0;  $ic2=0;
	if($cid){
	   
		$where .= " and cid='$cid'";
			$data = array();	$curr_time = time();
		$rs1=$DB->query("SELECT * FROM shua_class WHERE cidr='$cid'");
			while($res1 = $rs1->fetch()){
			    $cc=$res1['cid'];
			     $icc1=$DB->getColumn("SELECT count(tid) FROM shua_tools WHERE cid='$cc'");
			     $ic1=$ic1+$icc1;
	   $erjiid=$res1['cid'];
	   
	    session_start();
	  if( $_SESSION['vtid']>1){
	      $tt= $_SESSION['vtid'];
	       $rs3=$DB->query("SELECT *,round(price,4) as price2 FROM shua_tools WHERE cid='$erjiid' AND tid<=$tt ORDER BY $orderBy LIMIT $page,$limit");}else{
	     $rs3=$DB->query("SELECT *,round(price,4) as price2 FROM shua_tools WHERE cid='$erjiid' ORDER BY $orderBy LIMIT $page,$limit");
	}
	  
	
	   while($res3 = $rs3->fetch(PDO::FETCH_ASSOC)){
	      
		if(isset($_SESSION['gift_id']) && isset($_SESSION['gift_tid']) && $_SESSION['gift_tid']==$res3['tid']){
			$price=$conf["cjmoney"]?$conf["cjmoney"]:0;
		}elseif(isset($price_obj)){
			$price_obj->setToolInfo($res3['tid'],$res3);
			if($price_obj->getToolDel($res3['tid'])==1)continue;
			$price=$price_obj->getToolPrice($res3['tid']);
		}else $price=$res3['price2'];


		$is_stock_err = 0;
		if($res3['is_curl']==4){
			$isfaka = 1;
			$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='{$res3['tid']}' AND orderid=0");
			if($count == 0) $is_stock_err = 1;
			$res3['input'] = getFakaInput();
		}elseif($res3['stock']!==null){
			$isfaka = 0;
			$count = $res3['stock'];
			if($count == 0) $is_stock_err = 1;
		}else{
			$isfaka = 0;
			$count = null;
		}
  
		$data[]=array('tid'=>$res3['tid'],'cid'=>$res3['cid'],'sort'=>$res3['sort'],'name'=>$res3['name'],'value'=>$res3['value'],'price'=>$price,'input'=>$res3['input'],'inputs'=>$res3['inputs'],'desc'=>$res3['desc'],'alert'=>$res3['alert'],'shopimg'=>$res3['shopimg'],'repeat'=>$res3['repeat'],'multi'=>$res3['multi'],'close'=>$res3['close'],'prices'=>$res3['prices'],'min'=>$res3['min'],'max'=>$res3['max'],'sales'=>$res3['sales'],'stock'=>$count,'isfaka'=>$isfaka,'addtime'=>strtotime($res3['addtime']),'is_stock_err'=>$is_stock_err,'active'=>$res3['active']);
         
	}
	}$jg=true;
	}else{
	    	$data = array();
	}

	$num=$DB->getColumn("SELECT count(tid) FROM shua_tools WHERE $where");
	//tid AND tid<=1860 正序改成>   倒序代为<  
	 session_start();
	  if( $_SESSION['vtid']>1){
	      $tt= $_SESSION['vtid'];
	$rs=$DB->query("SELECT * FROM shua_tools WHERE $where AND tid<=$tt ORDER BY $orderBy LIMIT $page,$limit");}else{
	    	$rs=$DB->query("SELECT * FROM shua_tools WHERE $where ORDER BY $orderBy LIMIT $page,$limit");
	}
	    
	


	while($res = $rs->fetch(PDO::FETCH_ASSOC)){
	    $ic2++;
		if(isset($_SESSION['gift_id']) && isset($_SESSION['gift_tid']) && $_SESSION['gift_tid']==$res['tid']){
			$price=$conf["cjmoney"]?$conf["cjmoney"]:0;
		}elseif(isset($price_obj)){
			$price_obj->setToolInfo($res['tid'],$res);
			if($price_obj->getToolDel($res['tid'])==1)continue;
			$price=$price_obj->getToolPrice($res['tid']);
		}else $price=$res['price'];


		$is_stock_err = 0;
		if($res['is_curl']==4){
			$isfaka = 1;
			$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='{$res['tid']}' AND orderid=0");
			if($count == 0) $is_stock_err = 1;
			$res['input'] = getFakaInput();
		}elseif($res['stock']!==null){
			$isfaka = 0;
			$count = $res['stock'];
			if($count == 0) $is_stock_err = 1;
		}else{
			$isfaka = 0;
			$count = null;
		}
        $w5=mb_substr($price,-1);
        $w4=mb_substr($price,-2);
        $w3=mb_substr($price,-3);
        if($w5==0){$price=number_format($price,4);}
        if($w4==0){$price=number_format($price,4);}
        if($w3==0){$price=number_format($price,4);}
        
		$data[]=array('tid'=>$res['tid'],'cid'=>$res['cid'],'sort'=>$res['sort'],'name'=>$res['name'],'value'=>$res['value'],'price'=>$price,'input'=>$res['input'],'inputs'=>$res['inputs'],'desc'=>$res['desc'],'alert'=>$res['alert'],'shopimg'=>$res['shopimg'],'repeat'=>$res['repeat'],'multi'=>$res['multi'],'close'=>$res['close'],'prices'=>$res['prices'],'min'=>$res['min'],'max'=>$res['max'],'sales'=>$res['sales'],'stock'=>$count,'isfaka'=>$isfaka,'addtime'=>strtotime($res['addtime']),'is_stock_err'=>$is_stock_err,'active'=>$res['active']);
         
	}
	  $count8c=$DB->getColumn("SELECT count(*) from shua_tools");
	  
	$num2=$ic1+$num;
	$pages = ceil($num2/$limit);


	$result=array("code"=>0,"msg"=>"succ","data"=>$data,"info"=>$info,'pages'=>$pages,'total'=>intval($num2));
	exit(json_encode($result));
	break;
	
case 'duanju':	
    $get = $_GET;
    $page = isset($get['page']) ? (int)$get['page'] : 1;
    $limit = 6; // 每页显示的数量
    $offset = ($page - 1) * $limit;

    $where = '`id` > 0';
    
    if(isset($get['keyword']) && !empty($get['keyword'])){
       $kw = $get['keyword'];
       $where .= " and name LIKE '%{$kw}%'";
    }
    
    if (isset($get['type']) && !empty($get['type'])){
        $type = $get['type'];
    }else{
        $type = 'hot';
    }  
    
    if($type == 'hot'){
        $where .= ' and `is_hot` = 1 ORDER BY `sort` ASC';
    }elseif($type == 'new'){
        $where .= ' and `id` > 0 ORDER BY `id` DESC';
    }else{
        $where .= " and `type` LIKE '%$type%' ORDER BY `sort` ASC";
    }
    
    
    // 查询数据库并限制返回结果数量
    $query = "SELECT * FROM `pre_videolist` WHERE " . $where . " LIMIT $offset, $limit";
    $result = $DB->query($query);
    
    $data = '';
    while ($row = $result->fetch()) {
        $title = mb_strimwidth($row['name'], 0, 20, '...', 'UTF-8'); 
        $description = mb_strimwidth($row['desc'], 0, 24, '...', 'UTF-8'); 
        $data .= '<div class="drama-item"> 
            <a href="/?mod=video&id='.$row['id'].'">
            <img src="' . $row['img'] . '"> </a> 
            <p class="title">' . $title . '</p>  
            <p class="description">' . $description . '</p> 
        </div>';
    }
    
    $result=array("code"=>0,"msg"=>"succ","data"=>$data);

	exit(json_encode($result));
    
break;   
	
}
function processOrder2($srow, $is_fenzhan = true)
{
    
	global $islogin2;
	global $DB;
	global $date;
	global $conf;
	
	if ($srow['tid'] == -4) {
	    
	    $tools = $DB->getRow("SELECT * FROM `pre_videolist` WHERE `id`='" . $srow['vid'] . "' LIMIT 1");
   	    $status = 1;
	    $cost = $tools['price'] * $srow['num'];
	    
	    if(strpos($srow['name'],'购买短剧地址') !== false){
	        $name = '购买短剧地址';
	        $input = $tools['download_url'];
	        $input3 = '';
	    }elseif(strpos($srow['name'],'购买短剧播放') !== false){
	        $name = '购买短剧播放';
	        $input = '';
	        if(empty($srow['input'])){
	            $input3 = 'all';
	        }else{
	            $input3 = $srow['input'];
	        }
	        
	    }
	    
	    $DB->exec("INSERT INTO `shua_orders` (`vid`,`zid`,`input`,`input2`,`input3`,`input4`,`input5`,`value`,`userid`,`addtime`,`tradeno`,`money`,`cost`,`status`,`djzt`) VALUES ('" . $srow['vid'] . "','" . $srow['zid'] . "','" . $input . "','" . $name . "','" . $input3 . "','" . addslashes($input[3]) . "','" . addslashes($input[4]) . "','" . $srow['num'] . "','" . $srow['userid'] . "','" . $date . "','" . $srow['trade_no'] . "','" . $srow['money'] . "','" . $cost . "','" . $status . "','" . ($tools['is_curl'] == 2 ? 2 : 0) . "')");
	    $orderid = $DB->lastInsertId();
	    if (!$orderid) {
		    return false;
    	}
	    if (!$orderid) {
		   return false;
	    }
	    if ($srow['userid'] > 1 && $srow['money'] > 0 && $is_fenzhan == true) {
		    $price_obj = new \lib\Price($srow['userid']);
		    if($name == '购买短剧地址'){
		        $price_obj->setVideoInfo($srow['vid'], $tools);
		        $price_obj->setVideoProfit($srow['vid'], $srow['num'], $tools['name'], $srow['money'], $orderid, $srow['userid']);
		    }elseif($name == '购买短剧播放'){
		        if($input3 == 'all'){
		            $price_obj->setbfVideoInfo($srow['vid'], $tools);
		            $price_obj->setbfVideoProfit($srow['vid'], $srow['num'], $tools['name'], $srow['money'], $orderid, $srow['userid']);
		        }else{
		            $tools = $DB->getRow("SELECT * FROM `pre_video` WHERE `id`='" . $input3 . "' LIMIT 1");
		            $price_obj->setdbVideoInfo($input3, $tools);
		            $price_obj->setdbVideoProfit($input3, $srow['num'], $tools['name'], $srow['money'], $orderid, $srow['userid']);
		        }
		        
		    }
		    
    	}
	    
	    return $orderid;
	}
	
	$input = explode("|", $srow['input']);
	if ($srow['tid'] == -1) {
		$zid = intval($srow['input']);
		changeUserMoney($zid, $srow['money'], true, "充值", "您在线充值了" . $srow['money'] . "元余额");
		if ($conf['fenzhan_gift']) {
			$fenzhan_gift = explode('|', $conf['fenzhan_gift']);
			$fenzhan_gift_arr = array();
			foreach ($fenzhan_gift as $row) {
				$arr = explode(':', $row);
				$fenzhan_gift_arr[$arr[0]] = $arr[1];
			}
			krsort($fenzhan_gift_arr);
			foreach ($fenzhan_gift_arr as $key => $value) {
				if ($srow['money'] >= $key) {
					$money = round($value, 2);
				}
			}
			if ($money < $srow['money'] && $money > 0) {
				changeUserMoney($zid, $money, true, "返利", "您在线充值了" . $srow['money'] . "元余额，本次返利" . $money . "元已到账，感谢充值！");
			}
		}
		return true;
	}
	if ($srow['tid'] == -2) {
		$type = addslashes($input[0]);
		if ($type == "update") {
			$zid = intval($input[1]);
			$kind = intval($input[2]);
			$domain = addslashes($input[3]);
			$sitename = addslashes($input[4]);
			$endtime = addslashes($input[5]);
			$upzid = intval($srow['zid']);
			$fenzhan_free = $conf['fenzhan_free'] && $srow['money'] > $conf['fenzhan_free'] ? $conf['fenzhan_free'] : 0;
			$title = addslashes($conf['title']);
			$keywords = addslashes($conf['keywords']);
			$description = addslashes($conf['description']);
			if ($conf['fenzhan_html'] == 1) {
				$anounce = addslashes($conf['anounce']);
				$alert = addslashes($conf['alert']);
			}
			$sql="UPDATE `shua_site` SET `power`=:power,`domain`=:domain,`sitename`=:sitename,`title`=:title,`keywords`=:keywords,`description`=:description,`anounce`=:anounce,`alert`=:alert,`endtime`=:endtime WHERE `zid`=:zid";
			$data = [':power'=>$kind, ':domain'=>$domain, ':sitename'=>$sitename, ':title'=>$title, ':keywords'=>$keywords, ':description'=>$description, ':anounce'=>$anounce, ':alert'=>$alert, ':endtime'=>$endtime, ':zid'=>$zid];
			$DB->exec($sql, $data);
		} else {
			$kind = intval($input[1]);
			$domain = addslashes($input[2]);
			$user = addslashes($input[3]);
			$pwd = addslashes($input[4]);
			$sitename = addslashes($input[5]);
			$qq = addslashes($input[6]);
			$endtime = addslashes($input[8]);
			$upzid = intval($srow['zid']);
			$fenzhan_free = $conf['fenzhan_free'] && $srow['money'] > $conf['fenzhan_free'] ? $conf['fenzhan_free'] : 0;
			$keywords = addslashes($conf['keywords']);
			$description = addslashes($conf['description']);
			if ($conf['fenzhan_html'] == 1) {
				$anounce = addslashes($conf['anounce']);
				$alert = addslashes($conf['alert']);
			}
			$sql="INSERT INTO `shua_site` (`upzid`,`power`,`domain`,`domain2`,`user`,`pwd`,`giftcode`,`imvitecode`,`rmb`,`qq`,`sitename`,`title`,`keywords`,`description`,`anounce`,`alert`,`kfqq`,`addtime`,`endtime`,`status`) VALUES (:upzid, :power, :domain, NULL, :user, :pwd, :giftcode, :invitecode, :rmb, :qq, :sitename, :title, :keywords, :description, :anounce, :alert, :kfqq, NOW(), :endtime, 1)";
			$data = [':upzid'=>$upzid, ':power'=>$kind, ':domain'=>$domain, ':user'=>$user, ':pwd'=>$pwd, ':giftcode'=>random(8), ':invitecode'=>random(8), ':rmb'=>$fenzhan_free, ':qq'=>$qq, ':sitename'=>$sitename, ':title'=>$conf['title'], ':keywords'=>$keywords, ':description'=>$description, ':anounce'=>$anounce, ':alert'=>$alert, ':kfqq'=>$qq, ':endtime'=>$endtime];
			$DB->exec($sql, $data);
			$zid = $DB->lastInsertId();
			
			if (strlen($srow['userid']) == 32) {
				$DB->exec("update `shua_orders` set `userid`='" . $zid . "' where `userid`='" . $srow['userid'] . "'");
			}
		}
		if ($fenzhan_free > 0) {
			addPointRecord($zid, $fenzhan_free, "赠送", "您首次开通分站获赠" . $fenzhan_free . "元余额");
		}
		if ($srow['zid'] > 1) {
    $siterow = $DB->getColumn("SELECT power FROM shua_site WHERE zid='" . $srow['zid'] . "' limit 1");
    if ($siterow == 2 && $kind == 1) {
        $money = round($srow['money'] - $fenzhan_free, 2);
        fx($money,$srow['zid']);
        changeUserMoney($srow['zid'], $money*0.9, true, "提成", "您网站的用户开通分站获得" . $money*0.9 . "元提成");                         
    } else {
        if ($kind == 1 && $conf['fenzhan_cost'] > 0 && $srow['money'] > $conf['fenzhan_cost']) {
            $money = round($srow['money'] - $conf['fenzhan_cost'], 2);
            fx($money,$srow['zid']);
            changeUserMoney($srow['zid'], $money*0.9, true, "提成", "您网站的用户开通分站获得" . $money*0.9 . "元提成");
        } else {
            if ($kind == 2 && $conf['fenzhan_cost2'] > 0 && $srow['money'] > $conf['fenzhan_cost2']) {
                $money = round($srow['money'] - $conf['fenzhan_cost2'], 2);
                fx($money,$srow['zid']);
                changeUserMoney($srow['zid'], $money*0.9, true, "提成", "您网站的用户开通分站获得" . $money*0.9 . "元提成");
            } else {
               // $siterow为1的情况下，添加以下代码
           // 初始化父站点ID为0，当前站点ID为用户的 zid
$parent_zid = 0;
$current_zid = $srow['zid'];

// 当前站点ID不是根站点且未找到父站点时，不断查询上级站点
while ($current_zid > 1 && $parent_zid == 0) {

    // 查询当前站点的信息
    $site_info = $DB->getRow("SELECT * FROM shua_site WHERE zid='" . $current_zid . "' LIMIT 1");

    // 如果当前站点为父站点
    if ($site_info && $site_info['power'] == 2) {
        // 将当前站点的ID作为父站点ID
        $parent_zid = $site_info['zid'];
    }

    // 将当前站点的父级站点ID作为下一轮查询的站点ID
    $current_zid = $site_info['upzid'];
}

// 如果找到了父站点
if ($parent_zid > 0) {
    // 计算当前站点的收入的90%金额
    $money = round($srow['money'] * 0.9, 2);

    // 向父站点转账并添加收益通知
    changeUserMoney($parent_zid, $money, true, "提成", "您下级普及站点的用户开通分站获得" . $money . "元提成");
}
            }
        }
    }
}
		return true;
	}
	if ($srow['tid'] == -3) {
		$cart_num = count($input);
		for ($i = 0; $i < $cart_num; $i++) {
			$cart_id = $input[$i];
			if (intval($cart_id) < 1) {
				continue;
			}
			$cart_item = $DB->getRow("SELECT * FROM `shua_cart` WHERE `id`='" . $cart_id . "' LIMIT 1");
			if ($cart_item && $cart_item['input']) {
				if ($cart_id > 0 && $cart_num > 0) {
					$cart_item = $DB->getRow("SELECT * FROM `shua_cart` WHERE `id`='" . $cart_id . "' LIMIT 1");
					$tools = $DB->getRow("SELECT * FROM `shua_tools` WHERE `tid`='" . $cart_item['tid'] . "' LIMIT 1");
					$input = explode("|", $cart_item['input']);
					$cost = $tools['price'] * $cart_item['num'];
					$DB->exec("INSERT INTO `shua_orders` (`tid`,`zid`,`input`,`input2`,`input3`,`input4`,`input5`,`value`,`userid`,`addtime`,`tradeno`,`money`,`cost`,`status`,`djzt`) VALUES ('" . $cart_item['tid'] . "','" . $srow['zid'] . "','" . addslashes($input[0]) . "','" . addslashes($input[1]) . "','" . addslashes($input[2]) . "','" . addslashes($input[3]) . "','" . addslashes($input[4]) . "','" . $cart_item['num'] . "','" . $srow['userid'] . "','" . $date . "','cart" . $srow['trade_no'] . "','" . $cart_item['money'] . "','" . $cost . "','0','" . ($tools['is_curl'] == 2 ? 2 : 0) . "')");
					$orderid = $DB->lastInsertId();
					if (do_goods($orderid)) {
						$DB->exec("UPDATE `shua_cart` SET `endtime`='" . $date . "',`status`='1' WHERE `id`='" . $cart_id . "'");
						$invitelog_row = $DB->getRow("SELECT * FROM `shua_invitelog` WHERE `id` = '" . $srow['inviteid'] . "' LIMIT 1");
						$invite_row = $DB->getRow("SELECT * FROM `shua_invite` WHERE `id` = '" . $invitelog_row['iid'] . "' LIMIT 1");
						$inviteshop_row = $DB->getRow("SELECT * FROM `shua_inviteshop` WHERE `id` = '" . $invite_row['nid'] . "' LIMIT 1");
						if ($srow['inviteid'] > 0 && $conf['invite_tid'] && ($inviteshop_row['value'] > 0 && $srow['money'] >= $inviteshop_row['value'])) {
							if ($invitelog_row && $invitelog_row['status'] == 0 && $invite_row && $invite_row['status'] == 0 && $inviteshop_row && $invite_row['active'] == 1) {
								$qq = $DB->getColumn("SELECT qq FROM `shua_invite` WHERE `id` = '" . $invitelog_row['nid'] . "' LIMIT 1");
								$DB->exec("INSERT INTO `shua_orders` (`tid`,`zid`,`input`,`value`,`status`,`djzt`,`money`,`cost`,`tradeno`,`addtime`,`endtime`) VALUES (" . $inviteshop_row['tid'] . ",'" . $srow['zid'] . "','" . $qq . "',1,0,2,'0','0','invite" . $srow['trade_no'] . "','" . $date . "','" . $date . "')");
								$invite_orderid = $DB->lastInsertId();
								do_goods($invite_orderid);
								if ($inviteshop_row['times'] == 0) {
									$DB->exec("UPDATE `shua_invite` SET `status` = '1' WHERE `id` = '" . $invitelog_row['id'] . "'");
								}
								$DB->exec("UPDATE `shua_invitelog` SET `orderid` = '" . $invite_orderid . "',`status` = 1 WHERE `id` = '" . $srow['inviteid'] . "'");
							}
						}
					}
				}
			}
		}
		return true;
	}
	
	$tools = $DB->getRow("SELECT * FROM `shua_tools` WHERE `tid`='" . $srow['tid'] . "' LIMIT 1");
	$status = 0;
	$cost = $tools['price'] * $srow['num'];
	$DB->exec("INSERT INTO `shua_orders` (`tid`,`zid`,`input`,`input2`,`input3`,`input4`,`input5`,`value`,`userid`,`addtime`,`tradeno`,`money`,`cost`,`status`,`djzt`) VALUES ('" . $srow['tid'] . "','" . $srow['zid'] . "','" . addslashes($input[0]) . "','" . addslashes($input[1]) . "','" . addslashes($input[2]) . "','" . addslashes($input[3]) . "','" . addslashes($input[4]) . "','" . $srow['num'] . "','" . $srow['userid'] . "','" . $date . "','" . $srow['trade_no'] . "','" . $srow['money'] . "','" . $cost . "','" . $status . "','" . ($tools['is_curl'] == 2 ? 2 : 0) . "')");
	$orderid = $DB->lastInsertId();
	if (!$orderid) {
		return false;
	}
	if ($srow['zid'] > 1 && $srow['money'] > 0 && $is_fenzhan == true) {
		$price_obj = new \lib\Price($srow['zid']);
		$price_obj->setToolInfo($srow['tid'], $tools);
		$price_obj->setToolProfit($srow['tid'], $srow['num'], $tools['name'], $srow['money'], $orderid, $srow['userid']);
	}
	$num = $tools['value'] * $srow['num'];
	if ($num <= 0) {
		$num = 1;
	}
	if ($tools['is_curl'] == 1) {
		$result = do_curl($tools['curl'], $input, $num, $tools['name'], $tools['money'], $orderid, $tools['goods_param']);
		if ($result = json_decode($result, true)) {
			if ($result['code'] == 0) {
				$status = 1;
			} else {
				$status = 0;
			}
		} else {
			$status = 1;
		}
		$param = "url:" . $tools['curl'] . " data:" . http_build_query($input);
		log_result("自动访问URL", $param, $result, 0);
	} else {
		if ($tools['is_curl'] == 2 && $srow['blockdj'] == 0) {
			$inputsname = $tools['inputs'] ? $tools['input'] . "|" . $tools['inputs'] : $tools['input'];
			$shequ = $DB->getRow("SELECT * FROM `shua_shequ` WHERE `id`='" . $tools['shequ'] . "' limit 1");
			if ($shequ && $shequ['type'] && $shequ['username'] && $shequ['password']) {
				$result = third_call($shequ['type'], $shequ, 'do_goods', [$tools['goods_id'], $tools['goods_type'], $tools['goods_param'], $num, $input, $srow['money'], $srow['trade_no'], $inputsname]);
				$param = $shequ['type'] . ":" . $tools['shequ'] . " goods_id:" . $tools['goods_id'] . " num:" . $num . " data:" . http_build_query($input);
				if ($result['faka'] == true) {
					$kmdata = '';
					foreach ($result['kmdata'] as $km_arr) {
						$DB->query("INSERT INTO `shua_faka` (`tid`,`km`,`pw`,`orderid`,`addtime`,`usetime`) VALUES ('" . $srow['tid'] . "','" . $km_arr['card'] . "','" . $km_arr['pass'] . "','" . $orderid . "',NOW(),NOW())");
						if (!empty($km_arr['pass'])) {
							$kmdata = $kmdata . ("卡号：" . $km_arr['card'] . " 密码：" . $km_arr['pass'] . "<br/>");
						} else {
							$kmdata = $kmdata . ($km_arr['card'] . "<br/>");
						}
					}
					$DB->exec("UPDATE `shua_orders` SET `status`='1',`djzt`='3',`result`='" . $kmdata . "',`djorder`='" . $result['id'] . "' WHERE `id`='" . $orderid . "'");
					if (!empty($kmdata)) {
						if (is_numeric($input[0]) && strlen($input[0]) <= 10) {
							$to = $input[0] . "@qq.com";
						} else {
							if (strpos($input[0], "@")) {
								$to = $input[0];
							}
						}
						if (checkEmail($to)) {
							$sub = $conf['sitename'] . " 卡密购买提醒";
							$msg = $conf['faka_mail'];
							$msg = str_replace("[kmdata]", $kmdata, $msg);
							$msg = str_replace("[alert]", $tools['desc'], $msg);
							$msg = str_replace("[name]", $tools['name'], $msg);
							$msg = str_replace("[date]", $date, $msg);
							$msg = str_replace("[email]", $to, $msg);
							$msg = str_replace("[domain]", $_SERVER['HTTP_HOST'], $msg);
							$msg = str_replace("[sitename]", $conf['sitename'], $msg);
							send_mail($to, $sub, $msg);
						}
					}
				}
				log_result("社区对接", $param, $result, 0);
				if ($result['code'] == 0) {
					if (!strpos($_SERVER['PHPRC'], "phpStudy") && $shequ['status'] == 0) {
						$DB->exec("UPDATE `shua_shequ` SET status=1 WHERE `id`='" . $shequ['id'] . "'");
					}
					$status = $shequ['result'] ? $shequ['result'] : 1;
				} else {
					if ($conf['message_duijie'] == 1) {
						\lib\MessageSend::orderbuy_fail($tools['name'], $tools['input'], $tools['inputs'], $input, $srow['money'], $num, $srow['type'], 0, $param, $result);
					}
					$status = 0;
				}
			} else {
				$status = 0;
			}
		} else {
			if ($tools['is_curl'] == 3) {
				\lib\MessageSend::orderbuy($tools['name'], $tools['input'], $tools['inputs'], $input, $srow['money'], $num, $srow['type'], 0);
			} else {
				if ($tools['is_curl'] == 4) {
					$limit = $srow['num'];
					$rs = $DB->query("SELECT * FROM shua_faka WHERE `tid`='" . $srow['tid'] . "' AND orderid=0 LIMIT " . $limit . '');
					$kmdata = '';
					while ($res = $rs->fetch()) {
						if (!empty($res['pw'])) {
							$kmdata = $kmdata . ("卡号：" . $res['km'] . " 密码：" . $res['pw'] . "<br/>");
						} else {
							$kmdata = $kmdata . ($res['km'] . "<br/>");
						}
						$DB->exec("UPDATE `shua_faka` SET `orderid`='" . $orderid . "',`usetime`='" . $date . "' WHERE `kid`='" . $res['kid'] . "'");
					}
					if (is_numeric($input[0]) && strlen($input[0]) <= 10) {
						$to = $input[0] . "@qq.com";
					} else {
						if (strpos($input[0], "@")) {
							$to = $input[0];
						}
					}
					if (!empty($kmdata) && $to) {
						$status = 1;
						$DB->exec("UPDATE `shua_orders` SET `status`='1',`result`='" . $kmdata . "',`djzt`='3' WHERE `id`='" . $orderid . "'");
						$sub = $conf['sitename'] . " 卡密购买提醒";
						$msg = $conf['faka_mail'];
						$msg = str_replace("[kmdata]", $kmdata, $msg);
						$msg = str_replace("[alert]", $tools['alert'], $msg);
						$msg = str_replace("[name]", $tools['name'], $msg);
						$msg = str_replace("[date]", $date, $msg);
						$msg = str_replace("[email]", $to, $msg);
						$msg = str_replace("[domain]", $_SERVER['HTTP_HOST'], $msg);
						$msg = str_replace("[sitename]", $conf['sitename'], $msg);
						if (checkEmail($to)) {
							send_mail($to, $sub, $msg);
						}
					} else {
						$status = 0;
						$DB->exec("UPDATE `shua_orders` SET `status`='0',`djzt`='4' WHERE `id`='" . $orderid . "'");
					}
				} else {
					if ($tools['is_curl'] == 5) {
						$DB->exec("UPDATE `shua_orders` SET `status`='1',`djzt`='3',`result`='" . $tools['showcontent'] . "' WHERE `id`='" . $orderid . "'");
					}
				}
			}
		}
	}
	if ($tools['is_curl'] != 3) {
		\lib\MessageSend::orderbuy($tools['name'], $tools['input'], $tools['inputs'], $input, $srow['money'], $num, $srow['type'], $status);
	}
	if ($status > 0 && (!$result['faka']) || ($tools['is_curl'] != 4)) {
		$DB->exec("update `shua_orders` set `status`='" . $status . "',`djzt`='1',`djorder`='" . $result['id'] . "' where `id`='" . $orderid . "'");
	}

	$invitelog_row = $DB->getRow("SELECT * FROM `shua_invitelog` WHERE `id` = '" . $srow['inviteid'] . "' LIMIT 1");
	$invite_row = $DB->getRow("SELECT * FROM `shua_invite` WHERE `id` = '" . $invitelog_row['iid'] . "' LIMIT 1");
	$inviteshop_row = $DB->getRow("SELECT * FROM `shua_inviteshop` WHERE `id` = '" . $invite_row['nid'] . "' LIMIT 1");
	if ($srow['inviteid'] > 0 && $conf['invite_tid'] && ($inviteshop_row['value'] > 0 && $srow['money'] >= $inviteshop_row['value'])) {
		if ($invitelog_row && $invitelog_row['status'] == 0 && $invite_row && $invite_row['status'] == 0 && $inviteshop_row && $invite_row['active'] == 1) {
			$qq = $DB->getColumn("SELECT qq FROM `shua_invite` WHERE `id` = '" . $invitelog_row['nid'] . "' LIMIT 1");
			$DB->exec("INSERT INTO `shua_orders` (`tid`,`zid`,`input`,`value`,`status`,`djzt`,`money`,`cost`,`tradeno`,`addtime`,`endtime`) VALUES (" . $inviteshop_row['tid'] . ",'" . $srow['zid'] . "','" . $qq . "',1,0,2,'0','0','invite" . $srow['trade_no'] . "','" . $date . "','" . $date . "')");
			$invite_orderid = $DB->lastInsertId();
			do_goods($invite_orderid);
			if ($inviteshop_row['times'] == 0) {
				$DB->exec("UPDATE `shua_invite` SET `status` = '1' WHERE `id` = '" . $invitelog_row['iid'] . "'");
			}
			$DB->exec("UPDATE `shua_invitelog` SET `orderid` = '" . $invite_orderid . "',`status` = 1 WHERE `id` = '" . $srow['inviteid'] . "'");
		}
	}
	return $orderid;
}
function fx($money,$zid){
    global $DB;
    $row=$DB->getRow("SELECT * FROM shua_fxbl WHERE id=1 LIMIT 1"); 	  ///取出比例
		/*第一次寻找上级*/
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$zid");
		$userid = $userid_up['zid'];  //给值到userid  下面开始循环第二层
        for ($i=0; $i<=20; $i++){
		if($userid > 0){
		$fxbl_lv = $row["lv$i"] / 100; //循环等级取出佣金比例	
		$fxbl_money = $money*0.1 *  $fxbl_lv;
		$fxbl_money=round($fxbl_money, 2);
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid"); //查询当前用户的信息
		if($userid_up['power'] == 2){  //判断是否有上级且是否为合伙人
		changeUserMoney($userid, $fxbl_money ,true,'提成', '您的团队有人开通站点,获得'.$fxbl_money.'元提成');	
		}
		$userid = $userid_up['upzid'];
		if(!$userid){
		 break;   
		}
    }
  }
}