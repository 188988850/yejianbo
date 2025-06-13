<?php
class Logger {
    private static $instance = null;
    private $logFile;
    private $logLevel;
    private $logLevels = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4
    ];
    
    private function __construct() {
        $this->logFile = dirname(__DIR__) . '/logs/app.log';
        $this->logLevel = $this->logLevels['INFO'];
        
        // 确保日志目录存在
        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function setLogLevel($level) {
        if (isset($this->logLevels[$level])) {
            $this->logLevel = $this->logLevels[$level];
        }
    }
    
    public function debug($message, $context = []) {
        $this->log('DEBUG', $message, $context);
    }
    
    public function info($message, $context = []) {
        $this->log('INFO', $message, $context);
    }
    
    public function warning($message, $context = []) {
        $this->log('WARNING', $message, $context);
    }
    
    public function error($message, $context = []) {
        $this->log('ERROR', $message, $context);
    }
    
    public function critical($message, $context = []) {
        $this->log('CRITICAL', $message, $context);
    }
    
    private function log($level, $message, $context = []) {
        if ($this->logLevels[$level] < $this->logLevel) {
            return;
        }
        
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ];
        
        $logLine = json_encode($logEntry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
        
        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    public function getLogs($level = null, $limit = 100) {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $logs = [];
        $handle = fopen($this->logFile, 'r');
        
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $log = json_decode($line, true);
                
                if ($level === null || $log['level'] === $level) {
                    $logs[] = $log;
                }
                
                if (count($logs) >= $limit) {
                    array_shift($logs);
                }
            }
            fclose($handle);
        }
        
        return array_reverse($logs);
    }
    
    public function clearLogs() {
        if (file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }
    }
} 