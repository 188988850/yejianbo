<?php
class NotificationSystem {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function add($type, $message, $userId, $data = []) {
        $sql = "INSERT INTO notifications (type, message, user_id, data, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $dataJson = json_encode($data);
        return $this->db->query($sql, [$type, $message, $userId, $dataJson]);
    }
    
    public function getUnread($userId) {
        $sql = "SELECT * FROM notifications 
                WHERE user_id = ? AND is_read = 0 
                ORDER BY created_at DESC";
        $result = $this->db->query($sql, [$userId]);
        $notifications = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['data'] = json_decode($row['data'], true);
                $notifications[] = $row;
            }
        }
        return $notifications;
    }
    
    public function markAsRead($notificationId) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
        return $this->db->query($sql, [$notificationId]);
    }
    
    public function markAllAsRead($userId) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
        return $this->db->query($sql, [$userId]);
    }
    
    public function getCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM notifications 
                WHERE user_id = ? AND is_read = 0";
        $result = $this->db->query($sql, [$userId]);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['count'];
        }
        return 0;
    }
}
?> 