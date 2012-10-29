<?php class Fd_Twitter_Form_Element_Number extends Fd_Form_Element_Text{
	
	public $dependancies;
	public $button_ids;
	
	
    public $element_decorators = array('NumberSlider');
   
    public function init()
    {
    	  $this->element_decorators += Fd_Twitter_Form::$base_decorators;
          $this->addPrefixPath("Fd_Twitter_Form_Decorator", FD_LIB_PATH.'/Fd/Twitter/Form/Decorator','decorator');
          $this->setDecorators($this->element_decorators);
		  $this->setValue(1);
    }
    
}
