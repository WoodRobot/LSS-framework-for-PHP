<?php
class SmartyView {
    private static $smarty = NULL;
    public function __construct() {
        if(!is_null(self::$smarty)) return;
        $smarty = new Smarty();
        //配置模板目录
        $smarty -> template_dir = APP_TPL_PATH . '/' . CONTROLLER . '/';
        //编译处理
        $smarty -> compile_dir = APP_COMPILE_PATH;
        //缓存
        $smarty -> cache_dir = APP_CACHE_PATH;
        //定界符
        $smarty -> left_delimiter = C('LEFT_DELIMITER');
        $smarty -> right_delimiter = C('RIGHT_DELIMITER');
        //是否开启缓存
        if(DEBUG){
        }else{
            $smarty-> debugging = false;
        }
        
        if(!CACHE_ON){
            $smarty -> caching = false;
        }else{
            $smarty -> caching = C('CACHE_ON');
        }
        
        $smarty -> cache_lifetime = C('CACHE_TIME');
        self::$smarty = $smarty;
    }
    
    protected function display($tpl) {
        self::$smarty -> display($tpl, $_SERVER['REQUEST_URI']);
    }
    
    protected function assign($var, $value){
        self::$smarty -> assign($var, $value);
    }
    
    protected function is_cached($tpl=NULL) {
        if(!C('SMARTY_ON')) halt('Smarty is off!');
        $tpl = $this -> get_tpl($tpl);
        return self::$smarty -> is_cached($tpl, $_SERVER['REQUEST_URI']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
?>