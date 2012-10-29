
<?php 
class Fd_Validate_GreaterThan extends Zend_Validate_GreaterThan{
    
    public function init($options = null){
        parent::init($options);
        $labels = Fd_Language_Labels::getInstance();
        foreach($this->getMessageTemplates() as $k => $v){
            
            $this->setMessage(Fd_Language_Labels::get(str_replace("'%value%'",'Field',$v)),$k);
        }
    }
}
?>