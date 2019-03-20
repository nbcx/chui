<?php
/**
 * UEditor编辑器（1.4.3版本）
 *
 * @package UEditor
 * @author jy625
 * @version 1.0.0
 * @link http://jyboke.com
 * @date 2014-06-04
 */

namespace plugin\thirds;

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
        $this->pos('controller\Gateway')->proxy = ['plugin\thirds\Hook','login'];
        $this->conf($form);
        return '恭喜了，你的thirds插件安装成功了！';
    }

    public function login() {
        e($_GET);
        // 配置可以移到框架的配置文件里面
        $config = [
            'proxy' => [
                'dns' => '127.0.0.1:1080',
                'type' => CURLPROXY_SOCKS5
            ],
            'weibo' => [
                'key' => '111111111',
                'secret' => 'xxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=weibo',
                'scope' => 'email'
            ],
            'qq' => [
                'key' => '111111111',
                'secret' => 'xxxxxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=qq',
            ],
            'google' => [
                'key' => 'xxxxxxxxxxxxxx',
                'secret' => 'xxxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=google',
                'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
                'enableProxy' => true,
            ],
            'github' => [
                'key' => 'xxxxxxxxxxxx',
                'secret' => 'xxxxxxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=github',
            ],
        ];
        try {
            $obj = new Login($config);
            $socialInfo = $obj->authAndGetUserInfo();
        }
        catch (\Exception $e) {
            echo $e->getMessage(), '具体原因：' . print_r($obj->getLastError(), true);
            exit;
        }
        ed($socialInfo);
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
     * @throws Typecho_Plugin_Exception
     */
    public function activate() {
        //Typecho_Plugin::factory('admin/write-post.php')->richEditor = array('UEditor_Plugin', 'render');
        //Typecho_Plugin::factory('admin/write-page.php')->richEditor = array('UEditor_Plugin', 'render');

        //Helper::addPanel(0, 'UEditor/ueditor/ueditor.config.js', '', '', 'contributor');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public function deactivate() {
        //Helper::removePanel(0, 'UEditor/ueditor/ueditor.config.js');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public function config($form) { }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public function personalConfig($form) { }


}