<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/18 上午11:56
 */

namespace plugins\hello;


class Test {

    public function __construct() {
        echo 'Test __construct';
    }

    public static function index() {
        echo '<br/><span class="message success">'
            . htmlspecialchars('index')
            . '</span>';
    }

    public function show() {
        echo '<br/><span class="message success">'
            . htmlspecialchars('show')
            . '</span>';
    }

}