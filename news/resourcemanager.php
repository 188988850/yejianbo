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

// 引入主系统配置和认证
require_once dirname(__DIR__).'/includes/common.php';

session_start();

// 辅助日志函数
function writeLog($type, $action, $data = null) {
    $logFile = __DIR__.'/../logs/resource_manager.log';
    $logData = date('Y-m-d H:i:s') . " [$type] $action: " . 
        (is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data) . "\n";
    file_put_contents($logFile, $logData, FILE_APPEND);
}

// 检查登录状态 - 使用主系统认证
if(!isset($_SESSION['zid']) && !$islogin2) {
    header('Content-Type: application/json');
    echo json_encode(['code' => -1, 'msg' => '请先登录']);
    exit;
}

// 获取当前用户信息
$zid = intval($_SESSION['zid']);
$userrow = $DB->getRow("SELECT * FROM shua_site WHERE zid='{$zid}' AND status=1 LIMIT 1");

if(!$userrow) {
    header('Content-Type: application/json');
    echo json_encode(['code' => -1, 'msg' => '用户不存在或已被禁用']);
    exit;
}

// 统一资源管理类
class ResourceManager {
    private $DB;
    private $user;
    private $isVip;
    
    public function __construct() {
        global $DB, $userrow;
        $this->DB = $DB;
        $this->user = $userrow;
        
        // 检查VIP状态 - 统一使用主系统的VIP字段
        $this->isVip = ($this->user['finance_vip'] == 2 || $this->user['finance_vip'] == 9);
    }
    
    // 获取用户信息 - 统一格式
    public function getUserInfo() {
        return [
            'code' => 0,
            'data' => [
                'zid' => $this->user['zid'],
                'username' => $this->user['user'],
                'rmb' => floatval($this->user['rmb']), // 主系统余额字段
                'is_vip' => $this->isVip,
                'vip_level' => intval($this->user['finance_vip']),
                'vip_expire' => $this->user['finance_vip_expire'] ?? null,
                'power' => intval($this->user['power']),
                'status' => intval($this->user['status'])
            ]
        ];
    }
    
    // 购买资源 - 集成主系统余额扣除和订单系统
    public function buyResource($resourceId, $quantity = 1) {
        try {
            $this->DB->beginTransaction();
            
            // 检查资源是否存在
            $resource = $this->DB->getRow("SELECT * FROM shua_finance_content WHERE id='{$resourceId}' AND status=1");
            if(!$resource) {
                throw new Exception('资源不存在或已下架');
            }
            
            // 计算总价
            $totalPrice = floatval($resource['price']) * intval($quantity);
            
            // 检查余额
            if(floatval($this->user['rmb']) < $totalPrice) {
                throw new Exception('余额不足，当前余额：' . $this->user['rmb'] . '元');
            }
            
            // 生成订单号
            $orderNo = date('YmdHis').rand(1000,9999);
            
            // 创建金融订单 - 使用主系统订单表结构
            $orderData = [
                'trade_no' => $orderNo,
                'type' => 'finance_resource',
                'zid' => $this->user['zid'],
                'tid' => $resourceId,
                'name' => $resource['name'],
                'money' => $totalPrice,
                'num' => $quantity,
                'status' => 0,
                'addtime' => date('Y-m-d H:i:s')
            ];
            
            $orderId = $this->DB->insert('pay', $orderData);
            
            // 扣除余额 - 直接操作主系统余额字段
            $this->DB->exec("UPDATE shua_site SET rmb=rmb-{$totalPrice} WHERE zid='{$this->user['zid']}'");
            
            // 更新订单状态为已支付
            $this->DB->exec("UPDATE shua_pay SET status=1, endtime=NOW() WHERE trade_no='{$orderNo}'");
            
            // 记录余额变动 - 使用主系统积分记录表
            $this->DB->insert('points', [
                'zid' => $this->user['zid'],
                'action' => '购买金融资源',
                'point' => $totalPrice,
                'bz' => "购买资源: {$resource['name']} x{$quantity}",
                'orderid' => $orderNo,
                'addtime' => date('Y-m-d H:i:s'),
                'status' => 1
            ]);
            
            $this->DB->commit();
            
            return [
                'code' => 0,
                'msg' => '购买成功',
                'data' => [
                    'order_no' => $orderNo,
                    'order_id' => $orderId,
                    'resource_name' => $resource['name'],
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'remaining_balance' => floatval($this->user['rmb']) - $totalPrice
                ]
            ];
            
        } catch(Exception $e) {
            $this->DB->rollBack();
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
            $where .= " AND category_id='{$category}'";
        }
        
        $sql = "SELECT * FROM shua_finance_content WHERE {$where} ORDER BY id DESC LIMIT {$offset},{$limit}";
        $resources = $this->DB->getAll($sql);
        
        $countSql = "SELECT COUNT(*) as total FROM shua_finance_content WHERE {$where}";
        $totalResult = $this->DB->getRow($countSql);
        $total = $totalResult['total'];
        
        return [
            'code' => 0,
            'data' => [
                'list' => $resources,
                'total' => intval($total),
                'page' => intval($page),
                'limit' => intval($limit),
                'pages' => ceil($total / $limit)
            ]
        ];
    }
    
