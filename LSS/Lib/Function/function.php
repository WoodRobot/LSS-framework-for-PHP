<?php
function halt($error, $level='ERROR', $type=3, $dest=NULL) {

    if(is_array($error)) {
        Log::write($error['message'], $level, $type, $dest);
    }else{
        Log::write($error, $level, $type, $dest);
    }
    
    //DEBUG开启后的操作
    $e = array();
    if(DEBUG) {
        if(!is_array($error)){
            $trace = debug_backtrace();
            $e['message'] = $error;
            $e['file'] = $trace[0]['file'];
            $e['line'] = $trace[0]['line'];
            $e['class'] = isset($trace[0]['class']) ? $trace[0]['class'] : '';
            $e['function'] = isset($trace[0]['function']) ? $trace[0]['function'] : '';
            ob_start(); //开启缓冲区
            debug_print_backtrace();
            $e['trace'] = htmlspecialchars(ob_get_clean());
        }else{
            $e = $error;
        }
    }else{
        if($url = C('ERROE_URL')){
            go($url);
        }else{
            $e['message'] = C('ERROR_MSG');
        }
    }

    include DATA_PATH . '/Tpl/halt.html';
    die;
}
function substr_cut($str_cut,$length)
{
    if (strlen($str_cut) > $length)
    {
        for($i=0; $i < $length; $i++)
        if (ord($str_cut[$i]) > 128)    $i++;
        $str_cut = mb_substr($str_cut,0,$i)."..";
    }
    return $str_cut;
}
function P ($arr, $b = false, $font = 'Microsoft YaHei', $size = '14px'){
    if(is_bool($b)){
        $t = $b;
    }
    if(is_bool($arr)){
        var_dump($arr);
    }else if(is_null($arr)){
        var_dump(NULL);
    }else{
        if($b){
            echo "<pre style = 'font-family: $font;margin:1px;padding:10px;border-radius:1px;background:#f5f5f5;border:1px solid #CCC;font-size:$size;font-weight:bold'>";
        }else{
            echo "<pre style = 'font-family: $font;margin:1px;padding:10px;border-radius:1px;background:#f5f5f5;border:1px solid #CCC;font-size:$size'>";
        }
        print_r($arr);
        echo '</pre>'; 
    }

}
function go($controller = 'index', $func = 'index', $param = '', $time=0, $msg='') {
	$url =  str_replace('/','',$controller.'.php'.'?a='.$func.$param);
	if($url != str_replace('/','',$_SERVER["REQUEST_URI"])){
		if(!empty($param)) $param = '&'.$param;
		if(!headers_sent()){
			$time == 0 ? header('Location:' .$url) : header("Refresh:{$time};url={$url}");
		}else{
			echo "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		}
	}
}


function C ($var = NULL, $value = NULL) {
    static $config = array();
    //合并数组,转换为大写
    if(is_array($var)){
        $config = array_merge($config,array_change_key_case($var, CASE_UPPER));
        return;
    }
    
    if(is_string($var)){
        $var = strtoupper($var);
        if(!is_null($value)){
            $config[$var] = $value;
            return;
        }
        return isset($config[$var]) ? $config[$var] : NULL;
    }
    
    if(@is_null($val) && @is_null($value)) {
        return $config;
    }

}


function print_const() {
    $const = get_defined_constants(true);
    p($const ['user']);
}


function M($table) {
            $obj = new Model($table);
            return $obj;
 }


function allTools() {
    $e = '';
    $d=dir(TOOL_PATH);
    $result = array();
    while(false !== ($e= $d->read())) {
        if(strpos($e, '.php')){
            $result[] = $e;
        }
    }
    $d->close();
    P($result);
}

function K($model) {
    $model .= 'Model';
    return new $model;
}

function jQueryLoad($version){
    $jQuery = __ORG__.'/jQuery/jquery-'.$version.'.min.js';
    echo "<script src = '$jQuery'></script>";
}
function BootStrapLoad($theme = false, $js = false){
    $bootstrapCSS =  __ORG__.'/Bootstrap/css/bootstrap.min.css';
    $bootstrapCSS_theme =  __ORG__.'/Bootstrap/css/bootstrap-theme.min.css';
    $bootstrapJS = __ORG__.'/Bootstrap/js/bootstrap.min.js';
    echo "<link rel='stylesheet' href='$bootstrapCSS'>";
    if($js){
        echo "<script src = '$bootstrapJS'></script>";
    }
    if($theme){
        echo "<link rel='stylesheet' href='$bootstrapCSS_theme'>";
    }

}

function appjsLoad($js = false){
    $appjsCSS =  __ORG__.'/APP.js/app.min.css';
    $appjsZepto =  __ORG__.'/APP.js/zepto.js';
    $appjsJS = __ORG__.'/APP.js/app.min.js';
    echo "<link rel='stylesheet' href='$appjsCSS'>";
    if($js){
        echo "<script src = '$appjsJS'></script>";
		echo "<script src = '$appjsZepto'></script>";
    }

}

function FlatLoad($bootstrap = false,$jquery = false, $video = false){
	$bootcss = __ORG__.'/Flat/css/vendor/bootstrap/css/bootstrap.min.css';
	$flatcss = __ORG__.'/Flat/css/flat-ui.min.css';
	$jquery = __ORG__.'/Flat/js/vendor/jquery.min.js';
	$vodeo = __ORG__.'/Flat/js/vendor/video.js';
	$faltjs = __ORG__.'/Flat/js/flat-ui.min.js';
	
	if($bootstrap){
		echo "<link rel='stylesheet' href='$bootcss'>";
	}
	if($jquery){
		echo "<script src = '$jquery'></script>";
	}
	if($video){
		echo "<script src = '$vodeo'></script>";
	}
	echo "<link rel='stylesheet' href='$flatcss'>";
	echo "<script src = '$faltjs'></script>";
}

function Alert($msg = ''){
	echo "<script>alert('$msg');</script>";
}

function recurse_copy($src,$des) {
    $dir = opendir($src);
    if(!is_dir($des)){
        mkdir($des);
    }
    while(false !== ( $file = readdir($dir)) ) {
             if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($src . '/' . $file) ) {
                            recurse_copy($src . '/' . $file,$des . '/' . $file);
                    }  else  {
                            copy($src . '/' . $file,$des . '/' . $file);
                    }
             }
      }
      closedir($dir);
   }


?>