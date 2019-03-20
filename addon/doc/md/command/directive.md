# 自定义命令
NB框架本身提供了一些常用的命令。这些命令是通过`Command`接口被创建的。你也可以使用它创建自己的命令。

## 创建一个命令
命令通过类来定义，这些类必须实现`nb\console\Command`接口，类存放位置随意。
例如，一个名为`test`的命令必须遵循此结构:
> 本列子demo位于`../demo/application/command/Test.php`.

```php
namespace command;

class Test implements Command {
    
    public function configure(Pack $cmd) { 
        //指令描述和定义(必须)
    }
    
    
    public function execute(Input $input, Output $output) {
        //指令执行调用
    }

    public function initialize(Input $input, Output $output){
        //(可选)
        //此方法在 interact() 和 execute() 方法之前执行。
        //它的主要作用是初始化那些用在命令其余方法中的变量。
    }
    
    public function interact(Input $input, Output $output){
        //(可选)
        //此方法在 initialize() 之后、 execute() 之前执行。
        //它的作用是检查是否错失了某些选项/参数，然后以互动方式向用户请求这些值。
        //这是你可以问询错失的选项/参数的最后一个地方。此后，丢失的选项/参数将导致一个错误。
    }

    
}
```

## 配置命令
首先，你必须在 configure() 方法中配置命令的名称。然后可选地定义一个帮助信息（help message）和 输入选项及输入参数（input options and arguments）。
我们在这里定义了一个叫`test`的命令，并设置了一个name参数和一个city选项。
```php
public function configure(Pack $cmd) {
    $cmd->setName('test') //命令的名字（"php nb" 后面的部分）
        ->addArgument('name', Argument::OPTIONAL, "your name")
        ->addOption('city', null, Option::VALUE_REQUIRED, 'city name')
        ->setDescription('just test!') // 运行 "php nb test" 时的简短描述
        ->setHelp('default help');//运行命令时使用 "--help" 选项时的完整命令描述
}
```
`nb\console\Pack`是指令包装类，通过它可以对指令进行命名，描述等一些列操作。

## 执行命令
配置命令之后，我们需要把它加到框架配置里。我们就能在终端（terminal）中执行它：
```php
public $console = [
    'user'    => null,
    'commands'=>['command\\Test']
];
```
运行命令
```bash
$php nb test
```
你可能已经预期，这个命令将什么也不做，因为你还没有写入任何逻辑。在 execute() 方法里添加你自己的逻辑，这个方法可以访问到input stream（如，选项和参数）和output stream（以写入信息到命令行）。
```php
public function execute(Input $input, Output $output) {
    // outputs multiple lines to the console (adding "\n" at the end of each line)
    // 输出多行到控制台（在每一行的末尾添加 "\n"）
    $output->writeln([
        'User Creator',
        '============',
        '',
    ]);
 
    // outputs a message followed by a "\n"
    $output->writeln('Whoa!');
 
    // outputs a message without adding a "\n" at the end of the line
    $output->write('You are about to ');
    $output->write('create a user.');
}
```
现在，尝试执行此命令：
```bash
$  php bin/console app:create-user
User Creator
============
 
Whoa!
You are about to create a user.
```

## 控制台输入
使用input选项或参数来传入信息给命令：
```php
use Symfony\Component\Console\Input\InputArgument;
 
// ...
public function configure(Pack $cmd) {
    // configure an argument / 配置一个参数
    $cmd->addArgument('username', Argument::REQUIRED, 'The username of the user.');
}
 
// ...
public function execute(Input $input, Output $output){
    $output->writeln([
        'User Creator',
        '============',
        '',
    ]);
 
    // 使用 getArgument() 取出参数值
    $output->writeln('Username: '.$input->getArgument('username'));
}
```
现在，你可以传入用户名到命令中：
```bash
$php bin/console app:create-user Wouter
User Creator
============
 
Username: Wouter
```





