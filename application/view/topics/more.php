<thead>
<tr>
    <th>选择</th>
    <th>ID</th>
    <th>节点</th>
    <th>标题</th>
    <th>作者</th>
    <th>回复</th>
    <th>时间</th>
</tr>
</thead>
<tbody>
<volist name="topics" id="v">
    <tr>
        <td><input type="checkbox" /></td>
        <td>{$v.topic_id}</td>
        <td>{$v.node.cname}</td>
        <td>{$v.title}</td>
        <td>{$v.author.username}</td>
        <td>{$v.comments}</td>
        <td>{$v.date}</td>
    </tr>
</volist>
</tbody>