<?php
class UserSettings {
    private $userId;
    private $settings = [];
    private $db;
    
    public function __construct($userId) {
        $this->userId = $userId;
        $this->db = Database::getInstance();
        $this->loadSettings();
    }
    
    public function get($key, $default = null) {
        return $this->settings[$key] ?? $default;
    }
    
    public function set($key, $value) {
        $this->settings[$key] = $value;
        $this->saveSettings();
    }
    
    private function loadSettings() {
        $sql = "SELECT settings FROM user_settings WHERE user_id = ?";
        $result = $this->db->query($sql, [$this->userId]);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->settings = json_decode($row['settings'], true) ?: [];
        }
    }
    
    private function saveSettings() {
        $sql = "INSERT INTO user_settings (user_id, settings) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE settings = ?";
        $settingsJson = json_encode($this->settings);
        $this->db->query($sql, [$this->userId, $settingsJson, $settingsJson]);
    }
    
    public function getAllSettings() {
        return $this->settings;
    }
    
    public function resetSettings() {
        $this->settings = [];
        $this->saveSettings();
    }
}
?> 