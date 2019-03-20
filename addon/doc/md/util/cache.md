# 缓存

NB的缓存驱动是nb\driver\Cache类,是一个key-value形式的数据操作类。存储媒介可以为 file、memcache、sqlite 和redis。


## 设置
全局的缓存配置直接修改框架配置的cache属性。

公共的缓存配置参数包含：

|参数名|描述|
|:-----  |-----  |
|driver  |缓存驱动类名|
|expire  |缓存有效期（秒）|

## 使用
不同的缓存驱动还需要配置额外的缓存参数。下面先介绍`nb\driver\Cache`支持的函数，最后介绍不同缓存的详细配置；

#### 设置缓存

1.常规写入缓存
```
/**
 * 写入缓存
 * @param string $name 缓存变量名
 * @param null $value 缓存变量值
 * @param mixed $expire 有效时间
 * @return bool|void
 * @throws \Exception
 */
public static function set($name, $value, $expire = null)


//将内容world缓存12秒，键为hello
Cache::set('hello','world', 12);

//如果$expire为null，将读取配置里的过期时间
Cache::set('hello','world');

```

2.高级写入缓存的$value不支持基本类型的数据，支持function，类函数等
```
/**
 * 高级写入缓存
 *
 * @param $name 缓存变量名
 * @param $expire 有效时间
 * @param null $value 存储数据
 * @return mixed 写入成功后会返回写入的值
 */
public static function setx($name, $expire, $value = null)

//通过回调函数的返回值来设置缓存数据
Cache::setx('yes',function (){
    return 'yes';
});

//通过一个类静态方法来设置缓存数据
Cache::setx('yes','cache\Test::yes');

//通过一个类静态方法来设置缓存数据,并传递参数
Cache::setx('yes',[
    'cache\Test::yes',
    'world'
]);

//通过一个类方法来设置缓存数据,并传递参数
Cache::setx('yes',[
    [$class,'yes'],
    'world'
]);
```

#### 读取缓存

1.常规读取缓存
```
/**
 * 读取缓存数据
 *
 * @param $key
 * @param null $default 默认值
 * @return mixed
 */
public static function get($key, $default = null)

//读取yes的缓存数据，不存在返回null
Cache::get('hello');

//读取yes的缓存数据，如果yes不存在，将返回默认值'world'，且该值会写入yes的缓存
Cache::get('hello','world');
```

2.高级读取缓存，和高级写入缓存用法差不多，唯一区别是只有缓存不存在时才执行value
```
/**
 * 高级读取缓存
 *
 * @param $name
 * @param $expire 过期时间，也可以是存储数据或回调函数，此时过期时间使用配置时间
 * @param null $value 存储数据，省略此参数，将以$expire代替
 * @return mixed
 */
public static function getx($name,$expire,$value=null)

//读取yes的缓存数据，不存在则执行回调函数的返回值来设置缓存数据
Cache::getx('hello',function (){
    return 'world';
});

//读取yes的缓存数据，如果yes不存在，将返回默认值'world'，且该值会写入yes的缓存
Cache::getx('yes','world');
```
#### 自增缓存
#### 自减缓存

#### 判断缓存是否存在

#### 修改缓存


#### 删除缓存
