<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../includes/error.class.php'; // 错误处理
require_once __DIR__ . '/../config.php'; // 数据库配置
require_once __DIR__ . '/../includes/db.class.php'; // 数据库类

// 补全参数，兼容你的db.class.php
if (!isset($dbconfig['charset'])) $dbconfig['charset'] = 'utf8mb4';
if (!isset($dbconfig['pconnect'])) $dbconfig['pconnect'] = 0;

// 本地初始化数据库对象，不影响全局
$DB = DB::getInstance($dbconfig);

// 获取当前登录用户（主系统账号一体化）
$user = null;
if(isset($_SESSION['zid'])) {
    $user_id = $_SESSION['zid'];
    $user = $DB->query("SELECT * FROM shua_site WHERE zid='{$user_id}'")->fetch();
}

// 读取分类
$category = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_category` WHERE status=1 ORDER BY sort DESC, id ASC")->fetchAll();
// 读取banner
$banner_imgs = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_banner` WHERE status=1 ORDER BY sort DESC, id ASC")->fetchAll();
// 读取底部导航
$nav = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news_nav` WHERE status=1 ORDER BY sort DESC, id ASC")->fetchAll();
// 资讯
$page = 1;
$pageSize = 10;
$offset = 0;
$total = $DB->query("SELECT COUNT(*) as cnt FROM `{$dbconfig['dbqz']}_news`")->fetch()['cnt'];
$news = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news` ORDER BY add_time DESC LIMIT $offset, $pageSize")->fetchAll();

// 金融会员每日可看资讯条数限制
if ($user) {
    $today = date('Y-m-d');
    $viewed_count = $DB->fetchColumn("SELECT COUNT(*) FROM shua_news_viewlog WHERE zid='{$user['zid']}' AND date='$today'");
    $level = isset($user['finance_vip_level']) ? $user['finance_vip_level'] : 0;
    $expire_type = isset($user['finance_vip_expire_type']) ? $user['finance_vip_expire_type'] : '';
    $max_view = 0;
    if($level == 0) $max_view = 0;
    elseif($level == 1 && $expire_type=='month') $max_view = 5;
    elseif($level == 1 && $expire_type=='season') $max_view = 10;
    elseif($level == 1 && $expire_type=='year') $max_view = 20;
    elseif($level == 9) $max_view = 99999; // 永久VIP
    elseif($level == 2) $max_view = 99999; // 超级会员
    // 你可以在渲染资源区时用 $viewed_count >= $max_view 判断是否显示会员内容
}

