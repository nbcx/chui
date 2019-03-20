<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/11 下午4:56
 */

namespace plugin\UEditor;


use controller\Upload;
use model\Conf;
use nb\driver\Request;

class Editor {

    public static function before(Upload $upload) {
        $form = Request::form();
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
        $js = $conf->pluginUrl.'ueditor/editor/ueditor.all.min.js';
        $configJs = $conf->pluginUrl.'ueditor/editor/ueditor.config.js';

        echo '<style type="text/css">
				body{
					/** 保留此规则使dialogs的某些组件文字可见 */
					color:#000 !important;
				}
				.typecho-label + p{overflow:hidden;}
			</style>';
        echo '</script><script type="text/javascript" src="' . $configJs . '"></script><script type="text/javascript" src="' . $js . '"></script>';
        echo '<script type="text/javascript">
                    var ue1 = new baidu.editor.ui.Editor();
                    window.onload = function() {
                        // 渲染
                        ue1.render("content");
                    }
            </script>';
        echo '<script type="text/javascript">
                  ue1.ready(function(){
                     //附件插入
                      //Typecho.insertFileToEditor = function (file, url, isImage) {
                      //   html = isImage ? "<img src=" + url + "  alt=" + file + "/>": "<a href=" + url + ">" + file + "</a>";
                      //    ue1.setContent(html, true);
                      //};
                  })
           </script>';
    }

}