<?php
class ErrorHandler {
    private static $instance = null;
    private $logFile;
    private $errorTypes = [
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Strict',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated'
    ];

    private function __construct() {
        $this->logFile = dirname(__FILE__) . '/../logs/error.log';
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleFatalError']);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function handleError($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $errorType = isset($this->errorTypes[$errno]) ? $this->errorTypes[$errno] : 'Unknown Error';
        $message = sprintf(
            "[%s] %s: %s in %s on line %d\n",
            date('Y-m-d H:i:s'),
            $errorType,
            $errstr,
            $errfile,
            $errline
        );

        $this->logError($message);
        
        if ($errno == E_USER_ERROR) {
            $this->sendErrorEmail($message);
        }

        return true;
    }

    public function handleException($exception) {
        $message = sprintf(
            "[%s] Exception: %s in %s on line %d\nStack trace:\n%s\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        $this->logError($message);
        $this->sendErrorEmail($message);

        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            echo "<pre>" . $message . "</pre>";
        } else {
            echo "An error occurred. Please try again later.";
        }
    }

    public function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $message = sprintf(
                "[%s] Fatal Error: %s in %s on line %d\n",
                date('Y-m-d H:i:s'),
                $error['message'],
                $error['file'],
                $error['line']
            );

            $this->logError($message);
            $this->sendErrorEmail($message);
        }
    }

    private function logError($message) {
        error_log($message, 3, $this->logFile);
    }

    private function sendErrorEmail($message) {
        if (defined('ERROR_EMAIL') && ERROR_EMAIL) {
            $headers = 'From: ' . SITE_EMAIL . "\r\n" .
                      'X-Mailer: PHP/' . phpversion();
            mail(ERROR_EMAIL, 'Error Report from ' . SITE_NAME, $message, $headers);
        }
    }
}

// 初始化错误处理器
ErrorHandler::getInstance();
?> 