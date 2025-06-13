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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .controls {
            margin-top: 10px;
        }
        .episode-info {
            margin-bottom: 20px;
        }
        .video-container {
            position: relative;
        }
        video {
            width: 100%;
            height: auto;
        }
        .progress {
            position: absolute;
            height: 100%;
            background: #ff0000;
            width: 0;
            cursor: pointer;
            z-index: 1;
        }
        .back-to-theater {
            color: #000;
            text-decoration: none;
            font-weight: bold;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 700px;
        }
        .modal-header {
            text-align: center;
            font-size: 24px;
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
            <span><?php echo $videolist['name'];?>: 第<?php echo $video['num']?>集 / 共<?php echo $count;?>集</span>
            <?php if(isset($prev_video['id'])) { ?>
                <a href="/?mod=play&id=<?php echo $prev_video['id'];?>">上一集</a>
            <?php } ?>
            <?php if(isset($next_video['id'])) { ?>
                <a href="/?mod=play&id=<?php echo $next_video['id'];?>">下一集</a>
            <?php } ?>
        </div>
    </div>

    <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
    <script src="assets/store/js/main.js?ver=<?php echo VERSION ?>"></script>
    <script>
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
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'alipay\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/alipay.png" style="width:30px" class="logo">支付宝</button>';
                        }
                        if(data.pay_qqpay > 0) {
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'qqpay\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/qqpay.png" style="width:30px" class="logo">QQ钱包</button>';
                        }
                        if(data.pay_wxpay > 0) {
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'wxpay\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/wxpay.png" style="width:30px" class="logo">微信支付</button>';
                        }
                        if (data.pay_rmb > 0) {
                            paymsg += '<button class="btn btn-default btn-block" onclick="dopay(\'rmb\',\'' + data.trade_no + '\')" style="margin-top:10px;"><img src="assets/img/rmb.png" style="width:30px" class="logo">余额支付<span class="text-muted">（剩' + data.user_rmb + '元）</span></button>';
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

            // 关闭模态对话框
            var modal = document.getElementById("orderModal");
            modal.style.display = "none";
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
                var spans = this.getElementsByClassName("modal-content");
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