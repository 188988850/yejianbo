<!DOCTYPE html>
<?php
/**
 * 发布群聊
**/
include("../includes/common.php");

if(isset($_GET['kw'])){
    $kw=$_GET['kw'];
    $rscv=$DB->query("SELECT * FROM shua_tools WHERE name LIKE '%{$kw}%' AND active=1");
}
$title='发布群聊';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

unset($islogin2);
?>
<?php
if($userrow['power']<2){
	showmsg('您没有权限使用此功能！',3);
}

$my=isset($_GET['my'])?$_GET['my']:null;
$cid=isset($_GET['cidr'])?$_GET['cidr']:'';

$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 and cidr=0 ORDER BY sort ASC");
$selectson='';
$select='';
if($cid){
    $rss=$DB->query("SELECT * FROM shua_class WHERE active=1 and cidr=$cid ORDER BY sort ASC");
    while($res = $rss->fetch()){
        $selectson.='<a class="hotxy-item" id="tab_'.$res['cid'].'" href="./fufeiqun.php?cid='.$res['cid'].'&cidr='.$cid.'">'.$res['name'].'</a>';
    }
}
$shua_class[0]='未分类';
while($res = $rs->fetch()){
    $shua_class[$res['cid']]=$res['name'];
    $select .= '<a id="tab_'.$res['cid'].'" href="./fufeiqun.php?cid='.$res['cid'].'&cidr='.$res['cid'].'" style="margin:5px 15px 5px 15px;width: 20%;height: 55px;align-items: center;justify-content: space-between;display: flex;flex-direction: column;">
                <div style="height: 30px;width: 30px;display: inline-block;overflow: hidden;position: relative;">
                    <div style="background-image: url(\'/static/img/user/all.png\'); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                    <img src="'.(strpos($res['shopimg'], 'http') !== false?$res['shopimg']:'../'.$res['shopimg']).'" style="width: 100%;height: 100%;">
                </div>
                <div style="font-size: 12px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">'.$res['name'].'</div>
            </a>';
    // $select.='<a class="hotxy-item" id="tab_'.$res['cid'].'" href="./fufeiqun.php?cid='.$res['cid'].'&cidr='.$res['cid'].'">'.$res['name'].'</a>';
}
$numrows=$DB->getColumn("SELECT count(*) FROM shua_tools WHERE active=1");
?>
<html lang="zh-cn" style="" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers no-applicationcache svg inlinesvg smil svgclippaths"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>发布群聊</title>
    <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="//s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/simple/css/plugins.css">
    <link rel="stylesheet" href="../assets/simple/css/main.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/store/css/content.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet">
    <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
    <link rel="stylesheet" href="../assets/user/css/my.css">
    <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>
    <link id="layuicss-laydate" rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/modules/laydate/default/laydate.css?v=5.0.9" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/modules/layer/default/layer.css?v=3.1.1" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="http://cdn.staticfile.org/layui/2.5.7/css/modules/code.css" media="all">
    <link rel="stylesheet" href="../assets/user/css/work.css">
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body>
    
<style>
    .layerdemo{
        border-radius: 10px;
        color:black;
        overflow: hidden;
    }
    .btn-list{
        width: 100%;
        margin-top: 10px;
        padding: 0 5px;
    }
    .btn-list .btn-item{
        width: 48%;
        height: 4rem;
        border-radius: 8px;
        color: #000;
        font-size: 1.3rem;
        border:  1px solid #f2f2f2;
        overflow: hidden;

        background:linear-gradient(to top , rgba(254, 254, 254, 1.0), rgba(241, 241, 241, 1.0),rgba(254, 254, 254, 1.0));
        text-align: center;
        line-height: 4rem;
        margin-bottom: 10px;
        box-shadow: -1px -1px 1px #e2dfdf;

    }
    .btn-list .btn-item img{
        height: 1.8rem;
        margin-right: 5px;
        margin-top: -4px;
    }
    .hotxy {
        white-space: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        left: 30rem;

    }
    .hotxy::-webkit-scrollbar {
        display: none !important;
    }

    .hotxy .hotxy-item{
        display: inline-block;
        min-width: 68px;
        margin: 0 1px;
        text-align: center;
        padding: 5px 0;

    }
    .hotxy .hotxy-item2{
        display: inline-block;
        min-width: 68px;
        margin: 0 10px;
        text-align: center;
        padding: 5px 0;

    }
    .hotxy .hotxy-item-index{
        border-bottom: 3px solid #de815c;
        font-weight: 700;

    }
    .list-item .list-item-c .item-c-txet{
        min-height: 3.5rem;
    }
    input::placeholder{
        text-align: right;
    }
    input{
        text-align: right;
    }
    .form-control[disabled]{
        background-color:transparent;
        color: #999999;
    }
    .search-input::placeholder{
        text-align: left;
    }
    .layui-layer {

    }
    .layui-layer-btn{
        display: inline-block;
        height: 4.5rem;
        border-top: 1px solid #f2f2f2;
        padding: 0;
        text-align: center;
        align-items: center;
        width: 100%;
    }
    .layui-layer-iframe .layui-layer-btn, .layui-layer-page .layui-layer-btn {
        padding-top:0;
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
.layui-layer{
    border-radius: 9px !important;
}
.layui-layer-title{
    border-radius: 9px !important;
}
</style>
<style>
    /* 自定义开关样式 */
    .custom-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
        
    }
    .custom-switch input {
        opacity: 0;
        width: 0;
        height: 0;
        
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 15px; 
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 15px; 
    }
    input:checked + .slider {
        background-color: #2196F3;
    }
    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }
    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    
