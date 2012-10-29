<?php class Fd_Form_Element_Text extends Zend_Form_Element_Text{
	/**
	 * The popover title
	 */
	protected $_desc_title;

	/**
	 * the flag for multiplication property
	 * if it's true, then the input can be cloned into the form in 
	 * order to have more than one entry for the same field
	 */
	protected $_can_multiply;

	/** this is a flag used by the date fields
	 * 	to have a date range if it's set
	 */
	public $date_range;

	public function setDesc($desc, $title = null) {
		$this -> setDescription($desc);
		$this ->_desc_title = $title;
	}
	
	public function getDesc(){
		$ret = new stdClass();
		$ret->desc = $this->getDescription();
		$ret->title = $this->_desc_title;
		
		return $ret;
	}
	
	public function canMultiply($flag = null){
		if(is_bool($flag)){
			$this->_can_multiply = $flag;
		}
		return $this->_can_multiply;
		
	}
}
