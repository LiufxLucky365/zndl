<?php
return array(
    // 平台版本号，每次更新平台都需要修改，防止缓存
    'VERSION' => '1.0.6.5',
    //'配置项'=>'配置值'
    'DB_TYPE'             => 'mysql', // 数据库类型
    'DB_HOST'             => '127.0.0.1', // 服务器地址
    'DB_NAME'             => 'zndl', // 数据库名
    'DB_USER'             => 'root', // 用户名
    'DB_PWD'              => '123', // 密码
    'DB_PORT'             => 3306, // 端口
    'DB_PREFIX'           => '', // 数据库表前缀
    'DEFAULT_FILTER'      => 'htmlspecialchars',
    'DEFAULT_AJAX_RETURN' => 'JSON',
    'TMPL_CACHE_ON'       => false,
    'ACTION_CACHE_ON'     => false,
    'HTML_CACHE_ON'       => false,

    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/public',
        '__UPLOAD__' => __ROOT__ . '/upload',
        '__TMP__'    => __ROOT__ . '/tmp',
        '__LIB__'    => __ROOT__ . '/public/lib',
        '__IMG__'    => __ROOT__ . '/public/img',
    ),
);
