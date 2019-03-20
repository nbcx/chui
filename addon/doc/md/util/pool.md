# 容器和依赖注入

为了比较好的兼容Swoole运行模式，NB实现了一个简单而强大的对象池，也就是容器，由`nb\Pool`类完成。
框架的底层很多地方都用到了容器，你也可以把它用于自己的业务代码里。

容器使对象只需要一次构建，就可以到处使用，让你把对象的组织方式中心化。它节省你的时间，提升至强力架构，而且巨快！

## 注入和获取容器里的对象
在`nb\Pool`类里，有多种像容器里注入变量的函数，每一个函数在注入成功后，都会返回注入的对象。所以，注入和获取通常都是同时进行的。

我们随意的编写一个简单的测试用类：
```php
namespace utils;

use nb\Access;

class Ace extends Access {

    public function __construct($name=null) {
        $this->name = $name?:'ace';
    }
}
```

#### 最简单注入
本列在`demo\application\controller\Pool.php`文件里。
```php
$ace = \nb\Pool::set('hello','world');
ex($ace);

$ace = \nb\Pool::set('hello',new Ace());
ex($ace->name);

//通过get
$ace = \nb\Pool::get('hello');
ex($ace->name);
```
用`set`函数注入，后来的设置的变量会替换之前注入的变量。

#### 高级对象注入
高级对象注入通过`object`函数，它可以只接收一个类名(带命名空间)自动构建实例对象。
如下：
```
//两种方式效果是等同的
$ace = \nb\Pool::object(\utils\Ace::class);
$ace = \nb\Pool::object('\utils\Ace');
ex($ace->name);

//通过get
$ace = \nb\Pool::get('\utils\Ace');
ex($ace->name);
```

如果构建的对象需要参数，我们可以这样：
```php
$ace = \nb\Pool::object('\utils\Ace',['Ace Name!']);
```
如果你觉的`\utils\Ace`不好记忆和书写，可以给它定义一个别名。
```php
$ace = \nb\Pool::object('ace','\utils\Ace',['Ace Name!']);
$ace = \nb\Pool::object('ace','\utils\Ace');

//通过get
$ace = \nb\Pool::get('ace');
ex($ace->name);
```
> 通常推荐尽量使用完整命名空间作为获取标识，这样不容易造成覆盖

同`set`注入有一个很大的区别需要注意，如果注入的对象已经存在，将直接返回已经存在的对象变量，不会重新构建实例。
这个特性可以很方便的进行一些需要延迟生效的处理，也就是懒加载。


#### 高级变量注入
高级变量注入允许通过回调函数来进行设置，它的使用和特性和高级对象注入差不多，一旦注入，之后的注入将无效。
函数注入支持参数和不带参数两种方式：
```php
$ace = \nb\Pool::value('ace',function () {
    return new Ace();
});
//带参数的
$name = 'value';
$ace = \nb\Pool::value('ace',function () use ($name) {
    return new Ace($name);
});
e($ace->name);
```
如果你的回调函数是一个变量，你也可以这样传值：
```php
$func = function ($name,$age){
    e($age);
    return new Ace($name);
};
$name = 'value';
$age = 12;
$ace = \nb\Pool::value('ace',$name,$age,$func);
e($ace->name);
```
这种形式的判断条件是参数大于3个，将除第一个和最后一个参数外的所有参数传给回调函数。

除了上面两种形式外，`value`也可以像`set`一样使用，唯一的区别就是已经注入的变量不会被新变量代替。
```php
$ace = \nb\Pool::value('hello',['world']);
ex($ace);

$ace = \nb\Pool::value('hello',new Ace());
ex($ace->name);
```

## 删除和清空对象池
删除清空什么的很简单，就直接看看代码就可以了
```php
//删除指定变量
\nb\Pool::rm('ace');
//清空对象池
\nb\Pool::destroy();
```

## 固化
固化是为Swoole运行模式下提供的一组API。在常规的PHP运行模式中，每次请求结束后，PHP进程会自动销毁所有变量，这可以简化开发难度，但是会带来很大的性能损耗。

NB的Swoole Http Server模式下，为了模拟常规PHP运行方式，会在每次请求结束时，调用`\nb\Pool`的`destroy`方法来清空容器。
对于掌握了常驻内存开发方式的PHPer，可以使用`\nb\Pool`提供的一些方法，来保持容器里指定的一些变量，在调用`destroy`方法时，不被销毁，来达到性能提升。这一过程，我们称之为`固化`。











