<?php
if (!defined('IN_CRONLITE')) die();

function display_zt($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<font color=red>异常</font>';
	elseif($zt==4)
		return '<font color=grey>已退款(平台余额)</font>';
	else
		return '<font color=blue>待处理</font>';
}

if($islogin2==1){
	$cookiesid = $userrow['zid'];
}

$data=trim(daddslashes($_GET['data']));
$page=isset($_GET['page'])?intval($_GET['page']):1;
if(!empty($data)){
	if(strlen($data)==17 && is_numeric($data))
	{
	   $sql=" A.tradeno='{$data}'"; 
	}else{
	   $sql=" A.input='{$data}'";
	}
	if($conf['queryorderlimit']==1)$sql.=" AND A.`userid`='$cookiesid'";
}
else $sql=" A.userid='{$cookiesid}'";

$q_status=isset($_GET['status'])?trim(daddslashes($_GET['status'])):"";
if(isset($q_status) && $q_status != ""){
	$qu_status = intval($q_status);
	$sql .= " AND A.status = '{$qu_status}'";
}
$limit = 999;
$start = $limit * ($page-1);

$total = $DB->getColumn("SELECT count(*) FROM `pre_orders` A WHERE{$sql} ");
$total_page = ceil($total/$limit);
$sql = "SELECT A.*,B.`name`,B.`shopimg` FROM `pre_orders` A LEFT JOIN `pre_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT {$start},{$limit}";
$rs=$DB->query($sql);
$record=array();
while($res = $rs->fetch()){
	$record[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'input2'=>$res['input2'],'money'=>$res['money'],'name'=>$res['name'],'shopimg'=>$res['shopimg'],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status'],'djzt'=>$res['djzt'],'skey'=>md5($res['id'].SYS_KEY.$res['id']));
}

// 订单补全逻辑，保证影视订单有标题和封面
foreach($record as &$row){
    if($row['tid'] == -4){
        $inputArr = explode('|', $row['input']);
        $vid = intval($inputArr[0]);
        $video = $DB->getRow("SELECT * FROM shua_videolist WHERE id='$vid' LIMIT 1");
        if($video){
            $row['name'] = $video['name'];
            $row['img'] = $video['img'];
            $row['product_url'] = '/index.php?mod=video&id=' . $vid;
            
            // 解析购买类型和集数
            if(strpos($row['input2'], '第') !== false && preg_match('/第(\d+)集/', $row['input2'], $matches)){
                $row['episodes'] = $matches[1];
                $row['buytype'] = '第'.$matches[1].'集';
            } elseif(strpos($row['input2'], '整部') !== false || strpos($row['input2'], '全集') !== false){
                $row['buytype'] = '整部';
            } elseif(strpos($row['input2'], '网盘') !== false){
                $row['buytype'] = '网盘';
            }
        } else {
            $row['product_url'] = '#';
        }
    } else {
        $row['img'] = $row['shopimg'];
        $row['product_url'] = '/?mod=buy2&tid=' . $row['tid'];
        // 检查普通商品是否包含网盘
        if(strpos($row['input2'], '网盘') !== false || strpos($row['input'], '网盘') !== false){
            $row['buytype'] = '网盘';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $conf['sitename'].($conf['title']==''?'':' - '.$conf['title'])  ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <script src="<?php echo $cdnpublic ?>modernizr/2.8.3/modernizr.min.js"></script>
	<style>html{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>
     </head>
<style>
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }

    body {
        width: 100%;
        max-width: 550px;
        margin: auto;
    }

    .page-title{
        font-size: .68rem;
        color: #000000;
        padding: .7rem .7rem;
    }
    .page-title::before {
        content: " ";
        display: inline-block;
        width: 3px;
        height: 10px;
        background:  #5589d5;
        margin-right: 5px;

    }
    .fui-tab.fui-tab-primary a.active {
        color: #1492fb;
        border-color: #1492fb;
    }

    .qt-header {
        height: 10vh;
        background: #11998e; /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #38ef7d, #11998e); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #38ef7d, #11998e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        line-height: 10vh;
    }

    .qt-header > input {
        height: 5vh;
        width: 100%;
        border: none;
        text-indent: 2.5em;
        line-height: 5vh;
        border-radius: 0.5em;
        font-size: 0.7rem;
    }

    .qt-header > span {
        position: absolute;
        margin-left: 0.6rem;
        font-size: 0.7rem;
    }

    .qt-card {
        box-shadow: 0px 0px 8px #dddddd;
        border-radius: 0.8em;
    }

    .qt-card img {
        width: 6.8em;
        max-width: 100%;
        height: 5.8em;
        border-radius: 0.3em;
        box-shadow: 3px 3px 1px #eee;
    }

    .qt-btn {
        border-radius:50px;
        border: solid 1px #cecdcd;
    }

    td.stitle {
        max-width: 380px;
    }

    td.wbreak {
        max-width: 420px;
        word-break: break-all;
    }

    #orderItem .orderTitle {
        word-break: keep-all;
    }

    #orderItem .orderContent {
        word-break: break-all;
    }

    #orderItem .btn {
        height: 100%;
        margin: 0;
    }

    #orderItem .orderContent img {
        max-width: 100%
    }

    a, a:focus, a:hover, a:active {
        outline: none;
        text-decoration: none;
    }

    .btn.btn-primary-o {
        color: #1492fb;
        border: 1px solid #1492fb;
    }

    .elevator_item {
        position: fixed;
        right: 5px;
        bottom: 20%;
        z-index: 11;
    }

    .elevator_item .feedback {
        width: 36px;
        font-size: 12px;
        padding: 5px 6px;
        display: block;
        border-radius: 5px;
        text-align: center;
        margin-top: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .35);
        cursor: pointer;
    }

    .graHover {
        position: relative;
        overflow: hidden;
    }

    .toplan div .choose {
        border-radius: .3rem;
    }
    .fui-tab.fui-tab-danger a.active {
        color: #<?php echo $conf['rgb01']; ?>;
        border-color: #<?php echo $conf['rgb01']; ?>;
        z-index: 100;
    }
    ::-webkit-scrollbar{
        display: none;
    }
    .layui-layer-content{

    }
    .table, .table>tbody{
        border-collapse: separate;
        border-spacing: 0;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
        border-top: 1px solid transparent;
        padding: 4px 10px;
    }

    .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
        padding: 8px 10px;

    }
    .table>tbody+tbody{
        border-top: 0px solid #ddd;
        padding: 0 10px;
        margin: 0 10px;
    }
    .table .tbody{
        border-top: 0px solid #ddd;
        display: block;
        padding: 5px 10px;
        margin: 0 10px;
    }
    .kami a{
        width: 100% ;
        padding: 10px 0;
        border-radius: 5px;
    }
    .kami .btn,.kami .btn-primary{

        background: #ff5722;
        border: 1px solid transparent;
    }
    .top{
      background-color: #<?php echo $conf['rgb01']; ?>;
      width: 100%;
      max-width: 550px;
      padding-bottom:10px;
      }
      .toptitle{
      font-weight:600;
      color:#fff;
      text-align: center;
      height: 50px;
      line-height: 65px;
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
</style>

<body>
<div id="body" style="width: 100%;max-width: 550px">
    <div id="body" style="width: 100%;max-width: 550px">
    <div class="fui-page-group statusbar" style="max-width: 550px;left: auto;overflow: hidden;">
    <div class="top">
        
        <div class="toptitle" style="font-size: 15px;">
            我的订单
        </div>
<marquee behavior="scroll">
                                    <b><span style="color: #f5f5f5;">脚本卡密类项目下单后前往邮箱获取链接信息，近期红包封面下单量较大，如遇下单无序列号售后反馈提交工单处理。</span></b>
                                </marquee>
            </div>
            <div class="layui-card" style="    background-color: unset;box-shadow: unset;">
            <!--<div class="fui-searchbar bar">
                        <div class="searchbar center searchbar-active" style="padding-right:50px">
                        	<input type="hidden" id="page" value="1">
                        	<input type="hidden" id="q_status" value="">
                            <input type="button" class="searchbar-cancel searchbtn" style="font-size: 13px;width:3rem;background-color:#<?php echo $conf['rgb01']; ?>;height: 1.35rem; border-radius:5px 5px 5px 5px;color: #fff;" value="搜索" onclick="OrderQuery();">
                            <div class="search-input" style="border: 0px;padding-left:0px;padding-right:0px;margin-right: 40px">
                                <i class="icon icon-search"></i>
                                <input type="search" id="query" onkeydown="if(event.keyCode==13){OrderQuery()}" class="search" value="" name="title" placeholder="输入下单信息" style="font-size: 14px;" id="title">
                            </div>
                        </div>
                    </div>-->


<div id="tab" class="fui-tab fui-tab-danger">
        <a data-tab="tab" style="font-size: 13px;" class="external <?php if(isset($q_status) && $q_status === ""){echo "active";} ?>" onclick="window.location.href='?mod=query&data=<?php echo $data; ?>'">全部</a>
        <a data-tab="tab0" style="font-size: 13px;" class="external <?php if($q_status === '0'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=0&data=<?php echo $data; ?>'">待处理</a>
        <a data-tab="tab1" style="font-size: 13px;" class="external <?php if($q_status === '2'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=2&data=<?php echo $data; ?>'">处理中</a>
        <a data-tab="tab2" style="font-size: 13px;" class="external <?php if($q_status === '1'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=1&data=<?php echo $data; ?>'">已完成</a>
        <a data-tab="tab3" style="font-size: 13px;" class="external <?php if($q_status === '4'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=4&data=<?php echo $data; ?>'">已退款</a>
<!--         <a data-tab="tab3" class="external <?php if($q_status === '3'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=3'">异常</a> -->
</div>
<div align="right"><a href="user/kscxdd.php">快速查看订单</a>&nbsp;&nbsp;&nbsp;</div>
<?php if($record){ ?>
        <div class="elevator_item" id="elevator_item" style="display:block;">
            <a class="feedback graHover" style="background-color: #ffa56a;color:#fff;" onclick="$('.tzgg').show()" rel="nofollow">订单说明</a>
        </div>
        <div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000"
             class="tzgg"></div>
        <div class="tzgg" type="text/html" style="display: none">
            <div class="account-layer" style="z-index: 100000000;">
                <div class="account-main" style="padding:0.8rem;height: auto">

                    <div class="account-title"><strong>订单详情公告</strong></div>

                    <div class="account-verify"
                         style="  display: block;    max-height: 15rem;    overflow: auto;margin-top: -10px">
<p><span style="font-size: 14px;">尊重的用户，您好：</span></p><section class="_135editor" data-role="paragraph"><p style="text-indent: 2em;"><span style="font-size: 14px;"><span style="font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, Tahoma, Arial, sans-serif; caret-color: #ff0000; text-decoration-thickness: initial; color: #000000; font-size: 14px; display: inline !important;">如果您在使用本平台的过程中，遇到有关于产品售后相关的问题需要咨询或建议，首先，请您不要着急，您可以在</span><span style="color: #ff0000; font-size: 14px;"><strong><span style="font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, Tahoma, Arial, sans-serif; caret-color: #ff0000; text-decoration-thickness: initial; font-size: 14px; display: inline !important;">【会员中心-售后反馈】</span></strong></span><span style="font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, Tahoma, Arial, sans-serif; caret-color: #ff0000; text-decoration-thickness: initial; color: #000000; font-size: 14px; display: inline !important;">内</span><span style="box-sizing: border-box; caret-color: red; color: #000000; font-size: 14px;">提交工单</span><span style="box-sizing: border-box; caret-color: red; color: #ff0000; font-size: 14px;"></span><span style="font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, Tahoma, Arial, sans-serif; caret-color: #ff0000; text-decoration-thickness: initial; color: #000000; font-size: 14px; display: inline !important;">反馈，反馈时务必详情描述您的问题。</span></span></p><p style="text-indent: 2em;"><span style="font-size: 14px;">您所提交的售后工单，本站客服会在24小时内为您处理解答，请耐心等待，并且及时关注工单状况！</span></p><p style="text-indent: 2em;"><span style="font-size: 14px;">本站订单记录会定期性清理，如您购买了课程务必请及时转存至您自己的网盘内！</span></p></section>
                    </div>
                    <br>
                    <div class="account-btn" style="display: block" onclick="$('.tzgg').hide()">确认</div>
                    <div class="account-close" onclick="$('.tzgg').hide()">
                  
                    </div>
                </div>

            </div>
        </div>

        <div class="layui-card-body" style="padding: 0.5em;padding-bottom: 3em;overflow:hidden;overflow-y: auto;height: 80vh;">
            <div class="layui-tab-item layui-show" id="order_all">
<?php foreach($record as $row){?>
<div class="layui-card qt-card">
  <div class="layui-card-header" style="margin-top: -0.2rem;background: #f7f7f8;border-radius: 10px;font-size: 0.8em;color:black;font-family: '微软雅黑'">
    <p style="width: 99%" class="layui-elip">
      <a href="<?php echo $row['product_url']; ?>" target="_blank" style="text-decoration:none;">
        <span style="font-weight:bold;font-size:1.1em;color:#2196f3;vertical-align:middle;">
          <?php echo $row['name'] ? $row['name'] : '<span style=\'color:#bbb\'>无标题</span>'; ?>
          <?php if(isset($row['buytype']) && $row['buytype']){ ?>
            <span style="color:#ff9800;font-size:0.95em;margin-left:0.5em;"><?php echo $row['buytype']; ?></span>
          <?php } ?>
        </span>
      </a>
    </p>
  </div>
  <div class="layui-card-body">
    <div class="layui-row layui-col-space10">
      <div class="layui-col-xs4">
        <a href="<?php echo $row['product_url']; ?>" target="_blank">
          <img src="<?php echo $row['img'] ? $row['img'] : '/assets/store/picture/error_img.png'; ?>" onerror="this.src='/assets/store/picture/error_img.png'" style="width: 6.8em; height: 5.8em; border-radius: 0.3em; box-shadow: 3px 3px 1px #eee;"/>
        </a>
      </div>
      <div class="layui-col-xs8" border="10px" style="margin-top: .3rem;background: #f7f7f8;border-radius: 10px;font-size: 0.8em;color:black;font-family: '微软雅黑'">
        商品总价：<?php echo $row['money']?>元<br>
        订单状态：<?php echo display_zt($row['status'])?><br>
        下单时间：<?php echo $row['addtime']?><br>
        <?php if($row['input2'] == '购买短剧地址'){ echo '网盘地址:'.$row['input'].'<br>'; }?>
        <?php if(!empty($row['result'])){ echo '详细信息：'.$row['result'].'<br>'; }?>
      </div>
      <div style="width: 100%;text-align: right" class="showorders">
        <button style="margin-top: .3rem;" class="layui-btn qt-btn layui-btn-sm layui-btn-primary xiangqing" data-id="<?php echo $row['id']?>" data-skey="<?php echo $row['skey']?>" onclick="showOrder(<?php echo $row['id']?>,'<?php echo $row['skey']?>')">
          查看详情
        </button>
        <?php if($row['input2'] == '购买短剧地址'){ ?>
        <button style="margin-top: .3rem;" class="layui-btn qt-btn layui-btn-sm layui-btn-danger" id="copy" data-clipboard-text="<?php echo $row['input'];?>">
          复制地址
        </button>
        <?php } ?>
        <?php if($row['djzt'] == 3){ ?>
        <button style="margin-top: .3rem;" class="layui-btn qt-btn layui-btn-sm layui-btn-danger" onclick="window.location.href='?mod=faka&id=<?php echo $row['id']?>&skey=<?php echo $row['skey']?>'">
          查看卡密
        </button>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php }?>
							</div>
							<br>
<div class="layui-tab-item layui-show" id="order_all" style="margin-top: 5px;">
<?php if($page>1){?>
	<button class="layui-btn layui-btn-sm layui-btn-normal" onclick="LastPage()">
		上一页
	</button>
<?php }
if($total_page!=$page){?>
	<button class="layui-btn layui-btn-sm layui-btn-normal pull-right" onclick="NextPage()">
		下一页
	</button>
<?php }?>
</div>
<br><br><br>
<?php }else{ ?>
<div class="fui-content navbar order-list">
    <div class="fui-content-inner">
        <div class="content-empty" style="">
        	<img src="template/storenews/image/mescroll-empty.png" style="width: 100%;height:100%;margin-bottom: .5rem;"><br>
        		            <p style="color: #999;font-size: .65rem">~ 暂无相关订单 ~</p>
        		            <br>
        		            <a href="./" class="btn btn-sm" style="color: #ffffff;border-radius: 100px;height: 1.5rem;width: 5rem;font-size: 0.65rem;background: linear-gradient(270deg,#f9745a,#ff9e01);">去逛逛</a>


        </div>
    </div>
</div>	
<?php } ?>
<!--                             <?php if ($conf['gg_search'] != '') { ?>
                                <div style="width: 100%;min-height: 3em;padding: 1em;box-shadow: 0px 0px 16px #eee;margin-top: 1em;border-radius: 0.5em;margin-bottom: 1em;">
                                    <?php echo $conf['gg_search'] ?>
                                </div>
                            <?php } ?> -->

                        </div>
            </div>
        </div>




        <div class="fui-navbar" style="max-width: 550px;z-index: 100;">
            <a href="./" class="nav-item  "><img src="../assets/img/xtb/home_car.png">  <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">首页</span>
            </a>
            <a href="./?mod=fenlei" class="nav-item"> <img src="../assets/img/xtb/fenlei_car.png"> <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">分类</span></a>
            <a href="./?mod=query" class="nav-item active"> <img src="../assets/img/xtb/dingdan_index.png"> <span
                        class="label" style="color:#ff7c33;line-height: unset;font-weight: inherit;">订单</span> </a>
    <a href="./?mod=kf" class="nav-item "> <img src="../assets/img/xtb/kefu_car.png"><span class="label" style="color:#999;line-height: unset;font-weight: inherit;">客服</span>
    </a>
            <a href="./user/" class="nav-item "> <img src="../assets/img/xtb/my_car.png"> <span
                        class="label" style="color:#999;line-height: unset;font-weight: inherit;">会员中心</span> </a>
        </div>

    </div>
</div>
<script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnserver ?>assets/store/js/query.js"></script>
<script src="//lib.baomitu.com/layer/2.3/layer.js"></script>
<script src="/assets/js/clipboard.js"></script>

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

<script>
                                                var clipboard = new ClipboardJS('#copy');
                                                clipboard.on('success', function (e) {
                                                    layer.msg('链接复制成功！', {
                                                        icon: 1
                                                    });
                                                });

                                                clipboard.on('error', function (e) {
                                                    layer.msg('链接复制失败，请手动复制！', {
                                                        icon: 2
                                                    });
                                                });

                                              
 </script>  
<script>
// 重写 showOrder 增强弹窗内容
function showOrder(id,skey){
  var ii = layer.load(2, {shade:[0.1,'#fff']});
  var status = ['待处理','已完成','处理中','异常','已退款'];
  $.ajax({
    type : "POST",
    url : "ajax.php?act=order",
    data : {id:id,skey:skey},
    dataType : 'json',
    success : function(data) {
      layer.close(ii);
      if(data.code == 0){
        var mark = '';
        if(data.episodes && data.episodes !== '1') mark = ' <span style="color:#ff9800;font-size:0.95em;">第'+data.episodes+'集</span>';
        else if(data.buytype && (data.buytype.indexOf('整部')!==-1||data.buytype.indexOf('全集')!==-1)) mark = ' <span style="color:#4caf50;font-size:0.95em;">整部</span>';
        var item = '<table class="table" id="orderItem">';
        item += '<tr><td>商品名称</td><td colspan="5">'+data.name+mark+'</td></tr>';
        item += '<tr><td>订单编号</td><td colspan="5"><span id="orderid">'+id+'</span></td></tr>';
        item += '<tr><td>订单金额</td><td colspan="5">￥'+data.money+'</td></tr>';
        item += '<tr><td>订单状态</td><td colspan="5">'+status[data.status]+'</td></tr>';
        item += '<tr><td>下单时间</td><td colspan="5">'+data.date+'</td></tr>';
        if(data.download_url){
          item += '<tr><td>网盘地址</td><td colspan="5">'+
            '<span id="copy_netdisk">'+data.download_url+'</span>'+
            ' <button class="layui-btn layui-btn-xs layui-btn-primary" id="copy_netdisk_btn" data-clipboard-text="'+data.download_url+'">复制</button>'+
            '</td></tr>';
        }else if(data.inputs && data.inputs.indexOf('http')!==-1){
          // 兼容部分网盘地址直接在inputs
          var url = data.inputs.match(/https?:\/\/[^\s]+/);
          if(url){
            item += '<tr><td>网盘地址</td><td colspan="5">'+
              '<span id="copy_netdisk">'+url[0]+'</span>'+
              ' <button class="layui-btn layui-btn-xs layui-btn-primary" id="copy_netdisk_btn" data-clipboard-text="'+url[0]+'">复制</button>'+
              '</td></tr>';
          }
        }
        if(data.inputs && (!data.download_url || data.inputs.indexOf('http')===-1)){
          item += '<tr><td>下单信息</td><td colspan="5">'+data.inputs+'</td></tr>';
        }
        if(data.complain){
          item += '<tr><td>订单操作</td><td class="orderContent"><a href="./user/workorder.php?my=add&orderid='+id+'&skey='+skey+'" onclick="return checklogin('+data.islogin+')" class="text-primary">售后反馈</a>';
          if(data.selfrefund == 1 && data.islogin == 1 && (data.status == 0 || data.status == 3)){
            item += '&nbsp;<a onclick="return apply_refund('+id+',\''+skey+'\')" class="text-danger">申请退款</a>';
          }
          item += '</td></tr>';
        }
        if(data.desc){
          item += '<tr><td colspan="6" class="orderTitle page-title" style="padding:5px 15px"><b>商品简介</b></td></tr>';
          item += '<tr><td colspan="6" class="orderContent">'+data.desc+'</td></tr>';
        }
        item += '</table>';
        var area = [$(window).width() > 480 ? '480px' : '95%', '90%'];
        layer.open({
          type: 1,
          area: area,
          title: '订单详细信息',
          btnAlign:"c",
          zIndex: 2001,
          content: item
        });
        setTimeout(function(){
          var clipboard = new ClipboardJS('#copy_netdisk_btn');
          clipboard.on('success', function (e) {
            layer.msg('网盘地址复制成功！', {icon: 1});
          });
          clipboard.on('error', function (e) {
            layer.msg('复制失败，请手动复制！', {icon: 2});
          });
        }, 300);
      }
    }
  });
}
</script>
</body>
</html>