# 门面代理类

门面为容器中的类提供了一个静态调用接口，相比于传统的静态方法调用， 带来了更好的可测试性和扩展性，你可以为任何的非静态类库定义一个facade类。


下面是一个示例，假如我们定义了一个app\common\Test类，里面有一个hello动态方法。

~~~
<?php
namespace app\common;

class Test
{
    public function hello($name)
    {
        return 'hello,' . $name;
    }
}
~~~

调用hello方法的代码应该类似于：
~~~
$test = new \app\common\Test;
echo $test->hello('thinkphp'); // 输出 hello，thinkphp
~~~

接下来，我们给这个类定义一个静态代理类app\facade\Test（这个类名不一定要和Test类一致，但通常为了便于管理，建议保持名称统一）。

~~~
<?php
namespace app\facade;

use nb\Facade;

class Test extends Facade {

    protected static function driver() {
    	return 'app\common\Test';
    }
}
~~~

只要这个类库继承nb\Facade，就可以使用静态方式调用动态类app\common\Test的动态方法，例如上面的代码就可以改成：
~~~
// 无需进行实例化 直接以静态方法方式调用hello
echo \app\facade\Test::hello('thinkphp');
~~~