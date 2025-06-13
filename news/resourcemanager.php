<?php
require_once '../includes/common.php';

// 检查登录状态
if (!isset($_SESSION['user'])) {
    exit(json_encode(['code' => -1, 'msg' => '请先登录']));
}

$userrow = $_SESSION['user'];
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

switch ($action) {
    case 'list':
        // 获取资源列表
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        // 构建查询条件
        $where = [];
        $params = [];
        
        // 搜索条件
        if (!empty($_GET['search'])) {
            $where[] = "name LIKE :search";
            $params[':search'] = '%' . $_GET['search'] . '%';
        }
        
        // 分类条件
        if (!empty($_GET['category'])) {
            $where[] = "category = :category";
            $params[':category'] = $_GET['category'];
        }
        
        $where_sql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // 获取总数
        $total = $DB->getColumn("SELECT COUNT(*) FROM shua_finance_content {$where_sql}", $params);
        
        // 获取列表
        $list = $DB->getAll("SELECT * FROM shua_finance_content {$where_sql} ORDER BY id DESC LIMIT {$offset},{$limit}", $params);
        
        // 处理列表数据
        foreach ($list as &$item) {
            $item['is_vip'] = $item['vip_level'] > 0;
            // 检查用户是否已购买
            $item['is_purchased'] = $DB->getColumn("SELECT COUNT(*) FROM shua_finance_order WHERE zid=:zid AND resource_id=:resource_id", [
                ':zid' => $userrow['zid'],
                ':resource_id' => $item['id']
            ]) > 0;
        }
        
        exit(json_encode([
            'code' => 0,
            'data' => [
                'list' => $list,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ]
        ]));
        break;
        
    case 'buy':
        // 购买资源
        $resource_id = isset($_POST['resource_id']) ? intval($_POST['resource_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($resource_id <= 0 || $quantity <= 0) {
            exit(json_encode(['code' => -1, 'msg' => '参数错误']));
        }
        
        // 获取资源信息
        $resource = $DB->getRow("SELECT * FROM shua_finance_content WHERE id=:id", [':id' => $resource_id]);
        if (!$resource) {
            exit(json_encode(['code' => -1, 'msg' => '资源不存在']));
        }
        
        // 检查VIP权限
        if ($resource['vip_level'] > 0 && $userrow['finance_vip'] < $resource['vip_level']) {
            exit(json_encode(['code' => -1, 'msg' => '需要VIP' . $resource['vip_level'] . '才能购买']));
        }
        
        // 计算总价
        $total_price = $resource['price'] * $quantity;
        
        // 检查余额
        if ($userrow['money'] < $total_price) {
            exit(json_encode(['code' => -1, 'msg' => '余额不足']));
        }
        
        // 开始事务
        $DB->beginTransaction();
        try {
            // 扣除余额
            $DB->exec("UPDATE shua_site SET money=money-:money WHERE zid=:zid", [
                ':money' => $total_price,
                ':zid' => $userrow['zid']
            ]);
            
            // 创建订单
            $DB->exec("INSERT INTO shua_finance_order (zid,resource_id,price,status,addtime) VALUES (:zid,:resource_id,:price,1,:addtime)", [
                ':zid' => $userrow['zid'],
                ':resource_id' => $resource_id,
                ':price' => $total_price,
                ':addtime' => date('Y-m-d H:i:s')
            ]);
            
            // 更新用户session中的余额
            $_SESSION['user']['money'] -= $total_price;
            
            $DB->commit();
            exit(json_encode(['code' => 0, 'msg' => '购买成功']));
        } catch (Exception $e) {
            $DB->rollBack();
            exit(json_encode(['code' => -1, 'msg' => '购买失败：' . $e->getMessage()]));
        }
        break;
        
    default:
        exit(json_encode(['code' => -1, 'msg' => '未知操作']));
} 