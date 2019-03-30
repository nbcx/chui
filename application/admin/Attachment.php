<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/12 下午4:57
 */

namespace controller\admin;


use model\Attach;
use util\Administrator;

class Attachment extends Administrator {

    public function index() {
        $data['title'] = '管理后台';
        $this->assign('attachs',Attach::fetchs());
        $this->display();
    }

}