<?php
if(!defined('IN_CRONLITE'))die();

$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

if($islogin2==1){
    $price_obj = new \lib\Price($userrow['zid'],$userrow);
}elseif($is_fenzhan == true){
    $price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
    $price_obj = new \lib\Price(1);
}

$uid = $userrow['uid'];

switch($act){
    case 'withdraw': //申请提现
        $amount = floatval($_POST['amount']);
        $type = daddslashes($_POST['type']);
        $account = daddslashes($_POST['account']);
        $name = daddslashes($_POST['name']);

        if($amount <= 0){
            exit('{"code":-1,"msg":"提现金额必须大于0"}');
        }

        // 检查可提现佣金
        $available = $DB->getColumn("SELECT SUM(commission) FROM shua_orders WHERE uid='$uid' AND status=1");
        if($amount > $available){
            exit('{"code":-1,"msg":"提现金额不能大于可提现佣金"}');
        }

        // 检查最小提现金额
        if($amount < $conf['min_withdraw']){
            exit('{"code":-1,"msg":"提现金额不能小于'.$conf['min_withdraw'].'元"}');
        }

        // 检查提现频率
        $last_withdraw = $DB->getRow("SELECT * FROM shua_withdraws WHERE uid='$uid' ORDER BY id DESC LIMIT 1");
        if($last_withdraw && (time()-$last_withdraw['addtime']) < 3600){
            exit('{"code":-1,"msg":"提现申请过于频繁,请稍后再试"}');
        }

        // 创建提现记录
        $data = array(
            'uid' => $uid,
            'amount' => $amount,
            'type' => $type,
            'account' => $account,
            'name' => $name,
            'status' => 0,
            'addtime' => time()
        );
        if($DB->insert('shua_withdraws', $data)){
            exit('{"code":0,"msg":"提现申请已提交,请等待处理"}');
        }else{
            exit('{"code":-1,"msg":"提现申请失败,请重试"}');
        }
        break;

    case 'get_commission': //获取佣金信息
        $frozen = $DB->getColumn("SELECT SUM(commission) FROM shua_orders WHERE uid='$uid' AND status=0");
        $available = $DB->getColumn("SELECT SUM(commission) FROM shua_orders WHERE uid='$uid' AND status=1");
        $total = $DB->getColumn("SELECT SUM(commission) FROM shua_orders WHERE uid='$uid'");
        
        $data = array(
            'frozen' => number_format($frozen,2),
            'available' => number_format($available,2),
            'total' => number_format($total,2)
        );
        exit(json_encode(array('code'=>0,'msg'=>'success','data'=>$data)));
        break;

    default:
        exit('{"code":-1,"msg":"未知操作"}');
        break;
}
?> 