<?php

final class Application {
    public static function run () {
        //初始化框架
        self::_init();
        /**配置错误捕捉
         *set_error_handler 自定义错误处理 
         *register_shutdown_function 自定义致命错误处理
         */
        set_error_handler(array(__CLASS__, 'error'));
        register_shutdown_function(array(__CLASS__, 'fatal_error'));
        //载入用户扩展
        self::_user_import();
        //载入APP Model
        self::_user_model_import();
        //配置外部路径
        self::_set_url();
        spl_autoload_register(array(__CLASS__, '_autoload'));
        self::_creat_demo();
        self::_app_run();
        //输出控制台信息
        if(CONSOLE_ON){
            $etime = microtime(true);//获取程序执行结束的时间  
            $total = $etime - TIMER;   //计算差值
            if(!empty($_SERVER['HTTP_REFERER'])){
                $rer = $_SERVER['HTTP_REFERER'];
            }else{
                $rer = "No Referer";
            }
            echo '<br><br><br>';
            p("控制台:", true);
            $temp = "ScriptName:".$_SERVER['PHP_SELF']."  Host:".$_SERVER['HTTP_HOST']."  FileName:".$_SERVER['SCRIPT_FILENAME']."<hr>"."Language:".$_SERVER['HTTP_ACCEPT_LANGUAGE']."  ".$_SERVER['SERVER_PROTOCOL']."<hr>"."Referer:".$rer."  Query:".$_SERVER['QUERY_STRING']."<hr>"."Head:".$_SERVER['HTTP_ACCEPT'];
            p($temp);
            $temp = memory_get_usage() - MEMORY_;
            $mb = $temp/1000;
            $mb = $mb/1024;
            
            p("Runtime:$total".' Seconds.'."  Memory:".$temp." byte(s)  [$mb MB].");
        }
    }
	
    
    private static function _init () { //初始化框架
        //加载配置项
        C(include CONFIG_PATH . '/Config.php');
        //家在公共配置项
        $commonPath = COMMON_CONFIG_PATH .'/Config.php';
        $commonConfig = <<<str
<?php
return array(
    //自动加载Common/Lib目录下文件，支持多个文件
    'AUTO_LOAD_FILE' => array(),
    //数据库信息
    'DB_CHARSET' => 'utf8',
    'DB_HOST' => 'localhost',
    'DB_PORT' => '3306',
    'DB_USER' => 'root',
    'DB_PWD' => '',
    'DB_DATEBASE' => '',
    'DB_PREFIX' => ''
    );
?>
str;
        is_file($commonPath) || file_put_contents($commonPath, $commonConfig);
        C(include $commonPath);
        //加载用户配置项
        $userPath = APP_CONFIG_PATH . '/Config.php';
        $userConfig = <<<str
<?php
return array(
    //'AUTO_LOAD_FILE' => '',
    //'AUTO_LOAD_MODEL' => ''
    );
?>
str;
        is_file($userPath) ||  file_put_contents($userPath, $userConfig);
        //加载用户配置项
        C(include $userPath);
        date_default_timezone_set(C('DEFAULT_TIME_ZONE'));
        //是否开启SESSION
        C('SESSION_AUTO_STATRT') && session_start();
        
    }
    
    //设置外部路径
    private static function _set_url () {
        //p();
        $path = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $path = str_replace('\\', '/', $path);
        define('__APP__', $path);
        define('__ROOT__', dirname($path));
        define('__TPL__', __ROOT__ . '/' . APP_NAME . '/Tpl');
        define('__PUBLIC__', __TPL__ . '/Public');
        define('__TOOL__', __ROOT__ . '/PHPDof/Extends/Tool');
    }
    
