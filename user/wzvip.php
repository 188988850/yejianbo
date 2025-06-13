<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>短剧会员开通</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
  <link rel="stylesheet" href="../assets/store/css/content.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
   <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body>
<style>
    .layui-carousel, .layui-carousel>[carousel-item]>* {
        background-color:transparent;
    }    
    .layui-layer-title {
        padding: 0 80px 0 20px;
        height: 42px;
        line-height: 42px;
        border-bottom: 0px solid #fff1dc;
        font-size: 14px;
        color: #333;
        overflow: hidden;
        background-color: #fff1dc;
        border-radius: 2px !important;
    }
    .layui-layer-btn .layui-layer-btn0 {
        border-color: #fff1dc;
        background-color: #fff1dc;
        color: #333;
        font-size: 13px;
        border-radius: 10px !important;
    }
    .container {
        max-width: 100%;
        margin: 10px auto;
        background-color: #fff;
        padding: 0px;
        border-radius: 10px;
    }
    th {
        background-color: #f5f5f5;
        font-weight: bold;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }
</style>

<?php
/**
 * 自助升级站点
**/
include("../includes/common.php");
$title='短剧会员开通';

if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<?php
if($userrow['uu']==1){
	showmsg('您已开通过短剧VIP！',3);
}
?>
<?php
$price = isset($siterow['jumoney']) && $siterow['jumoney']!== '0'? $siterow['jumoney'] : 2;
 $timestamp = date('YmdHis'); 

 $upzid = $userrow['upzid'];
 while($upzid > 0) {
     	$siterow2 = $DB->getRow("SELECT `uu`, `zid` FROM `shua_site` WHERE `zid`='{$upzid}'");
    
   
    if($siterow2['uu'] == 1) {
        $upzid = $siterow2['zid'];
      
        break;
    } else {
        $upzid = $siterow2['upzid'];
    }
}
if($upzid > 0){
    
    
	$upsite = $DB->getRow("SELECT zid,uu,jumoney,ktfz_price2 FROM shua_site WHERE zid='$upzid' LIMIT 1");
	
	if($upsite ){
	    
	    
		if($upsite['ktfz_price2'] && $upsite['ktfz_price2']>0){
			$price = isset($upsite['jumoney']) && $upsite['jumoney']!== '0'? $upsite['jumoney'] : 2;
		}
		$tc_point=round($price, 2);
	}
}
if($_GET['act']=='submit'){
	if(!checkRefererHost())exit();
	if($price>$userrow['rmb'])exit("<script language='javascript'>alert('你的余额不足，请充值！');window.location.href='recharge.php';</script>");
	$DB->exec("UPDATE `shua_site` SET `uu`=1,`rmb`=`rmb`-'{$price}' WHERE `zid`='{$userrow['zid']}'");
     $tc1=$userrow['zid'];
    $existingTransaction = $DB->getRow("SELECT * FROM shua_points WHERE  duanju = '1' AND zid = '$tc1'");
     
    
        if(!$existingTransaction) {
            
         $sql_updfggg = "INSERT INTO shua_points (addtime, duanju, zid, action, bz, point, status) 
                        VALUES ('$timestamp','1', '$tc1', '消费', '开通短剧VIP会员', '$price', 0)";
        $DB->query($sql_updfggg);
      
     
       
   

	
	if(isset($tc_point) && $tc_point>0){
	    $tc2= $upsite['zid'];
	    $rrm = $tc_point*0.5;
        $sql_upffs = "UPDATE shua_site SET rmbtc = rmbtc + $rrm WHERE zid = $tc2";
        
        $sql_upffsss = "UPDATE shua_site SET rmb = rmb + $rrm WHERE zid = $tc2";
       $DB->query($sql_upffsss);
       
        $sql_updfggg = "INSERT INTO shua_points (addtime, duanju,zid, action, bz, point, status) 
                       VALUES ('$timestamp','1', '$tc2', '提成', '你网站的用户开通短剧会员,获得{$rrm}元提成', '$rrm', 0)";
        $DB->query($sql_updfggg);
        
        $DB->query($sql_upffs);
	    
	
	} }
	exit("<script language='javascript'>alert('恭喜你成功开通短剧会员！');window.location.href='../?mod=duanju';</script>");
}
?>
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block " style="float: none; background-color:#fff;padding:0;max-width: 550px;">
    <div class="block  block-all">
        <div class="block-white">
            <div class="block-back display-row align-center justify-between">
                <div style="border-width: .5px;
    border-radius: 100px;
    border-color: #dadbde;
    background-color: #f2f2f2;
    padding: 3px 7px;
    opacity: .8;align-items: center;justify-content: space-between;display: flex; flex-direction: row;height: 30px;">
                <a href="javascript:history.back()" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">短剧会员开通</a></font>

            </div>
                </div>

<div class="main-content">
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">

                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>短剧VIP会员说明</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="font-size: 1.3rem;color: #858585;">
                    <font color="#980000">抖音快手热门短剧、新剧首发全集，热门网剧全集<br><br>
                    永久更新，每天都会更新短剧，让您不再四处找剧<br><br>
                    一次付费，永久免费使用，永不过期</font><br>
                </div>
<div class="container">
        <table>
            <tbody><tr>
                <th>专属功能</th>
                <th>未开通</th>
                <th>已开通</th>
            </tr>
            <tr>
                <td>永久查看所有资源权限</td>
                <td><i class="fa fa-close"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>搜索权限</td>
                <td><i class="fa fa-close"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
<?php  if($userrow['power']==1 || $userrow['power']==2){?>
            <tr>
                <td>推广权限(专属二维码)</td>
                <td><i class="fa fa-close"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>下级升级分佣权限</td>
                <td><i class="fa fa-close"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
<?php }?>
        </tbody></table>
    </div>
                
                
                <?php  if($userrow['power']==1 || $userrow['power']==2){?>
                <div style="font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000">重要提示：</font><br>
                    <font color="#ff5b21">1.如果您未开通VIP会员，若下级有人开通升级了，你则无分佣（请优先保证自己是已开通会员状态）</font><br>
                    <font color="#000">2.仅分站站长/顶级合伙人有分佣，分佣50%，比如下级2元开通你得1元佣金，可自行设置下级开通价格</font><br>
                </div>
                <?php }?>
                
                </div>
            </div>
        </div>
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/shengji.png" style="width: 25px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>短剧会员开通</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span>当前余额：<?php echo $userrow['rmb']?>元</span></a>
                    </div>
        <div class="my-cell" style="padding: 5px 10px;border-radius: 0">

		<div class="panel-body">
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">
						开通VIP
					</div>
					<select name="uu" class="form-control"><option value="2">短剧永久VIP会员</option></select>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">
						开通价格
					</div>
					<input name="need" class="form-control" value="<?php echo $price ?>" disabled="">
					<div class="input-group-addon">
						元
					</div>
				</div>
			</div>
                <div class="text-center" style="padding: 10px 0;">
			<a class="btn submit_btn" style="width: 70%;padding:5px;" href="?act=submit">立即开通</a>
		</div>
	</div>
   </div>
  </div>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
            </div>
        </div>
<?php include './foot.php';?>
</script>

</body>
</html>