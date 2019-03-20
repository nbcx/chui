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

</tbody>