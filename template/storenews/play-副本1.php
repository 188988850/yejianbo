<?php
if (!defined('IN_CRONLITE')) die();

if($islogin2 == 1){} else {
    exit("<script language='javascript'>window.location.href='./user/login.php';</script>");
}

$get = $_GET;

if(isset($get['id']) && !empty($get['id'])) {
    $id = $get['id'];
    $video = $DB->getRow("SELECT * FROM `pre_video` WHERE id='$id' LIMIT 1");
    $pid = $video['pid'];
    $count = $DB->getColumn("SELECT count(*) from pre_video WHERE pid='$pid'");
    $next_num = $video['num'] + 1;
    $prev_num = $video['num'] - 1;

    // 获取上一集的集数
    $next_video = $DB->getRow("SELECT * FROM `pre_video` WHERE pid='$pid' and num='$next_num' LIMIT 1");
    $prev_video = $DB->getRow("SELECT * FROM `pre_video` WHERE pid='$pid' and num='$prev_num' LIMIT 1");

    // 查询上一集
    $next_id = 0;
    $prev_id = 0;

    if (!empty($next_video)) {
        $next_id = $next_video['id'];
        $next_play = 1;
    } else {
        $next_play = 0;
    }

    if (!empty($prev_video)) {
        $prev_id = $prev_video['id']; // 获取上一集的 ID
    }

    $videolist = $DB->getRow("SELECT * FROM `pre_videolist` WHERE id=" . $video['pid'] . " LIMIT 1");
    $price_obj = new \lib\Price($userrow['zid'], $userrow);
    $price_obj->setbfVideoInfo($id, $videolist);
    $allprice = $price_obj->getbfVideoPrice($id);

    if ($video['price'] == 0) {
        $play = 1;
    } else {
        $pay_play_log = $DB->getRow("SELECT * FROM `shua_orders` WHERE vid=" . $pid . " AND input2='购买短剧播放' AND (input3 = 'all' OR input3 = " . $id . ") AND userid=" . $userrow['zid'] . " AND status=1 LIMIT 1");

        if (empty($pay_play_log)) {
            $play = 0;
        } else {
            $play = 1;
        }
    }

    $price_obj->setdbVideoInfo($id, $video);
    $price = $price_obj->getdbVideoPrice($id);
} else {
    echo '参数错误！';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $videolist['name'];?></title>
    <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        /* 标准的CSS样式保持不变 */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('<?php echo $videolist['img'];?>') no-repeat center center fixed; /* 使用视频封面作为背景 */
            background-size: cover; /* 背景图像覆盖整个页面 */
            background-color: #f0f0f0;
        }

        .container {
            width: 100%;
            max-width: 800px;  /* 修改为更大的容器 */

            background: rgba(0, 0, 0, 0.7);  /* 半透明黑色背景 */
            padding: 20px; /* 增加内边距 */
            border-radius: 10px; /* 圆角 */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* 阴影效果 */
            text-align: center; /* 内容居中 */
        }












        .video-container {
            position: relative;
        }
        
        video {
            width: 100%;
            height: auto;
            border-radius: 10px; /* 视频播放器圆角效果 */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* 阴影效果 */
        }

        .episode-info {
            margin-top: 15px;
            margin-bottom: 20px;





        }

        .episode-info span {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .episode-buttons {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .episode-button {
            color: #007bff; /* 链接颜色 */
            text-decoration: none; /* 去掉下划线 */
            padding: 5px 10px;


            border-radius: 5px;



            transition: background-color 0.3s; /* 悬停效果 */
        }

        .episode-info a:hover {

            background-color: rgba(255, 255, 255, 0.2); /* 悬停背景颜色 */
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);

        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
        }

        .modal-header {

            font-size: 24px;
            text-align: center;
        }

        .modal-body {
            text-align: center;
            padding: 10px;
        }

        .buy-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .buy-btn:hover {
            background-color: #0056b3;
        }

        .buy-btn:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="JavaScript:history.back();" class="back-to-theater">返回</a>
            <a href="/?mod=duanju">首页</a>
        </div>
        <div class="video-container">
            <video id="videoPlayer" controls>
                <source src="<?php echo $video['url'];?>" type="video/mp4">
                您的浏览器不支持HTML5视频标签。
            </video>
        </div>
        <div class="episode-info">
            <span><?php echo htmlspecialchars($videolist['name']); ?>: 第<?php echo htmlspecialchars($video['num']); ?>集 / 共<?php echo htmlspecialchars($count); ?>集</span>
            <div class="episode-buttons">
                <?php if (isset($prev_video['id'])) { ?>
                    <button class="episode-button" onclick="navigateToEpisode(<?php echo $prev_video['id']; ?>)">上一集</button>
                <?php } ?>
                <?php if (isset($next_video['id'])) { ?>
                    <button class="episode-button" onclick="navigateToEpisode(<?php echo $next_video['id']; ?>)">下一集</button>
                <?php } ?>
                <button class="episode-button" id="toggleEpisodes">选集</button>
                <div class="action-buttons">  
</div>
            </div>
        </div>
        <div class="episodes-list" id="episodesList" style="display: none;">
            <?php
            $videos = $DB->query("SELECT * FROM `pre_video` WHERE pid='$pid' ORDER BY num ASC");
            while ($ep = $videos->fetch(PDO::FETCH_ASSOC)) {
                echo '<button class="episode-button" onclick="navigateToEpisode('.$ep['id'].')">第'.$ep['num'].'集</button> ';
            }
            ?>
        </div>
    </div>

    <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
    <script src="assets/store/js/main.js?ver=<?php echo VERSION ?>"></script>
    <script>
        function navigateToEpisode(vid) {
            window.location.href = `/?mod=play&id=${vid}`;
        }

        document.getElementById("toggleEpisodes").onclick = function() {
            const episodesList = document.getElementById("episodesList");
            episodesList.style.display = episodesList.style.display === "block" ? "none" : "block";
        };

        window.onload = function() {
            var video = document.getElementById('videoPlayer');
            var play = "<?php echo $play;?>";

            if(play == 0) {
                video.removeAttribute('controls');
                alert('该视频为收费视频,您没有播放权限,请付费购买');
                var name = "<?php echo $videolist['name']?>";
                var num = "<?php echo $video['num']?>";
                var type = 'play';
                var goods_name = '购买' + name + '第' + num + '集播放权限';
                var price = "<?php echo $price ?>";

                var modalHTML = `
                    <div id="orderModal" class="modal">
                        <div class="modal-content">
                            <h3 class="modal-header">下单信息</h3>
                            <div class="modal-body">
                                <p>商品信息: <span>` + goods_name + `</span></p>
                                <p>商品价格: ￥<span style="color:red" id="productPrice">` + price + `</span></p>
                                <button class="buy-btn" onclick="confirmPurchase('` + type + `')">立即购买</button>
                                <button class="buy-btn" onclick="closeModal()">关闭窗口</button> <!-- 新增关闭按钮 -->
                            </div>
                        </div>
                    </div>`;
                
                document.body.innerHTML += modalHTML;
                var modal = document.getElementById("orderModal");
                modal.style.display = "block";
            } else {
                video.play();
            }

            video.addEventListener('ended', function() {
                var next_play = "<?php echo $next_play;?>";
                if(next_play == 0) {
                    alert('该视频已播放完毕!');
                    window.location = "/?mod=duanju";
                } else {
                    var next_id = "<?php echo $next_id;?>";
                    window.location = "/?mod=play&id=" + next_id;
                }
            });
        };

        function confirmPurchase(type) {
            var id = <?php echo $id;?>;
            var vid = <?php echo $videolist['id'];?>;


            $.ajax({
                type: "POST",
                url: "ajax.php?act=payvideo",
                data: {
                    vid: vid,
                    id: id,
                    goods_type: type
                },
                dataType: 'json',
                success: function(data) {
                    if(data.code == 0) {
                        var paymsg = '';
                        if(data.pay_alipay > 0) {
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'alipay\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/alipay.png" style="width:30px" class="logo">支付宝购买，请在个人中心充值余额</button>';
                        }
                        if(data.pay_qqpay > 0) {
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'qqpay\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/qqpay.png" style="width:30px" class="logo">QQ钱包</button>';
                        }
                        if(data.pay_wxpay > 0) {
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'wxpay\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/wxpay.png" style="width:30px" class="logo">微信支付（可直接购买）</button>';
                        }
                        if (data.pay_rmb > 0) {
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'rmb\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/rmb.png" style="width:30px" class="logo">余额支付（可直接使用）<span class="text-muted">（剩' + data.user_rmb + '元）</span></button>';
                        }
                        if(data.paymsg != null) paymsg += data.paymsg;

                        layer.alert('<center><h2>￥ '+ data.need +'</h2><hr>' + paymsg + '<hr><a class="btn btn-default btn-block" onclick="cancel(\'' + data.trade_no + '\')">取消订单</a></center>', {btn: [], title: '提交订单成功', closeBtn: false});
                    } else if(data.code == 1) {
                        $('#alert_frame').hide();
                        if($('#inputname').html() == '你的邮箱') {
                            $.cookie('email', inputvalue);
                        }
                        alert('领取成功！');
                        window.location.href = '?buyok=1';
                    } else if(data.code == 2) {
                        if(data.type == 1) {
                            layer.open({
                                type: 1,
                                title: '完成验证',
                                skin: 'layui-layer-rim',
                                area: ['320px', '100px'],
                                content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div><div id="captcha_wait"><div class="loading"><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div></div></div></div>',
                                success: function() {
                                    $.getScript("//static.geetest.com/static/tools/gt.js", function() {
                                        $.ajax({
                                            url: "ajax.php?act=captcha&t=" + (new Date()).getTime(),
                                            type: "get",
                                            dataType: "json",
                                            success: function (data) {
                                                $('#captcha_text').hide();
                                                $('#captcha_wait').show();
                                                initGeetest({
                                                    gt: data.gt,
                                                    challenge: data.challenge,
                                                    new_captcha: data.new_captcha,
                                                    product: "popup",
                                                    width: "100%",
                                                    offline: !data.success
                                                }, handlerEmbed);
                                            }
                                        });
                                    });
                                }
                            });
                        } else if(data.type == 2) {
                            layer.open({
                                type: 1,
                                title: '完成验证',
                                skin: 'layui-layer-rim',
                                area: ['320px', '260px'],
                                content: '<div id="captcha" style="margin: auto;"><div id="captcha_text">正在加载验证码</div></div>',
                                success: function() {
                                    $.getScript("//cdn.dingxiang-inc.com/ctu-group/captcha-ui/index.js", function() {
                                        var myCaptcha = _dx.Captcha(document.getElementById('captcha'), {
                                            appId: data.appid,
                                            type: 'basic',
                                            style: 'embed',
                                            success: handlerEmbed2
                                        });
                                        myCaptcha.on('ready', function() {
                                            $('#captcha_text').hide();
                                        });
                                    });
                                }
                            });
                        } else if(data.type == 3) {
                            layer.open({
                                type: 1,
                                title: '完成验证',
                                skin: 'layui-layer-rim',
                                area: ['320px', '231px'],
                                content: '<div id="captcha"><div id="captcha_text">正在加载验证码</div></div>',
                                success: function() {
                                    $.getScript("//v.vaptcha.com/v3.js", function() {
                                        vaptcha({
                                            vid: data.appid,
                                            type: 'embed',
                                            container: '#captcha',
                                            offline_server: 'https://management.vaptcha.com/api/v3/demo/offline'
                                        }).then(handlerEmbed3);
                                    });
                                }
                            });
                        }
                    } else if(data.code == 3) {
                        layer.alert(data.msg, {closeBtn: false}, function() {
                            window.location.reload();
                        });
                    } else if(data.code == 4) {
                        var confirmobj = layer.confirm('请登录后再购买，是否现在登录？', {
                            btn: ['登录','注册','取消']
                        }, function() {
                            window.location.href='./user/login.php';
                        }, function() {
                            window.location.href='./user/reg.php';
                        }, function() {
                            layer.close(confirmobj);
                        });
                    } else {
                        layer.alert(data.msg);
                    }
                }
            });
        }

        function closeModal() {



















            var modal = document.getElementById("orderModal");
            if (modal) {
                modal.style.display = "none"; // 关闭模态框
                modal.remove(); // 从DOM中移除模态框
            }
        }





        document.addEventListener("DOMContentLoaded", function() {
            var modals = document.getElementsByClassName("modal");
            for (var i = 0; i < modals.length; i++) {
                modals[i].style.display = "none"; // 默认隐藏所有模态对话框

                // 点击模态对话框外部时关闭它
                modals[i].addEventListener('click', function(e) {
                    if (e.target == this) {
                        this.style.display = "none";
                    }
                });
                




                // 防止点击模态对话框内部元素时关闭它
                var spans = modals[i].getElementsByClassName("modal-content");
                for (var j = 0; j < spans.length; j++) {
                    spans[j].addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                }





            }
        });
    </script>
</body>
</html>