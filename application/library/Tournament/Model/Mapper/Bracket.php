<?php
/**
 * Mappings of tournament models to their sources
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Map the Bracket model to the database
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Model_Mapper_Bracket {

	/**
	 * @var string Main database table to map to
	 */
	protected $table;

	/**
	 * @var Zend_Db_Adapter_Abstract The database adapter
	 */
	protected $db;

	/**
	 * Initialize the database mapper
	 *
	 * @access public
	 */
	public function __construct() {
		// load the database adapter
		$this->db = Zend_Db_Table::getDefaultAdapter();

		// assign table names
		$prefix = Zend_Registry::get('table_prefix');
		$this->table = $prefix . 'tournaments';
	}

	/**
	 * Save a bracket into the database
	 *
	 * @access public
	 * @param  Tournament_Model_Bracket $bracket The bracket to save
	 * @return int The tournament ID of the saved tournament
	 */
	public function save(Tournament_Model_Bracket $bracket) {
		$data = $bracket->export();

		if (empty($data['id'])) {
			$result = $this->db->insert($this->table, $data);
			return $this->db->lastInsertId();
		} else {
			$result = $this->db->update($this->table, $data, array('id = ?' => $data['id']));
			return $data['id'];
		}
	}

	/**
	 * Delete a bracket from the database
	 *
	 * @access public
	 * @param  Tournament_Model_Bracket $bracket The bracket to delete
	 * @return TBD The result of the delete
	 */
	public function delete(Tournament_Model_Bracket $bracket) {
		$delete = $this->db->delete($this->table, array('id = ?' => $bracket->id));
		return $delete;
	}

	/**
	 * Find a bracket in the database
	 *
	 * @access public
	 * @param  int $id The bracket ID to look for
	 * @return Tournament_Model_Bracket The requested bracket
	 */
	public function find($id) {
		$query = $this->db->select()->from($this->table)->
			where("{$this->table}.id = ?", $id);

		$result = $this->db->fetchRow($query);
		if (0 == count($result)) {
			return NULL;
		}

		$bracket = new Tournament_Model_Bracket();
		$bracket->populate($result);
		return $bracket;
	}

	/**
	 * Retrieve all brackets created by a specific user in the database
	 *
	 * @access public
	 * @param  int $id The user ID
	 * @param  int $limit Maximum number of results
	 * @param  int $offset Maximum number of results
	 * @return array An array of Tournament_Model_Bracket
	 */
	public function fetchAllByUser($id, $limit = 50, $offset = 0) {
		$query = $this->db->select()->
			where("{$this->table}.user_id = ?", $id)->
			limit($limit, $offset);
		return $this->fetchAll($query);
	}

	/**
	 * Retrieve brackets in the database
	 *
	 * @access public
	 * @param  Zend_Db_Select $query Query with filters applied
	 * @param  string $select Custom select parameters
	 * @return array An array of Tournament_Model_Bracket
	 */
	public function fetchAll($query = NULL, $select = '*') {
		if ($query === NULL) {
			$query = $this->db->select();
		}
		$query = $query->from($this->table, $select);
		$resultSet = $this->db->fetchAll($query);

		$brackets = array();
		foreach ($resultSet as $row) {
			$bracket = new Tournament_Model_Bracket();
			$bracket->populate($row);
			$brackets []= $bracket;
		}
		return $brackets;
	}

}
