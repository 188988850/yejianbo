<?php
require_once '../config.php';
// 数据库连接
$mysqli = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($mysqli->connect_errno) {
    exit('数据库连接失败: ' . $mysqli->connect_error);
}
$mysqli->set_charset($dbconfig['charset'] ?? 'utf8mb4');

$catid = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
// 读取所有分类
$cats = [];
$res = $mysqli->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` ORDER BY sort DESC, id ASC");
while($row = $res->fetch_assoc()) $cats[] = $row;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catid = intval($_POST['category_id']);
    $title = $_POST['title'];
    $cover_url = $_POST['cover_url'];
    $public_content = $_POST['public_content'];
    $vip_content = $_POST['vip_content'];
    $add_time = date('Y-m-d H:i:s');
    $stmt = $mysqli->prepare("INSERT INTO `{$dbconfig['dbqz']}_news` (category_id, title, cover_url, add_time, public_content, vip_content) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isssss', $catid, $title, $cover_url, $add_time, $public_content, $vip_content);
    $stmt->execute();
    header('Location: newsList.php?cat=' . $catid);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>添加资讯</title>
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
    <h1>添加资讯</h1>
    <div class="tips">* 请填写完整资讯信息，分类必选。封面图建议尺寸 300x200。</div>
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
            <input type="text" name="title" required placeholder="请输入资讯标题">
        </div>
        <div class="form-row">
            <label>封面图URL：</label>
            <input type="text" name="cover_url" placeholder="如：https://... 或 /static/news/cover1.png">
        </div>
        <div class="form-row">
            <label>商品介绍：</label>
            <textarea name="public_content" placeholder="面向所有用户展示的内容"></textarea>
        </div>
        <div class="form-row">
            <label>会员可见：</label>
            <textarea name="vip_content" placeholder="仅会员可见内容"></textarea>
        </div>
        <div class="form-row">
            <button type="submit">提交</button>
            <a href="newsList.php?cat=<?php echo $catid; ?>" style="margin-left:18px;color:#888;">返回列表</a>
        </div>
    </form>
</div>
</body>
</html>