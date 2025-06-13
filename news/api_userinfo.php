<?php
require_once '../includes/common.php';

// 检查登录状态
if (!isset($_SESSION['zid'])) {
    echo json_encode(['code' => -1, 'msg' => '未登录']);
    exit;
}

$zid = intval($_SESSION['zid']);
$userrow = $DB->getRow("SELECT * FROM shua_site WHERE zid=:zid", [':zid' => $zid]);

if (!$userrow) {
    echo json_encode(['code' => -1, 'msg' => '用户不存在']);
    exit;
}

// 返回用户信息
echo json_encode([
    'code' => 0,
    'data' => [
        'username' => $userrow['user'],
        'money' => $userrow['money'],
        'vip_level' => $userrow['finance_vip'],
        'zid' => $userrow['zid'],
        'power' => $userrow['power']
    ]
]); 