<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/7/3 下午11:04
 */

namespace controller;

use util\Controller;
use util\Uploader;

class Action extends Controller {

    public function captcha() {
        $conf['name'] = 'yzm';
        $captcha = new \util\Captcha($conf);
        $captcha->show();
    }

    /**
     * 上传接口
     * @param string $type 上传类型，file,image,video
     * @return bool
     * @throws \Exception
     */
    public function upload($type='file') {
        $up = new Uploader();

        $return = \nb\Hook::pos('controller\Upload')->trigger($signal)->before($up);

        if($signal === false) {

            /* 生成上传实例对象并完成上传 */

            $method = Router::ins()->function;
            if (!method_exists($up, $method)) {
                ed('非法请求');
            }
            $return = $up->$method();
        }
        if(!$return) {
            return false;
        }
        $this->json($return);
    }

}