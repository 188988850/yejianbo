<?php
session_start();
require_once('../config.php');
$DB = DB::getInstance($dbconfig);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$resource = $DB->getRow("SELECT * FROM shua_finance_content WHERE id='$id' AND status=1 LIMIT 1");
if(!$resource) die('资源不存在');
$user = null;
if(isset($_SESSION['zid'])) {
    $user_id = $_SESSION['zid'];
    $user = $DB->getRow("SELECT * FROM shua_site WHERE zid='{$user_id}' LIMIT 1");
}
$is_vip = $user && $user['finance_vip'] == 1 && $user['finance_vip_expire'] > date('Y-m-d H:i:s');
$has_bought = false;
if($user && !$is_vip) {
    $order = $DB->getRow("SELECT * FROM shua_finance_order WHERE zid='{$user['zid']}' AND resource_id='$id' AND status=1 LIMIT 1");
    $has_bought = $order ? true : false;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($resource['title']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/news/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($resource['title']); ?></h1>
    <p><?php echo htmlspecialchars($resource['summary']); ?></p>
    <?php if(!$user): ?>
        <p>请先 <a href="/login.php">登录</a> 后查看详情内容。</p>
    <?php elseif($is_vip || $has_bought): ?>
        <div class="finance-content">
            <?php echo nl2br(htmlspecialchars($resource['content'])); ?>
        </div>
    <?php else: ?>
        <div class="finance-preview">
            <?php echo nl2br(htmlspecialchars($resource['preview'])); ?>
        </div>
        <form method="post" action="financebuy.php">
            <input type="hidden" name="id" value="<?php echo $resource['id']; ?>">
            <button type="submit">单独购买（<?php echo $resource['price']; ?>元）</button>
        </form>
        <a href="financevip.php">开通金融会员免费看全部内容</a>
    <?php endif; ?>
    <a href="finance.php">返回列表</a>
</body>
</html> 