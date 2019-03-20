# 输出
输出即在控制台向用户展示信息。NB封装了几种常用的控制台视图UI，可以方便的在控制台上输出带颜色的字体，表格，进度条等特殊信息。

## 色彩格式
格式（format）所提供的方法，用于配合颜色来格式化output。相对于 如何对命令行进行彩色和样式输出 中提到的，使用这个你可以做更多高端事情。

#### 如何对命令行进行彩色和样式输出
通过在命令行的output中使用颜色，你可以区分不同类型的输出（比如，重要的信息，标题，注释，等等）。
>默认时，Windows的command console不支持彩色输出。
>Console对Windows系统禁用了颜色输出，但如果你的命令调用了发射颜色序列的其他脚本，它们会被错误地显示为转义字符（译注：在win10上命令行问题比较多，写进度条时也是怪异的，颜色和小图标都无法应用，推荐使用mac osx等系统玩symfony）。
>安装 Cmder, ConEmu, ANSICON 或 Mintty (GitBash和Cygwin默认使用的) 等免费程序以便为你的windows命令行添加颜色支持。

####使用颜色样式
只要你输出文本，就可以对文字加上标签以实现彩色输出。例如：
```php
$output->writeln('<info>foo</info>');// 绿字
$output->writeln('<comment>foo</comment>');// 黄字
$output->writeln('<question>foo</question>');// 青色背景上的黑字
$output->writeln('<error>foo</error>');// 红背景上的白字
```
标签闭合时可以用 </> 来替代，它会撤消所有由“最后一个未关闭的标签”所建立的格式化选项。

`Output`封装了几种常用的样式以供快速使用：
```php
$output->info('message');
$output->error('message');
$output->comment('message');
$output->warning('message');
$output->highlight('message');
$output->question('message');
```

#### 自定义颜色样式
使用`Formatter`类，也可以建立你自己的样式。
Formatter通常已经集成在`Output`中了，我们可以直接通过`Output`的对象获取：
```php
use nb\console\output\formatter\Style;
$formatter = $output->getFormatter();
$style = new Style('red', 'yellow', ['bold', 'blink']);
$formatter->setStyle('fire', $style);

$output->writeln('<fire>foo</fire>');
```
可用的前景和背景颜色是: black, red, green, yellow, blue, magenta, cyan 以及 white.

另有可用的选项是: bold, underscore, blink, reverse (可开启 "reverse video" 模式，即将前景和背景颜色互换) 以及 conceal (设置前景的颜色为透明，可隐藏上屏的文字 - 却仍可以选择和复制; 此选项在要求用户键入敏感信息时常会用到)。
```php
// 绿字
$output->writeln('<fg=green>foo</>');
 
// 青背景上的黑字
$output->writeln('<fg=black;bg=cyan>foo</>');
 
// 黄背景上的粗字
$output->writeln('<bg=yellow;options=bold>foo</>');
```



## 提问
提问需要创建一个`nb\output\Ask`对象，它需要一个 Input 实例作为第一个参数，一个 Output 实例作为第二个参数， 以及一个 Question 作为最后一个参数：

#### 要求用户确认
假设你希望在一个action被执行之前先行确认。添加以下代码到你的命令中：
```php
// ...
use nb\console\output\Ask;
use nb\console\output\question\Confirmation;

class YourCommand implements Command {
    // ...
 
    public function execute(Input $input, Output $output) {

        $confirm = new Confirmation('继续执行吗?', false);
        $ask = new Ask($input, $output, $confirm);
        $answer = $ask->run();
        if($answer) {
            $output->writeln('你选择了继续执行');
        }
        else {
            $output->writeln('你选择了停止执行');
        }
    }
}
```
本例中，用户会被问到 "继续执行吗?"。如果用户回答`y`它就返回`true`，或者`false`，如果答案是`n`的话。`Confirmation` 的第二个参数，是当用户不键入任何有效input时，返回的默认值。如果没有提供第二个参数，`true`会被取用。

>你可以在构造器的第三个参数中自定义一个正则表达式，用于判断答案是否是 "yes"的意思。
>例如，允许任何以 y 或 j 开头的（input），你可以这样设置:

```php
$question = new Confirmation(
    'Continue with this action?',
    false,
    '/^(y|j)/i'
);
//默认的regex是 /^y/i。
```

