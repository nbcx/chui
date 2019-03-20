﻿<h1>启用的插件</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>

<table class="normal tablesorter fullwidth">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>版本</th>
        <th>作者</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <volist name="enables" id="v">
            <tr>
                <td>{$v.info.name}</td>
                <td>{$v.info.description}</td>
                <td>{$v.info.version}</td>
                <td><a target="_blank" href="{$v.info.link}">{$v.info.author}</a></td>
                <td>
                    <if condition="$v.configUrl">
                        <a pjax-content href="{$v.configUrl}">设置</a> •
                    </if>
                    <if condition="$v.overall">
                        <a pjax-content href="{$v.userUrl}">用户</a> •
                    </if>
                    <a ajax href="{$v.deactivateUrl}">禁用</a>
                    <!--
                    <a href="#" title="Edit this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Pencil.png" alt="" /></a>
                    <a href="#" title="Preferences" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Preferences.png" alt="" /></a>
                    <a href="#" title="Delete this user" class="tooltip table_icon"><img src="_pub_img/icons/actions_small/Trash.png" alt="" /></a>
                    -->
                </td>
            </tr>
        </volist>
    </tbody>
</table>
<!--
<a href="presentation.html" class="button_link" >Back to index</a>
-->
<hr />

<h1>禁用的插件</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>

<table class="fancy fullwidth">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>版本</th>
        <th>作者</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <volist name="unenables" id="v">
            <tr>
                <td>{$v.info.name}</td>
                <td>{$v.info.description}</td>
                <td>{$v.info.version}</td>
                <td><a target="_blank" href="{$v.info.link}">{$v.info.author}</a></td>
                <td>
                    <if condition="$v.isInstall">
                        <if condition="$v.uninstallConf">
                            <a dialog-form href="{$v.uninstallUrl}">卸载</a>
                            <else />
                            <a pjax-content href="{$v.uninstallUrl}">卸载</a>
                        </if>
                        <if condition="$v.configUrl">
                            <a pjax-content href="{$v.configUrl}">设置</a>
                        </if>
                        <a ajax href="{$v.activateUrl}">启用</a>
                    <else />
                        <a href="{$v.del}">删除</a>
                        <if condition="$v.installConf">
                            <a dialog-form href="{$v.installUrl}" title="插件安装:{$v.info.name}">安装</a>
                            <a dialog-form href="{$v.installActivateUrl}">安装并启用</a>
                        <else />
                            <a ajax href="{$v.installUrl}">安装</a>
                            <a ajax href="{$v.installActivateUrl}">安装并启用</a>
                        </if>
                    </if>
                </td>
            </tr>
        </volist>
    </tbody>
</table>