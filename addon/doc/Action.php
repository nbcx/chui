<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/18 下午2:02
 */
namespace plugin\doc\controller;

use util\Controller;
use nb\Cache;
use plugin\doc\Wiki;

class Action extends Controller {

    public function index($id=1) {

        $wiki = Wiki::findId($id);

        $md = file_get_contents(__APP__.'plugin/doc/'.$wiki->path);
        $md = markdown($md);
        $this->assign('md',$md);

        $nav = Wiki::pid();
        $nav->current = $wiki;

        $this->assign('nav',$nav);
        $this->display('index');
    }

    public function alias($id=1) {

        $wiki = Wiki::find('alias=?',$id);

        $md = file_get_contents(__APP__.'plugin/doc/'.$wiki->path);
        $md = markdown($md);
        $this->assign('md',$md);

        $nav = Wiki::pid();
        $nav->current = $wiki;

        $this->assign('nav',$nav);
        $this->display('index');
    }

    public function test($id=1) {

        $wiki = Wiki::findId($id);

        $path = 'util/cache.md';
        $md = file_get_contents(__APP__.'plugin/doc/md/'.$path);
        $md = markdown($md);
        $this->assign('md',$md);

        $nav = Wiki::pid();
        $nav->current = $wiki;

        $this->assign('nav',$nav);
        $this->display('index');
    }

    public function cache() {

        Cache::set('yes',321);

        $get = Cache::get('yes',123);
        e($get);

        Cache::setx('yes2',function (){
            return '2yess#@';
        });
        $getx = Cache::getx('yes2',function (){
            return 'yes2';
        });
        e($getx);

        $t = new Sql('table');
        Sql::find();
    }


}