<?php
/*
 * This file is part of the NB Framework package.
 *
 * Copyright (c) 2018 https://nb.cx All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace controller;

use model\Attach;
use model\System;
use nb\Collection;
use nb\Config;
use util\Auth;
use util\Controller;

/**
 * Posted
 *
 * @package controller
 * @link https://nb.cx
 * @author: collin <collin@nb.cx>
 * @date: 2018/9/14
 */
class Posted extends Controller {

    public function index($id=0,$nid=0) {
        $auth = Auth::init();
        //权限修改判断
        if($auth->notLogin) {
            $this->fail('请登录后再编辑!',3000,Config::$o->loginUrl);
        }

        //已选标签
        $tags = [];

        if($id) {
            $topic = \model\Topic::findId($id);
            if($auth->permitEdit($topic) == false) {
                $this->fail('你无权修改此贴子!',3000);
            }
            $this->title('编辑帖子-'.$topic->title);
            $data['nid'] = $topic->nid;
            $data['action'] = $topic->editPost;
            foreach ($topic->tags as $v) {
                $tags[] = $v['name'];
            }
        }
        else {
            $this->title('发布帖子');
            $topic = new Collection();
            $data['nid'] = $nid;
            $data['action'] = System::init()->postedPost;
            //应检测用户是否有发帖的权限
        }

        $data['attach'] = Attach::usable($auth->id,$id,'topic');

        $data['nodes'] = \model\Node::pid();
        $data['topic'] = $topic;

        //备选标签
        $data['candidateTags'] = array_diff(\model\Tag::hot(),$tags);
        $data['tags'] = $tags;

        $this->assign($data);
        $this->display('write');
    }

    public function post() {
        $this->authLogin();

        $run = \service\Topic::run('publish',function ($msg) {
            $this->fail($msg);
        });
        $conf = System::init();
        //审核未开启时
        if ($conf->is_approve == 'off') {
            redirect($run->data->url);
        }
        else {
            $this->success('贴子通过审核才能在前台显示','/');
        }
    }

    public function del() {

    }




}