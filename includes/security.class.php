<?php
class Security {
    private static $instance = null;
    private $csrfToken = null;
    private $ipWhitelist = [];
    private $ipBlacklist = [];
    private $rateLimits = [];
    private $xssCleaner = null;

    private function __construct() {
        $this->init();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init() {
        // 初始化CSRF令牌
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->csrfToken = $_SESSION['csrf_token'];

        // 加载IP白名单和黑名单
        $this->loadIpLists();

        // 初始化XSS清理器
        $this->xssCleaner = new XssCleaner();
    }

    private function loadIpLists() {
        // 从配置文件或数据库加载IP列表
        $this->ipWhitelist = ['127.0.0.1']; // 示例白名单
        $this->ipBlacklist = []; // 示例黑名单
    }

    public function checkCsrfToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!$token || $token !== $this->csrfToken) {
                throw new Exception('CSRF token验证失败');
            }
        }
        return true;
    }

    public function getCsrfToken() {
        return $this->csrfToken;
    }

    public function checkIpAccess() {
        $ip = $this->getClientIp();
        
        // 检查黑名单
        if (in_array($ip, $this->ipBlacklist)) {
            throw new Exception('您的IP已被禁止访问');
        }
        
        // 检查白名单
        if (!empty($this->ipWhitelist) && !in_array($ip, $this->ipWhitelist)) {
            throw new Exception('您的IP不在允许访问列表中');
        }
        
        return true;
    }

    public function checkRateLimit($key, $limit = 60, $period = 60) {
        $ip = $this->getClientIp();
        $cacheKey = "rate_limit:{$key}:{$ip}";
        
        $cache = Cache::getInstance();
        $count = $cache->get($cacheKey) ?? 0;
        
        if ($count >= $limit) {
            throw new Exception('请求过于频繁，请稍后再试');
        }
        
        $cache->set($cacheKey, $count + 1, $period);
        return true;
    }

    public function xssClean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->xssClean($value);
            }
        } else {
            $data = $this->xssCleaner->clean($data);
        }
        return $data;
    }

    public function sqlEscape($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sqlEscape($value);
            }
        } else {
            $data = addslashes($data);
        }
        return $data;
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }

    public function encrypt($data, $key) {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt(
            $data,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($data, $key) {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    public function getClientIp() {
        $ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

class XssCleaner {
    private $evilAttributes = [
        'on\w+',
        'style',
        'xmlns',
        'formaction',
        'form',
        'xlink:href',
        'FSCommand',
        'seekSegmentTime'
    ];

    private $evilHtmlTags = [
        'script',
        'applet',
        'audio',
        'basefont',
        'base',
        'behavior',
        'bgsound',
        'blink',
        'body',
        'embed',
        'expression',
        'form',
        'frameset',
        'frame',
        'head',
        'html',
        'ilayer',
        'iframe',
        'input',
        'isindex',
        'layer',
        'link',
        'meta',
        'object',
        'plaintext',
        'style',
        'textarea',
        'title',
        'video',
        'xml',
        'xss'
    ];

    public function clean($str) {
        // 移除不可见字符
        $str = $this->removeInvisibleCharacters($str);
        
        // 移除危险的HTML标签
        $str = $this->removeEvilTags($str);
        
        // 移除危险的属性
        $str = $this->removeEvilAttributes($str);
        
        // 转义特殊字符
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        
        return $str;
    }

    private function removeInvisibleCharacters($str) {
        return preg_replace('/[\x00-\x1F\x7F]/u', '', $str);
    }

    private function removeEvilTags($str) {
        $str = preg_replace(
            '#<(' . implode('|', $this->evilHtmlTags) . ')(?:[^>]*)?>.*?</\1>#si',
            '',
            $str
        );
        return preg_replace(
            '#<(' . implode('|', $this->evilHtmlTags) . ')(?:[^>]*)?>#si',
            '',
            $str
        );
    }

    private function removeEvilAttributes($str) {
        return preg_replace(
            '#(' . implode('|', $this->evilAttributes) . ')\s*=\s*(["\'])(.*?)\2#si',
            '',
            $str
        );
    }
}
?> 