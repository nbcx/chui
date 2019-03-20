# NB Framework

NB 是PHP语言开发的一套WEB框架，为敏捷开发，高性能微服务开发，服务器开发提供简单好用的解决方案。
NB从设计之初，就考虑了兼容swoole和php-fpm两种运行方式，一套代码，可以根据业务需求在两种方式之间轻松切换。 我们提供了缓存，数据库，路由，数据验证，调试，composer等等所有开发中常用的功能，并且使其简单易用。

## 优势:

- 自由在swoole和php-fpm两种运行方式之间切换
- 高度自由的自定义开发模式，打造你自己独特的项目风格
- 独立的DEBUG调试页面，使多端合作开发简单方便
- 简单而强大的配置方式


## 特性:
NB提供了WEB开发中几乎所有的基础功能，且依旧保持轻量。你可以很容易的从头到尾构建一个强大的网站应用。
- 全局的依赖注入容器
- 支持 Swoole 扩展
- 可扩展的驱动式组件开发
- 国际化(i18n)支持
- 数据库 ORM
- 强大的调试系统
- 视图模版
- 快速灵活的参数验证器
- 多类型的缓存驱动
- 多模块支持
- 命令行运行模式
- 高内聚低耦合
- 内置协程 HTTP, TCP, WebSocket 网络服务器
- 协程 Mysql, Redis 客户端
- 强大的命令行工具
- Composer管理

## 关注我们
你可以从下面这些渠道获取NB的最新消息和帮助。
- 官网主页：[https://nb.cx/](https://nb.cx/)
- 官方QQ交流群：1985508
- 官方交流社区：[https://chui.nb.cx](https://chui.nb.cx)(建设中)
- 项目托管地址：[github](https://github.com/nbcx/framework) [oschina](https://wiki.nb.cx) (暂未开放)


## 协议
NB Framework的开源协议为apache 2.0，详情参见LICENSE。

## 关于ab基准测试:
我们分别在swoole和fpm两种运行方式下做了AB测试，都是在同一台服务器上进行的纯hello world测试。
测试代码为NB提供的Demo项目。
服务器配置为：
> 系统: CentOS 7.1(未做系统内核调优)
> CPU: 阿里云单核
> 内存: 1G
> php: 5.6.30
> Swoole: 1.9.1


Swoole运行模式的测试结果：
```bash
ab -c 100 -n 500000 http://127.0.0.1:9501/ 测试结果如下

Server Software:        swoole-http-server
Server Hostname:        127.0.0.1
Server Port:            9501

Document Path:          /
Document Length:        21 bytes

Concurrency Level:      100
Time taken for tests:   5.808 seconds
Complete requests:      50000
Failed requests:        0
Write errors:           0
Total transferred:      8850000 bytes
HTML transferred:       1050000 bytes
Requests per second:    8608.19 [#/sec] (mean)
Time per request:       11.617 [ms] (mean)
Time per request:       0.116 [ms] (mean, across all concurrent requests)
Transfer rate:          1487.94 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1   0.4      1       2
Processing:     2   10   2.5     10      81
Waiting:        1   10   2.4      9      80
Total:          3   12   2.5     12      83

Percentage of the requests served within a certain time (ms)
  50%     12
  66%     12
  75%     12
  80%     12
  90%     12
  95%     13
  98%     13
  99%     13
 100%     83 (longest request)
```



    
    