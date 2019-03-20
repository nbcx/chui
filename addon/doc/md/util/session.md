# Session

可以直接使用nb\Session类操作Session。

## Session初始化

Session会在第一次调用Session类的时候按照框架配置里的session字段的值自动初始化，
默认配置如下：

```$php
class Configure extends nb\Config {

    public $session = [
        'driver'=>'',
        'id'             => '',
        'var_session_id' => '',// SESSION_ID的提交变量,解决flash上传跨域
        'prefix'         => 'nb_',// SESSION 前缀
        'type'           => '',// 驱动方式 支持redis memcache memcached
        'auto_start'     => true,// 是否自动开启 SESSION
    ]; 
}
```
如果我们使用上述的session配置参数的话，无需任何操作就可以直接调用Session类的相关方法，例如：
```$php
Session::set('name','thinkphp');
Session::get('name');
```
或者调用init方法进行初始化：
```$php
Session::init([
    'prefix'         => 'module',
    'type'           => '',
    'auto_start'     => true,
]);
```

>如果你没有使用Session类进行Session操作的话，例如直接操作$_SESSION,必须使用上面的方式手动初始化或者直接调用session_start()方法进行session初始化。

## 基础用法

#### 赋值
```$php
// 赋值（当前作用域）
Session::set('name','nb framework');
// 赋值nb作用域
Session::set('name','nb framework','nb');
```

#### 判断是否存在
```$php
// 判断（当前作用域）是否赋值
Session::has('name');
// 判断think作用域下面是否赋值
Session::has('name','nb');
```

#### 取值
```$php
// 取值（当前作用域）
Session::get('name');
// 取值nb作用域
Session::get('name','nb');
```
如果name的值不存在，返回null。

#### 删除
```$php
// 删除（当前作用域）
Session::delete('name');
// 删除nb作用域下面的值
Session::delete('name','nb');
```

#### 取值并删除
```$php
// 取值并删除
Session::pull('name');
```
如果name的值不存在，返回Null。

#### 清空
```$php
// 清除session（当前作用域）
Session::clear();
// 清除nb作用域
Session::clear('nb');
```

#### 闪存数据，下次请求之前有效
```$php
// 设置session 并且在下一次请求之前有效
Session::flash('name','value');
```

#### 提前清除当前请求有效的数据
```$php
// 清除当前请求有效的session
Session::flush();
```

## 二级数组
支持session的二维数组操作，例如：
```$php
// 赋值（当前作用域）
Session::set('name.item','thinkphp');
// 判断（当前作用域）是否赋值
Session::has('name.item');
// 取值（当前作用域）
Session::get('name.item');
// 删除（当前作用域）
Session::delete('name.item');
```

## Session驱动
目前内置支持使用redis、memcache和memcached作为session驱动类型。

当设置redis为Session驱动时：
```$php
class Configure extends nb\Config {

    public $session = [
        'driver'=>'redis',
        'prefix'     => 'module',
        'auto_start' => true,
        'host'       => '127.0.0.1',// redis主机
        'port'       => 6379,// redis端口
        'password'   => '',// 密码
    ]; 
}
```

当设置memcache为Session驱动时：
```$php
class Configure extends nb\Config {

    public $session = [
        'type'=>'redis',
        'prefix'     => 'module',
        'auto_start' => true,
        'host'       => '127.0.0.1',// redis主机
        'port'       => 6379,// redis端口
        'password'   => '',// 密码
    ]; 
}
```