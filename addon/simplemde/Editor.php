<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/11 下午4:56
 */

namespace plugin\simplemde;


use controller\Upload;
use model\Conf;

class Editor {

    public static function before(Upload $upload) {
        $form = Request::get();
        $action = $form['action'];
        switch ($action) {
            case 'config':
                $result = load('ueditor');
                break;
            /* 上传图片 */
            case 'uploadimage':
                $result = $upload->image();
                break;
                /* 上传涂鸦 */
            case 'uploadscrawl':
                $result = $upload->crawler();
                break;
                /* 上传视频 */
            case 'uploadvideo':
                $result = $upload->video();
                break;
                /* 上传文件 */
            case 'uploadfile':
                $result = $upload->attachment();
                break;
            /* 列出图片 */
            case 'listimage':
                $result = include("upload_list.php");
                break;
            /* 列出文件 */
            case 'listfile':
                $result = include("upload_list.php");
                break;
            /* 抓取远程文件 */
            case 'catchimage':
                $result = include("upload_crawler.php");
                break;

            default:
                $result = [
                    'state' => '请求地址出错'
                ];
                break;
        }
        echo json_encode($result);
    }

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render() {
        $conf = Conf::init();
        $css = $conf->pluginUrl.'simplemde/dist/simplemde.min.css';
        $js = $conf->pluginUrl.'simplemde/dist/simplemde.min.js';

        echo '<link rel="stylesheet" href="'.$css.'" /><script type="text/javascript" src="' . $js . '"></script>';
        echo '<script type="text/javascript">
            try{
              document.getElementById("resolver").value="markdown";  
            }
            catch (e) {
              
            }
        	var simplemde = new SimpleMDE({ element: document.getElementById("content") });
	        </script>';
    }

    public static function insert($data) {
        $data['resolver'] == 'markdown' and $data['content'] = markdown($data['content']);
        b($data['resolver'],$data['content']);
        b('sign','je');
        return $data;
    }

}