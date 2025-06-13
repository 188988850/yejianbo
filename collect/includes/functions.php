<?php
require_once(__DIR__ . '/../config/config.php');

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $charset = isset($GLOBALS['dbconfig']['charset']) ? $GLOBALS['dbconfig']['charset'] : 'utf8mb4';
            $dsn = "mysql:host={$GLOBALS['dbconfig']['host']};port={$GLOBALS['dbconfig']['port']};dbname={$GLOBALS['dbconfig']['dbname']};charset={$charset}";
            Logger::log("尝试连接数据库: {$dsn}", 'INFO');
            
            $this->conn = new PDO(
                $dsn,
                $GLOBALS['dbconfig']['user'],
                $GLOBALS['dbconfig']['pwd'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            Logger::log("数据库连接成功", 'INFO');
        } catch(PDOException $e) {
            // 如果是字符集不支持，自动降级为utf8重试
            if (strpos($e->getMessage(), 'Unknown character set') !== false && $charset !== 'utf8') {
                try {
                    $dsn = "mysql:host={$GLOBALS['dbconfig']['host']};port={$GLOBALS['dbconfig']['port']};dbname={$GLOBALS['dbconfig']['dbname']};charset=utf8";
                    Logger::log("降级重试数据库连接: {$dsn}", 'INFO');
                    $this->conn = new PDO(
                        $dsn,
                        $GLOBALS['dbconfig']['user'],
                        $GLOBALS['dbconfig']['pwd'],
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
                    Logger::log("数据库连接成功", 'INFO');
                } catch(PDOException $e2) {
                    $error_msg = "数据库连接失败: " . $e2->getMessage() . "\n";
                    $error_msg .= "数据库配置信息:\n";
                    $error_msg .= "主机: {$GLOBALS['dbconfig']['host']}\n";
                    $error_msg .= "端口: {$GLOBALS['dbconfig']['port']}\n";
                    $error_msg .= "数据库: {$GLOBALS['dbconfig']['dbname']}\n";
                    $error_msg .= "用户名: {$GLOBALS['dbconfig']['user']}\n";
                    Logger::log($error_msg, 'ERROR');
                    die("数据库连接失败，请检查配置信息");
                }
            } else {
                $error_msg = "数据库连接失败: " . $e->getMessage() . "\n";
                $error_msg .= "数据库配置信息:\n";
                $error_msg .= "主机: {$GLOBALS['dbconfig']['host']}\n";
                $error_msg .= "端口: {$GLOBALS['dbconfig']['port']}\n";
                $error_msg .= "数据库: {$GLOBALS['dbconfig']['dbname']}\n";
                $error_msg .= "用户名: {$GLOBALS['dbconfig']['user']}\n";
                Logger::log($error_msg, 'ERROR');
                die("数据库连接失败，请检查配置信息");
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

class Logger {
    public static function log($message, $type = 'INFO') {
        $log_file = $GLOBALS['config']['log_path'] . date('Y-m-d') . '.log';
        $log_dir = dirname($log_file);
        
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0777, true);
        }
        
        $log_message = date('Y-m-d H:i:s') . " [{$type}] " . $message . PHP_EOL;
        file_put_contents($log_file, $log_message, FILE_APPEND);
    }
}

class ContentFilter {
    public static function filter($content) {
        global $filter_rules;
        
        // 替换关键词
        foreach ($filter_rules['keywords']['replace'] as $old => $new) {
            $content = str_replace($old, $new, $content);
        }
        
        // 删除关键词
        foreach ($filter_rules['keywords']['remove'] as $keyword) {
            $content = str_replace($keyword, '', $content);
        }
        
        // 内容清理
        if ($filter_rules['content']['remove_html']) {
            $content = strip_tags($content);
        }
        
        if ($filter_rules['content']['remove_script']) {
            $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
        }
        
        if ($filter_rules['content']['remove_style']) {
            $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content);
        }
        
        if ($filter_rules['content']['remove_comments']) {
            $content = preg_replace('/<!--.*?-->/s', '', $content);
        }
        
        return $content;
    }
}

class ImageDownloader {
    public static function download($url, $filename = null) {
        if (!$filename) {
            $filename = basename($url);
        }
        
        $save_path = $GLOBALS['config']['image_path'] . $filename;
        $save_dir = dirname($save_path);
        
        if (!is_dir($save_dir)) {
            mkdir($save_dir, 0777, true);
        }
        
        $ch = curl_init($url);
        $fp = fopen($save_path, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['config']['timeout']);
        curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['config']['user_agent']);
        
        $success = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        
        if (!$success) {
            Logger::log("图片下载失败: {$url}", 'ERROR');
            return false;
        }
        
        return $save_path;
    }
}

