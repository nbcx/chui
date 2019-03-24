<?php
//项目的根路径
define('_APP_',__DIR__.'/..');

//加载初始化文件
include ('/home/www/nb/boot.php');

\bin\Config::register();

\nb\Dispatcher::run();