#### 询问用户信息
你也可以用超过一个简单的 yes/no 的答案来向用户提问。例如，如果你想要知道bundle的名称，可以把下面代码添加到你的命令中：
```php
use nb\console\output\Question;
// ...
public function execute(Input $input, Output $output) {

    $question = new Question('请输入你的名字?','张三');
    $ask = new Ask($input, $output, $question);
    $answer = $ask->run();
    $output->writeln('你好！'.$answer);
}
```
用户会被问 "请输入你的名字?"。他们可以键入一些会被Ask对象返回的名称。如果用户留空，默认值 (此处是`张三`) 会被返回。

#### 让用户从答案列表中选择
如果你预定义了一组答案让用户从中选择，你可以使用`Choice`，它确保用户只能从预定义列表中输入有效字符串:
```php
use nb\console\output\question\Choice;
// ...
public function execute(Input $input, Output $output) {
    // ...
    $choice = new Choice('请选择你的性别?', ['男','女'], 1);
    $ask = new Ask($input, $output, $choice);
    $answer = $ask->run();
    $output->writeln('你是一个'.$answer.'人');
    // ... 
}
```
默认被选中的选项由构造器的第三个参数提供。默认是`null`，代表没有默认的选项。

如果用户输入了无效字符串，会显示一个错误信息，用户会被要求再一次提供答案，直到他们输入一个有效字符串，或是达到了尝试上限为止。默认的最大尝试次数是`null`，代表可以无限次尝试。你可以使用`setErrorMessage()`定义自己的错误信息。
#### 多选
有时，可以给出多个答案。 ChoiceQuestion 使用逗号分隔的值，提供了此项功能。默认是禁用的，开启它可使用 setMultiselect():
```php
use nb\console\output\question\Choice;
// ...
public function execute(Inpute $input, Output $output) {
    // ...
    $choice = new Choice('请选择你喜欢的颜色?', ['red', 'blue', 'yellow'], '0,1');
    $choice->setErrorMessage('你输入了一个无效的选择！');
    $choice->setMultiselect(true);

    $ask = new Ask($input, $output, $choice);
    $answer = $ask->run();
    $output->writeln('你选择了: ' . implode(', ', $answer));
}
```
现在，当用户键入 1,2，结果会是: `你选择了: blue, yellow`。
如果用户不键入任何内容，结果是: `你选择了: red, blue`。

#### 自动完成
对于给定的问题，你也可以提供一个潜在答案的数组。它们将根据用户的敲击而自动完成：
```php
use nb\console\output\Question;
// ...
public function execute(Input $input, Output $output){
    // ...
    $word = ['AcmeDemoBundle', 'AcmeBlogBundle', 'AcmeStoreBundle'];
    $question = new Question('请输入一个单词？', 'FooBundle');
    $question->setAutocompleterValues($word);

    $ask = new Ask($input, $output, $question);
    $answer = $ask->run();
    $output->writeln($answer);
}
```
#### 隐藏用户响应 
你也可以在问问题时隐藏响应。这对密码来说极为方便：
```php
use nb\console\output\Question;
// ...
public function execute(Input $input, Output $output) {
    // ...
    $question = new Question('请输入你的密码?');
    $question->setHidden(true);
    $question->setHiddenFallback(false);

    $ask = new Ask($input, $output, $question);
    $pass = $ask->run();
    $output->writeln($pass);
}

```
>当你提问并隐藏响应时，Symofny将使用一个二进制的change stty mode或是使用另一种手段来隐藏之。
>如果都不可用，它就回滚为响应可见，除非你像上例那样，使用 setHiddenFallback() 来将此行为设置成 false。
>本例中，一个 RuntimeException 异常会被抛出。

#### 验证答案
你甚至可以验证答案。例如，前面例子中你曾询问过bundle名称。根据Symfony的命名约定，它应该被施以 Bundle 后缀，你可以使用 setValidator() 方法来验证它:
```php
// ...
public function execute(Input $input, Output $output) {
    // ...
    $question = new Question('Please enter the name of the bundle', 'AcmeDemoBundle');
    $question->setValidator(function ($answer) {
        if ('Bundle' !== substr($answer, -6)) {
            throw new \RuntimeException(
                'The name of the bundle should be suffixed with \'Bundle\''
            );
        }
        return $answer;
    });
    //允许的错误次数
    $question->setMaxAttempts(2);

    $ask = new Ask($input, $output, $question);
    $answer = $ask->run();
    $output->writeln($answer);
} 
```
$validator是一个callback，专门处理验证。它在有错误发生时应抛出一个异常。异常信息会被显示在控制台中，所以在里面放入一些有用的信息是一个很好的实践。回调函数在验证通过时，应该返回用户的input。

