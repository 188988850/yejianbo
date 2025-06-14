<?php
namespace lib;

class Price {
	private $zid;
	private $upzid;
	private $power;
	private $user;
	private $price_array = array();
	private $up_price_array = array();
	private $iprice_array = array();
	private $vprice_array = array();
	private $vbprice_array = array();
	private $tool = array();
	private static $price_rules;

	public function __construct($zid,$siterow=null){
		global $DB;
		if($zid == 1)return;
		if(!$siterow)$siterow=$this->getSiteInfo($zid);
		$this->endtime = $siterow['endtime'];
		if($siterow['power']==2){
			$this->zid = $zid;
			$this->power = $siterow['power'];
			$this->price_array = @unserialize($siterow['price']);
			$this->iprice_array = @unserialize($siterow['iprice']);
			$this->vprice_array = @unserialize($siterow['vprice']);
			$this->vbprice_array = @unserialize($siterow['vbprice']);
			$this->vdbprice_array = @unserialize($siterow['vbprice']);
		}elseif($siterow['power']==1){
			$this->zid = $zid;
			$this->power = $siterow['power'];
			$this->price_array = @unserialize($siterow['price']);
			$this->iprice_array = @unserialize($siterow['iprice']);
			$this->vprice_array = @unserialize($siterow['vprice']);
			$this->vbprice_array = @unserialize($siterow['vbprice']);
			$this->vdbprice_array = @unserialize($siterow['vbprice']);
			if($data = $DB->getRow("SELECT zid,price FROM shua_site WHERE zid='{$siterow['upzid']}' AND power=2 LIMIT 1")){
				$this->up_price_array = @unserialize($data['price']);
				$this->up_vprice_array = @unserialize($data['vprice']);
				$this->up_vbprice_array = @unserialize($data['vbprice']);
				$this->up_vdbprice_array = @unserialize($data['vbprice']);
				$this->upzid=$data['zid'];
			}
		}elseif($siterow['power']==0){
			$this->user = true;
			if($data = $DB->getRow("SELECT zid,upzid,power,price FROM shua_site WHERE zid='{$siterow['upzid']}' LIMIT 1")){
				$this->zid = $data['zid'];
				$this->power = $data['power'];
				$this->price_array = @unserialize($data['price']);
				$this->iprice_array = @unserialize($data['iprice']);
				$this->vprice_array = @unserialize($siterow['vprice']);
				$this->vbprice_array = @unserialize($siterow['vbprice']);
				$this->vdbprice_array = @unserialize($siterow['vbprice']);
				if($this->power == 1 && $data['upzid']>1 && $data = $DB->getRow("SELECT zid,price FROM shua_site WHERE zid='{$data['upzid']}' and power=2 limit 1")){
					$this->up_price_array = @unserialize($data['price']);
					$this->up_vprice_array = @unserialize($data['vprice']);
					$this->up_vbprice_array = @unserialize($data['vbprice']);
					$this->up_vdbprice_array = @unserialize($data['vbprice']);
					$this->upzid=$data['zid'];
				}
			}
		}
	}
	public function setToolInfo($tid,$row=null){
		global $DB,$CACHE;
		
		if(!$row)$row=$this->getToolInfo($tid);
		if($row['prid']==0){ //不加价
		}elseif($price_rules = $this->getPriceRules($row['prid'])){ //应用加价模板
			$price = $row['price'];
			$row['price'] = round($price_rules['kind']==1?$price+$price_rules['p_0']:$price*$price_rules['p_0'], 2);
			$row['cost'] = round($price_rules['kind']==1?$price+$price_rules['p_1']:$price*$price_rules['p_1'], 2);
			$row['cost2'] = round($price_rules['kind']==1?$price+$price_rules['p_2']:$price*$price_rules['p_2'], 2);
		}else{ //对应加价模板被删除
			$row['cost'] = $row['price'];
			$row['cost2'] = $row['price'];
		}
		//应用自定义密价
		if($this->power==1 && $this->iprice_array[$tid]>0){
			$row['cost'] = $this->iprice_array[$tid];
		}elseif($this->power==2 && $this->iprice_array[$tid]>0){
			$row['cost2'] = $this->iprice_array[$tid];
		}
		
		$this->tool=$row;
	}
	
	
	public function setVideoInfo($vid,$row=null){
		global $DB,$CACHE;
		
		if(!$row)$row=$this->getVideoInfo($vid);
		if($row['prid']==0){ //不加价
		}elseif($price_rules = $this->getPriceRules($row['prid'])){ //应用加价模板
			$price = $row['price'];
			$row['price'] = round($price_rules['kind']==1?$price+$price_rules['p_0']:$price*$price_rules['p_0'], 2);
			$row['cost'] = round($price_rules['kind']==1?$price+$price_rules['p_1']:$price*$price_rules['p_1'], 2);
			$row['cost2'] = round($price_rules['kind']==1?$price+$price_rules['p_2']:$price*$price_rules['p_2'], 2);
		}else{ //对应加价模板被删除
			$row['cost'] = $row['price'];
			$row['cost2'] = $row['price'];
		}
		
		$this->tool=$row;
	}
	
