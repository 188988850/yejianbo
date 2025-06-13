<?php
//通信密码
//例子$code='9999asas';
$code='asd4546qwe123';

$ctime=$_SERVER['HTTP_TIME'];
$ctoken=isset($_SERVER['HTTP_TOKEN'])?$_SERVER['HTTP_TOKEN']:1;




if(abs(time()-$ctime) >90){
    exit('通信时间超时,当前服务器和本地时间差'.abs(time()-$ctime).'秒');
}



if(sha1($code.$ctime) != $ctoken){
    exit('通信密码错误'.$ctoken);
}
$caihong=false;
$xiaochu=false;
$chenmeng=false;
$type=$_SERVER['HTTP_TYPE'];
switch($type){
    case 'caihong':
        $caihong=true;
        break;
    case 'xiaochu':
        $xiaochu=true;
        break;
    case 'chenmeng':
        $chenmeng=true;
        break;
    default:
        $chenmeng=true;
        break;
}
    
$host='';
$user='';
$pass='';

$qz='';
#检查程序类型
if($xiaochu){

    try{
        include './includes/deploy.php';
    }catch(Error $e){
        print "没有找到 当前目录/includes/deploy.php !请确定你用的是小褚云!!!:";
        exit;
    }
    $host="mysql:host={$dbconfig['host']};port={$dbconfig['port']};dbname={$dbconfig['dbname']};";
    $user=$dbconfig['user'];
    $pass=$dbconfig['pwd'];
}elseif($caihong){
    try{
        include("config.php");
    }catch(ERROR $e){
        exit('没有在当前目录找到数据库配置文件config.php,');
    }
    $qz=$dbconfig['dbqz'];
    $host="mysql:host={$dbconfig['host']};port={$dbconfig['port']};dbname={$dbconfig['dbname']};";
    $user=$dbconfig['user'];
    $pass=$dbconfig['pwd'];

}elseif($chenmeng){
    try{
        include("dbconfig.php");
    }catch(ERROR $e){
        exit('没有在当前目录找到数据库配置文件dbconfig.php,');
    }
    $qz=$dbconfig['dbqz'];
    $host="mysql:host={$dbconfig['dbhost']};port={$dbconfig['port']};dbname={$dbconfig['dbname']};";
    $user=$dbconfig['dbuser'];
    $pass=$dbconfig['dbpwd'];

}else{
    exit('错误 没找到程序类型');
}
//分类获取和商品添加
try{
    $DB=new PDO($host,$user,$pass);
}catch(PDOException $e){
    print "数据库连接失败! " . $e->getMessage() . "<br/>";
    exit;
}


