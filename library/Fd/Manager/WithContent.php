<?php 
abstract class Fd_Manager_WithContent extends Fd_ManagerAbstract{
    
    
    abstract protected function _getContentTable();
    
    protected $_table_content;
    protected $_column_name;
    
    public function __construct($id = null){
        $this->_table_content = $this->_getContentTable();
        if(!$this->_table_content){
            throw new Fd_Exception('no uploadable content table defined for '.get_called_class().' constructor');
        }
        parent::__construct($id);
        $this->_column_name = $this->_getColumnName();
    }
    
    public function getUploadedContent($id = null){
        if(!@$this->_row->id && !$id){
            throw new Fd_Exception("No main id defined for WithImages on getUploadedContent");
        }
        if(!$this->_row->id && $id){
            $this->_id = $id;
        }
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(array("uc" => "tbl_inv_uploaded_content"),array(
                "id",
                "file_name",
                "file_type",
                "file_size",
                "created_timestamp"
                ));
        $select->joinInner(array("c" => $this->_table_content->getName()), "uc.id = c.content_id",array());
        
        $select->group("uc.id");
        
        $select->where("c.{$this->_column_name} = {$this->_row->id}");
        return $db->fetchAll($select);
    }
    
    /**
     * Generates from entity_name, the column name
     * !important have the getEntityName() method defined in child class
     * @throws Fd_Exception
     * @return String
     */
    protected function _getColumnName(){
        if(!$this->_entity_name){
            throw new Fd_Exception('no entity name defined for '.get_called_class().' on getColumnName');
        }
        return $this->_entity_name."_id";
    }
    
    public function editContent($values, $id = null){
        if(!$this->_id && !$id){
            throw new Fd_Exception("No main id defined for WithImages on edit");  
        }
        if(!$this->_id && $id){
            $this->_id = $id;
        }
        if(!$this->_column_name){
            throw new Fd_Exception('No column name defined for '.get_called_class().' on edit');
        }
        $column = $this->_column_name;
    
        try{
            //add the uploaded content
            $table = new Fd_Db_Tbl_Inv_Uploaded_Content();
            $was_inserted = count($values) ? true : false;
            foreach($values as $uploaded_file){
                if($uploaded_file->type && $uploaded_file->name  && $uploaded_file->size){
                    $content_id = $table->insert(array(
                                'file_type' => $uploaded_file->type,
                                'file_size' => $uploaded_file->size,
                                'file_name' => $uploaded_file->name
                            ));
                    $was_inserted = $this->_table_content->insert(array(
                                $this->_column_name => $this->_id,
                                'content_id' => $content_id
                            )) && $was_inserted;

                }
            }
            return $was_inserted;

        }catch(Zend_Db_Exception $e){
            $this->_db_errors[] = $e->getMessage();
            return false;
        }
    }
    
    public function getAll($where = null){
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
        $table_key = str_replace("_id","",$this->_column_name);
        
        // build query
        $qry = $db->select()->from(array($table_key => $this->_table->getName()),array("*") );
		
        $qry->group($table_key.'.id');
        $qry->where($where);
		
        // perform query and get results
        $rows = $db->fetchAll($qry);
         
        return $rows;
    }
}
?>