	public function setdbVideoInfo($vid,$row=null){
		global $DB,$CACHE;
		
		if(!$row)$row=$this->getdbVideoInfo($vid);
		if($row['prid']==0){ //不加价
		}elseif($price_rules = $this->getdbPriceRules($row['prid'])){ //应用加价模板
			$price = $row['price'];
			$row['price'] = round($price_rules['kind']==1?$price+$price_rules['p_0']:$price*$price_rules['p_0'], 2);
			$row['cost'] = round($price_rules['kind']==1?$price+$price_rules['p_1']:$price*$price_rules['p_1'], 2);
			$row['cost2'] = round($price_rules['kind']==1?$price+$price_rules['p_2']:$price*$price_rules['p_2'], 2);
		}else{ //对应加价模板被删除
			$row['cost'] = $row['price'];
			$row['cost2'] = $row['price'];
		}
		
		$this->tool=$row;
	}
	
	public function setbfVideoInfo($vid,$row=null){
		global $DB,$CACHE;
		
		if(!$row)$row=$this->getbfVideoInfo($vid);
		if($row['prid']==0){ //不加价
		}elseif($price_rules = $this->getPriceRules($row['prid'])){ //应用加价模板
			$price = $row['price'];
			$row['bfprice'] = round($price_rules['kind']==1?$price+$price_rules['p_0']:$price*$price_rules['p_0'], 2);
			$row['bfcost'] = round($price_rules['kind']==1?$price+$price_rules['p_1']:$price*$price_rules['p_1'], 2);
			$row['bfcost2'] = round($price_rules['kind']==1?$price+$price_rules['p_2']:$price*$price_rules['p_2'], 2);
		}else{ //对应加价模板被删除
			$row['bfcost'] = $row['bfprice'];
			$row['bfcost2'] = $row['bfprice'];
		}
		
		$this->tool=$row;
	}
	
	
	
	public function getMainPrice(){
		return $this->tool['price'];
	}
	public function getMainCost(){
		return $this->tool['cost'];
	}
	public function getMainCost2(){
		return $this->tool['cost2'];
	}
	
