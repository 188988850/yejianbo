<?php
require_once '../includes/init.php';

// 检查管理员权限
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// 获取日志级别
$level = $_GET['level'] ?? null;
$limit = min((int)($_GET['limit'] ?? 100), 1000);

// 获取日志
$logger = Logger::getInstance();
$logs = $logger->getLogs($level, $limit);

// 处理清除日志请求
if (isset($_POST['clear_logs']) && $_POST['clear_logs'] === '1') {
    $logger->clearLogs();
    header('Location: logs.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统日志 - 管理后台</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>系统日志</h1>
        
        <!-- 日志过滤器 -->
        <div class="log-filters">
            <form method="get" class="filter-form">
                <div class="form-group">
                    <label for="level">日志级别：</label>
                    <select name="level" id="level">
                        <option value="">全部</option>
                        <option value="DEBUG" <?php echo $level === 'DEBUG' ? 'selected' : ''; ?>>调试</option>
                        <option value="INFO" <?php echo $level === 'INFO' ? 'selected' : ''; ?>>信息</option>
                        <option value="WARNING" <?php echo $level === 'WARNING' ? 'selected' : ''; ?>>警告</option>
                        <option value="ERROR" <?php echo $level === 'ERROR' ? 'selected' : ''; ?>>错误</option>
                        <option value="CRITICAL" <?php echo $level === 'CRITICAL' ? 'selected' : ''; ?>>严重</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="limit">显示条数：</label>
                    <input type="number" name="limit" id="limit" value="<?php echo $limit; ?>" min="1" max="1000">
                </div>
                <button type="submit" class="btn btn-primary">筛选</button>
            </form>
            
            <form method="post" class="clear-form" onsubmit="return confirm('确定要清除所有日志吗？');">
                <input type="hidden" name="clear_logs" value="1">
                <button type="submit" class="btn btn-danger">清除日志</button>
            </form>
        </div>
        
        <!-- 日志列表 -->
        <div class="log-list">
            <?php if (empty($logs)): ?>
                <div class="no-logs">没有找到日志记录</div>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <div class="log-entry log-level-<?php echo strtolower($log['level']); ?>">
                        <div class="log-header">
                            <span class="log-timestamp"><?php echo htmlspecialchars($log['timestamp']); ?></span>
                            <span class="log-level"><?php echo htmlspecialchars($log['level']); ?></span>
                            <span class="log-ip"><?php echo htmlspecialchars($log['ip']); ?></span>
                        </div>
                        <div class="log-message"><?php echo htmlspecialchars($log['message']); ?></div>
                        <?php if (!empty($log['context'])): ?>
                            <div class="log-context">
                                <pre><?php echo htmlspecialchars(json_encode($log['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
        .log-filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .filter-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .form-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .log-list {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
        }
        
        .log-entry {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #ccc;
        }
        
        .log-level-DEBUG {
            border-left-color: #6c757d;
        }
        
        .log-level-INFO {
            border-left-color: #17a2b8;
        }
        
        .log-level-WARNING {
            border-left-color: #ffc107;
        }
        
        .log-level-ERROR {
            border-left-color: #dc3545;
        }
        
        .log-level-CRITICAL {
            border-left-color: #721c24;
        }
        
        .log-header {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .log-message {
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .log-context {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
        }
        
        .no-logs {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</body>
</html> 