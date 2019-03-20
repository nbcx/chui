<h1>板块节点</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
<style>
    .node th,.node td {
        width: 300px;
    }
</style>
<p>
    <a href="presentation.html" class="button_link" >添加板块</a>
</p>
<table class="fancy node">
    <volist name="cates" id="v">
        <thead>
            <th>列表</th>
            <th><span class="pull-right">选项</span></th>
        </thead>
        <tbody>
            <tr>
                <td>{$v.cname}</td>
                <td>
                    <span class="pull-right">
                        <a pjax-content href="/admin/nodes/edit?node_id={$v.node_id}" class="btn btn-primary btn-sm" data-remote="true" id="edit_node_1">修改</a>
                        <a pjax-content href="/admin/nodes/move?node_id={$v.node_id}" class="btn btn-primary btn-sm" data-remote="true">移动</a>
                        <a pjax-content href="/admin/nodes/del?node_id={$v.node_id}" class="btn btn-sm btn-danger" data-confirm="真的要删除吗?" data-method="delete" data-remote="true" rel="nofollow">删除</a>
                    </span>
                </td>
            </tr>
            <volist name="v.child" id="vv">
                <tr>
                    <td>├─&nbsp;<a href="{$vv.url}">{$vv.cname}</a>
                    </td>
                    <td>
                        <span class="pull-right">
                            <a pjax-content href="/admin/nodes/edit?node_id={$vv.node_id}" class="btn btn-primary btn-sm" data-remote="true" id="edit_node_1">修改</a>
                            <a pjax-content href="/admin/nodes/move?node_id={$vv.node_id}" class="btn btn-primary btn-sm" data-remote="true">移动</a>
                            <a pjax-content href="/admin/nodes/del?node_id={$vv.node_id}" class="btn btn-sm btn-danger" data-confirm="真的要删除吗?" data-method="delete" data-remote="true" rel="nofollow">删除</a>
                        </span>
                    </td>
                </tr>
            </volist>
        </tbody>
    </volist>

</table>




