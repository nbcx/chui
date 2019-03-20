<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/22 上午8:05
 */
namespace util;

use nb\Config;
use nb\view\tag\Driver;
use util\Plugin;

class Label extends Driver {

    /**
     * 定义标签列表
     */
    protected $tags = [
        //前台模板标签
        'template'     => ['attr' => 'file,theme', 'close' => 0],
        'plugins'      => ['attr' => 'name,func,args,id', 'close' => 1],
        'plugin'       => ['attr' => 'name,func,args,return', 'close' => 1],
        //关键字
        'tags'         => ['attr' => 'do,num,page,sort,return', 'close' => 1],
        'tag'          => ['attr' => 'do,num,page,sort,return', 'close' => 1],
        //板块
        'nodes'        => ['attr' => 'do,num,page,sort,return', 'close' => 1],
        'node'         => ['attr' => 'do,num,page,sort,return', 'close' => 1],
        //帖子
        'topics'       => ['attr' => 'do,num,page,sort,return,nid', 'close' => 1],
        'topic'        => ['attr' => 'do,num,page,sort,return,nid', 'close' => 1],
        //评论
        'comments'     => ['attr' => 'do,num,page,sort,return,tid', 'close' => 1],
        'comment'      => ['attr' => 'do,num,page,sort,return,tid', 'close' => 1],

        //业务标签
        'navigation'   => ['attr' => 'name,type', 'close' => 1],
    ];


    public function tagTopic($tag, $content) {
        $method = $this->_topic($tag);
        return $this->_($tag,$content,$method);
    }

    public function tagTopics($tag, $content) {
        $method = $this->_topic($tag);
        return $this->_s($tag,$content,$method);
    }

    /**
     * 处理帖子Topic标签
     * do     执行的动作
     * num    返回的数量
     * page   第几页
     * sort   排序
     * return 接受返回值的变量名字
     * @param $tag
     * @return string
     * @throws \Exception
     */
    public function _topic($tag) {
        $do = isset($tag['do'])?$tag['do']:'id';
        switch ($do) {
            case 'id':
                $id = $tag['id'];
                $method = "\\model\\Topic::findId({$id})";
                break;
            case 'list':
                $num = isset($tag['num'])?$tag['num']:'10';
                $page = isset($tag['page'])?$tag['page']:'1';//id desc
                $sort = isset($tag['sort'])?$tag['sort']:'id desc';
                $method = "\\model\\Topic::more({$num},{$page},'{$sort}')";
                break;
        }
        return $method;
    }

    public function tagNode($tag, $content) {
        $method = $this->_node($tag);
        return $this->_($tag,$content,$method);
    }

    public function tagNodes($tag, $content) {
        $method = $this->_node($tag);
        return $this->_s($tag,$content,$method);
    }

    /**
     * 处理板块Node标签
     *
     * do     执行的动作
     * num    返回的数量
     * page   第几页
     * sort   排序
     * return 接受返回值的变量名字
     * @param $tag
     * @return string
     * @throws \Exception
     */
    public function _node($tag) {
        $do = isset($tag['do'])?$tag['do']:'id';
        switch ($do) {
            case 'id':
                $id = $tag['id'];
                $method = "\\model\\Node::findId({$id})";
                break;
            case 'list':
                $num = isset($tag['num'])?$tag['num']:'10';
                $page = isset($tag['page'])?$tag['page']:'1';//id desc
                $sort = isset($tag['sort'])?$tag['sort']:'id desc';
                $method = "\\model\\Node::more({$num},{$page},'{$sort}')";
                break;
        }
        return $method;
    }

    public function tagTag($tag, $content) {
        $method = $this->_tag($tag);
        return $this->_($tag,$content,$method);
    }

    public function tagTags($tag, $content) {
        $method = $this->_tag($tag);
        return $this->_s($tag,$content,$method);
    }

    /**
     * do     执行的动作
     * num    返回的数量
     * page   第几页
     * sort   排序
     * return 接受返回值的变量名字
     * @param $tag
     * @return string
     * @throws \Exception
     */
    public function _tag($tag) {
        $do = isset($tag['do'])?$tag['do']:'id';
        switch ($do) {
            case 'id':
                $id = $tag['id'];
                $method = "\\model\\Tag::findId({$id})";
                break;
            case 'list':
                $num = isset($tag['num'])?$tag['num']:'10';
                $page = isset($tag['page'])?$tag['page']:'1';//id desc
                $sort = isset($tag['sort'])?$tag['sort']:'id desc';
                $method = "\\model\\Tag::more({$num},{$page},'{$sort}')";
                break;
        }
        return $method;
    }