if(isset($_GET['flhq'])){
    if($caihong || $chenmeng){
        
        $class = $DB->query("select * from {$qz}_class order by sort ASC");
        
        $price=$DB->query("select * from {$qz}_price");
        
        $shequ=$DB->query("select * from {$qz}_shequ");
        
    }else if($xiaochu){
        $class = $DB->query("select * from sky_class order by sort ASC");
        $price=$DB->query("select * from sky_single_rule");
        $shequ=$DB->query("select * from sky_shequ");

    }else{
        exit;
    }
    $class=$class->fetchall(PDO::FETCH_ASSOC);
    $price=$price->fetchall(PDO::FETCH_ASSOC);
    $shequ=$shequ->fetchall(PDO::FETCH_ASSOC);
    if($chenmeng){
        //沉梦解密社区密码
        $_COOKIE["admin_token"]=$_SERVER['HTTP_CMTOKEN'];
        $num= 0;
        include "./includes/common.php";
        foreach ($shequ as $s){
            
            $str = strSafeEnCode($s['password'], "DECODE");
            
            $shequ[$num]['password']=$str;
            
            $num++;
        }
    }
    $json=[
        '分类'=>$class,
        '价格模板'=>$price,
        '社区'=>$shequ,
        'type'=>$type,
    ];
    exit(json_encode($json));

}elseif(isset($_GET['additem'])){//添加商品
    $json=file_get_contents('php://input');
    
    $json=json_decode($json,true);
    $arr=[
		'code'=>-1,
		'msg'=>''
	];
    $table="{$qz}_tools";
    if($xiaochu){
        $table='sky_goods';

    }
    $c=insert($table,$json);
    if($c[0]){
		$arr['code']=100;
		$arr['msg']='ok';
		echo json_encode($arr);
        exit();
    }else{
        
        $arr['msg']=$c[1];
        echo json_encode($arr);
        exit();
    }
    
}else if(isset($_GET['cookie'])){
    include "./includes/common.php";
    $user = $conf['adm_user'];
    $session = md5($user . $conf['adm_pwd'] . $password_hash);
    $token   = authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
    $arr['code']=100;
    $arr['msg']=$token;
    echo json_encode($arr);
    exit();

}else if(isset($_GET['sphq'])){
    $sqid=intval($_GET['sqid']);
    $sql='';
    if($caihong || $chenmeng){
        $sql="select `goods_id`,`goods_type`,`goods_param` from {$qz}_tools where shequ= '{$sqid}'";
        $shops = $DB->query($sql);

        
    }else if($xiaochu){
        $sql="select `extend` from sky_goods where sqid= '{$sqid}'";
        $shops = $DB->query($sql);


    }else{
        $shops=['错误'];
        exit(json_encode($shops));
    }
    
    $shoplist=$shops->fetchall(PDO::FETCH_ASSOC);
    exit(json_encode($shoplist));
}else if(isset($_GET['class'])){
    //增加分类
    $json=file_get_contents('php://input');
    
    $json=json_decode($json,true);
    $arr=[
		'code'=>-1,
		'msg'=>''
	];
    $table="{$qz}_class";
    if($xiaochu){
        $table='sky_class';

    }
    $c=insert($table,$json);
    if($c[0]){
		$arr['code']=100;
		$arr['msg']='ok';
		echo json_encode($arr);
        exit();
    }else{
        
        $arr['msg']=$c[1];
        echo json_encode($arr);
        exit();
    }
}else if(isset($_GET['update'])){
    //更新
    $json=file_get_contents('php://input');
    
    $json=json_decode($json,true);
    $arr=[
		'code'=>-1,
		'msg'=>''
	];

    $table="{$qz}_{$json['table']}";
    if($xiaochu){
        $table='sky_'.$json['table'];

    }
    $c=update($table,$json['data'],$json['where']);
    if($c[0]){
		$arr['code']=100;
		$arr['msg']='ok';
		echo json_encode($arr);
        exit();
    }else{
        
        $arr['msg']=$c[1];
        echo json_encode($arr);
        exit();
    }
}else if(isset($_GET['updatearr'])){
    //更新
    $json=file_get_contents('php://input');
    
    $json=json_decode($json,true);
    $arr=[
		'code'=>-1,
		'msg'=>''
	];

    $table="{$qz}_{$json[0]['table']}";
    if($xiaochu){
        $table='sky_'.$json[0]['table'];

    }
    $num=0;
    $err='无';
    foreach($json as $j){
		if($xiaochu){
			$j['data']['update_dat']=date('Y-m-d H:i:s', time());
		}else{
			$j['data']['uptime']=time();
		}
        
        $c=update($table,$j['data'],$j['where']);
        if($c[0]){
            $num++;
        }else{
            if ($err=='无'){
                $err=$c[1];
            }
            
        }
    }
    
    $arr['code']=100;
	$arr['msg']=$err;
    $arr['num']=$num;
    exit(json_encode($arr));

}else if(isset($_GET['query'])){
    //查询
    $json=file_get_contents('php://input');
    
    $json=json_decode($json,true);
    $arr=[
		'code'=>-1,
		'msg'=>''
	];

    
    if($xiaochu){
        $table='sky_'.$json['table'];

    }else{
        $table="{$qz}_{$json['table']}";
    }
    $c=query($table,$json['data'],$json['where']);
    
    if($c[0]){
		$arr['code']=100;
		$arr['msg']='ok';
        $arr['data']=$c[1];
        $arr['sql']=$c[2];
		echo json_encode($arr);
        exit();
    }else{
        
        $arr['msg']=$c[1];
        echo json_encode($arr);
        exit();
    }
}else{

}
function query($table,$data,$where=""){
    global $DB;
    
    $sql = "select {$data} from {$table}  {$where}";
    
    
    $row=$DB->query($sql);
    $row2=$row->fetchall(PDO::FETCH_ASSOC);
    
    
    if($row2){
		
		return array(true,$row2,$sql);
    }else{
        return array(false,'mysql查询失败'.$row->errorInfo()[2]);
    }
}

function prefix($table,$qz){
    return str_replace('pre',$qz,$table);
}




function insert($table,$data){
    global $DB;
    
    $keys = array_keys($data);
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $escapedKeys = array_map(function ($key) {
        return "`{$key}`";
    }, $keys);
    
    $sql = "INSERT IGNORE INTO {$table} (" . implode(', ', $escapedKeys) . ") VALUES ({$placeholders})";
    
    $stmt = $DB->prepare($sql);
    $rowCount = $stmt->execute(array_values($data));
    

    
    if($rowCount){
		$num=$DB->lastInsertId();
		$sql="UPDATE {$table} SET `sort`='{$num}' WHERE `tid`='{$num}'";

        $DB->exec($sql);
		return array(true,"{$num}");
    }else{
        return array(false,'mysql数据插入失败'.$stmt->errorInfo()[2]);
    }
}
function update($table,$data,$where=""){
    global $DB;
    
    $keys = array_keys($data);
    //$placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $escapedKeys = array_map(function ($key) {
        return "`{$key}`=?";
    }, $keys);
    
    $sql = "update {$table} set " . implode(', ', $escapedKeys) . " {$where}";
    
    $stmt = $DB->prepare($sql);
    $rowCount = $stmt->execute(array_values($data));
    

    
    if($rowCount){
		
		return array(true,"{$rowCount}");
    }else{
        return array(false,'mysql数据更新失败'.$stmt->errorInfo()[2]);
    }
}

?>