<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_domain', '.wamsg.cn');
session_set_cookie_params(['path' => '/', 'domain' => '.wamsg.cn']);
session_start();
require_once('../config.php');

$host = 'localhost';
$port = 3306;
$user = 'xinfaka';
$pwd = '82514a97de548852';
$dbname = 'xinfaka';

echo "<pre>host: $host\nuser: $user\npwd: $pwd\ndbname: $dbname\nport: $port</pre>";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "数据库连接成功！";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);
    echo "<br>当前数据库表：<br>";
    foreach ($tables as $t) {
        echo $t[0] . "<br>";
    }

    // 获取当前登录用户
    $user = null;
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $user = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_users WHERE id='{$user_id}'")->fetch();
    }
} catch (PDOException $e) {
    echo "<br><b style='color:red'>数据库连接失败：</b>" . htmlspecialchars($e->getMessage());
}