#Newbbs


命名空间，小写
文件夹小写

类文件名与类保持一致

命名规范

变量名 下划线
方法名 下划线

函数名 驼峰
类名 首字母大写的驼峰


目前难点：
插件主题   80%
菜单       开发中 80%
附件上传   待进行  40%
安全处理    待开发 开始处理安全问题了

PS:
每一个控制器方法应该都支持自定义模版页

2018-6-24 开发整理
1）完善JS导航高亮
2）开发更好的JS分页插件
3）完善发帖和回帖
总结：尽量在两周类完成前台所有问题，使其可以上线



提交的方式

```
<a dialog-confirm href="/admin/plugin/test">编辑</a>
<a dialog-form href="/admin/plugin/test">编辑</a>
<a dialog href="/admin/plugin/test">编辑</a>

<a ajax>编辑</a>
<a ajax-form>编辑</a>


<a pjax-content href="/admin/plugin/test">编辑</a>
```

# 错误码
```
0    成功
1000 业务逻辑错误
2000 参数缺失
3000 权限错误
5000 服务器错误
```

# 仅对WEB使用的状态码，方便Ajax做统一处理
status  0  other
msg  错误信息
url  跳转地址，空为返回上一页 




#Apache

```
<VirtualHost chui.ol.cx:80>
  DocumentRoot "D:\www\chui\public"
  Alias /themes/ D:/www/chui/themes/
  Alias /public/ D:/www/chui/public/
  Alias /uploads/ D:/www/chui/uploads/
  <Directory "D:\www\chui\public">
    Options Indexes FollowSymLinks ExecCGI
    AllowOverride All
    Order allow,deny
    Allow from all
    Require all granted
  </Directory>
</VirtualHost>
```

# TODO

* 登录
* 注册
* 发帖(附件，话题，分类)
* 修改自己的帖子
* 删除自己的帖子
* 
