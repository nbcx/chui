<p>
    <label>样式：</label>
    <input id="theme_style_compactly" type="radio" name="style" value="compactly"/>紧凑
    <input id="theme_style_economic" type="radio" name="style" value="economic"/>宽松
</p>

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
<p>
    <label>浏览权限：</label>
    <input type="checkbox" />版主
    <input type="checkbox" />会员
    <input type="checkbox" />游客
</p>
<p>
    <label>发帖权限：</label>
    <input type="checkbox" />版主
    <input type="checkbox" />会员
    <input type="checkbox" />游客
</p>
<p>
    <label>评论权限：</label>
    <input type="checkbox" />版主
    <input type="checkbox" />会员
    <input type="checkbox" />游客
</p>
<hr />
<div class="clearboth"></div>
<p>
    <input class="button" type="submit" value="Submit" />
    <input class="button" type="reset" value="Reset" />
</p>
<script>
    jQuery(document).on('pjax:complete', function() {
        jQuery('.iphone').iphoneStyle();
        jQuery( ".datepicker" ).datepicker();
        jQuery("#theme_style_{$theme.style}").prop("checked","checked");
    });
</script>