你可以用 setMaxAttempts() 方法来设置（验证失败时的）最大的提问次数。如果达到最大值，它将使用默认值。使用 null 代表可以无限次尝试回答（直到验证通过）。用户将被始终提问，直到他们提供了有效答案为止，也只有输入有效时命令才会继续执行。

#### 验证一个隐藏的响应
你也可以在隐藏（答案输入）的提问中使用validator：
```php
use Symfony\Component\Console\Question\Question;
 
// ...
public function execute(Input $input, Output $output) {
    // ...
    $helper = $this->getHelper('question');
 
    $question = new Question('Please enter your password');
    $question->setValidator(function ($value) {
        if (trim($value) == '') {
            throw new \Exception('The password can not be empty');
        }
 
        return $value;
    });
    $question->setHidden(true);
    $question->setMaxAttempts(20);
 
    $password = $output->ask($input, $question);
}
```


## 进度条
当执行长时间运行的命令时，显示进度信息可能是有益的，它会在你的命令运行时更新。

要显示进度细节，使用 ProgressBar，传给它一个单元（unit）总数，然后在命令执行时，推进（advance）进度:
```php
use nb\console\output\ProgressBar;
 
// create a new progress bar (50 units)
// 创建一个新的进度条（50单元）
$progress = new ProgressBar($output, 50);
 
// start and displays the progress bar
// 启动并显示进度条
$progress->start();
 
$i = 0;
while ($i++ < 50) {
    // ... do some work / 做一些事
 
    // advance the progress bar 1 unit
    // 推进进度条一个单位
    $progress->advance();
 
    // you can also advance the progress bar by more than 1 unit
    // 你也可以用一个以上的单位来推进进度条
    // $progress->advance(3);
}
 
// ensure that the progress bar is at 100%
// 确保进度条达到100%
$progress->finish();
```
不同于使用步数来推进条子 (即使用 advance() 方法)，你还可以通过 setProgress() 方法来设置当前的总进度。
>如果你的系统不支持 ANSI codes，更新进度条时会添加一个新行。
>要防止output溢出，调整相应的 setRedrawFrequency()。
>默认时，当使用 max时，重绘频率（redraw frequency）被设为你的 max 值的 10%。

如果你不知道要推进的总步数，在创建 ProgressBar 实例时可直接忽略step参数:
```php
$progress = new ProgressBar($output);
```
这时进度将被动态显示:
```bash
# no max steps (displays it like a throbber)
# 不提供最大步数时（动态指示）
0 [>---------------------------]
5 [----->----------------------]
5 [============================]
 
# max steps defined / 定义了max steps时
 0/3 [>---------------------------]   0%
 1/3 [=========>------------------]  33%
 3/3 [============================] 100%
```
当任务完成，不要忘记调用 finish() 以便进度条的显示被刷新为100%的完成度。
>如果你希望在进度条运行时，输出一些东西，首先调用 clear() 。
>然后调用 display() 来重新显示进度条。

### 自定义进度条

#### 内置的格式
默认时，在进度条上渲染的信息取决于当前 OutputInterface 实例的冗长度级别（verbosity level）：
```bash
# OutputInterface::VERBOSITY_NORMAL (CLI with no verbosity flag)
 0/3 [>---------------------------]   0%
 1/3 [=========>------------------]  33%
 3/3 [============================] 100%
 
# OutputInterface::VERBOSITY_VERBOSE (-v)
 0/3 [>---------------------------]   0%  1 sec
 1/3 [=========>------------------]  33%  1 sec
 3/3 [============================] 100%  1 sec
 
# OutputInterface::VERBOSITY_VERY_VERBOSE (-vv)
 0/3 [>---------------------------]   0%  1 sec
 1/3 [=========>------------------]  33%  1 sec
 3/3 [============================] 100%  1 sec
 
# OutputInterface::VERBOSITY_DEBUG (-vvv)
 0/3 [>---------------------------]   0%  1 sec/1 sec  1.0 MB
 1/3 [=========>------------------]  33%  1 sec/1 sec  1.0 MB
 3/3 [============================] 100%  1 sec/1 sec  1.0 MB
```
>如果你使用静默旗标`（-q）`来调用命令，进度条将不被显示。

