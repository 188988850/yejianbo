<?php
include("./head.php");
// 获取当前登录用户信息
if($islogin==1){
    $userrow = $DB->getRow("SELECT * FROM shua_site WHERE zid='{$admin_zid}' limit 1");
    $user_id = $userrow['zid'];
    $user_power = $userrow['power'];
    $user_balance = $userrow['rmb'];
} else {
    $user_id = 0;
    $user_power = 0;
    $user_balance = 0;
}
// 会员等级映射
$level_map = [0=>'游客/普通用户',1=>'普通用户',2=>'初级站长',3=>'顶级站长'];
// 获取所有金融资源
$news = $DB->getAll("SELECT * FROM shua_news WHERE 1 ORDER BY id DESC");
// 查询当前用户已购买的资源ID
$purchased = [];
if($user_id){
    $rs = $DB->query("SELECT news_id FROM shua_news_order WHERE user_id='{$user_id}'");
    while($row = $rs->fetch()){
        $purchased[] = $row['news_id'];
    }
}
?>
<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title">金融资源列表</h3></div>
  <div class="panel-body">
    <table class="table table-bordered">
      <tr>
        <th>资源名称</th>
        <th>简介</th>
        <th>游客/普通用户价</th>
        <th>初级站长价</th>
        <th>顶级站长价</th>
        <th>会员可见内容</th>
        <th>操作</th>
      </tr>
      <?php foreach($news as $item): ?>
      <tr>
        <td><?php echo htmlspecialchars($item['title']??$item['name']); ?></td>
        <td><?php echo mb_substr(strip_tags($item['public_content']??$item['desc']),0,30,'utf-8'); ?></td>
        <td style="color:#ff6600;">￥<?php echo $item['price_guest']; ?></td>
        <td style="color:#ff6600;">￥<?php echo $item['price_junior']; ?></td>
        <td style="color:#ff6600;">￥<?php echo $item['price_senior']; ?></td>
        <td>
          <?php
          $can_see = false;
          if($user_power>=3){ // 顶级站长
            $can_see = true;
          }elseif($user_power==2 && isset($item['price_junior'])){
            $can_see = true;
          }elseif($user_id && in_array($item['id'],$purchased)){
            $can_see = true;
          }
          if($can_see){
            echo '<div style="color:green">'.nl2br(htmlspecialchars($item['vip_content']??$item['vip_content'])).'</div>';
          }else{
            echo '<div style="color:#aaa">（会员可见内容，购买或升级会员后可查看）</div>';
          }
          ?>
        </td>
        <td>
          <?php
          // 价格选择
          if($user_power>=3){
            $price = $item['price_senior'];
          }elseif($user_power==2){
            $price = $item['price_junior'];
          }else{
            $price = $item['price_guest'];
          }
          if($user_id && in_array($item['id'],$purchased)){
            echo '<span class="label label-success">已购买</span>';
          }else{
            echo '<form method="post" action="finance_buy.php" class="buy-form" style="display:inline;">';
            echo '<input type="hidden" name="news_id" value="'.$item['id'].'">';
            echo '<input type="hidden" name="price" value="'.$price.'">';
            echo '<button type="submit" class="btn btn-primary btn-xs">购买￥'.$price.'</button>';
            echo '</form>';
          }
          ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <div id="buy-result"></div>
  </div>
</div>
<script>
document.querySelectorAll('.buy-form').forEach(function(form){
  form.onsubmit = function(e){
    e.preventDefault();
    var data = new FormData(form);
    fetch('finance_buy.php', {method:'POST', body:data})
      .then(r=>r.text())
      .then(txt=>{
        document.getElementById('buy-result').innerHTML = txt;
        if(txt.indexOf('成功')!==-1){
          setTimeout(function(){location.reload();}, 1000);
        }
      });
  }
});
</script> 