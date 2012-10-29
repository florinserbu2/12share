<?php class Fd_Advertiser_Prize_Manager extends Fd_Manager_WithContent{
	
	public $_advertiser_id;
	 
	public function setAdvertiser($id){
		$this->_advertiser_id = $id;
	}
	 
    protected function _getEntityTable(){
        return new Fd_Db_Tbl_Adv_Prizes();
    }
    protected function _getContentTable(){
        return new Fd_Db_Tbl_Adv_Prizes_Content();
    }
    public function getEntityName(){
        return "prize";
    }

	
	public function edit($values){
		if(!$this->_advertiser_id && !$this->_row->id){
			throw new Fd_Exception("Can't add a prize without advertiser id");
		}	
		$values['advertiser_id'] = $this->_advertiser_id;
		unset($values['uploads']);
		return parent::edit($values);
	}	

	public function form(){
		$form = new Fd_Twitter_Form();
		$form->submit->setAttrib("onClick", "");
		$form->submit->setAttrib("type", "submit");
		
		$element = new Fd_Twitter_Form_Element_Text("name");
		$element->setLabel(Fd_Language_Labels::get("Name"));
		$element->setRequired(true);
		$form->addElement($element);
		
		$element = new Fd_Twitter_Form_Element_Text("price");
		$element->addValidator(new Fd_Validate_Float);
		$element->setLabel(Fd_Language_Labels::get("Item credits value"));
		$element->setRequired(true);
		$form->addElement($element);
		
		$element = new Fd_Twitter_Form_Element_Number("items_no");
		$element->addValidator(new Fd_Validate_Float);
		$element->addValidator(new Fd_Validate_GreaterThan(0));
		$element->setLabel(Fd_Language_Labels::get("Number of items"));
		$element->setRequired(true);
		$form->addElement($element);
		
		$element = new Fd_Twitter_Form_Element_Text("url");
		$element->setLabel(Fd_Language_Labels::get("Click URL"));
		$element->setRequired(true);
		$form->addElement($element);
		
		if($this->_row){
			$form->setDefaults((array)$this->_row);
		}
		
		return $form;
	}

	public function canBuy($publisher_id){
		$advertiser_manager = new Fd_Advertiser_Manager($this->_row->advertiser_id);
		$credits = $advertiser_manager->calculateClicks($publisher_id);
		$convs = $advertiser_manager->calculateConvs($publisher_id);
		
		$publisher_manager = new Fd_Publisher_Manager($publisher_id);
		$spent = $publisher_manager->getSpentCredits();
		
		$available = 0;
		foreach($credits as $c){
			$available += $c->clicks;
		}
		foreach($convs as $c){
			$available += $c->convs*100;
		}
		
		
		$cost = $this->_row->price + $spent;
		
		if($available - $cost >= 0){
			return true;
		}
		
		return false;
	}
	
	public function sellPrize($publisher_id){
		$table = new Fd_Db_Tbl_Pub_Spent_Credits();
		return $table->insert(
			array(
				"prize_id" => $this->_row->id,
				"publisher_id" => $publisher_id,
				"current_cost" => $this->_row->price
				)
		);
	}
	
	
	public function removeContent($id = null){
		if(!$this->_row){
			throw new Fd_Exception("No row for removeContent");
		}	
		$row = $this->_table_content->fetchRow("prize_id = {$this->_row->id}");
		
		if($this->_table_content->delete("prize_id = {$this->_row->id}")){
	        $uplader = new Fd_Uploader($this);
			$uplader->removeContent();
			
			$table = new Fd_Db_Tbl_Inv_Uploaded_Content();
			return $table->delete("id = {$row->content_id}");
		}
	}
	
}
