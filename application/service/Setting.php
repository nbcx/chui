<?php
namespace service;

use bin\Config;
use util\Auth;
use model\Conf;
use nb\Middle;
use util\AvatarResize;
use util\ResizeImage;
use util\Service;
use util\Uploader;

class Setting extends Service {


    public function profile() {

        $data = $this->form('request',[
            'homepage','location','signature','occupation','sex'
        ]);

        list($year,$month,$day) = $this->input('year','month','day');
        $data['birthday'] = $year.'/'.$month.'/'.$day;

        \model\User::updateId(Auth::init()->id,$data);
        $this->msg = '修改成功';
        return true;
    }


    /**
     * 修改用户头像
     * @param string $msg
     */
    public function avatar() {
        $conf = load('avatar');
        $resizePath = 'avatar/' . Auth::init()->id.'/';

        $conf['name'] = $resizePath.'raw';

        $up = new Uploader($conf);

        $file = $this->input('files',['upfile']);

        $avatar = $up->upload($file);
        b('$avatar',$avatar);
        if(!$avatar) {
            $this->msg = $up['state'];
            return false;
        }
        $path = $up['fullPath'];
        $ri = new ResizeImage([
            'source_image' => $path,
            'target_image_dir' => $conf['path'].$resizePath,
            'format'=>$up['ext']
        ]);
        if (
            $ri->resize('big',120, 120 ) &&
            $ri->resize('normal',74, 74) &&
            $ri->resize('small', 30, 30)
        ) {
            \model\User::updateId(Auth::init()->id,[
                'avatar' => "/uploads/$resizePath%s{$up['ext']}",
                'ut'=> time()
            ]);
            //删除tmp下的原图
            unlink($path);
            return true;
        }
        //设置三个头像没有成功
        $this->msg = '头像上传失败，请重试！';
        return false;
    }

    /**
     * 修改密码
     * @return bool
     */
    public function password() {

        list($oldpass,$pass) = $this->input('oldpassword','password');

        $user = Auth::init();
        $oldpass = password_dohash($oldpass, $user->salt);

        if ($user['password'] != $oldpass) {
            $this->fail = '当前密码错误!';
            return false;
        }

        $salt = get_salt();
        $row = \model\User::updateId($user->id,[
            'password' => password_dohash($pass,$salt),
            'salt' => $salt,
        ]);

        if($row) {
            logout();
            $this->success = '修改密码成功,请重新登录！';
            return true;
        }

        $this->fail = '修改失败';
        return false;
    }


    protected function nick() {
        $nick = $this->input('nick');

        //检查是否可修改昵称
        if(!Auth::init()->ifNick) {
            $this->fail = '已经修改过昵称了！无法在修改！';
            return false;
        }

        $row = \model\User::find('username=?',$nick);
        if($row) {
            $this->fail = '昵称已被使用，请换一个试试！';
            return false;
        }

        \model\User::updateId(Auth::init()->id,[
            'username'=>$nick
        ]);
        Auth::freshen();
        $this->success = '修改成功';
        return true;
    }
}
