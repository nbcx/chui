<?php
namespace controller;

use util\Controller;

class Tag extends Controller  {

    public function index($page = 1) {
        $rows = 30;

        $data['title'] = "标签列表";
        $tags = \model\Tag::page($rows, $page);
        $data['num'] = $tags[0];
        $data['tags'] =  $tags[1];

        $data['action'] = 'tag';
        $this->assign($data);
        $this->display('tag/index');
    }

    public function show($name, $page = 1) {

        $data['title'] = urldecode(strip_tags($name));
        //分页
        $limit = 10;

        $tag = \model\Tag::find('name=?',$data['title']);
        if(!$tag) {
            show_message('标签不存在', site_url());
        }
        $data['tag'] = $tag;

        $topic = \model\Topic::byTag($data['title'],$limit,$page);
        $data['topics'] = $topic[1];//$this->tag_m->get_tag_topics_list($start, $limit, $data['title']);
        $this->assign($data);
        $this->display('tag/show');

    }

    public function autocomplete($query=null) {
        $tags['query'] = 'Unit';
        $tags['suggestions'][] = [
            'value' => $query.'0001',
            'data' => $query.'01'
        ];
        $tags['suggestions'][] = [
            'value' => $query.'0002',
            'data' => $query.'002'
        ];
        $tags['suggestions'][] = [
            'value' => $query.'ert3',
            'data' => $query.'tet3'
        ];
        $tags['suggestions'][] = [
            'value' => $query.'ert4',
            'data' => $query.'ert4'
        ];
        $this->json($tags);
    }


}
