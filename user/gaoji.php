<?php
include("../includes/common.php");
$title = '高级设置';
include './head.php';
if ($islogin2 == 1) {
} else {
    exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
?>
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block" style="float: none; background-color:#f2f2f2;padding:0;max-width:500px;">
    <div class="block block-all">
        <div class="block-back block-white">
            <a href="./" class="font-weight display-row align-center">
                <img style="height: 2rem" src="../assets/user/img/close.png">&nbsp;&nbsp;
                <font>高级设置</font>
            </a>
        </div>

        <!-- 虚拟订单开关 -->
        <div class="form-group form-group-transparent" style="background: #f2f2f2; padding:8px 10px">
            <div class="input-group" style="width:100%">
                <div class="input-group-addon" style="color:#969494;font-size:13px;">
                    虚拟订单开关
                </div>
                <div id="virtual_order" class="switch switch-off"></div>
            </div>
        </div>

        <!-- 虚拟金额设置 -->
        <div class="form-group form-group-transparent" style="background: #f2f2f2; padding:8px 10px">
            <div class="input-group" style="width:100%">
                <div class="input-group-addon" style="color:#969494;font-size:13px;">
                    虚拟金额设置
                </div>
            </div>
        </div>
        <div class="block-white" style="padding:0 10px">
            <div class="form-group form-group-transparent form-group-border-bottom">
                <input type="text" id="my_balance" class="form-control" placeholder="我的余额">
            </div>
            <div class="form-group form-group-transparent form-group-border-bottom">
                <input type="text" id="today_income" class="form-control" placeholder="今日收入">
            </div>
            <div class="form-group form-group-transparent form-group-border-bottom">
                <input type="text" id="month_income" class="form-control" placeholder="本月收入">
            </div>
            <div class="form-group form-group-transparent form-group-border-bottom">
                <input type="text" id="total_income" class="form-control" placeholder="总收入">
            </div>
            <div class="text-center" style="padding: 30px 0;">
                <input onclick="saveAmount()" class="btn submit_btn" style="width: 50%;padding:8px;" value="保存">
            </div>
        </div>

        <!-- 虚拟收益订单生成 -->
        <div class="form-group form-group-transparent" style="background: #f2f2f2; padding:8px 10px">
            <div class="input-group" style="width:100%">
                <div class="input-group-addon" style="color:#969494;font-size:13px;">
                    虚拟收益订单生成
                </div>
            </div>
        </div>
        <div class="block-white" style="padding:0 10px">
            <div class="form-group form-group-transparent form-group-border-bottom">
                <input type="number" id="order_count" class="form-control" placeholder="订单数量 (1-100)">
            </div>
            <div class="text-center" style="padding: 30px 0;">
                <input type="submit" onclick="generateOrders()" class="btn submit_btn" style="width: 50%;padding:8px;" value="生成">
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    // 加载设置
    function loadSettings() {
        $.ajax({
            url: 'ajax_gaoji.php?act=get_settings',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.code == 0) {
                    $('#my_balance').val(res.data.my_balance);
                    $('#today_income').val(res.data.today_income);
                    $('#month_income').val(res.data.month_income);
                    $('#total_income').val(res.data.total_income);
                    if (res.data.virtual_order == 1) {
                        $('#virtual_order').addClass('switch-on').removeClass('switch-off');
                    } else {
                        $('#virtual_order').addClass('switch-off').removeClass('switch-on');
                    }
                } else {
                    alert(res.msg);
                }
            },
            error: function(xhr, status, error) {
                alert('加载设置失败：' + error);
            }
        });
    }

    // 初始加载设置
    loadSettings();

    // 开关切换
    $('#virtual_order').on('click', function() {
        const isOn = $(this).hasClass('switch-on');
        const status = isOn ? 0 : 1;

        $.ajax({
            url: 'ajax_gaoji.php?act=toggle_virtual_order',
            type: 'POST',
            data: {status: status},
            dataType: 'json',
            success: function(res) {
                if (res.code == 0) {
                    alert(res.msg);
                    $('#virtual_order').toggleClass('switch-on', status === 1).toggleClass('switch-off', status === 0);
                } else {
                    alert(res.msg);
                }
            },
            error: function(xhr, status, error) {
                alert('设置失败：' + error);
            }
        });
    });

    // 保存金额设置
    window.saveAmount = function() {
        const data = {
            my_balance: $('#my_balance').val(),
            today_income: $('#today_income').val(),
            month_income: $('#month_income').val(),
            total_income: $('#total_income').val()
        };

        for (const key in data) {
            if (data[key] === '' || isNaN(data[key]) || parseFloat(data[key]) < 0) {
                alert('请输入有效的金额');
                return;
            }
        }

        $.ajax({
            url: 'ajax_gaoji.php?act=save_settings',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                if (res.code == 0) {
                    alert(res.msg);
                    loadSettings();
                } else {
                    alert(res.msg);
                }
            },
            error: function(xhr, status, error) {
                alert('保存失败：' + error);
            }
        });
    };

    // 生成虚拟订单
    window.generateOrders = function() {
        const count = $('#order_count').val();

        if (count < 1 || count > 100 || isNaN(count)) {
            alert('订单数量必须在1-100之间');
            return;
        }

        $.ajax({
            url: 'ajax_gaoji.php?act=generate_orders',
            type: 'POST',
            data: {count: count},
            dataType: 'json',
            success: function(res) {
                if (res.code == 0) {
                    alert(res.msg);
                    $('#order_count').val('');
                } else {
                    alert(res.msg);
                }
            },
            error: function(xhr, status, error) {
                alert('生成订单失败：' + error);
            }
        });
    };
});
</script>
<?php include './foot.php'; ?>