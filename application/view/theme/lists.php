<style>
    tr td img {
        max-width: 100%;
        max-height: 160px;
    }
    .theme {
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        color: #444;
        line-height: 1.5;
    }
    .theme tr.active {
        background: #FFF9E8;
    }
    .theme td {
        margin: 1em 0;
        vertical-align: top;
    }
    .theme .descr {

    }
    .theme tr>td>img {
        width: 100%;
    }
    .theme h3 {
        display: block;
        -webkit-margin-before: 1em;
        -webkit-margin-after: 1em;
        -webkit-margin-start: 0px;
        -webkit-margin-end: 0px;
        font-weight: bold;
        font-size: 1.40em;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    }
    .theme cite {
        font-style: normal;
        color: #999;
    }
    .theme a {
        color: #467B96;
        text-decoration: none;
    }
</style>
<table class="fancy fullwidth theme">
    <thead>
    <tr>
        <th style="width:280px;">截图</th>
        <th>详情</th>
    </tr>
    </thead>
    <tbody>
    <volist name="themes" id="v">
        <tr {:$v.isActivate?'class="active"':''}>
            <td><img src="{$v.screenshot}" alt="jianshu"></td>
            <td class="descr">
                <h3>{$v.info.name}</h3>
                <cite>作者: <a href="{$v.info.link}" target="_blank">{$v.info.author}</a> &nbsp;&nbsp; 版本: {$v.info.version} </cite>
                <p>{$v.info.description}</p>
                <p>
                    <if condition="$v.isInstall">
                        <if condition="!$v.isActivate">
                            <a theme-pjax class="edit" href="{$v.install}">卸载</a> &nbsp;
                            <a theme-pjax class="edit" href="#">编辑</a> &nbsp;
                            <a theme-pjax class="activate" href="{$v.activate}">启用</a>
                        </if>
                    <else />
                        <a theme-pjax class="edit" href="{$v.install}">安装</a> &nbsp;
                    </if>
                </p>
            </td>
        </tr>
    </volist>
    <!--
    <tr style="background:#FFF9E8;">
        <td><img src="https://chenxiong.cx/usr/themes/jianshu/screenshot.png" alt="jianshu"></td>
        <td class="descr">
            <h3>Typecho Replica Theme</h3>
            <cite>作者: <a href="http://typecho.org" target="_blank">Typecho Team</a> &nbsp;&nbsp; 版本: 1.2 </cite>
            <p>这是 Typecho 0.9 系统的一套默认皮肤</p>
            <p>
                <a class="edit" href="https://chenxiong.cx/admin/theme-editor.php?theme=default">编辑</a> &nbsp;
                <a class="activate" href="https://chenxiong.cx/action/themes-edit?change=default&amp;_=6403faff2ad9a00f285927c944f8630c">启用</a>
            </p>
        </td>
    </tr>
    <tr>
        <td><img src="https://chenxiong.cx/usr/themes/jianshu2/screenshot.png" alt="jianshu2"></td>
        <td>
            <h3>JianShu</h3>
            <cite>作者: <a href="http://lixianhua.com" target="_blank">绛木子</a> &nbsp;&nbsp;版本: 1.1.0</cite>
            <p>仿简书主题</p>
            <p>
                <a class="edit" href="https://chenxiong.cx/admin/theme-editor.php?theme=jianshu2">编辑</a> &nbsp;
                <a class="activate" href="https://chenxiong.cx/action/themes-edit?change=jianshu2&amp;_=6403faff2ad9a00f285927c944f8630c">启用</a>
            </p>
        </td>
    </tr>
    <tr class="odd">
        <td><img src="https://chenxiong.cx/usr/themes/default/screenshot.png" alt="default"></td>
        <td>
            <h3>Kibou</h3>
            <cite>作者: <a href="http://www.metheno.net" target="_blank">metheno</a> &nbsp;&nbsp;版本: 0.2.3</cite>
            <p>这位博主，请开始你的写作。</p>
            <p>
                <a class="edit" href="https://chenxiong.cx/admin/theme-editor.php?theme=kibou">编辑</a> &nbsp;
                <a class="activate" href="https://chenxiong.cx/action/themes-edit?change=kibou&amp;_=6403faff2ad9a00f285927c944f8630c">启用</a>
            </p>
        </td>
    </tr>
    -->
    </tbody>
</table>