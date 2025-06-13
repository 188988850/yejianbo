<?php
/**
 * 秒杀商品列表
**/
include("../includes/common.php");
$title='短剧分类管理';
include './head.php';

if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
<link rel="stylesheet" href="https://www.jq22.com/demo/TGTool202007201551/TGTool.css">
<script src="https://www.jq22.com/demo/TGTool202007201551/TGTool.js"></script>
<div class="col-md-12 center-block" style="float: none;">
        
<?php

adminpermission('shop', 1);

$my=isset($_GET['my'])?$_GET['my']:null;

// 添加分类
if($my=='add_submit'){
    $name=trim($_POST['name']);
    $parent_id=intval($_POST['parent_id']);
    $sort=intval($_POST['sort']);
    $addtime = time();
    if($name==NULL){
        showmsg('分类名称不能为空!',3);
    } else {
        $sql="insert into `shua_videotype` (`name`,`parent_id`,`sort`,`addtime`) values ('{$name}','{$parent_id}','{$sort}','{$addtime}')";
        if($DB->exec($sql)!==false){
            showmsg('添加分类成功！<br/><br/><a href="./videotype.php">>>返回分类列表</a>',1);
        }else{
            showmsg('添加分类失败！'.$DB->error(),4);
        }
    }
    exit;
}
// 编辑分类
if($my=='edit_submit'){
    $id=intval($_GET['id']);
    $name=trim($_POST['name']);
    $parent_id=intval($_POST['parent_id']);
    $sort=intval($_POST['sort']);
    if($name==NULL){
        showmsg('分类名称不能为空!',3);
    } else {
        $sql="UPDATE `shua_videotype` SET `name`='{$name}',`parent_id`='{$parent_id}',`sort`='{$sort}' WHERE `id`='{$id}'";
        if($DB->exec($sql)!==false){
            showmsg('修改分类成功！<br/><br/><a href="./videotype.php">>>返回分类列表</a>',1);
        }else{
            showmsg('修改分类失败！'.$DB->error(),4);
        }
    }
    exit;
}
// 删除分类
if($my=='delete'){
    $id=intval($_GET['id']);
    // 检查是否有子分类
    $sub_count = $DB->getColumn("SELECT COUNT(*) FROM shua_videotype WHERE parent_id='{$id}'");
    if($sub_count>0){
        showmsg('该分类下有子分类，不能删除！',3);
        exit;
    }
    // 检查是否有关联视频
    $video_count = $DB->getColumn("SELECT COUNT(*) FROM shua_videolist WHERE type='{$id}'");
    if($video_count>0){
        showmsg('该分类下有视频，不能删除！',3);
        exit;
    }
    $sql="DELETE FROM shua_videotype WHERE id='{$id}'";
    if($DB->exec($sql)!==false){
        showmsg('删除分类成功！<br/><br/><a href="./videotype.php">>>返回分类列表</a>',1);
    }else{
        showmsg('删除分类失败！'.$DB->error(),4);
    }
    exit;
}

// 获取主分类和子分类
$main_categories = $DB->getAll("SELECT * FROM shua_videotype WHERE parent_id=0 ORDER BY sort ASC, id ASC");
$sub_categories = $DB->getAll("SELECT * FROM shua_videotype WHERE parent_id>0 ORDER BY sort ASC, id ASC");

?>
<div class="col-md-12 center-block" style="float: none;">
    <div class="block">
        <div class="block-title"><h3 class="panel-title">添加/编辑短剧分类</h3></div>
        <form action="?my=add_submit" method="post" class="form-inline" style="margin-bottom:20px;">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="分类名称" required>
            </div>
            <div class="form-group">
                <select name="parent_id" class="form-control">
                    <option value="0">主分类</option>
                    <?php foreach($main_categories as $cat){?>
                        <option value="<?php echo $cat['id'];?>">└ <?php echo $cat['name'];?></option>
                    <?php }?>
                </select>
            </div>
            <div class="form-group">
                <input type="number" name="sort" class="form-control" placeholder="排序" value="1" required>
            </div>
            <button type="submit" class="btn btn-primary">添加分类</button>
        </form>
    </div>
    <div class="block">
        <div class="block-title clearfix">
            <h3 class="panel-title pull-left">主分类列表</h3>
        </div>
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>名称</th><th>排序</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach($main_categories as $cat){?>
                <tr>
                    <td><?php echo $cat['id'];?></td>
                    <td><?php echo $cat['name'];?></td>
                    <td><?php echo $cat['sort'];?></td>
                    <td>
                        <a href="?my=edit&id=<?php echo $cat['id'];?>" class="btn btn-xs btn-info">编辑</a>
                        <a href="?my=delete&id=<?php echo $cat['id'];?>" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除此主分类吗？')">删除</a>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="block">
        <div class="block-title clearfix">
            <h3 class="panel-title pull-left">子分类列表</h3>
        </div>
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>名称</th><th>所属主分类</th><th>排序</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach($sub_categories as $cat){
                $parent = $DB->getRow("SELECT name FROM shua_videotype WHERE id='{$cat['parent_id']}'");
            ?>
                <tr>
                    <td><?php echo $cat['id'];?></td>
                    <td><?php echo $cat['name'];?></td>
                    <td><?php echo $parent['name'];?></td>
                    <td><?php echo $cat['sort'];?></td>
                    <td>
                        <a href="?my=edit&id=<?php echo $cat['id'];?>" class="btn btn-xs btn-info">编辑</a>
                        <a href="?my=delete&id=<?php echo $cat['id'];?>" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除此子分类吗？')">删除</a>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <?php if($my=='edit'){
        $id=intval($_GET['id']);
        $row=$DB->getRow("SELECT * FROM shua_videotype WHERE id='{$id}' LIMIT 1");
        if($row){ ?>
        <div class="block">
            <div class="block-title"><h3 class="panel-title">编辑分类</h3></div>
            <form action="?my=edit_submit&id=<?php echo $row['id'];?>" method="post" class="form-inline">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" value="<?php echo $row['name'];?>" required>
                </div>
                <div class="form-group">
                    <select name="parent_id" class="form-control">
                        <option value="0">主分类</option>
                        <?php foreach($main_categories as $cat){?>
                            <option value="<?php echo $cat['id'];?>" <?php if($row['parent_id']==$cat['id'])echo 'selected';?>>└ <?php echo $cat['name'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="number" name="sort" class="form-control" value="<?php echo $row['sort'];?>" required>
                </div>
                <button type="submit" class="btn btn-primary">保存修改</button>
                <a href="./videotype.php" class="btn btn-default">返回</a>
            </form>
        </div>
    <?php }} ?>
</div>
<script src="<?php echo $cdnpublic?>layer/3.4.0/layer.js"></script>
<script src="assets/js/videotype.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>