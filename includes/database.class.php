<?php
class Database {
    private static $instance = null;
    private $pdo;
    private $logger;
    private $queryCount = 0;
    private $queryLog = [];
    private $lastQueryTime = 0;
    
    private function __construct() {
        $this->logger = Logger::getInstance();
        try {
            $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . $charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $charset
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // 如果是字符集不支持，自动降级为utf8重试
            if (strpos($e->getMessage(), 'Unknown character set') !== false && $charset !== 'utf8') {
                try {
                    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
                    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
                    $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
                } catch (PDOException $e2) {
                    $this->logger->critical('数据库连接失败: ' . $e2->getMessage(), [
                        'error_code' => $e2->getCode(),
                        'error_file' => $e2->getFile(),
                        'error_line' => $e2->getLine()
                    ]);
                    throw $e2;
                }
            } else {
                $this->logger->critical('数据库连接失败: ' . $e->getMessage(), [
                    'error_code' => $e->getCode(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine()
                ]);
                throw $e;
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function query($sql, $params = []) {
        try {
            $startTime = microtime(true);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            // 记录查询性能
            $this->queryCount++;
            $this->lastQueryTime = microtime(true) - $startTime;
            $this->queryLog[] = [
                'sql' => $sql,
                'params' => $params,
                'time' => $this->lastQueryTime
            ];
            
            // 如果查询时间过长，记录警告
            if ($this->lastQueryTime > 1) {
                $this->logger->warning('慢查询检测', [
                    'sql' => $sql,
                    'params' => $params,
                    'execution_time' => $this->lastQueryTime
                ]);
            }
            
            return $stmt;
        } catch (PDOException $e) {
            $this->logger->error('SQL查询失败: ' . $e->getMessage(), [
                'sql' => $sql,
                'params' => $params,
                'error_code' => $e->getCode()
            ]);
            throw $e;
        }
    }
    
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $this->query($sql, array_values($data));
        return $this->pdo->lastInsertId();
    }
    
    public function update($table, $data, $where, $whereParams = []) {
        $fields = array_map(function($field) {
            return "{$field} = ?";
        }, array_keys($data));
        
        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";
        $params = array_merge(array_values($data), $whereParams);
        
        return $this->query($sql, $params)->rowCount();
    }
    
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params)->rowCount();
    }
    
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    public function commit() {
        return $this->pdo->commit();
    }
    
    public function rollBack() {
        return $this->pdo->rollBack();
    }
    
    public function getLastError() {
        return $this->pdo->errorInfo();
    }
    
    public function quote($string) {
        return $this->pdo->quote($string);
    }
    
    public function getQueryCount() {
        return $this->queryCount;
    }
    
    public function getLastQueryTime() {
        return $this->lastQueryTime;
    }
    
    public function getQueryLog() {
        return $this->queryLog;
    }
    
    public function clearQueryLog() {
        $this->queryLog = [];
        $this->queryCount = 0;
    }
    
    public function optimizeTable($table) {
        $sql = "OPTIMIZE TABLE {$table}";
        return $this->query($sql);
    }
    
    public function analyzeTable($table) {
        $sql = "ANALYZE TABLE {$table}";
        return $this->query($sql);
    }
    
    public function repairTable($table) {
        $sql = "REPAIR TABLE {$table}";
        return $this->query($sql);
    }
    
    public function getTableStatus($table) {
        $sql = "SHOW TABLE STATUS LIKE ?";
        return $this->fetch($sql, [$table]);
    }
    
    public function getTableSize($table) {
        $status = $this->getTableStatus($table);
        return $status ? $status['Data_length'] + $status['Index_length'] : 0;
    }
} 