<?php
class HelpCenter {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getCategories() {
        $sql = "SELECT * FROM help_categories WHERE status = 1 ORDER BY sort_order";
        $result = $this->db->query($sql);
        $categories = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        return $categories;
    }
    
    public function getArticles($categoryId = null, $search = null) {
        $sql = "SELECT a.*, c.name as category_name 
                FROM help_articles a 
                LEFT JOIN help_categories c ON a.category_id = c.id 
                WHERE a.status = 1";
        $params = [];
        
        if ($categoryId) {
            $sql .= " AND a.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($search) {
            $sql .= " AND (a.title LIKE ? OR a.content LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $sql .= " ORDER BY a.sort_order, a.created_at DESC";
        
        $result = $this->db->query($sql, $params);
        $articles = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $articles[] = $row;
            }
        }
        
        return $articles;
    }
    
    public function getArticle($id) {
        $sql = "SELECT a.*, c.name as category_name 
                FROM help_articles a 
                LEFT JOIN help_categories c ON a.category_id = c.id 
                WHERE a.id = ? AND a.status = 1";
        $result = $this->db->query($sql, [$id]);
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        
        return null;
    }
    
    public function addViewCount($articleId) {
        $sql = "UPDATE help_articles SET view_count = view_count + 1 WHERE id = ?";
        return $this->db->query($sql, [$articleId]);
    }
    
    public function search($keyword) {
        $sql = "SELECT a.*, c.name as category_name 
                FROM help_articles a 
                LEFT JOIN help_categories c ON a.category_id = c.id 
                WHERE a.status = 1 AND 
                      (a.title LIKE ? OR a.content LIKE ? OR a.keywords LIKE ?)
                ORDER BY a.sort_order, a.created_at DESC";
        
        $keyword = "%$keyword%";
        $result = $this->db->query($sql, [$keyword, $keyword, $keyword]);
        $articles = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $articles[] = $row;
            }
        }
        
        return $articles;
    }
}
?> 