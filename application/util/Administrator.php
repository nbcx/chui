<?php
namespace util;

use bin\Config;
use model\Conf;
use model\Setting;
use nb\Request;

/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 17/9/7 下午8:13
 */
class Administrator extends Base {

    /**
     * @var Security
     */
    protected $security;

    /**
     * @var Config
     */
    protected $config;

    public function __before(){
        //在模版里读取配置
        $data['conf'] = Config::$o;

        $this->security = new Security();

        $this->view->config([
            'view_suffix' => 'html',
            /*'layout_on'   => true,
            'layout_name' => 'application@layout',*/
        ]);

        //初始化权限模块
        $auth = Auth::init();

        /** 检查登陆 */
        if(!$auth->islogin) {
            quit('please welcome in login!');
            show_message('请登陆后访问！', '/login');
        }
        if (!$auth->isadmin) {
            quit('you not premison!');
            show_message('你没有权限访问此模块！');
        }

        //用户相关信息
        $data['auth'] = $auth;//$this->user =  Auth::$o->getUserInfo();

        $this->assign($data);

        if(Request::input('_pjax') || isset($_SERVER['HTTP_X_PJAX'])) {
            $this->layout(false);
        }
        return true;
    }

    /**
     * 对dialog-form的提交做反应
     */
    protected function dialog($container,$url=null) {
        $ref = [
            'container'=>$container,
            'url'=>$url
        ];
        quit(json_encode($ref));
    }

    protected function display($template='', $vars = [], $config = []){
        //$router = Router::driver();
        //if($router->module) {
        //    $template = __APP__ . "plugins/{$router->module}/{$template}";
        //}
        parent::display($template, $vars, $config);
    }

}