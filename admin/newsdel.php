<?php
require_once '../config.php';
$mysqli = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($mysqli->connect_errno) exit('数据库连接失败: ' . $mysqli->connect_error);
$mysqli->set_charset($dbconfig['charset'] ?? 'utf8mb4');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$catid = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
if ($id) {
    $mysqli->query("DELETE FROM `{$dbconfig['dbqz']}_news` WHERE id=$id");
}
header('Location: newsList.php?cat=' . $catid);
exit;