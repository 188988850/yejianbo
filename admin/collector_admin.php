<?php
// 引入配置文件
require_once(__DIR__ . '/../config.php');

// 数据库配置
$dbconfig = array(
    "host" => "localhost",
    "port" => 3306,
    "user" => "caihong",
    "pwd" => "Z5D4He8LT6DDsiTr",
    "dbname" => "caihong",
    "dbqz" => "shua"
);

// 连接数据库
$conn = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 检查采集进程是否存在
function isCollectorRunning() {
    $output = [];
    exec("ps aux | grep 'php template/storenews/collector.php' | grep -v grep", $output);
    return count($output) > 0;
}
$is_running = isCollectorRunning();

// 读取采集状态文件
$status_file = __DIR__ . '/../logs/collection_status.json';
$current_status = array();
if (file_exists($status_file)) {
    $json = file_get_contents($status_file);
    $current_status = json_decode($json, true);
}

// 获取API配置
$api_config_file = __DIR__ . '/../template/storenews/collector.php';
$api_hosts = array();
if (file_exists($api_config_file)) {
    $content = file_get_contents($api_config_file);
    if (preg_match('/\'hosts\'\s*=>\s*array\s*\(\s*([^)]+)\s*\)/', $content, $matches)) {
        $hosts_str = $matches[1];
        preg_match_all('/\'([^\']+)\'/', $hosts_str, $hosts);
        $api_hosts = $hosts[1];
    }
}

// 获取价格配置
$prices = array(
    'disk_sale' => 0,
    'disk_basic' => 0,
    'disk_pro' => 0,
    'full_sale' => 0,
    'full_basic' => 0,
    'full_pro' => 0,
    'sale' => 0,
    'basic' => 0,
    'pro' => 0
);

$price_sql = "SELECT * FROM {$dbconfig['dbqz']}_config WHERE type='price'";
$result = $conn->query($price_sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prices[$row['name']] = $row['value'];
    }
}

// 获取今日统计
$today = date('Y-m-d');
$stats = array(
    'api_updated' => 0,
    'collected' => 0
);

$stats_sql = "SELECT COUNT(*) as count FROM {$dbconfig['dbqz']}_videolist WHERE DATE(addtime)='$today'";
$result = $conn->query($stats_sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['collected'] = $row['count'];
}

// 获取API统计信息
function getApiStats($host) {
    global $conn, $dbconfig;
    
    $stats = array(
        'status' => false,
        'total_videos' => 0,
        'total_categories' => 0,
        'last_update' => '未知'
    );
    
    // 检查API是否可访问
    $ch = curl_init($host . '/api.php?ac=list');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200 && !empty($response)) {
        $stats['status'] = true;
        
        // 获取资源总数
        $sql = "SELECT COUNT(*) as count FROM {$dbconfig['dbqz']}_videolist WHERE api_host = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $host);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stats['total_videos'] = $row['count'];
        }
        
        // 获取分类数量
        $sql = "SELECT COUNT(DISTINCT type_id) as count FROM {$dbconfig['dbqz']}_videolist WHERE api_host = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $host);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stats['total_categories'] = $row['count'];
        }
        
        // 获取最后更新时间
        $sql = "SELECT MAX(addtime) as last_update FROM {$dbconfig['dbqz']}_videolist WHERE api_host = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $host);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stats['last_update'] = $row['last_update'] ? date('Y-m-d H:i:s', strtotime($row['last_update'])) : '未知';
        }
    }
    
    return $stats;
}

// 统计数据文件内容
$status_file = __DIR__ . '/../logs/collection_status.json';
$log_file = __DIR__ . '/../logs/collection.log';
$checkpoint_file = __DIR__ . '/../logs/collection_checkpoint.json';

