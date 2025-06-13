<?php
if (!defined('IN_CRONLITE')) die();

if($islogin2 == 1){} else {
    exit("<script language='javascript'>window.location.href='./user/login.php';</script>");
}

// 获取用户余额信息
$user_rmb = $userrow['rmb'];
$frozen_rmb = $DB->getColumn("SELECT SUM(input5) FROM `shua_orders` WHERE userid='{$userrow['zid']}' AND status=0");

// 获取订单列表
$orders = $DB->query("SELECT * FROM `shua_orders` WHERE userid='{$userrow['zid']}' ORDER BY addtime DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的余额</title>
    <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .balance-card {
            background: #007bff;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .balance-amount {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }
        .frozen-amount {
            color: #ffc107;
            font-size: 18px;
            margin-top: 10px;
        }
        .order-list {
            margin-top: 20px;
        }
        .order-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .order-info {
            color: #666;
            font-size: 14px;
        }
        .order-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-top: 5px;
        }
        .status-pending {
            background: #ffc107;
            color: #000;
        }
        .status-success {
            background: #28a745;
            color: #fff;
        }
        .status-cancelled {
            background: #dc3545;
            color: #fff;
        }
        .notice-box {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="balance-card">
            <h2>我的余额</h2>
            <div class="balance-amount">￥<?php echo number_format($user_rmb, 2); ?></div>
            <div class="frozen-amount">冻结金额: ￥<?php echo number_format($frozen_rmb, 2); ?></div>
        </div>

        <div class="notice-box">
            <h4>温馨提示</h4>
            <p>1. 冻结金额为未完成订单的金额,订单完成后自动解冻</p>
            <p>2. 只有解冻后的金额才能提现</p>
            <p>3. 订单状态为"待支付"时,金额将被冻结</p>
            <p>4. 取消订单后,冻结金额将自动解冻</p>
        </div>

        <div class="order-list">
            <h3>订单记录</h3>
            <?php while($order = $orders->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="order-item">
                <div class="order-title"><?php echo htmlspecialchars($order['input4']); ?></div>
                <div class="order-info">
                    <p>订单号: <?php echo $order['trade_no']; ?></p>
                    <p>金额: ￥<?php echo number_format($order['input5'], 2); ?></p>
                    <p>时间: <?php echo $order['addtime']; ?></p>
                </div>
                <div class="order-status <?php echo $order['status']==1?'status-success':($order['status']==-1?'status-cancelled':'status-pending'); ?>">
                    <?php echo $order['status']==1?'已完成':($order['status']==-1?'已取消':'待支付'); ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
</body>
</html> 