<?php
// 数据库配置
$db_host = "localhost";
$db_port = 3306;
$db_user = "xinfaka";
$db_pass = "82514a97de548852";
$db_name = "xinfaka";

// 数据库连接
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 采集首页HTML
$html = file_get_contents('https://web.aimallol.com/home');
if(!$html){
    die("无法采集目标页面！");
}

// 用正则简单提取资讯（需根据实际HTML结构调整）
preg_match_all('/<div[^>]*class="news-item"[^>]*>.*?<a[^>]*href="(\\/info\\/\\d+\\?uid=\\d+)"[^>]*>(.*?)<\\/a>.*?<div[^>]*class="news-desc"[^>]*>(.*?)<\\/div>.*?(\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}).*?阅读数(\\d+).*?<img[^>]*src="([^"]+)"/s', $html, $matches, PREG_SET_ORDER);

if(!$matches){
    die("未匹配到任何资讯，请检查正则或页面结构！");
}

foreach ($matches as $item) {
    $url = 'https://web.aimallol.com' . $item[1];
    $title = trim(strip_tags($item[2]));
    $desc = trim(strip_tags($item[3]));
    $time = $item[4];
    $views = $item[5];
    $img = $item[6];
    $content = '';

    $stmt = $conn->prepare("INSERT IGNORE INTO shua_news (title, url, `desc`, time, views, content, img) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $title, $url, $desc, $time, $views, $content, $img);
    $stmt->execute();
}
echo "<b>采集完成！</b>";
?>