set_error_handler(function(
    $errno, $errstr, $errfile, $errline
){
    echo "<pre>PHP ERROR: $errstr in $errfile on line $errline</pre>";
    exit;
});
set_exception_handler(function($e){
    echo "<pre>EXCEPTION: ".$e->getMessage()."\n".$e->getTraceAsString()."</pre>";
    exit;
});
?>
<!DOCTYPE html>
<html>
<head>
    <title>首页 - 产品资讯</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://web.aimallol.com/static/css/iconfont.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link rel="stylesheet" href="/static/news/style.css">
    <style>
    .top-bar{display:flex;align-items:center;padding:12px 10px 0 10px;background:#fff;gap:0;}
    .country{font-size:15px;color:#222;}
    .city{font-size:15px;color:#222;margin-left:4px;}
    .search{position:relative;width:100%;}
    .search input:focus{outline:2px solid #ff6600;}
    .icon-bell{font-size:20px;color:#888;margin-left:10px;}
    .category-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:10px 0;padding:18px 10px 0 10px;background:#fff;}
    .category-item{text-align:center;text-decoration:none !important;color:#333;transition:color 0.2s;display:block;}
    .category-item:hover span{color:#ff6600;font-weight:bold;}
    .category-item span{text-decoration:none !important;font-weight:bold;}
    .banner-row {display: flex;gap: 12px;margin: 18px 10px 0 10px;}
    .banner-large {flex: 2.1;border-radius: 16px;overflow: hidden;box-shadow: 0 4px 16px rgba(0,0,0,0.08);background: #f2f2f2;}
    .banner-large img {width: 100%;height: 120px;object-fit: cover;display: block;}
    .banner-small-group {flex: 1;display: flex;flex-direction: column;gap: 12px;}
    .banner-small {flex: 1;border-radius: 16px;overflow: hidden;box-shadow: 0 4px 16px rgba(0,0,0,0.08);background: #f2f2f2;}
    .banner-small img {width: 100%;height: 56px;object-fit: cover;display: block;}
    .tab-bar{display:flex;align-items:center;margin:18px 0 0 0;background:#fff;}
    .tab-bar .tab{flex:1;text-align:center;padding:10px 0;font-size:15px;color:#888;cursor:pointer;}
    .tab-bar .tab.active{color:#ff6600;font-weight:bold;border-bottom:2px solid #ff6600;}
    .news-list{padding:0 0 60px 0;/*overflow-y:auto;max-height:60vh;*/min-height:120px;}
    .news-item{background:#fff;margin:12px 12px 0 12px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.03);overflow:hidden;display:flex;align-items:flex-start;transition:box-shadow 0.2s;}
    .news-item img{width:90px;height:70px;object-fit:cover;border-radius:8px;margin:12px;background:#eee;flex-shrink:0;}
    .news-item h3{font-size:16px;margin:12px 0 6px 0;font-weight:600;line-height:1.3;}
    .news-item p{font-size:13px;color:#888;margin:0 0 12px 0;}
    .news-item > a{display:flex;flex-direction:row;align-items:flex-start;text-decoration:none;color:inherit;width:100%;}
    .news-item .info{flex:1;display:flex;flex-direction:column;justify-content:space-between;padding:8px 0 8px 0;}
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
        .top-bar{flex-wrap:wrap;gap:6px;}
        .search{margin:8px 0 0 0;max-width:100%;}
        .bottom-nav{left:6px;right:6px;bottom:8px;height:60px;padding-bottom:2px;border-radius:14px;}
        .bottom-nav .icon-wrap {width: 30px; height: 30px;}
        .bottom-nav .icon-wrap img{width:30px;height:30px;}
        .bottom-nav span{font-size:12px;}
        .bottom-nav .center-btn { width: 54px; height: 54px; top: -16px; }
        .bottom-nav .center-btn img,
        .bottom-nav .center-btn .rebate-bg { width: 54px; height: 54px; }
        .bottom-nav .center-btn span { font-size: 12px; bottom: -16px; }
        .banner-row {gap: 6px;}.banner-large img {height: 90px;}.banner-small img {height: 40px;}}
    .fav-float-btn {
        position: fixed;
        right: 18px;
        bottom: 60px;
        z-index: 99999 !important;
        background: linear-gradient(135deg,#fff 60%,#ffe7c2 100%);
        border-radius: 50%;
        box-shadow: 0 2px 8px #ff880022;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ff8800;
        cursor: pointer;
        font-size: 22px;
        color: #ff8800;
        opacity: 0.96;
    }
    .top-float-btn {
        position: fixed;
        right: 18px;
        bottom: 110px;
        z-index: 99999 !important;
        background: linear-gradient(135deg,#ffe7c2 60%,#fff 100%);
        border-radius: 50%;
        box-shadow: 0 2px 8px #ff880022;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ff8800;
        cursor: pointer;
        font-size: 18px;
        color: #ff8800;
        opacity: 0.92;
    }
    .top-float-btn:hover, .fav-float-btn:hover {
        box-shadow: 0 6px 24px #ff880044;
        opacity: 1;
        transform: translateY(-2px) scale(1.08);
    }
    .top-float-btn, .fav-float-btn {
        z-index: 2147483647 !important;
        pointer-events: auto !important;
    }
    </style>
</head>
<body>
    <div class="top-bar">
        <span class="country">中国</span>
        <span class="city" style="margin-left:4px;">南平市</span>
        <div class="search" style="flex:1;display:flex;align-items:center;max-width:520px;margin:0 0 0 12px;position:relative;">
            <input type="text" placeholder="输入全称或关键词搜索资源" id="searchInput" style="flex:1;min-width:0;max-width:100%;border-radius:22px;padding:8px 38px 8px 16px;font-size:15px;box-sizing:border-box;">
            <button id="searchBtn" type="button" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);cursor:pointer;z-index:2;display:flex;align-items:center;justify-content:center;width:28px;height:28px;background:#fff;border:none;padding:0;">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="9" cy="9" r="7" stroke="#888" stroke-width="2"/>
                    <line x1="14.2" y1="14.2" x2="18" y2="18" stroke="#888" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <i class="iconfont icon-bell" style="margin-left:10px;">&#xe7f4;</i>
    </div>
    <div class="category-grid">
        <?php foreach($category as $cat): ?>
        <?php
            $caturl = trim($cat['url']);
            if ($caturl === '' || $caturl === null) {
                $caturl = '/news/list.php?cat=' . $cat['id'];
            }
        ?>
        <a class="category-item" href="<?php echo htmlspecialchars($caturl); ?>">
            <img src="<?php echo htmlspecialchars($cat['icon']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
            <span><?php echo htmlspecialchars($cat['name']); ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="banner-row">
      <?php
      $banner1 = $banner_imgs[0] ?? null;
      $banner2 = $banner_imgs[1] ?? null;
      $banner3 = $banner_imgs[2] ?? null;
      ?>
      <?php if($banner1): ?>
        <div class="banner-large">
          <a href="<?php echo htmlspecialchars($banner1['url']); ?>" target="_blank">
            <img src="<?php echo htmlspecialchars($banner1['img']); ?>" alt="">
          </a>
        </div>
      <?php endif; ?>
      <div class="banner-small-group">
        <?php if($banner2): ?>
          <div class="banner-small">
            <a href="<?php echo htmlspecialchars($banner2['url']); ?>" target="_blank">
              <img src="<?php echo htmlspecialchars($banner2['img']); ?>" alt="">
            </a>
          </div>
        <?php endif; ?>
        <?php if($banner3): ?>
          <div class="banner-small">
            <a href="<?php echo htmlspecialchars($banner3['url']); ?>" target="_blank">
              <img src="<?php echo htmlspecialchars($banner3['img']); ?>" alt="">
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="tab-bar">
        <div class="tab active" data-tab="news">热门产品资讯</div>
        <div class="tab" data-tab="welfare">活动福利</div>
        <div class="tab" data-tab="resource">资源市场</div>
    </div>
    <div class="news-list" id="tab-news">
        <?php if(empty($news)): ?>
            <div style="padding:2em;text-align:center;color:#888;">暂无资讯</div>
        <?php else: ?>
            <?php foreach($news as $row): ?>
        <div class="news-item" id="news-<?php echo $row['id']; ?>">
            <a href="detail.php?id=<?php echo $row['id']; ?>">
                    <img src="<?php echo htmlspecialchars($row['cover_url']); ?>" alt="">
                    <div class="info">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo date('Y-m-d H:i', strtotime($row['add_time'])); ?> 阅读:<?php echo $row['read_count']; ?></p>
                    </div>
            </a>
        </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div id="loadMore" style="text-align:center;color:#888;padding:1em;display:none;">加载中...</div>
    <div class="news-list" id="tab-welfare" style="display:none;">
        <div style="padding:2em;text-align:center;color:#888;">暂无活动福利</div>
    </div>
    <div class="news-list" id="tab-resource" style="display:none;">
      <div id="resource-market-section" style="margin-top:30px;">
        <h3>资源出售区</h3>
        <form class="form-inline" id="resource-search-form" style="margin-bottom:20px;">
          <input type="text" name="resource_keyword" placeholder="搜索资源" class="form-control">
          <select name="resource_category" class="form-control">
            <option value="">全部分类</option>
            <?php
            $categories = $DB->query("SELECT DISTINCT class FROM shua_goods WHERE status=1")->fetchAll();
            foreach($categories as $cat){ ?>
              <option value="<?=$cat['class']?>"><?=$cat['class']?></option>
            <?php } ?>
          </select>
          <button type="submit" class="btn btn-primary">搜索</button>
          <button type="button" class="btn btn-success" id="resource-publish-btn" style="margin-left:10px;">发布资源</button>
        </form>
        <div class="row" id="resource-list"></div>
      </div>
      <!-- 资源发布弹窗 -->
      <div class="modal fade" id="resource-publish-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <form method="post" enctype="multipart/form-data" id="resource-publish-form">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">发布资源</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <input type="text" name="name" class="form-control" placeholder="资源名称" required>
                <textarea name="content" class="form-control" placeholder="资源详情" required></textarea>
                <input type="text" name="class" class="form-control" placeholder="分类ID" required>
                <input type="number" name="price" class="form-control" placeholder="价格" required>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success">提交发布</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- 资源详情弹窗 -->
      <div class="modal fade" id="resource-detail-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content" id="resource-detail-content"></div>
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
    <!-- 收藏漂浮按钮 -->
    <button id="favFloatBtn" class="fav-float-btn" title="收藏夹">
        <svg id="favIcon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-6.5-5.2-9-8.5C-1.5 7.5 3.5 3 8 5.5c2.1 1.2 4 1.2 6 0C20.5 3 25.5 7.5 21 12.5c-2.5 3.3-9 8.5-9 8.5z"/></svg>
    </button>
    <!-- 返回顶部按钮 -->
    <button id="topFloatBtn" class="top-float-btn" title="返回顶部" style="display:none;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
    </button>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
    // 页面状态管理
    let pageState = {
        currentTab: 'news',
        currentPage: 1,
        loading: false,
        noMore: false,
        pageSize: 10
    };

    // 初始化页面
    function initPage() {
        // 初始化标签切换
        initTabSwitch();
        // 初始化搜索
        initSearch();
        // 初始化收藏按钮
        initFavButton();
        // 初始化返回顶部按钮
        initTopButton();
    }

    // 初始化标签切换
    function initTabSwitch() {
        const tabs = document.querySelectorAll('.tab-bar .tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.dataset.tab;
                if(tabName === pageState.currentTab) return;
                
                // 更新状态
                pageState.currentTab = tabName;
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // 显示对应内容
                document.querySelectorAll('.news-list').forEach(list => {
                    list.style.display = 'none';
                });
                const targetList = document.getElementById('tab-' + tabName);
                if(targetList) {
                    targetList.style.display = '';
                    // 如果是新闻标签，重置并加载数据
                    if(tabName === 'news') {
                        resetNewsList();
                        loadFirstPage();
                    }
                }
            });
        });
    }

    // 重置新闻列表
    function resetNewsList() {
        pageState.currentPage = 1;
        pageState.noMore = false;
        const newsList = document.getElementById('tab-news');
        if(newsList) {
            newsList.innerHTML = '';
        }
        const loadMore = document.getElementById('loadMore');
        if(loadMore) {
            loadMore.style.display = 'none';
        }
    }

    // 加载第一页数据
    function loadFirstPage() {
        if (pageState.loading) return;
        
        pageState.loading = true;
        const loadMore = document.getElementById('loadMore');
        if(loadMore) loadMore.style.display = '';
        
        fetch(`/news/api.php?page=1&pageSize=${pageState.pageSize}`)
            .then(res => res.json())
            .then(data => {
                if(data && data.data && data.data.length > 0) {
                    appendNews(data.data);
                    pageState.currentPage = 1;
                    if(data.data.length < pageState.pageSize) {
                        pageState.noMore = true;
                        if(loadMore) loadMore.innerText = '没有更多了';
                    } else {
                        if(loadMore) loadMore.style.display = 'none';
                    }
                } else {
                    pageState.noMore = true;
                    if(loadMore) loadMore.innerText = '没有更多了';
                }
                pageState.loading = false;
            })
            .catch((e) => {
                pageState.loading = false;
                if(loadMore) loadMore.innerText = '加载失败';
                console.error('API请求失败', e);
            });
    }

    // 添加新闻到列表
    function appendNews(items) {
        const list = document.getElementById('tab-news');
        if (!list) return;
        
        items.forEach(row => {
            const div = document.createElement('div');
            div.className = 'news-item';
            div.id = 'news-' + row.id;
            div.innerHTML = `
                <a href="detail.php?id=${row.id}">
                    <img src="${row.cover_url || '/static/news/default.jpg'}" alt="">
                    <div class="info">
                        <h3>${row.title}</h3>
                        <p>${row.add_time} 阅读:${row.read_count || 0}</p>
                    </div>
                </a>
            `;
            list.appendChild(div);
        });
    }

    // 优化滚动加载
    let scrollTimeout;
    function initScrollLoad() {
        window.addEventListener('scroll', function() {
            if(scrollTimeout) clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(tryLoadMore, 100);
        });
    }

    function tryLoadMore() {
        if (pageState.loading || pageState.noMore || pageState.currentTab !== 'news') return;
        
        let scrollTop = window.scrollY || document.documentElement.scrollTop || document.body.scrollTop;
        let winHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        let docHeight = document.body.offsetHeight || document.documentElement.offsetHeight;
        
        if ((winHeight + scrollTop) >= docHeight - 120) {
            pageState.loading = true;
            let loadMore = document.getElementById('loadMore');
            if(loadMore) loadMore.style.display = '';
            
            fetch(`/news/api.php?page=${pageState.currentPage + 1}&pageSize=${pageState.pageSize}`)
                .then(res => res.json())
                .then(data => {
                    if(data && data.data && data.data.length > 0) {
                        appendNews(data.data);
                        pageState.currentPage++;
                        if(data.data.length < pageState.pageSize) {
                            pageState.noMore = true;
                            if(loadMore) loadMore.innerText = '没有更多了';
                        } else {
                            if(loadMore) loadMore.style.display = 'none';
                        }
                    } else {
                        pageState.noMore = true;
                        if(loadMore) loadMore.innerText = '没有更多了';
                    }
                    pageState.loading = false;
                })
                .catch((e) => {
                    pageState.loading = false;
                    if(loadMore) loadMore.innerText = '加载失败';
                    console.error('API请求失败', e);
                });
        }
    }

    // 初始化搜索
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        
        if(searchInput && searchBtn) {
            searchInput.addEventListener('keydown', function(e) {
                if(e.key === 'Enter') doSearch();
            });
            
            searchBtn.addEventListener('click', doSearch);
        }
    }

    // 搜索功能
    function doSearch() {
        const searchTerm = document.getElementById('searchInput').value.trim();
        if(searchTerm.length > 0) {
            window.location.href = '/news/search.php?kw=' + encodeURIComponent(searchTerm);
        }
    }

    // 初始化收藏按钮
    function initFavButton() {
        const favBtn = document.getElementById('favFloatBtn');
        if(favBtn) {
            favBtn.onclick = function() {
                window.location.href = '/news/favorites.php';
            };
        }
    }

    // 初始化返回顶部按钮
    function initTopButton() {
        const topBtn = document.getElementById('topFloatBtn');
        if(topBtn) {
            window.addEventListener('scroll', function() {
                if(document.body.scrollHeight > window.innerHeight && window.scrollY > 100) {
                    topBtn.style.display = 'flex';
                } else {
                    topBtn.style.display = 'none';
                }
            });
            
            topBtn.onclick = function() {
                window.scrollTo({top: 0, behavior: 'smooth'});
            };
        }
    }

    // 页面加载完成后初始化
    window.addEventListener('DOMContentLoaded', function() {
        initPage();
        initScrollLoad();
        
        // 检查是否需要滚动到特定位置
        const url = new URL(window.location.href);
        const fromId = url.searchParams.get('fromId');
        if(fromId) {
            const target = document.getElementById('news-' + fromId);
            if(target) {
                setTimeout(() => {
                    target.scrollIntoView({behavior: 'auto', block: 'start'});
                }, 100);
            }
        }
    });
    </script>
</body>
</html>