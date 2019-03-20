<h1>{$uplugs.plugin.info.name}的用户使用列表</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
<p>
    <a pjax-content href="/admin/users/add" class="button_link" >添加使用用户</a>
</p>

<table class="normal tablesorter fullwidth" id="more">
    <thead>
    <tr>
        <th>用户ID</th>
        <th>昵称</th>
        <th>角色</th>
        <th>积分</th>
        <th>插件状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <volist name="$uplugs" id="v">
            <tr>
                <td><input type="checkbox" value="{$v.user.uid}"/> {$v.user.uid}</td>
                <td>{$v.user.username}</td>
                <td>{$v.user.group_type}</td>
                <td>{$v.user.credit}</td>
                <td><input type="checkbox" class="iphone" value="{$v.activate}" {:$v.activate?'checked="checked"':''}/></td>
                <td>
                    <a pjax-content href="#" title="Delete this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Trash.png" alt="" /></a>
                </td>
            </tr>
        </volist>
    </tbody>
</table>

<!--page -->
<div id='page' class="page"></div>
<script>
    jQuery(function(){
        jQuery('.iphone').iphoneStyle();
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
    <label>插件状态：</label>
    <input type="radio" />Smtp方式
    <input type="radio" />Mail方式
</p>
<p class="form">
    <label>SMTP服务器:</label>
    <input class="sf" name="sf" type="email" value="" placeholder="small input field" required/>
    <span class="form_hint">正确格式为：17sucai@something.com</span>
</p>
<p class="form">
    <label>发信人地址:</label>
    <input class="lf" name="sf" type="email" value="" placeholder="small input field" required/>
    <span class="form_hint">正确格式为：17sucai@something.com</span>
</p>

<hr />

<a href="presentation.html" class="button_link" >search</a>