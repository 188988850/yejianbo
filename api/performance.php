<?php
require_once '../includes/init.php';
require_once '../includes/performance.class.php';

// 检查管理员权限
if (!User::isAdmin()) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => '无权访问']);
    exit;
}

$performance = Performance::getInstance();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'metrics':
        // 获取性能指标
        $metrics = $performance->getPerformanceMetrics();
        echo json_encode($metrics);
        break;
        
    case 'optimization':
        // 获取优化建议
        $report = $performance->getOptimizationReport();
        echo json_encode($report);
        break;
        
    default:
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => '无效的操作']);
        break;
}
?> 