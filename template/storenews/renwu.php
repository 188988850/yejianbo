<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
  <title>任务赚钱</title>
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
                <a href="./"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">任务赚钱</a></font>

            </div>
        </div>
    </div>
 <div style="padding-top: 60px;"></div>
<div class="main-content">
<?php if($conf['rwkq_01']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_01']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;color:#ff0000"><span><?php echo $conf['rwbt_01']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_01']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000"><?php echo $conf['rwnr_01']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_02']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_02']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;color:#ff0000"><span><?php echo $conf['rwbt_02']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_02']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000"><?php echo $conf['rwnr_02']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_03']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_03']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_03']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_03']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_03']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_04']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_04']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_04']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_04']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_04']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_05']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_05']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;color:#ff0000"><span><?php echo $conf['rwbt_05']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_05']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000"><?php echo $conf['rwnr_05']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_06']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_06']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_06']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_06']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_06']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_07']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_07']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_07']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_07']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_07']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_08']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_08']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;color:#ff0000"><span><?php echo $conf['rwbt_08']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_08']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000"><?php echo $conf['rwnr_08']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_09']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_09']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_09']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_09']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_09']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_10']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_10']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_10']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_10']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_10']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_11']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_11']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_11']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_11']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_11']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_12']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_12']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_12']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_12']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_12']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_13']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_13']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_13']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_13']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_13']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_14']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_14']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_14']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_14']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_14']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_15']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_15']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;color:#ff0000"><span><?php echo $conf['rwbt_15']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_15']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#ff0000"><?php echo $conf['rwnr_15']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_16']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_16']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_16']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_16']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_16']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_17']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_17']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_17']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_17']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_17']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_18']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_18']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_18']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_18']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_18']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_19']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_19']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_19']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_19']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_19']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php if($conf['rwkq_20']==1){?>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item">
            <div class="content-item-top">
                    <img src="<?php echo $conf['rwtp_20']; ?>" style="width: 20px; margin-right: 10px;">
                    <a style="font-weight: 600; font-size: 16px; margin-right: 8px;"><span><?php echo $conf['rwbt_20']; ?></span></a>
                    <a style="font-size: 14px; color: rgb(255, 91, 33); padding-left: 11px;"><span></span></a>
                    <div class="charge-btn"><a href="<?php echo $conf['rwlj_20']; ?>" style="color: #fff;" target="_blank">立即查看</a></div></div>
                    <div class="content-item-bottom">
                <div style="padding: 0px 0;font-size: 1.3rem;color: #858585;">
                    <font color="#000"><?php echo $conf['rwnr_20']; ?></font>
                </div>
            </div>
        </div>
    </div>
<?php }?>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; padding-top: 11px;">
        <div class="content-item1">
            </div>
        </div>
</div>
</body>
</html>