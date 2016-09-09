<?php
define('APP_NAME','Index'); //定义APP名称，对应根目录下目录名
define('DEBUG', true);//是否开启调试模式，关闭后错误信息将会被隐藏
define('SMARTY_ON', true);//是否使用Smarty模板引擎
define('CACHE_ON', false);//是否开启缓存
define('CONSOLE_ON', false);//是否开启框架控制台
require 'LSS/Main.php';//引入主框架
?>