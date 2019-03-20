# 组件与驱动代理
在NB里，通过组件来提供统一的功能接口，组件根据配置自动加载对应的驱动，并代理这些驱动。最直观的表现就是，我们可以通过静态的方式使用所有驱动的功能，不用手动去实例化它们的对象，类似于门面的设计，但是进行了加强。
我们可以根据组件代理的驱动类，通过继承和配置来很方便的替换或自定义某些功能。
>本章节将通过构建一个完整的组件和驱动来讲解对应设计，代码在`demo\driver`文件夹下面。

## 定义驱动
我们模拟一个情景，比如做一个U盘，U盘存储空间有8G的，16G的和32G的。
每个U盘都应该有读和写，以及容量的功能。抽象出接口如下：
```php
interface IDriver {

    function read($index);//读取

    function save($data);//存储

    function del($index);//删除
}
```
我们并不一定需要严格的定义成接口，如果规范实现本身就很单一，我们可以定义成抽象类，这样具体实现起来就可以更好的复用相应的功能了。
现在我们将上面的接口定义成抽象类：
```php
namespace component\udisk;

abstract class Driver {

    private $data = [];

    abstract public function volume();//由子类定义U盘的容量

    public function read($index=0) {
        if(isset($this->data[$index])) {
            return $this->data[$index];
        }
    }

    public function save($data) {
        if(count($data) < $this->volume()) {
            $this->data[] = $data;
        }
    }

    public function del($index) {
        if(isset($this->data[$index])) {
            unset($this->data[$index]);
        }
    }
}
```
也可以同时存在接口和抽象类，这样扩展起来有更高的自由度，这都是有开发者自己根据需求来的。
```php
namespace component\udisk;

abstract class Driver  implements IDriver {
}
```

## 实现驱动
现在我们来实现具体的U盘，一个8G的`Base`默认U盘，加两个16G和32G的大容量U盘。
通过继承`Driver`来实现很简单：
```php
namespace component\udisk;

class Base extends Driver {

    public function volume() {
        return 8;
    }
}
```
16G的U盘：
```php
namespace component\udisk;

class Sixteen extends Driver {

    public function volume() {
        return 16;
    }
}
```
32G的实现也是很上面差不多的，`volume`返回32即可。

因为是演示，所以代码很简单，主要是看设计思想。

## 定义组件代理
我们在上面实现了三个U盘，如果我们在代码里使用这三个U盘的话，按常规的话，我们用那个U盘，就创建那个U盘的对象，然后使用。
这非常不利于管理和维护，所以我们统一它们的使用方式。这需要定义一个使用类，也就是组件了。

组件基类`nb\Component`提供了自动构建驱动对象的方法，我们只需要继承就可以通过其静态函数`driver`来获取对应的驱动对象。
```php
namespace component;

use nb\Component;

class Udisk extends Component {

    public static function config() {
        return [
            'driver'=>'base'
        ];
    }

    public static function read($index=0) {
        return self::driver()->read($index);
    }

    public static function save($data) {
        return self::driver()->save($data);
    }

    public static function del($index) {
        return self::driver()->del($index);
    }
}
```
我们定义了三个静态函数，分别对应`Driver`里的三个功能，可以让我们方便的使用。
这里需要注意的是`config`静态函数，它的返回值决定了加载什么驱动，并且它的返回值也将传给具体驱动实现类的构造函数。

这里，我们把`driver`的值设置为`base`,标示加载的是8G的U盘，如果要加载16G的，只需把其值设置为`sixteen`，也就是对应的类名即可。

> 通常`config`函数不是必须实现的，默认缺省情况下，`Component`自动从框架配置里读取与组件名对应的设置属性，如果设置属性里没有'driver'字段，将自动加载名为`Base`的驱动



TODO