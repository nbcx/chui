<style>
    .portlet>div>a {
        color: #467B96;
        text-decoration: none;
    }
</style>
<div class="sortable">
    <div class="two_third column">
        <div class="portlet">
            <form method="post">
	<textarea id="textarea_1" name="content" style="width: 100%" rows="15">/*This is some css that will be editable with EditArea.*/
body, html{
	margin: 0;
	padding: 0;
	height: 100%;
	border: none;
	overflow: hidden;
}</textarea>
            </form>
        </div>
    </div>

    <div class="one_third last column">
        <div class="portlet">
            <div class="portlet-header">模板文件</div>
            <div><a href="#">index.php</a></div>
            <div><a href="#">comments.php</a></div>
            <div><a href="#">footer.php</a></div>
            <div><a href="#">functions.php</a></div>
            <div><a href="#">page.php</a></div>
        </div>
    </div>

</div> <!-- sortable end -->
<div class="clearboth"></div>
<a href="columns.html" class="button_link" >保存</a>
<script type='text/javascript' src='_pub_plugin/editarea/edit_area_full.js'></script>
<script language="javascript" type="text/javascript">
    jQuery.getScript('_pub_plugin/editarea/edit_area_full.js',function(){
        editAreaLoader.init({
            base:'_pub_plugin/editarea/'
            ,language:'zh'
            ,id : "textarea_1"
            ,syntax: "css"
            ,start_highlight: true
        });
    });
    jQuery(document).on('pjax:complete', function() {
        loadScript('_pub_plugin/editarea/edit_area_full.js', function () { //加载,并执行回调函数

        });
        /*
        jQuery.getScript('_pub_plugin/editarea/edit_area_full.js',function(){
            editAreaLoader.init({
                base:'_pub_plugin/editarea/'
                ,language:'zh'
                ,id : "textarea_1"
                ,syntax: "css"
                ,start_highlight: true
            });
        });
        */
    });

</script>
