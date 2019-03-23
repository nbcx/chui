<?php
namespace bin;
use model\System;

/**
 * 系统框架配置
 */
class Config extends \sdk\Config {

    public $register    = 'util\\Framework';

    //自动包含路径
    public $path_autoinclude    = [
        'include'=>__APP__.'bin'.DS
    ];

    public $path_autoext = [
        'chui.nb.cx'=>'nb',
        'nb.cx'=>'nb',
        'www.nb.cx'=>'nb',
        'wiki.nb.cx'=>'nb',
        'chui.ol.cx'=>'ol',
        'ol.cx'=>'ol',
        'wiki.ol.cx'=>'ol',
    ];

    public $folder_module = 'plugin';

    public $module_register = [
        'doc', 'links', 'thirds','index'
    ];

    //给bind模块配置独立使用域名
    public $module_bind = [
        'ol.cx'=>'index',
        'nb.cx'=>'index',
        'www.nb.cx'=>'index',
        'wiki.ol.cx'=>'doc',
        'wiki.nb.cx'=>'doc',
        'sblog.nb.cx'=>'sblog',
        's.ol.cx'=>'sblog'
    ];

    protected function _cookie() {
        $ex = explode('.',\nb\Request::driver()->host);
        $n = count($ex);
        $domain = $ex[$n-2].'.'.$ex[$n-1];
        return [
            'driver'=>'',
            'prefix'    => '',// cookie 名称前缀
            'expire'    => 0,// cookie 保存时间
            'path'      => '/',// cookie 保存路径
            'domain'    => $domain,// cookie 有效域名
            'secure'    => false,//  cookie 启用安全传输
            'httponly'  => '',// httponly设置
            'setcookie' => true,// 是否使用 setcookie
        ];
    }

    //模版引擎配置
    public $view = [
        'tpl_replace_string' => [
            '_pub_' => '/public/',
            '_uploads_' => '/uploads/',
            '_url_' => '/',
        ],
        'tpl_cache' => true,
    ];

    public $router = [
        'match'=> __APP__.'bin/router.inc'
    ];

    public $mail = [
        'host' => 'smtp.ym.163.com',
        'port' => 25,
        'from' => 'admin@nb.cx',
        'name' => 'NullBB',
        'username' => 'admin@nb.cx',
        'password' => 'angelove123'
    ];

    public $redis = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'db' => 1,
    ];

    public $security = [
        'global_xss_filtering'=>false,
        'protection'=>true,
        'tokenname'=>'stb_csrf_token',
        'cookiename'=>'stb_csrf_cookie',
        'expire'=>7200
    ];

    //swoole配置
    public $swoole = [
        'driver'=>'http',
        'register'=>'nb\\register\\Server',//注册一个类，来实现swoole自定义事件
        'host'=>'0.0.0.0',
        'port'=>9502,
        'max_request'=>5,//worker进程的最大任务数
        'worker_num'=>2,//设置启动的worker进程数。
        'dispatch_mode'=>2,//据包分发策略,默认为2
        'debug_mode'=>3,
        'enable_gzip'=>0,//是否启用压缩，0为不启用，1-9为压缩等级
        'log_file'=>'tmp'.DS.'swoole-http.log',
        'enable_pid'=>'/tmp/swoole.pid',
        'daemonize'=>false,

        //静态文件处理
        'static_path'=> _APP_,
        'static_allow'=>'ico|css|js|jpg|png|gif',
        'static_expire'=>1800,

        //异步任务处理配置
        'task_worker_num'=>2
    ];

    protected function _dao() {
        return [
            'driver' => 'mysql',
            'host' => 'where.cx',
            'port' => '3306',
            'dbname' => 'chui',
            'user' => 'dev',
            'pass' => '123456',
            'connect' => 'false',
            'charset' => 'UTF8',
            'prefix' => 'nb_', // 数据库表前缀
        ];
    }

    protected function _system() {
        return System::init();
    }


    //上传设置
    protected function _upload() {
        return include(__APP__.'bin'.DS.'upload.inc');
    }


    /**
     * 主题父目录路径
     * @return string
     */
    protected function _themePath() {
        return __APP__.'template'.DS;
    }


}

