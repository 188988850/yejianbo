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
    

    
    /*点击切换风格*/
    $("#listblock").on("click",function(){

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
    setInterval(get_data, 10000);
}
function get_data() {
    if (window._getting_data) return;
    window._getting_data = true;
    
    $.ajax({
        type: "get",
        url: "./other/getdatashow.php",
        async: true,
        dataType: 'json',
        success: function(data) {
            if(data.code==1){
                $('#xn_text').text(data.text+" "+data.time+'前')
                    .fadeIn(1000);
                setTimeout(function() {
                    $('#xn_text').fadeOut(1000);
                }, 4000);
            }
        },
        complete: function() {
            window._getting_data = false;
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
function get_goods(){
    load();
    var cid = $("input[name=_cid]").val();
    var kw = $("input[name=kw]").val();
    var sort_type = $("input[name=_sort_type]").val();
    var sort = $("input[name=_sort]").val();
    
    if (window._getting_goods) return;
    window._getting_goods = true;
    
    $.ajax({
        type: "POST",
        url: "ajax.php?act=getgoods",
        data: {cid:cid,kw:kw,sort_type:sort_type,sort:sort},
        dataType: 'json',
        success: function(data) {
            $("#goods_list").empty();
            $("#goods_list").html(data.data);
            if(data.count == 0){
                $("#goods_list").html('<div class="fui-loading empty">未找到相关商品</div>');
            }
            $(".catname_show").html(data.catname);
            loaded();
        },
        complete: function() {
            window._getting_goods = false;
        }
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

function lazyLoadImages() {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

$(document).ready(function() {
    if(template_virtualdata == 1){
        ka();
    }
    
    setTimeout(function() {
        lazyLoadImages();
    }, 100);
    
    get_goods();
});