::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 1px 1px 0 rgb(0 0 0 / 10%), inset 0 -1px 0 rgb(0 0 0 / 7%);
    background-clip: padding-box;
    background-color: #0000;
    min-height: 40px;
    padding-top: 100px;
    border-radius: 4px;
}
.content-item-bottom {
    padding: 5px;
}
</style>
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block " style="float: none; background-color:#fff;padding:0;max-width: 550px;">
    <div class="block  block-all">
        <div class="block-white">
            <div class="block-back display-row align-center justify-between" style="position: fixed;background-color: #fff;max-width: 550px;left: 50%;transform: translateX(-50%);z-index: 9999;">
                               <div style="border-width: .5px;
    border-radius: 100px;
    border-color: #dadbde;
    background-color: #f2f2f2;
    padding: 3px 7px;
    opacity: .8;align-items: center;justify-content: space-between;display: flex; flex-direction: row;height: 30px;">
                <a href="javascript:history.back()"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">发布群聊</a></font>

            </div>
          
        </div>
 <div style="padding-top: 60px;"></div>


       
<div class="hotxy block-white" id="tab">
    
<?php
$price_obj = new \lib\Price($userrow['zid'],$userrow);

if($my=='add')
{
$id=intval($_GET['id']);
$row=$DB->getRow("SELECT * FROM shua_sscc WHERE id='$id' LIMIT 1");
$price_obj->setToolInfo($id,$row);
?>
</div>
  <form action="./uupx.php?my=add_submit" method="POST"  onsubmit="return checkinput()">
       
<div class="main-content1">
   <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/dingdan.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>群聊资料</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">

                 <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        演示模版图片
                    </div>
                    <a href="../assets/img/qunyanshi01.jpg" target="_blank"><div class="form-control  form-control-left" style="color:#ff0000">立即查看①</div></a>
                    <a href="../assets/img/qunyanshi02.jpg" target="_blank"><div class="form-control  form-control-left" style="color:#ff0000">立即查看②</div></a>
                    <a href="../assets/img/qunyanshi03.jpg" target="_blank"><div class="form-control  form-control-left" style="color:#ff0000">立即查看③</div></a>
                </div>
            </div>
       
               <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">群聊头像</div>
                    <input type="file" id="file" onchange="fileUpload()" style="display:none;"/>
                    <input type="text" class="form-control" id="picurl" name="ename7" value="" style="visibility: hidden;" readonly onclick="fileView()" >
                                <span class="input-group-btn" style="padding-right: 10px">
                                <a href="javascript:fileSelect()" title="上传图片" class="display-row align-center">
                                <img style="width: 3.5rem; height: 3.5rem; " id="picurl_img" src="../assets/user/img/add_img.svg"></img></a></span>
                    </div>
                </div>
       
            <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        群聊名称
                    </div>
                    <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" required="" placeholder="请输入群聊名称">
                </div>
            </div>
           <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        群聊标题
                    </div>
                    <input type="text" class="form-control"  name="ename" value="<?php echo $row['ename']; ?>" required="" placeholder="请输入群聊标题">
                </div>
            </div>
            
            <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        入群费用
                    </div>
                    <input type="text" class="form-control"  name="money" value="9.9" required="" placeholder="请输入入群费用">
                </div>
            </div>
            
              <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        您的微信<a class="my-cell-title-r" style="color: #b6bcbd;"
                       href="javascript:layer.alert('如二维码过期失效，用户添加的微信联系方式<br>
                       如若不填，用户购买后二维码一旦失效，客服确认属实，平台有权将驳回该笔订单收益')">
                        <i class="fa fa-question-circle-o"  style="font-size:1.5rem;"></i>
                    </a>

                    </div>
                    <input type="text" class="form-control"  name="weixing" value="" required="" placeholder="请输入微信号">
                </div>
            </div>
            
             <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        底部入群文字
                    </div>
                    <input type="text" class="form-control"  name="ename10" value="限时特价9.9元(原价49)" required="" placeholder="">
                </div>
            </div>
            
               <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">群二维码（不限制群类型）</div>
                    <input type="file" id="file3" onchange="fileUpload3()" style="display:none;"/>
                    <input type="text" class="form-control" id="picurl3" name="ename9" value="<?php echo $row['ename9']; ?>" style="visibility: hidden;" readonly onclick="fileView()">
                                <span class="input-group-btn" style="padding-right: 10px">
                                <a href="javascript:fileSelect3()" title="上传图片" class="display-row  align-center">
                                <img style="width: 3.5rem; height: 3.5rem; " id="picurl3_img" src="../assets/user/img/add_img.svg"></img></a></span>
                    </div>
                </div>
            
                </div>
            </div>
        </div>
     
   <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="../assets/img/icon/dingdan.png" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span>群聊内容</span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    </div>
                    <div class="content-item-bottom">

            
             <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        第一标题（如群简介）
                    </div>
                    <input type="text" class="form-control"  name="ename1" value="群简介" required="" placeholder="请输入第一标题">
                </div>
            </div>
            
            <div class="form-group form-group-transparent">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        第一标题内容
                    </div>
                    <div class="form-control  form-control-left" style="font-size: 11px;color:#696969">介绍该群用途</div>
                </div>
            </div>
      <div class="form-group form-group-transparent">
      <div align="center"><div style="width: 95%;"><textarea class="form-control" placeholder=" 请输入第一标题内容" name="ename2" style="width:100%;height:80px"></textarea>
       </div>
     </div>
     <div class="form-group form-group-transparent form-group-border-bottom"></div>
            
             <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        第二标题（如常见问题）
                    </div>
                    <input type="text" class="form-control"  name="ename3" value="常见问题" required="" placeholder="请输入第二标题">
                </div>
            </div>
           
            <div class="form-group form-group-transparent">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        第二标题内容
                    </div>
                    <div class="form-control  form-control-left" style="font-size: 11px;color:#696969">格式：标题----内容*标题----内容</div>
                </div>
            </div>
           
      <div class="form-group form-group-transparent">
      <div align="center"><div style="width: 95%;"><textarea class="form-control" placeholder=" 请输入项目介绍" name="ename4" style="width:100%;height:80px">1.付费后支持退款吗？----一旦付费，无论何种原因概不退款（包括被踢出群的），请谨慎选择，看好再买。群费不多不少，买个信任。做付费群，过滤人群，提升群质量。避免人多嘴杂，杜绝吃白食群众。付费入群更能让社群形成正向循环，是社群内容质量的保障。 *2.用户协议（付款即代表已读）----本平台禁止以任何形式传播电信网络诈骗、兼职、刷单、网恋交友诈骗、淫秽、色情、赌博、暴力、凶杀、恐怖、谣言等违法行为，违规者所传播的信息相关的任何法律责任由违规者自行承担，与平台无关 。用户要自己有辨别意识，未成年人应当在监护人的指导下使用本平台服务，遇到违法违规行为第一时间向管理员举报！违规者平台将报警移交给相关公安机关处理！ </textarea>
       </div>
     </div>
     <div class="form-group form-group-transparent form-group-border-bottom"></div>
     
      <div class="form-group form-group-transparent form-group-border-bottom">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        第三标题<a class="my-cell-title-r" style="color: #b6bcbd;"
                       href="javascript:layer.alert('不填则不显示')">
                        <i class="fa fa-question-circle-o"  style="font-size:1.5rem;"></i>
                    </a>
                    </div>
                    <input type="text" class="form-control"  name="ename5" value="<?php echo $row['ename5']; ?>" required="" placeholder="请输入第三标题">
                </div>
            </div>

                        <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">第三标题图片<a class="my-cell-title-r" style="color: #b6bcbd;"
                       href="javascript:layer.alert('不上传则不显示')">
                        <i class="fa fa-question-circle-o"  style="font-size:1.5rem;"></i>
                    </a></div>
                    <input type="file" id="file2" onchange="fileUpload2()" style="display:none;"/>
                    <input type="text" class="form-control" id="picurl2" name="ename6" value="<?php echo $row['ename6']; ?>" style="visibility: hidden;" readonly onclick="fileView()" >
                                <span class="input-group-btn" style="padding-right: 10px">
                                <a href="javascript:fileSelect2()" title="上传图片" class="display-row align-center">
                                <img style="width: 3.5rem; height: 3.5rem; " id="picurl2_img" src="../assets/user/img/add_img.svg"></img></a></span>
                    </div>
                </div>
            
            <div class="form-group form-group-transparent">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        底部用户评论
                    </div>
                    <div class="form-control  form-control-left" style="font-size: 11px;color:#696969">格式：内容----内容</div>
                </div>
            </div>
      <div class="form-group form-group-transparent">
      <div align="center"><div style="width: 95%;"><textarea class="form-control" placeholder=" 请输入评论比内容" name="ename8" style="width:100%;height:80px">群里找到人合作了----引流可以的----群主很好----值得----便宜----挺不错的</textarea>
       </div>
     </div>
          <div class="form-group form-group-transparent form-group-border-bottom"></div>
                
          
            <div class="text-center" style="padding: 20px 0;">
                <input type="submit" name="submit" class="btn submit_btn" style="width: 70%;padding:5px;" value=" 确定提交">
            </div>

         
        </form>
                </div>
                </div>
            </div>
        </div>
     </div>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
            </div>
        </div>
     
<script>
function fileSelect(){
	$("#file").trigger("click");
}
function fileSelect2(){
	$("#file2").trigger("click");
}
function fileSelect3(){
	$("#file3").trigger("click");
}

   function fileUpload3() {
        var fileObj = $("#file3")[0].files[0];
        if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
            return;
        }
        var formData = new FormData();
        formData.append("do", "upload");
        formData.append("type", "user");
        formData.append("file3", fileObj);
        var ii = layer.load(2, {shade: [0.1, '#fff']});
        $.ajax({
            url: "ajax_user.php?act=uploadimg3",
            data: formData,
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                layer.close(ii);
                if (data.code == 0) {
                    layer.msg('上传图片成功');
                    $("#picurl3").val(data.url);
                    $('#picurl3_img').attr('src' , '../'+data.url);
                } else {
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.msg('服务器错误');
                return false;
            }
        })
    }
   function fileUpload2() {
        var fileObj = $("#file2")[0].files[0];
        if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
            return;
        }
        var formData = new FormData();
        formData.append("do", "upload");
        formData.append("type", "user");
        formData.append("file2", fileObj);
        var ii = layer.load(2, {shade: [0.1, '#fff']});
        $.ajax({
            url: "ajax_user.php?act=uploadimg2",
            data: formData,
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                layer.close(ii);
                if (data.code == 0) {
                    layer.msg('上传图片成功');
                    $("#picurl2").val(data.url);
                    $('#picurl2_img').attr('src' , '../'+data.url);
                } else {
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.msg('服务器错误');
                return false;
            }
        })
    }
    
