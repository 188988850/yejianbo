
<?php
/**
 * 付费群管理
**/
include("../includes/common.php");
$title='付费群管理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>



<?php 
if($_GET['typesc']){
    $id=$_GET['typesc'];
  
    $sql = "delete FROM `pre_sscc` where id='" . $id . "'";
    $DB->query($sql);
    echo '<script>alert("删除成功！");！</script>';
    echo '<script>window.location.href = "./fufeulist.php";</script>';
}
?> 

<?php 
if($_GET['typesc']){
    $id = $_GET['typesc'];
?>
<script>
    var confirmDelete = confirm("您确定要删除此记录吗？");
    if(confirmDelete){
        window.location.href = "fufeulist.php?typesc=<?php echo $id; ?>";
    }
</script>
<?php
}
?>
<div class="col-sm-12 col-md-12 col-lg-12 center-block" style="float: none;padding-top: 10px;">
            <div class="block">
                <div class="block-title">
                    <h2>付费群列表</h2>
                </div>
                <a href="./fuxg.php?my=add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;发布内容</a>
                <div id="pcTable" class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                              
                                <th>
                                  id
                                </th>
                                <th>
                                    群名称
                                </th>
                                 <th>
                                    订单数
                                </th>
                                  <th>
                                    入群金额
                                </th>
                                <th>
                                    操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
   <?php		$rs=$DB->query("SELECT * FROM pre_sscc order by id desc");
    while ($res = $rs->fetch()) {?>
                         
                            <td>  <b><?php echo $res['id']?></b></td>
                            <td><?php echo $res['name']?></td>
                             <td><?php echo $res['orsers']?></td>
                              <td><?php echo $res['money']?></td>
                            
                            <td><a href="fuxg.php?my=edit&id=<?php echo $res['id']?>" class="btn btn-primary btn-xs"> 编辑</a><a href="?typesc=<?php echo $res['id']?>" class="btn btn-danger btn-xs"> 删除</a></td
                            >
                       </tr><?php }?>
                       </tbody>
        </table>
      </div>
     
    
            </div>
        </div>
