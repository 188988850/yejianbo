<?php
require_once('../includes/common.php');
if($islogin!=1) exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title = '分类管理';
include './head.php';
?>
<div class="main pjaxmain">
<?php
require_once '../includes/error.class.php';
require_once '../config.php';
require_once '../includes/db.class.php';
if (!isset($dbconfig['charset'])) $dbconfig['charset'] = 'utf8mb4';
if (!isset($dbconfig['pconnect'])) $dbconfig['pconnect'] = 0;
$DB = DB::getInstance($dbconfig);

// 检查并自动添加url字段
$check = $DB->query("SHOW COLUMNS FROM `{$dbconfig['dbqz']}_news_category` LIKE 'url'")->fetch();
if (!$check) {
    $DB->query("ALTER TABLE `{$dbconfig['dbqz']}_news_category` ADD COLUMN `url` VARCHAR(1024) DEFAULT NULL");
}

// ====== 安全校验与返回按钮 ======
session_start();
if (!isset($_SESSION['admin_token']) || empty($_SESSION['admin_token'])) {
    header('Location: ./login.php');
    exit('未登录，禁止访问！');
}
echo '<button onclick="window.history.back();" style="margin-bottom:15px;padding:6px 18px;background:#ff6600;color:#fff;border:none;border-radius:4px;cursor:pointer;" class="btn btn-default">返回上一页</button>';

// 新增或编辑分类
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $icon = trim($_POST['icon']);
    $url = trim($_POST['url']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id > 0) {
        $DB->query("UPDATE `{$dbconfig['dbqz']}_news_category` SET name=?, icon=?, url=? WHERE id=?", [$name, $icon, $url, $id]);
    } else {
        $DB->query("INSERT INTO `{$dbconfig['dbqz']}_news_category` (name, icon, url, status) VALUES (?, ?, ?, 1)", [$name, $icon, $url]);
    }
    header('Location: ' . $_SERVER['PHP_SELF']); exit;
}
// 删除分类
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $DB->query("DELETE FROM `{$dbconfig['dbqz']}_news_category` WHERE id=?", [$id]);
    header('Location: /admin/newscategory.php'); exit;
}
// 编辑分类
$editRow = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editRow = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` WHERE id=?", [$id])->fetch();
}
// 查询所有分类
$rows = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` ORDER BY sort DESC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html><head><title>分类管理</title><meta charset="utf-8">
<style>
body{font-family:Arial,sans-serif;background:#f7f7f7;}
.container{max-width:800px;margin:30px auto;background:#fff;padding:30px 30px 20px 30px;border-radius:10px;box-shadow:0 2px 12px #eee;}
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
</style>
</head><body><div class="container">
<h1>分类管理</h1>
<div class="tips">* 可在下方表格编辑/删除分类，图标URL支持外链或本地路径。<br>* 建议图标尺寸38x38，分类名称与前端一致。<br>* 新增"跳转URL"字段，留空则默认跳转本分类。</div>
<form method="post" style="margin-bottom:18px;">
    <div class="form-row">
        <label>名称：</label><input name="name" required placeholder="如：个人信贷" value="<?php echo $editRow ? htmlspecialchars($editRow['name']) : '';?>">
        <label style="margin-left:18px;">图标URL：</label><input name="icon" style="width:220px;" placeholder="如：https://... 或 /static/news/icon1.png" value="<?php echo $editRow ? htmlspecialchars($editRow['icon']) : '';?>">
        <label style="margin-left:18px;">跳转URL：</label><input name="url" style="width:220px;" placeholder="如：https://... 可留空自动生成" value="<?php echo $editRow ? htmlspecialchars($editRow['url']) : '';?>">
        <?php if($editRow): ?><input type="hidden" name="id" value="<?php echo $editRow['id']; ?>"><?php endif; ?>
        <button type="submit"><?php echo $editRow ? '保存修改' : '添加'; ?></button>
        <?php if($editRow): ?><a href="newscategory.php" style="margin-left:10px;">取消</a><?php endif; ?>
    </div>
</form>
<table>
<tr><th>#</th><th>ID</th><th>名称</th><th>图标</th><th>实际跳转路径</th><th>操作</th></tr>
<?php $i=1; foreach($rows as $r): ?>
<tr>
    <td><?php echo $i++; ?></td>
    <td><?php echo $r['id']; ?></td>
    <td><?php echo htmlspecialchars($r['name']); ?></td>
    <td><img src="<?php echo htmlspecialchars($r['icon']); ?>" style="width:32px;height:32px;"></td>
    <td>
        <?php
        $caturl = trim($r['url']);
        if ($caturl === '' || $caturl === null) {
            $caturl = '/news/list.php?cat=' . $r['id'];
        }
        echo htmlspecialchars($caturl);
        ?>
    </td>
    <td>
        <a href="?edit=<?php echo $r['id']; ?>"><button class="edit-btn">编辑</button></a>
        <a href="?del=<?php echo $r['id']; ?>" onclick="return confirm('确定删除？')"><button class="del-btn">删除</button></a>
        <a href="/admin/newslist.php?cat=<?php echo $r['id']; ?>" target="_blank"><button style="background:#52c41a;margin-left:6px;">管理资源</button></a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div></body></html>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<?php include './foot.php'; ?> 