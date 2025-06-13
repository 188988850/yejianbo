<?php
// 佣金设置页面（admin/commission.php）
require_once '../common.php'; // 数据库连接和权限校验

// 读取当前佣金设置（假设表名 shua_fxbl）
$setting = $DB->query("SELECT * FROM shua_fxbl LIMIT 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invite_commission = floatval($_POST['invite_commission']);
    $resource_commission = floatval($_POST['resource_commission']);
    $level = intval($_POST['level']);
    if ($setting) {
        $DB->exec("UPDATE shua_fxbl SET invite_commission=?, resource_commission=?, level=? WHERE id=?", [$invite_commission, $resource_commission, $level, $setting['id']]);
    } else {
        $DB->exec("INSERT INTO shua_fxbl (invite_commission, resource_commission, level) VALUES (?,?,?)", [$invite_commission, $resource_commission, $level]);
    }
    header('Location: commission.php');exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>佣金设置</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>佣金设置</h2>
    <form method="post" class="form-horizontal" style="max-width:500px;">
        <div class="form-group">
            <label class="control-label col-sm-4">邀请佣金比例（%）</label>
            <div class="col-sm-8">
                <input name="invite_commission" value="<?=isset($setting['invite_commission'])?$setting['invite_commission']:''?>" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4">资源返佣比例（%）</label>
            <div class="col-sm-8">
                <input name="resource_commission" value="<?=isset($setting['resource_commission'])?$setting['resource_commission']:''?>" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4">分销层级</label>
            <div class="col-sm-8">
                <input name="level" value="<?=isset($setting['level'])?$setting['level']:''?>" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" class="btn btn-success">保存设置</button>
            </div>
        </div>
    </form>
</div>
</body>
</html> 