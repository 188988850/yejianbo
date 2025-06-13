<?php
// 会员订单管理页面（admin/memberorder.php）
require_once '../common.php'; // 数据库连接和权限校验

// 筛选条件
$where = 'WHERE 1';
if (!empty($_GET['user'])) {
    $where .= " AND user = '".addslashes($_GET['user'])."'";
}
if (!empty($_GET['type'])) {
    $where .= " AND type = '".addslashes($_GET['type'])."'";
}
if (!empty($_GET['status'])) {
    $where .= " AND status = '".addslashes($_GET['status'])."'";
}

// 查询订单列表（假设表名 shua_orders，会员类型表 shua_user_level）
$sql = "SELECT o.*, u.name as username, l.name as typename FROM shua_orders o LEFT JOIN shua_user u ON o.user=u.id LEFT JOIN shua_user_level l ON o.type=l.id $where ORDER BY o.id DESC LIMIT 100";
$orders = $DB->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>会员订单管理</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>会员订单管理</h2>
    <form class="form-inline" method="get" style="margin-bottom:20px;">
        <input type="text" name="user" placeholder="用户名/ID" class="form-control" value="<?=isset($_GET['user'])?htmlspecialchars($_GET['user']):''?>">
        <input type="text" name="type" placeholder="会员类型" class="form-control" value="<?=isset($_GET['type'])?htmlspecialchars($_GET['type']):''?>">
        <select name="status" class="form-control">
            <option value="">全部状态</option>
            <option value="已支付"<?php if(isset($_GET['status'])&&$_GET['status']==='已支付')echo ' selected';?>>已支付</option>
            <option value="未支付"<?php if(isset($_GET['status'])&&$_GET['status']==='未支付')echo ' selected';?>>未支付</option>
        </select>
        <button type="submit" class="btn btn-primary">筛选</button>
    </form>
    <table class="table table-bordered">
        <tr><th>ID</th><th>用户</th><th>会员类型</th><th>金额</th><th>时长</th><th>状态</th><th>支付时间</th><th>到期时间</th></tr>
        <?php foreach($orders as $order){ ?>
        <tr>
            <td><?=$order['id']?></td>
            <td><?=$order['username']?> (<?=$order['user']?>)</td>
            <td><?=$order['typename']?></td>
            <td><?=$order['money']?></td>
            <td><?=$order['duration']?>天</td>
            <td><?=$order['status']?></td>
            <td><?=$order['pay_time']?></td>
            <td><?=$order['expire_time']?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html> 