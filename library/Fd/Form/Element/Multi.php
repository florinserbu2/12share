<?php class Fd_Form_Element_Multi extends Zend_Form_Element_Multi{
	/**
	 * The popover title
	 */
	protected $_desc_title;

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
	
}