    //自动载入功能 魔术方法_autoload
    private static function _autoload ($className) {
        switch(true) {
            //判断是否为控制器
            case strlen($className) > 10 && substr($className, -10) == 'Controller':
                $path = APP_CONTROLLER_PATH . '/' . $className .'.class.php';
                if(!is_file($path)){
                    $emptyPath = APP_CONTROLLER_PATH . '/EmptyController.class.php';
                    if(is_file($emptyPath)){
                        include $emptyPath;
                        return;
                    }else{
                       halt($path . '&nbsp;Controller was not found!'); 
                    }
                    
                }
                include $path;
                break;
            case strlen($className) > 9 && substr($className, -9) == 'UserModel';
                $path = APP_MODEL_PATH . '/' . $className . '.class.php';
                include $path;
                break;
            
            case strlen($className) > 5 && substr($className, -5) == 'Model':
                $path = COMMON_MODEL_PATH . '/' . $className . '.class.php';
                include $path;
                break;
            default:
                $path = TOOL_PATH . '/' . $className . '.class.php';
                if(!is_file($path)) halt($path . '&nbsp;Class was not found!');
                include $path;
                break;
            }
        }
        
    
    
    
    private static function _creat_demo () {
        $path = APP_CONTROLLER_PATH . '/IndexController.class.php';
        $str = <<<str
<?php
class IndexController extends Controller{
    /**
     *
     *执行构造方法__init() or __auto()
     *
     */
    public function __init () {
        //Construct is running!
    }
    public function index () {
        header('Content-type:text/html;charset=utf-8');
        echo "<style>body{font-family: Microsoft YaHei;}</style>";
        echo "<h1 style='font-size:110px'>:)</h1><h3 style='font-size:25px'>PHPDof is ready!</h3>";
        \$model = new MessageModel;
        \$data = \$model -> run();
        
        \$usermodel = new MessageUserModel;
        \$data = \$usermodel -> run();
        
        if(CONSOLE_ON){
            P('The Console is opened!');
        }else{
            P('The Console is off!');
        }
        
        if(SMARTY_ON){
            P('Smarty is running!',true);
        }else{
            P('Smarty is off!',true);
        }
        
        echo "<h3>Finded tools:</h3>";
        allTools();
        echo "<br><span style='font-size:12px'>PHPDOF Version." . PHPDOF_VERSION ." </span>";
    
        /**
        
        if(!\$this -> is_cached()){
            \$this -> assign('var', time());
        }
        \$this -> display();
        
        
        //or no smarty
        
        
        \$this -> assign('var', time());
        \$this -> display();
        
        
        */
    }
}
?>
str;

        is_file($path) || file_put_contents($path, $str);
        
        $path = APP_MODEL_PATH . '/MessageUserModel.class.php';
        $str = <<<str
<?php
class MessageUserModel extends Model {
    //测试UserModel
    public function run() {
        P('The UserModel is running!');
    }
}
?>
str;
        is_file($path) || file_put_contents($path, $str);
        
        $path = COMMON_MODEL_PATH . '/MessageModel.class.php';
        $str = <<<str
<?php
class MessageModel extends Model {
    //测试CommonModel
    public function run() {
        P('The CommonModel is running!');
    }
}
?>
str;
        is_file($path) || file_put_contents($path, $str);
        
        $path = APP_INDEX_PATH . '/index.html';
        $str = <<<str
<!DOCTYPE html>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>PHPDof Index</title>
</head>
<body>
Hello,World!
</body>
</html>
str;
        is_file($path) || file_put_contents($path, $str);
        
        
        
        
        
    }
    
    
    
    
    //实例化应用控制器
    private static function _app_run () {
        $c = isset($_GET[C('VAR_CONTROLLER')]) ? $_GET[C('VAR_CONTROLLER')] : 'Index';
        $a = isset($_GET[C('VAR_ACTION')]) ? $_GET[C('VAR_ACTION')] : 'Index';
        define('CONTROLLER', $c);
        define('ACTION', $a);
        $c .= 'Controller';
        //方法或控制器不存在处理
        if(class_exists($c)){
            $obj = new $c();
            if(!method_exists($obj, $a)){
                //是否自定义错误提示方法__empty
                if(method_exists($obj, '__empty')){
                    $obj -> __empty();
                }else{
                    halt($a . ' was not found in ' . $c);
                }
            }else{
                $par = array();
                $times = 0;
                foreach($_GET as $key => $value) {
                    if($times != 0){
                      $par [$times] = $value;
                    }
                    if($key != 'prm'){
                     $times++;
                    }
                }
                //判断是否需要加载多参数 注:多参数必须静态调用
                if(!empty($_GET['prm'])){
                    call_user_func_array("$c::$a",$par);
                }else{
                    $obj -> $a();
                }
                

            }
        }else{
            $obj = new EmptyController();
            $obj -> index();
        }
        

    }
  
  
     
     
     
     
    private static function _user_import () {
        $fileArr = C('AUTO_LOAD_FILE');
        if(is_array($fileArr) && !empty($fileArr)) {
            foreach ($fileArr as $v){
                require_once COMMON_LIB_PATH . '/' . $v;
            }
        }
    
    }
    
    private static function _user_model_import () {
        $fileArr = C('AUTO_LOAD_MODEL');
        if(is_array($fileArr) && !empty($fileArr)) {
            foreach ($fileArr as $v){
                require_once APP_MODEL_PATH . '/' . $v;
            }
        }
    
    }
    
    public static function error($errno, $error, $file, $line) {
        switch($errno){
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $msg = $error .  '<br>' . $file . ' ' . " in {$line}";
                halt($msg);
                break;  
                
            case E_STRICT:
            case E_USER_WARNING:
            case E_USER_NOTICE:
            default:
                if(DEBUG) {
                    include DATA_PATH . '/Tpl/notice.html';
                }
                break;
        }
    }
    
    public static function fatal_error() {
        if($e = error_get_last()){
            self::error($e['type'], $e['message'], $e['file'] ,$e['line']);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
?>