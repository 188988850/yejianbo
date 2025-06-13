<?php
include("../includes/common.php");
$title='金融会员';
$user = $_SESSION;
if(!$user){
    header('location:login.php');
    exit();
}

// 获取用户信息和金融会员状态
$userrow = $DB->getRow("SELECT * FROM shua_site WHERE zid='{$user['zid']}' LIMIT 1");
$is_finance_vip = ($userrow['finance_vip_level'] > 0 && ($userrow['finance_vip_expire'] >= date('Y-m-d') || $userrow['finance_vip_expire_type'] == 'forever'));

// 处理会员开通请求
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'buy_vip'){
    $vip_type = $_POST['vip_type'];
    $price = 0;
    $duration_type = '';
    $expire_date = null;
    
    switch($vip_type){
        case 'month':
            $price = 29.90;
            $duration_type = 'month';
            $expire_date = date('Y-m-d', strtotime('+1 month'));
            break;
        case 'season':
            $price = 79.90;
            $duration_type = 'season';
            $expire_date = date('Y-m-d', strtotime('+3 months'));
            break;
        case 'year':
            $price = 299.90;
            $duration_type = 'year';
            $expire_date = date('Y-m-d', strtotime('+1 year'));
            break;
        case 'forever':
            $price = 999.90;
            $duration_type = 'forever';
            $expire_date = null;
            break;
        default:
            exit(json_encode(['code' => -1, 'msg' => '无效的会员类型']));
    }
    
    // 创建订单
    $trade_no = date("YmdHis").rand(111,999);
    $name = "开通金融会员 - " . ($vip_type == 'forever' ? '永久' : ($vip_type == 'month' ? '月卡' : ($vip_type == 'season' ? '季卡' : '年卡')));
    
    $sql = "INSERT INTO `shua_pay` (`trade_no`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`status`) VALUES (:trade_no, :tid, :zid, :input, :num, :name, :money, :ip, :userid, NOW(), 0)";
    $data = [
        ':trade_no' => $trade_no,
        ':tid' => -7, // 金融会员专用tid
        ':zid' => $userrow['zid'],
        ':input' => $vip_type,
        ':num' => 1,
        ':name' => $name,
        ':money' => $price,
        ':ip' => $_SERVER['REMOTE_ADDR'],
        ':userid' => $user['zid']
    ];
    
    if($DB->exec($sql, $data)){
        // 自动使用余额支付
        if($userrow['rmb'] >= $price){
            if($DB->exec("UPDATE `shua_site` SET `rmb`=`rmb`-'{$price}' WHERE `zid`='{$userrow['zid']}'") && 
               $DB->exec("UPDATE `shua_pay` SET `type`='rmb',`status`='1',`endtime`=NOW() WHERE `trade_no`='{$trade_no}'")){
                // 更新用户金融会员状态
                $update_sql = "UPDATE `shua_site` SET `finance_vip_level`=1, `finance_vip_expire_type`=:duration_type";
                $update_data = [':duration_type' => $duration_type];
                
                if($duration_type != 'forever'){
                    $update_sql .= ", `finance_vip_expire`=:expire_date";
                    $update_data[':expire_date'] = $expire_date;
                }
                
                $update_sql .= " WHERE `zid`=:zid";
                $update_data[':zid'] = $userrow['zid'];
                
                $DB->exec($update_sql, $update_data);
                
                // 记录消费
                addPointRecord($userrow['zid'], $price, '消费', $name, $trade_no);
                
                exit(json_encode(['code' => 1, 'msg' => '会员开通成功！']));
            }
        }
        
        exit(json_encode(['code' => 0, 'msg' => '订单创建成功', 'trade_no' => $trade_no, 'need' => $price]));
    } else {
        exit(json_encode(['code' => -1, 'msg' => '订单创建失败']));
    }
}

include SYSTEM_ROOT."head.php";
?>

<div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
    <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">金融会员中心</h3></div>
        <div class="panel-body">
            <?php if($is_finance_vip): ?>
                <div class="alert alert-success">
                    <h4><i class="fa fa-check-circle"></i> 您已是金融会员</h4>
                    <p>等级：<?php echo $userrow['finance_vip_level'] == 9 ? '永久VIP' : '普通VIP'; ?></p>
                    <?php if($userrow['finance_vip_expire_type'] != 'forever'): ?>
                        <p>到期时间：<?php echo $userrow['finance_vip_expire']; ?></p>
                    <?php else: ?>
                        <p>到期时间：永久有效</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <h4><i class="fa fa-info-circle"></i> 您还不是金融会员</h4>
                    <p>开通金融会员，享受更多特权！</p>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">会员特权</div>
                        <div class="panel-body">
                            <ul>
                                <li><i class="fa fa-check text-success"></i> 免费查看所有金融资讯VIP内容</li>
                                <li><i class="fa fa-check text-success"></i> 资源市场商品享受8折优惠</li>
                                <li><i class="fa fa-check text-success"></i> 专属客服支持</li>
                                <li><i class="fa fa-check text-success"></i> 优先获取最新投资机会</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <?php if(!$is_finance_vip): ?>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">开通会员</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>选择套餐：</label>
                                <select class="form-control" id="vip_type">
                                    <option value="month">月卡 - ¥29.90</option>
                                    <option value="season">季卡 - ¥79.90 (省20元)</option>
                                    <option value="year">年卡 - ¥299.90 (省58元)</option>
                                    <option value="forever">永久 - ¥999.90</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary btn-block" onclick="buyVip()">立即开通</button>
                            <div class="text-muted text-center mt-2">
                                <small>当前余额：¥<?php echo $userrow['rmb']; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function buyVip() {
    var vip_type = $('#vip_type').val();
    
    $.post('', {
        action: 'buy_vip',
        vip_type: vip_type
    }, function(res) {
        if(typeof res === 'string') try{res=JSON.parse(res);}catch(e){}
        
        if(res.code === 1) {
            alert(res.msg);
            location.reload();
        } else if(res.code === 0 && res.trade_no) {
            // 需要支付
            alert('余额不足，请充值后重试或选择其他支付方式');
        } else {
            alert(res.msg || '开通失败');
        }
    }, 'json');
}
</script>

<?php include SYSTEM_ROOT."foot.php";?>