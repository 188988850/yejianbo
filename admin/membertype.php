<?php
// 会员类型管理页面（membertype.php）
require_once 'common.php'; // 假设有数据库连接和权限校验

// 处理增删改查逻辑
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if ($action === 'add') {
        $name = trim($_POST['name']);
        $price = floatval($_POST['price']);
        $duration = intval($_POST['duration']);
        $rights = trim($_POST['rights']);
        $pay_methods = trim($_POST['pay_methods']);
        $invite_limit = intval($_POST['invite_limit']);
        $status = intval($_POST['status']);
        $DB->exec("INSERT INTO shua_user_level (name,price,duration,rights,pay_methods,invite_limit,status) VALUES (?,?,?,?,?,?,?)", [$name,$price,$duration,$rights,$pay_methods,$invite_limit,$status]);
        header('Location: membertype.php');exit;
    } elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $price = floatval($_POST['price']);
        $duration = intval($_POST['duration']);
        $rights = trim($_POST['rights']);
        $pay_methods = trim($_POST['pay_methods']);
        $invite_limit = intval($_POST['invite_limit']);
        $status = intval($_POST['status']);
        $DB->exec("UPDATE shua_user_level SET name=?,price=?,duration=?,rights=?,pay_methods=?,invite_limit=?,status=? WHERE id=?", [$name,$price,$duration,$rights,$pay_methods,$invite_limit,$status,$id]);
        header('Location: membertype.php');exit;
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        $DB->exec("DELETE FROM shua_user_level WHERE id=?", [$id]);
        header('Location: membertype.php');exit;
    }
}

// 查询会员类型列表
$types = $DB->query("SELECT * FROM shua_user_level ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>会员类型管理</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>会员类型管理</h2>
    <form method="post" class="form-inline" style="margin-bottom:20px;">
        <input type="hidden" name="action" value="add">
        <input type="text" name="name" placeholder="名称" class="form-control" required>
        <input type="number" name="price" placeholder="价格" class="form-control" required>
        <input type="number" name="duration" placeholder="时长(天)" class="form-control" required>
        <input type="text" name="rights" placeholder="权益描述" class="form-control">
        <input type="text" name="pay_methods" placeholder="支付方式(逗号分隔)" class="form-control">
        <input type="number" name="invite_limit" placeholder="邀请门槛" class="form-control">
        <select name="status" class="form-control"><option value="1">启用</option><option value="0">禁用</option></select>
        <button type="submit" class="btn btn-success">添加</button>
    </form>
    <table class="table table-bordered">
        <tr><th>ID</th><th>名称</th><th>价格</th><th>时长</th><th>权益</th><th>支付方式</th><th>邀请门槛</th><th>状态</th><th>操作</th></tr>
        <?php foreach($types as $type){ ?>
        <tr>
            <form method="post" class="form-inline">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?=$type['id']?>">
                <td><?=$type['id']?></td>
                <td><input type="text" name="name" value="<?=$type['name']?>" class="form-control" required></td>
                <td><input type="number" name="price" value="<?=$type['price']?>" class="form-control" required></td>
                <td><input type="number" name="duration" value="<?=$type['duration']?>" class="form-control" required></td>
                <td><input type="text" name="rights" value="<?=$type['rights']?>" class="form-control"></td>
                <td><input type="text" name="pay_methods" value="<?=$type['pay_methods']?>" class="form-control"></td>
                <td><input type="number" name="invite_limit" value="<?=$type['invite_limit']?>" class="form-control"></td>
                <td><select name="status" class="form-control"><option value="1"<?=$type['status']?' selected':''?>>启用</option><option value="0"<?=!$type['status']?' selected':''?>>禁用</option></select></td>
                <td>
                    <button type="submit" class="btn btn-primary btn-xs">保存</button>
            </form>
            <form method="post" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?=$type['id']?>">
                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('确定删除？')">删除</button>
            </form>
                </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html> 