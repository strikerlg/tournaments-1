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
 * Tournament dashboard controller for users
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class DashboardController extends Zend_Controller_Action {

	/**
	 * Initialize the controller
	 *
	 * @access public
	 */
	public function init() {
		$this->user = Tournament_Auth::getInstance();
		$this->mapper = new Tournament_Model_Mapper_Bracket();
		$this->view->nav = 'dashboard';
	}

	/**
	 * Default action - list all brackets
	 *
	 * @access public
	 */
	public function indexAction() {
		$this->view->brackets = $this->mapper->fetchAllByUser($this->user->id);
	}

}
