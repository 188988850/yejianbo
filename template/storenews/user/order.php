<?php
session_start();
require_once dirname(__DIR__).'/../../config.php';
require_once dirname(__DIR__).'/../../includes/db.class.php';
$DB = DB::getInstance($dbconfig);

if(!isset($_SESSION['zid'])){
    header('Location: /user/login.php?return_url=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
$userrow = $DB->query("SELECT * FROM shua_site WHERE zid='{$_SESSION['zid']}' LIMIT 1")->fetch();
if(!$userrow){
    header('Location: /user/login.php?return_url=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

if($islogin2==1){
    $price_obj = new \lib\Price($userrow['zid'],$userrow);
    if($userrow['status']==0){
        sysmsg('你的账号已被封禁！',true);exit;
    }elseif($userrow['power']>0 && $conf['fenzhan_expiry']>0 && $userrow['endtime']<$date){
        sysmsg('你的账号已到期，请联系管理员续费！',true);exit;
    }
}elseif($is_fenzhan == true){
    $price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
    $price_obj = new \lib\Price(1);
}

$uid = $userrow['uid'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>我的订单 - <?php echo $conf['sitename']; ?></title>
    <link rel="stylesheet" href="<?php echo $cdnpublic?>bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/style1.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/member.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/store/css/iconfont.css">
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/store/css/user1.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>template/storenews/user/yangshi/toastr.min.css">
    <link rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/layui.css">
    <script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
</head>
<body>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">我的订单</h3>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#all" data-status="all">全部订单</a></li>
                <li role="presentation"><a href="#processing" data-status="processing">处理中</a></li>
                <li role="presentation"><a href="#completed" data-status="completed">已完成</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="order-list">
                    <!-- 订单列表将通过AJAX动态加载 -->
                </div>
            </div>
            <div class="text-center">
                <ul class="pagination" id="pagination">
                    <!-- 分页将通过AJAX动态加载 -->
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
var currentPage = 1;
var currentStatus = 'all';

function loadOrders(page, status) {
    $.ajax({
        url: '/ajax.php?act=query',
        type: 'POST',
        data: {
            page: page,
            type: 0, // 0=按用户查全部订单
            qq: ''   // 可根据需要传递查询条件
        },
        dataType: 'json',
        success: function(data) {
            if(data.code == 0) {
                var html = '';
                var orderList = data.data || [];
                orderList.forEach(function(order) {
                    html += '<div class="order-item">';
                    html += '<div class="order-header">';
                    html += '<span class="order-no">订单号：' + (order.id || '') + '</span>';
                    html += '<span class="order-status">' + (order.status_text || '') + '</span>';
                    html += '</div>';
                    html += '<div class="order-main-info" style="display:flex;align-items:center;">';
                    html += '<img src="' + (order.img ? order.img : 'assets/store/picture/error_img.png') + '" alt="' + (order.name || '') + '" class="order-cover" style="width:60px;height:60px;object-fit:cover;margin-right:12px;border-radius:6px;border:2px solid #2196f3;">';
                    html += '<div class="order-title" style="font-weight:bold;font-size:1.1em;color:#2196f3;">' + (order.name !== undefined ? order.name : '<span style=\'color:#bbb\'>无标题</span>') + '</div>';
                    html += '</div>';
                    html += '<div class="order-content">';
                    html += '<p>订单金额：￥' + (order.value || '') + '</p>';
                    html += '<p>下单时间：' + (order.addtime || '') + '</p>';
                    html += '<a href="javascript:void(0)" onclick="showOrderDetail(' + order.id + ')">查看详情</a>';
                    html += '</div>';
                    html += '</div>';
                });
                $('#order-list').html(html);
                // 分页逻辑可根据 data.page, data.isnext 等字段自行适配
            } else {
                layer.msg(data.msg, {icon: 2});
            }
        }
    });
}

$(document).ready(function() {
    loadOrders(1, 'all');
    
    // 切换订单状态
    $('.nav-tabs a').click(function(e) {
        e.preventDefault();
        var status = $(this).data('status');
        currentStatus = status;
        currentPage = 1;
        loadOrders(1, status);
        
        $('.nav-tabs li').removeClass('active');
        $(this).parent().addClass('active');
    });
});
</script>

<style>
.order-item {
    border: 1px solid #ddd;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 4px;
}
.order-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.order-content p {
    margin: 5px 0;
}
.order-status {
    color: #ff6b6b;
}
</style>
</body>
</html> 