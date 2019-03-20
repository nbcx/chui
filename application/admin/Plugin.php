<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/17 上午9:38
 */

namespace controller\admin;

use util\Administrator;
use model\User;
use model\UserPlugin;
use nb\Cookie;
use nb\Dispatcher;
use plugins\hello\Hello;
use util\Notice;

class Plugin extends Administrator {

    public function index() {
        $unenables = \model\Plugin::unenable();
        $this->assign('unenables',$unenables);

        $enables = \model\Plugin::enable();
        $this->assign('enables',$enables);

        $this->display();
    }

    public function config($id) {
        $this->_no_pjax_redirect();

        $plugin = \model\Plugin::id($id);


        $this->layout('plugin/config');

        $this->assign('title',$plugin->info['name']);
        $this->assign('plugin',$plugin);

        $tpl = __APP__ . "plugin/{$id}/conf/config";

        $this->display($tpl);
    }

    /**
     * 不是pjax请求就跳转到主题首页
     */
    private function _no_pjax_redirect() {
        if(!isset($_SERVER['HTTP_X_PJAX'])) {
            return redirect('/admin/theme/index');
        }
    }

    public function activate($id) {
        $plugin = \model\Plugin::id($id);
        if(!$plugin->isHave) {
            die('此插件不存在');
        }
        if(!$plugin->isInstall) {
            die('此插件还没有安装');
        }
        if($plugin->isActivate) {
            die('此插件已经是启用状态');
        }
        $this->assign('plugin',$plugin);

        $boot = $plugin->info->install?:"plugin\\{$plugin->folder}\\Hook";
        $boot = new $boot();

        $msg = $boot->activate();

        \model\Plugin::updateId($id,['activate'=>1]);

        $msg = $msg?:"插件[{$plugin->info['name']}]启用成功！";
        Notice::success($msg);
        $this->dialog('#content');
    }

    public function deactivate($id) {
        $plugin = \model\Plugin::id($id);
        if(!$plugin->isHave) {
            die('此插件不存在');
        }
        if(!$plugin->isInstall) {
            die('此插件还没有安装');
        }
        if($plugin->isDeactivate) {
            die('此插件已经是禁用状态');
        }
        $this->assign('plugin',$plugin);

        $boot = $plugin->info->install?:"plugin\\{$plugin->folder}\\Hook";
        $boot = new $boot();

        $msg = $boot->deactivate();

        \model\Plugin::updateId($id,['activate'=>0]);

        $msg = $msg?:"插件[{$plugin->info['name']}]禁用成功！";
        Notice::success($msg);
        $this->dialog('#content');
    }

    public function install($id) {
        $plugin = \model\Plugin::id($id);
        if(!$plugin->isHave) {
            die('此主题不存在');
        }
        if($plugin->isInstall) {
            die('此主题已经安装过了');
        }
        $this->assign('plugin',$plugin);

        if($this->isGet && $plugin->installConf) {
            $this->layout('plugin/install');
            $this->display($plugin->installConf);
            return;
        }

        $boot = $plugin->info->install?:"plugin\\{$plugin->folder}\\Hook";
        $boot = new $boot();

        $msg = $boot->install($this->form());


        list($conf,$handle) = \util\Plugin::export();

        \model\Plugin::insert([
            'folder' => $id,
            'config' => json_encode($conf),
            'handle' => json_encode($handle),
            'overall' => 0,
            'activate'=>0
        ]);
        $msg = $msg?:"插件[{$plugin->info['name']}]安装成功了！";
        Notice::success($msg);
        $this->dialog('#content');
    }

    public function uninstall($id) {
        $plugin = \model\Plugin::id($id);
        if($plugin->isEmpty) {
            die('此插件不存在');
        }
        if(!$plugin->isInstall) {
            die('此插件还没有安装，无需卸载');
        }

        $this->assign('plugin',$plugin);

        if($this->isGet && $plugin->uninstallConf) {
            $this->layout('plugin/uninstall');
            $this->display($plugin->uninstallConf);
            return;
        }

        $boot = $plugin->info->install?:"plugin\\{$plugin->folder}\\Hook";
        $boot = new $boot();

        $msg = $boot->uninstall($this->form());

        \model\Plugin::deleteId($id);

        $msg = $msg?:"插件[{$plugin->info['name']}]已经卸载了！";
        Notice::success($msg);
        $this->dialog('#content');
    }

    public function user($id,$page=1) {

        $data['title'] = '用户管理';
        $data['act'] = 'index';
        //分页
        $rows = 10;
        list($total,$uplugs) = UserPlugin::paginate($rows,$page,['pname=?',$id]);

        $data['total'] = $total;
        $data['uplugs'] = $uplugs;

        $this->assign($data);
        $this->display();
    }
}