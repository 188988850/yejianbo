<?php
require_once('../includes/common.php');
if($islogin!=1) exit("<script language='javascript'>window.location.href='./login.php';</script>");

// 数据库连接
try {
    $dsn = "mysql:host={$dbconfig['host']};port={$dbconfig['port']};dbname={$dbconfig['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbconfig['user'], $dbconfig['pwd']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('数据库连接失败: ' . $e->getMessage());
}

// 分类管理处理
if(isset($_POST['cat_action'])) {
    $cat_name = trim($_POST['cat_name']);
    $cat_id = intval($_POST['cat_id']);
    if($_POST['cat_action'] == 'add' && $cat_name) {
        $pdo->prepare("INSERT INTO {$dbconfig['dbqz']}_resource_category (class) VALUES (?)")->execute([$cat_name]);
    } elseif($_POST['cat_action'] == 'edit' && $cat_id && $cat_name) {
        $pdo->prepare("UPDATE {$dbconfig['dbqz']}_resource_category SET class=? WHERE id=?")->execute([$cat_name, $cat_id]);
    } elseif($_POST['cat_action'] == 'delete' && $cat_id) {
        $pdo->prepare("DELETE FROM {$dbconfig['dbqz']}_resource_category WHERE id=?")->execute([$cat_id]);
    }
}

// 资源管理处理（添加/编辑/删除/显示隐藏/文档上传）
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if($_POST['action'] == 'add' || $_POST['action'] == 'edit') {
        try {
            $name = trim($_POST['name']);
            $content = trim($_POST['content']);
            $class = trim($_POST['class']);
            $hidden_content = isset($_POST['hidden_content']) ? trim($_POST['hidden_content']) : '';
            $price = floatval($_POST['price']);
            $is_show = isset($_POST['is_show']) ? 1 : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $image = '';
            $doc = '';
            
            // 处理图片上传
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $upload_dir = '../uploads/resources/';
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                    $image = '/uploads/resources/' . $filename;
                }
            }
            
            // 处理文档上传
            if(isset($_FILES['doc']) && $_FILES['doc']['error'] == 0) {
                $upload_dir = '../uploads/resources/';
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                if(move_uploaded_file($_FILES['doc']['tmp_name'], $upload_dir . $filename)) {
                    $doc = '/uploads/resources/' . $filename;
                }
            }
            
            if($_POST['action'] == 'add') {
                $sql = "INSERT INTO {$dbconfig['dbqz']}_goods (name, content, class, price, image, doc, is_show, status, hidden_content, addtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $content, $class, $price, $image, $doc, $is_show, $status, $hidden_content, date('Y-m-d H:i:s')]);
                $msg = '添加成功！';
            } else {
                $id = intval($_POST['id']);
                $fields = "name=?, content=?, class=?, price=?, is_show=?, status=?, hidden_content=?";
                $params = [$name, $content, $class, $price, $is_show, $status, $hidden_content];
                if($image) {
                    $fields .= ", image=?";
                    $params[] = $image;
                }
                if($doc) {
                    $fields .= ", doc=?";
                    $params[] = $doc;
                }
                $params[] = $id;
                $sql = "UPDATE {$dbconfig['dbqz']}_goods SET $fields WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $msg = '更新成功！';
            }
            
            // 如果是AJAX请求，返回JSON响应
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                exit(json_encode(['code'=>1, 'msg'=>$msg]));
            }
            
        } catch(Exception $e) {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                exit(json_encode(['code'=>0, 'msg'=>'保存失败：'.$e->getMessage()]));
            }
            $error = '保存失败：'.$e->getMessage();
        }
    } elseif($_POST['action'] == 'delete') {
        try {
            $id = intval($_POST['id']);
            $sql = "DELETE FROM {$dbconfig['dbqz']}_goods WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $msg = '删除成功！';
            
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                exit(json_encode(['code'=>1, 'msg'=>$msg]));
            }
        } catch(Exception $e) {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                exit(json_encode(['code'=>0, 'msg'=>'删除失败：'.$e->getMessage()]));
            }
            $error = '删除失败：'.$e->getMessage();
        }
    } elseif($_POST['action'] == 'toggle_show') {
        try {
            $id = intval($_POST['id']);
            $is_show = intval($_POST['is_show']);
            $sql = "UPDATE {$dbconfig['dbqz']}_goods SET is_show=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$is_show, $id]);
            exit(json_encode(['code'=>1, 'msg'=>'操作成功']));
        } catch(Exception $e) {
            exit(json_encode(['code'=>0, 'msg'=>'操作失败：'.$e->getMessage()]));
        }
    } elseif($_POST['action'] == 'audit') {
        try {
            $id = intval($_POST['id']);
            $status = intval($_POST['status']);
            $sql = "UPDATE {$dbconfig['dbqz']}_goods SET status=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$status, $id]);
            exit(json_encode(['code'=>1, 'msg'=>'审核成功']));
        } catch(Exception $e) {
            exit(json_encode(['code'=>0, 'msg'=>'审核失败：'.$e->getMessage()]));
        }
    }
}

