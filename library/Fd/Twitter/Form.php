<?php 
class Fd_Twitter_Form extends Fd_Form {
    
	public $_old_container;
	
    
	public static $element_decorators = array(
            'ViewHelper',
            'Description',
            array('Errors', array('class' => 'alert-error', 'id' => 'field-error')),
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'span8')),
            array('Label', array('tag' => 'div', 'class' => 'span2')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'row-fluid')),
    );
	
	public static $base_decorators = array(
            array('Errors', array('class' => 'alert-error', 'id' => 'field-error')),
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'span8')),
            array('Label', array('tag' => 'div', 'class' => 'span2')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'row-fluid')),
    );
	
    public $button_decorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'span2')),
        array(array('label' => 'HtmlTag'), array('tag' => 'div', 'placement' => 'prepend')),
        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'row-fluid')),
    );
    
    protected $_id;
    protected $_container;
    protected $_custom_options;
    
	public function normal(){
		$submit = $this->getElement('submit');
		$submit->setAttrib("onClick", '');
		$submit->setAttrib("type",'submit');
	}
	
    public function __construct($options = null){
        if(isset($options)){
            if(isset($options['custom'])){
                $this->_custom_options = $options['custom'];
            }
            unset($options['custom']);
			if(isset($options['old_container'])){
                $this->_old_container = $options['old_container'];
            }
            unset($options['old_container']);
        }
        parent::__construct($options);
		
		//$this->element_decorators = self::$element_decorators;
    }
    
    public function init(){
        
        $this->_id = $this->getName() ? $this->getName() : rand(10000, 99999);
        $this->_container = $this->_old_container ? $this->_old_container : rand(10000, 99999);
        $this->setAttrib('id', $this->_id);
        if(!$this->getElement('submit')){
            $el = new Fd_Twitter_Form_Element_Button(array(
                    'name' => 'submit',
                    'label' => $this->_labels->get('Save')
            ));
            $el->setIgnore(true);
            $el->setOrder(99);
            $el->setType(Fd_Twitter_Form_Element_Button::BUTTON_CLASS_TYPE_PRYMARY);
            $el->setDecorators($this->button_decorators);
            if(isset($this->_custom_options['regular_form'])){
                $el->setAttrib('type','submit');
            }else{
                $el->setAttrib('type','button');
                $el->setAttrib('onClick','submitFormIntoContainer('.$this->_id.',"'.$this->_container.'")');
            }
            $this->addElement($el);
        }
        
		//disable autocomplete
		$this->setAttrib("autocomplete",'off');
    }
    
	
    public function getContainer(){
        return $this->_container;
    }
    public function getId(){
        return $this->_id;
    }
    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
                'FormElements',
                array(array('Inside'=>'HtmlTag'), array('tag' => 'div', 'class' => 'row-fluid')),
                'Form',
                array(array('Outside'=>'HtmlTag'), array('tag' => 'div', 'id' => $this->_container)),
        ));
    }
}
?>