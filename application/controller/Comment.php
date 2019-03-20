<?php
namespace controller;

use nb\Collection;
use util\Controller;
use nb\utility\Obj;

class Comment extends Controller {

    public function index($topic_id) {
        $this->authLogin();

        $data['comment'] = new Collection();
        $data['topic'] = \model\Topic::findId($topic_id);
        $data['title'] = '发表回贴';
        $data['action'] = '/comment/add';
        $this->assign($data);
        $this->display('comment');
    }

    public function post() {
        $this->authLogin();

        $run = \service\Comment::run('publish',function ($msg){
            $this->fail($msg);
        });

        $comment = \model\Comment::findId($run->data);
        $this->success($run->msg,$comment->topic->url,30);
    }

    public function add($topic_id) {

        $this->middle($this->isPost,'add',function ($data){
            $this->json($data);
        });

        $this->authLogin();

        $data['comment'] = Obj::ins();
        $data['topic'] = \model\Topic::findId($topic_id);
        $data['title'] = '发表回贴';
        $data['action'] = '/comment/add';
        $this->assign($data);
        $this->display('comment');
    }

    //删除回复
    public function del() {
        \service\Comment::run('del',function ($fail) {
            $this->fail($fail);
        });
        $this->success('删除成功！');
    }


    //编辑回复
    public function edit($id) {

        $this->middle($this->isPost,'edit',function ($data){
            $this->json($data);
        });

        $comment = \model\Comment::findId($id);

        if($comment->isEdit == false) {

            show_message('非本人或管理员或本版块版主不能操作', $comment->topic->url);
        }

        //$content = $this->input('content');
        //$data['comment']['content'] = br2nl($data['comment']['content']);
        //$data['comment']['content'] = $content ? $content : $data['comment']['content'];
        //$data['comment']['node_id'] = $node_id;


        $data['title'] = '编辑回贴';
        $data['comment'] = $comment;
        $data['topic'] = $comment->topic;
        $data['action'] = '/comment/edit?id='.$id;

        $this->assign($data);
        $this->display('comment');

    }


}
