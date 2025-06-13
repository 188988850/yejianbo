<?php
file_put_contents(__DIR__.'/delayed_start.log', date('Y-m-d H:i:s')." start\n", FILE_APPEND);
if ($argc < 2) exit;
$delay = intval($argv[1]);
if ($delay > 0) sleep($delay);
// 启动采集脚本前，更新状态日志
$cronjob_status_file = __DIR__ . '/../logs/cronjob_status.json';
$status = array();
if (file_exists($cronjob_status_file)) {
    $status = json_decode(file_get_contents($cronjob_status_file), true);
}
$status['status'] = 'running';
$status['last_run_time'] = time();
$status['last_run_time_str'] = date('Y-m-d H:i:s');
$status['last_result'] = 'started';
file_put_contents($cronjob_status_file, json_encode($status, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
// 启动采集脚本（请根据你的实际路径调整）
exec('php ' . dirname(__DIR__) . '/template/storenews/collector.php > /dev/null 2>&1 &'); 