    // 获取用户购买记录 - 集成主系统订单
    public function getUserPurchases($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT p.*, fc.name as resource_name, fc.description 
                FROM shua_pay p 
                LEFT JOIN shua_finance_content fc ON p.tid=fc.id 
                WHERE p.zid='{$this->user['zid']}' AND p.type='finance_resource' AND p.status=1 
                ORDER BY p.addtime DESC LIMIT {$offset},{$limit}";
        $purchases = $this->DB->getAll($sql);
        
        $countSql = "SELECT COUNT(*) as total FROM shua_pay 
                     WHERE zid='{$this->user['zid']}' AND type='finance_resource' AND status=1";
        $totalResult = $this->DB->getRow($countSql);
        $total = $totalResult['total'];
        
        return [
            'code' => 0,
            'data' => [
                'list' => $purchases,
                'total' => intval($total),
                'page' => intval($page),
                'limit' => intval($limit),
                'pages' => ceil($total / $limit)
            ]
        ];
    }
    
    // 充值余额 - 集成主系统充值
    public function recharge($amount, $payType = 'alipay') {
        try {
            $amount = floatval($amount);
            if($amount <= 0) {
                throw new Exception('充值金额必须大于0');
            }
            
            // 生成充值订单
            $orderNo = 'CZ'.date('YmdHis').rand(1000,9999);
            
            $orderData = [
                'trade_no' => $orderNo,
                'type' => 'recharge',
                'zid' => $this->user['zid'],
                'name' => '账户充值',
                'money' => $amount,
                'status' => 0,
                'addtime' => date('Y-m-d H:i:s')
            ];
            
            $orderId = $this->DB->insert('pay', $orderData);
            
            return [
                'code' => 0,
                'msg' => '充值订单创建成功',
                'data' => [
                    'order_no' => $orderNo,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'pay_type' => $payType
                ]
            ];
            
        } catch(Exception $e) {
            return [
                'code' => -1,
                'msg' => $e->getMessage()
            ];
        }
    }
    
    // 开通VIP - 集成主系统VIP
    public function openVip($vipType = 1, $months = 1) {
        try {
            $this->DB->beginTransaction();
            
            // VIP价格配置
            $vipPrices = [
                1 => 10.00, // 普通VIP月费
                2 => 30.00  // 高级VIP月费
            ];
            
            if(!isset($vipPrices[$vipType])) {
                throw new Exception('VIP类型不存在');
            }
            
            $totalPrice = $vipPrices[$vipType] * $months;
            
            // 检查余额
            if(floatval($this->user['rmb']) < $totalPrice) {
                throw new Exception('余额不足');
            }
            
            // 计算VIP到期时间
            $currentExpire = $this->user['finance_vip_expire'] && strtotime($this->user['finance_vip_expire']) > time() 
                           ? $this->user['finance_vip_expire'] 
                           : date('Y-m-d H:i:s');
            
            $newExpire = date('Y-m-d H:i:s', strtotime($currentExpire . " +{$months} months"));
            
            // 扣除余额
            $this->DB->exec("UPDATE shua_site SET rmb=rmb-{$totalPrice} WHERE zid='{$this->user['zid']}'");
            
            // 更新VIP状态
            $this->DB->exec("UPDATE shua_site SET finance_vip='{$vipType}', finance_vip_expire='{$newExpire}' WHERE zid='{$this->user['zid']}'");
            
            // 记录订单
            $orderNo = 'VIP'.date('YmdHis').rand(1000,9999);
            $this->DB->insert('pay', [
                'trade_no' => $orderNo,
                'type' => 'vip',
                'zid' => $this->user['zid'],
                'name' => "开通VIP{$vipType} {$months}个月",
                'money' => $totalPrice,
                'status' => 1,
                'addtime' => date('Y-m-d H:i:s'),
                'endtime' => date('Y-m-d H:i:s')
            ]);
            
            // 记录余额变动
            $this->DB->insert('points', [
                'zid' => $this->user['zid'],
                'action' => '开通VIP',
                'point' => $totalPrice,
                'bz' => "开通VIP{$vipType} {$months}个月",
                'orderid' => $orderNo,
                'addtime' => date('Y-m-d H:i:s'),
                'status' => 1
            ]);
            
            $this->DB->commit();
            
            return [
                'code' => 0,
                'msg' => 'VIP开通成功',
                'data' => [
                    'vip_type' => $vipType,
                    'expire_time' => $newExpire,
                    'order_no' => $orderNo
                ]
            ];
            
        } catch(Exception $e) {
            $this->DB->rollBack();
            return [
                'code' => -1,
                'msg' => $e->getMessage()
            ];
        }
    }
}

// 处理API请求
$manager = new ResourceManager();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch($action) {
        case 'user_info':
            $result = $manager->getUserInfo();
            break;
            
        case 'buy':
            $resourceId = intval($_POST['resource_id'] ?? 0);
            $quantity = intval($_POST['quantity'] ?? 1);
            if($resourceId <= 0) {
                throw new Exception('资源ID无效');
            }
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
            
        case 'recharge':
            $amount = floatval($_POST['amount'] ?? 0);
            $payType = $_POST['pay_type'] ?? 'alipay';
            $result = $manager->recharge($amount, $payType);
            break;
            
        case 'open_vip':
            $vipType = intval($_POST['vip_type'] ?? 1);
            $months = intval($_POST['months'] ?? 1);
            $result = $manager->openVip($vipType, $months);
            break;
            
        default:
            $result = ['code' => -1, 'msg' => '未知操作'];
    }
} catch(Exception $e) {
    $result = ['code' => -1, 'msg' => $e->getMessage()];
}

// 输出JSON响应
header('Content-Type: application/json; charset=utf-8');
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); 