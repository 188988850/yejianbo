<?php
class ActivityLog {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function log($userId, $action, $details = null, $ip = null) {
        $sql = "INSERT INTO activity_logs (user_id, action, details, ip, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->query($sql, [$userId, $action, $details, $ip]);
    }
    
    public function getUserLogs($userId, $limit = 50) {
        $sql = "SELECT * FROM activity_logs 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?";
        $result = $this->db->query($sql, [$userId, $limit]);
        $logs = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $logs[] = $row;
            }
        }
        
        return $logs;
    }
    
    public function getRecentLogs($limit = 100) {
        $sql = "SELECT l.*, u.username 
                FROM activity_logs l 
                LEFT JOIN users u ON l.user_id = u.id 
                ORDER BY l.created_at DESC 
                LIMIT ?";
        $result = $this->db->query($sql, [$limit]);
        $logs = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $logs[] = $row;
            }
        }
        
        return $logs;
    }
    
    public function clearOldLogs($days = 30) {
        $sql = "DELETE FROM activity_logs 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        return $this->db->query($sql, [$days]);
    }
}
?> 