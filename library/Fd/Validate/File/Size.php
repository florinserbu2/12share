<?php 
class Fd_Validate_File_Size extends Zend_Validate_File_Size{
    
    public function __construct($max, $min = null){
        parent::__construct($min ? array('max' => $max, "min" => $min) : $max);
        $labels = Fd_Language_Labels::getInstance();
      
       foreach($this->_messageTemplates as $k => $v){
           $this->setMessage($labels->get($v), $k);
       }
    }
    
}
?>