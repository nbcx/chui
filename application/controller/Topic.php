<?php
namespace controller;

use util\Auth;
use util\Controller;
use model\Conf;
use nb\Collection;

class Topic extends Controller  {


    public function show($topic_id = 1, $page = 1) {

        $topic = \model\Topic::findId($topic_id);

        if (!$topic) {
            show_message('贴子不存在', site_url('/'));
        }
        elseif (!Auth::init()->user_permit($topic['nid'])) {//权限
            show_message('您无权访问此节点中的贴子');
        }

        //更新浏览数
        \model\Topic::updateId($topic_id,'views=views+1');

        //评论分页
        $rows = 15;

        //获取评论
        $comment = \model\Comment::topic($topic_id, $page, $rows);  //CommentDao::obj()->get_comment($page, $rows, $topic_id, $this->config->comment_order);
        $data['sum'] = $comment[0];
        $data['comments'] = $comment[1];

        //获取当前分类
        $data['node'] = \model\Node::findId($topic['nid']);// NodeDao::obj()->getId($content['node_id']);//$this->db->get_where('nodes', array('node_id' => $content['node_id']))->row_array();

        //上下主题
        //$data['content']['previous'] = $topicDao->get_near_id($topic_id, 0);
        //$data['content']['next'] = $topicDao->get_near_id($topic_id, 1);
        //$data['content']['previous'] = $data['content']['previous']['topic_id'];
        //$data['content']['next'] = $data['content']['next']['topic_id'];
        //先用假数据,稍后完善
        //$data['content']['previous'] = 2;
        //$data['content']['next'] = 2;


        //相关贴子
        if (isset($data['tags'])) {
            //$this->load->model('tag_m');
            //暂时不想弄
            $data['related_topic_list'] = [];//TagDao::obj()->get_related_topics_by_tag($data['tags'], 10);

        }
        //set top
        if (@$_GET['act'] == 'set_top') {
            if (Auth::$o->is_admin() || Auth::$o->is_master($topic['nid'])) {
                TopicDao::obj()->set_top($topic['id'], $topic['is_top']);
                redirect('topic/show/' . $topic['id']);
            }
            else {
                show_message('你无权置顶贴子');
            }
        }

        $data['page'] = $page;

        //获取分类
        $data['topic'] = $topic;
        $data['catelist'] = \model\Node::pid(0);

        $this->assign($data);

        $this->title($topic->title);
        $this->display('show');
    }

    public function add($nid=0) {

        $this->middle($this->isPost,'add',function ($new_topic_id){
            $conf = Conf::init();
            //审核未开启时
            if ($conf->is_approve == 'off') {
                $new_topic = \model\Topic::findId($new_topic_id);
                redirect($new_topic->url);
            }
            else {
                show_message('贴子通过审核才能在前台显示', site_url());
            }
        });

        $this->authLogin();

        $data['cate'] = \model\Node::findId($nid); //NodeDao::obj()->row($node_id);

        $data['title'] = '发表话题';

        $data['nodes'] = \model\Node::pid();

        //action
        $data['action'] = 'add';
        $data['nid'] = $nid;

        //开启storage config
        //$data['csrf_name'] = $this->security->get_csrf_token_name();
        //$data['csrf_token'] = $this->security->get_csrf_hash();

        $data['json'] = json_encode(\model\Tag::hot());

        $this->assign($data);
        $this->assign('topic',new Collection());
        //$this->display('edit');
        $this->display('write');
    }

    public function edit($id) {

        $this->middle($this->isPost,'edit',function ($new_topic_id){

        });

        $data['title'] = '编辑话题';

        $topic = \model\Topic::findId($id);
        $auth = Auth::init();

        //权限修改判断
        if($auth->notLogin) {
            $this->fail('请登录后再编辑!',3000,Conf::init()->loginUrl);
        }

        if($auth->permitEdit($topic) == false) {
            $this->fail('你无权修改此贴子!',3000);
        }

        $data['topic'] = $topic;

        $data['json'] = json_encode(\model\Tag::hot());

        $this->assign($data);
        $this->assign('topic',$topic);
        $this->display('edit');
    }


    public function del() {
        $this->middle(true,'del',function ($new_topic_id){

        });
        return;
        $data['title'] = '删除贴子';
        //权限修改判断
        if ($this->auth->is_admin() || $this->auth->is_master($node_id)) {

            //$this->myclass->notice('alert("确定要删除此话题吗！");');
            //删除贴子及它的回复
            if ($this->topic_m->del_topic($topic_id, $node_id, $uid)) {
                $this->load->model('comment_m');
                $this->comment_m->del_comments_by_topic_id($topic_id, $uid);
                //更新统计
                $this->db->set('value', 'value-1', false)->where('item', 'total_topics')->update('site_stats');
                $stats = $this->db->where('item', 'today_topics')->get('site_stats')->row_array();
                $value = is_today(@$stats['update_time']) ? 'value-1' : 0;
                $this->db->set('value', $value, false)->set('update_time', time(), false)->where('item', 'today_topics')->update('site_stats');
                //更新会员积分
                $this->config->load('userset');
                $this->load->model('user_m');
                $this->user_m->update_credit($uid, $this->config->item('credit_del'));
                //更新数据库缓存
                $this->db->cache_delete('/default', 'index');
                show_message('删除贴子成功！', site_url('/node/show/' . $node_id));
            }
        }
        else {
            show_message('您无权删除此贴', site_url('/topic/show/' . $topic_id));
        }
    }


}