若不想受到当前命令的冗长模式约束，你还可以通过 setFormat() 来强制格式输出：
```php
$bar->setFormat('verbose');
```
有以下内置格式，如果你没有设置进度条的总步数，需使用 _nomax 变体：
|格式|_nomax格式|
|:---|:---|
|normal|normal_nomax|
|verbose|verbose_nomax|
|very_verbose|very_verbose_nomax|
|debug|debug_nomax|

#### 自定义格式 
不使用内置格式（built-in formats）时，可以设置你自己的：
```php
$bar->setFormat('%bar%');
```
它设置的是只显示进度条本身
```bash
>---------------------------
=========>------------------
============================
```
进度条格式（prgress bar format），就是包含了特定占位符（被 % 括起的名称）的一个字符串；占位符将根据条子的当前进度被替换掉。以下是内置占位符的列表：
|占位符|说明|
|:---|:---|
|current|The current step（当前步数）|
|max|最大步数 (未定义时是0)|
|bar|条子本身|
|percent|进度完成的百分比 (max未定义时不可用)|
|elapsed|启动进度条后流经的时间|
|remaining|完成任务的剩余时间 (max未定义时不可用)|
|estimated|预期的完成任务时间 (max未定义时不可用)|
|memory|当前内存占用|
|message|当前进度条上的附加信息|

例如，这里有一个如何去设置一个和 debug 相同的格式之样例：
```php
$bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
```
注意到被添加到某些占位符的 :6s 部分了吗？这就是你如何去调整条子外观 (包括格式和排列等)的方法。冒号 (:) 后面的部分用于设置字符串的 sprintf 格式。

message 占位符有些特殊，因为你必须自行设置它的值:
```php
$bar->setMessage('Task starts');
$bar->start();
 
$bar->setMessage('Task in progress...');
$bar->advance();
 
// ...
 
$bar->setMessage('Task is finished');
$bar->finish();
```
若不想对给定的进度条实例去设置格式，你可以设置全局格式（global formats）：
```php
ProgressBar::setFormatDefinition('minimal', 'Progress: %percent%%');
 
$bar = new ProgressBar($output, 3);
$bar->setFormat('minimal');
```
以上代码定义了一个全新的 minimal 格式，你可以用在自己的进度条中：
```bash
Progress: 0%
Progress: 33%
Progress: 100%
```
>重新的定义内置格式总是好过自行创造一种。因为它可以让你基于“命令的冗长级别旗标”来自动进行多样化显示。

要定义一个包含了“最大步数已知时才可用”的占位符之全新风格时，你应该创建一个 _nomax 变体：
```php
ProgressBar::setFormatDefinition('minimal', '%percent%% %remaining%');
ProgressBar::setFormatDefinition('minimal_nomax', '%percent%%');
 
$bar = new ProgressBar($output);
$bar->setFormat('minimal');
```

显示进度条时，该格式将被自动设置为 minimal_nomax，如果条子并没有一个“最大步数”的话，就像上例中的代码。

>一个格式（formata）可以包含任何有效的ANSI字符，也可以使用Symfony的特定方式来设置颜色：

```php
ProgressBar::setFormatDefinition(
    'minimal',
    '<info>%percent%</info>\033[32m%\033[0m <fg=white;bg=blue>%remaining%</>'
);
```

#### 条子设置
在所有占位符中，bar 有点特殊，因为所有用于显示它的占位符都可以被自定义：
```php
// the finished part of the bar / 条子的已完成部分
$progress->setBarCharacter('<comment>=</comment>');
 
// the unfinished part of the bar / 条子的未完成部分
$progress->setEmptyBarCharacter(' ');
 
// the progress character / 进度之字符
$progress->setProgressCharacter('|');
 
// the bar width / 条子宽度
$progress->setBarWidth(50);
```
>出于性能原因，在设置较高数值的总步数时要小心。
>例如，如果你遍历的是大量元素，考虑调用 setRedrawFrequency() 来将重绘频率设为一个高值，以便它只在少量的循环中被更新： so it updates on only some iterations:

