<?php class Publisher_IndexController extends Fd_Publisher_Controller_Action{
		
	public function indexAction(){
		$advertiser_manager = new Fd_Advertiser_Manager($this->user->advertiser->id);
		
		$publisher_manager = new Fd_Publisher_Manager($this->user->contact_id);
		
		$spent = $publisher_manager->getSpentCredits();
		$this->view->spent_credits = $spent ? $spent : 0;
		
		$credits = $publisher_manager->getCredits();
		$this->view->credits =  $credits ? $credits : 0;
		
		$this->view->urls = $advertiser_manager->getUrls($this->user->contact_id, $this->getParam('date_start'), $this->getParam('date_end'));

	}
	
	public function pickAdvertiserAction(){
		
		$advertiser_manager = new Fd_Advertiser_Manager();
		$this->view->list = $advertiser_manager->getCurrentList();
		
		if($this->_request->id){
			$publisher_manager = new Fd_Publisher_Manager($this->user->contact_id);
			if($publisher_manager->selectCurrentAdvertiser($this->_request->id)){
				$this->_redirect("/publisher/index");
			}
		}
	}
	
	public function prizesAction(){
		$advertiser = new Fd_Advertiser_Manager($this->user->advertiser->id);
		
		$publisher_manager = new Fd_Publisher_Manager($this->user->contact_id);
		$this->view->credits = $publisher_manager->getCredits();

		$this->view->prizes = $advertiser->getPrizes();
	}
	
	public function buyAction(){
		$prize_manager = new Fd_Advertiser_Prize_Manager($this->_request->id);
		if($prize_manager->canBuy($this->user->contact_id)){
			if($prize_manager->sellPrize($this->user->contact_id)){
				$this->_redirect("/publisher/index/earned-prizes");
			}
		}
	}
	
	public function earnedPrizesAction(){
		$advertiser = new Fd_Publisher_Manager($this->user->contact_id);
		$this->view->prizes = $advertiser->getEarnedPrizes();
	}
}
