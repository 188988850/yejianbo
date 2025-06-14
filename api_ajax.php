<?php
$nosession = false;
include("./includes/common.php");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');
if(!is_ajax_req())exit('{"code":403}');
if( $_SERVER['REQUEST_METHOD'] == 'GET') exit('{"code":403}');


if($act == "get_data_show" || $act == "gettool"){
  $hashsalt = isset($_SERVER['HTTP_TOKEN'])?$_SERVER['HTTP_TOKEN']:null;
  $csrf_arr = $_SESSION['csrf_arr'];
  if((empty($csrf_arr['token']) || $hashsalt!=$csrf_arr['token'] || $csrf_arr['exp'] < time())){
       unset($_SESSION['csrf_arr']);
	//	exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
}
}
switch ($act) {
	case 'get_data_show':
			get_data_show();
		break;
	case 'gettool':
		if($islogin2==1){
			$price_obj = new \lib\Price($userrow['zid'],$userrow);
			$cookiesid = $userrow['zid'];
			if($userrow['power']>0)$siterow = $userrow;
		}elseif($is_fenzhan == true){
			$price_obj = new \lib\Price($siterow['zid'],$siterow);
		}else{
			$price_obj = new \lib\Price(1);
		}

	    $page = $_POST['page'] ? intval(trim(daddslashes($_POST['page']))) : 1;
        $limit = $_POST['limit'] ? intval(trim(daddslashes($_POST['limit']))) : 9;
        if($limit < 1){
        	$limit = 9;
        }
        $page = ($page-1)*$limit;
        
		//排序语句组装
		$sort_type_arr = ['sort','price','xiaoliang'];
		$sort_arr = ['desc','asc'];
		$sort_type = trim(daddslashes($_POST['sort_type']));
		$sort = trim(daddslashes($_POST['sort']));
		
		$orderBy = "t.sort ASC";
		if(in_array($sort_type,$sort_type_arr)){
		    if(in_array($sort,$sort_arr)){
		        //组装sql语句
		        if($sort_type == "xiaoliang"){
		            //按订单销量
		            //$orderBy = "{$sort_type} {$sort}";
		            
		            //按人气值
		            $orderBy = "t.renqi {$sort}";
		        }else{
		           $orderBy = "t.{$sort_type} {$sort}"; 
		        }
		        
		    }else{
		        $orderBy = "t.{$sort_type} desc";
		    }
		}
		$orderBy .= ",t.tid desc";

        
		$kw=trim(daddslashes($_POST['kw'])); //关键词查询
		$cid = intval($_POST['cid']); //分类ID
		$lable = trim(daddslashes($_POST['lablename'])); //关键词查询
		
		$where = "t.`active`=1 and t.`close`=0";
		if(!empty($kw)){
		    $where .= " and t.name LIKE '%{$kw}%'";
		}
		
		if($cid){
		    $where .= " and t.cid='$cid'";
		}
		
		$label_arr = [];
		$sql = "SELECT t.label FROM shua_tools as t WHERE $where";
		$rs=$DB->query($sql);
		while($res = $rs->fetch(PDO::FETCH_ASSOC)){
		                //标签分组
            if(isset($res['label']) && $res['label'] != "")
            {
                //拆分为数组
                $arr = explode(',',$res['label']);
                foreach ($arr as $v)
                {
                    if(in_array($v,$label_arr)){
                        continue;
                    }
                    array_push($label_arr,$v);
                }
            }
		}
		if($lable && $lable != "全部")
		{
		    $where .= " and CONCAT(',',t.label,',') like '%,".$lable.",%'";
		}
		
		$count_sql = "SELECT count(tid) FROM shua_tools as t WHERE $where";
		//echo $count_sql;die();
		//按订单销量
        //$sql = "SELECT t.*,(select count(id) from shua_orders WHERE tid = t.tid) as xiaoliang FROM shua_tools as t WHERE $where ORDER by $orderBy limit $page,$limit";

        //按人气值
        $sql = "SELECT t.*,t.renqi as xiaoliang FROM shua_tools as t WHERE $where ORDER by $orderBy limit $page,$limit";
        
        $num=$DB->getColumn($count_sql); //获取总数量
        $rs=$DB->query($sql);

		$data = array();
		$curr_time = time();
		
		while($res = $rs->fetch(PDO::FETCH_ASSOC)){
			if(isset($_SESSION['gift_id']) && isset($_SESSION['gift_tid']) && $_SESSION['gift_tid']==$res['tid']){
				$price=$conf["cjmoney"]?$conf["cjmoney"]:0;
			}elseif(isset($price_obj)){
				$price_obj->setToolInfo($res['tid'],$res);
				if($price_obj->getToolDel($res['tid'])==1)continue;
				$price=$price_obj->getToolPrice($res['tid']);
			}else{
			    $price=$res['price'];
			} 


			$is_show_kucun_err = 0;
			if($res['is_curl']==4){
				$isfaka = 1;
				$res['input'] = getFakaInput();
				$count = $DB->getColumn("SELECT count(*) FROM shua_faka WHERE tid='{$res['tid']}' AND orderid=0");
				if($count == 0)
				{
					$is_show_kucun_err = 1;
				}
			}else if($res['is_curl']==2){
				if($res['kucun'] == 0 && ($curr_time-strtotime($res['kucun_time'])) <= 180){
					$is_show_kucun_err = 1;
				}

				if(($curr_time-strtotime($res['kucun_time'])) <= 180){
					$isfaka = 1;
					$count = $res['kucun'];
				}else{
					$isfaka = 0;
					$count = 0;
				}
			}else{
				$isfaka = 0;
				$count = 0;
			}
			$data[]=array('tid'=>$res['tid'],'cid'=>$res['cid'],'sort'=>$res['sort'],'name'=>$res['name'],'value'=>$res['value'],'price'=>$price,'input'=>$res['input'],'inputs'=>$res['inputs'],'desc'=>$res['desc'],'alert'=>$res['alert'],'shopimg'=>$res['shopimg'],'repeat'=>$res['repeat'],'multi'=>$res['multi'],'close'=>$res['close'],'prices'=>$res['prices'],'min'=>$res['min'],'max'=>$res['max'],'isfaka'=>$isfaka,'sales'=>float_number($res['xiaoliang']),'show_tag'=>$res['show_tag'],'add_time'=>strtotime($res['addtime']),'is_show_kucun_err'=>$is_show_kucun_err,'kucun'=>$count,'is_show_kucun'=>$is_show_kucun);
		}
		$pages = ceil($num/$limit);
		$result=array("code"=>0,"msg"=>"succ","data"=>$data,"info"=>$info,'pages'=>$pages,'total'=>$num,'catname'=>$_POST['name'],'label'=>count($label_arr)>0?$label_arr:"");
		exit(json_encode($result));
		break;
	case 'uploadimg':
		if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
		if($_POST['do']=='upload'){
			$filename = $_FILES['file']['name'];
			$ext = substr($filename, strripos($filename, '.') + 1);
			$arr = array('png', 'jpg', 'gif', 'jpeg', 'webp', 'bmp');
			if (!in_array($ext , $arr)) {
				exit('{"code":-1,"msg":"只支持上传图片文件"}');
			}
			if($_POST['upload_leixing']=='1')
			{
				$filename = 'sk_'.$userrow['zid'].'.png';
				$fileurl = 'assets/img/skimg/'.$filename;
			}else if($_POST['upload_leixing']=='2'){
				$filename = 'kf_'.$userrow['zid'].'.png';
				$fileurl = 'assets/img/kfewm/'.$filename;
			}else{
				exit('{"code":-1,"msg":"非法上传"}');
			}

			if(copy($_FILES['file']['tmp_name'], ROOT.$fileurl)){
				exit('{"code":0,"msg":"succ","url":"'.$fileurl.'"}');
			}else{
				exit('{"code":-1,"msg":"上传失败，请确保有本地写入权限"}');
			}
		}
		exit('{"code":-1,"msg":"null"}');
		break;
	case 'yijian_msg':
			if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
			if($userrow['power']==2){
				$type = array(0,2,4);
			}elseif($userrow['power']==1){
				$type = array(0,2,3);
			}else{
				$type = array(0,1);
			}
			$type = implode(',', $type);
			$rs=$DB->query("SELECT id FROM shua_message WHERE `type` in ({$type})");
			$id = "";
			foreach ($rs as $key => $value) {
				$id .= $value['id'].',';
			}

			if($id){
				$DB->exec("UPDATE shua_site SET msgread='".$id."' WHERE zid='{$userrow['zid']}'");
			}
			$result=array("code"=>0,"msg"=>"succ");
			exit(json_encode($result));
		break;
case 'xiaji_up_price':
		if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
		unset($islogin2);
		$price_obj = new \lib\Price($userrow['zid'],$userrow);
		$up=intval($_POST['up']);
		$type = intval($_POST['type']);
		if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
        $sql=$DB->query("select tid,price,name,cost from shua_tools where active=1");
		$price_arr = @unserialize($userrow['price']);
		
		while($row=$sql->fetch()){
			if($row['price']==0){
				continue;
			}
			if(strpos($row['name'],'免费')!==false){
				continue;
			}
			$price_obj->setToolInfo($row['tid'],$row);
			$price = $price_obj->getToolCost($row['tid']);//下级成本价
			
		//	$xs_price = $price_obj->getToolPrice($row['tid']);//销售价
            if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['tid']]['cost']=round($price*($a+1),2);
            }else{
              $a=(float)$up; 
              $price_arr[$row['tid']]['cost']=round($price+($a),2);
            }
		
 			$xs_price = ($xs_price-$price)+$data[$row['tid']]['cost'];
 			$price_arr[$row['tid']]['price']=round($xs_price);

		}

		$array_data=serialize($price_arr);
		$DB->exec("update `shua_site` set `price`='{$array_data}' where zid='{$userrow['zid']}'");
		exit('{"code":0}');
	break;
    case 'up_price':
        if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
    	unset($islogin2);
    	$price_obj = new \lib\Price($userrow['zid'],$userrow);
    	$up=intval($_POST['up']);
    	$type = intval($_POST['type']);
    	if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
    	if($conf['fenzhan_pricelimit']==1 && $up>100)exit('{"code":-1,"msg":"商品售价最高不能超过原售价的2倍"}');
    	$sql=$DB->query("select tid,price,name from shua_tools where active=1");
    	
    	$price_arr = @unserialize($userrow['price']);
    	

    	while($row=$sql->fetch()){
    		if($row['price']==0){
    			continue;
    		}
    		if(strpos($row['name'],'免费')!==false){
    			continue;
    		}
    		$price_obj->setToolInfo($row['tid'],$row);
    		$price = $price_obj->getToolPrice($row['tid']);
    		
    		if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['tid']]['price']=round($price*($a+1),2);
            }else{
               $a=(float)$up; 
               $price_arr[$row['tid']]['price']=round($price+($a),2);
            }
    	}
    	$array_data=serialize($price_arr);
    	$DB->exec("update `shua_site` set `price`='{$array_data}' where zid='{$userrow['zid']}'");
    	exit('{"code":0}');
    break;
    case 'xiaji_up_vprice':
		if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
		unset($islogin2);
		$price_obj = new \lib\Price($userrow['zid'],$userrow);
		$up=intval($_POST['up']);
		$type = intval($_POST['type']);
		if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
        $sql=$DB->query("select id,price,name,cost from pre_videolist where active=1");
		$price_arr = @unserialize($userrow['vprice']);
		
		while($row=$sql->fetch()){
			if($row['price']==0){
				continue;
			}
			if(strpos($row['name'],'免费')!==false){
				continue;
			}
			$price_obj->setVideoInfo($row['id'],$row);
			$price = $price_obj->getVideoCost($row['id']);//下级成本价
			
			$xs_price = $price_obj->getVideoPrice($row['id']);//销售价
            if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['id']]['cost']=round($price*($a+1),2);
            }else{
              $a=(float)$up; 
              $price_arr[$row['id']]['cost']=round($price+($a),2);
            }
		
 			$xs_price = ($xs_price-$price)+$data[$row['id']]['cost'];
 			$price_arr[$row['id']]['price']=round($xs_price);

		}

		$array_data=serialize($price_arr);
		$DB->exec("update `shua_site` set `vprice`='{$array_data}' where zid='{$userrow['zid']}'");
		exit('{"code":0}');
	break;
    case 'up_vprice':
        if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
    	unset($islogin2);
    	$price_obj = new \lib\Price($userrow['zid'],$userrow);
    	$up=intval($_POST['up']);
    	$type = intval($_POST['type']);
    	if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
    	if($conf['fenzhan_pricelimit']==1 && $up>100)exit('{"code":-1,"msg":"商品售价最高不能超过原售价的2倍"}');
    	$sql=$DB->query("select id,price,name from pre_videolist where active=1");
    	
    	$price_arr = @unserialize($userrow['vprice']);
    	

    	while($row=$sql->fetch()){
    		if($row['price']==0){
    			continue;
    		}
    		if(strpos($row['name'],'免费')!==false){
    			continue;
    		}
    		$price_obj->setVideoInfo($row['id'],$row);
    		$price = $price_obj->getVideoPrice($row['id']);
    		
    		if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['id']]['price']=round($price*($a+1),2);
            }else{
               $a=(float)$up; 
               $price_arr[$row['id']]['price']=round($price+($a),2);
            }
    	}
    	$array_data=serialize($price_arr);
    	$DB->exec("update `shua_site` set `vprice`='{$array_data}' where zid='{$userrow['zid']}'");
    	exit('{"code":0}');
    break;
    case 'xiaji_up_vbfprice':
		if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
		unset($islogin2);
		$price_obj = new \lib\Price($userrow['zid'],$userrow);
		$up=intval($_POST['up']);
		$type = intval($_POST['type']);
		if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
        $sql=$DB->query("select id,bfprice,name,bfcost from pre_videolist where active=1");
		$price_arr = @unserialize($userrow['vprice']);
		
		while($row=$sql->fetch()){
			if($row['price']==0){
				continue;
			}
			if(strpos($row['name'],'免费')!==false){
				continue;
			}
			$price_obj->setbfVideoInfo($row['id'],$row);
			$price = $price_obj->getbfVideoCost($row['id']);//下级成本价
			
			$xs_price = $price_obj->getbfVideoPrice($row['id']);//销售价
            if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['id']]['bfcost']=round($price*($a+1),2);
            }else{
              $a=(float)$up; 
              $price_arr[$row['id']]['bfcost']=round($price+($a),2);
            }
		
 			$xs_price = ($xs_price-$price)+$data[$row['id']]['bfcost'];
 			$price_arr[$row['id']]['bfprice']=round($xs_price);

		}

		$array_data=serialize($price_arr);
		$DB->exec("update `shua_site` set `vbprice`='{$array_data}' where zid='{$userrow['zid']}'");
		exit('{"code":0}');
	break;
    case 'up_vbfprice':
        if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
    	unset($islogin2);
    	$price_obj = new \lib\Price($userrow['zid'],$userrow);
    	$up=intval($_POST['up']);
    	$type = intval($_POST['type']);
    	if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
    	if($conf['fenzhan_pricelimit']==1 && $up>100)exit('{"code":-1,"msg":"商品售价最高不能超过原售价的2倍"}');
    	$sql=$DB->query("select id,bfprice,name from pre_videolist where active=1");
    	
    	$price_arr = @unserialize($userrow['vprice']);
    	

    	while($row=$sql->fetch()){
    		if($row['bfprice']==0){
    			continue;
    		}
    		if(strpos($row['name'],'免费')!==false){
    			continue;
    		}
    		$price_obj->setbfVideoInfo($row['id'],$row);
    		$price = $price_obj->getbfVideoPrice($row['id']);
    		
    		if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['id']]['bfprice']=round($price*($a+1),2);
            }else{
               $a=(float)$up; 
               $price_arr[$row['id']]['bfprice']=round($price+($a),2);
            }
    	}
    	$array_data=serialize($price_arr);
    	$DB->exec("update `shua_site` set `vbprice`='{$array_data}' where zid='{$userrow['zid']}'");
    	exit('{"code":0}');
    break;
    case 'xiaji_up_vdbprice':
		if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
		unset($islogin2);
		$price_obj = new \lib\Price($userrow['zid'],$userrow);
		$up=intval($_POST['up']);
		$type = intval($_POST['type']);
		if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
        $sql=$DB->query("select id,price,name,cost from pre_video where active=1");
		$price_arr = @unserialize($userrow['vprice']);
		
		while($row=$sql->fetch()){
			if($row['price']==0){
				continue;
			}
			if(strpos($row['name'],'免费')!==false){
				continue;
			}
			$price_obj->setdbVideoInfo($row['id'],$row);
			$price = $price_obj->getdbVideoCost($row['id']);//下级成本价
			
			$xs_price = $price_obj->getdbVideoPrice($row['id']);//销售价
            if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['id']]['cost']=round($price*($a+1),2);
            }else{
              $a=(float)$up; 
              $price_arr[$row['id']]['cost']=round($price+($a),2);
            }
		
 			$xs_price = ($xs_price-$price)+$data[$row['id']]['cost'];
 			$price_arr[$row['id']]['price']=round($xs_price);

		}

		$array_data=serialize($price_arr);
		$DB->exec("update `shua_site` set `vdbprice`='{$array_data}' where zid='{$userrow['zid']}'");
		exit('{"code":0}');
	break;
    case 'up_vdbprice':
        if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');
    	unset($islogin2);
    	$price_obj = new \lib\Price($userrow['zid'],$userrow);
    	$up=intval($_POST['up']);
    	$type = intval($_POST['type']);
    	if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
    	if($conf['fenzhan_pricelimit']==1 && $up>100)exit('{"code":-1,"msg":"商品售价最高不能超过原售价的2倍"}');
    	$sql=$DB->query("select id,price,name from pre_video where active=1");
    	
    	$price_arr = @unserialize($userrow['vprice']);
    	

    	while($row=$sql->fetch()){
    		if($row['price']==0){
    			continue;
    		}
    		if(strpos($row['name'],'免费')!==false){
    			continue;
    		}
    		$price_obj->setdbVideoInfo($row['id'],$row);
    		$price = $price_obj->getdbVideoPrice($row['id']);
    		
    		if($type == 1){
                $a=(float)$up/100; 
                $price_arr[$row['id']]['price']=round($price*($a+1),2);
            }else{
               $a=(float)$up; 
               $price_arr[$row['id']]['price']=round($price+($a),2);
            }
    	}
    	$array_data=serialize($price_arr);
    	$DB->exec("update `shua_site` set `vdbprice`='{$array_data}' where zid='{$userrow['zid']}'");
    	exit('{"code":0}');
    break;
	default:
		exit('{"code":404}');
		break;
}