	public function getToolPrice($tid){
		global $islogin2,$conf,$date;
		if($islogin2==1){
			if($this->user==true && $conf['user_level']==1){
				return $this->getToolCost($tid);
			}elseif($this->user==true || $conf['fenzhan_expiry']>0 && $this->endtime<$date){
			}elseif($this->power==1){
				return $this->getToolCost($tid);
			}elseif($this->power==2){
				return $this->getToolCost2($tid);
			}
		}
		$cost = $this->getToolCost($tid);
		if($this->price_array[$tid]['price'] && $this->price_array[$tid]['price']>=$cost && $cost>0){
			$price=$this->price_array[$tid]['price'];
		}elseif($this->up_price_array[$tid]['price'] && $this->up_price_array[$tid]['price']>=$cost && $cost>0){
			$price = $this->up_price_array[$tid]['price'];
		}elseif($cost>0 && $cost>$this->tool['price']){
			$price=$cost;
		}else{
			$price=$this->tool['price'];
		}
		return $price;
	}
	public function getToolCost($tid){
		$cost2 = $this->getToolCost2($tid);
		if($this->power<2 && $this->up_price_array[$tid]['cost'] && $this->up_price_array[$tid]['cost']>=$cost2){
			$cost = $this->up_price_array[$tid]['cost'];
		}elseif($this->power==2 && $this->price_array[$tid]['cost'] && $this->price_array[$tid]['cost']>=$cost2){
			$cost = $this->price_array[$tid]['cost'];
		}elseif($this->tool['cost']>0){
			$cost = $this->tool['cost'];
		}else{
			$cost = $this->tool['price'];
		}
		return $cost;
	}
	public function getToolCost2($tid){
		if($this->tool['cost2']>0){
			$cost = $this->tool['cost2'];
		}elseif($this->tool['cost']>0){
			$cost = $this->tool['cost'];
		}else{
			$cost = $this->tool['price'];
		}
		return $cost;
	}
	
	
	public function getVideoPrice($vid){
		global $islogin2,$conf,$date;
		$ret = null;
		if($islogin2==1){
			if($this->user==true && $conf['user_level']==1){
				$ret = $this->getVideoCost($vid);
			}elseif($this->user==true || $conf['fenzhan_expiry']>0 && $this->endtime<$date){
			}elseif($this->power==1){
				$ret = $this->getVideoCost($vid);
			}elseif($this->power==2){
				$ret = $this->getVideoCost2($vid);
			}
		}
		if($ret === null){
			$cost = $this->getVideoCost($vid);
			if($this->vprice_array[$vid]['price'] && $this->vprice_array[$vid]['price']>=$cost && $cost>0){
				$ret=$this->vprice_array[$vid]['price'];
			}elseif($this->up_vprice_array[$vid]['price'] && $this->up_vprice_array[$vid]['price']>=$cost && $cost>0){
				$ret = $this->up_vprice_array[$vid]['price'];
			}elseif($cost>0 && $cost>$this->tool['price']){
				$ret=$cost;
			}else{
				$ret=$this->tool['price'];
			}
		}
		// 调试输出
		error_log("[PriceDebug] getVideoPrice vid=$vid power={$this->power} price={$ret}");
		return $ret;
	}
	
	public function getVideoCost($vid){
		$cost2 = $this->getVideoCost2($vid);
		if($this->power<2 && $this->up_vprice_array[$vid]['cost'] && $this->up_vprice_array[$vid]['cost']>=$cost2){
			$cost = $this->up_vprice_array[$vid]['cost'];
		}elseif($this->power==2 && $this->vprice_array[$vid]['cost'] && $this->vprice_array[$vid]['cost']>=$cost2){
			$cost = $this->vprice_array[$vid]['cost'];
		}elseif($this->tool['cost']>0){
			$cost = $this->tool['cost'];
		}else{
			$cost = $this->tool['price'];
		}
		return $cost;
	}
	public function getVideoCost2($vid){
		if($this->tool['cost2']>0){
			$cost = $this->tool['cost2'];
		}elseif($this->tool['cost']>0){
			$cost = $this->tool['cost'];
		}else{
			$cost = $this->tool['price'];
		}
		return $cost;
	}
	
	
	public function getdbVideoPrice($vid){
		global $islogin2,$conf,$date;
		$ret = null;
		if($islogin2==1){
			if($this->user==true && $conf['user_level']==1){
				$ret = $this->getdbVideoCost($vid);
			}elseif($this->user==true || $conf['fenzhan_expiry']>0 && $this->endtime<$date){
			}elseif($this->power==1){
				$ret = $this->getdbVideoCost($vid);
			}elseif($this->power==2){
				$ret = $this->getdbVideoCost2($vid);
			}
		}
		if($ret === null){
			$cost = $this->getdbVideoCost($vid);
			if($this->vdbprice_array[$vid]['price'] && $this->vdbprice_array[$vid]['price']>=$cost && $cost>0){
				$ret=$this->vdbprice_array[$vid]['price'];
			}elseif($this->up_vdbprice_array[$vid]['price'] && $this->up_vdbprice_array[$vid]['price']>=$cost && $cost>0){
				$ret = $this->up_vdbprice_array[$vid]['price'];
			}elseif($cost>0 && $cost>$this->tool['price']){
				$ret=$cost;
			}else{
				$ret=$this->tool['price'];
			}
		}
		// 调试输出
		error_log("[PriceDebug] getdbVideoPrice vid=$vid power={$this->power} price={$ret}");
		return $ret;
	}
	
