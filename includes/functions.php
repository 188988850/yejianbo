<?php
// 安全过滤函数
function daddslashes($string) {
    if(!is_array($string)) {
        return addslashes(trim($string));
    }
    foreach($string as $key => $val) {
        $string[$key] = daddslashes($val);
    }
    return $string;
}

// XSS过滤
function xssClean($string) {
    if(!is_array($string)) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }
    foreach($string as $key => $val) {
        $string[$key] = xssClean($val);
    }
    return $string;
}

// SQL注入过滤
function sqlClean($string) {
    if(!is_array($string)) {
        return preg_replace('/[\'\"\\\;\-\-\#]/', '', trim($string));
    }
    foreach($string as $key => $val) {
        $string[$key] = sqlClean($val);
    }
    return $string;
}

// 获取客户端IP
function getClientIP() {
    $ip = $_SERVER['HTTP_CLIENT_IP'] ?? 
          $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
          $_SERVER['HTTP_X_FORWARDED'] ?? 
          $_SERVER['HTTP_FORWARDED_FOR'] ?? 
          $_SERVER['HTTP_FORWARDED'] ?? 
          $_SERVER['REMOTE_ADDR'] ?? 
          'Unknown';
    return $ip;
}

// 检查请求来源
function checkRefererHost() {
    if(!isset($_SERVER['HTTP_REFERER'])) return false;
    $referer = parse_url($_SERVER['HTTP_REFERER']);
    $host = parse_url(SITE_URL);
    return $referer['host'] == $host['host'];
}

// 生成订单号
function createOrderNo() {
    return date('YmdHis').rand(1000,9999);
}

// 生成随机字符串
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// 记录日志
function writeLog($type, $action, $data = null) {
    global $DB;
    $ip = getClientIP();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $url = getCurrentUrl();
    
    $sql = "INSERT INTO {$DB->table('logs')} (`type`,`action`,`data`,`ip`,`user_agent`,`url`,`date`) VALUES (:type,:action,:data,:ip,:user_agent,:url,NOW())";
    $DB->exec($sql, [
        ':type' => $type,
        ':action' => $action,
        ':data' => is_array($data) ? json_encode($data) : $data,
        ':ip' => $ip,
        ':user_agent' => $userAgent,
        ':url' => $url
    ]);
}

// 检查用户权限
function checkPermission($user, $permission) {
    global $DB;
    $row = $DB->getRow("SELECT * FROM {$DB->table('site')} WHERE user=:user LIMIT 1", [':user'=>$user]);
    if(!$row) return false;
    return $row['power'] >= $permission;
}

// 更新用户余额
function updateUserMoney($zid, $money, $type = true) {
    global $DB;
    $DB->beginTransaction();
    try {
        $sql = $type ? 
            "UPDATE {$DB->table('site')} SET rmb=rmb+:money WHERE zid=:zid" : 
            "UPDATE {$DB->table('site')} SET rmb=rmb-:money WHERE zid=:zid AND rmb>=:money";
        if($DB->exec($sql, [':money'=>$money, ':zid'=>$zid])) {
            $DB->commit();
            return true;
        }
        $DB->rollBack();
        return false;
    } catch(Exception $e) {
        $DB->rollBack();
        writeLog('error', 'updateUserMoney', $e->getMessage());
        return false;
    }
}

// 检查商品库存
function checkStock($tid, $num = 1) {
    global $DB;
    $tool = $DB->getRow("SELECT * FROM {$DB->table('tools')} WHERE tid=:tid LIMIT 1", [':tid'=>$tid]);
    if(!$tool) return false;
    if($tool['is_curl'] == 4) {
        $count = $DB->getColumn("SELECT COUNT(*) FROM {$DB->table('faka')} WHERE tid=:tid AND orderid=0", [':tid'=>$tid]);
        return $count >= $num;
    } else {
        return $tool['stock'] >= $num;
    }
}

