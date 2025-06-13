<?php
require_once '../config.php';
$mysqli = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($mysqli->connect_errno) exit('数据库连接失败: ' . $mysqli->connect_error);
$mysqli->set_charset($dbconfig['charset'] ?? 'utf8mb4');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$row = $mysqli->query("SELECT * FROM `{$dbconfig['dbqz']}_news` WHERE id=$id")->fetch_assoc();
if (!$row) exit('新闻不存在');
$catid = isset($_GET['cat']) ? intval($_GET['cat']) : $row['category_id'];

$cats = [];
$res = $mysqli->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` ORDER BY sort DESC, id ASC");
while($cat = $res->fetch_assoc()) $cats[] = $cat;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catid = intval($_POST['category_id']);
    $title = $_POST['title'];
    $cover_url = $_POST['cover_url'];
    $public_content = $_POST['public_content'];
    $vip_content = $_POST['vip_content'];
    $stmt = $mysqli->prepare("UPDATE `{$dbconfig['dbqz']}_news` SET category_id=?, title=?, cover_url=?, public_content=?, vip_content=? WHERE id=?");
    $stmt->bind_param('issssi', $catid, $title, $cover_url, $public_content, $vip_content, $id);
    $stmt->execute();
    header('Location: newsList.php?cat=' . $catid);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>编辑资讯</title>
    <meta charset="utf-8">
    <style>
    body{font-family:Arial;background:#f7f7f7;}
    .container{max-width:600px;margin:40px auto;background:#fff;padding:32px 32px 24px 32px;border-radius:12px;box-shadow:0 2px 16px #eee;}
    h1{font-size:22px;margin-bottom:22px;}
    .form-row{margin-bottom:18px;}
    label{display:inline-block;width:90px;color:#333;font-weight:500;}
    input[type=text],select,textarea{padding:7px 12px;border:1px solid #ccc;border-radius:5px;width:320px;font-size:15px;}
    textarea{width:100%;min-height:80px;}
    button{padding:8px 28px;background:#ff6600;color:#fff;border:none;border-radius:5px;font-size:16px;cursor:pointer;}
    button:hover{background:#ff8800;}
    .tips{color:#888;font-size:13px;margin-bottom:16px;}
    </style>
</head>
<body>
<div class="container">
    <h1>编辑资讯</h1>
    <form method="post">
        <div class="form-row">
            <label>分类：</label>
            <select name="category_id" required>
                <option value="">请选择分类</option>
                <?php foreach($cats as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if($catid==$cat['id']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label>标题：</label>
            <input type="text" name="title" required value="<?php echo htmlspecialchars($row['title']); ?>">
        </div>
        <div class="form-row">
            <label>封面图URL：</label>
            <input type="text" name="cover_url" value="<?php echo htmlspecialchars($row['cover_url']); ?>">
        </div>
        <div class="form-row">
            <label>商品介绍：</label>
            <textarea name="public_content"><?php echo htmlspecialchars($row['public_content']); ?></textarea>
        </div>
        <div class="form-row">
            <label>会员可见：</label>
            <textarea name="vip_content"><?php echo htmlspecialchars($row['vip_content']); ?></textarea>
        </div>
        <div class="form-row">
            <button type="submit">保存修改</button>
            <a href="newsList.php?cat=<?php echo $catid; ?>" style="margin-left:18px;color:#888;">返回列表</a>
        </div>
    </form>
</div>
</body>
</html>