	public function getdbVideoCost($vid){
		$cost2 = $this->getdbVideoCost2($vid);
		if($this->power<2 && $this->up_vdbprice_array[$vid]['cost'] && $this->up_vdbprice_array[$vid]['cost']>=$cost2){
			$cost = $this->up_vdbprice_array[$vid]['cost'];
		}elseif($this->power==2 && $this->vdbprice_array[$vid]['cost'] && $this->vdbprice_array[$vid]['cost']>=$cost2){
			$cost = $this->vdbprice_array[$vid]['cost'];
		}elseif($this->tool['cost']>0){
			$cost = $this->tool['cost'];
		}else{
			$cost = $this->tool['price'];
		}
		return $cost;
	}
	public function getdbVideoCost2($vid){
		if($this->tool['cost2']>0){
			$cost = $this->tool['cost2'];
		}elseif($this->tool['cost']>0){
			$cost = $this->tool['cost'];
		}else{
			$cost = $this->tool['price'];
		}
		return $cost;
	}
	
	
		public function getbfVideoPrice($vid){
		global $islogin2,$conf,$date;
		$ret = null;
		if($islogin2==1){
			if($this->user==true && $conf['user_level']==1){
				$ret = $this->getbfVideoCost($vid);
			}elseif($this->user==true || $conf['fenzhan_expiry']>0 && $this->endtime<$date){
			}elseif($this->power==1){
				$ret = $this->getbfVideoCost($vid);
			}elseif($this->power==2){
				$ret = $this->getbfVideoCost2($vid);
			}
		}
		if($ret === null){
			$cost = $this->getbfVideoCost($vid);
			if($this->vbprice_array[$vid]['bfprice'] && $this->vbprice_array[$vid]['bfprice']>=$cost && $cost>0){
				$ret=$this->vbprice_array[$vid]['bfprice'];
			}elseif($this->up_vbprice_array[$vid]['bfprice'] && $this->up_vbprice_array[$vid]['bfprice']>=$cost && $cost>0){
				$ret = $this->up_vbprice_array[$vid]['bfprice'];
			}elseif($cost>0 && $cost>$this->tool['bfprice']){
				$ret=$cost;
			}else{
				$ret=$this->tool['bfprice'];
			}
		}
		// 调试输出
		error_log("[PriceDebug] getbfVideoPrice vid=$vid power={$this->power} price={$ret}");
		return $ret;
	}
	
	public function getbfVideoCost($vid){
		$cost2 = $this->getbfVideoCost2($vid);
		if($this->power<2 && $this->up_vbprice_array[$vid]['bfcost'] && $this->up_vbprice_array[$vid]['bfcost']>=$cost2){
			$cost = $this->up_vbprice_array[$vid]['bfcost'];
		}elseif($this->power==2 && $this->vbprice_array[$vid]['bfcost'] && $this->vbprice_array[$vid]['bfcost']>=$cost2){
			$cost = $this->vbprice_array[$vid]['bfcost'];
		}elseif($this->tool['bfcost']>0){
			$cost = $this->tool['bfcost'];
		}else{
			$cost = $this->tool['bfprice'];
		}
		return $cost;
	}
	public function getbfVideoCost2($vid){
		if($this->tool['bfcost2']>0){
			$cost = $this->tool['bfcost2'];
		}elseif($this->tool['bfcost']>0){
			$cost = $this->tool['bfcost'];
		}else{
			$cost = $this->tool['bfprice'];
		}
		return $cost;
	}
	
	
	public function getToolDel($tid){
		return $this->price_array[$tid]['del'];
	}
	public function getFinalPrice($price, $num){
		if(!empty($this->tool['prices'])){
			$prices = explode(',',$this->tool['prices']);
			foreach($prices as $item){
				$arrs = explode('|',$item);
				if($num>=$arrs[0])$discount=$arrs[1];
			}
			$price -= $discount;
			if($price<=0)return false;
		}
		return $price;
	}
	public function getTooliPrice($tid){
		if($this->power>0 && $this->iprice_array[$tid]>0){
			return $this->iprice_array[$tid];
		}else{
			return null;
		}
	}
	
