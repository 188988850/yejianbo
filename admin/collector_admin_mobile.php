<?php
// 引入配置文件
require_once(__DIR__ . '/../config.php');

// ...数据库配置、连接、数据获取与PC端一致，省略...

?><!DOCTYPE html>
<html>
<script>
if (/Android|webOS|iPhone|iPod|BlackBerry|iPad|Windows Phone/i.test(navigator.userAgent)) {
    window.location.href = "collector_admin_mobile.php";
}
</script>
<head>
    <meta charset="utf-8">
    <title>采集脚本管理后台（手机版）</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background: #f7f7f7; }
        .section-title { font-weight: bold; margin: 18px 0 8px; font-size: 1.1rem; }
        .card { margin-bottom: 12px; }
        .btn, input, select { font-size: 1rem !important; }
        .table-responsive { font-size: 0.95rem; }
        .fixed-bottom-bar { position:fixed;bottom:0;left:0;width:100%;background:#fff;padding:8px 0;box-shadow:0 -2px 8px #eee;z-index:99; }
        .modal-content { font-size: 1rem; }
    </style>
</head>
<body>
<div class="container-fluid px-2">
    <h4 class="mt-3 mb-2 text-center">采集管理后台 <span class="badge badge-info">手机版</span></h4>
    <div class="section-title">采集控制</div>
    <div class="mb-2 d-flex flex-wrap">
        <button class="btn btn-success flex-fill mr-2 mb-2" onclick="startCollector()">启动采集</button>
        <button class="btn btn-danger flex-fill mr-2 mb-2" onclick="stopCollector()">停止采集</button>
        <button class="btn btn-info flex-fill mb-2" onclick="checkStatus()">刷新状态</button>
    </div>
    <div class="section-title">API通道管理</div>
    <div class="card">
        <div class="card-body p-2">
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="newApi" placeholder="输入新API地址">
                <div class="input-group-append">
                    <button class="btn btn-primary" onclick="addApi()">添加</button>
                </div>
            </div>
            <button class="btn btn-info btn-block mb-2" onclick="refreshApiStats()">刷新统计</button>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th>API地址</th>
                            <th>状态</th>
                            <th>资源</th>
                            <th>分类</th>
                            <th>更新</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="apiList"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="section-title">定时采集任务</div>
    <div class="card">
        <div class="card-body p-2">
            <div id="cronTaskList"></div>
            <button class="btn btn-danger btn-block mb-2" onclick="clearAllCronJobs()">清除所有定时任务</button>
            <form id="cronForm">
                <div class="form-group mb-2">
                    <label>采集模式</label>
                    <select class="form-control" id="cronMode" onchange="toggleCronOptions()">
                        <option value="daily">每天定时采集</option>
                        <option value="interval">间隔天数采集</option>
                        <option value="weekly">每周定时采集</option>
                        <option value="monthly">每月定时采集</option>
                    </select>
                </div>
                <div id="dailyOptions" class="cron-options">
                    <input type="time" class="form-control mb-2" id="dailyTime" value="00:00">
                </div>
                <div id="intervalOptions" class="cron-options" style="display:none;">
                    <input type="number" class="form-control mb-2" id="intervalDays" min="1" value="1" placeholder="间隔天数">
                    <input type="time" class="form-control mb-2" id="intervalTime" value="00:00">
                </div>
                <div id="weeklyOptions" class="cron-options" style="display:none;">
                    <div class="mb-2">
                        <label class="mr-2"><input type="checkbox" name="weekdays" value="1" checked> 周一</label>
                        <label class="mr-2"><input type="checkbox" name="weekdays" value="2" checked> 周二</label>
                        <label class="mr-2"><input type="checkbox" name="weekdays" value="3" checked> 周三</label>
                        <label class="mr-2"><input type="checkbox" name="weekdays" value="4" checked> 周四</label>
                        <label class="mr-2"><input type="checkbox" name="weekdays" value="5" checked> 周五</label>
                        <label class="mr-2"><input type="checkbox" name="weekdays" value="6" checked> 周六</label>
                        <label><input type="checkbox" name="weekdays" value="0" checked> 周日</label>
                    </div>
                    <input type="time" class="form-control mb-2" id="weeklyTime" value="00:00">
                </div>
                <div id="monthlyOptions" class="cron-options" style="display:none;">
                    <input type="number" class="form-control mb-2" id="monthlyDay" min="1" max="31" value="1" placeholder="每月日期">
                    <input type="time" class="form-control mb-2" id="monthlyTime" value="00:00">
                </div>
                <input type="number" class="form-control mb-2" id="durationLimit" min="0" value="0" placeholder="采集时长限制(分钟,0不限)">
                <div class="custom-control custom-switch mb-2">
                    <input type="checkbox" class="custom-control-input" id="autoStop" checked>
                    <label class="custom-control-label" for="autoStop">采集完成后自动停止</label>
                </div>
                <button type="button" class="btn btn-primary btn-block mb-2" onclick="saveCron()">保存定时任务</button>
                <button type="button" class="btn btn-danger btn-block mb-2" onclick="deleteCron()">删除定时任务</button>
            </form>
        </div>
    </div>
    <div class="section-title">价格设置</div>
    <form id="priceForm" class="mb-3">
        <div class="form-row">
            <div class="col-6 mb-2"><input type="number" class="form-control" name="disk_sale" placeholder="网盘销售价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="disk_basic" placeholder="网盘普及版价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="disk_pro" placeholder="网盘专业版价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="full_sale" placeholder="整部销售价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="full_basic" placeholder="整部普及版价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="full_pro" placeholder="整部专业版价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="sale" placeholder="销售价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="basic" placeholder="普及版价格"></div>
            <div class="col-6 mb-2"><input type="number" class="form-control" name="pro" placeholder="专业版价格"></div>
        </div>
        <button type="button" class="btn btn-success btn-block mb-2" onclick="savePrice()">保存价格设置</button>
        <button type="button" class="btn btn-secondary btn-block mb-2" onclick="fillLastPrice()">快捷填充</button>
    </form>
    <div class="section-title">网盘地址批量替换</div>
    <form id="diskUrlForm" class="mb-3">
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="disk_url" placeholder="自定义网盘URL">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary diskurl-shortcut" type="button">★</button>
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-block mb-2" onclick="saveDiskUrl()">一键替换</button>
        <button type="button" class="btn btn-secondary btn-block mb-2" onclick="fillLastDiskUrl()">快捷填充</button>
    </form>
    <div class="section-title">今日采集统计</div>
    <div class="card mb-3">
        <div class="card-body p-2" id="statsBox">
            <p>已采集资源数: <span id="collectedCount">-</span></p>
            <p>API更新资源数: <span id="apiUpdatedCount">-</span></p>
        </div>
    </div>
    <div class="section-title">清理与重置</div>
    <div class="mb-3">
        <button class="btn btn-warning btn-block mb-2" onclick="clearCache()">一键清理缓存垃圾</button>
        <button class="btn btn-outline-danger btn-block mb-2" onclick="clearStatusFile()">清空采集状态文件</button>
        <button class="btn btn-outline-warning btn-block mb-2" onclick="clearLogFile()">清空采集日志文件</button>
        <button class="btn btn-outline-info btn-block mb-2" onclick="clearCheckpointFile()">清空采集断点文件</button>
        <button class="btn btn-outline-danger btn-block mb-2" onclick="clearTypeTable()">清空分类数据</button>
        <button class="btn btn-outline-warning btn-block mb-2" onclick="clearVideolistTable()">清空影视数据</button>
        <button class="btn btn-outline-info btn-block mb-2" onclick="clearVideoTable()">清空集数数据</button>
    </div>
</div>
<script>
// 直接复用PC端JS函数（可从collector_admin.php复制，或用公共js文件引入）
// 这里只写核心入口，具体函数建议与PC端保持一致
$(function(){
    renderApiList();
    fetchCronStatus();
    // 价格快捷键、网盘快捷键等可选
});
</script>
</body>
</html> 