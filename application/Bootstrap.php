<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected $_front;
    protected $_root;

    public function __construct($application){
    	parent::__construct($application);
    }
    
    protected function _initBase(){
        // Set up autoload.
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('App_');
		$loader->setFallbackAutoloader(true);
		
		Zend_Session::start();
		$this->_front = Zend_Controller_Front::getInstance();
	    
		$root = realpath(dirname(__FILE__) . '/../');
		$this->_root = $root;
    }
	
	public function _initTime(){
		date_default_timezone_set("America/Chicago");
	}
	    
  
    public function _initViews(){
        // Initialize view
        $view = new Zend_View();
        $view->addHelperPath(APPLICATION_PATH.'/views/helpers');
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'ViewRenderer'
        );
        $viewRenderer->setView($view);
    
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    
    /**
     * Initialize plugins
     *
     * @return void
     */
    protected function _initPlugins ()
    {
        $this->_front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
        $this->_front->registerPlugin(new Fd_Auth_Controller_Plugin());
    }
    
    /**
     * Initialize config
     */
    public function _initConfig(){
        $config = $this->getOptions();
        Zend_Registry::set('config', $config);
    }
    public function _initDb ()
    {
        $registry = Zend_Registry::getInstance();
        $config = $registry->get('config');
       
        $db = Zend_Db::factory($config['resources']['db']['adapter'],
                $config['resources']['db']['params']);
        $db->query("SET NAMES utf8");
        $db->query("SET collation_connection = 'utf8_general_ci'");
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
		
		
		//$profiler = $db->getProfiler()->setEnabled(true);
		//Zend_Registry::set('db-profiler', $profiler);
		
    }
	
	 
    public function _initRoutes ()
	{
		$this->_front = Zend_Controller_Front::getInstance();
		$router = $this->_front->getRouter(); // returns a rewrite router by default
		$router->addRoute(
			    'go',
			    new Zend_Controller_Router_Route("go/:track_url",
			                                     array('module' => 'default',
														'controller' => 'track',
			                                           'action' => 'click'))
			);
		$router->addRoute(
			    'conv',
			    new Zend_Controller_Router_Route("conv/:prize_id",
			                                     array('module' => 'default',
														'controller' => 'track',
			                                           'action' => 'conv'))
			);
	}
	
    public function _initHelpers(){
       //require_once "firelogger.php";
    }
    
    /**
     * Initialize layout
     */
    public function _initLayout(){
        
        // Bootstrap layouts
        $layout = Zend_Layout::startMvc(array(
                'layoutPath' => APPLICATION_PATH.'/layouts/scripts',
                'layout' => 'layout'
        ));
    }
    /**
     * Initialize Controller paths
     *
     * @return void
     */
    public function _initControllers () 
    {
        $this->_front->_root = $this->_root;
        $this->_front->addControllerDirectory(
                $this->_root . '/application/modules/default/controllers', 'publisher');
        $this->_front->addControllerDirectory(
                $this->_root . '/application/modules/default/controllers', 'advertiser');
       
    }
}

