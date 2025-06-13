<?php
require_once '../includes/init.php';

// 检查是否已安装
if (file_exists('../install.lock')) {
    die('系统已经安装，如需重新安装请删除 install.lock 文件');
}

// 检查数据库连接
try {
    $db = Database::getInstance();
    $logger = Logger::getInstance();
} catch (PDOException $e) {
    die('数据库连接失败: ' . $e->getMessage());
}

// 处理安装请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 读取SQL文件
        $sql = file_get_contents(__DIR__ . '/database.sql');
        
        // 分割SQL语句
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        // 执行SQL语句
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $db->query($statement);
            }
        }
        
        // 创建安装锁定文件
        file_put_contents('../install.lock', date('Y-m-d H:i:s'));
        
        // 记录安装日志
        $logger->info('系统安装成功');
        
        // 跳转到首页
        header('Location: ../index.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统安装</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>系统安装</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="install-form">
            <h2>安装说明</h2>
            <ol>
                <li>请确保数据库配置正确</li>
                <li>系统将自动创建必要的数据库表</li>
                <li>安装完成后将自动创建管理员账户</li>
                <li>安装完成后请删除install目录</li>
            </ol>
            
            <form method="post">
                <div class="form-group">
                    <label>数据库主机：</label>
                    <input type="text" value="<?php echo DB_HOST; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>数据库名称：</label>
                    <input type="text" value="<?php echo DB_NAME; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>数据库用户：</label>
                    <input type="text" value="<?php echo DB_USER; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>默认管理员：</label>
                    <input type="text" value="admin" readonly>
                </div>
                
                <div class="form-group">
                    <label>默认密码：</label>
                    <input type="text" value="password" readonly>
                </div>
                
                <button type="submit" class="btn btn-primary">开始安装</button>
            </form>
        </div>
    </div>
    
    <style>
        .install-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .install-form h2 {
            margin-top: 0;
            color: #333;
        }
        
        .install-form ol {
            padding-left: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</body>
</html> 