	public function setToolProfit($tid,$num,$name,$money,$orderid,$userid=0){

		global $DB,$islogin2,$conf,$date;
		if(is_numeric($userid) && strlen($userid)!=32)$islogin2=1;
		$toolPrice = $this->getFinalPrice($this->getToolPrice($tid), $num);
		if(round($toolPrice*$num,2) != round($money,2))return false;
		if($this->power==2){
			$profit=$toolPrice - $this->getToolCost2($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}elseif($this->power==1){
			$profit=$toolPrice - $this->getToolCost($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
			$profit2=$this->getToolCost($tid) - $this->getToolCost2($tid);
			if($profit2>0 && $profit2<$money && $this->upzid>1){
				$tc_point=round($profit2*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->upzid, $tc_point * 0.9, '提成', '你下级分站站长有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}
		$row=$DB->getRow("SELECT * FROM shua_fxbl WHERE id=1 LIMIT 1"); 	  ///取出比例
		/*第一次寻找上级*/
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid");
	//  echo 	$userid_up['upzid'];
		$userid = $userid_up['upzid'];  //给值到userid  下面开始循环第二层
        for ($i=0; $i<=20; $i++){
		if($userid > 0){
		$fxbl_lv = $row["lv$i"] / 100; //循环等级取出佣金比例	
		$fxbl_money = $power_tc_point *  $fxbl_lv;
		$fxbl_money=round($fxbl_money, 2);
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid"); //查询当前用户的信息
		if($userid_up['power'] == 2){  //判断是否有上级且是否为合伙人
		//echo "账号：".$userid."是合伙人分佣".$fxbl_money."/n";
		$this->changeUserMoney($userid, $fxbl_money , '提成', '你的团队有人下单'.$name.',获得'.$fxbl_money.'元提成', $orderid);	
		}
		$userid = $userid_up['upzid'];
		}else{
		return $rs;//如果上级为0了  就跳出循环		
		}
		}


		return $rs;
	}
	
	public function setVideoProfit($tid,$num,$name,$money,$orderid,$userid=0){

		global $DB,$islogin2,$conf,$date;
		if(is_numeric($userid) && strlen($userid)!=32)$islogin2=1;
		$toolPrice = $this->getFinalPrice($this->getVideoPrice($tid), $num);
		if(round($toolPrice*$num,2) != round($money,2))return false;
		if($this->power==2){
			$profit=$toolPrice - $this->getVideoCost2($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}elseif($this->power==1){
			$profit=$toolPrice - $this->getVideoCost($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
			$profit2=$this->getVideoCost($tid) - $this->getVideoCost2($tid);
			if($profit2>0 && $profit2<$money && $this->upzid>1){
				$tc_point=round($profit2*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->upzid, $tc_point * 0.9, '提成', '你下级分站站长有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}
		$row=$DB->getRow("SELECT * FROM shua_fxbl WHERE id=1 LIMIT 1"); 	  ///取出比例
		/*第一次寻找上级*/
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid");
	//  echo 	$userid_up['upzid'];
		$userid = $userid_up['upzid'];  //给值到userid  下面开始循环第二层
        for ($i=0; $i<=20; $i++){
		if($userid > 0){
		$fxbl_lv = $row["lv$i"] / 100; //循环等级取出佣金比例	
		$fxbl_money = $power_tc_point *  $fxbl_lv;
		$fxbl_money=round($fxbl_money, 2);
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid"); //查询当前用户的信息
		if($userid_up['power'] == 2){  //判断是否有上级且是否为合伙人
		//echo "账号：".$userid."是合伙人分佣".$fxbl_money."/n";
		$this->changeUserMoney($userid, $fxbl_money , '提成', '你的团队有人下单'.$name.',获得'.$fxbl_money.'元提成', $orderid);	
		}
		$userid = $userid_up['upzid'];
		}else{
		return $rs;//如果上级为0了  就跳出循环		
		}
		}


		return $rs;
	}
	
	public function setbfVideoProfit($tid,$num,$name,$money,$orderid,$userid=0){

		global $DB,$islogin2,$conf,$date;
		if(is_numeric($userid) && strlen($userid)!=32)$islogin2=1;
		$toolPrice = $this->getFinalPrice($this->getbfVideoPrice($tid), $num);
		if(round($toolPrice*$num,2) != round($money,2))return false;
		if($this->power==2){
			$profit=$toolPrice - $this->getbfVideoCost2($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}elseif($this->power==1){
			$profit=$toolPrice - $this->getbfVideoCost($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
			$profit2=$this->getbfVideoCost($tid) - $this->getbfVideoCost2($tid);
			if($profit2>0 && $profit2<$money && $this->upzid>1){
				$tc_point=round($profit2*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->upzid, $tc_point * 0.9, '提成', '你下级分站站长有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}
		$row=$DB->getRow("SELECT * FROM shua_fxbl WHERE id=1 LIMIT 1"); 	  ///取出比例
		/*第一次寻找上级*/
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid");
	//  echo 	$userid_up['upzid'];
		$userid = $userid_up['upzid'];  //给值到userid  下面开始循环第二层
        for ($i=0; $i<=20; $i++){
		if($userid > 0){
		$fxbl_lv = $row["lv$i"] / 100; //循环等级取出佣金比例	
		$fxbl_money = $power_tc_point *  $fxbl_lv;
		$fxbl_money=round($fxbl_money, 2);
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid"); //查询当前用户的信息
		if($userid_up['power'] == 2){  //判断是否有上级且是否为合伙人
		//echo "账号：".$userid."是合伙人分佣".$fxbl_money."/n";
		$this->changeUserMoney($userid, $fxbl_money , '提成', '你的团队有人下单'.$name.',获得'.$fxbl_money.'元提成', $orderid);	
		}
		$userid = $userid_up['upzid'];
		}else{
		return $rs;//如果上级为0了  就跳出循环		
		}
		}


		return $rs;
	}
	
	public function setdbVideoProfit($tid,$num,$name,$money,$orderid,$userid=0){

		global $DB,$islogin2,$conf,$date;
		if(is_numeric($userid) && strlen($userid)!=32)$islogin2=1;
		$toolPrice = $this->getFinalPrice($this->getdbVideoPrice($tid), $num);
		if(round($toolPrice*$num,2) != round($money,2))return false;
		if($this->power==2){
			$profit=$toolPrice - $this->getdbVideoCost2($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}elseif($this->power==1){
			$profit=$toolPrice - $this->getdbVideoCost($tid);
			if($profit>0 && $profit<$money){
				$tc_point=round($profit*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->zid, $tc_point * 0.9, '提成', '你网站有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
			$profit2=$this->getdbVideoCost($tid) - $this->getdbVideoCost2($tid);
			if($profit2>0 && $profit2<$money && $this->upzid>1){
				$tc_point=round($profit2*$num, 2);
				$power_tc_point  = $tc_point * 0.1;
				$rs=$this->changeUserMoney($this->upzid, $tc_point * 0.9, '提成', '你下级分站站长有人下单'.$name.',获得'.$tc_point * 0.9.'元提成', $orderid);
			}
		}
		$row=$DB->getRow("SELECT * FROM shua_fxbl WHERE id=1 LIMIT 1"); 	  ///取出比例
		/*第一次寻找上级*/
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid");
	//  echo 	$userid_up['upzid'];
		$userid = $userid_up['upzid'];  //给值到userid  下面开始循环第二层
        for ($i=0; $i<=20; $i++){
		if($userid > 0){
		$fxbl_lv = $row["lv$i"] / 100; //循环等级取出佣金比例	
		$fxbl_money = $power_tc_point *  $fxbl_lv;
		$fxbl_money=round($fxbl_money, 2);
		$userid_up = $DB->getRow("SELECT * FROM shua_site WHERE zid=$userid"); //查询当前用户的信息
		if($userid_up['power'] == 2){  //判断是否有上级且是否为合伙人
		//echo "账号：".$userid."是合伙人分佣".$fxbl_money."/n";
		$this->changeUserMoney($userid, $fxbl_money , '提成', '你的团队有人下单'.$name.',获得'.$fxbl_money.'元提成', $orderid);	
		}
		$userid = $userid_up['upzid'];
		}else{
		return $rs;//如果上级为0了  就跳出循环		
		}
		}


		return $rs;
	}
	
	public function setPriceInfo($tid,$del,$price,$cost=0){
		global $DB;
		$this->price_array[$tid] = array();
		if($price != $this->tool['price'] || $cost>0 && $cost != $this->tool['cost'] || $del != $this->price_array[$tid]['del']){
			$this->price_array[$tid]['price'] = $price;
			if($this->power==2)$this->price_array[$tid]['cost'] = $cost;
			$this->price_array[$tid]['del'] = $del;
		}
		$price_data = serialize($this->price_array);
		return $DB->exec("UPDATE shua_site SET price='$price_data' WHERE zid='{$this->zid}'");
	}
	
	public function setvPriceInfo($vid,$del,$price,$cost=0){
		global $DB;
		$this->vprice_array[$vid] = array();
		if($price != $this->tool['price'] || $cost>0 && $cost != $this->tool['cost'] || $del != $this->vprice_array[$vid]['del']){
			$this->vprice_array[$vid]['price'] = $price;
			if($this->power==2)$this->vprice_array[$vid]['cost'] = $cost;
			$this->vprice_array[$vid]['del'] = $del;
		}
		$price_data = serialize($this->vprice_array);
		return $DB->exec("UPDATE shua_site SET vprice='$price_data' WHERE zid='{$this->zid}'");
	}
	
	public function setvbPriceInfo($vid,$del,$price,$cost=0){
		global $DB;
		$this->vbprice_array[$vid] = array();
		if($price != $this->tool['bfprice'] || $cost>0 && $cost != $this->tool['bfcost'] || $del != $this->vbprice_array[$vid]['del']){
			$this->vbprice_array[$vid]['bfprice'] = $price;
			if($this->power==2)$this->vbprice_array[$vid]['bfcost'] = $cost;
			$this->vbprice_array[$vid]['del'] = $del;
		}
		$price_data = serialize($this->vbprice_array);
		return $DB->exec("UPDATE shua_site SET vbprice='$price_data' WHERE zid='{$this->zid}'");
	}
	
	public function setiPriceInfo($tid,$price){
		global $DB;
		if($price==0){
			unset($this->iprice_array[$tid]);
		}else{
			$this->iprice_array[$tid] = $price;
		}
		$iprice_data = serialize($this->iprice_array);
		return $DB->exec("UPDATE shua_site SET iprice='$iprice_data' WHERE zid='{$this->zid}'");
	}
	
	public function getPower(){
		return $this->power;
	}
	private function changeUserMoney($zid, $money, $action=null, $desc = null, $orderid=null){
		global $DB,$conf;
		if($money<=0)return;
		if(!$conf['tixian_limit'] || $conf['tixian_limit']==1 && !$conf['tixian_days']){
			$sqls=",`rmbtc`=`rmbtc`+{$money}";
			$status=1;
		}else{
			$status=0;
		}
		$rs=$DB->exec("UPDATE `shua_site` SET `rmb`=`rmb`+{$money}{$sqls} WHERE `zid`='{$zid}'");
		$DB->exec("INSERT INTO `shua_points` (`zid`, `action`, `point`, `bz`, `addtime`, `orderid`, `status`) VALUES (:zid, :action, :point, :bz, NOW(), :orderid, :status)", [':zid'=>$zid, ':action'=>$action, ':point'=>$money, ':bz'=>$desc, ':orderid'=>$orderid, ':status'=>$status]);
		return $rs;
	}
	private function getSiteInfo($zid){
		global $DB;
		$data = $DB->getRow("SELECT zid,upzid,power,price,iprice,endtime FROM shua_site WHERE zid='$zid' LIMIT 1");
		return $data;
	}
	private function getToolInfo($tid){
		global $DB;
		$row=$DB->getRow("SELECT * FROM shua_tools WHERE tid='$tid' LIMIT 1");
		return $row;
	}

	private function getVideoInfo($vid){
		global $DB;
		$row=$DB->getRow("SELECT * FROM shua_videolist WHERE id='$vid' LIMIT 1");
		return $row;
	}
	
	private function getbfVideoInfo($vid){
		global $DB;
		$row=$DB->getRow("SELECT * FROM shua_videolist WHERE id='$vid' LIMIT 1");
		return $row;
	}
	
	private function getdbVideoInfo($vid){
		global $DB;
		$row=$DB->getRow("SELECT * FROM shua_video WHERE id='$vid' LIMIT 1");
		return $row;
	}
	
	private function getPriceRules($id){
		global $DB,$CACHE;
		if(self::$price_rules) return self::$price_rules[$id];
		$price_rules = unserialize($CACHE->read('pricerules'));
		if(!$price_rules){
			$this->updatePriceRules();
		}else{
			self::$price_rules = $price_rules;
		}
		return self::$price_rules[$id];
	}
	private function updatePriceRules(){
		global $DB,$CACHE;
		$array = array();
		$rs=$DB->query("SELECT * FROM shua_price ORDER BY id ASC");
		while($res = $rs->fetch()){
			$array[$res['id']] = array('kind'=>$res['kind'], 'p_2'=>$res['p_2'], 'p_1'=>$res['p_1'], 'p_0'=>$res['p_0']);
		}
		$CACHE->save('pricerules', $array);
		self::$price_rules = $array;
	}
}
