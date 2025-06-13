<?php
class UserStatistics {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function logUserAction($userId, $actionType, $details = null) {
        $sql = "INSERT INTO user_statistics (user_id, action_type, details, created_at) 
                VALUES (?, ?, ?, NOW())";
        return $this->db->query($sql, [$userId, $actionType, $details]);
    }
    
    public function getUserActivity($userId, $startDate = null, $endDate = null) {
        $sql = "SELECT action_type, COUNT(*) as count 
                FROM user_statistics 
                WHERE user_id = ?";
        $params = [$userId];
        
        if ($startDate) {
            $sql .= " AND created_at >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND created_at <= ?";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY action_type";
        
        $result = $this->db->query($sql, $params);
        $stats = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stats[$row['action_type']] = $row['count'];
            }
        }
        
        return $stats;
    }
    
    public function getPopularActions($limit = 10) {
        $sql = "SELECT action_type, COUNT(*) as count 
                FROM user_statistics 
                GROUP BY action_type 
                ORDER BY count DESC 
                LIMIT ?";
        $result = $this->db->query($sql, [$limit]);
        $stats = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stats[$row['action_type']] = $row['count'];
            }
        }
        
        return $stats;
    }
    
    public function getDailyStats($days = 30) {
        $sql = "SELECT DATE(created_at) as date, 
                       COUNT(*) as total_actions,
                       COUNT(DISTINCT user_id) as unique_users
                FROM user_statistics 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        $result = $this->db->query($sql, [$days]);
        $stats = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stats[$row['date']] = $row;
            }
        }
        
        return $stats;
    }
    
    public function clearOldStats($days = 90) {
        $sql = "DELETE FROM user_statistics 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        return $this->db->query($sql, [$days]);
    }
}
?> 