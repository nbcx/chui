<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/22 上午8:26
 */

namespace plugin\thirds;


class Label {

    /**
     * 获取支持登陆的方式
     * @return string
     */
    public static function can() {
        return 'This is plugin Hello';
    }


    public static function node($pid) {
        return Node::pid($pid);
    }

}