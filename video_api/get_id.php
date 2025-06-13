<?php
header('Content-Type: application/json');

// 获取URL中的查询参数
$theater_parent_id = isset($_GET['theater_parent_id']) ? intval($_GET['theater_parent_id']) : null;

if ($theater_parent_id === null) {
    echo json_encode(['error' => 'Missing required parameter: theater_parent_id']);
    exit();
}

// 构建查询字符串
$queryString = http_build_query([
    'theater_parent_id' => $theater_parent_id,
]);

// 请求的URL
$url = "https://xvapp.xingya.com.cn/v2/theater_parent/detail?" . $queryString;

// 初始化cURL会话
$ch = curl_init();

// 设置cURL选项
curl_setopt($ch, CURLOPT_URL, $url); // 设置请求的URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 设置获取的信息以文件流的形式返回
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MzA1MzM2MzcsIlVzZXJJZCI6NjM5NzEyNzEsInJlZ2lzdGVyX3RpbWUiOiIyMDI0LTEwLTE3IDIwOjQ0OjEwIiwiaXNfbW9iaWxlX2JpbmQiOnRydWV9.Ri-7ohGccK22l0amYYEmN0LqjjbulcKviNt9ZheNunQ',
    'Host: xvapp.xingya.com.cn',
    'x-app-id: 106',
    'platform: 1',
    'manufacturer: Xiaomi',
    'version_name: 3.6.1',
    'user-agent: Mozilla/5.0 (Linux; Android 13; M2102J2SC Build/TKQ1.221114.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/129.0.6668.70 Mobile Safari/537.36',
    'dev-token: BHbGIW5oJtu4mA2jG6VFDur-UbfKfMOOdS7mWH0UZYta47AW0HMxm3egArqyK39pGIBicvuzVihPgVHCWfPmw3FyFwdpLYaIfqYA-zTE0vsmlrExRUaAhZFl0ul49a0SHHEhrKpVE83uPDIFprCVOPD-cIjUefP_RXHFt-11kWZU*',
    'app-version: 3.6.1',
    'device-platform: android',
    'personalized-recommend-status: 1',
    'device-type: M2102J2SC',
    'device-brand: Xiaomi',
    'os-version: 13',
    'channel: default',
    'raw-channel: default',
    'oaid: 016ccba325a45228',
    'msa-oaid: 016ccba325a45228',
    'uuid: randomUUID_4c12ebd2-b16f-4423-9ed3-3a9ffd34daef',
    'device-id: 22a65e145cfa93e3a9d153dfaa24cf14f',
    'support-h265: 1',
    'font-scale: 1.0',
    'accept-encoding: gzip',
    'user-agent: okhttp/4.10.0'
]);

// 执行cURL会话
$response = curl_exec($ch);

// 检查是否有错误发生
if(curl_errno($ch)){
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
} else {
    // 解码响应数据（假设它是JSON）
    $responseData = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Failed to decode JSON response']);
    } else {
        // 输出解码后的数据
        echo json_encode($responseData);
    }
}

// 关闭cURL资源，并释放系统资源
curl_close($ch);
?>