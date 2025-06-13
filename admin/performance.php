<?php
require_once '../includes/init.php';
require_once '../includes/performance.class.php';

// 检查管理员权限
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// 获取性能指标
$performance = Performance::getInstance();
$metrics = $performance->getMetrics();
$reports = $performance->getOptimizationReports();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>性能监控 - 管理后台</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <h1>性能监控</h1>
        
        <!-- 性能指标卡片 -->
        <div class="metrics-grid">
            <div class="metric-card">
                <h3>执行时间</h3>
                <div class="metric-value"><?php echo number_format($metrics['execution_time'], 2); ?> ms</div>
                <div class="metric-description">页面加载时间</div>
            </div>
            <div class="metric-card">
                <h3>内存使用</h3>
                <div class="metric-value"><?php echo formatBytes($metrics['memory_usage']); ?></div>
                <div class="metric-description">当前内存占用</div>
            </div>
            <div class="metric-card">
                <h3>SQL查询次数</h3>
                <div class="metric-value"><?php echo $metrics['sql_queries']; ?></div>
                <div class="metric-description">数据库查询次数</div>
            </div>
            <div class="metric-card">
                <h3>API调用次数</h3>
                <div class="metric-value"><?php echo $metrics['api_calls']; ?></div>
                <div class="metric-description">外部API调用次数</div>
            </div>
            <div class="metric-card">
                <h3>缓存命中率</h3>
                <div class="metric-value"><?php echo number_format($metrics['cache_hit_rate'], 2); ?>%</div>
                <div class="metric-description">缓存效率</div>
            </div>
            <div class="metric-card">
                <h3>峰值内存</h3>
                <div class="metric-value"><?php echo formatBytes($metrics['peak_memory']); ?></div>
                <div class="metric-description">最大内存使用</div>
            </div>
        </div>

        <!-- 性能图表 -->
        <div class="chart-container">
            <h2>性能趋势</h2>
            <canvas id="performanceChart"></canvas>
        </div>

        <!-- 优化建议 -->
        <div class="optimization-section">
            <h2>优化建议</h2>
            <div class="suggestions">
                <?php foreach ($reports as $report): ?>
                <div class="suggestion-item">
                    <h3><?php echo htmlspecialchars($report['type']); ?></h3>
                    <p><?php echo htmlspecialchars($report['description']); ?></p>
                    <div class="priority" data-priority="<?php echo htmlspecialchars($report['priority']); ?>">
                        优先级: <?php echo htmlspecialchars($report['priority']); ?>
                    </div>
                    <?php if (!empty($report['details'])): ?>
                    <div class="details">
                        <pre><?php echo json_encode($report['details'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // 性能图表配置
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['执行时间', '内存使用', 'SQL查询', 'API调用', '缓存命中率', '峰值内存'],
                datasets: [{
                    label: '性能指标',
                    data: [
                        <?php echo $metrics['execution_time']; ?>,
                        <?php echo $metrics['memory_usage']; ?>,
                        <?php echo $metrics['sql_queries']; ?>,
                        <?php echo $metrics['api_calls']; ?>,
                        <?php echo $metrics['cache_hit_rate']; ?>,
                        <?php echo $metrics['peak_memory']; ?>
                    ],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatValue(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return formatValue(context.raw);
                            }
                        }
                    }
                }
            }
        });

        // 格式化数值显示
        function formatValue(value) {
            if (value >= 1024 * 1024 * 1024) {
                return (value / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
            } else if (value >= 1024 * 1024) {
                return (value / (1024 * 1024)).toFixed(2) + ' MB';
            } else if (value >= 1024) {
                return (value / 1024).toFixed(2) + ' KB';
            } else {
                return value.toFixed(2);
            }
        }
    </script>
</body>
</html> 