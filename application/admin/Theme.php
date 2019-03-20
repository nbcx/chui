<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/17 上午9:40
 */

namespace controller\admin;

use util\Administrator;
use model\Conf;
use nb\Collection;

class Theme extends Administrator {

    public function index() {
        $themes = \model\Theme::all();
        $this->assign('themes',$themes);
        $this->display();
    }

    public function lists() {
        $this->_no_pjax_redirect();

        $themes = \model\Theme::all();
        $this->assign('themes',$themes);
        $this->display();
    }

    public function config($theme=null) {
        $theme = $theme?:Conf::init()->themes;

        $this->_no_pjax_redirect();

        $this->layout('theme/config');

        $conf = \model\Theme::id($theme);

        $this->assign('title',$theme);
        $this->assign('name',$theme);
        $this->assign('theme',$conf);

        $this->on($this->isPost)->post($theme,$conf);

        $tpl = __APP__ . "theme/{$theme}/config";

        $this->display($tpl);
    }

    public function activate($id) {
        $theme = \model\Theme::id($id);
        if(!$theme->isHave) {
            die('此主题不存在');
        }
        if($theme->isActivate) {
            die('此主题已经是启用状态');
        }
        Conf::updateId('themes',[
            'value'=>$id
        ]);
        ed('ok');
        $this->lists();
    }

    public function install($id) {
        $theme = \model\Theme::id($id);
        if(!$theme->isHave) {
            die('此主题不存在');
        }
        if($theme->isInstall) {
            die('此主题已经是启用状态');
        }
        \model\Theme::insert([
            'folder' => $id,
            'config' => json_encode([])
        ]);
        ed('ok');
        $this->lists();
    }

    public function uninstall($id) {
        $theme = \model\Theme::id($id);
        if(!$theme->isHave) {
            die('此主题不存在');
        }
        if($theme->isActivate) {
            die('正在使用的主题无法卸载');
        }
        \model\Theme::deleteId($id);
        //\model\Theme::insert([
        //    'folder' => $id,
        //    'config' => json_encode([])
        //]);
        $this->lists();
    }


    public function edit() {
        $this->_no_pjax_redirect();
        $this->display();
    }

    private function post($theme,$conf) {
        $data = $this->form();//;
        if($conf->isHave) {
            \model\Theme::updateId($theme,[
                'config'=>json_encode($data)
            ]);
        }
        else {
            \model\Theme::insert([
                'name'=>$theme,
                'config'=>json_encode($data)
            ]);
        }
        $this->assign('theme',$data);
    }

    /**
     * 不是pjax请求就跳转到主题首页
     */
    private function _no_pjax_redirect() {
        if(!isset($_SERVER['HTTP_X_PJAX'])) {
            return redirect('/admin/theme/index');
        }
    }

}