<?php
if (!defined('IN_CRONLITE')) die();
$get = $_GET;

$type = isset($get['type']) ? $get['type'] : 'hot';
$main = isset($get['main']) ? intval($get['main']) : 0;
$sub = isset($get['sub']) ? $get['sub'] : '';

// 获取主分类
$main_categories = $DB->getAll("SELECT * FROM shua_videotype WHERE parent_id = 0 ORDER BY sort ASC");
// 获取子分类
$sub_categories = [];
if ($main > 0) {
    $sub_categories = $DB->getAll("SELECT * FROM shua_videotype WHERE parent_id = ? ORDER BY sort ASC", [$main]);
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>热门影视</title>
    <link rel="stylesheet" href="/assets/css/ysduanju.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var page = 1;
            var loading = false;
            var type = '<?=$type?>';
            var main = <?=$main?>;
            var sub = '<?=$sub?>';
            var end = false;

            function loadMoreData(reset) {
                if (loading || end) return;
                loading = true;
                if(reset){
                    page = 1;
                    end = false;
                    $('.drama-display').html('');
                }
                $.ajax({
                    url: '/ajax.php?act=ysduanju',
                    type: 'GET',
                    dataType: 'json',
                    data: {page: page, type: type, main: main, sub: sub, keyword: $('#keyword').val()},
                    success: function(data) {
                        if(data.code == 0){
                            if(data.data){
                                $('.drama-display').append(data.data);
                                page++;
                                loading = false;
                            }else{
                                if(page==1) $('.drama-display').html('<div style="text-align:center;color:#888;padding:30px 0;">暂无资源</div>');
                                end = true;
                            }
                        }else{
                            if(page==1) $('.drama-display').html('<div style="text-align:center;color:#888;padding:30px 0;">暂无资源</div>');
                            end = true;
                        }
                    },
                    error: function(){
                        loading = false;
                    }
                });
            }

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 50) {
                    loadMoreData();
                }
            });

            loadMoreData();

            // 切换热门/最新
            $('.category-tabs button[data-type]').click(function() {
                var type = $(this).data('type');
                window.location.href = '?mod=ysduanju&type=' + type + (main ? ('&main=' + main) : '');
            });
            // 切换主分类
            $('.main-category').click(function() {
                var mainId = $(this).data('id');
                window.location.href = '/index.php?mod=ysduanju&main=' + mainId + (type ? ('&type=' + type) : '');
            });
            // 切换子分类
            $('.sub-category').click(function() {
                var subName = $(this).data('name');
                window.location.href = '/index.php?mod=ysduanju&main=' + main + '&sub=' + encodeURIComponent(subName) + (type ? ('&type=' + type) : '');
            });
            // 搜索
            $('#keyword').on('keydown', function(e){if(e.keyCode==13) search();});
        });
        function search(){
            $(window).scrollTop(0);
            $('.drama-display').html('');
            page = 1;
            end = false;
            loading = false;
            $.ajax({
                url: '/ajax.php?act=ysduanju',
                type: 'GET',
                dataType: 'json',
                data: {
                    keyword:$('#keyword').val(),
                    page: 1,
                    type: '<?=$type?>',
                    main: <?=$main?>,
                    sub: '<?=$sub?>'
                },
                success: function(data) {
                    if(data.code == 0 && data.data){
                        $('.drama-display').html(data.data);
                        page = 2;
                        end = false;
                    }else{
                        $('.drama-display').html('<div style="text-align:center;color:#888;padding:30px 0;">暂无资源</div>');
                        end = true;
                    }
                },
                error: function(){
                    $('.drama-display').html('<div style="text-align:center;color:#888;padding:30px 0;">加载失败</div>');
                }
            });
        }
    </script>
</head>  
<body>  
<div class="container">
    <header>  
        <div class="search-bar">  
            <input type="text" id="keyword" placeholder="搜索影视...">  
            <button onclick="search()">搜索</button>  
        </div>  
        <nav class="category-tabs">  
           <button class="<?php if($type=='hot'){echo 'active';}?>" data-type="hot">热门影视</button> 
           <button class="<?php if($type=='new'){echo 'active';}?>" data-type="new">最新影视</button> 
           <?php foreach($main_categories as $cat){?>
                <button class="main-category <?php if($main==$cat['id']){echo 'active';}?>" data-id="<?php echo $cat['id'];?>"><?php echo $cat['name'];?></button> 
           <?php }?>
        </nav>  
        <?php if(!empty($sub_categories)){?>
        <div class="sub-categories">
            <?php foreach($sub_categories as $subcat){?>
                <button class="sub-category <?php if($sub==$subcat['name']){echo 'active';}?>" data-name="<?php echo $subcat['name'];?>"><?php echo $subcat['name'];?></button>
            <?php }?>
        </div>
        <?php }?>
    </header>  
    <main>
        <section class="drama-display" id="drama-display">
          <!-- 只显示封面和标题，内容由ajax.php返回，页面不再渲染简介、价格等内容 -->
        </section>
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
    
</body>  
</html>