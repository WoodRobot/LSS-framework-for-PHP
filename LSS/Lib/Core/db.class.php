<?php
class DB {
	protected $sql;
	//配置数据库信息
	protected $user = NULL;
	protected $pwd = NULL;
	protected $db = NULL;
	protected $table = NULL;
	protected $db_host = NULL;
	protected $frefix = NULL;
	protected $charset = NULL;
    protected $first;
	// define -------------------------------------

    function __construct($table = NULL) {
		if(empty($table)){
			halt('数据库实例化错误,未找到数据表');
		}
		$this -> table = $table;
		$this -> user = C('DB_USER');
	    $this -> pwd = C('DB_PWD');
		$this -> db = C('DB_DATEBASE');
		$this -> frefix = C('DB_PREFIX');
		$this -> charset = C('DB_CHARSET');
		$this -> db_host = C('DB_HOST');
        $this -> first = 1;
        $this -> sql = $this -> db_con();
    }
	// function safe construct-------------------------------------
    function setTable($table){
    	$this -> table = $table;
    }
	function is($value){
		if(is_int($value)){
			return 'INT';
		}
		if(is_string($value)){
			return 'STRING';
		}
		if(is_array($value)){
			return 'ARRAY';
		}
		if(is_bool($value)){
			return 'BOOL';
		}
		if(is_null($value)){
			return 'STRING';
		}
		return '';
	}
	// function is -------------------------------------

	function safe($sql, $location, $var, $type, $b = false, $cl = true){
		switch($type){
			default:
			    $var = addslashes($var);
			    $var = addcslashes($var,'(.)./.-.=.;.*.,');
				if($cl) $var = "'".$var."'";
				break;
			case 'STRING':
			    $var = addslashes($var);
			    $var = addcslashes($var,'(.)./.-.=.;.*.,');
				if($cl) $var = "'".$var."'";
			    break;
			case 'INTEGER':
			    $var = (int)$var;
			    break;
			case 'ARRAY':
				break;
			case 'BOOL':
			    if($type){$val = 1;}else{$val = 0;}
			case 'INT':
			    $var = (int)$var;
			    break;
		 }// switch
		if($b == true){
			 return $var;
		}
		for ($i=1; $i <= $location; $i++){
			$pos = strpos($sql, '?');
		}// for
		$sql = substr($sql, 0, $pos) .$var .substr($sql, $pos+1);
		return $sql;
	}
	// function safe --------------------------------------

	function db_con() {
    	$dsn = 'mysql:host = '.$this -> db_host.';dbname='.$this -> db;
    	$db = new PDO($dsn, $this -> user , $this -> pwd, array(PDO::ATTR_PERSISTENT => true));
		$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //设置可捕获异常
		$db -> query("SET NAMES ".$this -> charset);
	    $db -> query("set interactive_timeout=24*3600");
		return $db;
	}
	// function iSql_con -------------------------------------

	function sql($value = array(),$return = true,$sw = true) {
		if(CONSOLE_ON){
			$GLOBALS['DB_LOG']['sql'] = $GLOBALS['DB_LOG']['sql'] + 1;
		}
		if(empty($value)){
    		return false;
		}
		$u = count($value);
		if($sw == true){
		@$this -> sql -> beginTransaction();
	}


	if($return == false){

		try{
			for ($m=0; $m<$u; $m++){
				$j = substr_count($value [$m],'[');
				for ($i=1; $i <= $j; $i++){
    		    	$o = strpos($value [$m], ']')+1;
					$p1 = strpos($value [$m], '[')+1;
					$p2 = $o - $p1 - 1;
					$head =  substr($value [$m], 0, $p1-1);
					$foot =  substr($value [$m],  $o, strlen($value [$m]));
					$val =  substr($value [$m], $p1, $p2);
					$var = $this -> safe('','',$val,$this -> is($val),true,false);
					$value [$m] = $head.$var.$foot;
				}// for
			    $res = @$this -> sql -> query($value [$m]);
			}
	    	if($sw == true) {@$this -> sql -> commit();}
	    	return true;
	    }catch(PDOexception $err){
			if($sw == true) {@$this -> sql -> rollBack();}
	        return false;
	    }

	}else{

		try{

			for ($m=0; $m<$u; $m++){
				$j = substr_count($value [$m],'[');
				for ($i=1; $i <= $j; $i++){
					
    		    	$o = strpos($value [$m], ']')+1;
					$p1 = strpos($value [$m], '[')+1;
					$p2 = $o - $p1 - 1;
					$head =  substr($value [$m], 0, $p1-1);
					$foot =  substr($value [$m],  $o, strlen($value [$m]));
					$val =  substr($value [$m], $p1, $p2);
					$var = $this -> safe('','',$val,$this -> is($val),true,false);
					$value [$m]= $head.$var.$foot;
				}// for
				$re = @$this -> sql -> query($value [$m]);
				$res [$m] = $re -> fetchAll(PDO::FETCH_ASSOC);
			}
	    	if($sw == true){@$this -> sql -> commit();}
	    	return $res;
	    }catch(PDOexception $err){
	        if($sw == true){@$this -> sql -> rollBack();}
	        return false;
	    }


	}



	}
	// function sql --------------------------------------

