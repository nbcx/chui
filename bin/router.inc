<?php
return [
    'admin' => [
        'url' => '/admin',
        'action' => 'admin/dashboard/index'
    ],
    'login' => [
        'url' => '/login',
        'action' => 'login'
    ],
    'logout' => [
        'url' => '/logout',
        'action' => 'login/out'
    ],
    'register' => [
        'url' => '/register',
        'action' => 'register'
    ],
    'resetpwd' => [
        'url' => '/resetpwd',
        'action' => 'gateway/findpwd'
    ],
    'show' => [
        'url' => '/topic/show/[topic_id:digital]',
        'action' => 'topic/show'
    ],
    'node_show' => [
        'url' => '/node/show/[node_id:digital]',
        'action' => 'node/show'
    ],
    'msg_show' => [
        'url' => '/message/[id:digital]',
        'action' => 'message/show'
    ],
    'profile' => [
        'url' => '/user/[uid:digital]',
        'action' => 'user/profile'
    ],
];