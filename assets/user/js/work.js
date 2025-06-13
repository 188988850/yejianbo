

function tap_tab(t = 'contribute') {
    if(t == 'thing'){
        $('#thing').addClass('a_tap');
        $('#contribute').removeClass('a_tap');
    }else{
        $('#contribute').addClass('a_tap');
        $('#thing').removeClass('a_tap');

    }
    get_list(t);
}

function get_list(type = 'contribute') {
    layui.use(['flow'], function(){
        var flow = layui.flow;
        $("#list").remove();
        $(".flowlist").append("<div id=\"list\" ></div>");
        var end = "没有更多数据了";
        var mb = 180;
        var layui_index = load();
        $(".show_class").show();
        flow.load({
            elem: '#list', //流加载容器
            isAuto:true,
            end:end,
            mb:mb,
            done: function(page, next){ //执行下一页的回调
                var lis = [];
                //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                $.ajax({
                    type : "post",
                    url : "./ajax.php?act=thing",
                    data : {page:page,limit:10,type:type},
                    dataType : 'json',
                    success : function(res) {

                        layui.each(res.data, function(index, item){
                            var status = '<font>未通过</font>';
                            if(item.status == 0){
                                status ='<font>已通过</font>';
                            }else if(item.status == 1){
                                status ='<font>未通过</font>';
                            }else if(item.status == 88){
                                 status ='<font>未通过</font>';
                            }
                            html  = '<div class="list-item">';
                            html +=      '<div class="list-item-top">';
                            if(type == 'contribute'){
                                html +=       '<div class="item-logo-1">';
                                html +=          '<div class="item-logo-img">' + status + '</div>';
                                html +=      '</div>';
                            }else {
                                html +=      '<div class="item-logo-2">';
                                html +=          '<div class="item-logo-img">平台</div>';
                                html +=      '</div>';
                            }
                            html +=          '<div class="item-operate">';
                            html +=              '<a class="item-operate-item item-operate-border" onclick="delete_item(\'' + type + '.php?my=delete&tid=' + item.id + '\')">删除</a>';
                            html +=          '</div>';
                            html +=      '</div>';
                            html +=      '<div class="list-item-c">';
                            html +=          '<div class="item-c-txet">';
                            html +=              '<div class="item-c-title">商品名称</div>';
                            html +=              '<div class="item-c-data ellipsis1">' + item.name + '</div>';
                            html +=          '</div>';
                            html +=          '<div class="item-c-txet">';
                            html +=              '<div class="item-c-title">商品销售</div>';
                            html +=              '<div class="item-c-data">' + item.sales + '件</div>';
                            html +=          '</div>';
                            if(item.status == 2 && item.msg){
                                html +=          '<div style=" background:#f2f2f2;color: #a9a9a9;font-size: 1.1rem;border-radius: 5px;padding:5px 10px;line-height: 1.8rem">' + item.msg + '</div>';
                            }
                            html +=      '</div>';
                            html += '</div>';
                            lis.push(html);
                        });
                        $(".catname_show").html('<font >共找到'+res.total+'个商品</font>');
                        layer.close(layui_index);
                        next(lis.join(''), page < res.pages);
                    },
                    error:function(data){
                        layer.msg("获取数据超时");
                        layer.close(index);
                        return false;
                    }
                });
            }
        });
    });

}

function delete_item (url){
    if(!confirm('删除后将不可恢复,确定删除吗?')){
        return false;
    }

    window.location.href = url;
}

function load(text="加载中")
{
    var index = layer.load(1, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
    });
    return index;
}