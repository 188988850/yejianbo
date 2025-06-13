<?php
// 引入数据库配置
include_once __DIR__ . '/../includes/common.php';

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ======= 取消密钥校验，任何人都能访问 =======
// if(!isset($_GET['key']) || $_GET['key'] != md5('ysduanju_fix'.date('Ymd'))){
//     exit('访问密钥错误，请联系管理员获取正确的访问链接');
// }

try {
    echo "<h1>短剧分类和资源修复工具</h1>";

    // 如果提交了修复请求
    if(isset($_GET['action']) && $_GET['action']=='fix'){
        // 1. 修复分类数据
        echo "<h2>开始修复分类数据...</h2>";
        
        // 1.1 确保所有主分类的parent_id为0
        $DB->exec("UPDATE shua_videotype SET parent_id=0 WHERE parent_id IS NULL");
        echo "已将空parent_id值设为0<br>";
        
        // 1.2 检查是否存在无效的父分类ID (指向不存在的分类)
        $invalid_parents = $DB->getAll("SELECT t1.* FROM shua_videotype t1 LEFT JOIN shua_videotype t2 ON t1.parent_id=t2.id WHERE t1.parent_id>0 AND t2.id IS NULL");
        if(!empty($invalid_parents)){
            echo "发现".count($invalid_parents)."个无效的父分类引用，正在修复...<br>";
            foreach($invalid_parents as $row){
                $DB->exec("UPDATE shua_videotype SET parent_id=0 WHERE id='{$row['id']}'");
                echo "- 将ID为{$row['id']}的分类[{$row['name']}]改为主分类<br>";
            }
        }
        
        // 2. 修复资源数据
        echo "<h2>开始修复资源数据...</h2>";
        
        // 2.1 检查资源的分类是否存在
        $invalid_types = $DB->getAll("SELECT v.* FROM shua_videolist v LEFT JOIN shua_videotype t ON v.type=t.id WHERE t.id IS NULL AND v.type>0");
        if(!empty($invalid_types)){
            echo "发现".count($invalid_types)."个指向不存在分类的资源，正在修复...<br>";
            // 选择一个默认分类 (使用第一个子分类，如果没有子分类，则使用第一个主分类)
            $default_type = $DB->getColumn("SELECT id FROM shua_videotype WHERE parent_id>0 ORDER BY id ASC LIMIT 1");
            if(!$default_type){
                $default_type = $DB->getColumn("SELECT id FROM shua_videotype WHERE parent_id=0 ORDER BY id ASC LIMIT 1");
            }
            if($default_type){
                foreach($invalid_types as $row){
                    $DB->exec("UPDATE shua_videolist SET type='{$default_type}' WHERE id='{$row['id']}'");
                    echo "- 将ID为{$row['id']}的资源[{$row['name']}]分类修改为{$default_type}<br>";
                }
            }else{
                echo "未找到可用的分类，请先添加分类<br>";
            }
        }
        
        // 2.2 修复未分类的资源
        $untyped = $DB->getAll("SELECT * FROM shua_videolist WHERE type=0 OR type IS NULL");
        if(!empty($untyped)){
            echo "发现".count($untyped)."个未分类资源，正在修复...<br>";
            // 选择一个默认分类
            $default_type = $DB->getColumn("SELECT id FROM shua_videotype WHERE parent_id>0 ORDER BY id ASC LIMIT 1");
            if(!$default_type){
                $default_type = $DB->getColumn("SELECT id FROM shua_videotype WHERE parent_id=0 ORDER BY id ASC LIMIT 1");
            }
            if($default_type){
                foreach($untyped as $row){
                    $DB->exec("UPDATE shua_videolist SET type='{$default_type}' WHERE id='{$row['id']}'");
                    echo "- 将ID为{$row['id']}的资源[{$row['name']}]分类修改为{$default_type}<br>";
                }
            }else{
                echo "未找到可用的分类，请先添加分类<br>";
            }
        }
        
        echo "<h2>修复完成!</h2>";
        echo "<a href='ysduanju_check.php'>查看修复后的数据状态</a> | <a href='ysduanju_fix.php'>返回</a>";
    }else{
        // 显示诊断信息并提供修复选项
        // 检查videotype表
        echo "<h2>分类数据检查</h2>";
        
        // 1.1 检查分类表是否存在
        $table_exists = $DB->getColumn("SHOW TABLES LIKE 'shua_videotype'");
        if(!$table_exists){
            echo "<div style='color:red'>错误: shua_videotype表不存在!</div>";
            // 创建表的SQL
            echo "<pre>
CREATE TABLE `shua_videotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '10',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        </pre>";
        }else{
            // 1.2 检查主分类和子分类
            $main_count = $DB->getColumn("SELECT COUNT(*) FROM shua_videotype WHERE parent_id=0 OR parent_id IS NULL");
            $sub_count = $DB->getColumn("SELECT COUNT(*) FROM shua_videotype WHERE parent_id>0");
            echo "主分类数量: ".$main_count."<br>";
            echo "子分类数量: ".$sub_count."<br>";
            
            // 1.3 检查是否存在无效的父分类ID
            $invalid_parents = $DB->getColumn("SELECT COUNT(*) FROM shua_videotype t1 LEFT JOIN shua_videotype t2 ON t1.parent_id=t2.id WHERE t1.parent_id>0 AND t2.id IS NULL");
            echo "无效的父分类引用: ".$invalid_parents."<br>";
        }
        
        // 检查资源表
        echo "<h2>资源数据检查</h2>";
        
        // 2.1 检查资源表是否存在
        $table_exists = $DB->getColumn("SHOW TABLES LIKE 'shua_videolist'");
        if(!$table_exists){
            echo "<div style='color:red'>错误: shua_videolist表不存在!</div>";
        }else{
            // 2.2 检查资源数量
            $total_resources = $DB->getColumn("SELECT COUNT(*) FROM shua_videolist");
            echo "资源总数: ".$total_resources."<br>";
            
            // 2.3 检查资源的分类是否存在
            $invalid_types = $DB->getColumn("SELECT COUNT(*) FROM shua_videolist v LEFT JOIN shua_videotype t ON v.type=t.id WHERE t.id IS NULL AND v.type>0");
            echo "指向不存在分类的资源: ".$invalid_types."<br>";
            
            // 2.4 检查未分类的资源
            $untyped = $DB->getColumn("SELECT COUNT(*) FROM shua_videolist WHERE type=0 OR type IS NULL");
            echo "未分类资源数量: ".$untyped."<br>";
        }
        
        // 提供修复选项
        echo "<h2>修复选项</h2>";
        echo "<a href='ysduanju_fix.php?action=fix' onclick=\"return confirm('确认要执行修复操作吗？此操作将更改数据库内容！');\">开始修复</a>";
    }
} catch (Exception $e) {
    echo "<div style='color:red'>错误: " . $e->getMessage() . "</div>";
}
?> 