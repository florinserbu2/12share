<?php 
class Fd_Users_Manager extends Fd_Manager_WithContent{
    
    
    protected function _getEntityTable(){
        return new Fd_Db_Tbl_Crm_Contact_Hub();
    }
    protected function _getContentTable(){
        return new Fd_Db_Tbl_Crm_Contacts_Content();
    }
    public function getEntityName(){
        return "contact";
    }

	public static function auth($id, $code){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
    	  
		 
        $authAdapter->setTableName('tbl_crm_contact_user')
        ->setIdentityColumn('contact_id')
        ->setCredentialColumn('hub.code')
        ->setCredentialTreatment('?');
        
        $select = $authAdapter->getDbSelect();
        $select->joinInner(array("hub" => 'tbl_crm_contact_hub'),"hub.id = contact_id",array('hub_status' => 'hub.status'));
        $select->joinInner(array("role" => 'tbl_fd_system_roles'),"role.id = role_id",array("security" => 'role.security'));
		
		
	    $authAdapter->setIdentity($id);
	    $authAdapter->setCredential($code);
       
	    self::processAuth(array("username" => $id, "password" => $code), $authAdapter);
	}

	public static $auth_error_messages;

	public static function processAuth($values, $adapter = null){
		// Get our authentication adapter and check credentials
        $adapter = $adapter ? $adapter : self::getAuthAdapter();

	    $adapter->setIdentity($values['username']);
        $adapter->setCredential($values['password']);
        
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
        	
            $user = $adapter->getResultRowObject();
			
                $auth->getStorage()->write($user);
				$manager = new Fd_Users_Manager($user->contact_id);
				
            return $user;
        }
        if($result->getMessages()){
        	self::$auth_error_messages = $result->getMessages();
        }
        return false;
	}

	public static function getAuthAdapter(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
    	  
		 
        $authAdapter->setTableName('tbl_crm_contact_user')
        ->setIdentityColumn('username')
        ->setCredentialColumn('password')
        ->setCredentialTreatment('MD5(?)');
        
        $select = $authAdapter->getDbSelect();
        $select->joinInner(array("hub" => 'tbl_crm_contact_hub'),"hub.id = contact_id",array('hub_status' => 'hub.status'));
        
        return $authAdapter;
	}

   
    
    public function getRow($id = null){
        if(!isset($this->_row)){
        	
        	$db = Zend_Registry::get('db');
            $select = $db->select();
            $select->from(array('hub' => 'tbl_crm_contact_hub'), array('id' => "hub.id",'legal', 'status', 'code'));
            
            $select->joinInner(array('user' => 'tbl_crm_contact_user'),"user.contact_id = hub.id",
            	array("*"));
              $select->where("hub.id = {$id}");
            
			$select->group("hub.id");
            return $db->fetchRow($select);
        }else{
            return $this->_row;
        }
    }
  
	public function getRowByUname($name){
		$table = new Fd_Db_Tbl_Crm_Contact_User();
		$row = $table->fetchRow("username = '".trim(strtolower(mysql_real_escape_string($name)))."'");
		
		if(!$row){
			return false;
		}
		
		$this->_row = $this->getRow($row->contact_id);
		return $this->getFullInfo();
	}
	
	public function getCurrentAdvertiser(){
		if(!$this->_row){
			throw new Fd_Exception("Can't get current advertiser without the user row");
		}
		$table = new Fd_Db_Tbl_Pub_Current_Advertisers();
		
		return $table->fetchRow("publisher_id = {$this->_row->contact_id}");
	}
	
	public function delete(){
		$manager = new Fd_Items_Manager();
		$items = $manager->getItemsFor(array('contact_id' => $this->_row->id));
		
		foreach($items as $item){
			$manager->setRow($item->id);
			$manager->delete(true);
		}
		
		return $this->_table->update(array('delete' => 1));
	}
}
?>