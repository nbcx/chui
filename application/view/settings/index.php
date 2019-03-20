<h1>网站设置</h1>

<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
<div class="clearboth"></div>

<div class="tabs" style="width:870px;">
    <div class="ui-widget-header">
        <span>Website Statistics</span>
        <ul>
            <li><a href="#tabs-1">网站设定</a></li>
            <li><a href="#tabs-2">邮件设定</a></li>
            <li><a href="#tabs-3">自定义地址</a></li>
        </ul>
    </div>

    <div id="tabs-1">
        <form action="/admin/settings/basic" method="post" id="basic">
            <include file="settings/site" />
        </form>
    </div> <!-- end of first tab -->
    <div id="tabs-2">
        <include file="settings/mail" />
    </div>

    <div id="tabs-3">

        <include file="settings/route" />

        <div class="clearboth"></div>
    </div>
</div>
<div class="clearboth"></div>

<hr />

<script>
    $(document).ready(function() {
        //jQuery('.iphone').iphoneStyle();
        //jQuery( ".datepicker" ).datepicker();
        jQuery(".tabs").tabs();
    });
    jQuery(document).on('submit', '#basic', function (event) {
        jQuery.pjax.submit(event, '#content');
    });
</script>

