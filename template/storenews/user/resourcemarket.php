<?php
require_once __DIR__ . '/../../../includes/db.class.php';
require_once __DIR__ . '/../../../config.php';
$DB = DB::getInstance($dbconfig);
// 分类
$categories = $DB->query("SELECT DISTINCT class FROM shua_goods WHERE status=1")->fetchAll();
// 搜索与筛选
$where = "WHERE status=1";
if (!empty($_GET['resource_keyword'])) {
    $where .= " AND (name LIKE '%".addslashes($_GET['resource_keyword'])."%' OR content LIKE '%".addslashes($_GET['resource_keyword'])."%')";
}
if (!empty($_GET['resource_category'])) {
    $where .= " AND class = '".addslashes($_GET['resource_category'])."'";
}
$sql = "SELECT * FROM shua_goods $where ORDER BY id DESC LIMIT 100";
$resources = $DB->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>资源市场</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
    body { background: #f7f7fa; }
    .market-header { background: #fff; padding: 18px 0 8px 0; text-align: center; font-size: 22px; font-weight: bold; color: #ff6600; box-shadow: 0 2px 8px #eee; }
    .market-search { background: #fff; padding: 18px 12px 8px 12px; border-radius: 12px; margin: 18px auto 0 auto; max-width: 600px; box-shadow: 0 2px 8px #eee; }
    .market-list { margin: 24px auto 0 auto; max-width: 900px; }
    .resource-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #eee; margin-bottom: 18px; padding: 18px; }
    .resource-card h4 { font-size: 18px; font-weight: bold; color: #222; margin-bottom: 8px; }
    .resource-card p { color: #666; font-size: 14px; margin-bottom: 6px; }
    .resource-card .price { color: #ff6600; font-weight: bold; font-size: 16px; }
    .resource-card .btn { margin-right: 8px; }
    @media (max-width: 700px) {
      .market-header { font-size: 18px; }
      .market-search, .market-list { max-width: 98vw; }
      .resource-card { padding: 12px; }
    }
    </style>
</head>
<body>
    <div class="market-header">资源市场</div>
    <div class="market-search">
      <form class="form-inline" method="get" id="resource-search-form">
        <input type="text" name="resource_keyword" placeholder="搜索资源" class="form-control mb-2 mr-sm-2" value="<?=isset($_GET['resource_keyword'])?htmlspecialchars($_GET['resource_keyword']):''?>">
        <select name="resource_category" class="form-control mb-2 mr-sm-2">
          <option value="">全部分类</option>
          <?php foreach($categories as $cat){ ?>
            <option value="<?=$cat['class']?>"<?php if(isset($_GET['resource_category'])&&$_GET['resource_category']==$cat['class'])echo ' selected';?>><?=$cat['class']?></option>
          <?php } ?>
        </select>
        <button type="submit" class="btn btn-primary mb-2">搜索</button>
        <button type="button" class="btn btn-success mb-2 ml-2" id="resource-publish-btn">发布资源</button>
      </form>
    </div>
    <div class="market-list row">
      <?php if (!empty($resources)) { foreach($resources as $res){ ?>
      <div class="col-md-4">
        <div class="resource-card">
          <h4><?=htmlspecialchars($res['name'])?></h4>
          <p><?=mb_substr(strip_tags($res['content']),0,40)?>...</p>
          <p>分类：<?=htmlspecialchars($res['class'])?></p>
          <p class="price">￥<?=htmlspecialchars($res['price'])?></p>
          <button class="btn btn-primary btn-sm" onclick="showResourceDetail(<?=$res['id']?>)">查看详情</button>
          <button class="btn btn-warning btn-sm" onclick="buyResource(<?=$res['id']?>)">购买</button>
        </div>
      </div>
      <?php }} else { ?>
        <div class="col-12" style="padding:2em;text-align:center;color:#888;">暂无资源</div>
      <?php } ?>
    </div>
    <!-- 资源发布弹窗 -->
    <div class="modal fade" id="resource-publish-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <form method="post" enctype="multipart/form-data" id="resource-publish-form">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">发布资源</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <input type="text" name="name" class="form-control" placeholder="资源名称" required>
              <textarea name="content" class="form-control" placeholder="资源详情" required></textarea>
              <input type="text" name="class" class="form-control" placeholder="分类ID" required>
              <input type="number" name="price" class="form-control" placeholder="价格" required>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">提交发布</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- 资源详情弹窗 -->
    <div class="modal fade" id="resource-detail-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content" id="resource-detail-content"></div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // 发布资源弹窗
    $('#resource-publish-btn').click(function(){
      $('#resource-publish-modal').modal('show');
    });
    // 资源详情弹窗
    window.showResourceDetail = function(id){
      $.get('/news/api.php?resource_id='+id, function(data){
        if(data && data.id){
          $('#resource-detail-content').html(`
            <div class=\"modal-header\">\n              <h4 class=\"modal-title\">${data.name}</h4>\n              <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>\n            </div>\n            <div class=\"modal-body\">\n              <p><b>分类：</b>${data.class}</p>\n              <p><b>价格：</b>￥${data.price}</p>\n              <div>${data.content}</div>\n            </div>\n            <div class=\"modal-footer\">\n              <button class=\"btn btn-warning\" onclick=\"buyResource(${data.id})\">购买</button>\n            </div>\n          `);
          $('#resource-detail-modal').modal('show');
        }
      },'json');
    }
    window.buyResource = function(id){
      if(confirm('确定要购买该资源吗？')){
        window.location.href = '/news/buy.php?id=' + id;
      }
    }
    // 资源发布表单校验
    $('#resource-publish-form').submit(function(){
      if(!this.name.value||!this.content.value||!this.class.value||!this.price.value){alert('请填写完整');return false;}
    });
    </script>
</body>
</html> 