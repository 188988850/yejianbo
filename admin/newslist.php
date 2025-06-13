<?php
require_once('../includes/common.php');
if($islogin!=1) exit("<script language='javascript'>window.location.href='./login.php';</script>");
require_once '../config.php';
$mysqli = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($mysqli->connect_errno) exit('数据库连接失败: ' . $mysqli->connect_error);
$mysqli->set_charset($dbconfig['charset'] ?? 'utf8mb4');

$catid = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
// 获取所有分类
$cats = [];
$res = $mysqli->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` ORDER BY sort DESC, id ASC");
while($row = $res->fetch_assoc()) $cats[] = $row;
if (empty($cats)) {
    echo '<div class="container"><h1>新闻管理</h1><div style="color:#f00;margin:30px 0;">请先<a href="newscategory.php">添加分类</a>后再管理内容！</div></div>';
    exit;
}
if (!$catid || !$mysqli->query("SELECT 1 FROM `{$dbconfig['dbqz']}_news_category` WHERE id=$catid")->fetch_assoc()) {
    // 自动跳转到第一个分类
    header('Location: newslist.php?cat=' . $cats[0]['id']);
    exit;
}
$cat = $mysqli->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` WHERE id=$catid")->fetch_assoc();
$news = [];
$res = $mysqli->query("SELECT * FROM `{$dbconfig['dbqz']}_news` WHERE category_id=$catid ORDER BY add_time DESC");
while($row = $res->fetch_assoc()) $news[] = $row;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($cat['name']); ?> - 新闻管理</title>
    <meta charset="utf-8">
    <style>
        body{font-family:Arial;background:#f7f7f7;min-height:100vh;}
        .container{max-width:900px;margin:0 auto;padding:30px 30px 20px 30px;border-radius:10px;box-shadow:0 2px 12px #eee;}
        h1{font-size:22px;margin-bottom:18px;}
        table{width:100%;border-collapse:collapse;margin-top:18px;}
        th,td{border:1px solid #eee;padding:10px;text-align:center;}
        th{background:#fafafa;}
        tr:hover{background:#f5f5f5;}
        a.btn{padding:4px 12px;background:#1890ff;color:#fff;border-radius:4px;text-decoration:none;}
        a.btn:hover{background:#40a9ff;}
        .del-btn{background:#f5222d;}
        .del-btn:hover{background:#ff7875;}
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($cat['name']); ?> - 新闻管理</h1>
    <a href="newsadd.php?cat=<?php echo $catid; ?>" class="btn">添加新闻</a>
    <a href="newscategory.php" class="btn" style="background:#888;margin-left:10px;">返回分类管理</a>
    <table>
        <tr><th>ID</th><th>标题</th><th>添加时间</th><th>操作</th></tr>
        <?php foreach($news as $row): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo $row['add_time']; ?></td>
            <td>
                <a href="newsedit.php?id=<?php echo $row['id']; ?>&cat=<?php echo $catid; ?>" class="btn">编辑</a>
                <a href="newsdel.php?id=<?php echo $row['id']; ?>&cat=<?php echo $catid; ?>" class="btn del-btn" onclick="return confirm('确定删除？')">删除</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html> 