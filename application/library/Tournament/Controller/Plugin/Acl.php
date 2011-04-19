<?php
/**
 * Tournament controller plugins
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Handle logins and check if the user has access to the page they are requesting
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

	/**
	 * Check ACL to see if the user has access to the page they are accessing
	 *
	 * @access public
	 * @param  Zend_Controller_Request_Abstract $request The request object
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		if ($request->getPost('action') == 'login') {
			$user = Tournament_Auth::login($request->getPost('username'), $request->getPost('password'));
		}
		else {
			$user = Tournament_Auth::getInstance();
		}

		$controllerResourcePrefix = $request->getModuleName() . '/' . $request->getControllerName();
		$actionResourcePrefix = $controllerResourcePrefix . '/' . $request->getActionName();
		if (!$user->isAllowed($controllerResourcePrefix) && !$user->isAllowed($actionResourcePrefix)) {
			if ($user->isGuest()) {
				$request->setModuleName('default');
				$request->setControllerName('index');
				$request->setActionName('login');
			}
			else {
				$request->setModuleName('default');
				$request->setControllerName('index');
				$request->setActionName('unauthorized');
			}
		}
	}

}
