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