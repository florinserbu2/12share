<?php class TrackController extends Fd_Controller_Action{
	
	public function clickAction(){
		if(!$this->_request->track_url){
			exit;
		}
		$track_url = base64_decode($this->_request->track_url);
		$config = Zend_Registry::get("config");
		$key = $config['hash_keys']['url_key'];
		
		list($prize_id, $publisher_id) = explode($key, $track_url);
		
		$publisher_manager = new Fd_Publisher_Manager($publisher_id);
		$prize_manager = new Fd_Advertiser_Prize_Manager($prize_id);
		
		if($prize_manager->hasRow() && $publisher_manager->hasRow()){
			$advertiser_manager = new Fd_Advertiser_Manager($prize_manager->getRow()->advertiser_id);
			if($advertiser_manager->addOneClick($prize_manager, $publisher_manager)){
				$this->_redirect($prize_manager->getRow()->url);
			}
		}
	}
	
	public function convAction(){
		$prize_id = $this->_request->prize_id;
		@$cookie = $_COOKIE['12share_tracker'];
		
		if($prize_id){
			$prize_manager = new Fd_Advertiser_Prize_Manager($prize_id);
			$advertiser_manager = new Fd_Advertiser_Manager($prize_manager->getRow()->advertiser_id);
			$advertiser_manager->addOneConv($prize_manager, $cookie);
		}
		$file = $this->static['path'].'img/pixel.gif';

		$type = 'image/jpeg';
		header('Content-Type:'.$type);
		header('Content-Length: ' . filesize($file));
		readfile($file);
		exit;
	}
}?>
