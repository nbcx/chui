<h1>话题设定</h1>

<div class="notification info">
    <span></span>
    <div class="text">
        <p><strong>Info!</strong> This is a info notification. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla varius eros et risus suscipit vehicula.</p>
    </div>
</div>

<form action="/admin/settings/basic" method="post" id="basic">
    <fieldset>
        <legend>A fieldset with all form elements in it!</legend>

        <p>
            <label>回复列表顺序：</label>
            <input type="radio" />正序
            <input type="radio" />倒序
        </p>
        <p>
            <label>话题(回复)审核开关：</label>
            <input type="radio" />需审核
            <input type="radio" />无需审核
        </p>
        <p class="form">
            <label>首页列表条数:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>列表每夜条数:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>发帖时间间隔:</label>
            <input class="af" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>发帖字数限制:</label>
            <input class="af" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <hr />
        <p>
            <label>过滤关键字：</label>
            <input type="checkbox" />开启
            <input type="checkbox" />关闭
        </p>
        <p class="form">
            <label>关键字：</label>
            <textarea class="lf" name="welcome_tip" type="text" placeholder="以英文逗号(,)分割" ></textarea>
        </p>
        <hr />
        <div class="clearboth"></div>
        <p class="place">
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