	function insert($data = array()){
		if(CONSOLE_ON){
			$GLOBALS['DB_LOG']['write'] = $GLOBALS['DB_LOG']['write'] + 1;
		}
		$db = $this -> frefix . $this -> table;
		$val = "INSERT INTO $db VALUES (";
		for($i = 0; $i < count($data); $i++){
    		$val = $val."?,";
		}
		$val = substr($val,0,strlen($val)-1);
		$val = $val.")";
		for($i = 0; $i < count($data); $i++){
			$sha1 = strpos($data[$i],'/safe');
			$nosafe = strpos($data[$i],'/nosafe');
			str_replace($data[$i],'/nosafe','');
			if($sha1){
					$val = $this -> safe($val, 1,sha1($data[$i]), $this -> is($data[$i]));
			}else{
					$val = $this -> safe($val, 1,$data[$i], $this -> is($data[$i]));
			}

		}
		try{
			//echo $val.'<br>';
			$res = $this -> sql -> query($val);
			return true;
		}catch(PDOexception $err){
			return false;
		}

	}
	// function insert --------------------------------------

	function del($data = array()){
		if(CONSOLE_ON){
			$GLOBALS['DB_LOG']['delete'] = $GLOBALS['DB_LOG']['delete'] + 1;
		}
		$db = $this -> frefix . $this -> table;
		$val = "DELETE FROM $db where ";
		$times = 0;
		for($i = 0; $i < count($data); $i++){
			if(@$times == 0){
				@$val = $val.$data[$i].' = ?&&';
				$times = 2;
			}
			$times--;
		}
    	$val = substr($val,0,strlen($val)-2);
		for($i = 0; $i < count($data)/2; $i++){
    		$val = $this -> safe($val, 1, $data[$i+$i+1], $this -> is($data[$i+$i+1]));
		}

		try{
			$res = @$this -> sql -> query($val);
			return true;
		}catch(PDOexception $err){
			return false;
		}
	}
	function iSql_cleanSession(){
		session_destroy();
	}
	// function iSql_del --------------------------------------

	function select($data = array(), $where = array(), $cache = false, $param = ''){
		if(CONSOLE_ON){
			$GLOBALS['DB_LOG']['read'] = $GLOBALS['DB_LOG']['read'] + 1;
		}
		$db = $this -> frefix . $this -> table;
		if(!empty($data)){
			$times = 1;
			$val = "SELECT ";
			for($i = 0; $i < count($data); $i++){
				if(@$times == count($data)){
					@$val = $val.$data[$i];
				}else{
					@$val = $val.$data[$i].',';
				}
				$times++;
			}
			@$val .= " FROM $db";
		}else{
			$val = "SELECT * FROM $db";
		}
		
		if(empty($where)){
			if($cache == true){
				if(!isset($_SESSION['sql_cache'][$val])){
				    $res = @$this -> sql -> query($val.' '.$param) -> fetchAll(PDO::FETCH_ASSOC);
					$_SESSION['sql_cache'][$val] = $res;
					return $res;
				}else{
					return $_SESSION['sql_cache'][$val];
				}
			}else{
				$res = @$this -> sql -> query($val.' '.$param) -> fetchAll(PDO::FETCH_ASSOC);
				$_SESSION['sql_cache'][$val] = $res;
				return $res;
			}
		}
		$val .= ' where ';
		$times = 0;
		for($i = 0; $i < count($where); $i++){
			if(@$times == 0){
			@$val = $val.$where[$i].' = ?&&';
			$times = 2;
			}
			$times--;
		}
		
        $val = substr($val,0,strlen($val)-2);
		for($i = 0; $i < count($where)/2; $i++){
    		$val =  $val = $this -> safe($val, 1, $where[$i+$i+1], $this -> is($where[$i+$i+1]));
		}
		try{
			if($cache == true){
				if(!isset($_SESSION['sql_cache'][$val])){
					$res = @$this -> sql -> query($val.' '.$param) -> fetchAll(PDO::FETCH_ASSOC);
					$_SESSION['sql_cache'][$val] = $res;
					return $res;
				}else{
					return $_SESSION['sql_cache'][$val];
				}
			}else{
				$res = @$this -> sql -> query($val.' '.$param) -> fetchAll(PDO::FETCH_ASSOC);
				return $res;
			}
		}catch(PDOexception $err){
			return false;
		}
	}
	// function iSql_search --------------------------------------

	function update($set = array(),$data = array()){
		if(CONSOLE_ON){
			$GLOBALS['DB_LOG']['write'] = $GLOBALS['DB_LOG']['write'] + 1;
		}
		$db = $this -> frefix . $this -> table;
		for($i = 0,$times = 0,$s = "UPDATE $db "; $i < count($set); $i++){
			if($times == 0){
			  if($i == 0){$s = $s.' SET '.$set[$i].' = ?,';}else{$s = $s.$set[$i].' = ?,';}
				$times = 2;
			}
			$times--;
		}
		$s = substr($s,0,strlen($s)-1);
		for($i = 0; $i < count($set)/2; $i++){
			$sha1 = strpos($set[$i+$i+1],'/safe');
			if($sha1){
				$s = $this -> safe($s, 1,sha1($set[$i+$i+1]), $this -> is($set[$i+$i+1]));
			}else{
				$s = $this -> safe($s, 1, $set[$i+$i+1], $this -> is($set[$i+$i+1]));
			}
		}
		
		if(!empty($data)){
			$val = $s." where ";
			for($i = 0; $i < count($data); $i++){
				if(@$times == 0){
					@$val = $val.$data[$i].' = ?&&';
					$times = 2;
				}
				$times--;
			}
			$val = substr($val,0,strlen($val)-2);
			for($i = 0; $i < count($data)/2; $i++){
				 $val = $this -> safe($val, 1, $data[$i+$i+1], $this -> is($data[$i+$i+1]));
			}
		}else{
			$val = $s;
		}

		
		
		try{
    		$res = @$this -> sql -> query($val);
			return true;
		}catch(PDOexception $err){
			return false;
		}
	}
	// function iSql_edit --------------------------------------


}//class End

?>