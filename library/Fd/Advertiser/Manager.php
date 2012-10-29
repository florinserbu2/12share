<?php class Fd_Advertiser_Manager extends Fd_Manager_WithContent{
	 
    protected function _getEntityTable(){
        return new Fd_Db_Tbl_Crm_Contact_Hub();
    }
    protected function _getContentTable(){
        return new Fd_Db_Tbl_Adv_Advertisers_Content();
    }
    public function getEntityName(){
        return "contact";
    }

	public function getPrizes(){
		if(!$this->_row){
			throw new Fd_Exception("can't get prizes without a row");
		}
		$db = Zend_Registry::get('db');
        $select = $db->select();
        $select->from(array('prize' => 'tbl_adv_prizes'), array('*'));
        
        $select->joinLeft(array('spent' => 'tbl_pub_spent_credits'),"prize.id = spent.prize_id",
        	array("remaining_items" => "prize.items_no - COUNT(spent.prize_id)"));
       
	    $select->where("prize.advertiser_id = {$this->_row->id}");
		
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
	
	public function getCurrentList(){
		
    	$db = Zend_Registry::get('db');
        $select = $db->select();
        $select->from(array('hub' => 'tbl_crm_contact_hub'), array('id' => "hub.id",'legal', 'status', 'code'));
        
        $select->joinInner(array('user' => 'tbl_crm_contact_user'),"user.contact_id = hub.id",array('username'));
        $select->joinInner(array('prizes' => 'tbl_adv_prizes'),"user.contact_id = prizes.advertiser_id",array("prizes_no" => "COUNT(prizes.id)"));
		
		$select->where("hub.status = 1 AND hub.legal = 2");
		
		$select->group("hub.id");
        $rows = $db->fetchAll($select);
		
		$ret = array();
		foreach($rows as $row){
			$aux = clone($row);
			$this->getRow($row->id);
			$content = $this->getUploadedContent();
			$aux->images = $content;
			$ret[] = $aux;
		}
		
		return $ret;
	}
	
	public function getUrls($publisher_id = null, $date_start = null, $date_end = null){
		if($this->_row){
				
			$db = Zend_Registry::get('db');
        	$select = $db->select();
        	$select->from(array('prize' => 'tbl_adv_prizes'),array("id" => "prize.id","url", "name"));
        	
			$select->where("prize.advertiser_id = {$this->_row->id}");
		
			$select->group(array("prize.url"));

			$prizes = $db->fetchAll($select);
			
			$config = Zend_Registry::get("config");
			$has_key = $config['hash_keys']['url_key'];        
			
			$urls = array();
			foreach($prizes as $prize){
				if(!in_array($prize->url, $urls)){
					$aux = new stdClass;
					$aux->url = $prize->url;
					$aux->prize_id = $prize->id;
					$aux->prize_name = $prize->name;
					$this->calculateReport($aux, $publisher_id, $prize->id, $date_start, $date_end);
					$aux->track_url = base64_encode($prize->id.$has_key.$publisher_id);
					$urls[] = $aux ;
				}
			}
			return $urls;
		}
		
	}

	public function calculateReport(&$aux, $publisher_id, $prize_id, $date_start, $date_end){
		$clicks = $this->calculateClicks($publisher_id, $prize_id, $date_start, $date_end);
		$convs = $this->calculateConvs($publisher_id, $prize_id, $date_start, $date_end);
		
	
		$clicks_counter = 0;		
		$convs_counter = 0;
		
		$aux->report = array();
		
		foreach($clicks as $click){
			$clicks_counter += $click->clicks;
			$x = new stdClass;
			$x->date = $click->date;
			$x->clicks = $click->clicks;
			$x->convs = 0;
			$aux->report[$x->date] = $x;
		}
		
		foreach($convs as $conv){
			$convs_counter += $conv->convs;
			$x = @$aux->report[$conv->date];
			$x->convs = $conv->convs;
			$aux->report[$conv->date] = $x;
		}

		$aux->clicks = $clicks_counter;
		$aux->convs = $convs_counter;
		
		
	}

	public function calculateClicks($publisher_id = null, $prize_id = null, $date_start = null, $date_end = null){
		if(!$this->_row->id){
			throw new Fd_Exception("No row for calculateClicks");
		}	
		$db = Zend_Registry::get('db');
    	$select = $db->select();
    	$select->from(array('stats' => 'tbl_sts_clicks'),array(
    		"date" => "DATE(stats.timestamp)",
    		"clicks" => "COUNT(DISTINCT stats.cookie)"
			));
    	$select->joinInner(array("prize" => "tbl_adv_prizes"), "prize.id = stats.prize_id", array());
		
		$where = array("prize.advertiser_id = {$this->_row->id}");
		
		if($publisher_id){
			$where[] = "stats.publisher_id = {$publisher_id}";
		}
		if($prize_id){
			$where[] = "stats.prize_id = {$prize_id}";
		}
		if($date_start){
			$where[] = "stats.timestamp >= '$date_start'";
		}
		if($date_end){
			$where[] = "stats.timestamp <= '$date_end'";
		}
		$select->where(implode(" AND ", $where));
	
		$select->group(array("date"));
//if($prize_id ==1)dump($select->__toString(),1);
		$rows = $db->fetchAll($select);

		return $rows;
	}
	
	public function calculateConvs($publisher_id = null, $prize_id = null, $date_start = null, $date_end = null){
		$db = Zend_Registry::get('db');
    	$select = $db->select();
    	$select->from(array('conv' => 'tbl_sts_convs'),array(
    		"date" => "DATE(conv.timestamp)",
    		"convs" => "COUNT(conv.id)"
			));
    	$select->joinInner(array("prize" => "tbl_adv_prizes"), "prize.id = conv.prize_id", array());
		
		$where = array("prize.advertiser_id = {$this->_row->id}");
		
		if($publisher_id){
			$where[] = "conv.publisher_id = {$publisher_id}";
		}
		if($prize_id){
			$where[] = "conv.prize_id = {$prize_id}";
		}
		if($date_start){
			$where[] = "conv.timestamp >= '$date_start'";
		}
		if($date_end){
			$where[] = "conv.timestamp <= '$date_end'";
		}
		$select->where(implode(" AND ", $where));
	
		$select->group("date");

		$rows = $db->fetchAll($select);

		return $rows;
	}

	public function addOneClick($prize_manager, $publisher_manager){
		$table = new Fd_Db_Tbl_Sts_Clicks();
		if($prize_manager->getRow()->id && $publisher_manager->getRow()->id){
			$cookie = $_COOKIE['12share_tracker'] ? $_COOKIE['12share_tracker'] : md5(rand(999).$prize_manager->getRow()->id.$publisher_manager->getRow()->id);
			setcookie("12share_tracker", $cookie, time()+ 3600 * 24 * 7 * 31 * 12, "/");
			return $table->insert(
				array(
					"prize_id" => $prize_manager->getRow()->id, 
					"publisher_id" => $publisher_manager->getRow()->id, 
					"cookie" => $cookie,
					"ip" => $_SERVER['REMOTE_ADDR']
					)
				);
		}
	}
	public function addOneConv($prize_manager, $cookie){
		$table = new Fd_Db_Tbl_Sts_Clicks();
		if($prize_manager->getRow()->id && $cookie){
			if($row = $table->fetchRow("cookie = '{$cookie}' AND prize_id = {$prize_manager->getRow()->id}")){
				$table = new Fd_Db_Tbl_Sts_Convs();
				return $table->insert(array(
					"publisher_id" => $row->publisher_id,
					"prize_id" => $row->prize_id,
					"stats_id" => $row->id
				));
			}
		}
	}
}