// 采集状态内容
$status_info = '';
if (file_exists($status_file)) {
    $json = file_get_contents($status_file);
    $data = json_decode($json, true);
    if ($data) {
        $status_info = '（当前分类: ' . htmlspecialchars($data['current_category'] ?? '') .
            '，页码: ' . ($data['current_page'] ?? 0) . '/' . ($data['total_pages'] ?? 0) .
            '，已处理视频: ' . ($data['processed_videos'] ?? 0) . '）';
    }
}
// 采集日志条数
$log_count = 0;
if (file_exists($log_file)) {
    $log_count = substr_count(file_get_contents($log_file), "\n");
}
// 采集断点条数
$checkpoint_count = 0;
if (file_exists($checkpoint_file)) {
    $json = file_get_contents($checkpoint_file);
    $data = json_decode($json, true);
    if (is_array($data)) {
        $checkpoint_count = count($data);
    } elseif (!empty($json)) {
        $checkpoint_count = 1;
    }
}

// 查询数据库表数量
$type_count = 0;
$videolist_count = 0;
$video_count = 0;
if ($conn) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM {$dbconfig['dbqz']}_videotype");
    if ($row = $result->fetch_assoc()) $type_count = $row['cnt'];
    $result = $conn->query("SELECT COUNT(*) as cnt FROM {$dbconfig['dbqz']}_videolist");
    if ($row = $result->fetch_assoc()) $videolist_count = $row['cnt'];
    $result = $conn->query("SELECT COUNT(*) as cnt FROM {$dbconfig['dbqz']}_video");
    if ($row = $result->fetch_assoc()) $video_count = $row['cnt'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>采集脚本管理后台</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <style>
        .section-title { font-weight: bold; margin: 20px 0 10px; }
        .status-running { color: green; }
        .status-stopped { color: red; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>采集脚本管理后台</h1>
        
        <!-- 状态显示 -->
        <div class="section-title">当前状态</div>
        <div class="alert <?php echo $is_running ? 'alert-success' : 'alert-danger'; ?>">
            采集脚本状态: <span class="<?php echo $is_running ? 'status-running' : 'status-stopped'; ?>">
                <?php echo $is_running ? '运行中' : '已停止'; ?>
            </span>
            <?php if (!empty($current_status)) : ?>
            <br>
            当前分类: <?php echo htmlspecialchars($current_status['current_category'] ?? ''); ?><br>
            当前页码: <?php echo $current_status['current_page'] ?? 0; ?>/<?php echo $current_status['total_pages'] ?? 0; ?><br>
            已处理视频: <?php echo $current_status['processed_videos'] ?? 0; ?><br>
            开始时间: <?php echo $current_status['start_time'] ?? ''; ?><br>
            更新时间: <?php echo $current_status['last_update'] ?? ''; ?>
            <?php endif; ?>
        </div>

        <!-- 控制按钮 -->
        <div class="section-title">采集控制</div>
        <div class="btn-group mb-3">
            <button type="button" class="btn btn-success" onclick="startCollector()">启动采集</button>
            <button type="button" class="btn btn-danger" onclick="stopCollector()">停止采集</button>
            <button type="button" class="btn btn-info" onclick="checkStatus()">刷新状态</button>
        </div>

        <!-- API管理 -->
        <div class="section-title">API通道管理</div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="newApi" placeholder="输入新API地址">
                            <div class="input-group-append">
                                <button class="btn btn-primary" onclick="addApi()">添加</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-info" onclick="refreshApiStats()">刷新统计</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>API地址</th>
                                <th>状态</th>
                                <th>资源总数</th>
                                <th>分类数量</th>
                                <th>最后更新</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="apiList"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 分类详情模态框 -->
        <div class="modal fade" id="categoryModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">API分类详情</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>分类ID</th>
                                        <th>分类名称</th>
                                        <th>资源数量</th>
                                        <th>最后更新</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 价格设置 -->
        <div class="section-title">价格设置</div>
        <form id="priceForm" class="form-row mb-3">
            <div class="col-md-4 mb-2">
                <label>网盘销售价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="disk_sale" placeholder="请输入网盘销售价格" value="<?php echo $prices['disk_sale']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="disk_sale">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>网盘普及版价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="disk_basic" placeholder="请输入网盘普及版价格" value="<?php echo $prices['disk_basic']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="disk_basic">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>网盘专业版价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="disk_pro" placeholder="请输入网盘专业版价格" value="<?php echo $prices['disk_pro']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="disk_pro">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>整部销售价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="full_sale" placeholder="请输入整部销售价格" value="<?php echo $prices['full_sale']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="full_sale">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>整部普及版价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="full_basic" placeholder="请输入整部普及版价格" value="<?php echo $prices['full_basic']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="full_basic">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>整部专业版价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="full_pro" placeholder="请输入整部专业版价格" value="<?php echo $prices['full_pro']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="full_pro">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>销售价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="sale" placeholder="请输入销售价格" value="<?php echo $prices['sale']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="sale">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>普及版价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="basic" placeholder="请输入普及版价格" value="<?php echo $prices['basic']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="basic">★</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label>专业版价格</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="pro" placeholder="请输入专业版价格" value="<?php echo $prices['pro']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary price-shortcut" type="button" data-key="pro">★</button>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <button type="button" class="btn btn-success" onclick="savePrice()">保存价格设置</button>
                <button type="button" class="btn btn-secondary ml-2" onclick="fillLastPrice()">快捷填充</button>
            </div>
        </form>

        <!-- 网盘地址替换 -->
        <div class="section-title">网盘地址批量替换</div>
        <form id="diskUrlForm" class="form-inline mb-3">
            <div class="input-group">
                <input type="text" class="form-control mr-2" name="disk_url" placeholder="自定义网盘URL" style="width:400px;">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary diskurl-shortcut" type="button">★</button>
                </div>
            </div>
            <button type="button" class="btn btn-primary ml-2" onclick="saveDiskUrl()">一键替换</button>
            <button type="button" class="btn btn-secondary ml-2" onclick="fillLastDiskUrl()">快捷填充</button>
        </form>

        <!-- 今日采集统计 -->
        <div class="section-title">今日采集统计 <button class="btn btn-sm btn-info ml-2" onclick="refreshStats()">刷新统计</button></div>
        <div class="card mb-3">
            <div class="card-body" id="statsBox">
                <p>已采集资源数: <span id="collectedCount"><?php echo $stats['collected']; ?></span></p>
                <p>API更新资源数: <span id="apiUpdatedCount"><?php echo $stats['api_updated']; ?></span></p>
            </div>
        </div>

        <!-- 缓存垃圾清理 -->
        <div class="section-title">缓存垃圾清理</div>
        <button class="btn btn-warning mb-3" onclick="clearCache()">一键清理缓存垃圾</button>

        <!-- 数据文件一键清空 -->
        <div class="section-title">数据文件一键清空</div>
        <div class="mb-3">
            <button class="btn btn-outline-danger mr-2" onclick="clearStatusFile()">清空采集状态文件</button>
            <span style="margin-right:20px;">采集状态<?php echo $status_info; ?></span>
            <br>
            <button class="btn btn-outline-warning mr-2" onclick="clearLogFile()">清空采集日志文件</button>
            <span style="margin-right:20px;">采集日志（共 <?php echo $log_count; ?> 条）</span>
            <br>
            <button class="btn btn-outline-info" onclick="clearCheckpointFile()">清空采集断点文件</button>
            <span>采集断点（共 <?php echo $checkpoint_count; ?> 个）</span>
        </div>

        <!-- 数据库数据一键清空 -->
        <div class="section-title">数据库数据一键清空</div>
        <div class="mb-3">
            <button class="btn btn-outline-danger mr-2" onclick="clearTypeTable()">清空分类数据</button>
            <span style="margin-right:20px;">分类数量：<?php echo $type_count; ?></span>
            <br>
            <button class="btn btn-outline-warning mr-2" onclick="clearVideolistTable()">清空影视数据</button>
            <span style="margin-right:20px;">影视数量：<?php echo $videolist_count; ?></span>
            <br>
            <button class="btn btn-outline-info" onclick="clearVideoTable()">清空集数数据</button>
            <span>集数数量：<?php echo $video_count; ?></span>
        </div>

        <!-- 多定时任务状态展示区 -->
        <div id="cronStatusBox" style="margin:20px 0;padding:10px;border:1px solid #eee;background:#fafbfc;">
            <b>定时任务列表：</b>
            <button class="btn btn-danger btn-sm ml-3" onclick="clearAllCronJobs()">清除所有定时任务</button>
            <div id="cronTaskList"></div>
        </div>

        <!-- 定时任务 -->
        <div class="section-title">定时采集设置</div>
        <div class="card mb-3">
            <div class="card-body">
                <form id="cronForm">
                    <div class="form-group">
                        <label>采集模式</label>
                        <select class="form-control" id="cronMode" onchange="toggleCronOptions()">
                            <option value="daily">每天定时采集</option>
                            <option value="interval">间隔天数采集</option>
                            <option value="weekly">每周定时采集</option>
                            <option value="monthly">每月定时采集</option>
                        </select>
                    </div>

                    <!-- 每天定时采集选项 -->
                    <div id="dailyOptions" class="cron-options">
                        <div class="form-group">
                            <label>采集时间</label>
                            <input type="time" class="form-control" id="dailyTime" value="00:00">
                        </div>
                    </div>

                    <!-- 间隔天数采集选项 -->
                    <div id="intervalOptions" class="cron-options" style="display:none;">
                        <div class="form-group">
                            <label>间隔天数</label>
                            <input type="number" class="form-control" id="intervalDays" min="1" value="1">
                        </div>
                        <div class="form-group">
                            <label>采集时间</label>
                            <input type="time" class="form-control" id="intervalTime" value="00:00">
                        </div>
                    </div>

                    <!-- 每周定时采集选项 -->
                    <div id="weeklyOptions" class="cron-options" style="display:none;">
                        <div class="form-group">
                            <label>选择星期</label>
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-primary active">
                                    <input type="checkbox" name="weekdays" value="1" checked> 周一
                                </label>
                                <label class="btn btn-outline-primary active">
                                    <input type="checkbox" name="weekdays" value="2" checked> 周二
                                </label>
                                <label class="btn btn-outline-primary active">
                                    <input type="checkbox" name="weekdays" value="3" checked> 周三
                                </label>
                                <label class="btn btn-outline-primary active">
                                    <input type="checkbox" name="weekdays" value="4" checked> 周四
                                </label>
                                <label class="btn btn-outline-primary active">
                                    <input type="checkbox" name="weekdays" value="5" checked> 周五
                                </label>
                                <label class="btn btn-outline-primary active">
                                    <input type="checkbox" name="weekdays" value="6" checked> 周六
                                </label>
                                <label class="btn btn-outline-primary active">
                                    <input type="checkbox" name="weekdays" value="0" checked> 周日
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>采集时间</label>
                            <input type="time" class="form-control" id="weeklyTime" value="00:00">
                        </div>
                    </div>

                    <!-- 每月定时采集选项 -->
                    <div id="monthlyOptions" class="cron-options" style="display:none;">
                        <div class="form-group">
                            <label>每月日期</label>
                            <input type="number" class="form-control" id="monthlyDay" min="1" max="31" value="1">
                        </div>
                        <div class="form-group">
                            <label>采集时间</label>
                            <input type="time" class="form-control" id="monthlyTime" value="00:00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>采集时长限制（分钟）</label>
                        <input type="number" class="form-control" id="durationLimit" min="0" value="0" placeholder="0表示不限制">
                        <small class="form-text text-muted">设置0表示不限制采集时长</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="autoStop" checked>
                            <label class="custom-control-label" for="autoStop">采集完成后自动停止</label>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="saveCron()">保存定时任务</button>
                    <button type="button" class="btn btn-danger" onclick="deleteCron()">删除定时任务</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function startCollector() {
        $.post('collector_control.php', {action: 'start'}, function(res) {
            alert(res.message);
            location.reload();
        });
    }

    function stopCollector() {
        $.post('collector_control.php', {action: 'stop'}, function(res) {
            alert(res.message);
            location.reload();
        });
    }

    function checkStatus() {
        location.reload();
    }

    function renderApiList() {
        $.post('collector_api.php', {action: 'list'}, function(res) {
            if(res.code === 0 && Array.isArray(res.data)) {
                let html = '';
                if(res.data.length === 0) {
                    html = '<tr><td colspan="6" style="color:#888">暂无API</td></tr>';
                } else {
                    res.data.forEach(function(api, index) {
                        html += '<tr>';
                        html += '<td>' + (api.host || '-') + '</td>';
                        html += '<td>' + (api.status ? '<span class="badge badge-success">正常</span>' : '<span class="badge badge-danger">异常</span>') + '</td>';
                        html += '<td>' + (api.total_videos !== undefined ? api.total_videos : '-') + '</td>';
                        html += '<td>' + (api.total_categories !== undefined ? api.total_categories : '-') + '</td>';
                        html += '<td>' + (api.last_update || '-') + '</td>';
                        html += '<td>';
                        html += '<button class="btn btn-sm btn-info" onclick="viewCategories(' + (api.index !== undefined ? api.index : index) + ')">查看分类</button> ';
                        html += '<button class="btn btn-sm btn-danger" onclick="removeApi(' + (api.index !== undefined ? api.index : index) + ')">删除</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                }
                $('#apiList').html(html);
            } else {
                $('#apiList').html('<tr><td colspan="6" style="color:red">无法获取API列表</td></tr>');
            }
        }, 'json');
    }

    function addApi() {
        var api = $('#newApi').val();
        if (!api) return;
        $.post('collector_api.php', {action: 'add', api: api}, function(res) {
            alert(res.message);
            if(res.code === 0) renderApiList();
        }, 'json');
    }

    function removeApi(index) {
        if (!confirm('确定要删除这个API吗？')) return;
        $.post('collector_api.php', {action: 'remove', index: index}, function(res) {
            alert(res.message);
            if(res.code === 0) renderApiList();
        }, 'json');
    }

    function savePrice() {
        var data = {};
        $('#priceForm input').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        $.post('collector_price.php', {action: 'save', prices: data}, function(res) {
            alert(res.message);
        });
    }

    function saveDiskUrl() {
        var url = $('input[name="disk_url"]').val();
        if (!url) return;
        $.post('collector_diskurl.php', {action: 'replace', url: url}, function(res) {
            alert(res.message);
            if(res.code === 0) localStorage.setItem('lastDiskUrl', url);
        }, 'json');
    }

    function fillLastPrice() {
        $.post('collector_price.php', {action: 'get'}, function(res) {
            if(res.code === 0 && res.data) {
                for(var k in res.data) {
                    $("#priceForm input[name='"+k+"']").val(res.data[k]);
                }
                alert('已填充上次保存的价格');
            } else {
                alert(res.message || '未获取到价格');
            }
        }, 'json');
    }

    function fillLastDiskUrl() {
        var url = localStorage.getItem('lastDiskUrl') || '';
        if(url) {
            $('input[name="disk_url"]').val(url);
            alert('已填充上次保存的网盘地址');
        } else {
            alert('暂无历史网盘地址');
        }
    }

    function toggleCronOptions() {
        $('.cron-options').hide();
        var mode = $('#cronMode').val();
        $('#' + mode + 'Options').show();
    }

    function saveCron() {
        var mode = $('#cronMode').val();
        var data = {
            mode: mode,
            duration_limit: $('#durationLimit').val(),
            auto_stop: $('#autoStop').is(':checked')
        };

        switch(mode) {
            case 'daily':
                data.time = $('#dailyTime').val();
                break;
            case 'interval':
                data.days = $('#intervalDays').val();
                data.time = $('#intervalTime').val();
                break;
            case 'weekly':
                data.weekdays = [];
                $('input[name="weekdays"]:checked').each(function() {
                    data.weekdays.push($(this).val());
                });
                data.time = $('#weeklyTime').val();
                break;
            case 'monthly':
                data.day = $('#monthlyDay').val();
                data.time = $('#monthlyTime').val();
                break;
        }

        $.post('collector_control.php', {
            action: 'cron',
            data: JSON.stringify(data)
        }, function(res) {
            alert(res.message);
            if(res.code === 0) {
                fetchCronStatus();
            }
        }, 'json').fail(function(xhr){alert('请求失败: '+xhr.status);});
    }

    function deleteCron() {
        if(!confirm('确定要删除定时任务吗？')) return;
        $.post('collector_control.php', {
            action: 'delete_cron'
        }, function(res) {
            alert(res.message);
            fetchCronStatus();
        }, 'json').fail(function(xhr){alert('请求失败: '+xhr.status);});
    }

    function refreshApiStats() {
        $.post('collector_api.php', {action: 'refresh_stats'}, function(res) {
            if(res.code === 0) {
                location.reload();
            } else {
                alert(res.message);
            }
        });
    }

    function viewCategories(index) {
        $.post('collector_api.php', {
            action: 'get_categories',
            index: index
        }, function(res) {
            if(res.code === 0) {
                var html = '';
                res.data.forEach(function(category) {
                    html += '<tr>';
                    html += '<td>' + category.id + '</td>';
                    html += '<td>' + category.name + '</td>';
                    html += '<td>' + category.video_count + '</td>';
                    html += '<td>' + category.last_update + '</td>';
                    html += '</tr>';
                });
                $('#categoryList').html(html);
                $('#categoryModal').modal('show');
            } else {
                alert(res.message);
            }
        });
    }

    function clearCache() {
        if(!confirm('确定要清理缓存和临时文件吗？')) return;
        $.post('collector_control.php', {action: 'clear_cache'}, function(res) {
            alert(res.message);
            if(res.code === 0) {
                location.reload();
            }
        });
    }

    function clearStatusFile() {
        if(!confirm('确定要清空采集状态文件内容吗？')) return;
        $.post('collector_control.php', {action: 'clear_status_file'}, function(res) {
            alert(res.message);
            if(res.code === 0) location.reload();
        });
    }
    function clearLogFile() {
        if(!confirm('确定要清空采集日志文件内容吗？')) return;
        $.post('collector_control.php', {action: 'clear_log_file'}, function(res) {
            alert(res.message);
            if(res.code === 0) location.reload();
        });
    }
    function clearCheckpointFile() {
        if(!confirm('确定要清空采集断点文件内容吗？')) return;
        $.post('collector_control.php', {action: 'clear_checkpoint_file'}, function(res) {
            alert(res.message);
            if(res.code === 0) location.reload();
        });
    }

    function clearTypeTable() {
        if(!confirm('确定要清空所有分类数据吗？')) return;
        $.post('collector_control.php', {action: 'clear_type_table'}, function(res) {
            alert(res.message);
            if(res.code === 0) location.reload();
        });
    }
    function clearVideolistTable() {
        if(!confirm('确定要清空所有影视数据吗？')) return;
        $.post('collector_control.php', {action: 'clear_videolist_table'}, function(res) {
            alert(res.message);
            if(res.code === 0) location.reload();
        });
    }
    function clearVideoTable() {
        if(!confirm('确定要清空所有集数数据吗？')) return;
        $.post('collector_control.php', {action: 'clear_video_table'}, function(res) {
            alert(res.message);
            if(res.code === 0) location.reload();
        });
    }

    function fetchCronStatus() {
        $.post('collector_control.php', {action: 'get_cron_status'}, function(res) {
            if(res.code === 0 && Array.isArray(res.data)) {
                let html = '';
                if(res.data.length === 0) {
                    html = '<div style="color:#888">暂无定时任务</div>';
                } else {
                    res.data.forEach(function(task) {
                        html += '<div style="border-bottom:1px solid #eee;padding:8px 0;">';
                        html += '<b>任务ID:</b> ' + task.id + ' | ';
                        html += '<b>状态:</b> ' + (task.status || '无') + ' | ';
                        html += '<b>下次执行:</b> ' + (task.next_time_str || '无') + ' | ';
                        html += '<b>最近执行:</b> ' + (task.last_run_time_str || '无') + ' | ';
                        html += '<b>最近完成:</b> ' + (task.last_finish_time_str || '无') + ' | ';
                        html += '<b>结果:</b> ' + (task.last_result || '无');
                        if(task.fail_reason) html += ' | <span style="color:red">失败原因: ' + task.fail_reason + '</span>';
                        html += '<div style="margin-top:6px;">';
                        html += '<button class="btn btn-danger btn-sm" onclick="deleteCronJob(\''+task.id+'\')">删除</button> ';
                        html += '<button class="btn btn-success btn-sm" onclick="startCollectorNow(\''+task.id+'\')">立即采集</button> ';
                        html += '<button class="btn btn-warning btn-sm" onclick="stopCollectorNow(\''+task.id+'\')">停止采集</button>';
                        html += '</div>';
                        html += '</div>';
                    });
                }
                $('#cronTaskList').html(html);
            } else {
                $('#cronTaskList').html('<div style="color:#888">无法获取定时任务状态</div>');
            }
        }, 'json').fail(function(xhr){
            $('#cronTaskList').html('<div style="color:red">请求失败: '+xhr.status+'</div>');
        });
    }

    function deleteCronJob(id) {
        if(!confirm('确定要删除该定时任务吗？')) return;
        $.post('collector_control.php', {action: 'delete_cron', id: id}, function(res) {
            alert(res.message);
            fetchCronStatus();
        }, 'json').fail(function(xhr){alert('请求失败: '+xhr.status);});
    }

    function startCollectorNow(id) {
        $.post('collector_control.php', {action: 'start_now', id: id}, function(res) {
            alert(res.message);
            fetchCronStatus();
        }, 'json').fail(function(xhr){alert('请求失败: '+xhr.status);});
    }

    function stopCollectorNow(id) {
        $.post('collector_control.php', {action: 'stop_now', id: id}, function(res) {
            alert(res.message);
            fetchCronStatus();
        }, 'json').fail(function(xhr){alert('请求失败: '+xhr.status);});
    }

    function clearAllCronJobs() {
        if(!confirm('确定要清除所有定时任务吗？')) return;
        $.post('collector_control.php', {action: 'clear_all_cron'}, function(res) {
            alert(res.message);
            fetchCronStatus();
        }, 'json').fail(function(xhr){alert('请求失败: '+xhr.status);});
    }

    function refreshStats() {
        $.get(window.location.href, function(){ location.reload(); });
    }

    $(function(){
        renderApiList();
        fetchCronStatus();
        setInterval(fetchCronStatus, 30000);
        // 价格快捷键逻辑
        $('.price-shortcut').each(function(){
            var key = $(this).data('key');
            if(localStorage.getItem('price_'+key)) {
                $(this).addClass('btn-warning').removeClass('btn-outline-secondary');
            }
        });
        // 点击快捷键按钮
        $('.price-shortcut').click(function(){
            var key = $(this).data('key');
            var input = $(this).closest('.input-group').find('input');
            var val = localStorage.getItem('price_'+key);
            if(val) {
                input.val(val);
                alert('已填充常用价格');
            } else {
                var newVal = prompt('设置常用价格（下次可一键填充）:');
                if(newVal!==null && newVal!=='') {
                    localStorage.setItem('price_'+key, newVal);
                    input.val(newVal);
                    $(this).addClass('btn-warning').removeClass('btn-outline-secondary');
                    alert('常用价格已保存');
                }
            }
        });
        // 网盘地址快捷键
        var diskBtn = $('.diskurl-shortcut');
        var diskInput = $('input[name="disk_url"]');
        if(localStorage.getItem('diskurl_shortcut')) {
            diskBtn.addClass('btn-warning').removeClass('btn-outline-secondary');
        }
        diskBtn.click(function(){
            var val = localStorage.getItem('diskurl_shortcut');
            if(val) {
                diskInput.val(val);
                alert('已填充常用网盘地址');
            } else {
                var newVal = prompt('设置常用网盘地址（下次可一键填充）:');
                if(newVal!==null && newVal!=='') {
                    localStorage.setItem('diskurl_shortcut', newVal);
                    diskInput.val(newVal);
                    diskBtn.addClass('btn-warning').removeClass('btn-outline-secondary');
                    alert('常用网盘地址已保存');
                }
            }
        });
    });
    </script>
</body>
</html> 