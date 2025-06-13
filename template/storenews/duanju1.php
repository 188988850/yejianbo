<?php if (!defined('IN_CRONLITE')) die(); 
$get = $_GET; 
$type = isset($get['type']) && !empty($get['type']) ? $get['type'] : 'hot'; 
$videotype = $DB->getAll("SELECT * FROM shua_videotype"); 
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>热门短剧</title>
    <link rel="stylesheet" href="/assets/css/duanju.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        var page = 1;
        var loading = false;
        var type = '<?=$get['type']?>';
        
        // 四宫格内容数据
        var gridItems = [
            { title: "短剧1", description: "这是短剧1的简介", image: "https://img1.baidu.com/it/u=3075276504,3443869891&fm=253&fmt=auto&app=138&f=JPEG?w=630&h=318", url: "./page1.php" },
            { title: "短剧2", description: "这是短剧2的简介", image: "https://img1.baidu.com/it/u=410177692,2993954610&fm=253&fmt=auto&app=138&f=JPEG?w=889&h=500", url: "./page2.php" },
            { title: "短剧3", description: "这是短剧3的简介", image: "https://img1.baidu.com/it/u=2212281332,3442342214&fm=253&fmt=auto&app=138&f=JPEG?w=600&h=337", url: "./page3.php" },
            { title: "短剧4", description: "这是短剧4的简介", image: "https://img2.baidu.com/it/u=1286533134,3948294194&fm=253&fmt=auto&app=138&f=JPEG?w=889&h=500", url: "./page4.php" }
        ];

        // 加载四宫格内容
        function loadGridItems() {
            gridItems.forEach(function(item) {
                var gridItemHtml = `
                    <div class="grid-item">
                        <a href="${item.url}" class="grid-link">
                            <img src="${item.image}" alt="${item.title}">
                            <div class="item-info">
                                <h3>${item.title}</h3>
                                <p>${item.description}</p>
                            </div>
                        </a>
                    </div>
                `;
                $('.grid-container').append(gridItemHtml);
            });
        }

        loadGridItems(); // 加载四宫格内容

        function loadMoreData() {
            if (loading) return;
            loading = true;
            $.ajax({
                url: './ajax.php?act=duanju',
                type: 'GET',
                data: { page: page, type: type },
                success: function(data) {
                    if(data.code == 0) {
                        $('.drama-display').append(data.data);
                        page++;
                        loading = false;
                    }
                }
            });
        }

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                loadMoreData();
            }
        });

        $('.category-tabs button').click(function() {
            var type = $(this).data('type');
            if (type) {
                window.location.href = '?mod=duanju&type=' + type;
            }
        });

        // 回到顶部的功能
        $("#scrollTop").click(function() {
            $("html, body").animate({ scrollTop: 0 }, 500);
        });

        // 返回上一页的功能
        $("#goBack").click(function() {
            window.history.back();
        });

        // 另外，为了固定搜索框
        $('#keyword').focus(function() {
            $(this).css('opacity', 1); // 当获得焦点时，设置不透明度为1
        });
    });

    function search() {
        var keyword = $('#keyword').val();
        var page = 1;
        var type = '<?=$get['type']?>'; 
        $.ajax({
            url: './ajax.php?act=duanju',
            type: 'GET',
            data: { keyword: keyword, page: page, type: type },
            success: function(data) {
                if(data.code == 0) {
                    $('.drama-display').html(data.data);
                }
            }
        });
    }
    </script>
    <style>
        body {
            background-color: #121212; /* 黑色背景 */
            color: #ffffff; /* 字体颜色 */
        }

        .floating-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
        }

        .floating-button {
            background-color: #555;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            margin: 5px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .floating-button:hover {
            background-color: #777;
        }

        .marquee-container {
            background: #222; /* 外框背景颜色 */
            border: 2px solid #555; /* 外框样式 */
            border-radius: 10px; /* 圆角 */
            overflow: hidden; /* 隐藏溢出 */
            margin-top: 10px; /* 上边距 */
            width: 94%; /* 设置为95%宽度 */
        }

        .marquee {
            color: #fff; /* 字体颜色 */
            white-space: nowrap; /* 不换行 */
            overflow: hidden; /* 溢出隐藏 */
            box-shadow: inset 0 0 5px rgba(0,0,0,0.5); /* 内阴影效果 */
            display: flex; /* 使用flex布局 */
        }

        .marquee span {
            display: inline-block; /* 内联元素块显示 */
            padding: 10px; /* 内边距 */
            font-size: 12px; /* 缩小文本大小 */
            animation: marquee 20s linear infinite; /* 动画效果，每条公告滚动20秒不停止 */
        }

        @keyframes marquee {
            0% { transform: translateX(100%); } /* 从右侧开始 */
            100% { transform: translateX(-100%); } /* 向左结束 */
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 两列布局 */
            gap: 10px; /* 宫格间距 */
            margin-top: 20px;
            background-color: #222; /* 背景颜色 */
            border: 2px solid #000; /* 更改为黑色外框 */
            border-radius: 10px; /* 圆角外框 */
            padding: 10px; /* 内边距 */
        }

        .grid-item {
            position: relative; /* 为了能放置定位的覆盖层 */
            background-color: #333;
            border-radius: 10px; /* 圆角 */
            overflow: hidden; /* 隐藏超出部分 */
            transition: transform 0.3s;
            height: 90px; /* 设置高度 */
        }

        .grid-item img {
            width: 100%; /* 图片设置为100%宽度 */
            height: 90%; /* 修改为100%以适应新高度 */
            object-fit: cover; /* 保持比例裁剪 */
        }

        .item-info {
            position: absolute; /* 绝对定位 */
            top: 50%; /* 垂直居中 */
            left: 50%; /* 水平居中 */
            transform: translate(-50%, -50%); /* 完全居中 */
            text-align: center; /* 中心对齐 */
            color: #fff; /* 字体颜色 */
            z-index: 1; /* 确保信息在图片之上 */
        }

        .item-info h3 {
            margin: 5px 0 0; /* 顶部边距 */
            font-size: 14px; /* 标题字体大小 */
        }

        .item-info p {
            font-size: 10px; /* 简介字体大小 */
            margin: 5px 0 0; /* 上边距 */
        }

        .grid-item:hover {
            transform: scale(1.05); /* 悬停时放大 */
        }

        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            width: 95%; /* 设置为95%宽度 */
        }

        .search-bar input {
            flex: 1;
            margin-right: 5px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #fff; /* 输入框背景颜色设置为纯白色 */
            color: #000; /* 输入框字体颜色设置为黑色以适应背景 */
            font-size: 16px; /* 确保字体大小为16px，避免放大 */
            touch-action: manipulation; /* 禁用放大效果 */
        }

        .search-bar button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #555; /* 按钮的背景颜色 */
            color: #fff; /* 按钮文本颜色 */
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .search-bar button:hover {
            background-color: #777; 
        }

        .category-container {
            border: 2px solid #555; /* 外框样式 */
            border-radius: 10px; /* 圆角 */
            padding: 10px; /* 内边距 */
            margin-bottom: 20px; /* 下边距 */
            width: 95%; /* 设置为95%宽度 */
        }

        .category-tabs {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .category-tabs button {
            margin: 5px;
            flex: 0 1 calc(20% - 10px);
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 12px;
        }

        .category-tabs button.active {
            background-color: #777;
        }

        .category-tabs button:hover {
            background-color: #555;
            transform: scale(1.05);
        }

        .drama-section {
            padding: 10px;
            border: 2px solid #555; /* 整体区域外框 */
            border-radius: 10px; /* 圆角 */
            background-color: #222; /* 背景颜色 */
        }

        .footer-nav {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border: 2px solid #fff; /* 白色外框 */
            border-radius: 10px; /* 圆角 */
            background-color: #333; /* 背景颜色 */
        }

        .footer-nav a {
            color: #fff;
        }

        .cover-slider {
            position: relative; /* 为封面添加相对定位 */
            background-color: #000; /* 封面的背景色改为黑色 */
            border-radius: 10px; /* 封面的圆角 */
            overflow: hidden; /* 隐藏超出部分 */
            height: 200px; /* 固定高度，标记封面外框高度 */
            width: 95%; /* 固定宽度，标记封面外框宽度 */
        }

        .cover-slider img {
            width: 100%; /* 封面图100%宽 */
            height: 100%; /* 高度自适应 */
            object-fit: cover; /* 保持比例裁剪 */
        }

        .cover-content {
            position: absolute; /* 绝对定位 */
            top: 50%; /* 垂直居中 */
            left: 50%; /* 水平居中 */
            transform: translate(-50%, -50%); /* 完全居中 */
            text-align: center; /* 中心对齐 */
            color: #fff; /* 字体颜色 */
        }

        .cover-content h2 {
            margin: 0; /* 去掉边距 */
            font-size: 24px; /* 标题字体大小 */
        }

        .cover-content p {
            margin: 5px 0 0; /* 上边距 */
            font-size: 16px; /* 简介字体大小 */
        }

        .cover-link {
            display: none; /* 隐藏转接链接 */
            margin-top: 10px; /* 上边距 */
            padding: 10px 15px;
            background-color: #555; /* 链接按钮颜色 */
            color: #fff; /* 字体颜色 */
            text-decoration: none; /* 去掉下划线 */
            border-radius: 5px; /* 圆角 */
        }

        .cover-link:hover {
            background-color: #777; /* 悬停时颜色变化 */
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="cover-slider" id="cover-slider">
            <img src="https://pic1.zhimg.com/80/v2-5a1d1088c75217e4e3c5100bf024604f_r.jpg" alt="封面图" id="cover-image" style="display: block;">
            <div class="cover-content">
                <h2 id="cover-title">全民影院</h2>
                <p id="cover-description">上万影视，每日更新！</p>
                <a href="./cover-page.php" class="cover-link">转到封面页面</a>
            </div>
        </div>
        <div class="search-bar"> <!-- 搜索栏 -->
            <input type="text" id="keyword" placeholder="搜索短剧..." style="opacity: 1;"> <!-- 设置初始不透明度为1 -->
            <button onclick="search()">搜索</button>
        </div>
        <div class="marquee-container"> <!-- 公告栏 -->
            <div class="marquee">
                <span>欢迎来到全民影院！短剧每日更新中!如有失效影视无法播放，可提交工单，客服24小时内会为您做退款处理!</span>
                <span>感谢您的支持，如有任何意见和建议，请随时联系我们的客服团队。</span>
                <span>我们的平台会定期更新短剧，请保持关注以获取更多精彩内容！</span>
            </div>
        </div>
    </header>
    <main>
        <div class="grid-container"> <!-- 四宫格内容将由JavaScript加载 --> </div>
        <div class="category-container">
            <nav class="category-tabs">
                <button class="<?php if($type=='hot'){echo 'active';}?>" data-type="hot">热门短剧</button>
                <button class="<?php if($type=='new'){echo 'active';}?>" data-type="new">最新短剧</button>
                <?php foreach($videotype as $k => $v) { ?>
                    <button class="<?php if($type==$v['name']){echo 'active';}?>" data-type="<?php echo $v['name']; ?>"><?php echo $v['name']; ?></button>
                <?php } ?>
            </nav>
        </div>
        <div class="drama-section">
            <section class="drama-display" id="drama-display"></section>
        </div>
    </main>
    <footer>
        <nav class="footer-nav">
            <a href="/">首页</a>
            <a href="./?mod=cart">购物车</a>
            <a href="./?mod=fenlei">分类</a>
            <a href="./?mod=query">订单</a>
            <a href="./?mod=kf">客服</a>
            <a href="./user/">会员中心</a>
        </nav>
    </footer>
</div>

<!-- 漂浮窗口 -->
<div class="floating-buttons">
    <button id="scrollTop" class="floating-button">回到顶部</button>
    <button id="goBack" class="floating-button">返回上一页</button>
</div>

</body>
</html>