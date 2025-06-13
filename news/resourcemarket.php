<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('session.cookie_domain', '.wamsg.cn');
session_set_cookie_params(['path' => '/', 'domain' => '.wamsg.cn']);
session_start();
require_once('../config.php');

// 自动适配字符集的数据库连接
try {
    $dsn = "mysql:host={$dbconfig['host']};port={$dbconfig['port']};dbname={$dbconfig['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbconfig['user'], $dbconfig['pwd']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Unknown character set') !== false) {
        // 如果utf8mb4不支持，尝试使用utf8
        $dsn = "mysql:host={$dbconfig['host']};port={$dbconfig['port']};dbname={$dbconfig['dbname']};charset=utf8";
        $pdo = new PDO($dsn, $dbconfig['user'], $dbconfig['pwd']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } else {
        throw $e;
    }
}

// 获取所有分类
$categories = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_resource_category ORDER BY sort DESC, id ASC")->fetchAll();

// 获取资源列表
$where = "WHERE is_show=1 AND status=1";
if (!empty($_GET['resource_keyword'])) {
    $where .= " AND (name LIKE '%".addslashes($_GET['resource_keyword'])."%' OR content LIKE '%".addslashes($_GET['resource_keyword'])."%')";
}
if (!empty($_GET['resource_category'])) {
    $where .= " AND class = '".addslashes($_GET['resource_category'])."'";
}
$sql = "SELECT * FROM {$dbconfig['dbqz']}_goods $where ORDER BY id DESC LIMIT 100";
$resources = $pdo->query($sql)->fetchAll();

// 分类筛选
$select_class = isset($_GET['class']) ? $_GET['class'] : '';

// 获取当前登录用户
$user = null;
if(isset($_SESSION['zid'])) {
    $user_id = intval($_SESSION['zid']);
    $user = $pdo->query("SELECT * FROM shua_site WHERE zid='{$user_id}'")->fetch();
}

// 资源发布
if(isset($_POST['name'])){
    if(!$user) {
        echo json_encode(['code'=>0, 'msg'=>'请先登录']);
        exit;
    }
    try{
        $name = trim($_POST['name']);
        $content = trim($_POST['content']);
        $class = trim($_POST['class']);
        $price = floatval($_POST['price']);
        $hidden_content = isset($_POST['hidden_content']) ? trim($_POST['hidden_content']) : '';
        $image = '';
        $doc = '';
        // 图片上传
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = '../uploads/resources/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                $image = '/uploads/resources/' . $filename;
            }
        }
        // 文档上传
        if(isset($_FILES['doc']) && $_FILES['doc']['error'] == 0) {
            $upload_dir = '../uploads/resources/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            if(move_uploaded_file($_FILES['doc']['tmp_name'], $upload_dir . $filename)) {
                $doc = '/uploads/resources/' . $filename;
            }
        }
        // 插入
        $sql = "INSERT INTO {$dbconfig['dbqz']}_goods (name, content, class, price, image, doc, is_show, status, hidden_content, addtime, zid) VALUES (?, ?, ?, ?, ?, ?, 1, 0, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $content, $class, $price, $image, $doc, $hidden_content, date('Y-m-d H:i:s'), $user['zid']]);
        echo json_encode(['code'=>1, 'msg'=>'资源提交成功，等待审核']);
    }catch(Exception $e){
        echo json_encode(['code'=>0, 'msg'=>'保存失败:'.$e->getMessage()]);
    }
    exit;
}

