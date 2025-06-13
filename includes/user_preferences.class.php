<?php
class UserPreferences {
    private $db;
    private $userId;
    private $preferences = [];
    
    public function __construct($userId) {
        $this->db = Database::getInstance();
        $this->userId = $userId;
        $this->loadPreferences();
    }
    
    private function loadPreferences() {
        $sql = "SELECT * FROM user_preferences WHERE user_id = ?";
        $result = $this->db->query($sql, [$this->userId]);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->preferences[$row['key']] = $row['value'];
            }
        }
    }
    
    public function get($key, $default = null) {
        return $this->preferences[$key] ?? $default;
    }
    
    public function set($key, $value) {
        $sql = "INSERT INTO user_preferences (user_id, `key`, value) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE value = ?";
        $this->db->query($sql, [$this->userId, $key, $value, $value]);
        $this->preferences[$key] = $value;
    }
    
    public function getAll() {
        return $this->preferences;
    }
    
    public function delete($key) {
        $sql = "DELETE FROM user_preferences WHERE user_id = ? AND `key` = ?";
        $this->db->query($sql, [$this->userId, $key]);
        unset($this->preferences[$key]);
    }
    
    public function reset() {
        $sql = "DELETE FROM user_preferences WHERE user_id = ?";
        $this->db->query($sql, [$this->userId]);
        $this->preferences = [];
    }
}
?> 