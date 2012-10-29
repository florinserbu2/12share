<?php
class Fd_Twitter_Form_Element_Button extends Zend_Form_Element_Button{
	
    const BUTTON_CLASS_TYPE_DEFAULT = 'btn';
    const BUTTON_CLASS_TYPE_PRYMARY = 'btn btn-primary';
    const BUTTON_CLASS_TYPE_INFO = 'btn btn-info';
    const BUTTON_CLASS_TYPE_DANGER = 'btn btn-danger';
    const BUTTON_CLASS_TYPE_SUCCESS = 'btn btn-success';
    const BUTTON_CLASS_TYPE_WARNING = 'btn btn-warning';
    
    public $type;
    
    public function setType($type){
        $this->_type = $type;
        $this->setAttrib('class', $type);
    }
    
	/**
     * Constructor
     *
     * $spec may be:
     * - string: name of element
     * - array: options with which to configure element
     * - Zend_Config: Zend_Config with options for configuring element
     *
     * @param  string|array|Zend_Config $spec
     * @param  array|Zend_Config $options
     * @return void
     */
	public function __construct($spec, $options = null){
		parent::__construct($spec, $options);
		$this->setType(self::BUTTON_CLASS_TYPE_DEFAULT);
	}
}
	
?>