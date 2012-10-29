<?php 
class Fd_Validate_File_ImageSize extends Zend_Validate_File_ImageSize{
    public function __construct($options){
        parent::__construct($options);
        $labels = Fd_Language_Labels::getInstance();
    
        foreach($this->_messageTemplates as $k => $v){
            $this->setMessage($labels->get($v), $k);
        }
    }
}
?>