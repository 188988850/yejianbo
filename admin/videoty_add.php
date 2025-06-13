<?php
include("../includes/common.php"); // 导入公共配置文件
$title = '短剧分类管理';
include './head.php'; // 网站头部

// 确保用户已登录
if ($islogin !== 1) {
    exit("<script language='javascript'>window.location.href='./login.php';</script>");
}

// 处理添加分类的逻辑
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $sort = (int)$_POST['sort'];
    $addtime = time(); // 当前时间时间戳
    
    // 验证数据
    if (empty($name)) {
        showmsg('分类名称不能为空!', 3);
    } else {
        $sql = "INSERT INTO `shua_videotype` (`name`, `sort`, `addtime`) VALUES (?, ?, ?)";
        $stmt = $DB->prepare($sql);
        if ($stmt->execute([$name, $sort, $addtime])) {
            showmsg('添加短剧分类成功！', 1);
        } else {
            showmsg('添加短剧分类失败！' . $DB->error(), 4);
        }
    }
}

// 获取所有分类以供展示
$result = $DB->query("SELECT * FROM `shua_videotype` ORDER BY `sort` ASC");
?>

<div class="col-md-12 center-block" style="float: none;">
    <div class="block">
        <div class="block-title"><h3 class="panel-title">添加短剧分类</h3></div>
        <div class="">
            <form action="videoty_add.php" method="post" class="form" role="form">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">分类名称</span>
                        <input type="text" id="name" name="name" class="form-control" placeholder="输入分类名称" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">排序</span>
                        <input type="number" id="sort" name="sort" value="1" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="添加" class="btn btn-primary btn-block"/>
                </div>
            </form>
        </div>
    </div>

    <div class="block">
        <div class="block-title"><h3 class="panel-title">短剧分类列表</h3></div>
        <div class="">
            <ul>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                    <li><?php echo htmlspecialchars($row['name']) . ' (排序: ' . htmlspecialchars($row['sort']) . ')'; ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>