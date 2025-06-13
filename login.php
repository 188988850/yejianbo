<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username=? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: /user/center.php');
        exit;
    } else {
        $error = "账号或密码错误";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
</head>
<body>
    <h1>登录</h1>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <p>账号：<input type="text" name="username" required></p>
        <p>密码：<input type="password" name="password" required></p>
        <button type="submit">登录</button>
    </form>
    <a href="register.php">没有账号？注册</a>
</body>
</html>