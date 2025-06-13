<?php
// 错误处理
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<pre>PHP ERROR: $errstr in $errfile on line $errline</pre>";
    exit;
});

set_exception_handler(function($e) {
    echo "<pre>EXCEPTION: ".$e->getMessage()."\n".$e->getTraceAsString()."</pre>";
    exit;
});

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 引入必要的文件
require_once dirname(__DIR__).'/config.php';
require_once dirname(__DIR__).'/includes/db.class.php';
require_once 'resourcemanager.php';

// 测试辅助函数
function assert_equal($expected, $actual, $message = '') {
    if($expected !== $actual) {
        throw new Exception("Assertion failed: $message\nExpected: " . print_r($expected, true) . "\nActual: " . print_r($actual, true));
    }
    echo "✓ $message\n";
}

function assert_true($condition, $message = '') {
    if(!$condition) {
        throw new Exception("Assertion failed: $message");
    }
    echo "✓ $message\n";
}

// 初始化数据库连接
$db = DB::getInstance($dbconfig);

// 创建测试数据
$test_user = [
    'zid' => 999999,
    'user' => 'test_user',
    'rmb' => 1000.00,
    'finance_vip' => 0,
    'finance_vip_expire' => null
];

$test_resource = [
    'id' => 999999,
    'zid' => 1,
    'resource_id' => 1,
    'price' => 100.00,
    'status' => 1
];

try {
    echo "开始测试...\n\n";
    
    // 清理可能存在的测试数据
    $db->query("DELETE FROM shua_site WHERE zid=?", [$test_user['zid']]);
    $db->query("DELETE FROM shua_finance_order WHERE id=?", [$test_resource['id']]);
    $db->query("DELETE FROM shua_pay WHERE zid=?", [$test_user['zid']]);
    $db->query("DELETE FROM shua_points WHERE zid=?", [$test_user['zid']]);
    
    // 插入测试数据
    $db->query("INSERT INTO shua_site (zid,user,rmb,finance_vip,finance_vip_expire) VALUES (?,?,?,?,?)", 
        [$test_user['zid'], $test_user['user'], $test_user['rmb'], $test_user['finance_vip'], $test_user['finance_vip_expire']]);
    
    $db->query("INSERT INTO shua_finance_order (id,zid,resource_id,price,status) VALUES (?,?,?,?,?)",
        [$test_resource['id'], $test_resource['zid'], $test_resource['resource_id'], $test_resource['price'], $test_resource['status']]);
    
    // 插入资源内容表测试数据
    $db->query("DELETE FROM shua_finance_content WHERE id=?", [1]);
    $db->query("INSERT INTO shua_finance_content (id, name) VALUES (?, ?)", [1, 'Test Resource']);
    
    // 设置测试用户会话
    $_SESSION['zid'] = $test_user['zid'];
    
    // 初始化资源管理器
    $manager = new ResourceManager();
    
    echo "测试1: 用户信息获取\n";
    $user_info = $manager->get_user_info();
    assert_equal($test_user['zid'], $user_info['zid'], '用户ID正确');
    assert_equal($test_user['user'], $user_info['username'], '用户名正确');
    assert_equal((float)$test_user['rmb'], (float)$user_info['rmb'], '余额正确');
    assert_equal(false, $user_info['is_vip'], 'VIP状态正确');
    
    echo "\n测试2: 余额查询\n";
    $row = $db->fetch("SELECT rmb FROM shua_site WHERE zid=?", [$test_user['zid']]);
    $db_balance = $row ? (float)$row['rmb'] : null;
    assert_equal(1000.00, $db_balance, '数据库余额正确');
    assert_equal($db_balance, (float)$user_info['rmb'], '返回余额与数据库一致');
    
    echo "\n测试3: 余额更新\n";
    $result = $manager->buy_resource($test_resource['id'], 1);
    echo "购买资源返回: "; print_r($result);
    assert_equal(0, $result['code'], '购买成功');
    assert_equal('购买成功', $result['msg'], '返回消息正确');
    
    // 验证余额更新
    $row = $db->fetch("SELECT rmb FROM shua_site WHERE zid=?", [$test_user['zid']]);
    $new_balance = $row ? (float)$row['rmb'] : null;
    assert_equal(900.00, $new_balance, '余额已正确扣除');
    
    // 验证订单记录
    $order = $db->fetch(
        "SELECT * FROM shua_pay WHERE zid=? AND tid=? ORDER BY trade_no DESC LIMIT 1",
        [$test_user['zid'], $test_resource['id']]
    );
    assert_true($order !== null, '订单记录已创建');
    assert_equal(1, $order['status'], '订单状态正确');
    assert_equal(100.00, floatval($order['money']), '订单金额正确');
    
    // 调试输出 orderid 和最新一条 points 记录
    echo "orderid: " . $order['trade_no'] . PHP_EOL;
    $last_point = $db->fetch("SELECT * FROM shua_points ORDER BY id DESC LIMIT 1");
    print_r($last_point);
    // 验证交易记录
    $point = $db->fetch(
        "SELECT * FROM shua_points WHERE zid=? AND orderid=?",
        [$test_user['zid'], $order['trade_no']]
    );
    assert_true($point !== false && $point !== null, '交易记录已创建');
    if ($point !== false && $point !== null) {
        assert_equal('消费', $point['action'], '交易类型正确');
        assert_equal(100.00, floatval($point['point']), '交易金额正确');
    }
    
    echo "\n测试4: 异常情况处理\n";
    
    // 测试未登录
    unset($_SESSION['zid']);
    $manager = new ResourceManager(); // 重新实例化，刷新 user
    try {
        $manager->get_user_info();
        throw new Exception('未登录测试失败：应该抛出异常');
    } catch(Exception $e) {
        assert_equal('请先登录', $e->getMessage(), '未登录异常处理正确');
    }
    
    // 恢复登录状态
    $_SESSION['zid'] = $test_user['zid'];
    
    // 测试余额不足
    $db->query("UPDATE shua_site SET rmb=50.00 WHERE zid=?", [$test_user['zid']]);
    $result = $manager->buy_resource($test_resource['id'], 1);
    assert_equal(-1, $result['code'], '余额不足返回错误码');
    assert_equal('余额不足', $result['msg'], '余额不足错误消息正确');
    
    // 测试资源不存在
    $result = $manager->buy_resource(9999999, 1);
    assert_equal(-1, $result['code'], '资源不存在返回错误码');
    assert_equal('资源不存在或已下架', $result['msg'], '资源不存在错误消息正确');
    
    echo "\n所有测试通过！\n";
    
} catch(Exception $e) {
    echo "\n测试失败: " . $e->getMessage() . "\n";
} finally {
    // 清理测试数据
    $db->query("DELETE FROM shua_site WHERE zid=?", [$test_user['zid']]);
    $db->query("DELETE FROM shua_finance_order WHERE id=?", [$test_resource['id']]);
    $db->query("DELETE FROM shua_pay WHERE zid=?", [$test_user['zid']]);
    $db->query("DELETE FROM shua_points WHERE zid=?", [$test_user['zid']]);
    $db->query("DELETE FROM shua_finance_content WHERE id=?", [1]);
    unset($_SESSION['zid']);
} 