```php
$progress = new ProgressBar($output, 50000);
$progress->start();
 
// update every 100 iterations / 每100次循环更新一次
$progress->setRedrawFrequency(100);
 
$i = 0;
while ($i++ < 50000) {
    // ... do some work / 处理一些事
 
    $progress->advance();
}
```

#### 自定义占位符
如果你希望显示一些取决于 “在内置占位符列表中不可用” 的进度条显示 之信息，你可以创建自己的。看一下如何创建 remaining_steps 占位符，用来显示剩余的步数：
```php
ProgressBar::setPlaceholderFormatterDefinition(
    'remaining_steps',
    function (ProgressBar $bar, OutputInterface $output) {
        return $bar->getMaxSteps() - $bar->getProgress();
    }
);
```

#### 自定义消息
%message% 占位符允许你指定一个自定义消息，它会和进度条一起显示出来。但如果你需要更多条消息，只需定义你自己的：
```php
$bar->setMessage('Task starts');
$bar->setMessage('', 'filename');
$bar->start();
 
$bar->setMessage('Task is in progress...');
while ($file = array_pop($files)) {
    $bar->setMessage($filename, 'filename');
    $bar->advance();
}
 
$bar->setMessage('Task is finished');
$bar->setMessage('', 'filename');
$bar->finish();
```
对于让 filename 成为进度条的一部分，只需添加 %filename% 占位符到你的format中:
```php
$bar->setFormat(" %message%\n %current%/%max%\n Working on %filename%");
```
一个格式，可以扩展为多行；当你希望在进度条旁边显示更多的上下文信息时，这特别有用。

## 表格
当构建命令行程序时，显示表格数据可能是有用的。
```bash
+---------------+--------------------------+------------------+
| ISBN          | 书名                     | 作者              |
+---------------+--------------------------+------------------+
| 99921-58-10-7 | Divine Comedy            | Dante Alighieri  |
| 9971-5-0210-0 | A Tale of Two Cities     | Charles Dickens  |
| 960-425-059-0 | The Lord of the Rings    | J. R. R. Tolkien |
| 80-902734-1-6 | And Then There Were None | Agatha Christie  |
+---------------+--------------------------+------------------+ 
```
要显示一个表格，使用 Table，设置头（headers），设置行（rows），即可生成表格:
```php
use nb\console\output\Table;
// ...
class SomeCommand implements Command {

    public function execute(Input $input, Output $output) {
        $table = new Table($output);
        $table->setHeaders(['ISBN', '书名', '作者'])
          ->setRows([
              ['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri'],
              ['9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens'],
              ['960-425-059-0', 'The Lord of the Rings', 'J. R. R. Tolkien'],
              ['80-902734-1-6', 'And Then There Were None', 'Agatha Christie'],
          ]);
        $table->render();
    }
}
```
通过传入一个`Separator`实例作为row，你可以在表格输出的任何地方添加分隔符（separator）:
```php
use nb\console\output\table\Separator;
 
$table->setRows([
    ['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri'],
    ['9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens'],
    new Separator(),
    ['960-425-059-0', 'The Lord of the Rings', 'J. R. R. Tolkien'],
    ['80-902734-1-6', 'And Then There Were None', 'Agatha Christie'],
]);
```
```bash
+---------------+--------------------------+------------------+
| ISBN          | 书名                     | 作者              |
+---------------+--------------------------+------------------+
| 99921-58-10-7 | Divine Comedy            | Dante Alighieri  |
| 9971-5-0210-0 | A Tale of Two Cities     | Charles Dickens  |
+---------------+--------------------------+------------------+
| 960-425-059-0 | The Lord of the Rings    | J. R. R. Tolkien |
| 80-902734-1-6 | And Then There Were None | Agatha Christie  |
+---------------+--------------------------+------------------+ 
```

默认时，列（column）的宽度将根据其内容自动计算出来。使用 setColumnWidths() 方法可以显式指定列宽:
```php
$table->setColumnWidths([10, 0, 30];
$table->render();
```
本例中，第一列的宽度是 10，最后一列的是 30，而第二列的宽度将被自动计算，因为设了 0 值。可自行运行查看效果。

