<?php
namespace plugin\simplemde;

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
        $this->pos('controller\Upload')->before = ['addon\simplemde\Editor','before'];
        $this->pos('themes/add.htm')->richEditor = ['addon\simplemde\Editor','render'];
        $this->pos('controller\Comment')->insert = ['addon\simplemde\Editor','insert'];
        $this->conf($form);
        return '恭喜了，你的百度编辑器插件安装成功了！';
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