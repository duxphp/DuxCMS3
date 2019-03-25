<?php
$dir = dirname(__FILE__);
$comConfig = [];
//读取公共配置
$files = glob($dir . '/com/*.php');
foreach ($files as $file) {
    if (is_file($file)) {
        $array = include $file;
        $comConfig = array_merge($comConfig, (array)$array);
    }
}

$useConfig = [];
//读取其他配置
$files = glob($dir . '/use/*.php');
foreach ($files as $file) {
    if (is_file($file)) {
        $array = include $file;
        $useConfig = array_merge($useConfig, (array)$array);
    }
}

$useConfig = array_merge($comConfig, $useConfig);


//全局配置
$config = [
    //路由配置
    'dux.routes' => [
    ],
    'dux.module_default' => 'controller',
    //模块转发配置
    'dux.module' => [
        'controller' => 'c',
        'api' => 'a',
        'admin' => 's',
        'mobile' => 'm',
    ],
    //错误处理
    'dux.debug' => $useConfig['dux.use']['debug'],
    'dux.log' => $useConfig['dux.use']['log'],
    'dux.debug_browser' => $useConfig['dux.use']['debug_browser'],
    'dux.debug_key' => $useConfig['dux.use']['debug_key'],

    //服务相关
    'dux.service_key' => $useConfig['dux.use']['service_key'],

    //缓存配置
    'dux.cache' => $useConfig['dux.cache_driver'],

    //存储配置
    'dux.storage' => $useConfig['dux.storage_driver'],

    //模板设置
    'dux.tpl' => [
        'path' => ROOT_PATH,
        'cache' => $useConfig['dux.use']['tpl_cache'],
    ],

    //数据库配置
    'dux.database' => [
        'default' => $useConfig['dux.use_data'],
    ],

    //路由配置
    'dux.route' => [
        'params' => 'sale_code',
    ],

    //COOKIE
    'dux.cookie' => [
        'default' => [
            'pre' => $useConfig['dux.use']['cookie_pre'],
        ],
    ],

    //SESSION
    'dux.session' => [
        'default' => [
            'pre' => $useConfig['dux.use']['cookie_pre'],
            'time' => 172800,
            'cache' => $useConfig['dux.use']['session_cache'],
        ],
    ],

];

return array_merge($config, $useConfig);
