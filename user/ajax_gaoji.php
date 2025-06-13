<?php
include("../includes/common.php");

// 确保会话已启动
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 禁用错误输出
error_reporting(0);
ini_set('display_errors', 0);

// 清除之前的输出缓冲区
if (ob_get_length()) {
    ob_clean();
}
header('Content-Type: application/json');

// 检查用户登录状态
if (!isset($islogin2) || $islogin2 != 1) {
    exit(json_encode(['code' => -1, 'msg' => '未登录，请重新登录']));
}

// 检查会话是否有效
if (!isset($_SESSION['uid']) || empty($_SESSION['uid'])) {
    exit(json_encode(['code' => -1, 'msg' => '会话已过期，请重新登录']));
}

// 确保用户信息正确加载
try {
    $userrow = $DB->getRow("SELECT * FROM users WHERE uid=:uid LIMIT 1", [':uid' => $_SESSION['uid']]);
    if (!$userrow) {
        exit(json_encode(['code' => -1, 'msg' => '用户信息不存在，请重新登录']));
    }
} catch (Exception $e) {
    exit(json_encode(['code' => -1, 'msg' => '加载用户信息失败：' . $e->getMessage()]));
}

// 检查数据库连接
if (!isset($DB) || !$DB) {
    exit(json_encode(['code' => -1, 'msg' => '数据库连接失败']));
}

$act = isset($_GET['act']) ? $_GET['act'] : null;

if (!$act) {
    exit(json_encode(['code' => -4, 'msg' => 'No Act']));
}

switch ($act) {
    case 'get_settings':
        try {
            $settings = $DB->getRow("SELECT * FROM shua_settings WHERE uid=:uid LIMIT 1", [':uid' => $userrow['uid']]);
            if (!$settings) {
                $default_settings = [
                    'uid' => $userrow['uid'],
                    'virtual_order' => 0,
                    'order_display' => 0,
                    'my_balance' => '0.00',
                    'today_income' => '0.00',
                    'month_income' => '0.00',
                    'total_income' => '0.00',
                    'yesterday_income' => '0.00',
                    'today_detail_income' => '0.00',
                    'addtime' => date('Y-m-d H:i:s'),
                    'updatetime' => date('Y-m-d H:i:s')
                ];
                $DB->insert('shua_settings', $default_settings);
                $settings = $default_settings;
            }
            exit(json_encode(['code' => 0, 'msg' => 'success', 'data' => $settings]));
        } catch (Exception $e) {
            exit(json_encode(['code' => -1, 'msg' => '获取设置失败：' . $e->getMessage()]));
        }
        break;

    case 'save_settings':
        try {
            $data = [
                'my_balance' => floatval($_POST['my_balance']),
                'today_income' => floatval($_POST['today_income']),
                'month_income' => floatval($_POST['month_income']),
                'total_income' => floatval($_POST['total_income']),
                'yesterday_income' => floatval($_POST['yesterday_income']),
                'today_detail_income' => floatval($_POST['today_detail_income']),
                'updatetime' => date('Y-m-d H:i:s')
            ];

            $result = $DB->update('shua_settings', $data, ['uid' => $userrow['uid']]);
            if ($result === false) {
                exit(json_encode(['code' => -1, 'msg' => '保存失败']));
            }
            exit(json_encode(['code' => 0, 'msg' => '保存成功']));
        } catch (Exception $e) {
            exit(json_encode(['code' => -1, 'msg' => '保存失败：' . $e->getMessage()]));
        }
        break;

    case 'generate_orders':
        try {
            $count = intval($_POST['count']);
            $type = intval($_POST['type']);
            if ($count <= 0) {
                exit(json_encode(['code' => -1, 'msg' => '生成条数必须大于0']));
            }

            for ($i = 0; $i < $count; $i++) {
                $DB->insert('shua_orders', [
                    'uid' => $userrow['uid'],
                    'type' => $type,
                    'amount' => rand(1, 100), // 随机金额
                    'addtime' => date('Y-m-d H:i:s')
                ]);
            }
            exit(json_encode(['code' => 0, 'msg' => '生成成功']));
        } catch (Exception $e) {
            exit(json_encode(['code' => -1, 'msg' => '生成失败：' . $e->getMessage()]));
        }
        break;

    default:
        exit(json_encode(['code' => -4, 'msg' => 'No Act']));
        break;
}