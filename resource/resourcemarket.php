<?php
// 资源出售区页面（resource/resourcemarket.php）
require_once '../common.php'; // 数据库连接和权限校验

// 筛选条件
$where = 'WHERE status=1';
if (!empty($_GET['keyword'])) {
    $where .= " AND (title LIKE '%".addslashes($_GET['keyword'])."%' OR description LIKE '%".addslashes($_GET['keyword'])."%')";
}
if (!empty($_GET['category'])) {
    $where .= " AND category = '".addslashes($_GET['category'])."'";
}

// 查询资源列表（假设表名 shua_goods，卖家表 shua_user）
$sql = "SELECT g.*, u.name as seller FROM shua_goods g LEFT JOIN shua_user u ON g.userid=u.id $where ORDER BY g.id DESC LIMIT 100";
$resources = $DB->query($sql)->fetchAll();
$categories = $DB->query("SELECT DISTINCT category FROM shua_goods WHERE status=1")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>资源出售区</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
    <style>.resource-card{border:1px solid #eee;border-radius:8px;padding:16px;margin-bottom:16px;box-shadow:0 2px 8px #f0f1f2;}</style>
</head>
<body>
<div class="container">
    <h2>资源出售区</h2>
    <form class="form-inline" method="get" style="margin-bottom:20px;">
        <input type="text" name="keyword" placeholder="搜索资源" class="form-control" value="<?=isset($_GET['keyword'])?htmlspecialchars($_GET['keyword']):''?>">
        <select name="category" class="form-control">
            <option value="">全部分类</option>
            <?php foreach($categories as $cat){ ?><option value="<?=$cat['category']?>"<?php if(isset($_GET['category'])&&$_GET['category']==$cat['category'])echo ' selected';?>><?=$cat['category']?></option><?php } ?>
        </select>
        <button type="submit" class="btn btn-primary">搜索</button>
        <a href="publishresource.php" class="btn btn-success" style="margin-left:10px;">发布资源</a>
    </form>
    <div class="row">
        <?php foreach($resources as $res){ ?>
        <div class="col-md-4">
            <div class="resource-card">
                <h4><?=$res['title']?></h4>
                <p><?=mb_substr($res['description'],0,40)?>...</p>
                <p>分类：<?=$res['category']?> | 卖家：<?=$res['seller']?></p>
                <p>价格：<span style="color:#ff6600;font-weight:bold;">￥<?=$res['price']?></span></p>
                <a href="resourcedetail.php?id=<?=$res['id']?>" class="btn btn-primary btn-sm">查看详情</a>
                <a href="buyresource.php?id=<?=$res['id']?>" class="btn btn-warning btn-sm">购买</a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
</body>
</html> 