var template_virtualdata = $("input[name=_template_virtualdata]").val();
var template_showsales = $("input[name=_template_showsales]").val();
var curr_time = $("input[name=_curr_time]").val();

$(function() {
    //排序点击
    $(".goods_sort .item").on("click",function(){
       var sort = $(this).data("order"); //获取排序类型
       if(!sort){
           return false;
       }
       var sort_type = $(this).data("sort"); //获取类型
       if(sort_type == "DESC")
       {
           var sort_type_new = "ASC";
       }else{
           var sort_type_new = "DESC";
       }

        //移除其他已点击
        $(".goods_sort div").attr("class","item item-price");
        $(this).addClass(sort_type); 
        $(this).data("sort",sort_type_new);
        $('.goods_sort div').removeClass('on');
        $(this).addClass("on");
        $("input[name=_sort_type]").val(sort);
        $("input[name=_sort]").val(sort_type);
        get_goods();
    });
    if ($(".swiper-wrapper .content-slide").length > 1) {
        var swiper = new Swiper('.swiper-container', {
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
            renderBullet: function (index, className) {
              return '<span class="' + className + '">' + (index + 1) + '</span>';
            },
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
            mousewheel: true,
            keyboard: true,
            // loop:true,
        });
        $(".swiper-button-next").show();
        $(".swiper-button-prev").show();
    }  
    jQuery(function ($) {
        $(window).resize(function () {
            var width = $('#js-com-header-area').width();
            $('.touchslider-item a').css('width', width);
            $('.touchslider-viewport').css('height', 200 * (width / 640));
        }).resize();
    });

    if(template_virtualdata == 1){
        ka();
    }

    get_goods();
    $(".get_cat").on("click",function()
    {
        var cid = $(this).data("cid");
        var name = $(this).data("name");
         $("#tabtopl").html(name);
        		$.ajax({
		type : "GET",
		url : "./ajax.php?act=cidr&cid="+cid+"",
		dataType : 'json',
		success : function(cidrd) {
	if(cidrd.code>=1){
	   $(".hotxy").hide();
	}else{
	    $(".hotxy").show();
	    if(cid!=cidrd.cid){}else{
	        	$("#classtab").html(null);
	    		$.each(cidrd.data, function (i, res) {
					$("#classtab").append(' <a data-cid="'+res.cid+'" data-name="'+res.name+'" onclick="cidr('+res.cid+')" class="get_tab tab-bottom-item">'+res.name+'</a>');
					
				});
			
	    }
	}
		}
	
	});
        $(".ico img").addClass('imgpro');
       
       $("#"+cid).removeClass("imgpro").addClass('');
       
        var name = $(this).data("name");
        if($(this).hasClass("shop_active")){
            //return false;
        }
        $('.device .content-slide a').removeClass('shop_active');
        $("input[name=kw]").val("");
        $("input[name=_cid]").val(cid);
        $("input[name=_cidname]").val(name);
        get_goods();
        $(this).addClass('shop_active');
		history.replaceState({}, null, './?cid='+cid);
    });
    
        $(".get_tab").on("click",function()
    {
        var cid = $(this).data("cid");
        	
        $(".ico img").addClass('imgpro');
       
       $("#"+cid).removeClass("imgpro").addClass('');
       
        var name = $(this).data("name");
        if($(this).hasClass("shop_active")){
            //return false;
        }
        $('.device .content-slide a').removeClass('shop_active');
        $("input[name=kw]").val("");
        $("input[name=_cid]").val(cid);
        $("input[name=_cidname]").val(name);
        get_goods();
        $(this).addClass('shop_active');
		history.replaceState({}, null, './?cid='+cid);
    });
    //点击搜索拦截
    $("#goods_search").submit(function(e){
      var km = $("input[name=kw]").val();
      if(km == "")
      {
          layer.msg("请输入关键词进行查询");
          return false;
      }
      $("input[name=_cid]").val("");
      $("input[name=_cidname]").val("");
    $(".catname_show").html("正在获取数据");
    $(".show_class").hide();
    $('.device .content-slide a').removeClass('shop_active');
      get_goods();
     document.activeElement.blur();
      return false;
    });
    
    if($.cookie('goods_list_style') == 'list'){
        $("#listblock").data("state","gongge");
        $("#listblock").removeClass("icon-sort");
        $("#listblock").addClass("icon-app");
        $("#goods-list-container").removeClass("block three");
    }
    
    /*点击切换风格*/
    $("#listblock").on("click",function(){
        var index = layer.msg('加载中', {
          icon: 16
          ,shade: 0.01
        });
        var attr = $(this).data("state");
        if(attr == 'gongge'){
            $(this).data("state","list");
            $(this).removeClass("icon-app");
            $(this).addClass("icon-sort");
            $("#goods-list-container").addClass("block three");
        }else{
            $(this).data("state","gongge");
            $(this).removeClass("icon-sort");
            $(this).addClass("icon-app");
            $("#goods-list-container").removeClass("block three");
        }
        //设置cookie
        var cookietime = new Date(); 
        cookietime.setTime(cookietime.getTime() + (86400));
        $.cookie('goods_list_style', attr, { expires: cookietime });
        layer.close(index);
    });
        
    //弹窗广告
    if( !$.cookie('op')){
        $('.tzgg').show();
        $.cookie('op', false, { expires: 1});
    }
    
        /**
     * 兼容iphone
     * @type {number | boolean | *}
     */
    var isIphoneX = window.devicePixelRatio && window.devicePixelRatio === 3 && window.screen.width === 375 && testUA('iPhone');

    if (isIphoneX && window.history.length <= 2) {
        // document.body.classList.add('fix-iphonex-bottom');
//        $(".fui-navbar,.cart-list,.fui-footer,.fui-content.navbar").addClass('iphonex')
        $(".fui-navbar").css("bottom", "0px");
    } else {
        $(".fui-navbar,.cart-list,.fui-footer,.fui-content.navbar").removeClass('iphonex');
    }
});

