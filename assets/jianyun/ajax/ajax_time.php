<?php
include("../../../includes/common.php");
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

switch($act){

	//今日更新开始
	    	case 'gettoolday':
	    	    $time2=$_POST['time2'];
	    	   $time=$_POST['time'];
	    	      $qishuname=$DB->getRow("select * from pre_inpoint where k='shopname' limit 1");
    $isdec=$DB->getRow("select * from pre_inpoint where k='homedesc' limit 1");
    $dev=$DB->getRow("select * from pre_inpoint where k='syde' limit 1");
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
	
	
	
	if(in_array($sort_type,$sort_type_arr) && in_array($sort,$sort_arr)){
		$orderBy = "{$sort_type} {$sort}"; 
	}

	$where = "active=1";
	if($time2==1){
	   	$rs=$DB->query("SELECT * FROM pre_tools WHERE $where and addtime>='$time' ORDER BY $orderBy LIMIT $page,$limit");
	}else{
	$rs=$DB->query("SELECT * FROM pre_tools WHERE $where and addtime>='$time' and addtime<='$time2' ORDER BY $orderBy LIMIT $page,$limit");
	}
$is=0;
	while($res = $rs->fetch(PDO::FETCH_ASSOC)){
	    
	    $ic2++; $is++;
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
			$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$res['tid']}' AND orderid=0");
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
    
        $price=number_format($price,2);
          if($qishuname['v']==2){$v='1';}        
           $dname=$DB->getRow("select * from pre_class where cid='{$res['cid']}' limit 1");
          $classname=$dname['name'];
          $content = strip_tags($res['desc']);
        if (mb_strlen($content) > 25)
		$res['desc'] = mb_substr($content, 0, 25, 'utf-8') . '....';
		if(strrpos($res['desc'],'img',0)){
		    $res['desc']='...';
		}
		$res['time']=substr($res['addtime'],0,strlen($res['addtime'])-9);
		$res['time']=substr($res['time'],5);
		  if($res['msnum']>=1){$res['name']='秒杀分类';}
		$data[]=array('isdesc'=>$isdec['v'],'tid'=>$res['tid'],'cid'=>$res['cid'],'sort'=>$res['sort'],'name'=>$res['name'],'value'=>$res['value'],'price'=>$price,'input'=>$res['input'],'inputs'=>$res['inputs'],'desc'=>$res['desc'],'alert'=>$res['alert'],'shopimg'=>$res['shopimg'],'repeat'=>$res['repeat'],'multi'=>$res['multi'],'close'=>$res['close'],'prices'=>$res['prices'],'min'=>$res['min'],'max'=>$res['max'],'sales'=>$res['sales'],'stock'=>$count,'isfaka'=>$isfaka,'addtime'=>strtotime($res['addtime']),'time'=>$res['time'],'is_stock_err'=>$is_stock_err,'active'=>$res['active'],'v'=>$v,'classname'=>$classname);
         
	}
	  $count8c=$DB->getColumn("SELECT count(*) from pre_tools");
	 
	$num2=$ic1+$num;
	$pages = ceil($num2/$limit);


	$result=array("code"=>0,"msg"=>"succ","data"=>$data,'pages'=>$pages,'total'=>intval($is),'bb'=>$dev['v']);
	exit(json_encode($result));
	    	    	break;
	    	    	//今日更新结束

}
