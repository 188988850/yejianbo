<?php
if (!defined('IN_CRONLITE')) die();
$get = $_GET;

if(isset($get['type']) && !empty($get['type'])){
    $type = $get['type'];
}else{
    $type = 'hot';
}


$videotype=$DB->getAll("select * from shua_videotype");


?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>热门短剧</title>
    <link rel="stylesheet" href="/assets/css/duanju.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var page = 1;
            var loading = false; // 防止同时发起多个请求
            var type = '<?=$get['type']?>'; // 获取当前类型

            function loadMoreData() {
                if (loading) return;

                loading = true;
                $.ajax({
                    url: './ajax.php?act=duanju',
                    type: 'GET',
                    data: {page: page, type: type},
                    success: function(data) {
                        if(data.code == 0){
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

            loadMoreData();
            
            // 切换 active 类并更新 URL
            $('.category-tabs button').click(function() {
                 // 获取当前按钮的数据类型
                 var type = $(this).data('type');
                 if (type) {
                     window.location.href = '?mod=duanju&type=' + type;
                 }
            });
        });
        
        function search(){
                var keyword = $('#keyword').val(); 
                var page = 1;
                var type = '<?=$get['type']?>'; // 获取当前类型
                $.ajax({
                    url: './ajax.php?act=duanju',
                    type: 'GET',
                    data: {
                        keyword:keyword,
                        page: page, 
                        type: type
                        
                    },
                    success: function(data) {
                        if(data.code == 0){
                             $('.drama-display').html(data.data);
                        }
                    }
                });
            }
    </script>
</head>  
<body>  
<div class="container">
    <header>  
        <div class="search-bar">  
            <input type="text" id="keyword" placeholder="搜索短剧...">  
            <button onclick="search()">搜索</button>  
        </div>  
        <nav class="category-tabs">  
           <button class="<?php if($type=='hot'){echo 'active';}?>" data-type="hot">热门短剧</button> 
           <button class="<?php if($type=='new'){echo 'active';}?>" data-type="new">最新短剧</button> 
           <?php foreach($videotype as $k=>$v){?>
                <button class="<?php if($type==$v['name']){echo 'active';}?>" data-type="<?php echo $v['name'];?>"><?php echo $v['name'];?></button> 
            <?php }?>
        </nav>  
    </header>  
    <main>
        <section class="drama-display" id="drama-display">
          
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