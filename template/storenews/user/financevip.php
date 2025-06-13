<?php
session_start();
require_once dirname(__DIR__).'/../../config.php';
require_once dirname(__DIR__).'/../../includes/db.class.php';
$DB = DB::getInstance($dbconfig);

if(!isset($_SESSION['zid'])){
    header('Location: /user/login.php?return_url=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
$userrow = $DB->query("SELECT * FROM shua_site WHERE zid='{$_SESSION['zid']}' LIMIT 1")->fetch();
if(!$userrow){
    header('Location: /user/login.php?return_url=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
// 独立开发模式，模拟会员数据
$userrow = [
  'finance_vip_level' => 0, // 2=超级会员，1=VIP，0=未开通
  'finance_vip_expire' => '',
];
$title = '金融会员中心';

// 会员开通/续费接口
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'openvip') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['zid'])) {
        echo json_encode(['code'=>-1, 'msg'=>'请先登录']); exit;
    }
    $userrow = $DB->query("SELECT * FROM shua_site WHERE zid='{$_SESSION['zid']}' LIMIT 1")->fetch();
    if (!$userrow) {
        echo json_encode(['code'=>-1, 'msg'=>'用户不存在']); exit;
    }
    $type = $_POST['vip_type'] ?? '';
    $vip_map = [
        'month' => ['level'=>1, 'expire_type'=>'month', 'price'=>99, 'days'=>30],
        'quarter' => ['level'=>1, 'expire_type'=>'season', 'price'=>249, 'days'=>90],
        'year' => ['level'=>1, 'expire_type'=>'year', 'price'=>799, 'days'=>365],
        'forever' => ['level'=>9, 'expire_type'=>'forever', 'price'=>1999, 'days'=>36500],
        'super' => ['level'=>2, 'expire_type'=>'forever', 'price'=>2999, 'days'=>36500],
    ];
    if (!isset($vip_map[$type])) {
        echo json_encode(['code'=>-1, 'msg'=>'会员类型错误']); exit;
    }
    $vip = $vip_map[$type];
    if ($userrow['rmb'] < $vip['price']) {
        echo json_encode(['code'=>-2, 'msg'=>'余额不足，请先充值']); exit;
    }
    // 计算新到期时间
    $now = time();
    $old_expire = strtotime($userrow['finance_vip_expire'] ?? '');
    if ($old_expire > $now) {
        $new_expire = $old_expire + $vip['days']*86400;
    } else {
        $new_expire = $now + $vip['days']*86400;
    }
    if ($vip['expire_type'] === 'forever') {
        $expire_str = '2099-12-31';
    } else {
        $expire_str = date('Y-m-d', $new_expire);
    }
    // 扣费并更新会员信息
    $DB->update('site', [
        'rmb' => $userrow['rmb'] - $vip['price'],
        'finance_vip_level' => $vip['level'],
        'finance_vip_expire_type' => $vip['expire_type'],
        'finance_vip_expire' => $expire_str
    ], "zid=?", [$_SESSION['zid']]);
    echo json_encode(['code'=>0, 'msg'=>'开通/续费成功', 'expire'=>$expire_str]); exit;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title><?php echo $title; ?></title>
  <!-- 本地化CSS资源 -->
  <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
  <link href="/assets/layui/css/layui.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/user/css/my.css">
  <style>
    body {
      background: #101a2b; /* 纯色背景，极致加速 */
      /* background: #101a2b url("/assets/img/bg.jpg") fixed; 可选本地小图 */
      background-repeat: no-repeat;
      background-size: cover;
    }
    /* 优化弹窗内容区域为白色，提升可读性 */
    .modal-content, .modal-body {
      background: #fff !important;
      color: #222 !important;
    }
    /* 表单控件适配深色/浅色背景 */
    .modal-content select,
    .modal-content input,
    .modal-content textarea {
      background: #fff !important;
      color: #222 !important;
      border: 1px solid #ccc;
    }
    /* 优化按钮样式 */
    .modal-content .btn-warning,
    .modal-content .btn {
      background: linear-gradient(90deg,#f7d774,#e6b800);
      color: #222;
      border: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .modal-content .btn-warning:hover,
    .modal-content .btn:hover {
      background: linear-gradient(90deg,#ffe066,#f7d774);
      color: #000;
    }
    .vip-panel {
      margin: 18px 0;
      background: linear-gradient(90deg,#1e2a4a,#f7d774 90%);
      color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(40,40,40,0.12);
      cursor: default;
      padding: 22px 24px 18px 24px;
    }
    .vip-title {
      font-size: 1.5rem;
      font-weight: bold;
      letter-spacing: 2px;
      color: #f7d774;
      margin-bottom: 10px;
    }
    .vip-info {
      font-size: 1.1rem;
      margin-bottom: 8px;
    }
    .vip-expire {
      color: #fffbe6;
      font-size: 1rem;
      margin-left: 10px;
    }
    .vip-btn {
      background: linear-gradient(90deg,#f7d774,#e6b800);
      color: #222;
      border: none;
      border-radius: 6px;
      padding: 7px 28px;
      font-size: 1.1rem;
      font-weight: bold;
      margin-top: 10px;
      margin-right: 10px;
      transition: background 0.2s;
      display: inline-block;
    }
    .vip-btn:hover {
      background: linear-gradient(90deg,#ffe066,#f7d774);
      color: #000;
    }
    .vip-rights {
      background: #fffbe6;
      color: #222;
      border-radius: 8px;
      padding: 18px 18px 10px 18px;
      margin-top: 18px;
      font-size: 1.1rem;
      box-shadow: 0 1px 6px rgba(40,40,40,0.08);
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: space-between;
    }
    .vip-rights-col {
      flex: 1 1 220px;
      min-width: 200px;
    }
    .vip-rights h4 {
      color: #bfa100;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .vip-rights ul {
      padding-left: 18px;
      margin-bottom: 0;
    }
    .vip-rights li {
      margin-bottom: 7px;
      line-height: 1.7;
    }
    .agent-link {
      display: inline-block;
      margin-top: 12px;
      background: #1e2a4a;
      color: #ffe066;
      padding: 6px 18px;
      border-radius: 6px;
      font-weight: bold;
      text-decoration: none;
      transition: background 0.2s;
    }
    .agent-link:hover {
      background: #f7d774;
      color: #1e2a4a;
    }
    .top-btns-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 550px;
      margin: 0 auto;
      padding: 16px 10px 0 10px;
    }
    .simple-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      background: #4a90e2;
      color: #fff;
      border: none;
      border-radius: 22px;
      font-size: 17px;
      font-weight: 500;
      padding: 8px 22px;
      box-shadow: 0 2px 8px rgba(80,160,255,0.10);
      cursor: pointer;
      transition: background 0.18s, box-shadow 0.18s;
      outline: none;
      letter-spacing: 1px;
    }
    .simple-btn:hover {
      background: #357ab8;
      box-shadow: 0 4px 16px rgba(80,160,255,0.18);
    }
    .simple-btn i {
      font-size: 18px;
    }
  </style>
</head>
<body>
<!-- 顶部操作栏 -->
<div class="top-btns-bar">
  <button onclick="window.history.back()" class="simple-btn" title="返回"><i class="fa fa-arrow-left"></i> 返回</button>
  <button onclick="showShareQr()" class="simple-btn" title="分享">分享 <i class="fa fa-share-alt"></i></button>
</div>
<div class="container" style="max-width: 550px; margin: 0 auto;">
  <div class="vip-panel">
    <div class="vip-title"><i class="fa fa-diamond"></i> 金融会员中心</div>
    <!-- 会员开通区域 -->
    <div class="vip-info">
      <h4>月会员</h4>
      <ul>
        <li>可浏览全部金融内容</li>
        <li>享受部分产品特价</li>
        <li>专属金融资讯推送</li>
        <li>无代理权限</li>
      </ul>
      <button class="btn btn-warning btn-block" onclick="openVipModal('month')">开通月会员（99元）</button>
      <hr>
      <h4>季会员</h4>
      <ul>
        <li>可浏览全部金融内容</li>
        <li>享受部分产品特价</li>
        <li>专属金融资讯推送</li>
        <li>无代理权限</li>
      </ul>
      <button class="btn btn-warning btn-block" onclick="openVipModal('quarter')">开通季会员（249元）</button>
      <hr>
      <h4>年会员</h4>
      <ul>
        <li>可浏览全部金融内容</li>
        <li>享受部分产品特价</li>
        <li>专属金融资讯推送</li>
        <li>无代理权限</li>
      </ul>
      <button class="btn btn-warning btn-block" onclick="openVipModal('year')">开通年会员（799元）</button>
      <hr>
      <h4>永久VIP</h4>
      <ul>
        <li>可浏览全部金融内容</li>
        <li>享受全部产品特价</li>
        <li>专属金融资讯推送</li>
        <li>无代理权限</li>
      </ul>
      <button class="btn btn-warning btn-block" onclick="openVipModal('forever')">开通永久VIP（1999元）</button>
      <hr>
      <h4>超级会员</h4>
      <ul>
        <li>可浏览全部金融内容</li>
        <li>享受全部产品特价</li>
        <li>专属金融资讯推送</li>
        <li>拥有代理权限，可发展下级</li>
        <li>专属客服一对一服务</li>
        <li>更高返佣比例</li>
      </ul>
      <button class="btn btn-warning btn-block" onclick="openSvipModal()">开通超级会员（月299/季749/年2399元）</button>
    </div>
  </div>
</div>
<script src="/assets/js/jquery.min.js" defer></script>
<script src="https://cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
function openVipModal(type) {
  if(!type) return;
  if(!confirm('确定要开通/续费该会员吗？')) return;
  $.post('', {action:'openvip', vip_type:type}, function(res){
    if(res.code==0){
      alert('开通/续费成功！到期时间：'+res.expire); location.reload();
    }else{
      alert(res.msg);
      if(res.code==-2) location.href='rmb.php';
    }
  },'json');
}
function openSvipModal(){ openVipModal('super'); }
// 分享二维码弹窗
function showShareQr(){
  if($('#shareQrModal').length===0){
    $('body').append('<div id="shareQrModal" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;"><div style="background:#fff;padding:20px 30px 15px 30px;border-radius:10px;text-align:center;position:relative;"><div id="qrcode"></div><div style="margin-top:10px;font-size:14px;color:#333;">扫码分享本页</div><button onclick="$('#shareQrModal').remove()" style="position:absolute;top:5px;right:10px;background:none;border:none;font-size:18px;color:#888;">×</button></div></div>');
    $('#qrcode').qrcode({width:150,height:150,text:window.location.href});
  }
}
</script>
</body>
</html> 