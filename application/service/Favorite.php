<?php
namespace service;

use nb\Service;
use util\Auth;
use nb\Middle;

class Favorite extends Service {


    public function add() {

        $topic_id = $this->input('topic_id');

        //获取收藏数据
        $uid = Auth::init()->id;

        $topic = \model\Topic::findId($topic_id);

        if($topic->author->id === $uid) {
            $this->fail = '不能收藏自己发表的帖子';
            return false;
        }

        $favorite = \model\Favorite::find('uid=?',$uid);

        if($favorite == false) {
            $data['content'] = $topic_id;
            $data['total'] = 1;
            $data['uid'] = $uid;
            \model\Favorite::insert($data);
        }
        else if ($favorite['content']) {
            $ids_arr = explode(",", $favorite['content']);
            if (!in_array($topic_id, $ids_arr)) {
                array_unshift($ids_arr, $topic_id);
                //$topics = count($ids_arr);
                $content = implode(',', $ids_arr);

                $id = $favorite['id'];
                \model\Favorite::updateId($id,'total=total+1,content=?',$content);
            }
            unset($ids_arr);
        }
        else {
            $data['content'] = $topic_id;
            $data['total'] = 1;

            $id = $favorite['id'];
            \model\Favorite::updateId($id,$data);
        }

        \model\User::updateId($uid,'favorites=favorites+1');
        \model\Topic::updateId($topic_id,'favorites=favorites+1');

        $this->success = $topic_id;
        return true;

    }

    public function del() {
        $topic_id = $this->input('topic_id');

        //获取收藏数据
        $uid = Auth::init()->id;
        $favorite = \model\Favorite::find('uid=?',$uid);

        if($favorite == false) {
            $this->fail = '不能删除没有收藏的收藏';
            return false;
        }

        $ids_arr = explode(",", $favorite['content']);
        if (!in_array($topic_id, $ids_arr)) {
            $this->fail = '不能删除没有收藏的收藏';
            return false;
        }

        foreach ($ids_arr as $k => $v) {
            if ($v == $topic_id) {
                unset($ids_arr[$k]);
                break;
            }
        }
        $content = implode(',', $ids_arr);
        $id = $favorite['id'];
        \model\Favorite::updateId($id,'favorites=favorites-1,content=?',$content);
        \model\User::updateId($uid,'favorites=favorites-1');
        \model\Topic::updateId($topic_id,'favorites=favorites-1');
        $this->success = $topic_id;
        return true;
    }


}
