<h1>网站设定2</h1>

<div class="notification info">
    <span></span>
    <div class="text">
        <p><strong>Info!</strong> This is a info notification. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla varius eros et risus suscipit vehicula.</p>
    </div>
</div>

<form action="/admin/settings/basic" method="post" id="basic">
    <fieldset>
        <legend>A fieldset with all form elements in it!</legend>

        <!--<h2>Inputs &amp; Datepicker</h2>-->
        <p>
            <label>Small field:</label>
            <input class="sf" name="sf" type="text" value="small input field" />
            <span class="field_desc">Field description</span>
        </p>

        <p>
            <label>网站名称:</label>
            <input class="mf" name="site_name" type="text" value="medium input field" />
            <span class="validate_success">A positive message!</span>
        </p>

        <p>
            <label>欢迎信息:</label>
            <input class="lf" name="welcome_tip" type="text" value="large input field" />
            <span class="validate_error">A negative message!</span>
        </p>

        <p>
            <label>DropDown:</label>
            <select name="dropdown" class="dropdown">
                <option>Please select an option</option>
                <option>Upload</option>
                <option>Change</option>
                <option>Remove</option>
            </select>
        </p>

        <p>
            <label>Datepicker: </label>
            <input name="datepicker" class="datepicker" />
        </p>

        <p>
            <label>开通网站：</label>
            <input type="radio" />开启
            <input type="radio" />关闭
        </p>

        <p>
            <label>开通网站：</label>
            <input type="checkbox" />Lorem Ipsum
            <input type="checkbox" />Lorem Ipsum
        </p>
        <hr />

        <div class="clearboth"></div>
        <p>
            <input class="button" type="submit" value="Submit" />
            <input class="button" type="reset" value="Reset" />
        </p>
    </fieldset>
</form>
<script>
    jQuery(document).on('pjax:complete', function() {
        jQuery('.iphone').iphoneStyle();
        jQuery( ".datepicker" ).datepicker();
    });
    jQuery(document).on('submit', '#basic', function (event) {
        jQuery.pjax.submit(event, '#content');
    });
</script>