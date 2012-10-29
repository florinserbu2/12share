<?php 
class Fd_Auth_Controller_Plugin extends Zend_Controller_Plugin_Abstract
{ 

    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
    	$auth = Zend_Auth::getInstance();
        
        if (isset($_GET['lang']) && ! isset($_SESSION['current']['language'])){
            if($this->__checkLanguageExists($_GET['lang'])){
                $_SESSION['current']['language'] = $_GET['lang'];
            }else{
                $_SESSION['current']['language'] = 'ro';
            }
        }elseif (! isset($_SESSION['current']['language'])) {
            //check the browser language
            /*$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
            if($this->__checkLanguageExists($lang)){
                $_SESSION['current']['language'] = $lang;
            }else{
                $_SESSION['current']['language'] = 'ro';
            }*/
            $_SESSION['current']['language'] = 'ro';
        }elseif ($_SESSION['current']['language']==''){
            $_SESSION['current']['language'] = 'ro';
        }
        $_SESSION['current']['controller'] = $request->getControllerName();
        $_SESSION['current']['module'] = $request->getModuleName();
       
        if (! isset($_SESSION['current']['token'])) {
            $_SESSION['current']['token'] = Fd_Auth_Token::getToken();
        }
       

    }

}