function get_data_show(){
	global $DB;
	$phone = generate_name(1);
	$phone=current($phone);
	$phone = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $phone);
	$arr = [
		0=>0,
		1=>1,
		2=>2,
		3=>3,
		4=>4,
	];
	$arr = array_rand($arr);
	$num = mt_rand(1,18000);
	 if($num >= 60)
	 {
	 	if($num >= 3600)
	 	{
	 		$times = floor($num/3600).'小时';
	 	}else{
	 		$times = floor($num/60).'分钟';
	 	}

	 }else{
	 	$times = $num."秒";
	 }

	switch ($arr) {
		case 0:
			 $text = "【{$phone}】升级为分站站长";
			break;
		case 1:
			 $text = "【{$phone}】升级为顶级合伙人";
			break;
		case 2:
			//查询数据
			$rs = $DB->query("SELECT name FROM shua_tools where `active` = 1 and `close` = 0");
			$goods_list;
				while ($res = $rs->fetch()) {
					unset($res[0]);
				    $goods_list[] = $res;
			}
			if($goods_list)
			{
				$key = array_rand($goods_list,1);
				$goods_name = $goods_list[$key]['name'];
			}else{
				$goods_name = "未知";
			}
			 $text = "【{$phone}】购买了".$goods_name."";
			break;
		case 3:
			$num = mt_rand(10,500);
			 $text = "【{$phone}】提现了{$num}元收益";
			break;
		case 4:
			$num = 0 + mt_rand() / mt_getrandmax() * (50 - 0);
			$num = sprintf("%.2f",$num);
			$text = "【{$phone}】获得下级站点订单收益{$num}元";
			break;
	}
	

	$info= file_get_contents("https://api.uomg.com/api/rand.avatar?format=json");
    $info = json_decode($info,true);
    if($info['code'] == 1)
    {
        $avatar_url = $info['imgurl'];
    }else{
        $avatar_url = "https://q2.qlogo.cn/headimg_dl?bs=qq&dst_uin=3055902&src_uin=3055902&fid=3055902&spec=100&url_enc=0&referer=bu_interface&term_type=PC";
    }

    $data = ['code'=>1,'text'=>$text,'avatar'=>$avatar_url,"time"=>$times];
    exit(json_encode($data));
}

