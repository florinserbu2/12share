<?php class Advertiser_IndexController extends Fd_Advertiser_Controller_Action{
		
	public function indexAction(){
		$advertiser_manager = new Fd_Advertiser_Manager($this->user->contact_id);

		$this->view->urls = $advertiser_manager->getUrls(null, $this->getParam('date_start'), $this->getParam('date_end'));
	}
	
	public function deleteImageAction(){
		$prizes_manager = new Fd_Advertiser_Prize_Manager($this->_request->prize_id);
		$prizes_manager->setAdvertiser($this->user->contact_id);
		
		//upload remove
		if($prizes_manager->removeContent()){
			$this->_redirect("/advertiser/index/prize/id/".$this->_request->prize_id);
		}
		
		
	}
	
	public function prizeAction(){
		$prizes_manager = new Fd_Advertiser_Prize_Manager($this->_request->id);
		$prizes_manager->setAdvertiser($this->user->contact_id);
		$this->view->form = $form = $prizes_manager->form();
		$form->setAction($this->uri);
		
			//upload form
			$uploader = new Fd_Uploader($prizes_manager,1);
			
		if($prizes_manager->hasRow()){
			$this->view->images_url = $uploader->getUploadUrl();
			$this->view->prize = $prizes_manager->getRow();
			$content = $this->view->images = $prizes_manager->getUploadedContent();
		}
		if(!@$content){
			$uploader->form($form);
		}
		
		
		$request = $this->_request;
		if($request->isPost() && $form->isValid($request->getPost())){
			
			if($id = $prizes_manager->edit($form->getValues())){
				$prizes_manager->setRow($id);
				$uploader = new Fd_Uploader($prizes_manager,1);
				$uploader->upload();
				$this->instantMessage("Successfully saved", "success");
				$this->view->js_do = "redirect('/advertiser/index/prizes')";
				$this->view->js_do_after = "1000";
			}elseif($prizes_manager->hasRow()){
				if($uploader->upload()){
					$this->instantMessage("Successfully saved", "success");
					$this->view->js_do = "redirect('/advertiser/index/prizes')";
					$this->view->js_do_after = "1000";
				}elseif($errors = $uploader->getErrors()){
					$this->displayErrors($errors);
				}
			}elseif($errors = $prizes_manager->getErrors()){
				$this->displayErrors($errors);
			}
			
		}
		
	}
	
	public function prizesAction(){
		
		$advertiser = new Fd_Advertiser_Manager($this->user->contact_id);
		$this->view->prizes = $advertiser->getPrizes();
	}
	
	
}