function fileUpload(){
	var fileObj = $("#file")[0].files[0];
	if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
		return;
	}
	var formData = new FormData();
	formData.append("do","upload");
	formData.append("type","user");
	formData.append("file",fileObj);
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		url: "ajax_user.php?act=uploadimg1",
		data: formData,
		type: "POST",
		dataType: "json",
		cache: false,
		processData: false,
		contentType: false,
		success: function (data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('上传图片成功');
				$("#picurl").val(data.url);
				 $('#picurl_img').attr('src' , '../'+data.url);
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	})
}

var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
</script>
<?php
}

elseif($my=='add_submit')
{
$name=daddslashes($_POST['name']);
$ename=daddslashes($_POST['ename']);

$money=daddslashes($_POST['money']);
$ename1=daddslashes($_POST['ename1']);
$ename2=daddslashes($_POST['ename2']);
$ename3=daddslashes($_POST['ename3']);
$ename4=daddslashes($_POST['ename4']);
$ename5=daddslashes($_POST['ename5']);
$ename6=daddslashes($_POST['ename6']);
$ename7=daddslashes($_POST['ename7']);
$ename8=daddslashes($_POST['ename8']);
$ename9=daddslashes($_POST['ename9']);
$ename10=daddslashes($_POST['ename10']);
$weixing=daddslashes($_POST['weixing']);

if($name==NULL or $ename==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
$rows=$DB->getRow("select * from shua_sscc where type='$type' and name='$name' limit 1");
if($rows)
	showmsg('群聊标题已存在！',3);
$now = date('Y-m-d H:i:s'); // 获取当前时间
$zid=$userrow['zid']; 
$sql="insert into `shua_sscc` (`name`,`ename`,`money`,`ename1`,`ename2`,`ename3`,`ename4`,`ename5`,`ename6`,`ename7`,`ename8`,`ename9`,`ename10`, `active`, `addtime`, `zid`, `weixing`)  values  ('".$name."','".$ename."','".$money."','".$ename1."','".$ename2."','".$ename3."','".$ename4."','".$ename5."','".$ename6."','".$ename7."','".$ename8."','".$ename9."','".$ename10."', 1, '".$now."', '".$zid."', '".$weixing."')";
if($DB->exec($sql)!==false){
	showmsg('添加群聊成功！<br/><br/><a href="./fufeiqun.php">>>返回群聊列表</a>',1);
}else
	showmsg('添加群聊失败！'.$DB->error(),4);
}
}

