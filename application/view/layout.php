<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<title>{$title} - 管理后台 - {$conf.site_name}</title>

	<link type="text/css" href="_pub_test.css?v={:time()}" rel="stylesheet" /> <!-- the layout css file -->
	<link type="text/css" href="_pub_css/jquery.cleditor.css" rel="stylesheet" />
    <link type="text/css" href="_pub_plugin/nprogress/nprogress.css" rel="stylesheet" />
	
	<script type='text/javascript' src='_pub_js/jquery-1.9.1.min.js'></script>	<!-- jquery library -->
    <script src="_pub_js/jquery-migrate-1.2.1.js"></script>
    <script type='text/javascript' src='_pub_js/jquery.pjax.js'></script>
    <script type='text/javascript' src='_pub_plugin/nprogress/nprogress.js'></script>
    <!---
	<script type='text/javascript' src='_pub_js/jquery-ui-1.8.5.custom.min.js'></script> <!-- jquery UI ->

    <!---->
    <script type="text/javascript" src="_pub_plugin/jqueryui/jquery-ui.min.js"></script>

    <script type='text/javascript' src='_pub_js/cufon-yui.js'></script> <!-- Cufon font replacement -->
	<script type='text/javascript' src='_pub_js/ColaborateLight_400.font.js'></script> <!-- the Colaborate Light font -->
	<script type='text/javascript' src='_pub_js/easyTooltip.js'></script> <!-- element tooltips -->
	<script type='text/javascript' src='_pub_js/jquery.tablesorter.min.js'></script> <!-- tablesorter -->

	<!--[if IE 8]>
		<script type='text/javascript' src='_pub_js/excanvas.js'></script>
		<link rel="stylesheet" href="_pub_css/IEfix.css" type="text/css" media="screen" />
	<![endif]--> 
 
	<!--[if IE 7]>
		<script type='text/javascript' src='_pub_js/excanvas.js'></script>
		<link rel="stylesheet" href="_pub_css/IEfix.css" type="text/css" media="screen" />
	<![endif]--> 
	
	<script type='text/javascript' src='_pub_js/visualize.jQuery.js'></script> <!-- visualize plugin for graphs / statistics -->
    <script type='text/javascript' src='_pub_js/iphone-style-checkboxes.js'></script> <!-- iphone like checkboxes -->

    <script type='text/javascript' src='_pub_js/iphone-style-checkboxes.js'></script> <!-- cookie -->
    <!--<script type='text/javascript' src='_pub_js/jquery.cleditor.min.js'></script>  wysiwyg editor -->

    <script type='text/javascript' src='_pub_js/jquery.cookie.js'></script> <!-- the "make them work" script -->

    <link href="_pub_plugin/toastr/toastr.min.css" media="screen" rel="stylesheet" type="text/css"/>
    <script src="_pub_plugin/toastr/toastr.min.js" type="text/javascript"></script>

    <script type='text/javascript' src='_pub_custom.js?v={:time()}'></script> <!-- the "make them work" script -->
    <script src='_pub_plugin/pagination/pagination.js'></script> <!-- page -->

    <script type="text/javascript" src="_pub_plugin/ztree/js/jquery.ztree.all.js"></script></head>

<body>
	<div id="container">
		<div id="bgwrap">
			<div id="primary_left">
                <style>
                    #logo div {
                        text-align: center;
                        width: 210px;
                    }
                    #logo div a {
                        padding: 3px;
                        color: #ffffff;
                        font-weight: bold;
                        text-decoration: none;
                    }
                </style>
				<div id="logo">
					<a href="#" title="Dashboard"><img src="_pub_img/logo.png" alt="" /></a>
                    <div>
                        <a href="#">admin</a>|
                        <a href="#">登出</a>|
                        <a target="_blank" href="/">论坛</a>
                    </div>
				</div> <!-- logo end -->
                <style>

                </style>
				<div id="menu"> <!-- navigation menu -->
					<ul>
                        <?php $menus = \model\Menu::nav();?>
                        <volist name="menus" id="v">
                            <li {:$v.isCurrent?'class="current"':''}>
                                <a pjax-content href="#" class="dashboard">
                                    <img src="{$v.icon32}" alt="" />
                                    <span>{$v.name}</span>
                                </a>
                                <if condition="$v.childs">
                                    <div style="clear: both;height: 10px;"></div>
                                    <div class="recomend-list clearfix">
                                        <volist name="$v.childs" id="vv">
                                            <a pjax-content href="{$vv.link}" {:$vv.isCurrent?'class="on"':''} >{$vv.name}</a>
                                        </volist>
                                    </div>
                                </if>
                            </li>
                        </volist>
					</ul>
				</div> <!-- navigation menu end -->
			</div> <!-- sidebar end -->

			<div id="primary_right">
				<div class="inner" id="content">
                    {__CONTENT__}
				</div> <!-- inner -->
			</div> <!-- primary_right -->
		</div> <!-- bgwrap -->
	</div> <!-- container -->
    <div id="dialog" title="Modals with Hello!"></div>
    <div id="dialog-form" title="Modals with Hello!"></div>
    <div id="dialog-confirm" title="Modals with Hello!"></div>
</body>
</html>