    /**
     * 执行插件里定义的方法
     * @param $tag
     *          name 插件名字
     *          func 插件方法
     *          args 插件方法参数
     *          return 接受返回值的变量名字
     * @param $content
     * @return string
     */
    public function tagPlugin($tag, $content) {
        if(isset($tag['name'])) {
            $name = $tag['name'];
        }
        else {
            throw new \Exception('tag plugins must have attr name');
        }
        $func = isset($tag['func'])?$tag['func']:'func';
        $args = isset($tag['args'])?$tag['args']:'';
        $return = isset($tag['return'])?$tag['return']:'data';
        $label = Plugin::parseInfo($name)->label;
        if(!$label) {
            return '';
        }
        if($label) {
            $method = "\\{$label}::{$func}({$args})";
        }
        else {
            $method = "\\plugins\\{$name}\\Label::{$func}({$args})";
        }
        $parse = '<?php ';
        $parse .= '$'.$return.' = '.$method.';';
        $parse .= ' ?>';
        $parse .= $content;
        return $parse;
    }

    /**
     * 执行插件里定义的方法，并对结果做volist处理
     * @param $tag
     *          name 插件名字
     *          func 插件方法
     *          args 插件方法参数
     *          id   作为volist的id参数
     * @param $content
     * @return string
     */
    public function tagPlugins($tag, $content) {
        if(isset($tag['name'])) {
            $name = $tag['name'];
        }
        else {
            throw new \Exception('tag plugins must have attr name');
        }
        $func = isset($tag['func'])?$tag['func']:'func';
        $args = isset($tag['args'])?$tag['args']:'';
        $id   = isset($tag['id'])?$tag['id']:'v';
        $label = Plugin::parseInfo($name)->label;
        if(!$label) {
            return '';
        }
        if($label) {
            $method = "\\{$label}::{$func}({$args})";
        }
        else {
            $method = "\\plugins\\{$name}\\Label::{$func}({$args})";
        }
        $parse = '<?php ';
        $parse .= '$__LIST__ = '.$method.';';
        $parse .= ' ?>';
        $parse .= '<volist name="__LIST__" id="' . $id . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }


    /**
     * empty标签解析
     * 如果某个变量为empty 则输出内容
     * 格式： {empty name="" }content{/empty}
     * @access public
     * @param array $tag 标签属性
     * @param string $content 标签内容
     * @return string
     */
    public function tagNavigation($tag, $content) {
        $name = $tag['name'];
        $name = $this->autoBuildVar($name);

        $parseStr = '<?php $nav = entity\Navigation::Lists(); ?>';
        $parseStr .= $content;
        return $parseStr;
    }


    /**
     * 树
     */
    public function tagTree($tag, $content) {
        $data = $tag['name'];
        $wrapTag = empty($tag['wrapTag'])?'ul':$tag['data'];
        $wrapClass = empty($tag['wrapClass'])?'':$tag['wrapClass'];
        $firstClass = empty($tag['firstClass'])?'':$tag['firstClass'];
        $itemTag = empty($tag['itemTag'])?'li':$tag['itemTag'];
        $itemClass = empty($tag['itemClass'])?'':$tag['itemClass'];
        $childName = empty($tag['childName'])?'children':$tag['childName'];
        $hrefName = empty($tag['hrefName'])?'permalink':$tag['hrefName'];
        $showName = empty($tag['showName'])?'title':$tag['showName'];

        //viewTree($data,$wrapTag='ul',$wrapClass='',$firstClass='',$itemTag='li',$itemClass='',$childName='children',$hrefName='permalink',$showName='title')
        $parse = '<?php ';
        $parse .= "viewTree($data,'{$wrapTag}','{$wrapClass}','{$firstClass}','{$itemTag}','{$itemClass}','{$childName}','{$hrefName}','{$showName}');";
        $parse .= ' ?>';
        return $parse;
    }


    /**
     * 模板包含标签
     * 格式：<admintemplate file="模块/控制器/模板名"/>
     * @param type $attr 属性字符串
     * @param type $content 标签内容
     * @return string 标签解析后的内容
     */
    public function tagTemplate($attr, $content) {
        $file = explode("/", $attr['file']);

        $module = Config::getx('module');
        $alias = Config::getx('app_alias');
        if($file[0] == $alias) {
            $file_path = str_replace($file[0],"application/view",$attr['file']);
        }
        if(in_array($file[0],$module)) {
            $file_path = str_replace($file[0],"module/$file[0]/view",$attr['file']);
        }

        //模板路径
        $path = __APP__ . $file_path . '.php';

        //判断模板是否存在
        if (is_file($path)) {
            //读取内容
            $parseStr = file_get_contents($path);
            //解析模板内容
            $this->tpl->parse($parseStr);
            return $parseStr;//$parseStr;
        }
        return '';
    }




    /**
     * 拼接语句
     * @param $tag
     * @param $content
     * @param $method
     * @return string
     */
    public function _($tag,$content,$method) {
        $return = isset($tag['return'])?$tag['return']:'data';
        $parse = '<?php ';
        $parse .= '$'.$return.' = '.$method.';';
        $parse .= ' ?>';
        $parse .= $content;
        return $parse;
    }

    /**
     * 拼接语句，并加上volist
     * @param $tag
     * @param $content
     * @param $method
     * @return string
     */
    public function _s($tag,$content,$method) {
        $return = isset($tag['return'])?$tag['return']:'data';
        $parse = '<?php ';
        $parse .= '$__LIST__ = '.$method.';';
        $parse .= ' ?>';
        $parse .= '<volist name="__LIST__" id="' . $return . '">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }
}