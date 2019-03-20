<?php
/**
 * 插件接口
 *
 * @package Plugin
 * @abstract
 */
namespace util;

use model\Plugin;
use util\Pinstall;

abstract class IPlugin {

    /**
     * 安装插件方法,如果启用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws \Exception
     */
    abstract public function install($form=[]);

    /**
     * 卸载插件方法,如果启用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws \Exception
     */
    abstract public function uninstall($form=[]);


    /**
     * 启用插件方法,如果启用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws \Exception
     */
    abstract public function activate();

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws \Exception
     */
    abstract public function deactivate();

    /**
     * 获取插件配置面板
     *
     * @static
     * @access public
     * @param Form $form 配置面板
     * @return void
     */
    abstract public function config($form);

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Form $form
     * @return void
     */
    abstract public function personalConfig($form);

    /**
     * 是否可以使用此插件
     */
    public function isuse() {

    }

    protected function pos($position) {
        return \util\Plugin::pos($position);
    }

    protected function conf($name,$value=null) {
        return \util\Plugin::conf($name,$value);
    }

}
