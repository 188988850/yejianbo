<?php
require_once '../admin_check.php';
require_once '../config.php';

$sql = "SELECT * FROM news WHERE vip_content IS NOT NULL AND vip_content != '' ORDER BY add_time DESC";
$result = $db->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>会员内容管理</title>
</head>
<body>
    <h1>会员内容管理</h1>
    <table>
        <tr><th>ID</th><th>标题</th><th>时间</th><th>操作</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
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