elseif($my=='reset')
{
if($DB->exec("UPDATE shua_site SET price=NULL WHERE zid='{$userrow['zid']}'"))
	showmsg('重置成功！',1);
else
	showmsg('重置失败！'.$DB->error(),4);
}

else
{
if(isset($_GET['cid'])){
	$cid = intval($_GET['cid']);
	$numrows=$DB->getColumn("SELECT count(*) FROM shua_tools WHERE cid='$cid' AND active=1");
	$sql=" cid='$cid' AND active=1";
	$con='
	<div class="panel panel-default"><div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">'.$shua_class[$cid].'分类 - [<a href="fufeiqun.php" style="color:#fff00f">查看全部</a>]</div>
	<div class="well well-sm" style="margin: 0;">分类 '.$shua_class[$cid].' 共有 <b>'.$numrows.'</b> 个群聊</div>
	<div class="wrapper">
    <a href="#" data-toggle="modal" data-target="#search2" id="search2" class="btn btn-primary"><i class="fa fa-navicon"></i>&nbsp;分类查看</a>&nbsp;<a class="btn btn-info" href="javascript:void(0)" onclick="up_price('.$cid.')"><i class="fa fa-plus-circle"></i>&nbsp;提升售价</a></div>';
	$link='&cid='.$cid;
}elseif(isset($_GET['kw'])){
    $kw = isset($_GET['kw']);
    $numrows=$DB->getColumn("SELECT count(*) FROM shua_sscc WHERE A.name LIKE '%$kw%' AND active=1");
	$sql=" A.name LIKE '%$kw%'";
	$con='包含 <b>'.$kw.'</b> 的共有 <b>'.$numrows.'</b> 个群聊';
	$link='&kw='.$kw;
}else{
	$numrows=$DB->getColumn("SELECT count(*) FROM shua_sscc WHERE zid=$zid");
	$sql=" active=1";
	$con='
	<div class="panel panel-default"><div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">群聊列表</div>
	<div class="well well-sm" style="margin: 0;">系统共有 <b>'.$numrows.'</b> 个群聊 - 提升价格赚的更多哦！提高价格最好不要太贵了否则没人买的哦！</div>
    <div class="wrapper">
    <a href="#" data-toggle="modal" data-target="#search2" id="search2" class="btn btn-primary"><i class="fa fa-navicon"></i>&nbsp;分类查看</a>&nbsp;<a class="btn btn-success" onclick="reset_price(0)" href="javascript:void(0)"><i class="fa fa-refresh"></i>&nbsp;恢复价格</a>&nbsp;<a class="btn btn-info" href="javascript:void(0)" onclick="up_price(0)"><i class="fa fa-plus-circle"></i>&nbsp;提升售价</a></div>';
}
?>

        <div style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.3rem">群聊详情</div>
                <div class="my-cell-title-r  display-row  align-center">
                    <span style="color: #939393;font-size:1.3rem">共<?php echo ''.$numrows.'' ?>件群聊</span>
                </div>
            </div>
    
       
    
<br>

  
<?php

$pagesize=10;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);
$padds=$userrow['zid'];

$rs=$DB->query("SELECT * FROM shua_sscc WHERE zid =$padds ORDER BY addtime DESC LIMIT $offset,$pagesize");
while($res = $rs->fetch())
{
	$price_obj->setToolInfo($res['tid'],$res);

echo '<div class="list-item">
                                 <div class="list-item-top" style="padding-bottom: 10px">
                                      <div class="item-logo-1" style="width: auto;padding-right: 10px">
                                         <div class="item-logo-img" style="width: auto;padding: 0 25px">'.$res['name'].'</div>
                                      </div>
                                      <div class="item-operate" style="padding-top: 5px">
                                         <a class="item-operate-item" href="./fufeiqun.php?my=edit&id='.$res['id'].'">编辑</a >
                                      </div>
                                 </div>
                                 <div class="list-item-c">
                                     <div class="item-c-txet" style="line-height: normal;">
                                         <div class="item-c-title" style="font-size: 13px;">群聊名称</div>
                                         <div class="item-c-data">'.$res['name'].'</div>
                                     </div>
                                  <div class="item-c-txet" style="line-height: normal;">
                                         <div class="item-c-title" style="font-size: 13px;">进群金额</div>
                                         <div class="item-c-data">'.$res['money'].'元</div>
                                     </div>
                                     <div class="item-c-txet" style="line-height: normal;">
                                         <div class="item-c-title" style="font-size: 13px;">订单数</div>
                                         <div class="item-c-data">'.(isset($res['gg']) && $res['gg']!= ''? $res['gg'] : 0).'元</div>
                                     </div>
                                 </div>
                              </div>';}
?></div>            </div></div>
<div align="center">
<?php
echo'<ul class="pagination"  style="margin-left:1em">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="fufeiqun.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="fufeiqun.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-3>1?$page-3:1;
$end=$page+3<$pages?$page+3:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="fufeiqun.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="fufeiqun.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="fufeiqun.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="fufeiqun.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}?>
</div>
        </div>
            </div>