class ResourceCollector {
    private $db;
    private $session;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->session = array();
    }
    
    public function login($username, $password) {
        try {
            // 记录登录尝试
            Logger::log("尝试登录: 用户名={$username}", 'INFO');
            
            // 直接查询用户表
            $table_name = $GLOBALS['dbconfig']['dbqz'] . '_user';
            Logger::log("尝试查询用户表: {$table_name}", 'INFO');
            
            // 检查表是否存在
            $check_table = $this->db->query("SHOW TABLES LIKE '{$table_name}'");
            if ($check_table->rowCount() == 0) {
                Logger::log("错误: 用户表 {$table_name} 不存在", 'ERROR');
                return false;
            }
            
            // 获取表结构
            $columns = $this->db->query("SHOW COLUMNS FROM {$table_name}")->fetchAll(PDO::FETCH_COLUMN);
            Logger::log("用户表字段: " . implode(", ", $columns), 'INFO');
            
            // 查询用户
            $stmt = $this->db->prepare("SELECT * FROM {$table_name} WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                Logger::log("找到用户数据: " . json_encode($user, JSON_UNESCAPED_UNICODE), 'INFO');
                
                // 尝试不同的密码验证方式
                $password_verified = false;
                $stored_password = $user['password'];
                
                // 记录密码信息
                Logger::log("输入的密码MD5: " . md5($password), 'INFO');
                Logger::log("存储的密码: " . $stored_password, 'INFO');
                
                // 方式1：直接MD5
                if (md5($password) === $stored_password) {
                    $password_verified = true;
                    Logger::log("密码验证成功：直接MD5匹配", 'INFO');
                }
                
                // 方式2：MD5加盐
                if (!$password_verified && isset($user['salt'])) {
                    if (md5($password . $user['salt']) === $stored_password) {
                        $password_verified = true;
                        Logger::log("密码验证成功：MD5加盐匹配", 'INFO');
                    }
                }
                
                // 方式3：双重MD5
                if (!$password_verified && md5(md5($password)) === $stored_password) {
                    $password_verified = true;
                    Logger::log("密码验证成功：双重MD5匹配", 'INFO');
                }
                
                // 方式4：直接比较
                if (!$password_verified && $password === $stored_password) {
                    $password_verified = true;
                    Logger::log("密码验证成功：直接匹配", 'INFO');
                }
                
                if ($password_verified) {
                    $this->session = array(
                        'uid' => $user['uid'] ?? $user['id'] ?? 0,
                        'username' => $user['username'],
                        'token' => md5(($user['uid'] ?? $user['id'] ?? 0) . time() . rand(1000, 9999))
                    );
                    return $user;
                } else {
                    Logger::log("密码验证失败", 'ERROR');
                }
            } else {
                Logger::log("未找到用户: {$username}", 'ERROR');
            }
            
            return false;
            
        } catch(PDOException $e) {
            Logger::log("登录失败: " . $e->getMessage(), 'ERROR');
            return false;
        }
    }
    
    public function collectResource($url) {
        try {
            // 获取资源详情
            $stmt = $this->db->prepare("SELECT * FROM {$GLOBALS['dbconfig']['dbqz']}_tools WHERE url = ?");
            $stmt->execute([$url]);
            $resource = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$resource) {
                throw new Exception("资源不存在");
            }
            
            // 获取隐藏资源
            $hidden_resources = $this->getHiddenResources($resource['tid']);
            
            // 获取资源分类
            $category = $this->getCategory($resource['cid']);
            
            // 获取资源标签
            $tags = $this->getResourceTags($resource['tid']);
            
            // 处理资源内容
            $content = $this->processContent($resource['content']);
            
            // 下载相关图片
            $images = $this->downloadResourceImages($resource);
            
            return array(
                'basic_info' => array(
                    'tid' => $resource['tid'],
                    'name' => $resource['name'],
                    'description' => $resource['description'],
                    'price' => $resource['price'],
                    'category' => $category,
                    'tags' => $tags,
                    'create_time' => $resource['addtime'],
                    'update_time' => $resource['updatetime']
                ),
                'content' => $content,
                'images' => $images,
                'hidden_resources' => $hidden_resources
            );
        } catch (Exception $e) {
            Logger::log("资源采集失败: " . $e->getMessage(), 'ERROR');
            return false;
        }
    }
    
    private function getHiddenResources($tid) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$GLOBALS['dbconfig']['dbqz']}_hidden_resources WHERE tid = ?");
            $stmt->execute([$tid]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            Logger::log("获取隐藏资源失败: " . $e->getMessage(), 'ERROR');
            return array();
        }
    }
    
    private function getCategory($cid) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$GLOBALS['dbconfig']['dbqz']}_class WHERE cid = ?");
            $stmt->execute([$cid]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            Logger::log("获取分类失败: " . $e->getMessage(), 'ERROR');
            return null;
        }
    }
    
    private function getResourceTags($tid) {
        try {
            $stmt = $this->db->prepare("SELECT t.* FROM {$GLOBALS['dbconfig']['dbqz']}_tags t 
                                      LEFT JOIN {$GLOBALS['dbconfig']['dbqz']}_tool_tags tt ON t.tagid = tt.tagid 
                                      WHERE tt.tid = ?");
            $stmt->execute([$tid]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            Logger::log("获取标签失败: " . $e->getMessage(), 'ERROR');
            return array();
        }
    }
    
    private function processContent($content) {
        // 过滤内容
        $content = ContentFilter::filter($content);
        
        // 处理图片链接
        $content = preg_replace_callback('/<img[^>]+src=([\'"])?([^\'" >]+)/i', function($matches) {
            $img_url = $matches[2];
            $local_path = ImageDownloader::download($img_url);
            return $local_path ? 'src="' . $local_path . '"' : $matches[0];
        }, $content);
        
        return $content;
    }
    
    private function downloadResourceImages($resource) {
        $images = array();
        
        // 下载主图
        if (!empty($resource['img'])) {
            $main_image = ImageDownloader::download($resource['img']);
            if ($main_image) {
                $images['main'] = $main_image;
            }
        }
        
        // 下载其他图片
        if (!empty($resource['images'])) {
            $image_list = json_decode($resource['images'], true);
            if (is_array($image_list)) {
                foreach ($image_list as $key => $url) {
                    $local_path = ImageDownloader::download($url);
                    if ($local_path) {
                        $images[$key] = $local_path;
                    }
                }
            }
        }
        
        return $images;
    }
}
?> 