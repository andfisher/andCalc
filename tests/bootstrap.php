<?php
error_reporting( E_ALL | E_STRICT );
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
 
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
define('APPLICATION_ENV', 'testing');
define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));
define('TESTS_PATH', realpath(dirname(__FILE__)));
define('PHPUNIT_PATH', realpath(dirname(__FILE__) . '/../../phpunit/PHPUnit'));


$includePaths = array(LIBRARY_PATH, APPLICATION_PATH, PHPUNIT_PATH, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $includePaths));


require_once "TestHelper.php";

require_once "Zend/Loader/Autoloader.php";
$loader = Zend_Loader_Autoloader::getInstance();
//$loader->setFallbackAutoloader(true);
//$loader->suppressNotFoundWarnings(false);
 
Zend_Session::$_unitTestEnabled = true;
Zend_Session::start();

//$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH.'/configs/application.ini');
//$application->bootstrap();