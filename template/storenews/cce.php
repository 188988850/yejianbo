
<?php

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $row = $DB->getRow("SELECT * FROM pre_sscc WHERE id = '$id' LIMIT 1");
    if($row) {
       
    }
} else {
    echo "未获取到 ID 参数";
}
?>
<div class="wrapper">
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="UTF-8">
    <title><?php echo $row['name'];?></title>
        <link rel="stylesheet" type="text/css" href="/tt/group/index/css.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css2/iconfont.css">
  
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
<style>html{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>
</head>
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
.qunicon1 {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}
.qun img {
    width: 50%;
    height: 25%;
    border-radius: 0.5rem;
    margin: 0 auto;
}
.qun {
    width: 100%;
    margin: 0 auto;
    padding: 2.7rem 0;
    text-align: center;
    background: linear-gradient(263deg,#fe7e88 0%,#ffbb6e 100%);
    border-bottom-left-radius: 10%;
    border-bottom-right-radius: 10%;
    border-bottom: 0rem solid #c1c0c5;
}
.peoplez {
    width: 100%;
    margin: 0 auto;
    margin-top: 0.96rem;
}
.peoplez .peoplef img {
    width: 6rem;
    height: 6rem;
    border-radius: 0.5rem;
    margin: 0 auto;
}
.qunpeople .title2 {
    font-size: 2rem;
    margin-top: 1.5rem;
}
.qunliuyan .liuyantit .lyo {
    font-size: 1.7rem;
    color: #ff5050;
}
.qunliuyan {
    background-color: #f2f2f2;
    width: 100%;
    margin: 10 auto;
    padding: 0px 0px 15px 0px;
}
.qun .quntit {
    font-size: 1.8rem;
    color: #1d1d1d;
    margin-top: 1.3rem;
}
.qun .num {
    font-size: 1.5rem;
    color: #959595;
    margin-top: 0.67rem;
}
</style>
<body>
	    
<style>
.idjshow{width:94%; height:30px; background:#000;opacity:0.8; border-radius: 15px; line-height:30px; color:#FFF; text-align:center; margin-left:auto; margin-right:auto; z-index:99999; position:fixed; top:20px; left:3%}
</style>
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
                <a href="./?mod=weixin"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href=""><?php echo $row['name'];?></a></font>

            </div>
        </div>
    </div>
     <div style="padding-top: 60px;"></div>
<div id="idjshow"></div>  
		<div class="qun"><img src="<?php echo $row['ename7'];?>">
		<p class="quntit" id="quntit"><?php echo $row['name'];?></p>
		<p style="color:666666" class="num"><?php echo $row['ename'];?></p>
		</div>
		


<script>
    var titles = "<?php echo $row['name'];?>";  // 获取后台设置群昵称
    console.log(titles);

    function setTitleAndContent(title) {
        document.getElementById("quntit").innerText = title;
        document.title = title;
    }

    // 检查群昵称是否包含特定的文本
    if (titles.includes("xxx")) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "https://yszqd.jy9527.cn/group/index/getIp", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = xhr.responseText.trim();

                    // 检查响应是否表示错误
                    if (response.startsWith("请求失败")) {
                        console.log(response);  // 打印错误消息
                        setTitleAndContent(titles.replace("xxx", ""));
                    } else {
                        // 假设成功的响应直接是城市名称
                        var city = response;
                        console.log(city);
                        // 替换titles中的"xxx"为城市名称
                        setTitleAndContent(titles.replace("xxx", city));
                    }
                } else {
                    console.log("请求失败: 状态码 " + xhr.status);
                    setTitleAndContent(titles.replace("xxx", ""));
                }
            }
        };
        xhr.send();
    } else {
        setTitleAndContent(titles);
    }
</script>

		
		<div class="qunz">
		
		<div class="qunpeople">
			<div class="title2"><i class="fa fa-bookmark" style="color: #fe7e88;"></i> 群成员</div>
		
			    
			    
    <?php 
    $nickname = "最美的太阳花、孤海的浪漫、薰衣草、木槿，花、森林小巷少女与狐@、冬日暖阳、午後の夏天、嘴角的美人痣。、朽梦挽歌、心淡然、歇斯底里的狂笑、夏，好温暖、彼岸花开、岸与海的距离、猫味萝莉、软甜阮甜、枯酒无味、寄个拥抱、少女病、江南酒馆、淡尘轻烟、过气软妹、檬℃柠叶、仙九 、且听、风铃、野性_萌、樱桃小丸子、少女の烦躁期、无名小姐、香味少女、清澈的眼眸、海草舞蹈、淡淡de茶香味、雨后彩虹、安全等待你来、薄荷蓝、指尖上的星空、雲朵兒、准风月谈、柠檬、一整个夏天";
    $nickname_array = explode("、",$nickname);
    
    $numbers = range(1, 100);
    shuffle($numbers);
    shuffle($nickname_array);
