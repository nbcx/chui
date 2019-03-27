<?php
/**
 * 提示框组件
 *
 * @package Widget
 */
namespace util;

use nb\Cookie;
use nb\Pool;

class Notice {
    /**
     * 提示高亮
     *
     * @access public
     * @var string
     */
    public $highlight;

    /**
     * 高亮相关元素
     *
     * @access public
     * @param string $theId 需要高亮元素的id
     * @return void
     */
    public static function highlight($theId) {
        Pool::in(get_class())->highlight = $theId;
        $options = Option::initialization();
        Cookie::set('__typecho_notice_highlight', $theId, $options->gmtTime + $options->timezone + 86400);
    }

    /**
     * 获取高亮的id
     *
     * @access public
     * @return integer
     */
    public static function getHighlightId() {
        return preg_match("/[0-9]+/", Pool::in(get_class())->highlight, $matches) ? $matches[0] : 0;
    }

    /**
     * 设定堆栈每一行的值
     *
     * @param string $value 值对应的键值
     * @param string $type 提示类型
     * @param string $typeFix 兼容老插件
     * @return $this
     */
    public function set($value) {
        $notice = is_array($value) ? array_values($value) : [$value];
        //if (empty($type) && $typeFix) {
        //    $type = $typeFix;
        //}
        //$options = Option::initialization();
        //Cookie::set('__nb_notice', json_encode($notice),$options->gmtTime + $options->timezone + 86400);
        //Cookie::set('__nb_notice_type', $type,$options->gmtTime + $options->timezone + 86400);

        Cookie::set('__nb_notice', json_encode($notice));

        return $this;

    }

    /**
     * @param $value
     * @return $this
     */
    public static function success($value){
        $notice = Pool::object(get_called_class());
        $notice->set($value);
        Cookie::set('__nb_notice_type', 'success');
        return $notice;
    }

    /**
     * @param $value
     * @return $this
     */
    public static function error($value) {
        $notice = Pool::object(get_called_class());
        $notice->set($value);
        Cookie::set('__nb_notice_type', 'error');
        return $notice;
    }

    /**
     * @param $value
     * @return $this
     */
    public static function info($value) {
        $notice = Pool::object(get_called_class());
        $notice->set($value);
        Cookie::set('__nb_notice_type', 'info');
        return $notice;
    }

    /**
     * @param $value
     * @return $this
     */
    public static function warning($value) {
        $notice = Pool::object(get_called_class());
        $notice->set($value);
        Cookie::set('__nb_notice_type', 'warning');
        return $notice;
    }

    public function topRight() {
        Cookie::set('__nb_notice_position', 'toast-top-right');
    }

    public function topLeft() {
        Cookie::set('__nb_notice_position', 'toast-top-left');
    }

    public function bottomRight() {
        Cookie::set('__nb_notice_position', 'toast-bottom-right');
    }

    public function bottomLeft() {
        Cookie::set('__nb_notice_position', 'toast-bottom-left');
    }

    public function topFullWidth() {
        Cookie::set('__nb_notice_position', 'toast-top-full-width');
    }

    public function bottomFullWidth() {
        Cookie::set('__nb_notice_position', 'toast-bottom-full-width');
    }
}
