<?php class Fd_Publisher_Manager extends Fd_ManagerAbstract{
	 
    protected function _getEntityTable(){
        return new Fd_Db_Tbl_Crm_Contact_Hub();
    }
   
    public function getEntityName(){
        return "contact";
    }

	public function selectCurrentAdvertiser($id){
		if(!$this->_row){
			throw new Fd_Exception("No row for selectCurrentAdvertiser");
		}
		$table = new Fd_Db_Tbl_Pub_Current_Advertisers();
		$table->delete("publisher_id = {$this->_row->id}");
		
		return $table->insert(array("publisher_id" => $this->_row->id, "advertiser_id" => (int)$id));
	}

	public function getCurrentAdvertiser(){
		
		if(!$this->_row){
			throw new Fd_Exception("No row for getCurrentAdvertiser");
		}
		
    	$db = Zend_Registry::get('db');
        $select = $db->select();
        $select->from(array('cadv' => 'tbl_pub_current_advertisers'),array('advertiser_id'));
        $select->joinInner(array('hub' => 'tbl_crm_contact_hub'),"cadv.advertiser_id = hub.id", array('id' => "hub.id",'legal', 'status', 'code'));
        
        $select->joinInner(array('user' => 'tbl_crm_contact_user'),"user.contact_id = hub.id",array('username'));
        
		$select->where("hub.status = 1 AND hub.legal = 2 AND cadv.publisher_id = {$this->_row->id}");
		
		$select->group("hub.id");
        
		return $db->fetchRow($select);
	}
	
	public function getSpentCredits(){
		$db = Zend_Registry::get('db');
        $select = $db->select();
        $select->from(array('spent' => 'tbl_pub_spent_credits'),array('total' => 'SUM(spent.current_cost)'));
        
		$select->where("publisher_id = {$this->_row->id}");
		
		//$select->group("spent.id");
		
		return @$db->fetchRow($select)->total;
	}
	
	public function getEarnedPrizes(){
		$db = Zend_Registry::get('db');
        $select = $db->select();
        $select->from(array('spent' => 'tbl_pub_spent_credits'),array("earn_date" => "spent.timestamp", "cost" => 'spent.current_cost'));
        $select->joinInner(array("prize" => "tbl_adv_prizes"), "prize.id = spent.prize_id", array("*"));
		
		$select->where("publisher_id = {$this->_row->id}");
		
		$select->group("prize.id");
		
		$ret = array();
		
		$rows = $db->fetchAll($select);
		foreach($rows as $row){
			$aux = new stdClass;
			$aux = clone($row);
			
			$man = new Fd_Advertiser_Prize_Manager($row->id);
			$uploader = new Fd_Uploader($man);
			$aux->images_url = $uploader->getUploadUrl();
			$aux->images = $man->getUploadedContent();
			
			$ret[] = $aux;
			
		}
		
		return $ret;
	}
	
	public function getCredits(){
		$advertiser = new Fd_Advertiser_Manager($this->getCurrentAdvertiser()->id);
		$credits = 0;
		foreach($advertiser->calculateClicks($this->_row->id) as $date){
			$credits += $date->clicks;
		}
		foreach($advertiser->calculateConvs($this->_row->id) as $date){
			$credits += $date->convs*100;
		}
		return $credits - $this->getSpentCredits();
	}
}