注意已定义的列宽，总是被认为是最小的列宽度。如果内容难以匹配，则给定的列宽将被增加到最大内容长度。这就是为什么在上例中，第一列是 13 个字符长度，而用户只定义了 10 的宽度。

你可以使用 setColumnWidth() 方法分别地设置每一列的宽度。第一个参数是列的索引 (从 0 开始)，第二个参数是列的宽度:
```php
// ...
$table->setColumnWidth(0, 10);
$table->setColumnWidth(2, 30);
$table->render();
```
通过 setStyle() 可以改变表格默认的样式:
```php
// 与不设置样式时相同
$table->setStyle('default');
 
// 改变默认样式为紧凑型
$table->setStyle('compact');

// 设置样式为 borderless
$table->setStyle('borderless');
$table->render();
```
代码的结果是：
```bash
 =============== ========================== ================== 
  ISBN            书名                       作者              
 =============== ========================== ================== 
  99921-58-10-7   Divine Comedy              Dante Alighieri   
  9971-5-0210-0   A Tale of Two Cities       Charles Dickens   
  960-425-059-0   The Lord of the Rings      J. R. R. Tolkien  
  80-902734-1-6   And Then There Were None   Agatha Christie   
 =============== ========================== ==================
```
如果内置的风格不适合你的需求，定义你自己的：
```php
use nb\console\output\table\Style;
 
// by default, this is based on the default style
$style = new Style();
 
// customize the style
$style
    ->setHorizontalBorderChar('<fg=magenta>|</>')
    ->setVerticalBorderChar('<fg=magenta>-</>')
    ->setCrossingChar(' ')
;
 
// use the style for this table
$table->setStyle($style);
```
以下是你可以自定义内容的完整列表：
|方法|
|:---|
|[setPaddingChar()](#)|
|[setHorizontalBorderChar()](#)|
|[setVerticalBorderChar()](#)|
|[setCrossingChar()](#)|
|[setCellHeaderFormat()](#)|
|[setCellRowFormat()](#)|
|[setBorderFormat()](#)|
|[setPadType()](#)|

你也可以注册一个全局样式：
```php
// register the style under the colorful name
// 注册colorful这个名称的样式
Table::setStyleDefinition('colorful', $style);
 
// use it for a table / 在表格中使用
$table->setStyle('colorful');
```
本方法也可用于覆写内置样式。

#### 扩展多列和多行
要让表格单元扩展到多个列，可以使用`Cell`:
```php
use nb\console\output\Table;
use nb\console\output\table\Separator;
use nb\console\output\table\Cell;
 
$table = new Table($output);
$table->setHeaders(['ISBN', '书名', '作者'])
    ->setRows(array(
        ['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri'],
        new Separator(),
        [new Cell('This value spans 3 columns.', ['colspan' => 3])]
    ));
$table->render();
```
```bash
+---------------+---------------+-----------------+
| ISBN          | 书名          | 作者             |
+---------------+---------------+-----------------+
| 99921-58-10-7 | Divine Comedy | Dante Alighieri |
+---------------+---------------+-----------------+
| This value spans 3 columns.                     |
+---------------+---------------+-----------------+
```
把header cell（头所在的单元格）扩展为整个表格宽度，你可以创建一个多行的页面标题：
```php
$table->setHeaders([
    [new Cell('Main table title', ['colspan' => 3]],
    ['ISBN', 'Title', 'Author'],
])
// ...
```
以类似方式你可以扩展多行：
```php
use nb\console\output\Table;
use nb\console\output\table\Separator;
use nb\console\output\table\Cell;
 
$table = new Table($output);
$table->setHeaders(['ISBN', '书名', '作者'])
    ->setRows(array(
        [
            '978-0521567817',
            'De Monarchia',
            new Cell("Dante Alighieri\nspans multiple rows", ['rowspan' => 2]),
        ],
        ['978-0804169127', 'Divine Comedy'],
    ));
$table->render();
```
代码将输出：
```bash
+----------------+---------------+---------------------+
| ISBN           | 书名          | 作者                |
+----------------+---------------+---------------------+
| 978-0521567817 | De Monarchia  | Dante Alighieri     |
| 978-0804169127 | Divine Comedy | spans multiple rows |
+----------------+---------------+---------------------+
```
同时使用 colspan 和 rowspan 选项，即可创建你所希望的任何表格（table layer）。



