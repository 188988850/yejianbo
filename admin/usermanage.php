<?php
// 用户管理页面（admin/usermanage.php）
require_once '../common.php'; // 数据库连接和权限校验

// 处理手动操作
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = intval($_POST['id']);
    if ($action === 'open_member') {
        $level = intval($_POST['level']);
        $expire = date('Y-m-d H:i:s', strtotime('+'.intval($_POST['duration']).' days'));
        $DB->exec("UPDATE shua_user SET member_type=?, expire_time=? WHERE id=?", [$level, $expire, $id]);
    } elseif ($action === 'close_member') {
        $DB->exec("UPDATE shua_user SET member_type=0, expire_time=NULL WHERE id=?", [$id]);
    } elseif ($action === 'disable') {
        $DB->exec("UPDATE shua_user SET status=0 WHERE id=?", [$id]);
    } elseif ($action === 'enable') {
        $DB->exec("UPDATE shua_user SET status=1 WHERE id=?", [$id]);
    }
    header('Location: usermanage.php');exit;
}

// 筛选条件
$where = 'WHERE 1';
if (!empty($_GET['name'])) {
    $where .= " AND name LIKE '%".addslashes($_GET['name'])."%'";
}
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $where .= " AND status = '".addslashes($_GET['status'])."'";
}

// 查询用户列表（假设表名 shua_user，会员类型表 shua_user_level）
$sql = "SELECT u.*, l.name as levelname FROM shua_user u LEFT JOIN shua_user_level l ON u.member_type=l.id $where ORDER BY u.id DESC LIMIT 100";
$users = $DB->query($sql)->fetchAll();
$levels = $DB->query("SELECT * FROM shua_user_level WHERE status=1")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>用户管理</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>用户管理</h2>
    <form class="form-inline" method="get" style="margin-bottom:20px;">
        <input type="text" name="name" placeholder="用户名" class="form-control" value="<?=isset($_GET['name'])?htmlspecialchars($_GET['name']):''?>">
        <select name="status" class="form-control">
            <option value="">全部状态</option>
            <option value="1"<?php if(isset($_GET['status'])&&$_GET['status']==='1')echo ' selected';?>>正常</option>
            <option value="0"<?php if(isset($_GET['status'])&&$_GET['status']==='0')echo ' selected';?>>禁用</option>
        </select>
        <button type="submit" class="btn btn-primary">筛选</button>
    </form>
    <table class="table table-bordered">
        <tr><th>ID</th><th>用户名</th><th>会员类型</th><th>到期时间</th><th>状态</th><th>操作</th></tr>
        <?php foreach($users as $user){ ?>
        <tr>
            <td><?=$user['id']?></td>
            <td><?=$user['name']?></td>
            <td><?=$user['levelname']?></td>
            <td><?=$user['expire_time']?></td>
            <td><?=$user['status']?'正常':'禁用'?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?=$user['id']?>">
                    <input type="hidden" name="action" value="open_member">
                    <select name="level" class="form-control input-sm">
                        <?php foreach($levels as $level){ ?><option value="<?=$level['id']?>"><?=$level['name']?></option><?php } ?>
                    </select>
                    <input type="number" name="duration" value="30" class="form-control input-sm" style="width:70px;" placeholder="天数">
                    <button type="submit" class="btn btn-success btn-xs">开通会员</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?=$user['id']?>">
                    <input type="hidden" name="action" value="close_member">
                    <button type="submit" class="btn btn-warning btn-xs">关闭会员</button>
                </form>
                <?php if($user['status']){ ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?=$user['id']?>">
                    <input type="hidden" name="action" value="disable">
                    <button type="submit" class="btn btn-danger btn-xs">禁用账号</button>
                </form>
                <?php }else{ ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?=$user['id']?>">
                    <input type="hidden" name="action" value="enable">
                    <button type="submit" class="btn btn-info btn-xs">启用账号</button>
                </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html> 