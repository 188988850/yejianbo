<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_host = "localhost";
$db_port = 3306;
$db_user = "xinfaka";
$db_pass = "82514a97de548852";
$db_name = "xinfaka";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 详情页
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM shua_news WHERE id=$id");
    $row = $result->fetch_assoc();
    if(!$row) die("内容不存在！");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?=$row['title']?></title>
        <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
        <style>
            .container{max-width:700px;margin-top:30px;}
            .news-title{font-size:22px;font-weight:bold;}
            .news-meta{color:#888;font-size:13px;margin-bottom:15px;}
            .news-content{margin-top:20px;}
        </style>
    </head>
    <body>
    <div class="container">
        <div class="news-title"><?=$row['title']?></div>
        <div class="news-meta"><?=$row['time']?> | 阅读<?=$row['views']?></div>
        <?php if($row['img']){ ?><img src="<?=$row['img']?>" style="max-width:100%;margin-bottom:20px;"><?php } ?>
        <div class="news-content"><?=$row['content'] ? $row['content'] : '暂无正文'?></div>
        <a href="newsList.php" class="btn btn-default" style="margin-top:20px;">返回列表</a>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// 列表页
$page = max(1, intval($_GET['page'] ?? 1));
$pagesize = 10;
$offset = ($page-1)*$pagesize;
$total = $conn->query("SELECT COUNT(*) FROM shua_news")->fetch_row()[0];
$result = $conn->query("SELECT * FROM shua_news ORDER BY time DESC LIMIT $offset,$pagesize");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>热门产品资讯</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .container{max-width:700px;margin-top:30px;}
        .news-item{border-bottom:1px solid #eee;padding:15px 0;}
        .news-title{font-size:18px;font-weight:bold;}
        .news-meta{color:#888;font-size:13px;}
        .news-desc{margin:10px 0;}
        .news-img{max-width:120px;max-height:80px;margin-right:15px;}
    </style>
</head>
<body>
<div class="container">
    <h3>热门产品资讯</h3>
    <?php while($row = $result->fetch_assoc()){ ?>
    <div class="news-item row">
        <?php if($row['img']){ ?>
        <div class="col-xs-3"><img src="<?=$row['img']?>" class="news-img"></div>
        <?php } ?>
        <div class="col-xs-9">
            <a href="newsList.php?id=<?=$row['id']?>" class="news-title"><?=$row['title']?></a>
            <div class="news-meta"><?=$row['time']?> | 阅读<?=$row['views']?></div>
            <div class="news-desc"><?=mb_substr(strip_tags($row['desc']),0,50)?>...</div>
        </div>
    </div>
    <?php } ?>
    <nav>
      <ul class="pagination">
        <?php for($i=1;$i<=ceil($total/$pagesize);$i++){ ?>
        <li<?=$i==$page?' class="active"':''?>><a href="?page=<?=$i?>"><?=$i?></a></li>
        <?php } ?>
      </ul>
    </nav>
</div>
</body>
</html>