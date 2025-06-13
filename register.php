<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, balance, vip) VALUES (?, ?, 0, 0)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $db->insert_id;
        header('Location: /user/center.php');
        exit;
    } else {
        $error = "注册失败，用户名可能已存在";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>注册</title>
</head>
<body>
    <h1>注册</h1>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <p>账号：<input type="text" name="username" required></p>
        <p>密码：<input type="password" name="password" required></p>
        <button type="submit">注册</button>
    </form>
    <a href="login.php">已有账号？登录</a>
</body>
</html>