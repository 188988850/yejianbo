<?php 
//查询一级分类
$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 AND cidr='0' order by sort asc");
$classhide = explode(',',$siterow['class']);
while ($res = $rs->fetch()) {
    if($is_fenzhan && in_array($res['cid'], $classhide))continue;
    $classdata[] = $res;
    $classdata2[] = $res;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品分类</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css2/class.css?2">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css2/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index1.css">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">
	<style>html{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>
</head>
<body>
<style>
.yijav{
    background: #fff !important;
}
.headbox {
    height: 30px;
    width: 96%;
    margin: 10px 2%;
    background: #F0F3F7;
    /* border-radius: 5px; */
    /* padding: 2px; */
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    align-content: center;
    flex-wrap: nowrap;
    flex-direction: row;
}

.fui-navbar .nav-item {
    position: relative;
    display: table-cell;
    <?php if(checkmobile()){ ?>height: 3.3rem;<?php }else{ ?>height: 5rem;<?php }?>
    text-align: center;
    vertical-align: middle;
    width: 1%;
    color: #999;
}

.box img {
    border-radius: 0%;
    width: 35px;
    height: 35px;
}
.box .erjitlele {
    color: #555555;
    font-size: 13px;
    padding: 3px 5px 0px 5px;
}
.goods-right {
    width: 100%;
    height: 100%;
    overflow-y: scroll;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #f2f2f2;
}
.erjibox {
    border-radius: 10px;
    background: #fff;
    width: 95%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    margin: 5px 3%;
    padding: 5px 0px;
}
.yiji p {
    font-weight: 400;
    font-size: 13px;
    color: #333;
}
.goods-list1 {
    padding-top: 0px;
    display: flex;
    background: #fff;
}
.erjibox .box {
    margin: 5px 0;
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    align-items: center;
    width: 24.8%;
    text-decoration: none;
}
.hometop {
    padding-top: 10px;
    width: 100%;
    background: linear-gradient( 
180deg,#ff9552 0%,#ffa56a 60%,#f2f2f2 100%);
}
</style>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 550px;background: #fff;">
    <div class="head">

    </div>
                <a id="backToTop" style="position:fixed;right:10px;bottom:10%; z-index:20; display:none;" href="#top">
                    <img style="width:45px;height:45px;border-radius:50px;border: 2px solid #e8e9ec;background-color:#fff" src="/assets/img/xtb/dingbu.png"/>
                </a>
    
    <div class="goods-list1">

    <div class="goods-right">
                       <div class="hometop">

<!--滚动横幅开始-->
                    <div class="fui-swipe">
                        <style>
                            .fui-swipe-page .fui-swipe-bullet {
                                background: #ffffff;
                                opacity: 0.5;
                            }

                            .fui-swipe-page .fui-swipe-bullet.active {
                                opacity: 1;
                            }
                        </style>
                        <div class="fui-swipe-wrapper"  style="transition-duration: 500ms;">
                            
                            <?php
                            $banner = explode('|', $conf['banner']);
                            foreach ($banner as $v) {
                                $image_url = explode('*', $v);
                                echo '<a class="fui-swipe-item" href="' . $image_url[1] . '">
                                <img src="' . $image_url[0] . '" style="border-radius: 15px;display: block; width: 90%;margin:10px 5%; height: auto;" />
                            </a>';
                            }
                            ?>
                                        </div>
                        <!--div class="fui-swipe-page right round" style="text-align:center;padding: 0 5px; bottom: 5px; ">
                        </div-->
                    </div>
<!--滚动横幅结束-->
                </div>
        
    <?php foreach ($classdata2 as $v){ ?>
        <div class="erjibox" id="cid<?php echo $v['cid']?>" style="margin: 5px 0px;background: #FFF;position: relative;">
                
    <div class="erjibox" style="display: flex; align-items: center;">
    <span style="margin-left: 30px;">
        <img src="<?php echo $v['shopimg']?>" onerror="this.src='assets/store/picture/1d61d24d09e6a12b86e18249722b65ef.png'" style="position: absolute;width: 22px;height: 22px;left: 15px;"><a href="./?mod=type&cid=<?php echo $v['cid']?>" style="color: #333; font-size: 15px;font-weight: 400;">
            <?php echo $v['name']?>
        </a>
    </span>
    <span style="margin-left: auto;">
        <a href="./?mod=type&cid=<?php echo $v['cid']?>" style="color: #b2b2b2; font-size: 13px;">
            查看全部 <i class="	fa fa-angle-double-right"></i>
        </a>
    </span>
    <hr style="margin-top: 15px; border-top: 1px dashed #f2f2f2; width: 100%;">
</div>
<?php
$classhide = explode(',',$siterow['class']);
$rs=$DB->query("SELECT * FROM shua_class WHERE cidr='{$v['cid']}' and active=1 order by sort asc");
$shua_class=array();
while($res = $rs->fetch()){
if($is_fenzhan && in_array($res['cid'], $classhide))continue;
?>
                  <a href="./?mod=type&cid=<?php echo $res['cid']?>" class="box">
                      <img src="<?php echo $res['shopimg']?>" onerror="this.src='assets/store/picture/1d61d24d09e6a12b86e18249722b65ef.png'">
                      <span class="erjitlele"><?php echo $res['name']?></span>
                  </a>
                  <?php }?>
              </div>
              <?php }?>
        </div>
    </div><br><br><br>
<!--导航开始-->
<style>
  .bottom-nav {
    max-width: 550px;
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #fff;
    color: #333;
    text-align: center;
    padding: 5px 0;
    z-index: 100;
    /* 添加一些额外的样式来优化小屏幕设备的显示 */
    display: flex;
    justify-content: space-around; /* 导航项均匀分布 */
    flex-wrap: wrap; /* 允许换行 */
  }

  .bottom-nav a {
    color: #333;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    padding: 5px 15px;
    /* 可以在这里添加更小的字体和间距，以适应更小的屏幕 */
  }

  .bottom-nav a:hover {
    background-color: #ddd;
    color: black;
  }

  /* 媒体查询开始，针对手机等小屏幕设备 */
  @media (max-width: 768px) {
    .bottom-nav a {
      font-size: 12px; /* 减小字体大小 */
      padding: 5px 10px; /* 减小内边距 */
    }
    
    .bottom-nav i {
      font-size: 14px; /* 减小图标大小 */
    }

    /* 你可以根据需要添加更多的样式调整 */
  }

  /* 如果需要，可以添加更多媒体查询来适应不同屏幕尺寸 */
</style>

<div class="bottom-nav" style="border-radius: 0px;box-shadow: 0px 1px 1px 0px #e2dfdf;border: 1px solid #f2f2f2;">
  <a href="./"><i class="fa fa-windows" style="font-size: 17px;"></i><br>首页</a>
  <a href="" style="color:#ff7c33;"><i class="fa fa-table" style="font-size: 17px;"></i><br>分类</a>
  <a href="./?mod=duanju"><i class="fa fa-youtube-play" style="font-size: 17px;"></i><br>短剧</a>
  <?php  if($userrow['power']==0 || $userrow['power']==1){?>
  <a href="user/upgrade.php"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>升级</a>
  <?php }?>
  <?php  if( $userrow['power']==2){?>
  <a href="./?mod=latest"><i class="fa fa-diamond" style="font-size: 17px;"></i><br>最新上架</a>
  <?php }?>
  <a href="./?mod=query"><i class="fa fa-list-ol" style="font-size: 17px;"></i><br>订单</a>
  <a href="./user/"><i class="fa fa-github-alt" style="font-size: 17px;"></i><br>会员中心</a>
</div>
<!--导航结束-->
    
    
    <script src="<?php echo $cdnpublic?>jquery/3.4.1/jquery.min.js"></script>
    <script>
    var backToTop = document.getElementById('backToTop');
window.onscroll = function() {
    var scrollDistance = window.pageYOffset || document.documentElement.scrollTop;
    var threshold = 300; 
    if (scrollDistance > threshold) {
        backToTop.style.display = 'block';
    } else {
        backToTop.style.display = 'none';
    }
};
         $('.yiji').click(function(event) {
          $('.yiji span').removeClass('active'); // 移除其他链接下的.active类
          $('.yiji').removeClass('yijav'); // 移除其他链接下的.active类
        $(this).find('span').addClass('active'); // 添加当前链接下的.active类
        $(this).addClass('yijav');
        event.preventDefault(); // 阻止默认的标签点击行为

        var targetId = $(this).attr('href'); // 获取目标id
        var targetPosition = $(targetId).offset().top; // 获取目标位置的上边缘位置
        console.log(targetPosition);

        $('.goods-right').animate({ scrollTop: targetPosition }, 800); // 平滑滚动到目标位置，时间为800毫秒
      });
        $('#myinput').keypress(function(event) {
        if (event.which === 13) {
          // 按下了回车键
          var inputValue = $(this).val();
          window.location.href = './?kw='+inputValue;
          // 在这里执行您想要的操作
        }
      });
    </script>
</body>
</html>