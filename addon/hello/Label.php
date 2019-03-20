<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/22 上午8:26
 */

namespace plugin\hello;


use model\Node;

class Label {

    public static function func() {
        return 'This is plugin Hello';
    }

    public static function node($pid) {
        return Node::pid($pid);
    }

}