function generate_name($count=1,$type="array",$white_space=true)
{
	$arr = array(
    130,131,132,133,134,135,136,137,138,139,
    144,147,
    150,151,152,153,155,156,157,158,159,
    176,177,178,
    180,181,182,183,184,185,186,187,188,189,
	);
	for($i = 0; $i < $count; $i++) {
	    $tmp[] = $arr[array_rand($arr)].' '.mt_rand(1000,9999).' '.mt_rand(1000,9999);
	}
	if($type==="string"){
	    $tmp=json_encode($tmp);//如果是字符串，解析成字符串
	}
	if($white_space===true){
	    $tmp=preg_replace("/\s*/","",$tmp);
	}
	return array_unique($tmp);
}

function getname_2()
{
	    $nicheng_tou=array('快乐的','冷静的','醉熏的','潇洒的','糊涂的','积极的','冷酷的','深情的','粗暴的','温柔的','可爱的','愉快的','义气的','认真的','威武的','帅气的','传统的','潇洒的','漂亮的','自然的','专一的','听话的','昏睡的','狂野的','等待的','搞怪的','幽默的','魁梧的','活泼的','开心的','高兴的','超帅的','留胡子的','坦率的','直率的','轻松的','痴情的','完美的','精明的','无聊的','有魅力的','丰富的','繁荣的','饱满的','炙热的','暴躁的','碧蓝的','俊逸的','英勇的','健忘的','故意的','无心的','土豪的','朴实的','兴奋的','幸福的','淡定的','不安的','阔达的','孤独的','独特的','疯狂的','时尚的','落后的','风趣的','忧伤的','大胆的','爱笑的','矮小的','健康的','合适的','玩命的','沉默的','斯文的','香蕉','苹果','鲤鱼','鳗鱼','任性的','细心的','粗心的','大意的','甜甜的','酷酷的','健壮的','英俊的','霸气的','阳光的','默默的','大力的','孝顺的','忧虑的','着急的','紧张的','善良的','凶狠的','害怕的','重要的','危机的','欢喜的','欣慰的','满意的','跳跃的','诚心的','称心的','如意的','怡然的','娇气的','无奈的','无语的','激动的','愤怒的','美好的','感动的','激情的','激昂的','震动的','虚拟的','超级的','寒冷的','精明的','明理的','犹豫的','忧郁的','寂寞的','奋斗的','勤奋的','现代的','过时的','稳重的','热情的','含蓄的','开放的','无辜的','多情的','纯真的','拉长的','热心的','从容的','体贴的','风中的','曾经的','追寻的','儒雅的','优雅的','开朗的','外向的','内向的','清爽的','文艺的','长情的','平常的','单身的','伶俐的','高大的','懦弱的','柔弱的','爱笑的','乐观的','耍酷的','酷炫的','神勇的','年轻的','唠叨的','瘦瘦的','无情的','包容的','顺心的','畅快的','舒适的','靓丽的','负责的','背后的','简单的','谦让的','彩色的','缥缈的','欢呼的','生动的','复杂的','慈祥的','仁爱的','魔幻的','虚幻的','淡然的','受伤的','雪白的','高高的','糟糕的','顺利的','闪闪的','羞涩的','缓慢的','迅速的','优秀的','聪明的','含糊的','俏皮的','淡淡的','坚强的','平淡的','欣喜的','能干的','灵巧的','友好的','机智的','机灵的','正直的','谨慎的','俭朴的','殷勤的','虚心的','辛勤的','自觉的','无私的','无限的','踏实的','老实的','现实的','可靠的','务实的','拼搏的','个性的','粗犷的','活力的','成就的','勤劳的','单纯的','落寞的','朴素的','悲凉的','忧心的','洁净的','清秀的','自由的','小巧的','单薄的','贪玩的','刻苦的','干净的','壮观的','和谐的','文静的','调皮的','害羞的','安详的','自信的','端庄的','坚定的','美满的','舒心的','温暖的','专注的','勤恳的','美丽的','腼腆的','优美的','甜美的','甜蜜的','整齐的','动人的','典雅的','尊敬的','舒服的','妩媚的','秀丽的','喜悦的','甜美的','彪壮的','强健的','大方的','俊秀的','聪慧的','迷人的','陶醉的','悦耳的','动听的','明亮的','结实的','魁梧的','标致的','清脆的','敏感的','光亮的','大气的','老迟到的','知性的','冷傲的','呆萌的','野性的','隐形的','笑点低的','微笑的','笨笨的','难过的','沉静的','火星上的','失眠的','安静的','纯情的','要减肥的','迷路的','烂漫的','哭泣的','贤惠的','苗条的','温婉的','发嗲的','会撒娇的','贪玩的','执着的','眯眯眼的','花痴的','想人陪的','眼睛大的','高贵的','傲娇的','心灵美的','爱撒娇的','细腻的','天真的','怕黑的','感性的','飘逸的','怕孤独的','忐忑的','高挑的','傻傻的','冷艳的','爱听歌的','还单身的','怕孤单的','懵懂的');
        $nicheng_wei=array('嚓茶','凉面','便当','毛豆','花生','可乐','灯泡','哈密瓜','野狼','背包','眼神','缘分','雪碧','人生','牛排','蚂蚁','飞鸟','灰狼','斑马','汉堡','悟空','巨人','绿茶','自行车','保温杯','大碗','墨镜','魔镜','煎饼','月饼','月亮','星星','芝麻','啤酒','玫瑰','大叔','小伙','哈密瓜，数据线','太阳','树叶','芹菜','黄蜂','蜜粉','蜜蜂','信封','西装','外套','裙子','大象','猫咪','母鸡','路灯','蓝天','白云','星月','彩虹','微笑','摩托','板栗','高山','大地','大树','电灯胆','砖头','楼房','水池','鸡翅','蜻蜓','红牛','咖啡','机器猫','枕头','大船','诺言','钢笔','刺猬','天空','飞机','大炮','冬天','洋葱','春天','夏天','秋天','冬日','航空','毛衣','豌豆','黑米','玉米','眼睛','老鼠','白羊','帅哥','美女','季节','鲜花','服饰','裙子','白开水','秀发','大山','火车','汽车','歌曲','舞蹈','老师','导师','方盒','大米','麦片','水杯','水壶','手套','鞋子','自行车','鼠标','手机','电脑','书本','奇迹','身影','香烟','夕阳','台灯','宝贝','未来','皮带','钥匙','心锁','故事','花瓣','滑板','画笔','画板','学姐','店员','电源','饼干','宝马','过客','大白','时光','石头','钻石','河马','犀牛','西牛','绿草','抽屉','柜子','往事','寒风','路人','橘子','耳机','鸵鸟','朋友','苗条','铅笔','钢笔','硬币','热狗','大侠','御姐','萝莉','毛巾','期待','盼望','白昼','黑夜','大门','黑裤','钢铁侠','哑铃','板凳','枫叶','荷花','乌龟','仙人掌','衬衫','大神','草丛','早晨','心情','茉莉','流沙','蜗牛','战斗机','冥王星','猎豹','棒球','篮球','乐曲','电话','网络','世界','中心','鱼','鸡','狗','老虎','鸭子','雨','羽毛','翅膀','外套','火','丝袜','书包','钢笔','冷风','八宝粥','烤鸡','大雁','音响','招牌','胡萝卜','冰棍','帽子','菠萝','蛋挞','香水','泥猴桃','吐司','溪流','黄豆','樱桃','小鸽子','小蝴蝶','爆米花','花卷','小鸭子','小海豚','日记本','小熊猫','小懒猪','小懒虫','荔枝','镜子','曲奇','金针菇','小松鼠','小虾米','酒窝','紫菜','金鱼','柚子','果汁','百褶裙','项链','帆布鞋','火龙果','奇异果','煎蛋','唇彩','小土豆','高跟鞋','戒指','雪糕','睫毛','铃铛','手链','香氛','红酒','月光','酸奶','银耳汤','咖啡豆','小蜜蜂','小蚂蚁','蜡烛','棉花糖','向日葵','水蜜桃','小蝴蝶','小刺猬','小丸子','指甲油','康乃馨','糖豆','薯片','口红','超短裙','乌冬面','冰淇淋','棒棒糖','长颈鹿','豆芽','发箍','发卡','发夹','发带','铃铛','小馒头','小笼包','小甜瓜','冬瓜','香菇','小兔子','含羞草','短靴','睫毛膏','小蘑菇','跳跳糖','小白菜','草莓','柠檬','月饼','百合','纸鹤','小天鹅','云朵','芒果','面包','海燕','小猫咪','龙猫','唇膏','鞋垫','羊','黑猫','白猫','万宝路','金毛','山水','音响');
        $tou_num=rand(0,331);
        $wei_num=rand(0,325);
        $nicheng=$nicheng_tou[$tou_num].$nicheng_wei[$wei_num];
        return $nicheng;
}



