
<?php
if (!defined('IN_CRONLITE')) exit();
if(!$conf['invite_tid'])exit("<script language='javascript'>alert('当前站点未开启推广链接功能');window.location.href='./';</script>");

$shops = array();
$rs=$DB->query("SELECT A.*,B.name,B.shopimg FROM shua_inviteshop A LEFT JOIN shua_tools B ON A.tid=B.tid WHERE A.active=1 ORDER BY A.sort ASC");
while($res = $rs->fetch())
{
	$shops[] = $res;
}
?>
    
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>推广链接生成</title>
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
    
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<style>
    
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
.order-hd{
    justify-content: space-between;
    padding: 16px 20px 5px;
    margin-top: 1px;
    font-size: 16px;
    color: #282828;
}
.btn-primary {
    background-color: #5c92d2;
    border-color: #5c92d2;
}
</style>
<body>

<div class="wrapper">
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block " style="float: none; background-color:#fff;padding:0;max-width: 550px;">
    <div class="block  block-all">
        <div class="block-white">
            <div class="block-back display-row align-center justify-between" style="position: fixed;background-color: #fff;max-width: 550px;left: 50%;transform: translateX(-50%);z-index: 1;">
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
            <font><a href="">推广链接生成</a></font>

            </div>
        </div>
    </div>

<div class="main-content">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/gz.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>推广规则</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000">请选择下方的商品，填写好相关信息，然后点击生成您的专属推广链接，复制链接或者广告语发送到QQ好友/QQ群聊/微信好友/朋友圈/空间/贴吧/论坛等地方宣传。<br><br>
                    如发现作弊的将永久拉黑IP和QQ并封禁账号！不做任何提醒请大家自觉遵守。<br><br></font>
                    <font color="#ff0000">自生成链接到任务完成限时10天，超过10天完成的则属于无效，不发放奖励。奖励到账时间24小时内。</font>
                </div>
            </div>
        </div>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/sc.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>生成链接</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                <div class="panel-heading">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in" id="share">
                         <div class="list-group">
                            <?php foreach ($shops as $row) {
							if(empty($row['shopimg']))$row['shopimg'] = 'https://ae01.alicdn.com/kf/H62814210ab734f578208b4e0276dd392k.png';
							?>
								<div style="padding:5px"><label class="css-input css-radio css-radio-primary" style="font-size: 14px;"><input type="radio" name="ctool" data-tid="<?php echo $row['tid'] ?>" data-type="<?php echo $row['type'] ?>" data-value="<?php echo $row['value'] ?>" value="<?php echo $row['id'] ?>"><span></span>
										<img src="<?php echo $row['shopimg'] ?>"  width="18px">&nbsp;<?php echo $row['name'] ?>
									</label>
								</div>
                            <?php } ?>
                        </div>
                        <div  class="form-group">
							<div class="hide" id="tidframe"></div>
                            <div class="input-group">
                                <div class="input-group-addon">查询进度QQ号</div>
                                <input type="text" name="query_qq" id="query_qq" class="form-control" placeholder="请输入用于查询推广进度QQ号" required="required">
                            </div>
                            </div>

                            <div id="inputsname"></div>


							<div id="alert_invite" style="display: none" class="alert alert-success"></div>
                        

                        <div>
                            <input type="submit" name="submit" id="submit_buy" value="立即创建推广订单" class="btn btn-primary btn-block" style="background-color: ##5c92d2; border-color: ##5c92d2;">
                        </div>
						<div id="resulturl" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/chaxun.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>查询进度</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                <div class="panel-heading">
                    <div class="tab-pane fade in" id="query">
                        <div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        查询QQ号
                                    </div>
                                    <input type="text" name="qq" id="qq" class="form-control"
                                           placeholder="请输入用于查询订单的QQ账号,查询推广进度" required="required">
                                </div>
                            </div>
                            <div>
                                <input type="submit" id="submit_sublog" value="立即查询"
                                       class="btn btn-primary btn-block"
                                       style="background-color: ##5c92d2; border-color: ##5c92d2;">
                            </div>
                            <font color="#000000">
                            <div id="result" style="display:none">
                                <div class="table-responsive">
                                    <table class="table table-vcenter table-condensed table-striped">
                                        <thead>
                                        <tr>
                                            <th>领取账号</th>
                                            <th>商品名称</th>
											<th>奖励次数</th>
											<th>状态</th>
											<th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody id="list" style="font-size: 13px;color: #ff0000;"></tbody>
                                    </table>
                                    </font>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
    <div class="content-item1">
    </div>
</div>
                        
<script src="<?php echo $cdnpublic ?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script type="text/javascript">
    var hashsalt =<?php echo $addsalt_js?>;
</script>
<script src="assets/js/invite.js?ver=<?php echo VERSION ?>"></script>
 <script>
function fuckyou(){
window.close(); 
window.location="about:blank"; 
}
function click(e) {
if (document.all) {
  if (event.button==2||event.button==3) { 
alert("欢迎光临寒舍，有什么需要帮忙的话，请与站长联系！谢谢您的合作！！！");
oncontextmenu='return false';
}
}
if (document.layers) {
if (e.which == 3) {
oncontextmenu='return false';
}
}
}
if (document.layers) {
fuckyou();
document.captureEvents(Event.MOUSEDOWN);
}
document.onmousedown=click;
document.oncontextmenu = new Function("return false;")
document.onkeydown =document.onkeyup = document.onkeypress=function(){ 
if(window.event.keyCode == 123) { 
fuckyou();
window.event.returnValue=false;
return(false); 
} 
}
</script>
</body>
</html>