<?php 
class Fd_Validate_Float extends Zend_Validate_Float{
    
    public function init($options = null){
        parent::init($options);
        $labels = Fd_Language_Labels::getInstance();
        foreach($this->getMessageTemplates() as $k => $v){
            
            $this->setMessage(Fd_Language_Labels::get(str_replace("'%value%'",'Field',$v)),$k);
        }
    }
}
?>