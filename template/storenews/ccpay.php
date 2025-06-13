<?php if($islogin2==1){
if($userrow['status']==0){
	sysmsg('你的账号已被封禁！',true);exit;
}elseif($userrow['power']>0 && $conf['fenzhan_expiry']>0 && $userrow['endtime']<$date){
	sysmsg('你的账号已到期，请联系管理员续费！',true);exit;
}
}
if($islogin2==1){
    // 如果 $islogin2 的值等于 1
    $cookiesid = $userrow['zid'];
    
    if($siterow['power'] == 2) {
    $current_zid = $siterow['zid'];
} else {
    $uu = $siterow['zid'];
    $rs = $DB->query("SELECT upzid FROM shua_site WHERE zid='{$uu}'");
    $row = $rs->fetch();
    
    if($row['upzid']) {
        // 上级存在
        do {
            $rs = $DB->query("SELECT power FROM shua_site WHERE zid='{$uu}'");
            $row = $rs->fetch();
            if($row['power']!= 2) {
                $rs = $DB->query("SELECT upzid FROM shua_site WHERE zid='{$uu}' ");
                $row = $rs->fetch();
                $uu = $row['upzid'];
            }
        } while($row['power']!= 2);
    
        $current_zid = $uu;
    } else {
        // 上级不存在
        $current_zid = $siterow['zid'];
    }
}
     $powereee = $current_zid;
} else {
    // 如果 $islogin2 的值不等于 1，弹出提示并跳转到 baidu.com
    echo "<script>alert('您当前未登录，请登录后再操作！');window.location.href='/user/login.php';</script>";
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $row = $DB->getRow("SELECT * FROM shua_sscc WHERE id = '$id' LIMIT 1");

    if($row) {
        // 如果 获取金额

        $year = date('Y'); 
        $month = date('m'); 
        $day = date('d'); 
        $timestamp = date('YmdHis'); 
        $trade_no = $year. '11111111'. $id. '_'. $cookiesid; 
        $tc1 = $row['zid'];
        $money = $row['money'];
         $name = $row['name'];
           $zidValue = $row['zid'];
        // 检查用户是否有未完成的交易
        $existingTransaction = $DB->getRow("SELECT * FROM shua_pay WHERE zid = '$cookiesid' AND status = 1 AND tid = '$id'");
        $exisf = $DB->getRow("SELECT rmb FROM shua_site WHERE zid = '$cookiesid' ");
     
        if($existingTransaction) {
            echo '<script> window.location.href = "./?mod=dingddu&id=' . $id . '";</script>';
        } else {
            if ($powereee == $cookiesid) {
                echo '<script>alert("本人无需购买，前往查看"); window.location.href = "./?mod=dingddu&id=' . $id . '";</script>';
            }
            if ($exisf['rmb'] < $money) {
                echo '<script>alert("您当前余额不足，请先进行充值再购买"); window.location.href = "/user/recharge.php";</script>';
            } else {
                // 插入数据到 pay 表
                $sql = "INSERT INTO shua_pay (addtime,zid, tid, trade_no, money, status) VALUES ('$timestamp','$cookiesid', '$id', '$trade_no', '$money', 0)";
                $DB->query($sql);

                // 更新 site 表
                $sql_update = "UPDATE shua_site SET rmb = rmb - $money WHERE zid = $cookiesid AND rmb > $money";
                $DB->query($sql_update);

     $sql_updffssd = "INSERT INTO shua_points (addtime,fufei,zid, action, bz, point, status) VALUES ('$timestamp','1','$cookiesid', '消费', '购买 群聊$name', '$money', 0)";
                $DB->query($sql_updffssd);



                // 更新 shua_pay 表中 trade_no 等于变量 trade_no 的数据的 status 为1
                $sql_update_pay = "UPDATE shua_pay SET status = 1 WHERE trade_no = '$trade_no'";
                $DB->query($sql_update_pay);

                // 弹窗提示支付成功并跳转到 baidu.cn
                echo '<script>alert("支付成功！"); window.location.href = "./?mod=dingddu&id=' . $id . '";</script>';
            
                $sql_updff = "UPDATE shua_sscc SET gg = gg + 1 WHERE id = $id ";
                $DB->query($sql_updff);
   
   
   $rrm = $money *  $conf['yyu']  / 100;
        $sql_upffs = "UPDATE shua_site SET rmbtc = rmbtc + $rrm WHERE zid = $tc1";
        
        
        $sql_upffsss = "UPDATE shua_site SET rmb = rmb + $rrm WHERE zid = $tc1";
       $DB->query($sql_upffsss);
       
        $sql_updfggg = "INSERT INTO shua_points (addtime, fufei,zid, action, bz, point, status) 
                       VALUES ('$timestamp','1', '$tc1', '提成', '有人购买你的群聊 获得{$rrm}元提成', '$rrm', 0)";
        $DB->query($sql_updfggg);
        
        $DB->query($sql_upffs);


                
            }
        }
    }
 
    
}
else {
    echo "未获取到 ID 参数";
}

?>
