<!DOCTYPE html>
<html>
<head>
    <title>后台总控台</title>
    <meta charset="utf-8">
    <style>
        body{font-family:Arial;background:#f7f7f7;}
        .container{max-width:600px;margin:40px auto;background:#fff;padding:32px 32px 24px 32px;border-radius:12px;box-shadow:0 2px 16px #eee;}
        h1{font-size:24px;margin-bottom:24px;}
        ul{list-style:none;padding:0;}
        li{margin-bottom:18px;}
        a{font-size:18px;color:#1890ff;text-decoration:none;}
        a:hover{color:#ff6600;}
    </style>
</head>
<body>
<div class="container">
    <h1>后台总控台</h1>
    <ul>
        <li><a href="newscategory.php">分类管理</a></li>
        <li><a href="newsbanner.php">Banner管理</a></li>
        <li><a href="newsnav.php">底部导航管理</a></li>
        <li><a href="newslist.php?cat=1">新闻管理（默认分类）</a></li>
        <!-- 可根据需要添加更多入口 -->
    </ul>
</div>
</body>
</html> 