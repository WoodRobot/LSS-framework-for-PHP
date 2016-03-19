<?php
final class Main {
    public static function run() {
        defined('DEBUG') || define('DEBUG', true);
        defined('SMARTY_ON') || define('SMARTY_ON', false);
        defined('CACHE_ON') || define('CACHE_ON', false);
        defined('CONSOLE_ON') || define('CONSOLE_ON', false);
        if(CONSOLE_ON){
             define('MEMORY_', memory_get_usage());
             define('TIMER', microtime(true));
        }
        error_reporting(0);
        self::_set_const(); //设置常量
        self::_creat_dir();//创建框架所需文件夹
        self::_import_file();//载入核心文件
        Application::run();//执行应用类
    }
    
    private static function _set_const() {
        //var_dump(__FILE__);
        $path = str_ireplace('\\','/',__FILE__);
        define('PHPDOF_PATH', dirname($path));
        define('CONFIG_PATH', PHPDOF_PATH . '/Config');
        define('DATA_PATH', PHPDOF_PATH . '/Data');
        define('LIB_PATH', PHPDOF_PATH . '/Lib');
        define('CORE_PATH', LIB_PATH . '/Core');
        define('FUNCTION_PATH', LIB_PATH . '/Function');
        //Extends 配置
        
        define('EXTENDS_PATH', PHPDOF_PATH . '/Extends');
        define('ORG_PATH', EXTENDS_PATH . '/Org');
        define('TOOL_PATH', EXTENDS_PATH . '/Tool');
        
        define('ROOT_PATH', dirname(PHPDOF_PATH));
        define('APP_PATH', ROOT_PATH . '/' . APP_NAME);
        define('APP_CONFIG_PATH', APP_PATH . '/Config');
        define('APP_CONTROLLER_PATH', APP_PATH . '/Controller' );
        define('APP_TPL_PATH', APP_PATH . '/Tpl' );
        define('APP_PUBLIC_PATH', APP_TPL_PATH . '/Public' );
        define('APP_MODEL_PATH', APP_PATH . '/Model');
        define('APP_INDEX_PATH', APP_TPL_PATH . '/index');
        define('APP_TEMP_PATH', APP_PATH . '/Temp');
        define('APP_COMPILE_PATH', APP_PATH .'/Temp/Compile');
        define('APP_CACHE_PATH', APP_PATH . '/Temp/Cache');
        define('LOG_PATH', PHPDOF_PATH . '/Log');
        define('PHPDOF_VERSION', 'PHPDOF 1.2 Beta');
        define('IS_POST', ($_SERVER['REQUEST_METHOD'] == 'POST') ? true :false);
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
            define('IS_AJAX',true);
        }else{
            define('IS_AJAX',false);
        }
        //创建公共
        define('COMMON_PATH', ROOT_PATH . '/Common');
        //公共配置项
        define('COMMON_CONFIG_PATH', COMMON_PATH . '/Config');
        //公共模型
        define('COMMON_MODEL_PATH', COMMON_PATH . '/Model');
        //公共库
        define('COMMON_LIB_PATH', COMMON_PATH . '/Lib');
    }
    //创建APP所需文件
    private static function _creat_dir() {
        $arr = array(
            APP_PATH,
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_TPL_PATH,
            APP_PUBLIC_PATH,
            APP_MODEL_PATH,
            LOG_PATH,
            APP_TEMP_PATH,
            APP_COMPILE_PATH,
            APP_CACHE_PATH,
            APP_INDEX_PATH,
            COMMON_PATH,
            COMMON_CONFIG_PATH,
            COMMON_MODEL_PATH,
            COMMON_LIB_PATH,
            EXTENDS_PATH,
            TOOL_PATH,
            ORG_PATH
        );
        foreach ($arr as $v) {
            is_dir($v) || mkdir($v, 0777, true);
        }
        
        is_file(APP_TPL_PATH . '/success.html') || copy(DATA_PATH . '/Tpl/success.html', APP_TPL_PATH . '/success.html');
        is_file(APP_TPL_PATH . '/error.html') || copy(DATA_PATH . '/Tpl/error.html', APP_TPL_PATH . '/error.html');
    }
    
    //载入框架所需文件
    private static function _import_file() {
        $fileArr = array(
            //smarty
            ORG_PATH . '/Smarty/Smarty.class.php',
            CORE_PATH . '/SmartyView.class.php',
            
            CORE_PATH . '/log.class.php',
            FUNCTION_PATH . '/function.php',
            CORE_PATH . '/Controller.class.php',
            CORE_PATH . '/Application.class.php',
            CORE_PATH . '/Model.class.php'
        );
        $str = '';
        foreach ($fileArr as $v){
            require_once $v;
        }
        
    }
    
}
Main::run();
?>