<?php
use PHPUnit\Framework\TestCase;

class ResourceManagerTest extends TestCase
{
    private $db;
    private $manager;
    private $test_user;
    private $test_resource;
    
    protected function setUp(): void
    {
        // 初始化数据库连接
        require_once dirname(__DIR__).'/config.php';
        require_once dirname(__DIR__).'/includes/db.class.php';
        
        $this->db = DB::getInstance($GLOBALS['dbconfig']);
        
        // 创建测试用户
        $this->test_user = [
            'zid' => 999999,
            'user' => 'test_user',
            'rmb' => 1000.00,
            'finance_vip_level' => 0,
            'finance_vip_expire' => null
        ];
        
        $this->db->insert('shua_site', $this->test_user);
        
        // 创建测试资源
        $this->test_resource = [
            'id' => 999999,
            'name' => 'Test Resource',
            'description' => 'Test Description',
            'price' => 100.00,
            'status' => 1,
            'is_vip' => 0
        ];
        
        $this->db->insert('shua_resources', $this->test_resource);
        
        // 初始化资源管理器
        $_SESSION['zid'] = $this->test_user['zid'];
        $this->manager = new ResourceManager();
    }
    
    protected function tearDown(): void
    {
        // 清理测试数据
        $this->db->exec("DELETE FROM shua_site WHERE zid=?", [$this->test_user['zid']]);
        $this->db->exec("DELETE FROM shua_resources WHERE id=?", [$this->test_resource['id']]);
        $this->db->exec("DELETE FROM shua_pay WHERE zid=?", [$this->test_user['zid']]);
        $this->db->exec("DELETE FROM shua_points WHERE zid=?", [$this->test_user['zid']]);
        
        unset($_SESSION['zid']);
    }
    
    /**
     * 测试用户信息获取
     */
    public function testGetUserInfo()
    {
        $user_info = $this->manager->get_user_info();
        
        $this->assertEquals($this->test_user['zid'], $user_info['zid']);
        $this->assertEquals($this->test_user['user'], $user_info['username']);
        $this->assertEquals($this->test_user['rmb'], $user_info['rmb']);
        $this->assertEquals(false, $user_info['is_vip']);
    }
    
    /**
     * 测试余额查询
     */
    public function testBalanceQuery()
    {
        $user_info = $this->manager->get_user_info();
        $this->assertEquals(1000.00, $user_info['rmb']);
        
        // 验证数据库中的余额
        $db_balance = $this->db->getColumn(
            "SELECT rmb FROM shua_site WHERE zid=?", 
            [$this->test_user['zid']]
        );
        $this->assertEquals(1000.00, $db_balance);
    }
    
    /**
     * 测试余额更新
     */
    public function testBalanceUpdate()
    {
        // 购买资源
        $result = $this->manager->buy_resource($this->test_resource['id'], 1);
        
        $this->assertEquals(0, $result['code']);
        $this->assertEquals('购买成功', $result['msg']);
        
        // 验证余额已扣除
        $new_balance = $this->db->getColumn(
            "SELECT rmb FROM shua_site WHERE zid=?", 
            [$this->test_user['zid']]
        );
        $this->assertEquals(900.00, $new_balance);
        
        // 验证订单记录
        $order = $this->db->fetch(
            "SELECT * FROM shua_pay WHERE zid=? AND tid=? ORDER BY id DESC LIMIT 1",
            [$this->test_user['zid'], $this->test_resource['id']]
        );
        $this->assertNotNull($order);
        $this->assertEquals(1, $order['status']);
        $this->assertEquals(100.00, $order['money']);
        
        // 验证交易记录
        $point = $this->db->fetch(
            "SELECT * FROM shua_points WHERE zid=? AND orderid=?",
            [$this->test_user['zid'], $order['trade_no']]
        );
        $this->assertNotNull($point);
        $this->assertEquals('消费', $point['action']);
        $this->assertEquals(100.00, $point['point']);
    }
    
    /**
     * 测试异常情况
     */
    public function testExceptionHandling()
    {
        // 测试未登录
        unset($_SESSION['zid']);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('请先登录');
        $this->manager->get_user_info();
        
        // 恢复登录状态
        $_SESSION['zid'] = $this->test_user['zid'];
        
        // 测试余额不足
        $this->test_user['rmb'] = 50.00;
        $this->db->exec(
            "UPDATE shua_site SET rmb=? WHERE zid=?", 
            [$this->test_user['rmb'], $this->test_user['zid']]
        );
        
        $result = $this->manager->buy_resource($this->test_resource['id'], 1);
        $this->assertEquals(-1, $result['code']);
        $this->assertEquals('余额不足', $result['msg']);
        
        // 测试资源不存在
        $result = $this->manager->buy_resource(9999999, 1);
        $this->assertEquals(-1, $result['code']);
        $this->assertEquals('资源不存在或已下架', $result['msg']);
    }
    
    /**
     * 测试事务回滚
     */
    public function testTransactionRollback()
    {
        // 模拟数据库错误
        $this->db->exec("DROP TABLE IF EXISTS shua_points");
        
        $result = $this->manager->buy_resource($this->test_resource['id'], 1);
        $this->assertEquals(-1, $result['code']);
        
        // 验证余额未变化
        $balance = $this->db->getColumn(
            "SELECT rmb FROM shua_site WHERE zid=?", 
            [$this->test_user['zid']]
        );
        $this->assertEquals(1000.00, $balance);
        
        // 恢复表结构
        $this->db->exec("CREATE TABLE shua_points (
            id INT AUTO_INCREMENT PRIMARY KEY,
            zid INT NOT NULL,
            action VARCHAR(50) NOT NULL,
            point DECIMAL(10,2) NOT NULL,
            bz VARCHAR(255),
            orderid VARCHAR(50),
            addtime DATETIME,
            status TINYINT DEFAULT 1
        )");
    }
    
    /**
     * 测试VIP资源访问
     */
    public function testVipResourceAccess()
    {
        // 设置资源为VIP资源
        $this->db->exec(
            "UPDATE shua_resources SET is_vip=1 WHERE id=?", 
            [$this->test_resource['id']]
        );
        
        // 非VIP用户尝试购买
        $result = $this->manager->buy_resource($this->test_resource['id'], 1);
        $this->assertEquals(-1, $result['code']);
        $this->assertEquals('需要VIP权限', $result['msg']);
        
        // 升级为VIP用户
        $this->db->exec(
            "UPDATE shua_site SET finance_vip_level=2 WHERE zid=?", 
            [$this->test_user['zid']]
        );
        
        // VIP用户购买
        $result = $this->manager->buy_resource($this->test_resource['id'], 1);
        $this->assertEquals(0, $result['code']);
    }
} 