<?php
require_once '../admin_check.php';
require_once '../includes/common.php';

$sql = "SELECT * FROM `{$dbconfig['dbqz']}_news` ORDER BY add_time DESC";
$result = $DB->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>资讯管理</title>
</head>
<body>
    <h1>资讯管理</h1>
    <a href="news_add.php">添加资讯</a>
    <table>
        <tr><th>ID</th><th>标题</th><th>时间</th><th>操作</th></tr>
        <?php while($row = $DB->fetch($result)) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo $row['add_time']; ?></td>
            <td>
                <a href="news_edit.php?id=<?php echo $row['id']; ?>">编辑</a>
                <a href="news_del.php?id=<?php echo $row['id']; ?>">删除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>