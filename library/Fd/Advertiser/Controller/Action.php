<?php 
class Fd_Advertiser_Controller_Action extends Fd_Controller_Action{
    
   
   const ROLE_ID = 1;
   
    public function preDispatch(){
        parent::preDispatch();
		if($this->user->role_id != self::ROLE_ID){
			$this->redirect("/");
		}
    }
   
}
?>