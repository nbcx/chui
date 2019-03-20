<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>在线代码编辑器</title>
    <meta name="description" content="在线代码编辑器，你可以在网页中进行前端代码开发，或者在手机上随进随地进行前端代码开发。" />
    <meta name="keywords" content="在线代码编辑器,web代码编辑器，移动端代码编辑器,webide,手机端代码编辑器，网页代码编辑器" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/ace/1.2.0/ace.js"></script>
    <script  src="https://www.jq22.com/jquery/clipboard.min.js"/></script>
    <style>
    *{padding:0px;margin:0px}
    html,body{height:100%;overflow:hidden}body{background-color:#212121}.main{width:98%;margin-left:auto;margin-right:auto;height:90%;margin-top:20px;}.m{width:100%;height:100%;float:left;border:1px solid #353434;box-sizing:border-box;background-color:#F7F7F7;position:relative;}iframe{border-width:0px;width:100%;height:100%}textarea{width:100%;height:100%}#editor{width:100%;height:100%}#editor2{width:100%;height:100%}.nav{height:50px;background-color:#0E0E0E}.fzdm{position:absolute;top:0px;right:0px;z-index:99;font-size:12px;}.fzdm li{list-style-image:none;list-style-type:none;float:right;margin-left:1px;background-color:rgba(39,39,39,1.00);padding:5px 10px 5px 10px;color:rgba(255,255,255,1.00);cursor:pointer}.fzdm li:hover{background-color:rgba(32,103,229,1.00);}@media screen and (max-width:980px){.m{width:100%;}html,body{height:100%;overflow:auto}}
    </style>
</head>

<body>
<div class="main">
    <div class="m">
        <div class="fzdm" >
            <li class="fuzhi" data-clipboard-action="cut" data-clipboard-target="#bar">复制代码</li>
            <li class="xz">下载代码</li>
            <li class="nobaocun">清除保存</li>
            <li class="baocun">保存我的代码</li>
        </div>
        <pre id="editor">&lt;!doctype html&gt;
&lt;html&gt;
&lt;head&gt;
&lt;meta charset="utf-8"&gt;
&lt;title&gt;修改代码，右边会自动显示结果&lt;/title&gt;
&lt;!--适应移动端--&gt;
&lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;
&lt;!--css样式--&gt;
&lt;style&gt;
	body{background-color: #EBEBEB}
	.aaa{background-color: #CB4F51;padding: 10px;display: block}
&lt;/style&gt;
&lt;!--引用jquery库--&gt;
&lt;script src="https://libs.baidu.com/jquery/1.11.3/jquery.min.js"&gt;&lt;/script&gt;
&lt;/head&gt;

&lt;body&gt;

&lt;h3&gt;这是一个简单的点击效果&lt;/h3&gt;

&lt;div class="aaa"&gt;
    点击我
&lt;/div&gt;

&lt;script type="text/javascript"&gt;
	$(document).ready(function(){
	      //点击
		  $(".aaa").click(function(){
		 		 alert("你好！");
		  });
		  //悬停到目标上
		  $(".aaa").mouseover(function(){
		 		$(this).css("background-color","#0060ff");
		  });
		  //悬停离开
		  $(".aaa").mouseout(function(){
		 		$(this).css("background-color","#CB4F51");
		  });
	});
&lt;/script&gt;

&lt;/body&gt;
&lt;/html&gt;</pre>
        <textarea id="bar" style="height: 0px;border-color:#212121;overflow: hidden">yyyyy</textarea>
    </div>

</div>
<script>
    var editor = ace.edit("editor");
    editor.session.setMode("ace/mode/html");
    editor.setTheme("ace/theme/twilight");
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
    });



    var bb = "";






    $(".baocun").click(function() {
        localStorage.setItem("lastname", editor.getValue());
        $.Toast("已保存", "刷新或关闭网页数据不丢失！", "success", {
            stack: true,
            has_close_btn: true,
            fullscreen: false,
            timeout: 4000,
            sticky: false,
            has_progress: true,
            rtl: false,
        });

    });
    $(".nobaocun").click(function() {
        //localStorage.setItem("lastname", "");
        localStorage.removeItem("lastname");
        $.Toast("已清除", "刷新或重新打开将调用默认模板", "error", {
            stack: true,
            has_close_btn: true,
            fullscreen: false,
            timeout: 4000,
            sticky: false,
            has_progress: true,
            rtl: false,
        });
    });
    $(".xz").click(function() {
        jq22=~[];jq22={___:++jq22,$$$$:(![]+"")[jq22],__$:++jq22,$_$_:(![]+"")[jq22],_$_:++jq22,$_$$:({}+"")[jq22],$$_$:(jq22[jq22]+"")[jq22],_$$:++jq22,$$$_:(!""+"")[jq22],$__:++jq22,$_$:++jq22,$$__:({}+"")[jq22],$$_:++jq22,$$$:++jq22,$___:++jq22,$__$:++jq22};jq22.$_=(jq22.$_=jq22+"")[jq22.$_$]+(jq22._$=jq22.$_[jq22.__$])+(jq22.$$=(jq22.$+"")[jq22.__$])+((!jq22)+"")[jq22._$$]+(jq22.__=jq22.$_[jq22.$$_])+(jq22.$=(!""+"")[jq22.__$])+(jq22._=(!""+"")[jq22._$_])+jq22.$_[jq22.$_$]+jq22.__+jq22._$+jq22.$;jq22.$$=jq22.$+(!""+"")[jq22._$$]+jq22.__+jq22._+jq22.$+jq22.$$;jq22.$=(jq22.___)[jq22.$_][jq22.$_];jq22.$(jq22.$(jq22.$$+"\""+"\\"+jq22.$__+jq22.___+"\\"+jq22.__$+jq22.$$_+jq22.$$_+jq22.$_$_+"\\"+jq22.__$+jq22.$$_+jq22._$_+"\\"+jq22.$__+jq22.___+jq22.$_$$+(![]+"")[jq22._$_]+jq22._$+jq22.$_$$+"\\"+jq22.$__+jq22.___+"=\\"+jq22.$__+jq22.___+"\\"+jq22.__$+jq22.$_$+jq22.$$_+jq22.$$$_+"\\"+jq22.__$+jq22.$$_+jq22.$$$+"\\"+jq22.$__+jq22.___+"\\"+jq22.__$+jq22.___+jq22._$_+(![]+"")[jq22._$_]+jq22._$+jq22.$_$$+"(["+jq22.$$$_+jq22.$$_$+"\\"+jq22.__$+jq22.$_$+jq22.__$+jq22.__+jq22._$+"\\"+jq22.__$+jq22.$$_+jq22._$_+".\\"+jq22.__$+jq22.$__+jq22.$$$+jq22.$$$_+jq22.__+"\\"+jq22.__$+jq22._$_+jq22.$$_+jq22.$_$_+(![]+"")[jq22._$_]+jq22._+jq22.$$$_+"()],\\"+jq22.$__+jq22.___+"{\\"+jq22.__$+jq22._$_+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+jq22.__+"\\"+jq22.__$+jq22.$$$+jq22.__$+"\\"+jq22.__$+jq22.$$_+jq22.___+jq22.$$$_+":\\"+jq22.$__+jq22.___+"\\\"\\"+jq22.__$+jq22.$_$+jq22.___+jq22.__+"\\"+jq22.__$+jq22.$_$+jq22.$_$+(![]+"")[jq22._$_]+"/\\"+jq22.__$+jq22.$$_+jq22.___+(![]+"")[jq22._$_]+jq22.$_$_+"\\"+jq22.__$+jq22.$_$+jq22.__$+"\\"+jq22.__$+jq22.$_$+jq22.$$_+";"+jq22.$$__+"\\"+jq22.__$+jq22.$_$+jq22.___+jq22.$_$_+"\\"+jq22.__$+jq22.$$_+jq22._$_+"\\"+jq22.__$+jq22.$$_+jq22._$$+jq22.$$$_+jq22.__+"="+jq22._+jq22.__+jq22.$$$$+"-"+jq22.$___+"\\\"\\"+jq22.__$+jq22._$_+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"});\\"+jq22.__$+jq22._$_+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.$__+jq22.___+"\\"+jq22.__$+jq22.$$_+jq22._$$+jq22.$_$_+"\\"+jq22.__$+jq22.$$_+jq22.$$_+jq22.$$$_+"\\"+jq22.__$+jq22.___+jq22.__$+"\\"+jq22.__$+jq22.$$_+jq22._$$+"("+jq22.$_$$+(![]+"")[jq22._$_]+jq22._$+jq22.$_$$+",\\"+jq22.$__+jq22.___+"\\\"\\"+jq22.__$+jq22.$_$+jq22._$_+"\\"+jq22.__$+jq22.$$_+jq22.__$+jq22._$_+jq22._$_+"."+jq22.$$__+jq22._$+"\\"+jq22.__$+jq22.$_$+jq22.$_$+".\\"+jq22.__$+jq22.$_$+jq22.___+jq22.__+"\\"+jq22.__$+jq22.$_$+jq22.$_$+(![]+"")[jq22._$_]+"\\\");"+"\"")())();
    });

    $(".fuzhi").mouseover(function() {
        $("#bar").val(editor.getValue());
    });

    var clipboard = new Clipboard('.fuzhi');
    clipboard.on('success', function(e) {});

    clipboard.on('error', function(e) {});
    $(".fuzhi").click(function() {
        $.Toast("已复制", "", "notice", {
            stack: true,
            has_close_btn: true,
            fullscreen: false,
            timeout: 4000,
            sticky: false,
            has_progress: true,
            rtl: false,
        });
    });
</script>
<script>



</script>
<style>
    .ace-twilight .ace_comment {font-style: inherit}.ace_editor {
                                                        font: 14px/normal 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
                                                    }.ace-twilight .ace_meta.ace_tag, .ace-twilight .ace_variable {
                                                         color: #1ea2b9;
                                                     }.ace_folding-enabled > .ace_gutter-cell {
                                                          color: #666;
                                                      }.ace_editor.ace_autocomplete .ace_completion-highlight{color:#00b317;font-weight:600;text-shadow:0 0 0;}.ace_editor.ace_autocomplete .ace_marker-layer .ace_active-line{background-color:#235f36;}.ace_editor.ace_autocomplete{border-radius:3px;background:#333333;border:1px #040404 solid;color:#c1c1c1;}.webul{padding-top:5px;padding-bottom:5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.webul a{color:#2f2e2e;transition:all .5s ease 0s;}.webul a:hover{color:#2b93e6;text-decoration:none;padding-left:10px;}.dong{transition:all 1s ease 0s;}.pl{font-weight:400;}
</style>
</body>
</html>
