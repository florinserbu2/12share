<?php 
   
function  dump($val, $exit = false){
    echo "<pre>";
    var_dump($val);
    if($exit) exit;
}


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
// Define path to application directory
defined('FD_LIB_PATH')
|| define('FD_LIB_PATH', realpath(APPLICATION_PATH.'/../library'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
     realpath(APPLICATION_PATH . '/../library'),
     realpath(APPLICATION_PATH . '/../../library'),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$application->bootstrap()
            ->run();

