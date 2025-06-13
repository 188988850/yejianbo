<?php
class Util {
    private static $instance = null;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function generateRandomString($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }

    public function generateOrderNo() {
        return date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public function formatMoney($amount, $decimals = 2) {
        return number_format($amount, $decimals, '.', '');
    }

    public function formatDate($date, $format = 'Y-m-d H:i:s') {
        return date($format, strtotime($date));
    }

    public function getClientIp() {
        $ip = $_SERVER['HTTP_CLIENT_IP'] ?? 
              $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
              $_SERVER['HTTP_X_FORWARDED'] ?? 
              $_SERVER['HTTP_FORWARDED_FOR'] ?? 
              $_SERVER['HTTP_FORWARDED'] ?? 
              $_SERVER['REMOTE_ADDR'] ?? 
              'Unknown';
        return $ip;
    }

    public function getCurrentUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function isMobile() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return preg_match('/(android|iphone|ipad|ipod|mobile)/i', $userAgent);
    }

    public function isWechat() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return strpos($userAgent, 'MicroMessenger') !== false;
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function getFileExtension($filename) {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    public function getFileSize($size) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    public function createThumbnail($source, $destination, $width, $height) {
        list($srcWidth, $srcHeight, $type) = getimagesize($source);
        
        $ratio = min($width / $srcWidth, $height / $srcHeight);
        $newWidth = round($srcWidth * $ratio);
        $newHeight = round($srcHeight * $ratio);
        
        $image = imagecreatetruecolor($newWidth, $newHeight);
        
        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($source);
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($source);
                break;
            default:
                return false;
        }
        
        imagecopyresampled($image, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
        
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $destination, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($image, $destination, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($image, $destination);
                break;
        }
        
        imagedestroy($image);
        imagedestroy($sourceImage);
        
        return true;
    }

    public function sendEmail($to, $subject, $message, $headers = []) {
        $defaultHeaders = [
            'From' => SMTP_FROM,
            'Reply-To' => SMTP_FROM,
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/html; charset=UTF-8'
        ];
        
        $headers = array_merge($defaultHeaders, $headers);
        $headerString = '';
        foreach ($headers as $key => $value) {
            $headerString .= "$key: $value\r\n";
        }
        
        return mail($to, $subject, $message, $headerString);
    }

    public function curl($url, $options = []) {
        $defaultOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ];
        
        $options = array_replace($defaultOptions, $options);
        
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception('CURL Error: ' . $error);
        }
        
        return [
            'response' => $response,
            'info' => $info
        ];
    }

    public function arrayToXml($data, $root = 'root') {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><' . $root . '></' . $root . '>');
        $this->arrayToXmlRecursive($data, $xml);
        return $xml->asXML();
    }

    private function arrayToXmlRecursive($data, &$xml) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $subnode = $xml->addChild($key);
                $this->arrayToXmlRecursive($value, $subnode);
            } else {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }

    public function xmlToArray($xml) {
        $array = json_decode(json_encode(simplexml_load_string($xml)), true);
        return $array;
    }

    public function generateQrCode($text, $size = 200) {
        require_once dirname(__FILE__) . '/../vendor/phpqrcode/qrlib.php';
        
        $tempDir = dirname(__FILE__) . '/../temp/';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        
        $filename = $tempDir . md5($text) . '.png';
        QRcode::png($text, $filename, QR_ECLEVEL_L, $size / 25);
        
        return $filename;
    }

    public function getDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // 地球半径，单位：公里
        
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        
        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
} 