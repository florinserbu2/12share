<?php class Fd_Db_Table extends Zend_Db_Table
{

	protected $_access_tester;

	/**
	 * This function gives a count back of the number of rows that will be fetched
	 *
	 * @param string $where
	 *
	 * @return int count
	 */
	public function fetchCount ($where = null)
	{
		$select = "select count(*) as count from " . $this->_name;
		if ($where != null) {
			$select .= " where " . $where;
		}
		$retval = $this->_db->fetchRow($select);
		return is_array($retval) ? $retval["count"] : $retval->count;
	}
	
	public function addTester($tester){
		$this->_access_tester = $tester;
	}
	
	
	public function getName(){
	    return $this->_name;
	}
	
	public function getPrimary(){
	    return $this->_primary;
	}
	
	public function __construct($access_tester = null){
		parent::__construct();
		$this->_access_tester = $access_tester;
	}
	
	public function update($values, $where){
		if($this->_access_tester){ //access tester was found, find the EDIT rule
			if($this->_access_tester->level->rules->edit){ //EDIT rule found, process it
				$row = parent::fetchRow($where);
	
				switch($this->_access_tester->level->rules->edit){ 
					case 1: //EDIT - owned : can edit only owned rows ( contact_id = row.contact_id )
							if(!@$row->contact_id){
								throw new Fd_Exception("No contact_id column defined for {$this->_name}, occured on update access test");
							}
							if($row->contact_id == $this->_access_tester->contact_id){ //row is owned, access granted
								return parent::update($values, $where);
							}else{ //row is not owned, access denied
								throw new Fd_Exception("No access for this");
							}
							break;
					case 2: //EDIT - parent : can edit only rows from the same parent - currently not usable	
							throw new Fd_Exception('parent level access test not defined');
							break;
					case 3: //EDIT - all - access granted
							return parent::update($values, $where);
							break;
				}
			}
		}else{
			return parent::update($values, $where);
		}
	}
	
	public function insert($values){
		if($this->_access_tester){
			if(isset($this->_access_tester->level->rules->create)){ //CREATE rule found, process it
				switch($this->_access_tester->level->rules->create){ 
					case 1: //CREATE - allowed - access granted
							return parent::insert($values);
							break;
					case 0: //CREATE - denied - access denied
							throw new Fd_Exception('No access for this');
							break;
				}
			}else{
				throw new Fd_Exception("No CREATE rule defined");
			}
		}else{
			return parent::insert($values);
		}
	}
	
	public function delete($where){
		if($this->_access_tester){
			if($this->_access_tester->level->rules->delete){ //DELETE rule found, process it
				$row = parent::fetchRow($where);
	
				switch($this->_access_tester->level->rules->delete){ 
					case 1: //DELETE - owned : can edit only owned rows ( contact_id = row.contact_id )
							if(!@$row->contact_id){
								throw new Fd_Exception("No contact_id column defined for {$this->_name}, occured on update access test");
							}
							if($row->contact_id == $this->_access_tester->contact_id){ //row is owned, access granted
								return parent::delete($where);
							}else{ //row is not owned, access denied
								throw new Fd_Exception("No access for this");
							}
							break;
					case 2: //DELETE - parent : can edit only rows from the same parent - currently not usable	
							throw new Fd_Exception('parent level access test not defined');
							break;
					case 3: //DELETE - all - access granted
							return parent::delete($where);
							break;
				}
			}
		}else{
			return parent::delete($where);
		}
	}

	public function fetchRow($where){
				
		if($this->_access_tester){
			if($this->_access_tester->level->rules->read){ //READ rule found, process it
				$row = parent::fetchRow($where); 
				switch($this->_access_tester->level->rules->read){ 
					case 1: //READ - owned : can edit only owned rows ( contact_id = row.contact_id )
							if(!@$row->contact_id){
								throw new Fd_Exception("No contact_id column defined for {$this->_name}, occured on update access test");
							}
							if($row->contact_id == $this->_access_tester->contact_id){ //row is owned, access granted
								return $row;
							}else{ //row is not owned, access denied
								throw new Fd_Exception("No access for this");
							}
							break;
					case 2: //READ - parent : can edit only rows from the same parent - currently not usable	
							throw new Fd_Exception('parent level access test not defined');
							break;
					case 3: //READ - all - access granted
							return $row;
							break;
				}
			}
		}else{
			$row = parent::fetchRow($where); 
			return $row;
		}
	}
}
?>