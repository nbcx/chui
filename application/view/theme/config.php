<form action="/admin/theme/config" method="post" id="theme-config">
    <input type="hidden" name="name" value="{$name}">
    <fieldset>
        {__CONTENT__}
    </fieldset>
</form>

<script>
    $(document).on('submit', '#theme-config', function (event) {
        jQuery.pjax.submit(event, '#theme');
    });
</script>
