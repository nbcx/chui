<?php
/*
 * This file is part of the NB Framework package.
 *
 * Copyright (c) 2018 https://nb.cx All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace service;

use util\Service;

/**
 * Plugin
 *
 * @package service
 * @link https://nb.cx
 * @author: collin <collin@nb.cx>
 * @date: 2019/3/26
 */
class Addon extends Service {

    public function activate() {
        $id = $this->input('id');

        $plugin = \model\Plugin::id($id);

        if(!$plugin->isHave) {
            $this->msg = '此插件不存在';
            return false;
        }

        if(!$plugin->isInstall) {
            $this->msg = '此插件还没有安装';
            return false;
        }

        if($plugin->isActivate) {
            $this->msg = '此插件已经是启用状态';
            return false;
        }

        $boot = $plugin->info->install?:"addon\\{$plugin->folder}\\Hook";
        $boot = new $boot();

        $msg = $boot->activate();

        \model\Plugin::updateId($id,['activate'=>1]);

        $this->msg = $msg?:"插件[{$plugin->info['name']}]启用成功！";
        $this->data = $plugin;
        return true;
    }

    public function deactivate() {
        $id = $this->input('id');
        $plugin = \model\Plugin::id($id);
        if(!$plugin->isHave) {
            $this->msg = '此插件不存在';
            return false;
        }

        if(!$plugin->isInstall) {
            $this->msg = '此插件还没有安装';
            return false;
        }

        if($plugin->isDeactivate) {
            $this->msg = '此插件已经是禁用状态';
            return false;
        }

        $boot = $plugin->info->install?:"addon\\{$plugin->folder}\\Hook";
        $boot = new $boot();

        $msg = $boot->deactivate();

        \model\Plugin::updateId($id,['activate'=>0]);

        $this->msg = $msg?:"插件[{$plugin->info['name']}]禁用成功！";
        $this->data = $plugin;
        return true;
    }




}