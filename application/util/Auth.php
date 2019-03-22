<?php
namespace util;

use model\Conf;
use model\Node;
use model\Setting;
use model\User;
use nb\Collection;
use nb\Session;
use nb\Pool;

class Auth extends User {

    /**
     * 用户组
     *
     * @access public
     * @var array
     */
    public $groups = [
        'administrator' => 0,
        'editor' => 1,
        'contributor' => 2
    ];

    /**
     * @return $this
     */
    public static function init() {
        if($user = Pool::get(get_class())) {
            return $user;
        }
        $token = \nb\Cookie::get('_user');
        $user = $token?self::find('token=?',$token):new self();
        return Pool::value(
            get_class(),
            $user
        );
    }

    public static function init_bak() {
        if($user = Pool::get(get_class())) {
            return $user;
        }

        $token = \sdk\Auth::token();

        //没有token则为未登录
        if(!$token) {
            return new self();
        }

        //判断本地是否登录
        $user = Session::get($token);

        //如果登录，返回用户信息
        if($user) {
            $user = new self($user);
            return Pool::set(get_class(),$user);
        }

        //若果没有登录，判断Auth是否登录
        $auth = \sdk\Auth::init();
        if($auth->empty) {
            \sdk\Auth::logout();
            return new self();
        }

        //修改本地为登录
        $user = self::find('nbid=?',$auth->nbid);

        //如果没有用户记录，则为没在此应用注册信息
        //可以选择注册或其它操作
        //这里进行自动注册
        if($user->empty) {
            $id = self::add([
                'nbid' => $auth->nbid,
                'username'   => $auth->username,
                'mail'   => $auth->mail,
            ]);
            //返回登录信息
            $user = self::findId($id);
        }

        Session::set($token,$user->stack);
        return Pool::set(get_class(),$user);
    }

    /**
     * 用于用户修改个人资料后
     * 刷新登录的缓存信息
     */
    public function freshen() {
        $token = \sdk\Auth::token();
        if($token) {
            $user = self::findId($this->id);
            Session::set($token,$user->stack);
        }
    }


    /**
     * 用户当用户修改了自己个人信息后
     * 及时刷新登录的缓存信息
     * @return bool
     */
    /*
    public static function freshen() {
        $token = \sdk\Auth::token();
        $user = Session::get($token);
        if($user) {
            Session::set($token,self::findId($user['id'],false));
            b('user',Session::get($token));
            return true;
        }
        return false;
    }
    */

    public function _isNotLogin() {
        return !$this->id;
    }

    /**
     * 判断用户是否已经登录
     *
     * @access public
     * @return void
     */
    public function _isLogin() {
        if ($this->id) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否管理员
     *
     * @access    public
     * @param    string $group 用户组
     * @param    boolean $return 是否为返回模式
     * @return    boolean
     */
    public function _isAdmin() {
        //$user = $this->getUserInfo();
        $group_type = $this->group->type;//NSession::get('user.group_type');//$_SESSION['group_type'];//$this->_CI->session->userdata('group_type');
        /** 权限验证通过 */
        return ($this->islogin && $group_type != '' && $group_type == 0) ? true : false;
    }

    public function _isMaster($node_id) {
        $query = NodeDao::obj()->getId($node_id);
        $data = explode(',', $query['master']);

        $username = $this->user['username'];//$this->_CI->session->userdata('username');
        $group_type = $this->user['group_type'];//$this->_CI->session->userdata('group_type');
        /** 权限验证通过 */
        return ($this->is_login() && in_array($username, $data) && $group_type == 1) ? true : false;
    }

    ///////////////////////////////////
    ///平台相关功能

    public function _nick() {
        return $this->username;
    }

    //是否可以修改昵称
    public function _ifNick() {
        return $this->username == $this->nb->username;
    }

    //通行证信息
    protected function _nb() {
        return new Collection(\sdk\Auth::nb($this->nbid));
    }

    public function is_user($uid) {
        //$user = $this->getUserInfo();
        //$suid = $_SESSION['uid'];//$this->_CI->session->userdata('uid');
        if ($this->user['uid'] != '' && $uid == $this->user['uid']) {
            return true;
        }
        return false;
        //return ($this->is_login() && $uid==$this->_CI->session->userdata('uid')) ? TRUE : FALSE;
    }

    /**
     * 验证是否可以编辑帖子
     */
    public function permitEdit($topic) {
        if(is_numeric($topic)) {
            $topic = \model\Topic::findId($topic);
        }
        if ($this->isAdmin || $this->isMaster($topic->id)) {
            return true;
        }
        return false;
    }

    /**
     * 验证是否可以删除帖子
     */
    public function permitDel($topic) {
        if(is_numeric($topic)) {
            $topic = \model\Topic::findId($topic);
        }
        if ($this->is_admin || $this->is_master($topic->id)) {
            return true;
        }
        return false;
    }

    public function user_permit($node_id) {
        $query = Node::findId($node_id);// NodeDao::obj()->row($node_id);
        //$query = $this->_CI->db->select('permit')->get_where('nodes', array('node_id' => $node_id))->row_array();
        if ($query['permit']) {
            $data = explode(',', $query['permit']);
            //$user = $this->getUserInfo();
            $gid = $this->user['gid'];
            /** 权限验证通过 */
            return ($this->islogin && in_array($gid, $data)) ? true : false;
        }
        return true;
    }

    //修改昵称的接口地址
    public function _alterNickPost() {
        return Security::url('/setting/post?action=nick');
    }

}