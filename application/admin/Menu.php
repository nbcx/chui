<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/2 下午6:21
 */

namespace controller\admin;

use util\Administrator;
use util\Notice;

class Menu extends Administrator {

    public function index() {
        $ztree = \model\Menu::zTree();
        $menu = \model\Menu::findId($ztree[0]['id']);

        $this->assign('menu',$menu);
        $this->assign('ztree',json_encode($ztree));
        $this->display();
    }

    public function sort() {

    }

    public function info($id) {
        if(!$this->isPjax) {
            return redirect('/admin/menu/index');
        }
        $menu = \model\Menu::findId($id);
        $this->assign('menu',$menu);
        $this->on($this->isPost)->edit();
        $this->display();
    }

    private function edit() {
        $data = $this->form();
        $row = \model\Menu::updateId($data['id'],$data);
        if($row) {
            $menu = \model\Menu::findId($data['id']);
            $this->assign('menu',$menu);
            Notice::success('修改成功！');
        }
        else {
            Notice::warning('你没有修改任何数据！');
        }
    }

}