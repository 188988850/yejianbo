<?php
ini_set('session.cookie_domain', '.wamsg.cn');
session_set_cookie_params(['path' => '/', 'domain' => '.wamsg.cn']);
session_start();
require_once __DIR__ . '/../includes/error.class.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.class.php';
if (!isset($dbconfig['charset'])) $dbconfig['charset'] = 'utf8mb4';
if (!isset($dbconfig['pconnect'])) $dbconfig['pconnect'] = 0;
$DB = DB::getInstance($dbconfig);
$catid = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
$category = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` WHERE id=?", [$catid])->fetch();
if (!$category) { exit('分类不存在'); }
$news = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news` WHERE category_id=? ORDER BY add_time DESC", [$catid])->fetchAll();
// 获取当前登录用户
$user = null;
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_users` WHERE id='{$user_id}'")->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($category['name']); ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/static/news/style.css">
    <style>
    body{font-family:Arial;background:#f7f7f7;}
    .container{max-width:900px;margin:30px auto 0 auto;}
    h1{font-size:22px;margin:24px 0 18px 0;text-align:center;}
    .news-list{padding:0 0 60px 0;}
    .news-item{background:#fff;margin:12px 12px 0 12px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.03);overflow:hidden;display:flex;align-items:flex-start;transition:box-shadow 0.2s;}
    .news-item img{width:90px;height:70px;object-fit:cover;border-radius:8px;margin:12px;background:#eee;flex-shrink:0;}
    .news-item h3{font-size:16px;margin:12px 0 6px 0;font-weight:600;line-height:1.3;}
    .news-item p{font-size:13px;color:#888;margin:0 0 12px 0;}
    .news-item > a{display:flex;flex-direction:row;align-items:flex-start;text-decoration:none;color:inherit;width:100%;}
    .news-item .info{flex:1;display:flex;flex-direction:column;justify-content:space-between;padding:8px 0 8px 0;}
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($category['name']); ?></h1>
    <div class="news-list">
    <?php foreach($news as $row): ?>
        <div class="news-item">
            <a href="/news/detail.php?id=<?php echo $row['id']; ?>">
                <img src="<?php echo htmlspecialchars($row['cover_url']); ?>" alt="">
                <div class="info">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo date('Y-m-d H:i', strtotime($row['add_time'])); ?></p>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    </div>
</div>
</body>
</html> 