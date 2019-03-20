# 基本使用

控制台应用程序的结构非常类似于 NB 的一个 Web 应用程序。 它由一个或多个 指令 类组成，它们在控制台环境下通常被称为“命令”。
每个命令都是一个 `nb\console\Command`接口的实现。

## 入口文件
同常规的Web应用程序一样，控制台应用也需要一个入口文件，且它们基本上差不多。我们先来看看：
```php
#!/usr/bin/env php
<?php
//项目的跟路径
define('_APP_',__DIR__);

//加载NB引导文件
include ('/home/www/nb/boot.php');

\nb\Config::register();
\nb\Console::run();
```
控制台的启动类是`\nb\Console`。

## 配置
我们可以在框架配置里添加`console`属性，来对控制台进行一些设置：
```php
public $console = [
    'name'    => 'Demo Console',
    'version' => '1.0',
    'user'    => null,
    'commands'=>[
        'common\\Test'
    ]
];
```
Console支持下面这些设置：
|名称  |说明  |
| --- |--- |
|name     | 控制台名称 |
|version  | 当前版本号 |
|commands | 指令类的完整包名 |

## 运行
我们在Demo项目的根目录编写一个名`nb`的控制台入口文件。然后打开控制台，进入到项目的根目录。
先确认你的`php`命令是否可用：
```bash
-> php -v

PHP 7.2.3 (cli) (built: Mar  8 2018 10:30:06) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
    with Zend OPcache v7.2.3, Copyright (c) 1999-2018, by Zend Technologies
```
如果你的`php`没有设置系统变量。你可能要加上它的完整路径：
```bash
-> /usr/local/php -v
```
当出现php的版本信息时，表明里的php安装是正确的。然后执行`php nb`命令：
```bash
-> php nb
 __     _   ____
|   \  | | | __ )
| |\ \ | | |  _ \    @_@
| | \ \| | | |_) |     。。。
|_|  \___| |____/  is a good framework!
--------------------------------------------------
Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -v, --version         Display this console version
  -q, --quiet           Do not output any message
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question

Available commands:
  action  execute controller in cli
  server  run server for http,tcp,udp,websocket,php in config
  test    

```
如果出现上面信息，说明一切正常。
通过上面的命令输出，我们可以看到`Usage`表示命令格式，`Options`表示当前命令支持的参数，`Available commands`表示当前可以使用的指令。

在没有执行指令的情况下，我们可以使用提示的参数进行：
```bash
-> php nb -v #查看版本
-> php nb -h # 查看帮助
```

如果想要知道某一个指令的信息，比如`test` 指令，我们可以这样:
```bash
-> php nb test -h   
Usage:
  test [options] [--] [<name>]

Arguments:
  name                  your name

Options:
      --city=CITY       city name
  -h, --help            Display this help message
  -v, --version         Display this console version
  -q, --quiet           Do not output any message
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question

Help:
 default help
```
通过上面显示，我们就可以知道`test`指令需要什么参数和配置参数了。
> 任何指令我们都可以通过`php nb xxx -h`来获取帮助信息





