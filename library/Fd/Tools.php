<?php 
class Fd_Tools extends Fd_Object{
    
    public static function kv($array, $key_name = 'id', $val_name = "name"){
        $ret = array();
        foreach($array as $v){
        	if(array_key_exists($v->$key_name, $ret)){
        		if(is_array($ret[$v->$key_name])){
        			$ret[$v->$key_name][] = $v->$val_name;
        		}else{
        			$aux = $ret[$v->$key_name];
        			$ret[$v->$key_name] = array($aux, $v->$val_name);
        		}
        	}else{
            	$ret[$v->$key_name] = $v->$val_name;
        	}
        }
        return $ret;
    }
    
	public static function dbRows($input){
		$output = array();
		foreach($input as $row){
			$aux = new stdClass();
			foreach($row as $column => $value){
				$aux->$column = $value;
			}
			$output[] = $aux;
		}
		return $output;
	}
	public static function dbRow($input){
		
		$aux = new stdClass();
		if($input){
			foreach(@$input as $column => $value){
				$aux->$column = $value;
			}
		}
	
		return $aux;
	}
	
	public static function clearUTF($s)
	{
		setlocale(LC_ALL, 'en_US.UTF8');
		
	    $r = '';
	    $s1 = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
	    for ($i = 0; $i < strlen($s1); $i++)
	    {
	        $ch1 = $s1[$i];
	        $ch2 = mb_substr($s, $i, 1);
	
	        $r .= $ch1=='?'?$ch2:$ch1;
	    }
	    return $r;
	}
	
	public static function getUrlSeparator($type){
		switch($type){
			case "options":
				return "_".Fd_Language_Labels::get("or")."_";
				break;
			case "groups":
				return "_".Fd_Language_Labels::get("and")."_";
				break;
			case "group-option":
				return "_".Fd_Language_Labels::get("is")."_";
				break;
		}	

	}
	
	public function removeValueFromArray($value, $array){
		$new = array();
		foreach($array as $v){
			if($v == $value){
				continue;
			}
			$new[] = $v;
		}	
		return $new;
	}
}
?>