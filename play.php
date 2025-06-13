function monitor_log($msg, $data = []) {
    $line = date('Y-m-d H:i:s') . ' ' . $msg . ' ' . json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    file_put_contents(__DIR__.'/logs/monitor.log', $line, FILE_APPEND);
} 