<?php
/**
 * 友情链接插件
 *
 * @package Links
 * @author Admin
 * @version 1.1.0
 * @dependence 13.10.18-*
 * @link http://www.cehoo.cn
 *
 */
namespace plugin\links;

use util\IPlugin;
use nb\Config;
use util\Common;
use util\Db;
use util\PluginInterface;
use util\Request;
use util\Widget;
use util\Helper;

class Plugin extends IPlugin {

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
     * @throws Typecho_Plugin_Exception
     */
    public function activate() {

        $info = Links::linksInstall();
        Helper::addPanel(3, 'Links/manage-links.php', '友情链接', '管理友情链接', 'administrator');
        Helper::addAction('links-edit', 'Links_Action');
        Plugin::factory('widget\abstract\Contents')->contentEx = ['Links', 'parse'];
        Plugin::factory('widget\abstract\Contents')->excerptEx = ['Links', 'parse'];
        Plugin::factory('widget\abstract\Comments')->contentEx = ['Links', 'parse'];
        return _t($info);
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
        Helper::removeAction('links-edit');
        Helper::removePanel(3, 'Links/manage-links.php');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Form $form 配置面板
     * @return void
     */
    public function config($form) { }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Form $form
     * @return void
     */
    public function personalConfig($form) { }

    public static function linksInstall() {
        $installDb = Db::get();
        $type = explode('_', $installDb->getAdapterName());
        $type = array_pop($type);
        $prefix = $installDb->getPrefix();
        $scripts = file_get_contents(Config::getx('usr_dir').'plugins/links/Mysql.sql');
        $scripts = str_replace('typecho_', $prefix, $scripts);
        $scripts = str_replace('%charset%', 'utf8', $scripts);
        $scripts = explode(';', $scripts);
        try {

            foreach ($scripts as $script) {
                $script = trim($script);
                if ($script) {
                    $installDb->query($script, Db::WRITE);
                }
            }
            return '建立友情链接数据表，插件启用成功';
        }
        catch (\Exception $e) {
            $code = $e->getCode();
            if('42S01' == $code) {
                return '检测到友情链接数据表，友情链接插件启用成功';
            }
            else if (('pdo\Mysql' == $type && 1050 == $code) || ('SQLite' == $type && ('HY000' == $code || 1 == $code)) ) {
                try {
                    $script = 'SELECT `lid`, `name`, `url`, `sort`, `image`, `description`, `user`, `order` from `' . $prefix . 'links`';
                    $installDb->query($script, Db::READ);
                    return '检测到友情链接数据表，友情链接插件启用成功';
                }
                catch (\Exception $e) {
                    $code = $e->getCode();
                    if (('Mysql' == $type && 1054 == $code) ||
                        ('SQLite' == $type && ('HY000' == $code || 1 == $code))
                    ) {
                        return Links::linksUpdate($installDb, $type, $prefix);
                    }
                    throw new \Exception('数据表检测失败，友情链接插件启用失败。错误号：' . $code);
                }
            }
            else {
                throw new \Exception('数据表建立失败，友情链接插件启用失败。错误号：' . $code);
            }
        }
    }

    public static function linksUpdate($installDb, $type, $prefix) {
        $scripts = file_get_contents(Config::getx('usr_dir').'plugins/links/Update_' . $type . '.sql');
        $scripts = str_replace('typecho_', $prefix, $scripts);
        $scripts = str_replace('%charset%', 'utf8', $scripts);
        $scripts = explode(';', $scripts);
        try {
            foreach ($scripts as $script) {
                $script = trim($script);
                if ($script) {
                    $installDb->query($script, Typecho_Db::WRITE);
                }
            }
            return '检测到旧版本友情链接数据表，升级成功';
        }
        catch (\Exception $e) {
            $code = $e->getCode();
            if (('Mysql' == $type && 1060 == $code)) {
                return '友情链接数据表已经存在，插件启用成功';
            }
            throw new \Exception('友情链接插件启用失败。错误号：' . $code);
        }
    }

    public static function form($action = NULL) {
        /** 构建表格 */
        $options = Widget::widget('Widget_Options');
        $form = new Form(Common::url('/action/links-edit', $options->index), Form::POST_METHOD);

        /** 链接名称 */
        $name = new Text('name', NULL, NULL, _t('链接名称*'));
        $form->addInput($name);

        /** 链接地址 */
        $url = new Text('url', NULL, "http://", _t('链接地址*'));
        $form->addInput($url);

        /** 链接分类 */
        $sort = new Text('sort', NULL, NULL, _t('链接分类'), _t('建议以英文字母开头，只包含字母与数字'));
        $form->addInput($sort);

        /** 链接图片 */
        $image = new Text('image', NULL, NULL, _t('链接图片'), _t('需要以http://开头，留空表示没有链接图片'));
        $form->addInput($image);

        /** 链接描述 */
        $description = new Textarea('description', NULL, NULL, _t('链接描述'));
        $form->addInput($description);

        /** 自定义数据 */
        $user = new Text('user', NULL, NULL, _t('自定义数据'), _t('该项用于用户自定义数据扩展'));
        $form->addInput($user);

        /** 链接动作 */
        $do = new Hidden('do');
        $form->addInput($do);

        /** 链接主键 */
        $lid = new Hidden('lid');
        $form->addInput($lid);

        /** 提交按钮 */
        $submit = new Submit();
        $form->addItem($submit);
        $request = Request::getInstance();

        if (isset($request->lid) && 'insert' != $action) {
            /** 更新模式 */
            $db = Db::get();
            $prefix = $db->getPrefix();
            $link = $db->fetchRow($db->select()->from($prefix . 'links')->where('lid = ?', $request->lid));
            if (!$link) {
                throw new \Exception(_t('链接不存在'), 404);
            }

            $name->value($link['name']);
            $url->value($link['url']);
            $sort->value($link['sort']);
            $image->value($link['image']);
            $description->value($link['description']);
            $user->value($link['user']);
            $do->value('update');
            $lid->value($link['lid']);
            $submit->value(_t('编辑链接'));
            $_action = 'update';
        }
        else {
            $do->value('insert');
            $submit->value(_t('增加链接'));
            $_action = 'insert';
        }

        if (empty($action)) {
            $action = $_action;
        }

        /** 给表单增加规则 */
        if ('insert' == $action || 'update' == $action) {
            $name->addRule('required', _t('必须填写链接名称'));
            $url->addRule('required', _t('必须填写链接地址'));
            $url->addRule('url', _t('不是一个合法的链接地址'));
            $image->addRule('url', _t('不是一个合法的图片地址'));
        }
        if ('update' == $action) {
            $lid->addRule('required', _t('链接主键不存在'));
            $lid->addRule(array(new Links_Plugin, 'LinkExists'), _t('链接不存在'));
        }
        return $form;
    }

    public static function LinkExists($lid) {
        $db = Db::get();
        $prefix = $db->getPrefix();
        $link = $db->fetchRow($db->select()->from($prefix . 'links')->where('lid = ?', $lid)->limit(1));
        return $link ? true : false;
    }

    /**
     * 控制输出格式
     */
    public static function output_str($pattern = NULL, $links_num = 0, $sort = NULL) {
        $options = Widget::widget('Widget_Options');
        if (!isset($options->plugins['activated']['Links'])) {
            return '友情链接插件未激活';
        }
        if (!isset($pattern) || $pattern == "" || $pattern == NULL || $pattern == "SHOW_TEXT") {
            $pattern = "<li><a href=\"{url}\" title=\"{title}\" target=\"_blank\">{name}</a></li>\n";
        }
        else if ($pattern == "SHOW_IMG") {
            $pattern = "<li><a href=\"{url}\" title=\"{title}\" target=\"_blank\"><img src=\"{image}\" alt=\"{name}\" /></a></li>\n";
        }
        else if ($pattern == "SHOW_MIX") {
            $pattern = "<li><a href=\"{url}\" title=\"{title}\" target=\"_blank\"><img src=\"{image}\" alt=\"{name}\" /><span>{name}</span></a></li>\n";
        }
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        $options = Typecho_Widget::widget('Widget_Options');
        $nopic_url = Typecho_Common::url('/usr/plugins/Links/nopic.jpg', $options->siteUrl);
        $sql = $db->select()->from($prefix . 'links');
        if (!isset($sort) || $sort == "") {
            $sort = NULL;
        }
        if ($sort) {
            $sql = $sql->where('sort=?', $sort);
        }
        $sql = $sql->order($prefix . 'links.order', Typecho_Db::SORT_ASC);
        $links_num = intval($links_num);
        if ($links_num > 0) {
            $sql = $sql->limit($links_num);
        }
        $links = $db->fetchAll($sql);
        $str = "";
        foreach ($links as $link) {
            if ($link['image'] == NULL) {
                $link['image'] = $nopic_url;
            }
            $str .= str_replace(
                array('{lid}', '{name}', '{url}', '{sort}', '{title}', '{description}', '{image}', '{user}'),
                array($link['lid'], $link['name'], $link['url'], $link['sort'], $link['description'], $link['description'], $link['image'], $link['user']),
                $pattern
            );
        }
        return $str;
    }

    //输出
    public static function output($pattern = NULL, $links_num = 0, $sort = NULL) {
        echo Links::output_str($pattern, $links_num, $sort);
    }

    /**
     * 解析
     *
     * @access public
     * @param array $matches 解析值
     * @return string
     */
    public static function parseCallback($matches) {
        $db = Db::get();
        $pattern = $matches[3];
        $links_num = $matches[1];
        $sort = $matches[2];
        return Links::output_str($pattern, $links_num, $sort);
    }

    public static function parse($text, $widget, $lastResult) {
        $text = empty($lastResult) ? $text : $lastResult;

        if ($widget instanceof Archive || $widget instanceof Comments) {
            return preg_replace_callback("/<links\s*(\d*)\s*(\w*)>\s*(.*?)\s*<\/links>/is", ['plugin\links\Links', 'parseCallback'], $text);
        }
        else {
            return $text;
        }
    }
}
