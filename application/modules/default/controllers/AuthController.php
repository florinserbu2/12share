<?php

class AuthController extends Fd_Controller_Action
{

    protected function _getAuthAdapter()
    {
    	return  Fd_Users_Manager::getAuthAdapter();
    }

    protected function _process($values)
    {
        if($user = Fd_Users_Manager::processAuth($values)){
        	return $user;
        }else{
        	if(Fd_Users_Manager::$auth_error_messages){       
            	foreach(Fd_Users_Manager::$auth_error_messages as $k => $v){
                	$this->instantMessage($v, 'error');     
				}   
			}
        }
    }

    public function init(){
		parent::init();
		
	}
   
    public function indexAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($user = $this->_process($request->getPost())) {
                // We're authenticated! Redirect to the home page
				$this->_redirect($user->role_id == 1 ? "/advertiser/index" : "/publisher/index");				
            }           
        }
    } 

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_response->setRedirect('/default/index'); // back to login page
    }

   

}