?>

<div class="peoplez">
    <?php 
        for($i = 0; $i < 13; $i++){
            ?>
            <div class="peoplef"><img src="/face/qq/<?php echo $numbers[$i]; ?>.jpg"><p><?php echo $nickname_array[$i]; ?></p> </div>
            
            <?php
        }
    ?><div class="peoplef">
					<img src="/tt/group/index/images/photoadd.png"/>
					<p></p>
				</div>
    
</div>
			
			<hr>
			
			
	
		

	
			<div class="qunstate"><div class="title2"><i class="fa fa-bookmark" style="color: #2894FF;"></i> <?php echo $row['ename1'];?></div><div class="qs"><p><?php echo $row['ename2'];?></p></div></div>
		          <div class="title2"><i class="fa fa-bookmark" style="color: #72aa76;"></i> <?php echo $row['ename3'];?></div>
              <div class="qs">
   
        <?php 
        $ename2 = $row['ename4'];
        $paragraphs = explode("*", $ename2);

        foreach ($paragraphs as $paragraph) {
            $parts = explode("----", $paragraph);
            echo "<p class='ad' style='color: #425887;margin-top: 1rem;'>{$parts[0]}</p>";
           echo "<p  style='color: #7e7e7e;margin-top: 0.57rem;'>{$parts[1]}</p >";
        }
        ?>
   
</div>

		
		
		
		
		
	
		
				<div class="qunfuli">
			<div class="title2"><i class="fa fa-bookmark" style="color: #f09aff;"></i> <?php echo $row['ename5'];?></div>
			
		
			<div class="fuli svvv">
				<p><?php 
if(!empty($row['ename6'])){
    echo '<div class="fuli svvv">';
    echo '<p><img src="' . $row['ename6'] . '"></p >';
    echo '</div>';
}
?></p>			</div>
				
		</div>
				
		

		<div class="yuedu">阅读10万+</div>
		<div class="qunicon">
		  <div class="qunicon1">
			<a href="./user/bsdf.php?id=<?php echo $row['id'];?>"><div class="icon1"><img src="/tt/group/index/images/icon1.png"><span>分享赚钱（生成推广码）</span></div></a>
			<div class="icon1"><img src="/tt/group/index/images/icon3.jpg"><span>1万+</span></div>
		  </div>
		</div>
		
	</div>
	
	
	
		<div class="qunliuyan">

			<div class="liuyantit">
				<p class="lyo"><i class="	fa fa-comments" style="color: #ff5050;"></i> 群友评论（精选）</p>
			</div>
			
			<?php 
    $nickname = "最美的太阳花、孤海的浪漫、薰衣草、木槿，花、森林小巷少女与狐@、冬日暖阳、午後の夏天、嘴角的美人痣。、朽梦挽歌、心淡然、歇斯底里的狂笑、夏，好温暖、彼岸花开、岸与海的距离、猫味萝莉、软甜阮甜、枯酒无味、寄个拥抱、少女病、江南酒馆、淡尘轻烟、过气软妹、檬℃柠叶、仙九 、且听、风铃、野性_萌、樱桃小丸子、少女の烦躁期、无名小姐、香味少女、清澈的眼眸、海草舞蹈、淡淡de茶香味、雨后彩虹、安全等待你来、薄荷蓝、指尖上的星空、雲朵兒、准风月谈、柠檬、一整个夏天、Drunk、DARK_KNIGHT、EVIL_DEMON、Perfect″—完美、Dear(挚爱)、离瑰ⅠThekhoi、The star星星、Toxic、茴忆、KRY┃ 控、deep love、Monody、Ace 王者゛、Jasmine、Go over 重温︴︴、Rainbow、㈠只猪╰つ、δ尐儍猪Θ、伱佷謿尐吢髮黣、黑色幽默╕、尒、￣祖爷、宠爱↘白痴、≈大白痴琼、咦嘻嘻哈哈、黑妞爱狗蛋、逗鹅、逗比逗比呼叫傻比、婊子配骚狗、傻傻的傻瓜、可爱爆了、老子爆你头";
    $nickname_array = explode("、",$nickname);
    
    $numbers = range(1, 100);
    shuffle($numbers);
    shuffle($nickname_array);
