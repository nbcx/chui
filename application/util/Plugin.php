<?php
/**
 * 插件处理类
 *
 * @category typecho
 * @package Plugin
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
namespace util;

use nb\Hook;
use nb\Pool;

class Plugin extends Hook {

    public static function conf($name,$value=null) {
        $key = get_called_class().':conf';
        $conf = Pool::value($key,[]);
        if(is_array($name)) {
            $conf = array_merge($conf,$name);
        }
        else {
            $conf[$name] = $value;
        }
        Pool::set($key,$conf);
    }

    /**
     * 导出当前插件handle
     *
     * @access public
     * @return array
     */
    public static function export() {
        return [
            Pool::value(get_called_class().':conf',[]),
            Pool::value(get_called_class().':handles',[])
        ];
    }
}
