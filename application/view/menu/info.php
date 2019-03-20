<legend>{$menu.name}</legend>
<input type="hidden" name="id" value="{$menu.id}">
<p class="form">
    <label>菜单名字:</label>
    <input class="sf" name="name" type="text" value="{$menu.name}" placeholder="small input field" required/>
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
    <label>链接地址:</label>
    <input class="mf"  type="text" value="{$menu.link}" disabled />
</p>
<p class="form">
    <label>菜单说明:</label>
    <textarea class="lf" name="descr" type="text" >{$menu.descr}</textarea>
</p>

<p class="form">
    <label>来源:</label>
    <input class="sf"  type="text" value="{$menu.origin}" disabled />
</p>
<p>
    <label>是否加入快速菜单：</label>
    <input type="radio" name="quick" value="2" />是的
    <input type="radio" name="quick" value="2" />不用了
</p>
<hr />
<p style="text-align: center;margin-bottom: 12px;">
    <input class="button" type="submit" value="提  交" />
</p>

<table class="fancy fullwidth">
    <thead>
    <tr>
        <th>名称</th>
        <th>链接地址</th>
        <th>来源</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <tr class="odd">
        <td>Access</td>
        <td>Typecho Access Plugin</td>
        <td>Kokororin</td>
        <td><a data-confirm href="/admin/plugin/test">编辑</a></td>
    </tr>
    <tr>
        <td>SwitchTheme</td>
        <td>主题自动根据设备切换</td>
        <td>loftor</td>
        <td><a data-confirm href="/admin/plugin/test">编辑</a></td>
    </tr>
    <tr class="odd">
        <td>Tools</td>
        <td>简书主题配套工具</td>
        <td>绛木子</td>
        <td>
            <a data-confirm href="/admin/plugin/test">编辑</a>
        </td>
    </tr>
    </tbody>
</table>