</div><script>
   //var cid = '//';
    var cid = getUrlParam('cid');
    var set_auto_price = '0';
    var set_auto_price_num = '0.00';
    //获取url中的参数
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值
    }
    $(document).ready(function(){
        if(cid){
            cid = parseInt(cid);
            $("#tab_0").removeClass('hotxy-item-index');
            $("#tab_"+ cid).addClass('hotxy-item-index');
            $("#tab").scrollLeft(100 * cid/2);
        }else{
            $("#tab_0").addClass('hotxy-item-index');
        }

    });

</script>

<script>

function reset_price(cid) {
    layer.open({
        type:1,
        title: false,
        area: '28rem',
        shade: 0.7,
        skin: "layerdemo",
        shadeClose: false,
        closeBtn: 0,
        offset: '30%',
        btnAlign: 'c',
        btn: ['确认', '再想想'],
        content:
            '<div class="showtip display-column align-center" style="letter-spacing:.05rem;">' +
            '<div class="showtip-title" style="height: 3rem"></div>' +
            '<div class="showtip-center  display-column justify-center align-center" style="margin-bottom: 3rem">'+
            '<img src="../assets/user/img/warning.png" style="height:4rem">'+
            '<div class="showtip-center-msg">恢复价格</div>'+
            '<div class="showtip-center-num" style="font-size: 1.2rem">重置所有群聊价格至最初状态</div>' +
            '</div>'+
            '</div>',
        btn1: function(index, layero){
            $.ajax({
                type: "post",
                url: "ajax_user.php?act=reset_price",
                data: {
                    cid: cid
                },
                dataType: "json",
                success: function (data) {
                    if (data.code == 0) {
                        layer.close(index);
                        layer.alert('恢复价格成功！', {icon: 1}, function () {
                            window.location.reload();
                        });
                    } else {

                        layer.alert(data.msg);
                    }
                }
            });
        },
        btn2: function(index, layero){
            layer.closeAll();

        },
        success: function(layero, index){
            var btn1= $(".layui-layer-btn .layui-layer-btn0");
            btn1.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "background": "transparent",
                "color":"#000",
            })
            var btn2= $(".layui-layer-btn .layui-layer-btn1");
            btn2.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "border-left": "1px solid #f2f2f2",
                "background": "transparent",
                "color":"#999999",
            })

        }
    });

}

 function up_price(zid){
    layer.open({
        type:1,
        title: false,
        area: '28rem',
        shade: 0.7,
        skin: "layerdemo",
        shadeClose: false,
        closeBtn: 0,
        offset: '30%',
        btnAlign: 'c',
        btn: ['确认', '取消'],
        content:
            '<div class="showtip display-column align-center" style="letter-spacing:.05rem;">' +
            '<div class="showtip-title" style="height: 1rem"></div>' +
            '<div class="showtip-center  display-column justify-center align-center" style="width: 100%">'+
            '<div class="showtip-center-msg">一键提升销售价格</div>'+
            '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px">' +
            '<input type="hidden"  name="tisheng_type" id="tisheng_type" >' +
            '<div class="display-row align-center justify-around" style="width: 100%;margin: 10px 0"> ' +
            '<a onclick="$(\'#tisheng_type\').val(1);$(\'#tisheng_1\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#tisheng_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="tisheng_1" style="width: 43%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">百分比</a>' +
            '<a onclick="$(\'#tisheng_type\').val(2);$(\'#tisheng_2\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#tisheng_1\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="tisheng_2" style="width: 43%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">固定金额</a>' +
            '</div>' +
            '<input style="width: 93%; margin: 0 auto;text-align: left;background:#f2f2f2;"  name="tisheng_value" id="tisheng_value" value="" placeholder="请输入数值" class="form-control  search-input">' +
            '</div>'+

            '</div>' +
            '</div>',
                yes: function (index,layero) {
                    var type = $('#tisheng_type').val();
                    if(type == "")
                    {
                        layer.msg("请选择提升类型");
                        return false;
                    }
                    var text = $('#tisheng_value').val();
                    if(text == "")
                    {
                        layer.msg("请填写数值");
                        return false;
                    }
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/api_ajax.php?act=up_price',
                        cache: false,
                        data: {zid:<?=$userrow['zid']?>,up:text,type:type},
                        success: function(data){
                            if(data.code==0){
                                layer.alert('销售价格提升成功',function(){
                                  window.location.reload();
                                });
                            }else{
                                layer.alert(data.msg);
                            }
                        },
                        error: function(data){
                            layer.msg('网络异常，请稍后重试');
                        }
                    });
                },
        success: function(layero, index){
            var btn1= $(".layui-layer-btn .layui-layer-btn0");
            btn1.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "background": "transparent",
                "color":"#000",
            })
            var btn2= $(".layui-layer-btn .layui-layer-btn1");
            btn2.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "border-left": "1px solid #f2f2f2",
                "background": "transparent",
                "color":"#999999",
            })

        }
    });
}
function up_price01(cid){
            layer.open({
                type:1,
                title: false,
                area: '28rem',
                shade: 0.7,
                skin: "layerdemo",
                shadeClose: false,
                closeBtn: 0,
                offset: '30%',
                btnAlign: 'c',
                btn: ['确认', '取消']
                , content: '<div class="showtip display-column align-center" style="letter-spacing:.05rem;">' +
            '<div class="showtip-title" style="height: 1rem"></div>' +
            '<div class="showtip-center  display-column justify-center align-center" style="width: 100%">'+
            '<div class="showtip-center-msg" align="center">提升群聊销售价格-普通用户<br>提升单独小分类售价<br>只可以在小分类或二级分类使用</div>'+
            '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px">' +
            '<input type="hidden" name="tisheng_type" id="tisheng_type" >' +
            '<div class="display-row align-center justify-around" style="width: 100%;margin: 10px 0"> ' +
            '' +
            '<a onclick="$(\'#tisheng_type\').val(2);$(\'#tisheng_2\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#tisheng_1\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="tisheng_2" style="width: 43%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">百分比</a>' +
            '</div>' +
            '<input style="width: 93%; margin: 0 auto;text-align: left;background:#f2f2f2;"  name="tisheng_value" id="tisheng_value" value="" placeholder="请输入数值" class="form-control  search-input">' +
            '</div>'+
            '</div>' +
            '</div>',
                btn2: function(index, layero){
                layer.closeAll();
                },
                btn1: function(index, layero){
                    var type = $('#tisheng_type').val();
                    if(type == "")
                    {
                        layer.msg("请选择提升类型");
                        return false;
                    }
                    var text = $('#tisheng_value').val();
                    if(text == "")
                    {
                        layer.msg("请填写数值");
                        return false;
                    }
                     $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: 'ajax_user.php?act=up_price',
                        cache: false,
                        data: {up:text,cid:cid,type:type},
                        success: function (data) {
                            if (data.code == 0) {
                                layer.alert('价格提升成功，刷新即可看到效果', {icon: 1}, function () {
                                    window.location.reload();
                                });
                            } else {
                                layer.alert(data.msg);
                            }
                        },
                        error: function (data) {
                            layer.msg('网络异常，请稍后重试');
                        }
                    });
                },
        success: function(layero, index){
            var btn1= $(".layui-layer-btn .layui-layer-btn0");
            btn1.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "background": "transparent",
                "color":"#000",
            })
            var btn2= $(".layui-layer-btn .layui-layer-btn1");
            btn2.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "border-left": "1px solid #f2f2f2",
                "background": "transparent",
                "color":"#999999",
            })
        
                }
            });
        }
