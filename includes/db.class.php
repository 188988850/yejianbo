<?php
class DB {
    private static $instance = null;
    private $connections = [];
    private $currentConnection = null;
    private $config = null;
    private $queryLog = [];
    private $lastQuery = null;
    private $transactionLevel = 0;

    private function __construct($config) {
        $this->config = $config;
    }

    public static function getInstance($config) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public static function getDB() {
        global $DB;
        if (!isset($DB) || !is_object($DB)) {
            global $dbconfig;
            $DB = self::getInstance($dbconfig);
        }
        return $DB;
    }

    private function getConnection() {
        if ($this->currentConnection === null) {
            $charset = isset($this->config['charset']) ? $this->config['charset'] : 'utf8mb4';
            try {
                $this->currentConnection = new PDO(
                    "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['dbname']};charset={$charset}",
                    $this->config['user'],
                    $this->config['pwd'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_PERSISTENT => isset($this->config['pconnect']) ? $this->config['pconnect'] : false
                    ]
                );
                $this->connections[] = $this->currentConnection;
            } catch (PDOException $e) {
                // 如果是字符集不支持，自动降级为utf8重试
                if (strpos($e->getMessage(), 'Unknown character set') !== false && $charset !== 'utf8') {
                    try {
                        $this->currentConnection = new PDO(
                            "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['dbname']};charset=utf8",
                            $this->config['user'],
                            $this->config['pwd'],
                            [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::ATTR_EMULATE_PREPARES => false,
                                PDO::ATTR_PERSISTENT => isset($this->config['pconnect']) ? $this->config['pconnect'] : false
                            ]
                        );
                        $this->connections[] = $this->currentConnection;
                    } catch (PDOException $e2) {
                        exit('<div style="color:red;padding:2em;">数据库连接失败: '.htmlspecialchars($e2->getMessage()).'</div>');
                    }
                } else {
                    exit('<div style="color:red;padding:2em;">数据库连接失败: '.htmlspecialchars($e->getMessage()).'</div>');
                }
            }
        }
        return $this->currentConnection;
    }

    public function query($sql, $params = []) {
        $start = microtime(true);
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            $this->lastQuery = $stmt;
            
            $this->queryLog[] = [
                'sql' => $sql,
                'params' => $params,
                'time' => microtime(true) - $start
            ];
            
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception('SQL执行失败: ' . $e->getMessage() . "\nSQL: " . $sql);
        }
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchColumn($sql, $params = [], $column = 0) {
        return $this->query($sql, $params)->fetchColumn($column);
    }

    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO {$this->config['dbqz']}_{$table} (" . implode(',', $fields) . ") 
                VALUES (" . implode(',', $placeholders) . ")";
        
        $this->query($sql, array_values($data));
        return $this->getConnection()->lastInsertId();
    }

    public function update($table, $data, $where, $whereParams = []) {
        $fields = array_keys($data);
        $set = implode('=?,', $fields) . '=?';
        
        $sql = "UPDATE {$this->config['dbqz']}_{$table} SET {$set} WHERE {$where}";
        
        $params = array_merge(array_values($data), $whereParams);
        return $this->query($sql, $params)->rowCount();
    }

    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$this->config['dbqz']}_{$table} WHERE {$where}";
        return $this->query($sql, $params)->rowCount();
    }

    public function beginTransaction() {
        if ($this->transactionLevel === 0) {
            $this->getConnection()->beginTransaction();
        }
        $this->transactionLevel++;
    }

    public function commit() {
        $this->transactionLevel--;
        if ($this->transactionLevel === 0) {
            $this->getConnection()->commit();
        }
    }

    public function rollback() {
        $this->transactionLevel--;
        if ($this->transactionLevel === 0) {
            $this->getConnection()->rollBack();
        }
    }

    public function getQueryLog() {
        return $this->queryLog;
    }

    public function getLastQuery() {
        return $this->lastQuery;
    }

    public function quote($string) {
        return $this->getConnection()->quote($string);
    }

    public function close() {
        foreach ($this->connections as $connection) {
            $connection = null;
        }
        $this->connections = [];
        $this->currentConnection = null;
    }

    public function __destruct() {
        $this->close();
    }

    public function getPrefix() {
        return $this->config['dbqz'] . '_';
    }
}
?> 