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
 * Main tournament controller
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class IndexController extends Zend_Controller_Action {

	/**
	 * Show the splash page
	 *
	 * @access public
	 */
	public function indexAction() {
		$this->_forward('login');
	}

	/**
	 * Show the login page and/or perform login
	 *
	 * @access public
	 */
	public function loginAction() {
		$this->forwardLoggedIn();
		$this->_helper->layout()->setLayout('splash');
	}

	/**
	 * Log the user out
	 *
	 * @access public
	 */
	public function logoutAction() {
		Tournament_Auth::logout();
		$this->_redirect('/');
	}

	/**
	 * Register a user
	 *
	 * @access public
	 */
	public function registerAction() {
		if ($this->getRequest()->getPost('action') == 'Register') {
			$user = new Tournament_Model_User();
			$user->email = $this->getRequest()->getPost('email');
			$user->username = $this->getRequest()->getPost('username');
			$user->roleId = Tournament_Constants_User_Role::MEMBER;
			$userMapper = new Tournament_Model_Mapper_User();
			$register = $userMapper->register($user, $this->getRequest()->getPost('password'));

			if ($register->getStatus() == Tournament_Registration_Status::SUCCESS) {
				Tournament_Auth::loadUser($register->getUserId());
			}
			else {
				$this->view->error = $register->getMessage();
			}
		}

		$this->forwardLoggedIn();
		$this->_helper->layout()->setLayout('splash');
	}

	/**
	 * Forward to the dashboard if the user is logged in
	 *
	 * @access protected
	 */
	protected function forwardLoggedIn() {
		$user = Tournament_Auth::getInstance();
		if (!$user->isGuest()) {
			$this->_helper->redirector('index', 'dashboard');
		}
	}

}
