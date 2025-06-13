<?php
//通信密码
//例子$code='9999asas';
$code='asd4546qwe123';

$ctime=$_SERVER['HTTP_TIME'];
$ctoken=isset($_SERVER['HTTP_TOKEN'])?$_SERVER['HTTP_TOKEN']:1;

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


if($_GET['flhq']){
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

}elseif($_GET['additem']){//添加商品
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
    
}else if($_GET['cookie']){
    include "./includes/common.php";
    $user = $conf['adm_user'];
    $session = md5($user . $conf['adm_pwd'] . $password_hash);
    $token   = authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
    $arr['code']=100;
    $arr['msg']=$token;
    echo json_encode($arr);
    exit();

}else if($_GET['sphq']){
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
}


function prefix($table,$qz){
    return str_replace('pre',$qz,$table);
}




function insert($table,$data){
    global $DB;
    // $values = '';
    // foreach ($data as $k=>$v){
    //     $keys[] = "`{$k}`";
    //     $values .= "'{$v}', ";
        
    // }
	// $values=trim($values,', ');
    // $sql="INSERT INTO {$table} (".implode(', ', $keys).") VALUES ({$values})";
    //$rowCount = $DB->exec($sql);
    $keys = array_keys($data);
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $escapedKeys = array_map(function ($key) {
        return "`{$key}`";
    }, $keys);
    
    $sql = "INSERT INTO {$table} (" . implode(', ', $escapedKeys) . ") VALUES ({$placeholders})";
    
    $stmt = $DB->prepare($sql);
    $rowCount = $stmt->execute(array_values($data));
    

    
    if($rowCount){
		$num=$DB->lastInsertId();
		$sql="UPDATE {$table} SET `sort`='{$num}' WHERE `tid`='{$num}'";

        $DB->exec($sql);
		return array(true,'');
    }else{
        return array(false,$stmt->errorInfo()[2].'|');
    }
}

?>