?>
			
			 <?php 
for($i = 0; $i < 5; $i++){
    ?>
    <div class="liuyanz">
        <img class="qleft" src="/face/qq/<?php echo $numbers[$i]; ?>.jpg">
        <div class="qcenter"><p class="nichen"><?php echo $nickname_array[$i]; ?></p >
        
        <?php 
        $messages = explode("----", $row['ename8']);
        $random_message = $messages[array_rand($messages)];
        ?>
        
<?php
// 生成随机数
$Num1 = rand(); 
//输出
print_r(""); 
//在一个范围内生成随机数
$Num2 = rand(100,9999); 
//输出
?>
        <div class="liuyan"><?php echo $random_message; ?></div>
				</div>
				<div class="qright"><img src="/tt/group/index/images/icon3.png"><p>赞：<?php echo $Num2?></p></div>
			</div>
            
            <?php
        }
    ?>
				
		
			
					
					</div>
	
	<style>
	.qunbtn{
	        display: inline-block;
			border:0;
			margin:0 auto;
			<?php if(checkmobile()){ ?>
			left:10%;
			width: 80%;
            <?php }else{ ?>
            left:40%;
			width: 20%;
            <?php }?>
			background: linear-gradient(263deg,#fe7e88 0%,#ffbb6e 100%);
			font-size: 17px;
			padding:13px;
			bottom:2.8rem;
			font-weight: bold;
			z-index: 99999;
		}
@keyframes fadeIn {
  0% {
    opacity: 0;
    top: -20px;
  }
  60% {
    opacity: 1;
    top: 20px;
  }
  80% {
    opacity: 1;
    top: 20px;
  }
  90% {
    opacity: 0.5;
    top: 25px;
  }
  100% {
    opacity: 0;
    top: 30px;
  }
}


.fadeIn {
  animation: fadeIn 3.5s infinite;
}

</style>
	<!--<button class="qunbtn" onClick="btnfun()" id="qunbtn">立即付费9.9加入群聊</button>
	
	<button class="qunbtnss" id="qunbtn" onclick="location='这里填跳转链接'">招收代理</button>-->

<button class="qunbtn" onclick="showPaymentOptions()">
  <?php echo $row['ename10'];?>
</button>
	<div id="tzurlcontent"></div>
	

	<style>
.payment-option {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  margin-bottom: 20px;
}

.payment-logo {
  margin-right: 15px;
}
</style>

<script>



var modalExists = false;  // 新增一个变量来判断弹窗是否已经存在

function showPaymentOptions() {
  if (modalExists) {  // 如果弹窗已经存在，直接返回，不再创建新的弹窗
    return;
  }

  var modal = document.createElement('div');
  modal.style.position = 'fixed';
  modal.style.top = '0';
  modal.style.left = '0';
  modal.style.width = '100%';
  modal.style.height = '100%';
  modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
  modal.style.display = 'flex';
  modal.style.justifyContent = 'center';
  modal.style.alignItems = 'center';

  var content = document.createElement('div');
  content.style.backgroundColor = 'white';
  content.style.padding = '20px';
  content.style.borderRadius = '10px';
  content.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.3)';
  content.style.width = '80%';  
  content.style.maxHeight = '40%';  
  content.style.overflowY = 'auto';  

  content.innerHTML = `
    <div style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">付款方式<span style="font-size: 13px;font-weight:400;"> 账户余额:<?php echo $userrow['rmb'] ?? '请先登录后查看'; ?></span></div>

<?php if (!$islogin2) { ?>
    <div class="payment-option" onclick="window.location.href='./user/login.php'" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; background-color: #f5f5f5; border-radius: 15px; width: 80%; margin: 0 auto; margin-bottom: 20px;">
            <img src="../assets/img/icon/yonghutb.png" class="payment-logo" width="30" height="30">
            <span>前往登录/注册</span>
        </div>
<?php } ?>

<?php
      /*
    if($conf['alipay_api'])echo '
     <div class="payment-option" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; background-color: #f5f5f5; border-radius: 15px; width: 80%; margin: 0 auto; margin-bottom: 20px;">
          <img src="../assets/img/alipay.png" class="payment-logo" width="30" height="30">
          <span>支付宝</span>
          </div>
        ';
   */
?>

    <div class="payment-option" onclick="window.location.href='./?mod=ccpay&amp;id=<?php echo $row['id']?>'" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; background-color: #f5f5f5; border-radius: 15px; width: 80%; margin: 0 auto; margin-bottom: 20px;">
            <img src="../assets/img/rmb.png" class="payment-logo" width="30" height="30">
            <span>余额购买</span>
        </div>
        
    <div class="payment-option" onclick="window.location.href='./user/recharge.php'" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; background-color: #f5f5f5; border-radius: 15px; width: 80%; margin: 0 auto; margin-bottom: 20px;">
            <img src="../assets/img/rmb.png" class="payment-logo" width="30" height="30">
            <span>充值余额</span>
        </div>

  `;

  modal.appendChild(content);
  document.body.appendChild(modal);

  // 点击空白处关闭弹窗
  modal.addEventListener('click', function(event) {
    if (event.target === modal) {
      modal.remove();
      modalExists = false;  // 弹窗关闭时，将标志位设置为false
    }
  });

  // 为每个支付选项添加点击事件
 var paymentOptions = content.querySelectorAll('.payment-option');
paymentOptions.forEach(function(option) {
    option.addEventListener('click', function() {
        var paymentMethod = this.querySelector('span').textContent;
        if (paymentMethod === '微信支付') {
            window.location.href = '' ;// 这里修改为微信支付的跳转链接
        } else if (paymentMethod === '支付宝') {
            window.location.href = '../other/submit2.php?type=alipay&id=<?php echo $row['id']?>';  // 这里修改为支付宝的跳转链接
        } else if (paymentMethod === 'QQ钱包') {
            window.location.href = '';  // 这里修改为 QQ 钱包的跳转链接
        }
        modal.remove();
        modalExists = false;  // 弹窗关闭时，将标志位设置为 false
    });
});

  modalExists = true;  // 成功创建弹窗后，将标志位设置为true
}
  function dopay(type) {
        var value = $("input[name='value']").val();
        if (value == '' || value == 0) {
            layer.alert('充值金额不能为空');
            return false;
        }
        $.get("ajax_user.php?act=recharge&type=" + type + "&value=" + value, function (data) {
            if (data.code == 0) {
                window.location.href = '../other/submit.php?type=' + type + '&orderid=' + data.trade_no;
            } else {
                layer.alert(data.msg);
            }
        }, 'json');
    }
 $(document).ready(function () {
        $("#buy_alipay").click(function () {
            dopay('alipay')
        });
        $("#buy_qqpay").click(function () {
            dopay('qqpay')
        });
        $("#buy_wxpay").click(function () {
            dopay('wxpay')
        });
      
    })
 var names = ["ID:7","ID:2*","ID:5","ID:1*","ID:9","ID:3","ID:4*","ID:6","ID:8"];
 var names_count = names.length;
 var names_i = 0;

 setInterval(function(){
	
	if($("#idjjshow").is(':visible')){
		$("#idjshow").html("");
	}else{
		if(names_i == names_count-1){
			names_i = 0;
		}else{
			names_i = names_i + 1;
		}
		
		tmp_name = names[names_i];
		tmp_str  = '<div class="idjshow fadeIn" id="idjjshow">'+tmp_name+'*** 刚刚支付了<?php echo $row['money'];?>元成功加入群聊</div>';
		$("#idjshow").html(tmp_str);
		
	}
	console.log(tmp_str);
 },5000); 
 
</script>
    <script>
        
        var qunbtn = "<?php echo $row['ename10'];?>";
        qunbtn = qunbtn.replace("[加粗]","<strong>")
        qunbtn = qunbtn.replace("[/加粗]","</strong>")
        qunbtn = qunbtn.replace("[加大+1]","<font size='+1'>")
        qunbtn = qunbtn.replace("[加大+2]","<font size='+2'>")
        qunbtn = qunbtn.replace("[加大+3]","<font size='+3'>")
        qunbtn = qunbtn.replace("[加大+4]","<font size='+4'>")
        qunbtn = qunbtn.replace("[加大+5]","<font size='+5'>")
        qunbtn = qunbtn.replace("[/加大]","</font>")
        $("#qunbtn").html(qunbtn);
        
        
        // var titles = "聊天-交友-找搭子";
        // var citycode =returnCitySN.cname;
        // titles = titles.replace("【本地】",citycode)
        // $("#quntit").html(titles);
        // $("title").html(titles);
    </script>

</body>