// 获取分类和资源
$categories = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_resource_category ORDER BY sort DESC, id ASC")->fetchAll();
$resources = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_goods ORDER BY id DESC")->fetchAll();
$pending = $pdo->query("SELECT * FROM {$dbconfig['dbqz']}_goods WHERE status=0 ORDER BY id DESC")->fetchAll();

$title = '资源市场';
include './head.php';
?>
<style>
.resadmin-card { background: #fff; border-radius: 1.2rem; box-shadow: 0 2px 12px #e0e0e0; padding: 1.5rem 1.2rem; margin-bottom: 2rem; }
.resadmin-title { font-size: 1.3rem; font-weight: 600; color: #2980b9; margin-bottom: 1rem; }
.resadmin-table th, .resadmin-table td { vertical-align: middle!important; }
.resadmin-table th { background: #f6f9fc; }
.resadmin-table .btn { border-radius: 1.2rem; font-size: 0.95rem; }
@media (max-width: 767px) {
  .resadmin-card { padding: 1rem 0.5rem; }
  .resadmin-title { font-size: 1.1rem; }
}
</style>
<div class="main pjaxmain">
    <div class="row">
        <!-- 分类管理 -->
        <div class="col-md-3 mb-3">
            <div class="resadmin-card">
                <div class="resadmin-title">资源分类管理</div>
                <form method="post" class="form-inline mb-2">
                    <input type="text" name="cat_name" class="form-control form-control-sm mr-2" placeholder="新分类名" required>
                    <input type="hidden" name="cat_action" value="add">
                    <button type="submit" class="btn btn-success btn-sm">添加</button>
                </form>
                <ul class="list-group">
                    <?php foreach($categories as $cat): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <span><?php echo htmlspecialchars($cat['class']); ?></span>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="cat_id" value="<?php echo $cat['id']; ?>">
                            <input type="hidden" name="cat_action" value="delete">
                            <button type="submit" class="btn btn-danger btn-xs btn-sm" onclick="return confirm('确定删除？')">删除</button>
                        </form>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!-- 资源管理主界面 -->
        <div class="col-md-9">
            <div class="resadmin-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="resadmin-title mb-0">资源管理</div>
                    <a href="#" class="btn btn-success btn-xs mb-2" onclick="showAddModal();return false;">添加资源</a>
                </div>
                <ul class="nav nav-tabs mb-2">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#all">全部资源</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pending">投稿审核</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="all">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped resadmin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>图片</th>
                                        <th>名称</th>
                                        <th>分类</th>
                                        <th>价格</th>
                                        <th>文档</th>
                                        <th>显示</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($resources as $resource): ?>
                                    <tr>
                                        <td><?php echo $resource['id']; ?></td>
                                        <td><img src="<?php echo !empty($resource['image']) ? $resource['image'] : '/assets/img/default-resource.jpg'; ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;"></td>
                                        <td><?php echo htmlspecialchars($resource['name']); ?></td>
                                        <td><?php echo htmlspecialchars($resource['class']); ?></td>
                                        <td>￥<?php echo htmlspecialchars($resource['price']); ?></td>
                                        <td><?php if($resource['doc']): ?><a href="<?php echo $resource['doc']; ?>" target="_blank" class="btn btn-outline-info btn-sm py-0">下载</a><?php endif; ?></td>
                                        <td><input type="checkbox" onchange="toggleShow(<?php echo $resource['id']; ?>, this.checked)" <?php if($resource['is_show']) echo 'checked'; ?>></td>
                                        <td><?php echo $resource['addtime']; ?></td>
                                        <td>
                                            <button class="btn btn-info btn-xs btn-sm" onclick="showEditModal(<?php echo htmlspecialchars(json_encode($resource)); ?>)">编辑</button>
                                            <button class="btn btn-danger btn-xs btn-sm" onclick="deleteResource(<?php echo $resource['id']; ?>)">删除</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pending">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped resadmin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>名称</th>
                                        <th>分类</th>
                                        <th>价格</th>
                                        <th>投稿用户</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($pending as $resource): ?>
                                    <tr>
                                        <td><?php echo $resource['id']; ?></td>
                                        <td><?php echo htmlspecialchars($resource['name']); ?></td>
                                        <td><?php echo htmlspecialchars($resource['class']); ?></td>
                                        <td>￥<?php echo htmlspecialchars($resource['price']); ?></td>
                                        <td><?php echo $resource['user_id']; ?></td>
                                        <td>
                                            <button class="btn btn-success btn-xs btn-sm" onclick="auditResource(<?php echo $resource['id']; ?>,1)">通过</button>
                                            <button class="btn btn-danger btn-xs btn-sm" onclick="auditResource(<?php echo $resource['id']; ?>,2)">拒绝</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 添加/编辑资源模态框 -->
<div class="modal fade" id="resourceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTitle">添加资源</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resourceForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label>资源名称</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>资源分类</label>
                        <select class="form-control" name="class" required>
                            <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['class']; ?>"><?php echo $category['class']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>资源价格</label>
                        <input type="number" class="form-control" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>资源图片</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>资源文档</label>
                        <input type="file" class="form-control" name="doc" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
                    </div>
                    <div class="form-group">
                        <label>资源详情</label>
                        <textarea class="form-control" name="content" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>隐藏资源内容（付费后可见）</label>
                        <textarea class="form-control" name="hidden_content" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="is_show" value="1" checked> 前台显示</label>
                    </div>
                    <div class="form-group">
                        <label>状态</label>
                        <select class="form-control" name="status">
                            <option value="1">正常/已审核</option>
                            <option value="0">待审核</option>
                            <option value="2">未通过</option>
                        </select>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script>
function showAddModal() {
    $('#modalTitle').text('添加资源');
    $('#resourceForm')[0].reset();
    $('#resourceForm input[name="action"]').val('add');
    $('#resourceForm input[name="id"]').val('');
    $('#resourceModal').modal('show');
}

function showEditModal(resource) {
    $('#modalTitle').text('编辑资源');
    $('#resourceForm input[name="action"]').val('edit');
    $('#resourceForm input[name="id"]').val(resource.id);
    $('#resourceForm input[name="name"]').val(resource.name);
    $('#resourceForm select[name="class"]').val(resource.class);
    $('#resourceForm input[name="price"]').val(resource.price);
    $('#resourceForm textarea[name="content"]').val(resource.content);
    $('#resourceForm textarea[name="hidden_content"]').val(resource.hidden_content||'');
    $('#resourceForm input[name="is_show"]').prop('checked', resource.is_show==1);
    $('#resourceForm select[name="status"]').val(resource.status);
    $('#resourceModal').modal('show');
}

function deleteResource(id) {
    if(confirm('确定要删除这个资源吗？')) {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                action: 'delete',
                id: id
            },
            dataType: 'json',
            success: function(res) {
                alert(res.msg);
                if(res.code == 1) {
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('删除失败：' + (xhr.responseText || '未知错误'));
            }
        });
    }
}

function toggleShow(id, checked) {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            action: 'toggle_show',
            id: id,
            is_show: checked ? 1 : 0
        },
        dataType: 'json',
        success: function(res) {
            if(res.code == 0) {
                alert(res.msg);
                location.reload();
            }
        },
        error: function(xhr) {
            alert('操作失败：' + (xhr.responseText || '未知错误'));
            location.reload();
        }
    });
}

function auditResource(id, status) {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            action: 'audit',
            id: id,
            status: status
        },
        dataType: 'json',
        success: function(res) {
            alert(res.msg);
            if(res.code == 1) {
                location.reload();
            }
        },
        error: function(xhr) {
            alert('审核失败：' + (xhr.responseText || '未知错误'));
        }
    });
}

// 资源表单提交
$('#resourceForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            alert(res.msg);
            if(res.code == 1) {
                $('#resourceModal').modal('hide');
                location.reload();
            }
        },
        error: function(xhr) {
            alert('保存失败：' + (xhr.responseText || '未知错误'));
        }
    });
});

<?php if(isset($msg)): ?>
alert('<?php echo $msg; ?>');
<?php endif; ?>
<?php if(isset($error)): ?>
alert('<?php echo $error; ?>');
<?php endif; ?>
</script>
<?php include './foot.php'; ?> 