<?php 
class Fd_Publisher_Controller_Action extends Fd_Controller_Action{
    
     
   const ROLE_ID = 2;
   
    public function preDispatch(){
        parent::preDispatch();
		if($this->user->role_id != self::ROLE_ID){
			$this->redirect("/");
		}
		$manager = new Fd_Publisher_Manager($this->user->contact_id);
		
		if(($advertiser = $manager->getCurrentAdvertiser())){
			$this->user->advertiser = $advertiser;
		}else{
			if($this->action != 'pick-advertiser')
			$this->redirect("/publisher/index/pick-advertiser");
		}
    }
   
}
?>