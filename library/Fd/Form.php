<?php 
class Fd_Form extends Zend_Form{
    
    protected $_labels;
    
    public function getElement($name){
    	if($main_element = parent::getElement($name)){
    		return $main_element;
    	}
		foreach($this->getSubForms() as $sub_form){
			if($element = $sub_form->getElement($name)){
				return $element;
			}
		}
		
		return null;
    }
	
    
    public function __construct($options = null){
       $this->addElementPrefixPath("Fd", FD_LIB_PATH.'/Fd');
       
       $this->_labels = Fd_Language_Labels::getInstance();
       parent::__construct($options);
    }
	
	/**
	 * Extends getValue from Zend_Form parrent
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function getValue($name) {
		$ret = parent::getValue ( $name );
		
		return self::sanitize($ret);
	}
	/**
	 * Extends getValues from Zend_Form parrent
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function getValues($skip_sanitize = false) {
		$ret = parent::getValues ();
		
		$new_ret = array ();
		foreach ( $ret as $key => $val ) {
			$new_ret [$key] = self::sanitize($val, $skip_sanitize);		
		}
		
		return $new_ret;
	}
	
	public static function sanitize($val, $skip_escape = false){
			if (! is_array ( $val )) {
				return $skip_escape ? $val : mysql_real_escape_string (( $val ) );
			} else {
				$vals = array();
				foreach($val as $k => $v){
					$vals[$k] = self::sanitize($v, $skip_escape);
				}
				return $vals;
			}
	}
}
?>