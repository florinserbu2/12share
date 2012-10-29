<?php 
class Fd_Validate_File_IsImage extends Zend_Validate_File_IsImage{
    
    public function __construct($mimetype = null){
        parent::__construct($mimetype);
        $labels = Fd_Language_Labels::getInstance();
      
        foreach($this->_messageTemplates as $k => $v){
           $this->setMessage($labels->get($v), $k);
       }
    }
    
}
?>