<?php 
class Fd_Object_Factory extends Fd_Object implements Fd_Object_FactoryInterface{
    public static function getInstance(){
        if(Zend_Registry::isRegistered(get_called_class())){
            return Zend_Registry::get(get_called_class());
        }else{
            $str = get_called_class();
            $obj = new $str();
            Zend_Registry::set(get_called_class(), $obj);
            return $obj;
        }
    }
}
?>