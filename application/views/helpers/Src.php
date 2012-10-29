<?php 
class Zend_View_Helper_Src extends Zend_View_Helper_Abstract
{
    public function src($url,$size = '150x150', $for = 'default.gif')
    {
        $config = Zend_Registry::get('config');
	    $static = $config['static'];
        if(is_file(str_replace($static['url'],$static['path'],$url))){
        	return $url;
        }else{
        	
	        return trim($static['url'],"/")."/uploads/defaults/{$size}/{$for}";
        }
    }
}
?>