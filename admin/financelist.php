<?php
// 金融资源管理页面，字段严格对应shua_news表结构，支持弹窗表单
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 引入数据库配置
require_once dirname(__DIR__).'/config.php';
$host = $dbconfig['host'];
$dbname = $dbconfig['dbname'];
$user = $dbconfig['user'];
$pass = $dbconfig['pwd'];
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $DB = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit('数据库连接失败: ' . $e->getMessage());
}

// 处理新增/编辑/删除操作
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $title = trim($_POST['title']);
        $desc = trim($_POST['desc']);
        $public_content = trim($_POST['public_content']);
        $vip_content = trim($_POST['vip_content']);
        $category_id = intval($_POST['category_id']);
        $cover_url = trim($_POST['cover_url']);
        $add_time = date('Y-m-d H:i:s');
        if ($title) {
            $stmt = $DB->prepare("INSERT INTO shua_news (title, `desc`, public_content, vip_content, category_id, cover_url, add_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $desc, $public_content, $vip_content, $category_id, $cover_url, $add_time]);
            header('Location: '.$_SERVER['PHP_SELF'].'?page='.intval($_GET['page'] ?? 1)); exit;
        } else {
            $msg = '请填写完整信息';
        }
    } elseif (isset($_POST['edit_id']) && $_POST['edit_id'] !== '') {
        $id = intval($_POST['edit_id']);
        $title = trim($_POST['title']);
        $desc = trim($_POST['desc']);
        $public_content = trim($_POST['public_content']);
        $vip_content = trim($_POST['vip_content']);
        $category_id = intval($_POST['category_id']);
        $cover_url = trim($_POST['cover_url']);
        $stmt = $DB->prepare("UPDATE shua_news SET title=?, `desc`=?, public_content=?, vip_content=?, category_id=?, cover_url=? WHERE id=?");
        $stmt->execute([$title, $desc, $public_content, $vip_content, $category_id, $cover_url, $id]);
        header('Location: '.$_SERVER['PHP_SELF'].'?page='.intval($_GET['page'] ?? 1)); exit;
    } elseif (isset($_POST['del_id'])) {
        $id = intval($_POST['del_id']);
        $stmt = $DB->prepare("DELETE FROM shua_news WHERE id=?");
        $stmt->execute([$id]);
        header('Location: '.$_SERVER['PHP_SELF'].'?page='.intval($_GET['page'] ?? 1)); exit;
    }
}
// 分页参数
$page = max(1, intval($_GET['page'] ?? 1));
$pageSize = 30;
$offset = ($page - 1) * $pageSize;
// 查询总数
$total = $DB->query("SELECT COUNT(*) FROM shua_news")->fetchColumn();
// 查询当前页数据
$stmt = $DB->prepare("SELECT * FROM shua_news ORDER BY id DESC LIMIT :offset, :pagesize");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':pagesize', $pageSize, PDO::PARAM_INT);
$stmt->execute();
$newslist = $stmt ? $stmt->fetchAll() : [];
// 分类选项
$cat_stmt = $DB->query("SELECT id, name FROM shua_news_category ORDER BY sort DESC, id ASC");
$categories = $cat_stmt ? $cat_stmt->fetchAll() : [];
?><!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>金融资源管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.staticfile.org/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container" style="max-width:1100px;margin:30px auto;">
  <h3 style="margin-bottom:20px;">金融资源管理 <button class="btn btn-success btn-xs pull-right" onclick="showAddForm()">添加资源</button></h3>
  <?php if($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
  <table class="table table-bordered">
    <tr>
      <th>ID</th>
      <th>资源名称</th>
      <th>简介</th>
      <th>公开内容</th>
      <th>会员可见内容</th>
      <th>分类</th>
      <th>封面图</th>
      <th>添加时间</th>
      <th>操作</th>
    </tr>
    <?php if(empty($newslist)): ?>
      <tr><td colspan="9" style="text-align:center;">暂无金融资源</td></tr>
    <?php endif; ?>
    <?php foreach($newslist as $item): ?>
    <tr>
      <td><?php echo $item['id']; ?></td>
      <td><?php echo htmlspecialchars($item['title']); ?></td>
      <td><?php echo mb_substr(strip_tags($item['desc']),0,30,'utf-8'); ?></td>
      <td><?php echo htmlspecialchars(mb_substr($item['public_content'],0,20,'utf-8')); ?></td>
      <td><?php echo htmlspecialchars(mb_substr($item['vip_content'],0,20,'utf-8')); ?></td>
      <td><?php
        $catname = '';
        foreach($categories as $cat){ if($cat['id']==$item['category_id']){$catname=$cat['name'];break;} }
        echo htmlspecialchars($catname);
      ?></td>
      <td><?php if($item['cover_url']){ ?><img src="<?php echo htmlspecialchars($item['cover_url']); ?>" style="max-width:60px;max-height:40px;"/><?php } ?></td>
      <td><?php echo $item['add_time']; ?></td>
      <td>
        <button class="btn btn-primary btn-xs" onclick="showEditForm(
          <?php echo $item['id']; ?>,
          '<?php echo htmlspecialchars(addslashes($item['title'])); ?>',
          '<?php echo htmlspecialchars(addslashes($item['desc'])); ?>',
          '<?php echo htmlspecialchars(addslashes($item['public_content'])); ?>',
          '<?php echo htmlspecialchars(addslashes($item['vip_content'])); ?>',
          '<?php echo $item['category_id']; ?>',
          '<?php echo htmlspecialchars(addslashes($item['cover_url'])); ?>'
        )">编辑</button>
        <form method="post" style="display:inline;" onsubmit="return confirm('确定删除？')">
          <input type="hidden" name="del_id" value="<?php echo $item['id']; ?>">
          <button type="submit" class="btn btn-danger btn-xs">删除</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <!-- 分页导航 -->
  <?php
  $totalPages = ceil($total / $pageSize);
  if($totalPages > 1){
    echo '<nav><ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
      $active = $i == $page ? 'active' : '';
      echo "<li class='$active'><a href='?page=$i'>$i</a></li>";
    }
    echo '</ul></nav>';
  }
  ?>
  <!-- 添加/编辑弹窗Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="post" id="resourceForm">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="editModalLabel">新增资源</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" name="edit_id" id="edit_id">
            <input type="hidden" name="add" id="add_flag" value="1">
            <div class="form-group">
              <label>资源名称 <span style="color:red">*</span></label>
              <input type="text" class="form-control" name="title" id="title" required>
            </div>
            <div class="form-group">
              <label>简介</label>
              <textarea class="form-control" name="desc" id="desc"></textarea>
            </div>
            <div class="form-group">
              <label>公开内容</label>
              <button type="button" class="btn btn-default btn-xs" onclick="insertImage('public_content')">插入图片</button>
              <textarea class="form-control" name="public_content" id="public_content" style="min-height:80px;"></textarea>
            </div>
            <div class="form-group">
              <label>会员可见内容</label>
              <button type="button" class="btn btn-default btn-xs" onclick="insertImage('vip_content')">插入图片</button>
              <textarea class="form-control" name="vip_content" id="vip_content" style="min-height:80px;"></textarea>
            </div>
            <div class="form-group">
              <label>分类</label>
              <select class="form-control" name="category_id" id="category_id">
                <option value="0">无</option>
                <?php foreach($categories as $cat): ?>
                  <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>封面图URL</label>
              <input type="text" class="form-control" name="cover_url" id="cover_url">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-success">保存</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function showAddForm(){
  $('#editModalLabel').text('新增资源');
  $('#resourceForm')[0].reset();
  $('#edit_id').val('');
  $('#add_flag').val('1').attr('name','add');
  $('#editModal').modal('show');
}
function showEditForm(id, title, desc, public_content, vip_content, category_id, cover_url){
  $('#editModalLabel').text('编辑资源');
  $('#edit_id').val(id);
  $('#title').val(title);
  $('#desc').val(desc);
  $('#public_content').val(public_content);
  $('#vip_content').val(vip_content);
  $('#category_id').val(category_id);
  $('#cover_url').val(cover_url);
  $('#add_flag').val('').attr('name','');
  $('#editModal').modal('show');
}
$('#editModal').on('hidden.bs.modal', function () {
  $('#resourceForm')[0].reset();
  $('#edit_id').val('');
  $('#add_flag').val('1').attr('name','add');
});
// 插入图片按钮功能
function insertImage(textareaId){
  var url = prompt('请输入图片URL：');
  if(url){
    var textarea = document.getElementById(textareaId);
    var start = textarea.selectionStart, end = textarea.selectionEnd;
    var before = textarea.value.substring(0, start);
    var after = textarea.value.substring(end);
    var imgTag = '<img src="'+url+'" style="max-width:100%;">';
    textarea.value = before + imgTag + after;
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = before.length + imgTag.length;
  }
}
</script>
</body>
</html> 