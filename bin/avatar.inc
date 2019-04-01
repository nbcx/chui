<?php
/* 上传附件配置 */
return [
    'path' => __APP__.'uploads/',
    'url'  => \nb\Request::driver()->domain.'/uploads/',
    'field' => 'upfile',/* 提交的文件表单名称 */
    'name' => 'image/{yyyy}{mm}{dd}/{time}{rand:6}',/* 上传保存路径,可以自定义保存路径和文件名格式 */
    'urlPrefix' => '',/* 文件访问路径前缀 */
    'max' => 51200000,/* 上传大小限制，单位B，默认50MB */
    'allow' => [/* 上传文件格式显示 */
        ".png", ".jpg", ".jpeg", ".gif"
    ]
];