function setActive(id){

    var i = $("input[type=hidden][name="+id+"]").val();
    console.log(id)
    if(id == 'del'){
        if(i == 1){
            $("#"+ id + " i").removeClass('fa-flip-horizontal');
            $("#"+ id + " i").css({"color":"#0b9ff5"});
            $("input[type=hidden][name="+id+"]").val(0)
        }else {
            $("#"+ id + " i").addClass('fa-flip-horizontal');
            $("#"+ id + " i").css({"color":"#94a7c1"});
            $("input[type=hidden][name="+id+"]").val(1)
        }
    }

}
function xiaji_up_price(zid) {
    layer.open({
        type:1,
        title: false,
        area: '28rem',
        shade: 0.7,
        skin: "layerdemo",
        shadeClose: false,
        closeBtn: 0,
        offset: '30%',
        btnAlign: 'c',
        btn: ['确认', '取消'],
        content:
            '<div class="showtip display-column align-center" style="letter-spacing:.05rem;">' +
            '<div class="showtip-title" style="height: 1rem"></div>' +
            '<div class="showtip-center  display-column justify-center align-center" style="width: 100%">'+
            '<div class="showtip-center-msg">一键提升下级价格</div>'+
            '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px">' +
            '<input type="hidden" name="tisheng_type" id="tisheng_type" >' +
            '<div class="display-row align-center justify-around" style="width: 100%;margin: 10px 0"> ' +
            '<a onclick="$(\'#tisheng_type\').val(1);$(\'#tisheng_1\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#tisheng_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="tisheng_1" style="width: 43%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">百分比</a>' +
            '<a onclick="$(\'#tisheng_type\').val(2);$(\'#tisheng_2\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#tisheng_1\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="tisheng_2" style="width: 43%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">固定金额</a>' +
            '</div>' +
            '<input style="width: 93%; margin: 0 auto;text-align: left;background:#f2f2f2;"  name="tisheng_value" id="tisheng_value" value="" placeholder="请输入数值" class="form-control  search-input">' +
            '</div>'+
            '</div>' +
            '</div>',
                yes: function (index,layero) {
                    var type = $('#tisheng_type').val();
                    if(type == "")
                    {
                        layer.msg("请选择提升类型");
                        return false;
                    }
                    var text = $('#tisheng_value').val();
                    if(text == "")
                    {
                        layer.msg("请填写数值");
                        return false;
                    }
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/api_ajax.php?act=xiaji_up_price',
                        cache: false,
                        data: {zid:<?=$userrow['zid']?>,up:text,type:type},
                        success: function(data){
                            if(data.code==0){
                                layer.alert('下级价格提升成功！',function(){
                                  window.location.reload();
                                });
                            }else{
                                layer.alert(data.msg);
                            }
                        },
                        error: function(data){
                            layer.msg('网络异常，请稍后重试');
                        }
            });

        },
        success: function(layero, index){
            var btn1= $(".layui-layer-btn .layui-layer-btn0");
            btn1.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "background": "transparent",
                "color":"#000",
            })
            var btn2= $(".layui-layer-btn .layui-layer-btn1");
            btn2.css({
                "width":" 50%",
                "height": "100%",
                "padding": "0",
                "margin":"0",
                "line-height": "4.5rem",
                "border-radius":" 0",
                "border":" 1px solid transparent",
                "border-left": "1px solid #f2f2f2",
                "background": "transparent",
                "color":"#999999",
            })

        }
    });
}
//设置自动价格
    function auto_price() {

        layer.open({
            type:1,
            title: false,
            area: '28rem',
            shade: 0.7,
            skin: "layerdemo",
            shadeClose: false,
            closeBtn: 0,
            offset: '30%',
            btnAlign: 'c',
            btn: ['取消', '确认'],
            content:
                '<div class="showtip display-column align-center" style="letter-spacing:.05rem;">' +
                '<div class="showtip-title" style="height: 1rem"></div>' +
                '<div class="showtip-center  display-column justify-center align-center" style="width: 100%">'+
                '<div class="showtip-center-msg">新群聊自动同步群聊价格</div>'+
                '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px">' +
                '<input type="hidden"  name="auto_price_type" id="auto_price_type" value="">' +
                '<div class="display-row align-center justify-around" style="width: 100%;margin: 10px 0"> ' +
                '<a onclick="$(\'#auto_price_type\').val(0);$(\'#auto_0\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_1,#auto_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});demo(0);" id="auto_0" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">关闭</a>' +
                '<a onclick="$(\'#auto_price_type\').val(1);$(\'#auto_1\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_0,#auto_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});demo(1);" id="auto_1" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">固定金额</a>' +
                '<a  onclick="$(\'#auto_price_type\').val(2);$(\'#auto_2\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_0,#auto_1\').css({\'background\':\'transparent\',\'color\':\'#000\'});demo(2);" id="auto_2" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">百分比</a>' +
                '</div>' +
                '<input style="width: 93%; margin: 0 auto;text-align: left;background:#f2f2f2;"  name="auto_price_num" id="auto_price_num" value="" placeholder="请输入数值" class="form-control  search-input">' +
                '<p style="font-size:.95rem;color: #999999;padding: 0 10px;margin-top: 10px; line-height: 1.6rem">*自动同步群聊价格,是根据您选择的提升类型,自动提升最新群聊的价格(只同步后期新上架群聊)</p>'+
                '</div>'+

                '</div>' +
                '</div>',
            btn1: function(index, layero){
                layer.closeAll();
            },
            btn2: function(index, layero){
                var type = $('#auto_price_type').val();
                
                if (type == "") {
                    layer.msg("请选择自动提升类型");
                    return false;
                }
                var price_num = $('#auto_price_num').val();
                var re = /^[0-9]+.?[0-9]*$/;
                if (!re.test(price_num)) {
                    layer.msg("请填写数字数值");
                    return false;
                }
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: 'ajax_user.php?act=auto_price',
                    cache: false,
                    data: { auto_price: type, auto_price_num: price_num},
                    success: function (data) {

                        layer.alert('修改成功', {icon: 1}, function () {
                            window.location.reload();
                        });
                    },
                    error: function (data) {
                        layer.alert('修改成功', {icon: 1}, function () {
                            window.location.reload();
                        });
                    }
                });

            },
            success: function(layero, index){
                $('#auto_'+ 0).css({'background':'#438ff3','color':'#fff'});
                var btn1= $(".layui-layer-btn .layui-layer-btn0");
                btn1.css({
                    "width":" 50%",
                    "height": "100%",
                    "padding": "0",
                    "margin":"0",
                    "line-height": "4.5rem",
                    "border-radius":" 0",
                    "border":" 1px solid transparent",
                    "background": "transparent",
                    "color":"#999999",
                })
                var btn2= $(".layui-layer-btn .layui-layer-btn1");
                btn2.css({
                    "width":" 50%",
                    "height": "100%",
                    "padding": "0",
                    "margin":"0",
                    "line-height": "4.5rem",
                    "border-radius":" 0",
                    "border":" 1px solid transparent",
                    "border-left": "1px solid #f2f2f2",
                    "background": "transparent",
                    "color":"#000",
                })

            }
        });
    }

    function  openmsg1() {
        layer.open({
            type:1,
            title: false,
            area: ['30rem'],
            shade: 0.7,
            skin: "layerdemo",
            shadeClose: false,
            closeBtn: 0,
            content: '<center><div class="showtip display-column  align-center" style="background:#FFF">'+
                '<b></b>'+
                '<div class="text-left" style="width:100%;padding: 15px">'+
                '<font style="font-weight: 800;line-height: 3rem">如何提升群聊售价？</font><br>'+
                '<font style="color: #999999">点击提升售价，选择百分比或者固定金额，输入数字整数，百分比输入10=提升10%售价，固定金额输入10=提升10元售价，群聊售价不做上限要求，具体由您自己设定。</font><br>'+
                '</div>'+
                '<div class="text-left" style="width:100%;padding: 15px;background:#f2f2f2 ">'+
                '<font style="font-weight: 800;line-height: 3rem">修改价格之后首页价格没变化？</font><br>'+
                '<font style="color: #999999"> 因为您当前的账号属于站长级别，看到的当然是您自己的价格，需要您退出当前登录的账号后首页才能看到您设定的售价，否则看到的都是系统成本价。</font><br>'+
                '</div>'+
                
                '<div class="showtip-btn display-row justify-center align-center" >' +
                '<a  href="javascript:layer.closeAll();" class="showtip-btn-yes display-column justify-center align-center" style="height:4rem;color: #0b9ff5">确定</a>' +
                '</div>'+
                '</div>'+
                '</center>',
        });

    }
    function demo(id){
        if(id==0){
            $("#auto_price_num").val('0');

        }else if(id==1){
            $("#auto_price_num").val('1');
        }
        else if(id==2){
            $("#auto_price_num").val('');
        }
    }
