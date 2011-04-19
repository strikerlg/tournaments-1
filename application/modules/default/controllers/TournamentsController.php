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
 * Controller for tournaments
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class TournamentsController extends Zend_Controller_Action {

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
	 * Default action - view a tournament
	 *
	 * @access public
	 */
	public function indexAction() {
		if ($this->getRequest()->isPost()) {
			if ($this->getRequest()->getParam('submit') == 'Add') {
				$save = $this->_addComment();
			}
		}
		$id = $this->getRequest()->getParam('id');
		$this->view->bracket = $this->mapper->find($id);
		if (empty($this->view->bracket)) {
			throw new Exception('Tournament not found');
		}
	}

	/**
	 * Advance a position in a tournament
	 *
	 * @access public
	 */
	public function advanceAction() {
		$id = $this->getRequest()->getParam('id');
		$position = $this->getRequest()->getParam('position');
		$bracket = $this->mapper->find($id);
		if (empty($bracket)) {
			throw new Exception('Tournament not found');
		}

		if ($this->user->id == $bracket->userId) {
			$bracket->getHelper()->advance($position);
			$this->mapper->save($bracket);
		}
		$this->_redirect('/t/' . $bracket->id);
	}

	/**
	 * Remove a position in a tournament
	 *
	 * @access public
	 */
	public function removeAction() {
		$id = $this->getRequest()->getParam('id');
		$position = $this->getRequest()->getParam('position');
		$bracket = $this->mapper->find($id);
		if (empty($bracket)) {
			throw new Exception('Tournament not found');
		}

		if ($this->user->id == $bracket->userId) {
			$bracket->getHelper()->remove($position);
			$this->mapper->save($bracket);
		}
		$this->_redirect('/t/' . $bracket->id);
	}

	/**
	 * Create a new tournament
	 *
	 * @access public
	 */
	public function createAction() {
		$this->view->nav = 'create';
		if ($this->getRequest()->getPost('action') == 'Create') {
			$save = $this->_create();
			if ($save) {
				$this->_redirect('/t/' . $save);
			}
			$this->view->params = $this->getRequest()->getPost();
		}
		$this->view->action = 'Create';
	}

	/**
	 * Edit an existing tournament
	 *
	 * @access public
	 */
	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$this->view->bracket = $this->mapper->find($id);
		if ($this->user->id != $this->view->bracket->userId) {
			$this->_helper->redirector('index', 'dashboard');
		}
		if ($this->getRequest()->getPost('action') == 'Edit') {
			$save = $this->_edit($this->view->bracket);
			if ($save) {
				$this->_redirect('/t/' . $save);
			}
		}
		if (empty($this->view->bracket)) {
			throw new Exception('Tournament not found');
		}
		$this->view->action = 'Edit';
		$this->render('create');
	}

	/**
	 * Delete an existing tournament
	 *
	 * @access public
	 */
	public function deleteAction() {
		if (TRUE || $this->getRequest()->isPost()) {
			$id = $this->getRequest()->getParam('id');
			$bracket = $this->mapper->find($id);
			if ($bracket->userId == $this->user->id) {
				$this->mapper->delete($bracket);
			}
			$this->_helper->redirector('index', 'dashboard');
		}
	}

	/**
	 * Perform the new tournament creation
	 *
	 * @access protected
	 * @return The newly created bracket's ID
	 */
	protected function _create() {
		$bracket = new Tournament_Model_Bracket();
		$bracket->create($this->getRequest()->getPost('bracket'), $this->getRequest()->getPost('randomize'));
		$bracket->userId = $this->user->id;
		$bracket->title = $this->getRequest()->getPost('title');
		$bracket->description = $this->getRequest()->getPost('description');

		if ($bracket->validate() !== TRUE) {
			$this->view->bracket = $bracket;
			return FALSE;
		}
		else {
			return $this->mapper->save($bracket);
		}
	}

	/**
	 * Perform the tournament editing
	 *
	 * @access protected
	 * @param  Tournament_Model_Bracket $bracket The bracket to update
	 * @return The updated bracket's id
	 */
	protected function _edit($bracket) {
		$entrants = $this->getRequest()->getPost('bracket');
		// recreate bracket if entrants was updated
		for ($i = 0; $i < $bracket->size; $i++) {
			if ($entrants[$i] != $bracket->data[$i]) {
				if ($entrants[$i] !== '' || $bracket->data[$i] !== NULL) {
					$bracket->create($entrants, $this->getRequest()->getPost('randomize'));
					break;
				}
			}
		}
		$bracket->title = $this->getRequest()->getPost('title');
		$bracket->description = $this->getRequest()->getPost('description');
		$this->view->bracket = $bracket;

		if ($bracket->validate() !== TRUE) {
			$this->view->bracket = $bracket;
			return FALSE;
		}
		else {
			return $this->mapper->save($bracket);
		}
	}

}
