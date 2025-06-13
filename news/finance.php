<?php
session_start();
require_once '../config.php';
$DB = DB::getInstance($dbconfig);

// 获取分类ID
$catid = isset($_GET['cat']) ? intval($_GET['cat']) : 0;

// 获取所有分类
$categories = $DB->query("SELECT * FROM shua_finance_category WHERE status=1 ORDER BY sort DESC, id ASC")->fetchAll();

// 获取Banner
$banners = $DB->query("SELECT * FROM shua_finance_banner WHERE status=1 ORDER BY sort DESC, id DESC")->fetchAll();

// 获取导航
$navs = $DB->query("SELECT * FROM shua_finance_nav WHERE status=1 ORDER BY sort DESC, id DESC")->fetchAll();

// 获取资源列表
$where = $catid > 0 ? "WHERE a.category_id='$catid' AND a.status=1" : "WHERE a.status=1";
$list = $DB->query("SELECT a.*,b.name as category_name FROM shua_finance_content a LEFT JOIN shua_finance_category b ON a.category_id=b.id $where ORDER BY a.id DESC")->fetchAll();

// 账号识别
$user = null;
if(isset($_SESSION['zid'])) {
    $user_id = $_SESSION['zid'];
    $user = $DB->query("SELECT * FROM shua_site WHERE zid='{$user_id}'")->fetch();
}
// 会员判断
$is_vip = $user && $user['vip'] == 1 && strtotime($user['vip_expire']) > time();
?>
<!DOCTYPE html>
<html>
<head>
    <title>金融资源</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    .banner{position:relative;height:200px;overflow:hidden;margin-bottom:20px;}
    .banner img{width:100%;height:100%;object-fit:cover;}
    .nav-list{display:flex;flex-wrap:wrap;margin-bottom:20px;background:#fff;padding:10px;border-radius:5px;}
    .nav-item{width:25%;text-align:center;padding:10px 0;}
    .nav-item img{width:40px;height:40px;margin-bottom:5px;}
    .nav-item p{margin:0;color:#666;}
    .category-list{margin-bottom:20px;}
    .category-list .btn{margin-right:10px;margin-bottom:10px;}
    .resource-list .item{background:#fff;padding:15px;margin-bottom:15px;border-radius:5px;}
    .resource-list .item h3{margin-top:0;margin-bottom:10px;}
    .resource-list .item .summary{color:#666;margin-bottom:10px;}
    .resource-list .item .price{color:#f00;font-size:18px;font-weight:bold;}
    </style>
</head>
<body>
    <div class="container">
        <!-- Banner -->
        <div class="banner">
            <?php if($banners): ?>
            <div id="carousel-banner" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach($banners as $k=>$banner): ?>
                    <div class="item <?php echo $k==0?'active':''; ?>">
                        <a href="<?php echo htmlspecialchars($banner['url']); ?>">
                            <img src="<?php echo htmlspecialchars($banner['image']); ?>" alt="<?php echo htmlspecialchars($banner['title']); ?>">
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if(count($banners)>1): ?>
                <a class="left carousel-control" href="#carousel-banner" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-banner" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- 导航 -->
        <?php if($navs): ?>
        <div class="nav-list">
            <?php foreach($navs as $nav): ?>
            <div class="nav-item">
                <a href="<?php echo htmlspecialchars($nav['url']); ?>">
                    <?php if($nav['icon']): ?>
                    <img src="<?php echo htmlspecialchars($nav['icon']); ?>" alt="<?php echo htmlspecialchars($nav['name']); ?>">
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars($nav['name']); ?></p>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- 分类 -->
        <div class="category-list">
            <a href="?cat=0" class="btn btn-default <?php echo $catid==0?'active':''; ?>">全部</a>
            <?php foreach($categories as $cat): ?>
            <a href="?cat=<?php echo $cat['id']; ?>" class="btn btn-default <?php echo $catid==$cat['id']?'active':''; ?>"><?php echo htmlspecialchars($cat['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <!-- 资源列表 -->
        <div class="resource-list">
            <?php foreach($list as $row): ?>
            <div class="item">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <div class="summary"><?php echo htmlspecialchars($row['summary']); ?></div>
                <div class="preview"><?php echo $row['preview']; ?></div>
                <div class="price">￥<?php echo $row['price']; ?></div>
                <a href="financedetail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">查看详情</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html> 