</script>
<div class="layui-layer-move"></div>
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


//设置自动价格
    function auto_price() {

        layer.open({
            type:1,
            title: false,
            area: '28rem',
            shade: 0.7,
            skin: "layerdemo",
            shadeClose: false,
            closeBtn: 0,
            offset: '30%',
            btnAlign: 'c',
            btn: ['取消', '确认'],
            content:
                '<div class="showtip display-column align-center" style="letter-spacing:.05rem;">' +
                '<div class="showtip-title" style="height: 1rem"></div>' +
                '<div class="showtip-center  display-column justify-center align-center" style="width: 100%">'+
                '<div class="showtip-center-msg">新群聊自动同步群聊价格</div>'+
                '<div class="row-title"><div class="row-title-text">销售价格设置</div></div>'+
                '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px">' +
                '<input type="hidden"  name="auto_price_type" id="auto_price_type" value="">' +
                '<div class="display-row align-center justify-around" style="width: 100%;margin: 10px 0"> ' +
                '<a onclick="$(\'#auto_price_type\').val(0);$(\'#auto_0\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_1,#auto_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="auto_0" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">关闭</a>' +
                '<a onclick="$(\'#auto_price_type\').val(1);$(\'#auto_1\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_0,#auto_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="auto_1" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">固定金额</a>' +
                '<a  onclick="$(\'#auto_price_type\').val(2);$(\'#auto_2\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_0,#auto_1\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="auto_2" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">百分比</a>' +
                '</div>' +
                '<input style="width: 93%; margin: 0 auto;text-align: left;background:#f2f2f2;"  name="auto_price_num" id="auto_price_num" value="" placeholder="请输入销售价格数值" class="form-control  search-input">' +
                <?php if($userrow['power'] >= 2){?>
                '<br>'+
                '<div class="showtip-center  display-column justify-center align-center" style="width: 100%">'+
                '<div class="row-title " style="color:black;"><div class="row-title-text">下级价格设置</div></div>'+
                '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px">' +
                '<input type="hidden"  name="auto_xiajiprice_type" id="auto_xiajiprice_type" value="">' +
                '<div class="display-row align-center justify-around" style="width: 100%;margin: 10px 0"> ' +
                '<a onclick="$(\'#auto_xiajiprice_type\').val(0);$(\'#auto2_0\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto2_1,#auto2_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="auto2_0" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">关闭</a>' +
                '<a onclick="$(\'#auto_xiajiprice_type\').val(1);$(\'#auto2_1\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto2_0,#auto2_2\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="auto2_1" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">固定金额</a>' +
                '<a  onclick="$(\'#auto_xiajiprice_type\').val(2);$(\'#auto2_2\').css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto2_0,#auto2_1\').css({\'background\':\'transparent\',\'color\':\'#000\'});" id="auto2_2" style="width: 30%;height: 3.5rem; border: 1px solid #bfbfbf;text-align: center;border-radius: 5px;line-height: 3.5rem">百分比</a>' +
                '</div>' +
                '<input style="width: 93%; margin: 0 auto;text-align: left;background:#f2f2f2;"  name="auto_xiajiprice_num" id="auto_xiajiprice_num" value="" placeholder="请输入下级价格数值" class="form-control  search-input">' +
                <?php }?>
                '<p style="font-size:.95rem;color: #999999;padding: 0 10px;margin-top: 10px; line-height: 1.6rem">*自动同步群聊价格,是根据您选择的提升类型,自动提升最新群聊的价格(只同步后期新上架群聊)</p>'+
                '</div>'+

                '</div>' +
                '</div>',
            btn1: function(index, layero){
                layer.closeAll();
            },
            btn2: function(index, layero){
                var type = $('#auto_price_type').val();
                var type2 = $('#auto_xiajiprice_type').val();
                // if (type == "" || type2 == "") {
                //     layer.msg("请选择自动提升类型");
                //     return false;
                // }
                var price_num = $('#auto_price_num').val();
                var price_num2 = $('#auto_xiajiprice_num').val();
                var re = /^[0-9]+.?[0-9]*$/;
                if (type != 0 && type != undefined && !re.test(price_num)) {
                    layer.msg("请填写数字数值");
                    return false;
                }
                if (type2 != 0 && type2 != undefined && !re.test(price_num2)) {
                    
                    layer.msg("请填写数字数值");
                    return false;
                }
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: 'ajax_user.php?act=auto_price',
                    cache: false,
                    data: { auto_price: type, auto_price_num: price_num, auto_price2: type2, auto_price_num2: price_num2},
                    success: function (data) {

                        layer.alert('修改成功', {icon: 1}, function () {
                            window.location.reload();
                        });
                    },
                    error: function (data) {
                        layer.alert('修改成功', {icon: 1}, function () {
                            window.location.reload();
                        });
                    }
                });

            },
            success: function(layero, index){
                <?php
                if($userrow['inbr'] == 2){
                    echo '$(\'#auto_\'+ 2).css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_price_num\').val(\''.$userrow['inbfb'].'\');';
                }else if($userrow['inbr'] == 1){
                    echo '$(\'#auto_\'+ 1).css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_price_num\').val(\''.$userrow['ingd'].'\');';
                }else{
                    echo '$(\'#auto_\'+ 0).css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_price_num\').val(\'\');';
                }
                if($userrow['inbr2'] == 2){
                    echo '$(\'#auto2_\'+ 2).css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_xiajiprice_num\').val(\''.$userrow['inbfb2'].'\');';
                }else if($userrow['inbr2'] == 1){
                    echo '$(\'#auto2_\'+ 1).css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_xiajiprice_num\').val(\''.$userrow['ingd2'].'\');';
                }else{
                    echo '$(\'#auto2_\'+ 0).css({\'background\':\'#438ff3\',\'color\':\'#fff\'});$(\'#auto_xiajiprice_num\').val(\'\');';
                }
                ?>
                var btn1= $(".layui-layer-btn .layui-layer-btn0");
                btn1.css({
                    "width":" 50%",
                    "height": "100%",
                    "padding": "0",
                    "margin":"0",
                    "line-height": "4.5rem",
                    "border-radius":" 0",
                    "border":" 1px solid transparent",
                    "background": "transparent",
                    "color":"#999999",
                })
                var btn2= $(".layui-layer-btn .layui-layer-btn1");
                btn2.css({
                    "width":" 50%",
                    "height": "100%",
                    "padding": "0",
                    "margin":"0",
                    "line-height": "4.5rem",
                    "border-radius":" 0",
                    "border":" 1px solid transparent",
                    "border-left": "1px solid #f2f2f2",
                    "background": "transparent",
                    "color":"#000",
                })

            }
        });
    }
    tanc();
    function tanc() {
        layer.alert('<div class="article-content">'
                          +'<p><span style="font-size:14px;"><strong><span>发布群聊前请你务必知悉并确认：</span><br/></strong></span></p>'
            +' <p><span style="font-size:14px;">请勿用作诈骗及任何违法等用途</span></p>'
            +' <p><span style="font-size:14px;">请勿发布黄赌毒之类的群聊</span></p>'
            +' <p><span style="font-size:14px;">违规者平台有权封禁账号</span></p>'
            +' <p><span style="font-size:14px;">违规者所传播的信息相关的任何法律责任由违规者自行承担</span></p>'
            +'</div>',
            function () {
            $('#switch1').attr('checked', 'checked');
            layer.closeAll();
        })
    }

    function setRadio(i) {
        $(":radio[name='kind'][value=" + i + "]").prop("checked", "checked").trigger('change');
    }
    
</script>
</body></html>