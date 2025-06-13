<?php
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

// 引入配置和数据库
require_once dirname(__DIR__).'/config.php';
require_once dirname(__DIR__).'/includes/db.class.php';

session_start();

// 辅助日志函数
function writeLog($type, $action, $data = null) {
    $logFile = __DIR__.'/../logs/resource_manager.log';
    $logData = date('Y-m-d H:i:s') . " [$type] $action: " . 
        (is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data) . "\n";
    file_put_contents($logFile, $logData, FILE_APPEND);
}

class ResourceManager {
    private $DB;
    private $user;
    private $isVip;
    
    public function __construct() {
        $this->DB = DB::getInstance($GLOBALS['dbconfig']);
        $this->initUser();
    }
    
    // 用户验证和初始化
    private function initUser() {
        $this->user = null;
        $this->isVip = false;
        
        if(isset($_SESSION['zid'])) {
            $zid = intval($_SESSION['zid']);
            $this->user = $this->DB->fetch("SELECT * FROM shua_site WHERE zid=?", [$zid]);
            
            if($this->user) {
                // 检查VIP状态
                $this->isVip = ($this->user['finance_vip_level'] == 2 || $this->user['finance_vip_level'] == 9);
            } else {
                unset($_SESSION['zid']);
            }
        }
    }
    
    // 检查用户登录状态
    public function checkLogin() {
        if(!$this->user) {
            throw new Exception('请先登录');
        }
        return true;
    }
    
    // 检查VIP状态
    public function checkVip() {
        if(!$this->isVip) {
            throw new Exception('需要VIP权限');
        }
        return true;
    }
    
    // 获取用户信息
    public function getUserInfo() {
        $this->checkLogin();
        return [
            'zid' => $this->user['zid'],
            'username' => $this->user['user'],
            'rmb' => $this->user['rmb'],
            'is_vip' => $this->isVip,
            'vip_expire' => $this->user['finance_vip_expire'] ?? null
        ];
    }
    
    // 购买资源
    public function buyResource($resourceId, $quantity = 1) {
        $this->checkLogin();
        
        try {
            $this->DB->beginTransaction();
            
            // 获取资源信息
            $resource = $this->DB->fetch("SELECT * FROM shua_resources WHERE id=? AND status=1", [$resourceId]);
            if(!$resource) {
                throw new Exception('资源不存在或已下架');
            }
            
            // 计算总价
            $totalPrice = $resource['price'] * $quantity;
            
            // 检查余额
            if($this->user['rmb'] < $totalPrice) {
                throw new Exception('余额不足');
            }
            
            // 创建订单
            $orderNo = date('YmdHis').rand(111,999);
            $orderData = [
                'trade_no' => $orderNo,
                'type' => 'resource',
                'tid' => $resourceId,
                'zid' => $this->user['zid'],
                'num' => $quantity,
                'name' => $resource['name'],
                'money' => $totalPrice,
                'status' => 0,
                'addtime' => date('Y-m-d H:i:s')
            ];
            
            $this->DB->insert('shua_pay', $orderData);
            
            // 扣除余额
            $this->DB->exec("UPDATE shua_site SET rmb=rmb-? WHERE zid=?", 
                [$totalPrice, $this->user['zid']]);
            
            // 更新订单状态
            $this->DB->exec("UPDATE shua_pay SET status=1, endtime=NOW() WHERE trade_no=?", 
                [$orderNo]);
            
            // 记录交易
            $this->DB->insert('shua_points', [
                'zid' => $this->user['zid'],
                'action' => '消费',
                'point' => $totalPrice,
                'bz' => "购买资源: {$resource['name']}",
                'orderid' => $orderNo,
                'addtime' => date('Y-m-d H:i:s'),
                'status' => 1
            ]);
            
            $this->DB->commit();
            writeLog('info', 'buyResource', [
                'user_id' => $this->user['zid'],
                'resource_id' => $resourceId,
                'quantity' => $quantity,
                'order_no' => $orderNo
            ]);
            
            return [
                'code' => 0,
                'msg' => '购买成功',
                'order_no' => $orderNo
            ];
            
        } catch(Exception $e) {
            $this->DB->rollBack();
            writeLog('error', 'buyResource', $e->getMessage());
            return [
                'code' => -1,
                'msg' => $e->getMessage()
            ];
        }
    }
    
    // 获取资源列表
    public function getResourceList($page = 1, $limit = 10, $category = null) {
        $offset = ($page - 1) * $limit;
        $where = "status=1";
        $params = [];
        
        if($category) {
            $where .= " AND category_id=?";
            $params[] = $category;
        }
        
        $resources = $this->DB->fetchAll(
            "SELECT * FROM shua_resources WHERE {$where} ORDER BY id DESC LIMIT ?,?", 
            array_merge($params, [$offset, $limit])
        );
        
        $total = $this->DB->getColumn(
            "SELECT COUNT(*) FROM shua_resources WHERE {$where}", 
            $params
        );
        
        return [
            'list' => $resources,
            'total' => $total,
            'page' => $page,
            'limit' => $limit
        ];
    }
    
    // 获取用户购买记录
    public function getUserPurchases($page = 1, $limit = 10) {
        $this->checkLogin();
        
        $offset = ($page - 1) * $limit;
        $purchases = $this->DB->fetchAll(
            "SELECT p.*, r.name as resource_name 
            FROM shua_pay p 
            LEFT JOIN shua_resources r ON p.tid=r.id 
            WHERE p.zid=? AND p.type='resource' AND p.status=1 
            ORDER BY p.addtime DESC LIMIT ?,?", 
            [$this->user['zid'], $offset, $limit]
        );
        
        $total = $this->DB->getColumn(
            "SELECT COUNT(*) FROM shua_pay WHERE zid=? AND type='resource' AND status=1", 
            [$this->user['zid']]
        );
        
        return [
            'list' => $purchases,
            'total' => $total,
            'page' => $page,
            'limit' => $limit
        ];
    }
}

// 处理请求
$manager = new ResourceManager();

// 根据action参数处理不同的请求
$action = $_GET['action'] ?? '';

try {
    switch($action) {
        case 'user_info':
            $result = $manager->getUserInfo();
            break;
            
        case 'buy':
            $resourceId = intval($_POST['resource_id'] ?? 0);
            $quantity = intval($_POST['quantity'] ?? 1);
            $result = $manager->buyResource($resourceId, $quantity);
            break;
            
        case 'list':
            $page = intval($_GET['page'] ?? 1);
            $limit = intval($_GET['limit'] ?? 10);
            $category = $_GET['category'] ?? null;
            $result = $manager->getResourceList($page, $limit, $category);
            break;
            
        case 'purchases':
            $page = intval($_GET['page'] ?? 1);
            $limit = intval($_GET['limit'] ?? 10);
            $result = $manager->getUserPurchases($page, $limit);
            break;
            
        default:
            $result = ['code' => -1, 'msg' => '未知操作'];
    }
} catch(Exception $e) {
    $result = ['code' => -1, 'msg' => $e->getMessage()];
}

// 输出JSON响应
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE); 