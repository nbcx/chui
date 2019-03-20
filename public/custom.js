//使用 noConflict() 方法为 jQuery 变量规定新的名称：
//该方法释放 jQuery 对 $ 变量的控制
//jQuery.noConflict();
function initMenu() {
    //jQuery('#menu ul ul').hide();
    //jQuery('#menu ul div').hide();
    $('#menu ul li>a').click(function() {
        $(this).parent().parent().find("ul").slideUp('fast');
        $(this).parent().parent().find("div").slideUp('fast');
        $(this).parent().parent().find("li").removeClass("current");
        //jQuery(this).parent().find("ul").slideToggle('fast');
        jQuery(this).parent().find("div").slideToggle('fast');
        jQuery(this).parent().toggleClass("current");
    });
    $('#menu ul div a').click(function(e) {
        $(this).parent().parent().parent().find("a").removeClass("on");
        $(this).toggleClass("on");
    });
}
function boxheight(){ //函数：获取尺寸
    //获取浏览器窗口高度
    var winHeight=0;
    if (window.innerHeight)
        winHeight = window.innerHeight;
    else if ((document.body) && (document.body.clientHeight))
        winHeight = document.body.clientHeight;
    //通过Document对body进行检测，获取浏览器可视化高度
    if (document.documentElement && document.documentElement.clientHeight)
        winHeight = document.documentElement.clientHeight;
    //DIV高度为浏览器窗口高度
    document.getElementById("primary_right").style.height= winHeight +"px";
}

//消息处理机制
function message() {
    var prefix = '',
        cookies = {
            notice      :   $.cookie(prefix + '__nb_notice'),
            noticeType  :   $.cookie(prefix + '__nb_notice_type'),
            position    :   $.cookie(prefix + '__nb_notice_position')
        },
        path = '/';

    if (cookies.notice && 'success|info|error|warning'.indexOf(cookies.noticeType) >= 0) {
        var p = $.parseJSON(cookies.notice);

        if (!cookies.position || cookies.position == 'null') {
            cookies.position = "toast-top-right";
        }
        toastr.options = {
            "positionClass": cookies.position
        }
        toastr[cookies.noticeType](p)

        $.cookie(prefix + '__nb_notice', null, {path : path});
        $.cookie(prefix + '__nb_notice_type', null, {path : path});
        $.cookie(prefix + '__nb_notice_position', null, {path : path});
    }
}

function ajax(url,type,data) {
    $.ajax({
        type: type?type:"GET",
        url: url,
        data: data?data:{},
        dataType: "json",
        success: function(data){
            if(data.url) {
                $.pjax({ url: data.url, container: data.container });
            }
            else if(data.container) {
                $.pjax.reload(data.container);
            }
            else {
                $.pjax.reload('#content');
            }
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            toastr.error(textStatus)
        },
        complete:function(XMLHttpRequest, textStatus){
            message();  //调用本次ajax请求时传递的options参数
        }

    })
}
$(document).ready(function() {
	
	//Cufon.replace('h1, h2, h5, .notification strong', { hover: 'true' }); // Cufon font replacement
	initMenu(); // Initialize the menu!
	

	$(".tooltip").easyTooltip({
		xOffset: -60,
		yOffset: 70
	}); // Tooltips! 
			
	$('#menu li:not(".current"), #menu ul ul li a').hover(function() {
		$(this).find('span').animate({ marginLeft: '5px' }, 100);
	}, function() {
		$(this).find('span').animate({ marginLeft: '0px' }, 100);
	}); // Menu simple animation
			
	$('.fade_hover').hover(
		function() {
			$(this).stop().animate({opacity:0.6},200);
		},
		function() {
			$(this).stop().animate({opacity:1},200);
		}
	); // The fade function

    //窗口或框架被调整大小时执行
    window.onresize=boxheight;
    boxheight(); //执行函数
    message();

    $(document).pjax('[pjax-content] a, a[pjax-content]', '#content');
    $(document).on('pjax:start', NProgress.start).on('pjax:end', NProgress.done).on('pjax:end', function () {
        // 处理消息机制
        message();
    });
    $('#dialog-confirm').dialog({modal: true,autoOpen: false,width: 350});
    $('#dialog-form').dialog({modal: true,autoOpen: false,width: 650});
    $('#dialog').dialog({modal: true,autoOpen: false,width: 650});
    $(document).on("submit",'#dialog-form form',function (e) {
        //$.pjax.submit(e, '#content');
        //$.pjax.reload('#content');
        var action = $(this).attr("action");
        $.post(action, $(this).serialize(),function (data) {
            if(data.url) {
                $.pjax({ url: data.url, container: data.container });
            }
            else if(data.container) {
                $.pjax.reload(data.container);
            }
            else {
                $.pjax.reload('#content');
            }
        },"json");
        return false;
    });
    $(document).on("click",'[dialog-confirm]',function (e) {
        e.preventDefault()
        var href = $(this).attr("href");
        $.get(href, function(result){
            $("#dialog-confirm").html(result);
            var opt = {
                title:'Dialog Title',
                buttons: {
                    "确定": function() {
                        $(this).dialog("close");
                    },
                    "取消": function() {
                        $(this).dialog("close");
                    }
                }
            }
            jQuery('#dialog').dialog(opt).dialog('open');
        });
    })
    $(document).on("click",'[dialog]',function (e) {
        e.preventDefault()
        var href = $(this).attr("href");
        $.get(href, function(result){
            $("#dialog").html(result);
            var opt = {
                title:'Dialog Title',
                buttons:{}
            }
            $('#dialog').dialog(opt).dialog('open');
        });
    })
    $(document).on("click",'[dialog-form]',function (e) {
        e.preventDefault()
        var href  = $(this).attr("href"),
            title = $(this).attr("title");

        $.get(href, function(result){
            $("#dialog-form").html(result);
            var opt = {
                title:title,
                buttons: {
                    "确定": function() {
                        $(this).dialog("close");
                        $("#dialog-form form").submit()
                    },
                    "取消": function() {
                        $(this).dialog("close");
                    }
                }
            }
            $('#dialog-form').dialog(opt).dialog('open');
        });
    })
    $(document).on("click",'[ajax]',function (e) {
        e.preventDefault()
        var href = $(this).attr("href");
        ajax(href);
    })

    $(document).on("click",'[ajax-form]',function (e) {
        e.preventDefault()
        var form = $(this).attr("form");
        var href = $(form).attr("action");
        var method = $(form).attr("method");
        ajax(href,method,$(form).serialize());
    })
});





