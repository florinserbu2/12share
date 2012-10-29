<?php 
abstract class Fd_ManagerAbstract implements Fd_ManagerInterface{
    
    protected $_row;
    protected $_table;
    protected $_entity_name;
    protected $_grid;
    protected $_id; 

    
    protected $_db_errors = array(); 
    protected $_errors = array(); 
    
    
    public abstract function getEntityName();
    protected abstract function _getEntityTable();
    
    public function __construct($id = null){
    	
		$config = Zend_Registry::get('config');
		$params = $config['resources']['db']['params'];
		$link = mysql_connect($params['host'], $params['username'], $params['password']);
		mysql_select_db($params['dbname']);
		
        $this->_table = $this->_getEntityTable();
        $this->_entity_name = $this->getEntityName();
        if(isset($id)){
            $this->_row = $this->getRow($id);
            $this->setId($id);
        }
    }
    public function hasRow(){
    	return $this->_row ? true : false;
    }
    public function getErrors(){
        if(!empty($this->_db_errors) || !empty($this->_errors)){
            return $this->_db_errors + $this->_errors;
        }
		
        return false;
    }
     
    public function setId($id){
        $this->_id = $id;
    }
    
	public function emptyRow(){
       	unset($this->_id);
       	unset($this->_row);
    }
	
    public function getRow($id = null){
        if(@$this->_row && !isset($id)){
            return $this->_row;
        }
       
        if(!@$this->_id && !$id){
            throw new Fd_Exception('No id for getRow'); 
        }
        
        if(!$this->_table){
            throw new Fd_Exception('No table for getRow');
        }
        $row = Fd_Tools::dbRow($this->_table->fetchRow("id = ".mysql_real_escape_string($id ? $id : $this->_id)));
		if($row){
			 if($id){
	            $this->setId($id);
	         }
			 $this->_row = $row;
		}
		return $row;
    }
    
    public function setRow($id = null){
        if(!isset($id)){
           throw new Fd_Exception('No id for setRow');
        }
        
        
        
        $this->_row = $this->_table->fetchRow("id = ".$id);
		if($this->_row){
			$this->setId($id);
		}else{
			throw new Fd_Exception("No row found");
		}
    }
    public function getAll($where = null){
        return Fd_Tools::dbRows($this->_table->fetchAll($where));
    }
    
    public function grid($url, $title, $container = ''){
        $class = get_called_class();
        $class_name = str_replace("_Manager", "_Grid", $class);

        $grid_gui = new $class_name($url, $title);
        $this->_grid = $grid_gui;
         
        return $grid_gui;
    }
    
   
    public function edit($values){
		
		if(!$this->_table){
            throw new Fd_Exception("No table specified for ManagerAbstract in update");
        }
		unset($values['submit']);
		try{
			if($this->_row->id){
				return $this->_table->update($values, "id = {$this->_row->id}");
			}else{
				return $this->_table->insert($values);
			}
		}catch(Zend_Db_Exception $e){
			$this->_db_errors[] = $e;
		}
	}
	
	public function sanitize(&$value){
			
		
		if(is_array($value)){
			foreach($value as $k => &$v){
				$this->sanitize($v);
			} 
		}else{
			$value = is_string($value) ? mysql_real_escape_string($value) : $value;
		}
		
	}
	
	public function delete(){
		if($this->_row){
			return $this->_table->delete("id = {$this->_row->id}");
		}else{
			throw new Fd_Exception("No row for delete");
		}
		return false;
	}
}
?>