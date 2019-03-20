<h1>设置插件: {$plugin.info.name}</h1>
<form action="/admin/theme/config" method="post" id="plugin-config">
    {__CONTENT__}
</form>

<script>
    jQuery(document).on('pjax:complete', function() {
        jQuery('.iphone').iphoneStyle();
        jQuery( ".datepicker" ).datepicker();
    });
    $(document).on('submit', '#plugin-config', function (event) {
        jQuery.pjax.submit(event, '#theme');
    });
</script>
