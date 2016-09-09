<?php
/**
 *
 *父类Controller
 *
 */
class Controller extends SmartyView{
    private $var = array();
    
    public function __construct() {
        header('Content-type:text/html;charset='.CHAR_SET);
        if(!SMARTY_ON){
            C('SMARTY_ON', false);
        }
        
		
		
        if(C('SMARTY_ON')){
            parent::__construct();
        }

        if(method_exists($this, '__init')) {
            $this -> __init();
        }
        if(method_exists($this, '__auto')) {
            $this -> __auto();
        }
        $_SESSION['error'] = ''; 
    }
    /**
     *[success 成功提示方法]
     *
     */
    protected function goAction ($url = NULL, $par = NULL) {
        $url =  __ROOT__.$_SERVER['PHP_SELF'].'?a='.$url;
        if(!empty($par)){
            foreach($par as $key => $value){
                $url .= '&'.$key.'='.$par[$key];
            }
        }
        header("Location:".$url);
        die;
    }

    /**
     *[success 成功提示方法]
     *
     */
    protected function success ($msg, $url = NULL, $time = 3) {
        $time = $time*1000;
        $url = $url ? $url : __ROOT__ . '/' . APP_NAME . '.php';
        include APP_TPL_PATH . '/success.html';
        die;
    }
    /**
     *[error 错误提示方法]
     *
     */
    protected function error ($msg, $url = NULL, $time = 3) {
        $time = $time*1000;
        $url = $url ? $url : 'back';
        include APP_TPL_PATH . '/error.html';
        die;
    }
    
    protected function get_tpl($tpl) {
        if(is_null($tpl)){
            $path = APP_TPL_PATH . '/' . CONTROLLER . '/' . ACTION . '.html';
        }else{
            $suffix = strrchr($tpl, '.');
            $tpl = empty($suffix) ? $tpl .'.html' : $tpl;
            $path = APP_TPL_PATH . '/' . CONTROLLER . '/' . $tpl;
        }
        return $path;
    }
    
    
    /**
     *
     *
     *
     */
    protected function display($tpl=NULL) {
         $path = $this -> get_tpl($tpl);
         
        if(!is_file($path)) halt($path . '模板文件不存在');
        if(C('SMARTY_ON')){
            parent::display($path);
        }else{
            extract($this -> var);
            include $path;
        }
    }
    
    protected function assign($var, $value) {
        if(C('SMARTY_ON')){
            parent::assign($var, $value);
        }else{
            $this -> var[$var] = $value;     
        }
        
    }
    
    
   
}
?>