// 读取底部导航
$nav = $pdo->query("SELECT * FROM `{$dbconfig['dbqz']}_news_nav` WHERE status=1 ORDER BY sort DESC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <title>资源市场 - 专业资源交易平台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <style>
    body { background: #f8f9fa; }
    .market-header { background: linear-gradient(90deg, #4a90e2 0%, #2980b9 100%); color: #fff; padding: 2rem 0 1rem 0; margin-bottom: 2rem; }
    .market-header h1 { font-size: 2rem; font-weight: 700; }
    .search-bar { background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 8px #e0e0e0; padding: 1rem 2rem; margin-bottom: 2rem; }
    .category-bar { margin-bottom: 1.5rem; text-align: center; }
    .category-btn { margin: 0.25rem; border-radius: 2rem; border: 1px solid #e0e0e0; background: #fff; color: #4a90e2; font-weight: 500; padding: 0.4rem 1.2rem; transition: .2s; }
    .category-btn.active, .category-btn:hover { background: #4a90e2; color: #fff; }
    .resource-card { border-radius: 1.2rem; box-shadow: 0 2px 12px #e0e0e0; margin-bottom: 2rem; background: #fff; transition: box-shadow .2s; }
    .resource-card:hover { box-shadow: 0 6px 24px #b0c4de; }
    .resource-img { width: 100%; height: 180px; object-fit: cover; border-top-left-radius: 1.2rem; border-top-right-radius: 1.2rem; }
    .resource-title { font-size: 1.2rem; font-weight: 600; margin: 0.8rem 0 0.5rem 0; color: #333; }
    .resource-desc {
        color: #666;
        font-size: 0.98rem;
        min-height: 2.8em;
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        height: 2.8em;
    }
    .resource-price { color: #e74c3c; font-weight: 700; font-size: 1.1rem; }
    .resource-actions { margin-top: 0.7rem; }
    .resource-actions .btn { border-radius: 1.5rem; font-size: 0.95rem; }
    @media (max-width: 767px) {
        .resource-img { height: 120px; }
        .market-header { padding: 1.2rem 0 0.7rem 0; }
        .search-bar { padding: 0.7rem 1rem; }
    }
    .bottom-nav {
        position: fixed;
        left: 16px; right: 16px; bottom: 20px;
        height: 74px;
        background: linear-gradient(180deg,#fff 80%,#f7f7fa 100%);
        border-top: 1.5px solid #e0e0e0;
        display: flex;
        z-index: 200;
        box-shadow: 0 8px 32px 0 rgba(0,0,0,0.13);
        padding-bottom: 8px; padding-top: 4px;
        border-radius: 22px;
        transition: box-shadow 0.2s;
        align-items: flex-end;
    }
    .bottom-nav a {
        flex: 1;
        text-align: center;
        color: #222;
        font-size: 14px;
        text-decoration: none;
        padding-top: 10px;
        transition: color 0.2s, font-size 0.2s;
        user-select: none;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        overflow: hidden;
        font-family: 'PingFang SC','Microsoft YaHei','Heiti SC','SimHei',Arial,sans-serif;
    }
    .bottom-nav a.active,.bottom-nav a:active{color:#ff6600;font-weight:bold;}
    .bottom-nav .icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2px;
        width: 38px; height: 38px;
    }
    .bottom-nav .icon-wrap img {
        width: 38px; height: 38px;
        object-fit: contain;
        border-radius: 50%;
        display: block;
    }
    .bottom-nav span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        font-weight: bold;
        margin-top: 2px;
    }
    .bottom-nav .center-btn {
        position: absolute;
        left: 50%;
        top: -28px;
        transform: translateX(-50%);
        width: 72px;
        height: 72px;
        z-index: 10;
        background: transparent;
        border-radius: 50%;
        box-shadow: 0 2px 12px #ff660044;
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        overflow: visible;
    }
    .bottom-nav .center-btn .rebate-bg {
        position: absolute;
        left: 0; top: 0; width: 100%; height: 100%;
        background: url('https://cdn.aimallol.com/statics/weapp/icon/icon-rebate-bg.png') no-repeat center/cover;
        z-index: 1;
        border-radius: 50%;
        pointer-events: none;
    }
    .bottom-nav .center-btn .rebate-img1,
    .bottom-nav .center-btn .rebate-img2 {
        position: absolute;
        left: 50%; top: 50%;
        width: 48px; height: 48px;
        border-radius: 50%;
        z-index: 2;
        object-fit: contain;
        transition: opacity 0.2s;
        box-shadow: 0 2px 8px #ff660022;
        pointer-events: none;
        transform: translate(-50%, -50%);
    }
    .bottom-nav .center-btn .rebate-img1 { opacity: 1; }
    .bottom-nav .center-btn .rebate-img2 { opacity: 0; }
    @media (max-width:600px){
        .bottom-nav{left:6px;right:6px;bottom:8px;height:60px;padding-bottom:2px;border-radius:14px;}
        .bottom-nav .icon-wrap {width: 30px; height: 30px;}
        .bottom-nav .icon-wrap img{width:30px;height:30px;}
        .bottom-nav span{font-size:12px;}
        .bottom-nav .center-btn { width: 54px; height: 54px; top: -16px; }
        .bottom-nav .center-btn img,
        .bottom-nav .center-btn .rebate-bg { width: 54px; height: 54px; }
        .bottom-nav .center-btn span { font-size: 12px; bottom: -16px; }
    }
    </style>
</head>
<body>
    <div class="market-header text-center">
        <h1><i class="fas fa-store mr-2"></i>资源市场</h1>
        <div class="lead">专业资源交易平台，安全高效</div>
        <button class="btn btn-light mt-3" onclick="if(!window.isLogin){window.location.href='/user/login.php';return false;}$('#publishModal').modal('show')">
            <i class="fas fa-plus mr-1"></i>发布资源
        </button>
    </div>
    <div class="container">
        <form method="get" class="search-bar mb-3">
            <div class="form-row align-items-center">
                <div class="col-12 col-md-5 mb-2 mb-md-0">
                    <input type="text" class="form-control" name="resource_keyword" placeholder="搜索资源..." value="<?php echo isset($_GET['resource_keyword'])?htmlspecialchars($_GET['resource_keyword']):''; ?>">
                </div>
                <div class="col-12 col-md-5 mb-2 mb-md-0">
                    <select class="form-control" name="resource_category">
                        <option value="">全部分类</option>
                        <?php foreach($categories as $category): ?>
                        <?php $catname = isset($category['name']) ? $category['name'] : ''; ?>
                        <option value="<?php echo $catname; ?>" <?php echo isset($_GET['resource_category']) && $_GET['resource_category']==$catname?'selected':''; ?>><?php echo $catname; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i>搜索</button>
                </div>
            </div>
        </form>
        <div class="category-bar">
            <button class="category-btn<?php if(empty($_GET['resource_category'])) echo ' active'; ?>" onclick="location.href='?<?php echo http_build_query(array_merge($_GET, ['resource_category'=>''])); ?>'">全部</button>
            <?php foreach($categories as $category): ?>
            <?php $catname = isset($category['name']) ? $category['name'] : ''; ?>
            <button class="category-btn<?php if(isset($_GET['resource_category']) && $_GET['resource_category']==$catname) echo ' active'; ?>" onclick="location.href='?<?php echo http_build_query(array_merge($_GET, ['resource_category'=>$catname])); ?>'">
                <?php echo $catname; ?>
            </button>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <?php if (!empty($resources)): ?>
                <?php foreach($resources as $resource): ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="goodsdetail.php?id=<?php echo $resource['id']; ?>">
                        <div class="resource-card mb-4">
                            <img class="resource-img" src="<?php echo !empty($resource['image']) ? $resource['image'] : '/assets/img/default-resource.jpg'; ?>" alt="资源图片">
                            <div class="p-3">
                                <div class="resource-title"><?php echo htmlspecialchars($resource['name']); ?></div>
                                <div class="resource-desc"><?php echo mb_substr(strip_tags($resource['content']), 0, 60); ?>...</div>
                                <div class="resource-price"><i class="fas fa-yen-sign mr-1"></i><?php echo htmlspecialchars($resource['price']); ?></div>
                                <?php if($resource['doc']): ?>
                                <div class="mb-2"><a href="<?php echo $resource['doc']; ?>" class="btn btn-outline-info btn-sm" target="_blank"><i class="fas fa-file-download mr-1"></i>下载文档</a></div>
                                <?php endif; ?>
                                <div class="resource-actions d-flex">
                                    <a href="goodsdetail.php?id=<?php echo $resource['id']; ?>" class="btn btn-outline-primary flex-fill mr-2"><i class="fas fa-eye mr-1"></i>详情</a>
                                    <a href="goodsdetail.php?id=<?php echo $resource['id']; ?>#buy" class="btn btn-primary flex-fill"><i class="fas fa-shopping-cart mr-1"></i>购买</a>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-box-open fa-2x mb-3"></i><br>
                        <strong>暂无资源</strong><br>当前没有找到符合条件的资源
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- 资源发布模态框 -->
    <div class="modal fade" id="publishModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">发布资源</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="resource-publish-form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>资源名称</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>资源分类</label>
                            <select class="form-control" name="class" required>
                                <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>资源价格</label>
                            <input type="number" class="form-control" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>资源图片</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>资源文档</label>
                            <input type="file" class="form-control" name="doc" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
                        </div>
                        <div class="form-group">
                            <label>资源详情</label>
                            <textarea class="form-control" name="content" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>隐藏资源内容（付费后可见）</label>
                            <textarea class="form-control" name="hidden_content" rows="3"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-nav">
        <?php
        $navCount = count($nav);
        foreach($nav as $i => $n):
            // 判断是否为个人中心按钮
            $isCenter = (strpos($n['url'], 'user') !== false || strpos($n['name'], '个人中心') !== false || $i === 3);
            $url = $isCenter ? '/user/index.php' : htmlspecialchars($n['url']);
        ?>
            <?php if($i === 2): ?>
                <a href="/rebate/index.php" class="center-btn rebate-btn">
                    <span class="rebate-bg"></span>
                    <img class="rebate-img1" src="https://cdn.aimallol.com/statics/weapp/icon/icon-rebate-img2.png" alt="返佣主图">
                    <img class="rebate-img2" src="https://cdn.aimallol.com/statics/weapp/icon/icon-rebate-img1.png" alt="返佣闪烁">
                </a>
            <?php endif; ?>
            <a href="<?php echo $url; ?>" class="<?php echo strpos($_SERVER['REQUEST_URI'], $n['url']) !== false ? 'active' : ''; ?>">
                <span class="icon-wrap"><img src="<?php echo htmlspecialchars($n['icon']); ?>" alt="<?php echo htmlspecialchars($n['name']); ?>"></span>
                <span><?php echo htmlspecialchars($n['name']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // 判断是否登录
    window.isLogin = <?php echo isset($_SESSION['zid']) ? 'true' : 'false'; ?>;
    // 资源发布表单AJAX提交
    $('#resource-publish-form').on('submit', function(e){
        if(!window.isLogin){
            window.location.href='/user/login.php';
            return false;
        }
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                var data = JSON.parse(res);
                alert(data.msg);
                if(data.code == 1) {
                    $('#publishModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr){
                alert('保存失败：' + (xhr.responseText || '未知错误'));
            }
        });
    });
    </script>
</body>
</html> 