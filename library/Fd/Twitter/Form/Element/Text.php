<?php 
class Fd_Twitter_Form_Element_Text extends Zend_Form_Element_Text{
	
	public function init(){
		$this->setDecorators(Fd_Twitter_Form::$element_decorators);
	}

}
?>