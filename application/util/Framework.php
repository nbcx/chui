<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 17/12/2 下午4:46
 */
namespace util;

use model\Plugin;
use nb\Config;
use nb\Hook;
use \nb\event\Framework as Frame;

class Framework extends Frame {

    public function config(Config &$conf) {
        $auth = Auth::init();

        $handle = Plugin::handle($auth);
        //加载插件
        Hook::init($handle);
    }

    public function request(\nb\request\Driver $request) {

    }

    public function validate($msg,$args) {
        quit($msg);
        //show_message($msg);
    }

}