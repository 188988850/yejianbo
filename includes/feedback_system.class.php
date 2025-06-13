<?php
class FeedbackSystem {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function addFeedback($userId, $type, $content, $rating = null) {
        $sql = "INSERT INTO feedback (user_id, type, content, rating, status, created_at) 
                VALUES (?, ?, ?, ?, 'pending', NOW())";
        return $this->db->query($sql, [$userId, $type, $content, $rating]);
    }
    
    public function getFeedback($userId = null, $status = null) {
        $sql = "SELECT f.*, u.username 
                FROM feedback f 
                LEFT JOIN users u ON f.user_id = u.id 
                WHERE 1=1";
        $params = [];
        
        if ($userId) {
            $sql .= " AND f.user_id = ?";
            $params[] = $userId;
        }
        
        if ($status) {
            $sql .= " AND f.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY f.created_at DESC";
        
        $result = $this->db->query($sql, $params);
        $feedback = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $feedback[] = $row;
            }
        }
        
        return $feedback;
    }
    
    public function updateStatus($feedbackId, $status, $response = null) {
        $sql = "UPDATE feedback SET status = ?, response = ?, updated_at = NOW() 
                WHERE id = ?";
        return $this->db->query($sql, [$status, $response, $feedbackId]);
    }
    
    public function getAverageRating() {
        $sql = "SELECT AVG(rating) as avg_rating FROM feedback WHERE rating IS NOT NULL";
        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return round($row['avg_rating'], 1);
        }
        return 0;
    }
}
?> 