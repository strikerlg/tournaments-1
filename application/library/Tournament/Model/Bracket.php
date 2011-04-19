<?php
/**
 * Tournament data models
 *
 * @package	   Tournament_Model
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Represents a single tournament bracket
 *
 * @package	   Tournament_Model
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Model_Bracket extends JY_Model_Bean {

	/**
	 * @var Mixed All ticket attributes
	 */
	protected $id, $userId, $title, $description, $size, $showSeeds,
		$data, $statusId, $created, $updated;
	protected $status, $username;

	/**
	 *
	 * @var Tournament_Model_Bracket_Data Bracket helper to manipulate bracket data
	 */
	protected $helper;

	/**
	 * Populate attributes of the bracket
	 *
	 * @access public
	 * @param  array $data An associative array of column => values
	 */
	public function populate($data) {
		$this->setId($data['id']);
		$this->setUserId($data['user_id']);
		$this->setTitle($data['title']);
		$this->setDescription($data['description']);
		$this->setSize($data['size']);
		$this->setShowSeeds($data['show_seeds']);
		$this->setStatusId($data['status_id']);
		$this->setCreated($data['created']);
		$this->setUpdated($data['updated']);
		$this->helper = new Tournament_Model_Bracket_Data(json_decode($data['data']));
		$this->helper->setShowSeeds($this->getShowSeeds());
		$this->helper->setSize($this->getSize());
	}

	/**
	 * Convert this bracket object into an associative array to store into the database
	 *
	 * @access public
	 * @return array An associative array of column => values
	 */
	public function export() {
		$data = array(
			'id' => $this->getId(),
			'user_id' => $this->getUserId(),
			'title' => $this->getTitle(),
			'description' => $this->getDescription(),
			'size' => $this->getSize(),
			'show_seeds' => $this->getShowSeeds(),
			'data' => json_encode($this->getData()),
			'status_id' => $this->getStatusId(),
			'updated' => date('Y-m-d H:i:s'),
		);
		if (empty($data['id'])) {
			unset($data['id']);
			$data['created'] = $data['updated'];
		}
		return $data;
	}

	/**
	 * Fill the bracket with the initial array of entrants
	 *
	 * @access public
	 * @param  array $bracket An array of entrants
	 * @param  boolean $randomize Whether or not to randomize seeds
	 */
	public function create($bracket, $randomize = FALSE) {
		$this->helper = new Tournament_Model_Bracket_Data();
		$this->helper->setShowSeeds($this->getShowSeeds());
		$this->helper->create($bracket, $randomize);
		$this->setSize($this->helper->getSize());
	}

	/**
	 * Fetch the bracket data
	 *
	 * @access public
	 * @return array The bracket array
	 */
	public function getData() {
		return $this->helper->getBracket();
	}

	/**
	 * Validate a bracket
	 *
	 * @access public
	 * @return mixed The error message or boolean TRUE if valid
	 */
	public function validate() {
		return TRUE;
	}

}
