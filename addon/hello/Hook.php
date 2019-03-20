<?php
/**
 * Hello
 * 
 * @package Hello
 * @author Admin
 * @version 1.0.0
 * @link http://typecho.org
 */
namespace plugin\hello;

use util\IPlugin;

class Hook extends IPlugin {

    /**
     * 安装插件方法,如果启用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws \Exception
     */
    public function install($form=[]) {
        //$this->pos('admin/menu.php')->navBar = 'plugins\hello\Hello:render';//['', 'render'];
        $this->pos('admin/menu.php')->navBar = ['plugins\hello\Hello','text'];//['', 'render'];
        $this->conf($form);
        return '恭喜了，你的Hello插件安装成功了！';
    }

    /**
     * 卸载插件方法,如果启用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws \Exception
     */
    public function uninstall($form=[]){

    }

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws \Exception
     */
    public function activate() {
        \nb\Hook::pos('admin_hello')->Yes_text = ['plugins\hello\Test', 'index'];
        \nb\Hook::pos('admin/menu.php')->___text = ['plugins\hello\Test', 'index'];
        \nb\Hook::pos('admin/menu.php')->navBar = ['plugins\hello\Hello', 'render'];
        \nb\Hook::pos('admin/menu.php')->text = ['plugins\hello\Test', 'index'];

        return;

        $this->hander([
            'admin/menu.php:navBar' => [
                'plugins\hello\Hello::render',
                ['plugins\hello\Hello','render']
            ],
        ]);
        $this->hander('admin/menu.php:navBar',['plugins\hello\Hello', 'render']);

        return [
            'admin/menu.php:navBar'=>['plugins\hello\Hello', 'render']
        ];
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws \Exception
     */
    public function deactivate(){}

    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Form $form 配置面板
     * @return void
     */
    public function config($form) {
        /** 分类名称 */
        $name = new Text('word', NULL, 'Hello World', _t('说点什么'));
        $form->addInput($name);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Form $form
     * @return void
     */
    public function personalConfig($form){}
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render() {
        echo '<span class="message success">'. htmlspecialchars('hello'). '</span>';
    }

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function text() {
        echo '<span class="message success">'. htmlspecialchars('hello'). '</span>';
    }
}