function  getname_1( $name_count=1)
{
	$firstname_arr  = array('赵','钱','孙','李','周','吴','郑','王','冯','陈','褚','卫','蒋','沈','韩','杨','朱','秦','尤','许','何','吕','施','张','孔','曹','严','华','金','魏','陶','姜',
	  '戚','谢','邹','喻','柏','水','窦','章','云','苏','潘','葛','奚','范','彭','郎','鲁','韦','昌','马','苗','凤','花','方','任','袁','柳','鲍','史','唐','费','薛','雷','贺','倪',
	  '汤','滕','殷','罗','毕','郝','安','常','傅','卞','齐','元','顾','孟','平','黄','穆','萧','尹','姚','邵','湛','汪','祁','毛','狄','米','伏','成','戴','谈','宋','茅','庞','熊',
	  '纪','舒','屈','项','祝','董','梁','杜','阮','蓝','闵','季','贾','路','娄','江','童','颜','郭','梅','盛','林','钟','徐','邱','骆','高','夏','蔡','田','樊','胡','凌','霍','虞',
	  '万','支','柯','管','卢','莫','柯','房','裘','缪','解','应','宗','丁','宣','邓','单','杭','洪','包','诸','左','石','崔','吉','龚','程','嵇','邢','裴','陆','荣','翁','荀','于',
	  '惠','甄','曲','封','储','仲','伊','宁','仇','甘','武','符','刘','景','詹','龙','叶','幸','司','黎','溥','印','怀','蒲','邰','从','索','赖','卓','屠','池','乔','胥','闻','莘',
	  '党','翟','谭','贡','劳','逄','姬','申','扶','堵','冉','宰','雍','桑','寿','通','燕','浦','尚','农','温','别','庄','晏','柴','瞿','阎','连','习','容','向','古','易','廖','庾',
	  '终','步','都','耿','满','弘','匡','国','文','寇','广','禄','阙','东','欧','利','师','巩','聂','关','荆','司马','上官','欧阳','夏侯','诸葛','闻人','东方','赫连','皇甫','尉迟',
	  '公羊','澹台','公冶','宗政','濮阳','淳于','单于','太叔','申屠','公孙','仲孙','轩辕','令狐','徐离','宇文','长孙','慕容','司徒','司空');
	$lastname_arr  = array('伟','刚','勇','毅','俊','峰','强','军','平','保','东','文','辉','力','明','永','健','世','广','志','义','兴','良','海','山','仁','波','宁','贵','福','生','龙',
	   '元','全','国','胜','学','祥','才','发','武','新','利','清','飞','彬','富','顺','信','子','杰','涛','昌','成','康','星','光','天','达','安','岩','中','茂','进','林','有','坚',
	    '和','彪','博','诚','先','敬','震','振','壮','会','思','群','豪','心','邦','承','乐','绍','功','松','善','厚','庆','磊','民','友','裕','河','哲','江','超','浩','亮','政','谦',
	    '亨','奇','固','之','轮','翰','朗','伯','宏','言','若','鸣','朋','斌','梁','栋','维','启','克','伦','翔','旭','鹏','泽','晨','辰','士','以','建','家','致','树','炎','德','行',
	    '时','泰','盛','雄','琛','钧','冠','策','腾','楠','榕','风','航','弘','秀','娟','英','华','慧','巧','美','娜','静','淑','惠','珠','翠','雅','芝','玉','萍','红','娥','玲','芬',
	    '芳','燕','彩','春','菊','兰','凤','洁','梅','琳','素','云','莲','真','环','雪','荣','爱','妹','霞','香','月','莺','媛','艳','瑞','凡','佳','嘉','琼','勤','珍','贞','莉','桂',
	    '娣','叶','璧','璐','娅','琦','晶','妍','茜','秋','珊','莎','锦','黛','青','倩','婷','姣','婉','娴','瑾','颖','露','瑶','怡','婵','雁','蓓','纨','仪','荷','丹','蓉','眉','君',
	    '琴','蕊','薇','菁','梦','岚','苑','婕','馨','瑗','琰','韵','融','园','艺','咏','卿','聪','澜','纯','毓','悦','昭','冰','爽','琬','茗','羽','希','欣','飘','育','滢','馥','筠',
	    '柔','竹','霭','凝','晓','欢','霄','枫','芸','菲','寒','伊','亚','宜','可','姬','舒','影','荔','枝','丽','阳','妮','宝','贝','初','程','梵','罡','恒','鸿','桦','骅','剑','娇',
	    '纪','宽','苛','灵','玛','媚','琪','晴','容','睿','烁','堂','唯','威','韦','雯','苇','萱','阅','彦','宇','雨','洋','忠','宗','曼','紫','逸','贤','蝶','菡','绿','蓝','儿','翠',
	    '钱','孙','李','周','吴','郑','王','冯','陈','褚','卫','蒋','沈','韩','杨','朱','秦','尤','许','何','吕','施','张','孔','曹','严','华','金','魏','陶','姜','戚','谢','邹','喻','柏','水','窦','章','云','苏','潘','葛','奚','范','彭','郎','鲁','韦','昌','马','苗','凤','花','方','任','袁','柳','鲍','史','唐','费','薛','雷','贺','倪','汤','滕','殷','罗','毕','郝','安','常','傅','卞','齐','元','顾','孟','平','黄','穆','萧','尹','姚','邵','湛','汪','祁','毛','狄','米','伏','成','戴','谈','宋','茅','庞','熊','纪','舒','屈','项','祝','董','梁','杜','阮','蓝','闵','季','贾','路','娄','江','童','颜','郭','梅','盛','林','钟','徐','邱','骆','高','夏','蔡','田','樊','胡','凌','霍','虞','万','支','柯','管','卢','莫','柯','房','裘','缪','解','应','宗','丁','宣','邓','单','杭','洪','包','诸','左','石','崔','吉','龚','程','嵇','邢','裴','陆','荣','翁','荀','于','惠','甄','曲','封','储','仲','伊','宁','仇','甘','武','符','刘','景','詹','龙','叶','幸','司','黎','溥','印','怀','蒲','邰','从','索','赖','卓','屠','池','乔','胥','闻','莘','党','翟','谭','贡','劳','逄','姬','申','扶','堵','冉','宰','雍','桑','寿','通','燕','浦','尚','农','温','别','庄','晏','柴','瞿','阎','连','习','容','向','古','易','廖','庾','终','步','都','耿','满','弘','匡','国','文','寇','广','禄','阙','东','欧','利','师','巩','聂','关','荆',
	    '烟');
  $temp;
	for( $j=1 ;$j<=$name_count; $j++ )
	{
		$firstname_rand_key   = mt_rand( 0,count( $firstname_arr )-1 );
		$firstname   =  $firstname_arr[$firstname_rand_key]; 
		$name_length = mt_rand( 1,2 );
		$lastname='';
	  for( $i=1;$i<=$name_length;$i++ )
	  {
	    $lastname_rand_key = mt_rand( 0,count( $lastname_arr )-1 );
	 	$lastname    .=$lastname_arr[$lastname_rand_key];
	  }
	  $temp[]=$firstname. $lastname;
	}
    $ret= json_encode($temp,JSON_UNESCAPED_UNICODE);
	return $ret; 
}
function is_ajax_req()
{
    // php 判断是否为 ajax 请求
    if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")
    {
        return true;
    }else
    {
        return false;
    }
}
   /**
 * 格式化数字
 */
function float_number($number){
    $length = strlen($number);  //数字长度
    if($length > 8){ //亿单位
        $str = substr_replace(strstr($number,substr($number,-7),' '),'.',-1,0)."亿";
    }elseif($length >4){ //万单位
        //截取前俩为
        $str = substr_replace(strstr($number,substr($number,-3),' '),'.',-1,0)."万";
    }else{
        return $number;
    }
    return ltrim($str,'.');
}
?>