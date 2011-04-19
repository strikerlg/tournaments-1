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
 * Controller for user management
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class UserController extends Zend_Controller_Action {

	/**
	 * Initialize the controller
	 *
	 * @access public
	 */
	public function init() {
		$this->user = Tournament_Auth::getInstance();
		$this->mapper = new Tournament_Model_Mapper_User();
	}

	/**
	 * Default action - view logged in user's profile
	 *
	 * @access public
	 */
	public function indexAction() {
	}

}