// 处理订单
function processOrder($order) {
    global $DB;
    $DB->beginTransaction();
    try {
        $sql = "INSERT INTO {$DB->table('orders')} (`tid`,`zid`,`userid`,`input`,`num`,`money`,`status`,`addtime`) VALUES (:tid,:zid,:userid,:input,:num,:money,0,NOW())";
        if($DB->exec($sql, [
            ':tid' => $order['tid'],
            ':zid' => $order['zid'],
            ':userid' => $order['userid'],
            ':input' => $order['input'],
            ':num' => $order['num'],
            ':money' => $order['money']
        ])) {
            $orderid = $DB->lastInsertId();
            $DB->commit();
            return $orderid;
        }
        $DB->rollBack();
        return false;
    } catch(Exception $e) {
        $DB->rollBack();
        writeLog('error', 'processOrder', $e->getMessage());
        return false;
    }
}

// 获取系统配置
function getConfig($key = null) {
    global $DB;
    static $config = null;
    if($config === null) {
        $config = [];
        $rs = $DB->query("SELECT * FROM {$DB->table('config')}");
        while($row = $rs->fetch()) {
            $config[$row['k']] = $row['v'];
        }
    }
    return $key ? ($config[$key] ?? null) : $config;
}

// 缓存函数
function getCache($key) {
    global $DB;
    $row = $DB->getRow("SELECT * FROM {$DB->table('cache')} WHERE `key`=:key AND expire_time>NOW()", [':key'=>$key]);
    return $row ? json_decode($row['value'], true) : null;
}

function setCache($key, $value, $expire = 3600) {
    global $DB;
    $value = json_encode($value);
    $sql = "REPLACE INTO {$DB->table('cache')} (`key`,`value`,`expire_time`) VALUES (:key,:value,DATE_ADD(NOW(),INTERVAL :expire SECOND))";
    return $DB->exec($sql, [':key'=>$key, ':value'=>$value, ':expire'=>$expire]);
}

// 检查更新
function checkUpdate() {
    $cache = getCache('update_check');
    if($cache !== null) return $cache;
    
    $url = 'https://api.example.com/check-update';
    $data = [
        'version' => VERSION,
        'domain' => $_SERVER['HTTP_HOST']
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($result, true);
    setCache('update_check', $result, 3600);
    return $result;
}

// 获取当前URL
function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// 格式化时间
function formatTime($time) {
    $diff = time() - $time;
    if($diff < 60) {
        return $diff . '秒前';
    } elseif($diff < 3600) {
        return floor($diff/60) . '分钟前';
    } elseif($diff < 86400) {
        return floor($diff/3600) . '小时前';
    } elseif($diff < 2592000) {
        return floor($diff/86400) . '天前';
    } else {
        return date('Y-m-d H:i:s', $time);
    }
}

// 格式化金额
function formatMoney($money) {
    return number_format($money, 2, '.', '');
}

// 检查手机号
function checkMobile($mobile) {
    return preg_match('/^1[3-9]\d{9}$/', $mobile);
}

// 检查邮箱
function checkEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// 生成密码哈希
function generatePasswordHash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// 验证密码
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// 发送邮件
function sendMail($to, $subject, $message) {
    $headers = 'From: ' . SITE_NAME . ' <' . SITE_EMAIL . ">\r\n" .
               'Reply-To: ' . SITE_EMAIL . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    
    return mail($to, $subject, $message, $headers);
}

// 获取文件扩展名
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// 检查文件类型
function checkFileType($filename, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
    $ext = getFileExtension($filename);
    return in_array($ext, $allowedTypes);
}

// 生成缩略图
function generateThumbnail($source, $destination, $width, $height) {
    list($srcWidth, $srcHeight, $type) = getimagesize($source);
    
    $ratio = min($width/$srcWidth, $height/$srcHeight);
    $newWidth = $srcWidth * $ratio;
    $newHeight = $srcHeight * $ratio;
    
    $thumb = imagecreatetruecolor($newWidth, $newHeight);
    
    switch($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($source);
            break;
    }
    
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
    
    switch($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $destination);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $destination);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $destination);
            break;
    }
    
    imagedestroy($thumb);
    imagedestroy($source);
}

// 获取文件大小
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

// 检查是否是AJAX请求
function isAjaxRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// 检查是否是POST请求
function isPostRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// 检查是否是GET请求
function isGetRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

// 重定向
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// 返回JSON响应
function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// 获取客户端语言
function getClientLanguage() {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    return in_array($lang, ['zh', 'en']) ? $lang : 'zh';
}

// 生成CSRF Token
function generateCsrfToken() {
    if(!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// 验证CSRF Token
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?> 