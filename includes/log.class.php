<?php
class Log {
    private static $instance = null;
    private $logPath;
    private $logLevel;
    private $logFile;
    private $maxFileSize;
    private $maxFiles;
    private $logLevels = [
        'debug' => 0,
        'info' => 1,
        'notice' => 2,
        'warning' => 3,
        'error' => 4,
        'critical' => 5,
        'alert' => 6,
        'emergency' => 7
    ];

    private function __construct() {
        $this->logPath = dirname(__FILE__) . '/../logs/';
        $this->logLevel = 'info';
        $this->maxFileSize = 10 * 1024 * 1024; // 10MB
        $this->maxFiles = 10;
        $this->init();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init() {
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0777, true);
        }
        $this->logFile = $this->logPath . date('Y-m-d') . '.log';
    }

    public function setLogLevel($level) {
        if (isset($this->logLevels[$level])) {
            $this->logLevel = $level;
        }
    }

    public function setMaxFileSize($size) {
        $this->maxFileSize = $size;
    }

    public function setMaxFiles($count) {
        $this->maxFiles = $count;
    }

    public function debug($message, $context = []) {
        $this->log('debug', $message, $context);
    }

    public function info($message, $context = []) {
        $this->log('info', $message, $context);
    }

    public function notice($message, $context = []) {
        $this->log('notice', $message, $context);
    }

    public function warning($message, $context = []) {
        $this->log('warning', $message, $context);
    }

    public function error($message, $context = []) {
        $this->log('error', $message, $context);
    }

    public function critical($message, $context = []) {
        $this->log('critical', $message, $context);
    }

    public function alert($message, $context = []) {
        $this->log('alert', $message, $context);
    }

    public function emergency($message, $context = []) {
        $this->log('emergency', $message, $context);
    }

    private function log($level, $message, $context = []) {
        if ($this->logLevels[$level] < $this->logLevels[$this->logLevel]) {
            return;
        }

        $logEntry = $this->formatLogEntry($level, $message, $context);
        $this->writeLog($logEntry);
    }

    private function formatLogEntry($level, $message, $context) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $url = $_SERVER['REQUEST_URI'] ?? 'Unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        $logEntry = sprintf(
            "[%s] [%s] [%s] [%s] [%s] %s",
            $timestamp,
            strtoupper($level),
            $ip,
            $url,
            $userAgent,
            $message
        );

        if (!empty($context)) {
            $logEntry .= "\nContext: " . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $logEntry . "\n";
    }

    private function writeLog($logEntry) {
        if (file_exists($this->logFile) && filesize($this->logFile) >= $this->maxFileSize) {
            $this->rotateLogs();
        }

        error_log($logEntry, 3, $this->logFile);
    }

    private function rotateLogs() {
        $files = glob($this->logPath . '*.log');
        if (count($files) >= $this->maxFiles) {
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            unlink($files[0]);
        }

        $date = date('Y-m-d');
        $newFile = $this->logPath . $date . '.log';
        if (file_exists($newFile)) {
            $i = 1;
            while (file_exists($newFile . '.' . $i)) {
                $i++;
            }
            $newFile .= '.' . $i;
        }
        rename($this->logFile, $newFile);
    }

    public function getLogContent($lines = 100) {
        if (!file_exists($this->logFile)) {
            return '';
        }

        $content = '';
        $handle = fopen($this->logFile, 'r');
        if ($handle) {
            $pos = -2;
            $currentLine = '';
            $lineCount = 0;

            while ($lineCount < $lines) {
                $char = fgetc($handle);
                if ($char === false) {
                    break;
                }
                if ($char === "\n") {
                    $lineCount++;
                    $content = $currentLine . "\n" . $content;
                    $currentLine = '';
                } else {
                    $currentLine = $char . $currentLine;
                }
                $pos--;
            }
            fclose($handle);
        }

        return $content;
    }

    public function clearLog() {
        if (file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }
    }
} 