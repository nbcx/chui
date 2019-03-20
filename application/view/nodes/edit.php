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
            <label>分类名称:</label>
            <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
            <span class="form_hint">正确格式为：17sucai@something.com</span>
        </p>

        <p class="form">
            <label>父节点:</label>
            <select name="dropdown" class="dropdown">
                <option>Please select an option</option>
                <option>Upload</option>
                <option>Change</option>
                <option>Remove</option>
            </select>
        </p>

        <p class="form">
            <label>分类关键字:</label>
            <input class="mf" name="site_name" type="text" value="" placeholder="medium input field"/>
            <span class="validate_success">A positive message!</span>
        </p>
        <p class="form">
            <label>分类简介:</label>
            <textarea class="lf" name="welcome_tip" type="text" value="l" >arge input field</textarea>
            <span class="validate_error">A negative message!</span>
        </p>
        <p class="form">
            <label>节点版主: </label>
            <input name="datepicker" class="datepicker" />
        </p>
        <p>
            <label>节点ICON：</label>
            <input type="radio" />开启
            <input type="radio" />关闭
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
        <p class="form">
            <label>关键字：</label>
            <span class="tags" id="tags" tabindex="1">
                <input id="form-field-tags" type="text" placeholder="请输入标签 ..." value="Tag Input Control" name="tags" style="display: none;"/>
                <input type="text" placeholder="请输入标签 ..." class="tags_enter" autocomplete="off"/>
            </span>

        </p>
        <hr />
        <div class="clearboth"></div>
        <p>
            <input class="button" type="submit" value="Submit" />
            <input class="button" type="reset" value="Reset" />
        </p>
    </fieldset>
</form>

<style>
    .tags {
        /**/
        background-color: #fff;
        border: 1px solid #d5d5d5;
        width: 406px;

        color: #777;
        padding: 4px 6px;

        margin:40px auto;
    }
    .tags:hover {
        border-color: #f59942;
        outline: 0 none;
    }
    .tags[class*="span"] {
        float: none;
        margin-left: 0;
    }
    .tags input[type="text"], .tags input[type="text"]:focus {
        border: 0 none;
        box-shadow: none;
        display: inline;
        line-height: 22px;
        margin: 0;
        outline: 0 none;
        padding: 4px 6px;
    }
    .tags .tag {
        background-color: #91b8d0;
        color: #fff;
        display: inline-block;
        font-size: 12px;
        font-weight: normal;
        margin-bottom: 3px;
        margin-right: 3px;
        padding: 4px 22px 5px 9px;
        position: relative;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease 0s;
        vertical-align: baseline;
        white-space: nowrap;
    }
    .tags .tag .close {
        bottom: 0;
        color: #fff;
        float: none;
        font-size: 12px;
        line-height: 20px;
        opacity: 1;
        position: absolute;
        right: 0;
        text-align: center;
        text-shadow: none;
        top: 0;
        width: 18px;
    }
    .tags .tag .close:hover {
        background-color: rgba(0, 0, 0, 0.2);
    }
    .close {
        color: #000;
        float: right;
        font-size: 21px;
        font-weight: bold;
        line-height: 1;
        opacity: 0.2;
        text-shadow: 0 1px 0 #fff;
    }
    .close:hover, .close:focus {
        color: #000;
        cursor: pointer;
        opacity: 0.5;
        text-decoration: none;
    }
    button.close {
        background: transparent none repeat scroll 0 0;
        border: 0 none;
        cursor: pointer;
        padding: 0;
    }
    .tags .tag-warning {
        background-color: #ffb752;
    }
</style>
<script>
    jQuery(document).on('pjax:complete', function() {
        jQuery('.iphone').iphoneStyle();
        jQuery( ".datepicker" ).datepicker();
    });
    jQuery(document).on('submit', '#basic', function (event) {
        jQuery.pjax.submit(event, '#content');
    });
    jQuery(function() {
        jQuery(".tags_enter").blur(function() { //焦点失去触发
            var txtvalue=jQuery(this).val().trim();
            if(txtvalue!=''){
                addTag(jQuery(this));
                jQuery(this).parents(".tags").css({"border-color": "#d5d5d5"})
            }
        }).keydown(function(event) {
            var key_code = event.keyCode;
            var txtvalue=jQuery(this).val().trim();
            if (key_code == 13&& txtvalue != '') { //enter
                addTag(jQuery(this));
            }
            if (key_code == 32 && txtvalue!='') { //space
                addTag(jQuery(this));
            }
        });
        jQuery(".close").live("click", function() {
            jQuery(this).parent(".tag").remove();
        });
        jQuery(".tags").click(function() {
            jQuery(this).css({"border-color": "#f59942"})
        }).blur(function() {
            jQuery(this).css({"border-color": "#d5d5d5"})
        })
    })
    function addTag(obj) {
        var tag = obj.val();
        if (tag != '') {
            var i = 0;
            jQuery(".tag").each(function() {
                if (jQuery(this).text() == tag + "×") {
                    jQuery(this).addClass("tag-warning");
                    setTimeout("removeWarning()", 400);
                    i++;
                }
            })
            obj.val('');
            if (i > 0) { //说明有重复
                return false;
            }
            jQuery("#form-field-tags").before("<span class='tag'>" + tag + "<button class='close' type='button'>×</button></span>"); //添加标签
        }
    }
    function removeWarning() {
        jQuery(".tag-warning").removeClass("tag-warning");
    }
</script>