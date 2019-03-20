
<h1>用户组</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>

<table class="fancy">
    <thead>
    <tr>
        <th>GID</th>
        <th>组名称</th>
        <th>类型</th>
        <th>用户数</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <volist name="group_list" id="v">
            <tr>
                <td>{$v.gid}</td>
                <td>{$v.group_name}</td>
                <td>{$v.group_type}</td>
                <td>{$v.usernum}</td>
                <td>
                    <a href="#" title="Preferences" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Preferences.png" alt="" /></a>
                    <a href="#" title="Delete this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Trash.png" alt="" /></a>
                </td>
            </tr>
        </volist>
    </tbody>
</table>
<!---->
<a href="presentation.html" class="button_link" >返回用户列表</a>

<div class="clearboth"></div>
<hr />


<h1>新建用户组</h1>
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
<p class="form">
    <label>&nbsp;</label>
    <a href="presentation.html" class="button_link" >提&nbsp;交</a>
</p>



