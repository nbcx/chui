<?php
namespace model;

use util\Auth;
use nb\Collection;
use nb\Model;

class User extends Model {

    protected static function __config() {
        return ['user', 'id'];
    }

    /**
     * 通过用户名查找用户详细信息
     * @param $username
     * @return User
     */
    public static function findName($username) {
        return self::find('username=?',$username);
    }

    public static function page($rows,$start,$desc='id desc') {
        return self::dao()->driver->limit($rows,$start)
            ->orderby($desc)
            ->paginate();
    }

    /**
     * 获取指定条件的用户排行列表
     * @param $row 数量
     * @param $ord 排行条件
     * @return mixed
     */
    public static function rank($row, $ord='id desc') {
        return self::dao()->where('is_active=?',1)
            ->orderby($ord)
            ->limit($row,1)
            ->fetchAll();
    }

    /**
     * 用户发帖成功后，更新用户发帖信息
     * @param $uid
     */
    public static function refreshTopic($uid,$now) {
        $credit = System::init()->credit_post;
        return self::updateId(
            $uid,
            "credit=credit+{$credit},lastpost={$now},topics=topics"
        );
        //$this->setId($uid,"credit=credit+{$credit}");
        //\model\User::updateId($uid,"lastpost={$now},topics=topics+1");
    }

    public static function add($data) {
        $data = array_merge([
            'ct'=>time(),
            'ut'=>time()
        ],$data);
        return self::insert($data);
    }


    /**
     * 用户个人主页地址
     * @return string
     */
    public function _url() {
        return '/user/' . $this->id;
    }

    /**
     * 是否为当前登陆用户
     */
    public function _isSelf() {
        return $this->id == Auth::init()->id;
    }

    /**
     * 用户头像地址
     * @return string
     */
    public function _avatar() {
        return "/uploads/avatar/default/default.png";
        return '//gravatar.nb.cx/'.md5($this->id);
        //return System::init()->uploadUrl.$this->row['avatar'] . 'normal.png?'.$this->ut;
    }

    public function _avatarBig() {
        return "/uploads/avatar/default/big.png";
        return System::init()->uploadUrl.$this->row['avatar'] . 'big.png?'.$this->ut;
    }

    public function _avatarSmall() {
        return "/uploads/avatar/default/small.png";
        return System::init()->uploadUrl.$this->row['avatar'] . 'small.png?'.$this->ut;
    }

    public function _regtime() {
        return date('Y-m-d H:i:s',$this->row['regtime']);
    }


    public function _favoriteUrl() {
        return '/favorite';
    }

    /**
     * 我的关注主页
     * @return string
     */
    public function _followUrl() {
        return '/follow';
    }

    /**
     * 关注我的主页
     * @return string
     */
    public function _fansUrl() {
        return '#';
    }

    public function _topicUrl() {
        return '/user/topic?id='.$this->id;
    }

    public function _commentUrl() {
        return '/user/comment?id='.$this->id;
    }

    /**
     * 关注我的接口
     * @return string
     */
    public function _followPost() {
        return '/follow/add?uid='.$this->id;
    }

    /**
     * 取消关注我的接口
     * @return string
     */
    public function _followDelPost() {
        return '/follow/del?id='.$this->id;
    }

    /**
     * 是否关注了我
     * @return $this|bool
     */
    public function _isFollow() {
        if($this->isSelf) {
            return false;
        }
        $uid = Auth::init()->id;
        $is_followed = \model\Follow::find('id=? and follow_uid=?',[$uid,$this->id]);
        return $is_followed;
    }


    public function _letterPost() {

    }


    public function _group() {
        if($this->gid) {
            return Group::find('id=?',$this->gid);
        }
        return new Collection();
    }

    public function _birth() {
        if($this->birthday) {
            list($year,$month,$day) = explode('/',$this->birthday);
            $birth = [
                'year'=>$year,
                'month'=>$month,
                'day'=>$day
            ];
        }
        else {
            $birth = ['year'=>0,'month'=>0,'day'=>0];
        }
        return new Collection($birth);
    }

}
