# 内置命令
NB内置了两个很有用的命令。

## 在命令行执行控制器
有时我们需要用脚本处理一些任务，但是我们可能并不想写一个命令类。
那么我们通过内置的`action`命令，像编写控制器一样编写我们的业务脚本，然后就像通过地址访问一样执行它们。当然，你需要注意处理控制台不支持的如Session，Cookie等数据。
```bash
$php nb action [/模块][/文件夹]/控制器[/动作(即方法)] [参数][...] [--选项][...]
```
`Action`后面三部分，分别为路由，控制器方法的参数，和选项。
```php
namespace controller;

class Cli {

    public function index(){
       ex('console:cli/index');
    }

    //带参数
    public function args($a,$b=12) {
        ex('console:cli/args;args:a='.$a.',b='.$b);
    }
}
```
我们现在通过控制台来执行上面的index方法:
```bash
$php nb action cli
#将输出
console:cli/index
```
我们也可以调用带参数的控制器：
```bash
$php nb action cli/args hello
#将输出
console:cli/args;args:a=hello,b=12
```

如果的的网站同时支持WEB访问和控制台运行，为了安全，你可能想要限制一些控制器只能通过控制台处理。
如此，你需要在框架配置里进行设置：
```php
public $folder_console = 'console';
```
上面的设置是给命令行控制器单独定义一个文件夹，来区别默认控制器文件夹。
就像下面的目录结构：
```
/home/www/demo/                      项目根目录
├─application                        应用目录
│  ├─controller                      控制器目录
│  ├─...                         
│  ├─console                         只能在命令行里运行的控制器目录
│  └─view                            视图目录
├─tmp                                缓存目录
├─config.inc.php                     框架配置目录
├─LICENSE.txt                        授权说明文件
├─README.md                          README 文件
└─server                             服务器运行命令
```



## 启动服务器
这里请看Swoole章节的详细介绍。