<?php 
class Fd_Language_Labels extends Fd_Object_Factory{
    
    
    public static function get($key, $language_id = null){
        $labels = self::getInstance();
        return $labels->getKey($key);
    }
    
    public function getKey($key){
    		return $key;
    }
    
   
    
    protected function __construct($categories = null){
		
    }
}
?>
