
<h1>会员列表</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
<p>
    <a pjax-content href="/admin/users/add" class="button_link" >新建用户</a>
    <a pjax-content href="/admin/group/index" class="button_link" >用户组</a>
</p>

<table class="normal tablesorter fullwidth" id="more">
    <thead>
    <tr>
        <th>选择</th>
        <th>ID</th>
        <th>头像</th>
        <th>昵称</th>
        <th>角色</th>
        <th>积分</th>
        <!--<th>等级</th>-->
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <volist name="users" id="v">
        <tr>
            <td><input type="checkbox" class="iphone" /></td>
            <td>{$v.uid}</td>
            <td><img src="{$v.avatar}" alt="" /></td>
            <td>{$v.username}</td>
            <td>{$v.group_type}</td>
            <td>{$v.credit}</td>
            <td>
                <a pjax-content href="#" title="Edit this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Pencil.png" alt="" /></a>
                <a pjax-content href="#" title="Preferences" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Preferences.png" alt="" /></a>
                <a pjax-content href="#" title="Delete this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Trash.png" alt="" /></a>
            </td>
        </tr>
    </volist>

    <tr>
        <td><input type="checkbox" checked="checked" class="iphone" /></td>
        <td>107</td>
        <td><img src="_pub_img/avatar.png" alt="" /></td>
        <td>Johnatan Doe</td>
        <td>1 month membership</td>
        <td>09-12-2011</td>
        <td>
            <a href="#" title="Edit this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Pencil.png" alt="" /></a>
            <a href="#" title="Preferences" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Preferences.png" alt="" /></a>
            <a href="#" title="Delete this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Trash.png" alt="" /></a>
        </td>
    </tr>
    <tr class="odd">
        <td><input type="checkbox" class="iphone" /></td>
        <td>34</td>
        <td><img src="_pub_img/avatar.png" alt="" /></td>
        <td>Johnatan Doe</td>
        <td>9 months membership</td>
        <td>09-12-2011</td>
        <td>
            <a href="#" title="Edit this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Pencil.png" alt="" /></a>
            <a href="#" title="Preferences" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Preferences.png" alt="" /></a>
            <a href="#" title="Delete this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Trash.png" alt="" /></a>
        </td>
    </tr>
    <tr>
        <td><input type="checkbox" class="iphone" /></td>
        <td>48</td>
        <td><img src="_pub_img/avatar.png" alt="" /></td>
        <td>Johnatan Doe</td>
        <td>3 months membership</td>
        <td>09-12-2011</td>
        <td>
            <a href="#" title="Edit this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Pencil.png" alt="" /></a>
            <a href="#" title="Preferences" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Preferences.png" alt="" /></a>
            <a href="#" title="Delete this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Trash.png" alt="" /></a>
        </td>
    </tr>
    </tbody>
</table>

<!--page -->
<a href="presentation.html" class="button_link" >小黑屋</a>
<div id='page' class="page"></div>
<script>
    jQuery(function(){
        page({
            box:'page',//存放分页的容器
            count:20,//总页数
            num:9,//页面展示的页码个数
            step:6,//每次更新页码个数
            callBack:function(i){
                console.log(i);
                //点击页码的按钮发生回调函数一般都是操作ajax
                jQuery.pjax({ url: '/admin/users/more', container: '#more' });
            },
            pre:function(p){
                alert(p)
            },
            next:function(p){
                alert(p)
            }
        })
    });
    jQuery(document).pjax('[more-pjax] a, a[more-pjax]', '#more');
</script>
<!--page -->
<hr />


<h1>搜索刷选</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>

<p>
    <label>邮件发送方式：</label>
    <input type="radio" />Smtp方式
    <input type="radio" />Mail方式
</p>
<p class="form">
    <label>SMTP服务器:</label>
    <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
    <span class="form_hint">正确格式为：17sucai@something.com</span>
</p>
<p class="form">
    <label>SMTP端口:</label>
    <input class="af" name="sf" type="email" value="" placeholder="small input field" required/>
    <span class="form_hint">正确格式为：17sucai@something.com</span>
</p>
<p class="form">
    <label>发信人地址:</label>
    <input class="lf" name="sf" type="email" value="" placeholder="small input field" required/>
    <span class="form_hint">正确格式为：17sucai@something.com</span>
</p>
<p class="form">
    <label>邮箱密码:</label>
    <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
    <span class="form_hint">正确格式为：17sucai@something.com</span>
</p>

<hr />

<a href="presentation.html" class="button_link" >search</a>