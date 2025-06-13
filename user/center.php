<?php
require_once '../includes/common.php';
$user = get_login_user();
?>
<!DOCTYPE html>
<html>
<head>
    <title>会员中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/news/style.css">
</head>
<body>
    <h1>会员中心</h1>
    <p>用户名：<?php echo htmlspecialchars($user['user']); ?></p>
    <p>余额：<?php echo $user['money']; ?> 元</p>
    <p>会员状态：<?php echo is_user_vip($user['id']) ? 'VIP会员' : '普通用户'; ?></p>
    <a href="/user/vip.php">开通/续费VIP</a>
    <a href="/news/index.php">返回资讯</a>
</body>
</html>