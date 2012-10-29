<?php 
class Zend_View_Helper_Tlt extends Zend_View_Helper_Abstract
{
    public function tlt($key, $to_upper = false)
    {
        
        $labels = Fd_Language_Labels::getInstance();
        return $to_upper ? ucfirst($labels->getKey($key)) : $labels->getKey($key);
    }
}
?>