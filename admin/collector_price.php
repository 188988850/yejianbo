<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../config.php');

$conn = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($conn->connect_error) {
    echo json_encode(['code' => -1, 'message' => '数据库连接失败']); exit;
}
$conn->set_charset("utf8mb4");

$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($action === 'save') {
    $prices = isset($_POST['prices']) ? $_POST['prices'] : array();
    if (empty($prices)) {
        echo json_encode(['code' => -1, 'message' => '价格数据不能为空']); exit;
    }
    foreach ($prices as $name => $value) {
        $name = $conn->real_escape_string($name);
        $value = $conn->real_escape_string($value);
        $sql = "UPDATE {$dbconfig['dbqz']}_config SET value='$value' WHERE name='$name' AND type='price'";
        $conn->query($sql);
        if ($conn->affected_rows === 0) {
            $sql2 = "INSERT INTO {$dbconfig['dbqz']}_config (type, name, value) VALUES ('price', '$name', '$value')";
            $conn->query($sql2);
        }
    }
    echo json_encode(['code' => 0, 'message' => '价格设置已保存']); exit;
}
if ($action === 'get') {
    $result = $conn->query("SELECT name, value FROM {$dbconfig['dbqz']}_config WHERE type='price'");
    $prices = array();
    while ($row = $result->fetch_assoc()) {
        $prices[$row['name']] = $row['value'];
    }
    echo json_encode(['code'=>0, 'data'=>$prices]); exit;
}
echo json_encode(['code' => -1, 'message' => '未知操作']); exit; 