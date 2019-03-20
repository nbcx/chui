# Cookie

NB Framework采用nb\Cookie类提供Cookie支持。

### 配置
Cookie的设置在框架配置里的cookie字段，无需手动初始化，系统会自动在调用之前进行Cookie初始化工作。
如下为框架默认配置
```$php
class Configure extends nb\Config {

    public $cookie = [
        'driver'=>'',
        'prefix'    => '',// cookie 名称前缀
        'expire'    => 0,// cookie 保存时间
        'path'      => '/',// cookie 保存路径
        'domain'    => '',// cookie 有效域名
        'secure'    => false,//  cookie 启用安全传输
        'httponly'  => '',// httponly设置
        'setcookie' => true,// 是否使用 setcookie
    ]; 
}

```

### 设置

```$php
// 设置Cookie 有效期为 3600秒
Cookie::set('name','value',3600);
// 设置cookie 前缀为think_
Cookie::set('name','value',['prefix'=>'nb_','expire'=>3600]);
// 支持数组
Cookie::set('name',[1,2,3]);
```

### 判断
```$php
Cookie::has('name');
// 判断指定前缀的cookie值是否存在
Cookie::has('name','nb_');
```

### 获取
```$php
Cookie::get('name');
// 获取指定前缀的cookie值
Cookie::get('name','nb_');
```

### 删除
```$php
//删除cookie
Cookie::delete('name');
// 删除指定前缀的cookie
Cookie::delete('name','nb_');
```

### 清空
```$php
// 清空指定前缀的cookie
Cookie::clear('nb_');
```