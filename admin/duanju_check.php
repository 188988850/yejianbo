<?php
// 引入数据库配置
include("./includes/common.php");

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "<h1>短剧分类和资源诊断</h1>";

    // 检查videotype表
    echo "<h2>主分类列表</h2>";
    $main_categories = $DB->getAll("SELECT * FROM shua_videotype WHERE parent_id=0 ORDER BY id ASC");
    if (!$main_categories) throw new Exception("无法获取主分类列表");
    echo "<table border='1'>
    <tr><th>ID</th><th>名称</th><th>排序</th></tr>";
    foreach($main_categories as $row){
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['sort']}</td></tr>";
    }
    echo "</table>";

    echo "<h2>子分类列表</h2>";
    $sub_categories = $DB->getAll("SELECT * FROM shua_videotype WHERE parent_id>0 ORDER BY parent_id ASC");
    if (!$sub_categories) throw new Exception("无法获取子分类列表");
    echo "<table border='1'>
    <tr><th>ID</th><th>父分类ID</th><th>名称</th><th>排序</th></tr>";
    foreach($sub_categories as $row){
        echo "<tr><td>{$row['id']}</td><td>{$row['parent_id']}</td><td>{$row['name']}</td><td>{$row['sort']}</td></tr>";
    }
    echo "</table>";

    // 检查资源表
    echo "<h2>资源统计</h2>";
    foreach($main_categories as $main){
        $sub_types = $DB->getAll("SELECT id FROM shua_videotype WHERE parent_id='{$main['id']}'");
        if(!empty($sub_types)){
            $sub_ids = array();
            foreach($sub_types as $t){
                $sub_ids[] = $t['id'];
            }
            $count = $DB->getColumn("SELECT COUNT(*) FROM shua_videolist WHERE type IN (".implode(',', $sub_ids).")");
            echo "主分类 [{$main['name']}] 下共有 {$count} 个资源<br>";
            
            // 显示每个子分类的资源数
            foreach($sub_types as $sub){
                $subname = $DB->getColumn("SELECT name FROM shua_videotype WHERE id='{$sub['id']}'");
                $subcount = $DB->getColumn("SELECT COUNT(*) FROM shua_videolist WHERE type='{$sub['id']}'");
                echo "-- 子分类 [{$subname}] 下有 {$subcount} 个资源<br>";
            }
        } else {
            echo "主分类 [{$main['name']}] 下没有子分类<br>";
        }
    }

    // 检查资源没有分类的情况
    $nocat_count = $DB->getColumn("SELECT COUNT(*) FROM shua_videolist WHERE type=0 OR type IS NULL");
    echo "<br>无分类资源数量: {$nocat_count}<br>";

    echo "<h2>最近10个资源</h2>";
    $recent = $DB->getAll("SELECT * FROM shua_videolist ORDER BY id DESC LIMIT 10");
    if (!$recent) throw new Exception("无法获取最近的资源");
    echo "<table border='1'>
    <tr><th>ID</th><th>名称</th><th>分类ID</th><th>添加时间</th></tr>";
    foreach($recent as $row){
        $typename = $DB->getColumn("SELECT name FROM shua_videotype WHERE id='{$row['type']}'");
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['type']} ({$typename})</td><td>{$row['addtime']}</td></tr>";
    }
    echo "</table>";

} catch (Exception $e) {
    echo "<div style='color:red'>错误: " . $e->getMessage() . "</div>";
}
?> 