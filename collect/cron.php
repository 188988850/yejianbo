<?php
// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 记录开始时间
$start_time = time();

// 引入主程序
require_once(__DIR__ . '/collect.php');

// 记录结束时间
$end_time = time();

// 计算运行时间
$run_time = $end_time - $start_time;

// 记录计划任务执行日志
$cron_log = __DIR__ . '/data/logs/cron_' . date('Y-m-d') . '.log';
$log_message = date('Y-m-d H:i:s') . " 计划任务执行完成，耗时：{$run_time}秒\n";
file_put_contents($cron_log, $log_message, FILE_APPEND);

// 清理旧日志文件（保留30天）
$log_dir = __DIR__ . '/data/logs';
$files = glob($log_dir . '/cron_*.log');
foreach ($files as $file) {
    if (time() - filemtime($file) > 30 * 24 * 3600) {
        unlink($file);
    }
}
?> 