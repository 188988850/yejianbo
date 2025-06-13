<?php
class SystemMonitor {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function logSystemStatus() {
        $status = [
            'cpu_usage' => $this->getCPUUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
            'database_size' => $this->getDatabaseSize(),
            'active_users' => $this->getActiveUsers(),
            'request_count' => $this->getRequestCount(),
            'error_count' => $this->getErrorCount()
        ];
        
        $sql = "INSERT INTO system_status (status_data, created_at) VALUES (?, NOW())";
        return $this->db->query($sql, [json_encode($status)]);
    }
    
    private function getCPUUsage() {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return $load[0];
        }
        return null;
    }
    
    private function getMemoryUsage() {
        if (function_exists('memory_get_usage')) {
            return memory_get_usage(true);
        }
        return null;
    }
    
    private function getDiskUsage() {
        return disk_free_space('/');
    }
    
    private function getDatabaseSize() {
        $sql = "SELECT SUM(data_length + index_length) as size 
                FROM information_schema.TABLES 
                WHERE table_schema = ?";
        $result = $this->db->query($sql, [DB_NAME]);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['size'];
        }
        return 0;
    }
    
    private function getActiveUsers() {
        $sql = "SELECT COUNT(DISTINCT user_id) as count 
                FROM user_sessions 
                WHERE last_activity > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['count'];
        }
        return 0;
    }
    
    private function getRequestCount() {
        $sql = "SELECT COUNT(*) as count FROM request_logs 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['count'];
        }
        return 0;
    }
    
    private function getErrorCount() {
        $sql = "SELECT COUNT(*) as count FROM error_logs 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['count'];
        }
        return 0;
    }
    
    public function getSystemStatus($hours = 24) {
        $sql = "SELECT * FROM system_status 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL ? HOUR)
                ORDER BY created_at DESC";
        $result = $this->db->query($sql, [$hours]);
        $status = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $status[] = json_decode($row['status_data'], true);
            }
        }
        
        return $status;
    }
    
    public function clearOldStatus($days = 7) {
        $sql = "DELETE FROM system_status 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        return $this->db->query($sql, [$days]);
    }
}
?> 