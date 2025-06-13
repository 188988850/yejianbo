<?php
require_once __DIR__.'/config.php';
session_start();
// 数据库连接
$DB = DB::getInstance($dbconfig);
// 获取资源列表
$resources = $DB->query("SELECT * FROM shua_finance_content WHERE status=1 ORDER BY id DESC")->fetchAll();
// 判断登录
$user = isset($_SESSION['user_id']) ? $DB->getRow("SELECT * FROM shua_site WHERE zid='".intval($_SESSION['user_id'])."' LIMIT 1") : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>金融资源列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/news/style.css">
</head>
<body>
    <h1>金融资源列表</h1>
    <div class="finance-list">
        <?php foreach($resources as $row): ?>
            <div class="finance-item">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['summary']); ?></p>
                <?php if($user): ?>
                    <a href="financedetail.php?id=<?php echo $row['id']; ?>">查看详情</a>
                <?php else: ?>
                    <a href="login.php" onclick="alert('请先登录后查看详情');return false;">查看详情</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="financevip.php">开通金融会员</a>
</body>
</html> 