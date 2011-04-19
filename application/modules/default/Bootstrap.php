<?php
/**
 * Default tournament module
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Bootstrap file for the default tournament module
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Bootstrap extends Zend_Application_Module_Bootstrap {

    /**
     * Initialize routing
     *
     * @access protected
     */
    protected function _initRouting() {
		$this->bootstrap('FrontController');
		$front = $this->getResource('FrontController');
		$router = $front->getRouter();
		$route = new Zend_Controller_Router_Route (
			'login',
			array (
				'module'     => 'default',
				'controller' => 'index',
				'action'     => 'login'
			)
		);
		$router->addRoute('login', $route);
		$route = new Zend_Controller_Router_Route (
			'logout',
			array (
				'module'     => 'default',
				'controller' => 'index',
				'action'     => 'logout'
			)
		);
		$router->addRoute('logout', $route);
		$route = new Zend_Controller_Router_Route (
			'register',
			array (
				'module'     => 'default',
				'controller' => 'index',
				'action'     => 'register'
			)
		);
		$router->addRoute('register', $route);
		$route = new Zend_Controller_Router_Route (
			't/:id',
			array (
				'module'     => 'default',
				'controller' => 'tournaments',
				'action'     => 'index'
			),
			array('id' => '\d+')
		);
		$router->addRoute('view', $route);
	}

    /**
     * Initialize session
     *
     * @access protected
     */
	protected function _initSession() {
		Zend_Session::start();
	}

	/**
	 * Initialize app constants
	 *
	 * @access protected
	 */
	protected function _initConstants() {
		$resourceConfig = $this->getApplication()->getOption('resources');

		// Prefix for DB tables
		Zend_Registry::set('table_prefix', $resourceConfig['db']['tablePrefix']);

		// Password salting
		Zend_Registry::set('salt', $resourceConfig['db']['passwordSalt']);
	}

}
