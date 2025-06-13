<?php
include("../includes/common.php");
if($islogin==1){
    $userrow = $DB->getRow("SELECT * FROM shua_site WHERE zid='{$admin_zid}' limit 1");
    $level_discount = [1=>1.0, 2=>0.9, 3=>0.8];
    $discount = isset($level_discount[$userrow['power']]) ? $level_discount[$userrow['power']] : 1.0;
} else {
    exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
$goods = $DB->getAll("SELECT * FROM shua_tools WHERE active=1 AND close=0");
?>
<div style="padding:10px;background:#f5f5f5;">
    当前账号：<?php echo htmlspecialchars($userrow['user']); ?>　
    余额：<b id="balance" style="color:#ff6600;\"><?php echo $userrow['rmb']; ?></b>　
    等级：<?php echo $userrow['power']; ?>　
    折扣：<?php echo ($discount*10).'折'; ?>
</div>
<table class="table table-bordered">
    <tr><th>商品名</th><th>原价</th><th>会员价</th><th>操作</th></tr>
    <?php foreach($goods as $g): 
        $vip_price = round($g['price'] * $discount, 2);
    ?>
    <tr>
        <td><?php echo $g['name']; ?></td>
        <td><?php echo $g['price']; ?></td>
        <td style="color:#ff6600;\"><?php echo $vip_price; ?></td>
        <td>
            <form method="post" action="buy.php" class="buy-form" style="display:inline;">
                <input type="hidden" name="goods_id" value="<?php echo $g['tid']; ?>">
                <input type="hidden" name="price" value="<?php echo $vip_price; ?>">
                <button type="submit" class="btn btn-primary">购买</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<div id="buy-result"></div>
<script>
function refreshBalance(){
    fetch('get_balance.php').then(r=>r.text()).then(bal=>{
        document.getElementById('balance').innerText = bal;
    });
}
document.querySelectorAll('.buy-form').forEach(function(form){
  form.onsubmit = function(e){
    e.preventDefault();
    var data = new FormData(form);
    fetch('buy.php', {method:'POST', body:data})
      .then(r=>r.text())
      .then(txt=>{
        document.getElementById('buy-result').innerHTML = txt;
        if(txt.indexOf('成功')!==-1){
          refreshBalance();
        }
      });
  }
});
</script> 