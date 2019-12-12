<?php
/**
 * @copyright Copyright (c) 2012 Andrew Fisher.
 * @author Andrew Fisher
 */


class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
	protected $_config;

	protected function _initTimezone()
	{
		date_default_timezone_set('Europe/London');
	}
	
	protected function _initConfig()
	{
		Zend_Registry::set('config', new Zend_Config($this->getOptions(), true));		
		$this->_config = $this->getOptions();
	}
    
    protected function _initDoctype()
    {
        // Initialize view
        $this->bootstrap('view');
		
		$view = $this->getResource('view');
        //$view->doctype('XHTML1_STRICT');
        $view->doctype('HTML5');
        $view->headTitle('Zend Calculator');
		$view->headTitle()->setSeparator(' - ');
        $view->env = APPLICATION_ENV;

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
	
	protected function _initAutoLoad()
	{
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH
		));
		return $moduleLoader;
	}
	
	protected function _initTranslate()
	{
		$translate = new Zend_Translate(array(
			'adapter'	=> 'array',
			'locale'	=> 'en-gb',
			'content'	=> APPLICATION_PATH.'/languages/en-gb/array.php'
		));
		
		$translate->setLocale('en-gb');
		
		Zend_Registry::set('Zend_Translate', $translate);
	}
	
	protected function _initHelpers()
	{	
		# Init Action helpers
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/controllers/helpers', 'Application_Helper_');
	}
	
	
	protected function _initForms()
	{   
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms.ini', APPLICATION_ENV);
		Zend_Registry::set('Form', $config);
	}
}

// End of /application/Bootstrap.php