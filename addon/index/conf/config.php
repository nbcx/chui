<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>

<input type="hidden" name="name" value="{$name}">
<fieldset>
    <p class="form">
        <label>分类名称:</label>
        <input class="sf" type="email" value="" placeholder="small input field" />
        <span class="form_hint">正确格式为：17sucai@something.com</span>
    </p>

    <p class="form">
        <label>父节点:</label>
        <select class="dropdown">
            <option>Please select an option</option>
            <option>Upload</option>
            <option>Change</option>
            <option>Remove</option>
        </select>
    </p>

    <p class="form">
        <label>分类关键字:</label>
        <input class="mf" type="text" value="" placeholder="medium input field"/>
        <span class="validate_success">A positive message!</span>
    </p>
    <p class="form">
        <label>分类简介:</label>
        <textarea class="lf" type="text" value="l" >arge input field</textarea>
        <span class="validate_error">A negative message!</span>
    </p>
    <p class="form">
        <label>节点版主: </label>
        <input class="datepicker" />
    </p>

    <hr />
    <div class="clearboth"></div>
    <p>
        <input class="button" type="submit" value="Submit" />
        <input class="button" type="reset" value="Reset" />
    </p>
</fieldset>

<script>
    jQuery(document).on('pjax:complete', function() {
        jQuery('.iphone').iphoneStyle();
        jQuery( ".datepicker" ).datepicker();
        jQuery("#theme_style_{$theme.style}").prop("checked","checked");
    });
</script>
