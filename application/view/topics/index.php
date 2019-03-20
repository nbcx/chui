<h1>话题列表</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
<p>
    <a href="presentation.html" class="button_link" >批量删除</a>
    <a href="presentation.html" class="button_link" >批量审核</a>
</p>
<table class="fancy fullwidth" id="more">
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
</table>
<!--page -->
<a href="presentation.html" class="button_link" >回收站</a>
<div id='page' class="page"></div>

<script>
    page({
        box:'page',//存放分页的容器
        count:20,//总页数
        num:9,//页面展示的页码个数
        step:6,//每次更新页码个数
        callBack:function(i){
            console.log(i);
            //点击页码的按钮发生回调函数一般都是操作ajax
            jQuery.pjax({ url: '/admin/topics/more?page='+i, container: '#more' });
        },
        pre:function(p){
            alert(p)
        },
        next:function(p){
            alert(p)
        }
    })
</script>
<!--page -->
<hr />
<h1>搜索筛选</h1>
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



