<?php
namespace controller\admin;

use util\Administrator;
use model\Node;

class Nodes extends Administrator  {

    public function index() {
        $data['title'] = '节点分类管理';
        $data['cates'] = Node::pid(0);// NodeDao::obj()->get_cates_by_pid($pid);
        $this->assign($data);
        $this->display();
    }

    public function del($node_id) {
        $data['title'] = '删除分类';
        //$this->myclass->notice('alert("确定再删除吗！");');
        NodeDao::obj()->del_cate($node_id);
        show_message('删除分类成功！', site_url('admin/nodes'), 1);
    }

    private function data_post($arr) {
        $permit = [];
        foreach ($arr as $key => $a) {
            if (preg_match("/^permit_\\d+$/i", $key)) {
                $permit[$key] = $a;
            }
        }
        if ($permit) {
            $permit = implode(',', $permit);
        }
        $str = $this->form('pid','cname','content','keywords','master');
        $str['content'] = cleanhtml($str['content']);
        $str['permit'] = $permit?:'';

        $ico = $this->input('ico');
        if($ico) {
            $str['ico'] = $ico;
        }
        return $str;
    }

    public function add() {
        $data['title'] = '添加分类';

        if ($_POST) {
            $str = $this->data_post($_POST);//引用
            NodeDao::obj()->add($str);
            show_message('添加分类成功', site_url('admin/nodes'), 1);
        }
        $pid = 0;
        $data['cates'] = NodeDao::obj()->get_cates_by_pid($pid);

        $data['group_list'] = GroupDao::obj()->group_list();
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->assign($data);
        $this->display('nodes_add');
    }

    public function move($node_id) {
        $data['title'] = '移动分类';
        if ($_POST) {
            $pid = $this->input('pid');
            NodeDao::obj()->move_cate($node_id, $pid);
            show_message('移动分类成功', site_url('admin/nodes'), 1);
        }
        $pid = 0;
        $data['node_id'] = $node_id;
        $data['cates'] = NodeDao::obj()->get_cates_by_pid($pid);
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display('nodes_move');
    }

    public function edit($node_id) {
        $data['title'] = '修改分类';
        //$nodeDao = NodeDao::obj();

        if ($_POST) {
            $str = $this->data_post($_POST);//引用
            if (Node::updateId($node_id, $str)) {//$nodeDao->update_cate($node_id, $str)
                show_message('修改分类成功', site_url('admin/nodes'), 1);
            }
            else {
                show_message('分类未做修改', site_url('admin/nodes'));
            }
        }

        $pid = 0;
        $data['cates'] = Node::pid($pid);//'$nodeDao->get_cates_by_pid($pid);
        $data['cateinfo'] = Node::findId($node_id);//$nodeDao->getId($node_id);
        $data['pcateinfo'] = Node::findId($data['cateinfo']['pid']);//$nodeDao->getId($data['cateinfo']['pid']);
        if ($data['cateinfo']['pid'] == 0) {
            $data['pcateinfo']['node_id'] = '0';
            $data['pcateinfo']['cname'] = '根目录';
        }

        //$data['cates']=$this->cate_m->get_cates_by_pid($node_id);
        $data['group_list'] = \model\Group::all();// GroupDao::obj()->group_list();
        $data['permit_selected'] = explode(',', $data['cateinfo']['permit']);
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display();
    }


}