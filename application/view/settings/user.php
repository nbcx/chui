<h1>网站设定</h1>

<div class="notification info">
    <span></span>
    <div class="text">
        <p><strong>Info!</strong> This is a info notification. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla varius eros et risus suscipit vehicula.</p>
    </div>
</div>

<form action="/admin/settings/basic" method="post" id="basic">
    <fieldset>
        <legend>A fieldset with all form elements in it!</legend>

        <p class="form">
            <label>注册初始奖励:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>登陆奖励:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>发帖奖励:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>回复奖励:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>被回复奖励:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>删除扣除奖励:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>被关注奖励:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <hr />

        <p class="form">
            <label>禁注册的用户名:</label>
            <textarea class="lf" name="welcome_tip" type="text" placeholder="以英文逗号(,)分割" ></textarea>
        <div style="margin-left: 120px">A negative message!</div>
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