<?php
namespace plugin;

use util\Common;
use util\Db;
use util\Widget;
use widget\interfaces\Doo;

class Action extends Widget implements Doo {

    private $db;
    private $options;
    private $prefix;

    public function insertLink() {
        if (Links::form('insert')->validate()) {
            $this->response->goBack();
        }
        /** 取出数据 */
        $link = $this->request->from('name', 'url', 'sort', 'image', 'description', 'user');
        $link['order'] = $this->db->fetchObject($this->db->select(['MAX(order)' => 'maxOrder'])->from($this->prefix . 'links'))->maxOrder + 1;

        /** 插入数据 */
        $link['lid'] = $this->db->query($this->db->insert($this->prefix . 'links')->rows($link));

        /** 设置高亮 */
        $this->widget('logic\Notice')->highlight('link-' . $link['lid']);

        /** 提示信息 */
        $this->widget('logic\Notice')->set(_t('链接 <a href="%s">%s</a> 已经被增加',
            $link['url'], $link['name']), NULL, 'success');

        /** 转向原页 */
        $this->response->redirect(Common::url('extending.php?panel=Links%2Fmanage-links.php', $this->options->adminUrl));
    }

    public function addHannysBlog() {
        /** 取出数据 */
        $link = [
            'name' => "Hanny's Blog",
            'url' => "http://www.imhan.com",
            'description' => "寒泥 - Typecho插件开发者",
        ];
        $link['order'] = $this->db->fetchObject($this->db->select(array('MAX(order)' => 'maxOrder'))->from($this->prefix . 'links'))->maxOrder + 1;

        /** 插入数据 */
        $link['lid'] = $this->db->query($this->db->insert($this->prefix . 'links')->rows($link));

        /** 设置高亮 */
        $this->widget('logic\Notice')->highlight('link-' . $link['lid']);

        /** 提示信息 */
        $this->widget('logic\Notice')->set(_t('链接 <a href="%s">%s</a> 已经被增加',
            $link['url'], $link['name']), NULL, 'success');

        /** 转向原页 */
        $this->response->redirect(Typecho_Common::url('extending.php?panel=Links%2Fmanage-links.php', $this->options->adminUrl));
    }

    public function updateLink() {
        if (Links::form('update')->validate()) {
            $this->response->goBack();
        }

        /** 取出数据 */
        $link = $this->request->from('lid', 'name', 'sort', 'image', 'url', 'description', 'user');

        /** 更新数据 */
        $this->db->query($this->db->update($this->prefix . 'links')->rows($link)->where('lid = ?', $link['lid']));

        /** 设置高亮 */
        $this->widget('Widget_Notice')->highlight('link-' . $link['lid']);

        /** 提示信息 */
        $this->widget('Widget_Notice')->set(_t('链接 <a href="%s">%s</a> 已经被更新',
            $link['url'], $link['name']), NULL, 'success');

        /** 转向原页 */
        $this->response->redirect(Typecho_Common::url('extending.php?panel=Links%2Fmanage-links.php', $this->options->adminUrl));
    }

    public function deleteLink() {
        $lids = $this->request->filter('int')->lid;
        $deleteCount = 0;
        if ($lids && is_array($lids)) {
            foreach ($lids as $lid) {
                if ($this->db->query($this->db->delete($this->prefix . 'links')->where('lid = ?', $lid))) {
                    $deleteCount++;
                }
            }
        }
        /** 提示信息 */
        $this->widget('logic\Notice')->set($deleteCount > 0 ? _t('链接已经删除') : _t('没有链接被删除'), NULL,
            $deleteCount > 0 ? 'success' : 'notice');

        /** 转向原页 */
        $this->response->redirect(Common::url('extending.php?panel=Links%2Fmanage-links.php', $this->options->adminUrl));
    }

    public function sortLink() {
        $links = $this->request->filter('int')->lid;
        if ($links && is_array($links)) {
            foreach ($links as $sort => $lid) {
                $this->db->query($this->db->update($this->prefix . 'links')->rows(array('order' => $sort + 1))->where('lid = ?', $lid));
            }
        }
    }

    public function action() {
        $this->db = Db::get();
        $this->prefix = $this->db->getPrefix();
        $this->options = Widget::widget('logic\Options');
        $this->on($this->request->is('do=insert'))->insertLink();
        $this->on($this->request->is('do=addhanny'))->addHannysBlog();
        $this->on($this->request->is('do=update'))->updateLink();
        $this->on($this->request->is('do=delete'))->deleteLink();
        $this->on($this->request->is('do=sort'))->sortLink();
        $this->response->redirect($this->options->adminUrl);
    }
}
