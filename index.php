<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG', true);
define('NO_CACHE_RUNTIME', true);
define('APP_NAME', 'app');
define('APP_PATH', './protected/app/');
define('PUBLIC_PATH', './public');
define('RUNTIME_PATH', './protected/Runtime/');

// 引入ThinkPHP入口文件
require './protected/ThinkPHP/ThinkPHP.php';