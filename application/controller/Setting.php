<?php
namespace controller;

use util\Auth;
use util\Controller;
use model\Conf;
use model\Plugin;

class Setting extends Controller  {

    public function __before(){
        $pass = parent::__before();
        if(Auth::init()->isNotLogin) {
            $this->jump('请登录后再查看', Conf::init()->loginUrl);
            return false;
        }
        return $pass;
    }

    public function post($action) {
        $this->protect();

        $run = \service\Setting::run($action,function ($fail){
            $this->fail($fail);
        });

        Auth::init()->freshen();
        $this->success($run->msg);
    }

    /**
     * 基本信息设置
     */
    public function index() {
        $this->assignSafe('action','/setting/post?action=profile');
        $this->title('账户设置');
        $this->display('setting/index');
    }


    /**
     * 账户设置
     */
    public function account() {
        $this->display('setting/account');
    }

    /**
     * 头像设置
     */
    public function avatar() {
        $this->assignSafe('action','/setting/post?action=avatar');
        $this->title('头像设置');
        $this->display('setting/avatar');
    }

    /**
     * 修改密码控制器
     */
    public function password() {
        $this->assignSafe('action','/setting/post?action=password');
        $this->title('修改密码');
        $this->display('setting/password');
    }

    public function addon() {
        $data['activates'] = Plugin::personal(Auth::init()->id,1);
        $data['deactivates'] = Plugin::personal(Auth::init()->id,0);
        $this->assign($data);
        $this->display('setting/addon');
    }

    public function conceal() {
        $this->title('隐私设置');
        $this->display('setting/conceal');
    }

}
