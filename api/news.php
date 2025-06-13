<?php
require_once '../config.php';
header('Content-Type: application/json');
$page = max(1, intval($_GET['page']));
$limit = 10;
$offset = ($page-1)*$limit;
$sql = "SELECT id, title, cover_url, add_time, read_count FROM news ORDER BY add_time DESC LIMIT $offset, $limit";
$result = $db->query($sql);
$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode([
    'code' => 200,
    'msg' => 'success',
    'data' => $data
]);