<?php 
class Fd_Object{
    
    protected function _objectByArray($array, $bypass_null_value = false){
        $return = new stdClass();
        foreach($array as $k => $v){
            if($bypass_null_value){
                if(is_null($v)){
                    continue;
                }
            }
            $return->$k = $v;
        }
        
        return $return;
    }
    
}
?>