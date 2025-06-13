<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("../includes/common.php");
$title='导航管理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
require_once '../includes/error.class.php';
require_once '../config.php';
require_once '../includes/db.class.php';
if (!isset($dbconfig['charset'])) $dbconfig['charset'] = 'utf8mb4';
if (!isset($dbconfig['pconnect'])) $dbconfig['pconnect'] = 0;
$DB = DB::getInstance($dbconfig);

// 新增或编辑导航
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $icon = trim($_POST['icon']);
    $icon2 = isset($_POST['icon2']) ? trim($_POST['icon2']) : '';
    $bg = isset($_POST['bg']) ? trim($_POST['bg']) : '';
    $url = trim($_POST['url']);
    $is_circle = isset($_POST['is_circle']) ? 1 : 0;
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id > 0) {
        $DB->query("UPDATE `{$dbconfig['dbqz']}_news_nav` SET name=?, icon=?, icon2=?, bg=?, url=?, is_circle=? WHERE id=?", [$name, $icon, $icon2, $bg, $url, $is_circle, $id]);
    } else {
        $DB->query("INSERT INTO `{$dbconfig['dbqz']}_news_nav` (name, icon, icon2, bg, url, is_circle, status) VALUES (?, ?, ?, ?, ?, ?, 1)", [$name, $icon, $icon2, $bg, $url, $is_circle]);
    }
    header('Location: /admin/newsnav.php'); exit;
}
// 删除导航
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $DB->query("DELETE FROM `{$dbconfig['dbqz']}_news_nav` WHERE id=?", [$id]);
    header('Location: /admin/newsnav.php'); exit;
}
// 编辑导航
$editRow = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editRow = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_nav` WHERE id=?", [$id])->fetch();
}
// 查询所有导航
$rows = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_nav` ORDER BY sort DESC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html><head><title>底部导航管理</title><meta charset="utf-8">
<style>
body{font-family:Arial,sans-serif;background:#f7f7f7;min-height:100vh;}
.container{max-width:700px;margin:0 auto;padding:30px 30px 20px 30px;border-radius:10px;box-shadow:0 2px 12px #eee;}
h1{font-size:22px;margin-bottom:18px;}
table{width:100%;border-collapse:collapse;margin-top:18px;}
th,td{border:1px solid #eee;padding:10px;text-align:center;}
th{background:#fafafa;}
tr:hover{background:#f5f5f5;}
input[type=text]{padding:6px 10px;border:1px solid #ccc;border-radius:4px;width:180px;}
button{padding:6px 18px;background:#ff6600;color:#fff;border:none;border-radius:4px;cursor:pointer;}
button:hover{background:#ff8800;}
.edit-btn{background:#1890ff;}
.edit-btn:hover{background:#40a9ff;}
.del-btn{background:#f5222d;}
.del-btn:hover{background:#ff7875;}
img{vertical-align:middle;}
.form-row{margin-bottom:12px;}
.tips{color:#888;font-size:13px;margin-bottom:10px;}
.circle-row{background:#fffbe6 !important;}
.circle-label{color:#fa541c;font-weight:bold;}
</style>
</head><body><div class="container">
<h1>底部导航管理</h1>
<div class="tips">* 可在下方表格编辑/删除导航，图标URL支持外链或本地路径。<br>* 建议图标尺寸32x32，名称与前端一致。<br>* 新增：可自定义激活图标、背景图、是否中间推广按钮（不填则无影响）。</div>
<form method="post" style="margin-bottom:18px;">
    <div class="form-row">
        <label>名称：</label><input name="name" required placeholder="如：首页" value="<?php echo $editRow ? htmlspecialchars($editRow['name']) : '';?>">
        <label style="margin-left:18px;">图标URL：</label><input name="icon" style="width:180px;" placeholder="如：https://..." value="<?php echo $editRow ? htmlspecialchars($editRow['icon']) : '';?>">
        <label style="margin-left:18px;">激活图标URL：</label><input name="icon2" style="width:180px;" placeholder="激活图标URL" value="<?php echo $editRow ? htmlspecialchars($editRow['icon2']) : '';?>">
        <label style="margin-left:18px;">背景图片：</label><input name="bg" style="width:180px;" placeholder="背景图片URL" value="<?php echo $editRow ? htmlspecialchars($editRow['bg']) : '';?>">
        <label style="margin-left:18px;">跳转URL：</label><input name="url" style="width:180px;" placeholder="如：/news/index.php" value="<?php echo $editRow ? htmlspecialchars($editRow['url']) : '';?>">
        <label style="margin-left:18px;"><input type="checkbox" name="is_circle" value="1" <?php echo $editRow && $editRow['is_circle'] ? 'checked' : '';?> > 设为中间推广按钮</label>
        <?php if($editRow): ?><input type="hidden" name="id" value="<?php echo $editRow['id']; ?>"><?php endif; ?>
        <button type="submit"><?php echo $editRow ? '保存修改' : '添加'; ?></button>
        <?php if($editRow): ?><a href="newsnav.php" style="margin-left:10px;">取消</a><?php endif; ?>
    </div>
</form>
<table>
<tr><th>#</th><th>ID</th><th>名称</th><th>图标</th><th>激活图标</th><th>背景</th><th>跳转</th><th>类型</th><th>操作</th></tr>
<?php $i=1; foreach($rows as $r): ?>
<tr<?php echo $r['is_circle'] ? ' class="circle-row"' : ''; ?>>
    <td><?php echo $i++; ?></td>
    <td><?php echo $r['id']; ?></td>
    <td><?php echo htmlspecialchars($r['name']); ?></td>
    <td><img src="<?php echo htmlspecialchars($r['icon']); ?>" style="width:32px;height:32px;"></td>
    <td><?php echo $r['icon2'] ? '<img src="'.htmlspecialchars($r['icon2']).'" style="width:32px;height:32px;">' : '-'; ?></td>
    <td><?php echo $r['bg'] ? '<img src="'.htmlspecialchars($r['bg']).'" style="width:32px;height:32px;">' : '-'; ?></td>
    <td><?php echo htmlspecialchars($r['url']); ?></td>
    <td><?php echo $r['is_circle'] ? '<span class="circle-label">推广按钮</span>' : '普通'; ?></td>
    <td>
        <a href="?edit=<?php echo $r['id']; ?>"><button class="edit-btn">编辑</button></a>
        <a href="?del=<?php echo $r['id']; ?>" onclick="return confirm('确定删除？')"><button class="del-btn">删除</button></a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div></body></html> 