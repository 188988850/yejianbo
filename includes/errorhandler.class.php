<?php
class ErrorHandler {
    private static $instance = null;
    private $logger;
    private $displayErrors;
    
    private function __construct() {
        $this->logger = Logger::getInstance();
        $this->displayErrors = ini_get('display_errors');
        
        // 设置错误处理函数
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function handleError($errno, $errstr, $errfile, $errline) {
        $errorType = $this->getErrorType($errno);
        $context = [
            'file' => $errfile,
            'line' => $errline,
            'error_type' => $errorType
        ];
        
        switch ($errno) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $this->logger->error($errstr, $context);
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                $this->logger->warning($errstr, $context);
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $this->logger->info($errstr, $context);
                break;
            default:
                $this->logger->debug($errstr, $context);
        }
        
        // 如果是致命错误，显示错误页面
        if ($this->isFatalError($errno)) {
            $this->displayErrorPage($errorType, $errstr, $errfile, $errline);
        }
        
        return true;
    }
    
    public function handleException(Throwable $exception) {
        $context = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
        
        $this->logger->critical($exception->getMessage(), $context);
        $this->displayErrorPage('Exception', $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }
    
    public function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && $this->isFatalError($error['type'])) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
    
    private function getErrorType($errno) {
        $types = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED'
        ];
        
        return $types[$errno] ?? 'Unknown Error';
    }
    
    private function isFatalError($errno) {
        return in_array($errno, [
            E_ERROR,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
            E_PARSE
        ]);
    }
    
    private function displayErrorPage($type, $message, $file, $line) {
        if (!$this->displayErrors) {
            return;
        }
        
        http_response_code(500);
        
        $errorPage = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>系统错误</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .error-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #dc3545;
            margin-top: 0;
        }
        .error-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>系统错误</h1>
        <div class="error-details">
            <p><strong>错误类型：</strong> {$type}</p>
            <p><strong>错误信息：</strong> {$message}</p>
            <p><strong>文件：</strong> {$file}</p>
            <p><strong>行号：</strong> {$line}</p>
        </div>
    </div>
</body>
</html>
HTML;
        
        echo $errorPage;
        exit;
    }
} 