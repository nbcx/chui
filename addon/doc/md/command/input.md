# 输入
命令行最有趣的部分，就是你可以利用参数（arguments）和选项（options）。参数和选项，能够让你从终端（terminal）向命令（command）动态地传入信息。

##  使用控制台参数
参数是字符串 - 由空格（space）分隔 - 跟在命令名称的后面。参数是有顺序的，而且可以是可选的或必须的。例如，添加一个可选的 last_name 参数到命令中，并且令 name 参数必填:
```php
use nb\console\input\Argument;
use nb\console\Pack;

class Test implements Command {
    // ...
    protected function configure(Pack $cmd) {
        $cmd->addArgument('name', Argument::REQUIRED, '你想打招呼的人?')
            ->addArgument('last_name', Argument::OPTIONAL, '你的名字呢?');
    }
}
```
现在你可以在命令中访问 last_name 参数：
```php
use nb\console\input\Input;
use nb\console\output\Output;

class Test implements Command {
    // ...
    protected function execute(Input $input, Output $output) {
        $text = 'Hi '.$input->getArgument('name');

        $lastName = $input->getArgument('last_name');
        if ($lastName) {
            $text .= ',I`m '.$lastName;
        }

        $output->writeln($text.'!');
    }
}
```
命令可以像下面列出的这样使用：
```bash
$php nb test Collin
Hi Collin!
 
$php nb test Collin Jack
Hi Collin,I`m Jack!
```

也可以让参数接收“值的列表”（假设你希望欢迎你的所有朋友）。但只有最后一个参数才能是列表：
```php
$cmd->addArgument(
    'names',
    Argument::IS_ARRAY,
    '你想问候谁（用空格分隔多个名字）?'
);
```
要使用列表，指定任意多的名字即可：
```bash
$php nb test Collin Az Jack
```

你可以访问到作为数组的 names 参数：
```php
$names = $input->getArgument('names')
if (count($names) > 0) {
    $text .= ' '.implode(', ', $names);
}
```

有三种参数变体可用：
|参数|说明|
|:----|:----|
|Argument::REQUIRED|参数必填。如果不提供，则命令不运行|
|Argument::OPTIONAL |参数可选，因此可以忽略|
|Argument::IS_ARRAYL|参数可以包含任意多个值。因此，它必须在参数列表中的最后一个|

你可以像下面这样同时使用`IS_ARRAY`和`REQUIRED`以及`OPTIONAL`:
```php
$cmd->addArgument(
    'names',
    Argument::IS_ARRAY | Argument::REQUIRED,
    '你想问候谁（用空格分隔多个名字）?'
);
```
## 使用控制台选项 
和参数不同，选项是没有顺序之分的 (也就是你可以按任意顺序指定它们) ，指定选项是用两个中杠 (如 --yell)。选项 始终 是可选的，而且可以被设置为接收一个值 (如 --dir=src) ，或者是一个布尔值旗标而不需要值 (如 --yell)。

例如，向一个“信息在一行之内应该被输出指定的次数”的命令中添加一个新的选项：
```php
$cmd->addOption(
    'iterations',
    null,
    Option::VALUE_REQUIRED,
    'How many times should the message be printed?',
    1
);
```

接下来，使用这个命令来多次输出信息：
```php
for ($i = 0; $i < $input->getOption('iterations'); $i++) {
    $output->writeln($text);
}
```
现在，运行命令时，你可以可选地指定一个 --iterations 旗标了:
```bash
# 不提供--iterations，使用的默认值是(1）
$php nb test Collin
Hi Collin!
 
$php nb test Collin --iterations=5
Hi Collin!
Hi Collin!
Hi Collin!
Hi Collin!
Hi Collin!
 
# 选项的顺序是无所谓的
$php nb test Collin --iterations=5 --yell
$php nb test Collin --yell --iterations=5
$php nb test --yell --iterations=5 Collin
```
> 你还可以为选项声明一个“以单个中杠开头”的单字符的快捷方式，像是 -i:

```php
$cmd->addOption(
    'iterations',
    'i',
    InputOption::VALUE_REQUIRED,
    'How many times should the message be printed?',
    1
);
```

另有四种选项的变体可用：
|参数|说明|
|:----|:----|
|Option::VALUE_IS_ARRAY|此选项可接收多个值 (如 --dir=/foo --dir=/bar)  |
|Option::VALUE_NONE|此选项不接受输入的值 (如 --yell)  |
|Option::VALUE_REQUIRED|此选项的值必填 (如 --iterations=5), 但选项本身仍然是可选的 |
|Option::VALUE_OPTIONAL|此选项的值可有可无 (e.g. --yell 或 --yell=loud)  |

你可以像下面这样同时使用 VALUE_IS_ARRAY 和 VALUE_REQUIRED 或 VALUE_OPTIONAL :
```php
$cmd->addOption(
    'colors',
    null,
    Option::VALUE_REQUIRED | Option::VALUE_IS_ARRAY,
    'Which colors do you like?',
    ['blue', 'red']
);
```
当你创建命令时，使用选项并令其可选地接受一个值，是不受约束的。但是，当这个选项并没有具体值 (command --language) ，或者它根本没被使用 (command) 的时候，你是没有办法区分这些情况的。两种情况下，此选项所收到的值都是 null。