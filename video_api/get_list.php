<?php
header('Content-Type: application/json');

$class2_ids = isset($_GET['class2_ids']) ? intval($_GET['class2_ids']) : null;
$page_num = isset($_GET['page_num']) ? intval($_GET['page_num']) : null;
$theater_class_id = isset($_GET['theater_class_id']) ? intval($_GET['theater_class_id']) : null;

$url = 'https://xvapp.xingya.com.cn/cloud/v2/theater/home_page';
$queryString = http_build_query([
    'theater_class_id' => $theater_class_id,
    'type' => 1,
    'class2_ids' => $class2_ids,
    'page_num' => $page_num,
    'page_size' => 2000,
]);

$headers = [
    'Host: xvapp.xingya.com.cn',
    'x-app-id: 106',
    'authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MzA2ODI2NDAsIlVzZXJJZCI6NjM5NzEyNzEsInJlZ2lzdGVyX3RpbWUiOiIyMDI0LTEwLTE3IDIwOjQ0OjEwIiwiaXNfbW9iaWxlX2JpbmQiOnRydWV9.MEA0ySUmiDH738vxuJWbaBnH2m2vHy2O43BoHlyYdOs',
    'platform: 1',
    'manufacturer: Xiaomi',
    'version_name: 3.6.1',
    'user_agent: Mozilla/5.0 (Linux; Android 13; M2102J2SC Build/TKQ1.221114.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/129.0.6668.70 Mobile Safari/537.36',
    'dev_token: BG5CzkRi4mTpG0_i1gg0eO-H4S7orGmHabV15onWInLGiF8zo8Xg8-5PlVgBzPHI9EWUpsvF7OTi93rHgpruDsN-5EU5a_bjrrRjt1PdXH6hTco1ZwCnAwPTHJ2wyOJxjCQ43i9kc5EIeW34ijfVFOQxZCIazQQ0KeiWP8GfJW0M*',
    'app_version: 3.6.1',
    'device_platform: android',
    'personalized_recommend_status: 1',
    'device_type: M2102J2SC',
    'device_brand: Xiaomi',
    'os_version: 13',
    'channel: default',
    'raw_channel: default',
    'oaid: 016ccba325a45228',
    'msa_oaid: 016ccba325a45228',
    'uuid: randomUUID_4c12ebd2-b16f-4423-9ed3-3a9ffd34daef',
    'device_id: 22a65e145cfa93e3a9d153dfaa24cf14f',
    'ab_id: ',
    'support_h265: 1',
    'font_scale: 1.0',
    'accept-encoding: gzip',
    'user-agent: okhttp/4.10.0'
];

$options = [
    'http' => [
        'method' => 'GET',
        'header' => implode("\r\n", $headers)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url . '?' . $queryString, false, $context);

if ($response === false) {
    echo "Failed to fetch data";
} else {
    echo $response;
}
?>