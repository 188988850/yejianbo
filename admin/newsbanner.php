<?php
require_once('../includes/common.php');
if($islogin!=1) exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title = 'Banner管理';
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

// 位置定义
$positions = [
    'left' => '左侧大图',
    'mid' => '右上小图',
    'right' => '右下小图',
];

// 处理保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($positions as $pos => $label) {
        $img = trim($_POST[$pos.'_img']);
        $url = trim($_POST[$pos.'_url']);
        // 检查是否已存在该位置
        $row = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_banner` WHERE position=?", [$pos])->fetch();
        if ($row) {
            $DB->query("UPDATE `{$dbconfig['dbqz']}_news_banner` SET img=?, url=? WHERE position=?", [$img, $url, $pos]);
        } else {
            $DB->query("INSERT INTO `{$dbconfig['dbqz']}_news_banner` (img, url, status, position) VALUES (?, ?, 1, ?)", [$img, $url, $pos]);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']); exit;
}
// 读取当前三格数据
$banner = [];
foreach ($positions as $pos => $label) {
    $banner[$pos] = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_banner` WHERE position=?", [$pos])->fetch();
}

// ====== 安全校验与返回按钮 ======
session_start();
if (!isset($_SESSION['admin_token']) || empty($_SESSION['admin_token'])) {
    header('Location: ./login.php');
    exit('未登录，禁止访问！');
}
echo '<button onclick="window.history.back();" style="margin-bottom:15px;padding:6px 18px;background:#ff6600;color:#fff;border:none;border-radius:4px;cursor:pointer;" class="btn btn-default">返回上一页</button>';
?>
<!DOCTYPE html>
<html><head><title>Banner管理</title><meta charset="utf-8">
<style>
body{font-family:Arial,sans-serif;background:#f7f7f7;}
.container{max-width:700px;margin:30px auto;background:#fff;padding:30px 30px 20px 30px;border-radius:10px;box-shadow:0 2px 12px #eee;}
h1{font-size:22px;margin-bottom:18px;}
input[type=text]{padding:6px 10px;border:1px solid #ccc;border-radius:4px;width:320px;}
button{padding:6px 18px;background:#ff6600;color:#fff;border:none;border-radius:4px;cursor:pointer;}
button:hover{background:#ff8800;}
.form-row{margin-bottom:18px;}
label{display:inline-block;width:80px;}
.tips{color:#888;font-size:13px;margin-bottom:10px;}
.banner-preview{margin:8px 0;}
.banner-preview img{width:100%;max-width:320px;border-radius:12px;box-shadow:0 2px 8px #eee;}
</style>
</head><body><div class="container">
<h1>Banner三格管理</h1>
<div class="tips">* 分别设置首页三格Banner（左/右上/右下），图片URL支持外链或本地路径。建议尺寸：左750x120，右375x56。</div>
<form method="post">
<?php foreach($positions as $pos => $label): ?>
    <div class="form-row">
        <h3><?php echo $label; ?></h3>
        <label>图片URL：</label><input name="<?php echo $pos; ?>_img" value="<?php echo isset($banner[$pos]['img']) ? htmlspecialchars($banner[$pos]['img']) : ''; ?>" placeholder="如：https://... 或 /static/news/banner1.png"><br><br>
        <label>跳转URL：</label><input name="<?php echo $pos; ?>_url" value="<?php echo isset($banner[$pos]['url']) ? htmlspecialchars($banner[$pos]['url']) : ''; ?>" placeholder="如：https://... 可留空"><br>
        <?php if(!empty($banner[$pos]['img'])): ?>
        <div class="banner-preview"><img src="<?php echo htmlspecialchars($banner[$pos]['img']); ?>" alt="预览"></div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
    <button type="submit">保存全部</button>
</form>
</div></body></html>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<?php include './foot.php'; ?> 