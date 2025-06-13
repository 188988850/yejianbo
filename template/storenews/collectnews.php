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

// 采集前3页（可根据需要调整页数）
for($page=1;$page<=3;$page++){
    $url = "https://web.aimallol.com/notice?type=10&page=$page&status=0";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36",
        "Accept: application/json, text/javascript, */*; q=0.01",
        "Accept-Language: zh-CN,zh;q=0.9",
        "Referer: https://web.aimallol.com/home",
        "Origin: https://web.aimallol.com",
        "X-Requested-With: XMLHttpRequest"
    ]);
    curl_setopt($ch, CURLOPT_COOKIE, "UM_distinctid=19721b8be6a8c0-07747ca3e0c82a-26011951-1fa400-19721b8be6bb6e; access_token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd2ViLmFpbWFsbG9sLmNvbSIsImF1ZCI6Imh0dHBzOlwvXC93ZWIuYWltYWxsb2wuY29tIiwiaWF0IjoxNzQ4NjE3NDY5LCJuYmYiOjE3NDg2MTc0NjksImV4cCI6MTc1MTIwOTQ2OSwiZGF0YSI6eyJ1c2VybmFtZSI6IjE4ODg4OTU1MTAwIiwiaWQiOiJNREF3TURBd01EQXdNSF9QZW1TemVJeWkifSwic2NvcGUiOiJhY2Nlc3MifQ.A6A96XSSHaBWyadF07cnDTTMCJp9WHg7zbey8IdZHSo; HWWAFSESID=e758873373d98d9352; HWWAFSESTIME=1748673871404; PHPSESSID=e7ba57cf4e4c0f80fc68448cffe54a1f; CNZZDATA1281364142=787794421-1748617445-%7C1748684707");
    $json = curl_exec($ch);
    if($json === false){
        echo "第{$page}页采集失败，curl错误：" . curl_error($ch) . "<br>";
        curl_close($ch);
        continue;
    }
    echo "<pre>第{$page}页原始返回：\n" . htmlspecialchars($json) . "</pre>";
    $data = json_decode($json, true);
    if(!$data || $data['code']!=200){
        echo "第{$page}页解析失败，code=" . ($data['code'] ?? '无') . "<br>";
        curl_close($ch);
        continue;
    }
    foreach($data['data']['data'] as $item){
        $id = $item['id'];
        $title = $item['title'];
        $img = str_replace('\\/', '/', $item['img']);
        $views = $item['hit'];
        $time = date('Y-m-d H:i:s', $item['create_time']);
        $desc = $item['desc'] ?? '';
        $url = "https://web.aimallol.com/info/{$id}?uid=60405";
        $content = '';
        $stmt = $conn->prepare("INSERT IGNORE INTO shua_news (title, url, `desc`, time, views, content, img) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiss", $title, $url, $desc, $time, $views, $content, $img);
        $stmt->execute();
    }
    curl_close($ch);
}
echo "<b>采集完成！</b>";
?> 