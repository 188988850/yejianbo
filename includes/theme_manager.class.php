<?php
class ThemeManager {
    private $themes = [];
    private $currentTheme;
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->loadThemes();
    }
    
    public function getThemes() {
        return $this->themes;
    }
    
    public function setTheme($themeName) {
        if (isset($this->themes[$themeName])) {
            $this->currentTheme = $themeName;
            $this->applyTheme();
            return true;
        }
        return false;
    }
    
    private function loadThemes() {
        $sql = "SELECT * FROM themes WHERE status = 1";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->themes[$row['name']] = $row;
            }
        }
    }
    
    private function applyTheme() {
        if (isset($this->themes[$this->currentTheme])) {
            $theme = $this->themes[$this->currentTheme];
            // 应用主题CSS
            echo '<link rel="stylesheet" href="' . $theme['css_path'] . '">';
            // 应用主题JS
            echo '<script src="' . $theme['js_path'] . '"></script>';
        }
    }
    
    public function getCurrentTheme() {
        return $this->currentTheme;
    }
    
    public function addTheme($name, $cssPath, $jsPath = '') {
        $sql = "INSERT INTO themes (name, css_path, js_path, status) VALUES (?, ?, ?, 1)";
        return $this->db->query($sql, [$name, $cssPath, $jsPath]);
    }
}
?> 