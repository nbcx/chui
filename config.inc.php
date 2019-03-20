<?php
/**
 * 系统框架配置
 */
return [
    //是否开启调试
    'debug' => 'show:server',

    'default_index' => 'home',//node

    'dao' => [
        'driver' => 'mysql',
        'host' => 'data.nb.cx',
        'port' => '3306',
        'dbname' => 'chui',
        'user' => 'dev',
        'pass' => '123456',
        'connect' => 'false',
        'charset' => 'UTF8',
        'prefix' => 'nb_', // 数据库表前缀
    ]
];


