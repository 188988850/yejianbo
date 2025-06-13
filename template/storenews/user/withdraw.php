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

// 获取可用佣金
$available_commission = $DB->getColumn("SELECT COALESCE(SUM(commission), 0) FROM shua_orders WHERE uid='{$userrow['uid']}' AND status=1");

// 获取提现记录
$withdraws = $DB->getAll("SELECT * FROM shua_withdraws WHERE uid='{$userrow['uid']}' ORDER BY addtime DESC LIMIT 10");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>申请提现 - <?php echo $conf['sitename']; ?></title>
    <link rel="stylesheet" href="<?php echo $cdnpublic?>bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/style1.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/member.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/store/css/iconfont.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/store/css/user1.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/toastr.min.css">
    <link rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/layui.css">
    <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
</head>
<body>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">申请提现</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                当前可用佣金：<span class="text-danger">￥<?php echo number_format($available_commission, 2); ?></span>
            </div>
            
            <form id="withdraw-form">
                <div class="form-group">
                    <label>提现金额</label>
                    <div class="input-group">
                        <span class="input-group-addon">￥</span>
                        <input type="number" class="form-control" name="amount" required min="<?php echo $conf['tixian_min']; ?>" max="<?php echo $available_commission; ?>" step="0.01">
                    </div>
                    <small class="help-block">单次提现金额不低于<?php echo $conf['tixian_min']; ?>元</small>
                </div>
                
                <div class="form-group">
                    <label>提现方式</label>
                    <select class="form-control" name="type" required>
                        <option value="">请选择提现方式</option>
                        <?php if($conf['fenzhan_tixian_alipay']==1){ ?>
                        <option value="alipay">支付宝</option>
                        <?php } ?>
                        <?php if($conf['fenzhan_tixian_wx']==1){ ?>
                        <option value="wxpay">微信支付</option>
                        <?php } ?>
                        <?php if($conf['fenzhan_tixian_qq']==1){ ?>
                        <option value="qqpay">QQ钱包</option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>收款账号</label>
                    <input type="text" class="form-control" name="account" required>
                </div>
                
                <div class="form-group">
                    <label>收款人姓名</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">申请提现</button>
                </div>
            </form>
            
            <div class="withdraw-history">
                <h4>提现记录</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>提现时间</th>
                                <th>提现金额</th>
                                <th>提现方式</th>
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($withdraws as $withdraw){ ?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i:s', $withdraw['addtime']); ?></td>
                                <td>￥<?php echo number_format($withdraw['amount'], 2); ?></td>
                                <td><?php echo $withdraw['type']=='alipay'?'支付宝':($withdraw['type']=='wxpay'?'微信支付':'QQ钱包'); ?></td>
                                <td>
                                    <?php if($withdraw['status']==0){ ?>
                                    <span class="label label-warning">处理中</span>
                                    <?php }elseif($withdraw['status']==1){ ?>
                                    <span class="label label-success">已完成</span>
                                    <?php }else{ ?>
                                    <span class="label label-danger">已拒绝</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#withdraw-form').submit(function(e) {
        e.preventDefault();
        
        var amount = $('input[name="amount"]').val();
        var type = $('select[name="type"]').val();
        var account = $('input[name="account"]').val();
        var name = $('input[name="name"]').val();
        
        if(!amount || amount < <?php echo $conf['tixian_min']; ?>) {
            layer.msg('提现金额不能低于<?php echo $conf['tixian_min']; ?>元', {icon: 2});
            return;
        }
        
        if(!type) {
            layer.msg('请选择提现方式', {icon: 2});
            return;
        }
        
        if(!account) {
            layer.msg('请输入收款账号', {icon: 2});
            return;
        }
        
        if(!name) {
            layer.msg('请输入收款人姓名', {icon: 2});
            return;
        }
        
        $.ajax({
            url: '<?php echo $cdnpublic?>jianyun/ajax/ajax_commission.php?act=withdraw',
            type: 'POST',
            data: {
                amount: amount,
                type: type,
                account: account,
                name: name
            },
            dataType: 'json',
            success: function(data) {
                if(data.code == 0) {
                    layer.msg('提现申请已提交，请等待处理', {icon: 1});
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
            }
        });
    });
});
</script>

<style>
.withdraw-history {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.withdraw-history h4 {
    margin-bottom: 15px;
}
</style>
</body>
</html> 