function ka() {
	setInterval("get_data()",6000);
}
function get_data() {
	$.ajax({
		type : "get",
		url : "./other/getdatashow.php",
		async: true,
		dataType : 'json',
		success : function(data) {
			if(data.code==1){
				$('#xn_text').text(data.text+" "+data.time+'前');
				$('#xn_text').fadeIn(1000);
				setTimeout("$('#xn_text').fadeOut(1000);",4000);
			}
		}
	});
}



function testUA(str) {
    return navigator.userAgent.indexOf(str) > -1
}

function load(text="商品加载中...")
{

}

//获取商品
function get_goods(time,time2,cssd){
    $("#c"+cssd).addClass("classacvt").siblings().removeClass("classacvt");
    var regex_match = /(nokia|iphone|android|motorola|^mot-|softbank|foma|docomo|kddi|up.browser|up.link|htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte-|longcos|pantech|gionee|^sie-|portalmmm|jigs browser|hiptop|^benq|haier|^lct|operas*mobi|opera*mini|320x320|240x320|176x220)/i;
  var u = navigator.userAgent;
  if (null == u) {
   return true;
  }
  var result = regex_match.exec(u);
 
  if (null == result) {
   var wap=1;
  } else {
   var wap=2;
  }
    $("#goods_list").remove();
    $(".flow_load").append("<div id=\"goods_list\" ></div>");
    layui.use(['flow'], function(){
        var flow = layui.flow;
        var cid  = $("input[name=_cid]").val();
        var name  = $("input[name=_cidname]").val();
        var kw = $("input[name=kw]").val();
        var sort_type = $("input[name=_sort_type]").val();
        var sort = $("input[name=_sort]").val();
        var mb = testUA('Safari')?180:100;
        var end = kw?"没有更多数据了":" ";
        limit = 100
        if(name != "")
        {
            load();
        }
        //写入数据

        $(".show_class").show();  
        flow.load({
                elem: '#goods_list' //流加载容器
                ,isAuto:true
                ,mb:mb
                ,isLazyimg:true
                ,end:end
                ,done: function(page, next){ //执行下一页的回调
                    var lis = [];
                    //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                    $.ajax({
                    type : "post",
                    url : "./ajax.php?act=gettoolday",
                    data : {page:page,limit:limit,cid:cid,kw:kw,sort_type:sort_type,sort:sort,time:time},
                    dataType : 'json',
                    success : function(res) {
							$(".tag_name").hide();
							$(".tag_name ul").html("");
                            console.log(res);
                            //假设你的列表返回在data集合中
                            var html='';
                        if(res.bb=="2"){
                            layui.each(res.data, function(index, item){
                              
                                html = '<a class="fui-goods-item" title="'+item.name+'" onclick="ifbox(\'./?mod=buy5&tid='+item.tid+'\')">';
                                html += '<div class="image">';
                                if(!item.shopimg){
                                    item.shopimg="./assets/store/picture/error_img.png"
                                }
                                if(item.show_tag){
                                    show_tag = item.show_tag;
                                }else{
                                    if((curr_time-(item.addtime)) <= 604800000)
                                    {
                                        show_tag = "";
                                    }else{
                                        show_tag = "";
                                    }
                                }


                                //显示商品标签
                                 show_tag_html = "";
                                if(show_tag)
                                {
                                   // show_tag_html = '<div style="background: -webkit-linear-gradient(left, #f10707, #b92b2b);color:#FFFFFF;width: 60%;text-align: center;font-size:.2rem; padding:5px 0; border-radius: 10px 0 15px 0;position: absolute;">'+show_tag+'</div>';
                                         show_tag_html = '';
                                }
                               
                               
                                //库存为0的
                                    var shoukong = '';
                                    var kucun = '24小时全自动发货';
                                    if(item.is_stock_err == 1){
                                        shoukong = '';
                                    }

                                    if(item.stock > 0){
                                        kucun = '24小时全自动发货';
                                    }

                                    html += ''+show_tag_html+'<img class="lazy" lay-src="'+item.shopimg+'" src="./assets/store/picture/loadimg.gif" alt="'+item.name+'">'+shoukong+'';
                                    html += '</div>';


                                    html += '<div class="detail" style="height:unset;">';
                                    html += '<div class="defbox"><div class="def1"></div>&nbsp;'+item.classname+'&nbsp;&nbsp;&nbsp;';
                                    if(item.v==1){
                                    html += '<div class="def2"></div>&nbsp;第'+item.tid+'期';
                                    }
                                    html += '</div>';
                                    html += '<div class="name" style="color: #34495e;margin-top: 5px;">'+item.name+'</div>';
                                   if(item.isdesc==2){
                                     html += '<div style="color: #888;height:40px;font-size:.6rem;margin-top: 5px;">'+item.desc+'';
                                      html += '</div>';
                                   }
                                    var price = '<p class="minprice" style="font-size:.75rem;"><font style="font-size:.6rem; ">￥</font>'+item.price+'元</p>';
                                    if(item.price <=0){
                                        price = '<p class="minprice" style="font-size:.75rem;">免费领取</p>';
                                    }
                                    html += '';
                            

                               console.log(wap);
                          if(wap=='1'){
                                    html += '<div style="display: flex;margin-top:5px; ">';
                                    html +='<div><i class="layui-icon layui-icon-time"></i>'+item.time+'</div>';
                                     html +='<div style="margin-left:20px;"><i class="layui-icon layui-icon-fire" style="font-size:10px;"></i>'+item.sales+'</div>';
                                      html +='<div style="margin-left: auto;"><i class="layui-icon layui-icon-rmb"></i>'+item.price+'</div>';
                                      html += '</div>';
                                    }else if(wap=='2'){
                            html += '<div style="display: flex;margin-top:5px; justify-content: space-between;">';
                            html +='<div><i class="layui-icon layui-icon-time"></i>'+item.time+'</div>';
                             html +='<div> <i class="layui-icon layui-icon-fire" style="font-size:10px;"></i>'+item.sales+'</div>';
                              html +='<div><i class="layui-icon layui-icon-rmb"></i>'+item.price+'</div>';
                            html += '</div>';
                          }
                                  
                                    html += '</div>';
                                    html += '</a>';
                                    lis.push(html);
                                });
                        }else{
                             layui.each(res.data, function(index, item){
                              
                                html = '<a class="fui-goods-item" title="'+item.name+'" onclick="ifbox(\'./?mod=buy5&tid='+item.tid+'\')">';
                                html += '<div class="image">';
                                if(!item.shopimg){
                                    item.shopimg="./assets/store/picture/error_img.png"
                                }
                                if(item.show_tag){
                                    show_tag = item.show_tag;
                                }else{
                                    if((curr_time-(item.addtime)) <= 259200)
                                    {
                                        show_tag = "";
                                    }else{
                                        show_tag = "";
                                    }
                                }


                                //显示商品标签
                                 show_tag_html = "";
                                if(show_tag)
                                {
                                   // show_tag_html = '<div style="background: -webkit-linear-gradient(left, #f10707, #b92b2b);color:#FFFFFF;width: 60%;text-align: center;font-size:.2rem; padding:5px 0; border-radius: 10px 0 15px 0;position: absolute;">'+show_tag+'</div>';
                                         show_tag_html = '';
                                }

                                //库存为0的
                                    var shoukong = '';
                                    var kucun;
                                    
                                    if (item.price == 0) {
                                        kucun = '<p style="color: #f03867;font-size: 14px;font-weight:600;"">免费商品</p>';
                                    } else {
                                        kucun = '<p style="font-size: 10px;">￥<span style="color: #f03867;font-size: 14px;">' + item.price + '</span></p>';
                                    }
                                    if(item.is_stock_err == 1){
                                        shoukong = '';
                                    }
                                    //if(item.stock > 0){
                                    //kucun = '<p style="font-size: 12px;font-family: PingFangHK-Medium, PingFangHK;">￥'+item.price+'元 / 库存:较少</p>';
                                    //}
                                    //if(item.stock > 10){
                                    //kucun = '<p style="font-size: 12px;font-family: PingFangHK-Medium, PingFangHK;">￥'+item.price+'元 / 库存:一般</p>';
                                    //}
                                    //if(item.stock > 100){
                                    //kucun = '<p style="font-size: 12px;font-family: PingFangHK-Medium, PingFangHK;">￥'+item.price+'元 / 库存:较多</p>';
                                    //}
                                    //if(item.stock > 500){
                                    //kucun = '<p style="font-size: 12px;font-family: PingFangHK-Medium, PingFangHK;">￥'+item.price+'元 / 库存:充足</p>';
                                    //}

                                    if(template_showsales == 1){
								    //var suiji = Math.round(Math.random()*400+200);
									//html += '<div style="background: -webkit-linear-gradient(left, #ff9000 0,#ff5000 100%);color:#FFFFFF;width: 45%;text-align: center;font-size:.5rem; padding:5px 0;border-radius: 15px 0 10px 0;position: absolute;right: 0px;bottom:0px;">'+suiji+'人已购买</div>';
									html += '<div style="background: -webkit-linear-gradient(left, #ffa56a 0,#ffa56a 50%);color:#FFFFFF;width: 70%;text-align: center;font-size:.35rem; padding:3px 0;border-radius: 15px 0 10px 0;position: absolute;right: 0px;bottom:0px;background\-color: \#000; opacity: 0\.7;">'+timestampToTime(item.addtime)+'</div>';
                                    }
                                    html += ''+show_tag_html+'<img class="lazy" lay-src="'+item.shopimg+'" src="./assets/store/picture/loadimg.gif" alt="'+item.name+'">'+shoukong+'';
                                    html += '</div>';


                                    html += '<div class="detail" style="height:unset;">';
                                    html += '<div class="name" style="font-size: 13px;color: #000000;font-family: PingFangHK-Medium, PingFangHK;">'+item.name+'</div>';
                                    html += '<br>';
                                    var price = '';
                                    if(item.price <=0){
                                        price = '';
                                    }
                                    html += '';
                                    if(item.price <=0){
                                        buy = '<div class="rob_st"><img src="../assets/img/rob_icon3.png" style="width: 100%;height: 100%;"></div>';
                                    }else{
                                        buy = '<div class="rob_st"><img src="../assets/img/rob_icon5.png" style="width: 100%;height: 100%;"></div>';
                                    }

                                    if(item.stock == 0){
                                        buy = '<div class="rob_st"><img src="../assets/img/rob_icon4.png" style="width: 100%;height: 100%;"></div>';
                                    }

                                    if(item.close == 1){
                                        buy = '<div class="rob_st"><img src="../assets/img/rob_icon2.png" style="width: 100%;height: 100%;"></div>';
                                    }

                                    html += '<div class="price" style="margin-top: 0.2rem;background-color:rgba(242,242,242);border-radius: 0.2rem; "><span class="text" style="color:#f03867;font-size:0.65rem;line-height:1.4rem;padding-left: 10px">'+ kucun +'</span>'+buy+'</div>';
                                    html += '</div>';
                                    html += '</a>';
                                    lis.push(html);
                                });
                        }
                            
                            if(cid == "")
                            {
                                      
                                 $(".catname_c").hide(); $(".catname_show").show();  $(".catname_cc").hide();
                                $(".catname_show").html('共找到<font style="color: #7d7c7a;font-size: 12px;">'+res.total+'件商品</font>');
                            }else if(name != ""){
                                  $(".catname_c").hide(); $(".catname_show").show();  $(".catname_cc").hide();
                                $(".catname_show").html('<font style="color: #7d7c7a;font-size: 12px;">'+name+'</font>共有'+res.total+'件商品');
                             
                            }
                            if(kw != ""){
                            
                                   $(".catname_c").hide(); $(".catname_show").show();  $(".catname_cc").hide();
                                $(".catname_show").html('当前共有<font style="">'+res.total+'</font>件商品');
                            }else{
                                   $(".catname_c").hide(); $(".catname_show").show();  $(".catname_cc").hide();
                                   $(".catname_show").html('（全站商品共有'+res.count+'个,当前有'+res.total+'个）');                            }
                            layer.closeAll();
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
function timestampToTime(timestamp) {
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        var Y = date.getFullYear() + '年';
        var M = (date.getMonth()+1 < 10 ? ''+(date.getMonth()+1) : date.getMonth()+1) + '月';
        var D = date.getDate() + '日';
        var h = date.getHours() + ':';
        var m = date.getMinutes() + ':';
        var s = date.getSeconds();
        return Y+M+D;
    }
var audio_init = {
	changeClass: function (target,id) {
       	var className = $(target).attr('class');
       	var ids = document.getElementById(id);
       	(className == 'on')
           	? $(target).removeClass('on').addClass('off')
           	: $(target).removeClass('off').addClass('on');
       	(className == 'on')
           	? ids.pause()
           	: ids.play();
   	},
	play:function(){
		document.getElementById('media').play();
	}
}
if($('#audio-play').is(':visible')){
	audio_init.play();
}

/*layui.use(['util'], function(){
    var util = layui.util;
    //固定块客服
    util.fixbar({
        bar1: true
        ,bar2: true
        ,css: {right:8,bottom: '25%','z-index':1}
        ,bgcolor: '#393D49'
        ,click: function(type){
          if(type === 'bar1'){
            window.location.href = ("./?mod=kf");
          } else if(type === 'bar2') {
            window.location.href = ("./?mod=articlelist");
          }
        }
    });
});*/