<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_domain', '.wamsg.cn');
session_set_cookie_params(['path' => '/', 'domain' => '.wamsg.cn']);
session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.class.php';

if (!isset($dbconfig['charset'])) $dbconfig['charset'] = 'utf8mb4';
if (!isset($dbconfig['pconnect'])) $dbconfig['pconnect'] = 0;
$DB = DB::getInstance($dbconfig);

$page = max(1, intval($_GET['page'] ?? 1));
$pageSize = max(1, intval($_GET['pageSize'] ?? 10));
$offset = ($page - 1) * $pageSize;

$list = $DB->query("SELECT * FROM `{$dbconfig['dbqz']}_news` ORDER BY add_time DESC LIMIT $offset, $pageSize")->fetchAll();

foreach ($list as &$row) {
    $row['add_time'] = date('Y-m-d H:i', strtotime($row['add_time']));
    $row['cover_url'] = $row['cover_url'] ?: '/static/news/default.jpg';
    $row['read_count'] = $row['read_count'] ?? 0;
}
unset($row);

// 获取当前登录用户（主系统账号一体化）
$user = null;
if(isset($_SESSION['zid'])) {
    $user_id = $_SESSION['zid'];
    $user = $DB->query("SELECT * FROM shua_site WHERE zid='{$user_id}'")->fetch();
}

header('Content-Type: application/json');
echo json_encode([
    'code' => 0,
    'msg' => 'ok',
    'data' => $list
]);
?>