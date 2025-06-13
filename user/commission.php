<?php
if(!defined('IN_CRONLITE'))die();

if($islogin2==1){
    $price_obj = new \lib\Price($userrow['zid'],$userrow);
    if($userrow['status']==0){
        sysmsg('你的账号已被封禁！',true);exit;
    }elseif($userrow['power']>0 && $conf['fenzhan_expiry']>0 && $userrow['endtime']<$date){
        sysmsg('你的账号已到期，请联系管理员续费！',true);exit;
    }
}elseif($is_fenzhan == true){
    $price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
    $price_obj = new \lib\Price(1);
}

$uid = $userrow['uid'];

// 获取佣金信息
$frozen = $DB->getColumn("SELECT SUM(commission) FROM shua_orders WHERE uid='$uid' AND status=0");
$available = $DB->getColumn("SELECT SUM(commission) FROM shua_orders WHERE uid='$uid' AND status=1");
$total = $DB->getColumn("SELECT SUM(commission) FROM shua_orders WHERE uid='$uid'");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title>佣金管理-<?php echo $conf['sitename']; ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>template/storenews/user/yangshi/foxui1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>template/storenews/user/yangshi/style1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>template/storenews/user/yangshi/member.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver?>assets/store/css/user1.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/toastr.min.css">
    <link rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/layui.css">
    <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
</head>
<body>
<div class="foxui-panel">
    <div class="foxui-panel-head">
        <div class="foxui-panel-title">佣金管理</div>
    </div>
    <div class="foxui-panel-body">
        <div class="foxui-commission-box">
            <div class="foxui-commission-item">
                <div class="foxui-commission-label">总佣金</div>
                <div class="foxui-commission-amount">￥<?php echo number_format($total,2)?></div>
            </div>
            <div class="foxui-commission-item">
                <div class="foxui-commission-label">可提现</div>
                <div class="foxui-commission-amount">￥<?php echo number_format($available,2)?></div>
            </div>
            <div class="foxui-commission-item">
                <div class="foxui-commission-label">冻结中</div>
                <div class="foxui-commission-amount">￥<?php echo number_format($frozen,2)?></div>
            </div>
        </div>
        <a href="withdraw.php" class="foxui-btn foxui-btn-primary foxui-btn-block" <?php echo $available<=0?'disabled':''?>>申请提现</a>
    </div>
</div>

<div class="foxui-panel">
    <div class="foxui-panel-head">
        <div class="foxui-panel-title">佣金说明</div>
    </div>
    <div class="foxui-panel-body">
        <div class="foxui-commission-desc">
            <p>1. 订单完成后,佣金将自动解冻并计入可提现金额</p>
            <p>2. 可提现金额达到<?php echo $conf['min_withdraw']?>元即可申请提现</p>
            <p>3. 提现申请将在1-3个工作日内处理完成</p>
            <p>4. 如有疑问请联系客服处理</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // 定时更新佣金信息
    setInterval(function(){
        $.get('<?php echo $cdnpublic?>jianyun/ajax/ajax_commission.php?act=get_commission',function(data){
            if(data.code==0){
                $('.foxui-commission-amount').eq(0).text('￥'+data.data.total);
                $('.foxui-commission-amount').eq(1).text('￥'+data.data.available);
                $('.foxui-commission-amount').eq(2).text('￥'+data.data.frozen);
                
                // 更新提现按钮状态
                if(parseFloat(data.data.available) <= 0){
                    $('.foxui-btn').addClass('disabled');
                }else{
                    $('.foxui-btn').removeClass('disabled');
                }
            }
        },'json');
    },30000);
});
</script>
</body>
</html> 