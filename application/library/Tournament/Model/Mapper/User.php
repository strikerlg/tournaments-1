<?php
/**
 * Mappings of tournament models to their sources
 *
 * @package	   Tournament_Model_Mapper
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Map the User model to the database
 *
 * @package	   Tournament_Model_Mapper
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Model_Mapper_User {

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
		$this->table = $prefix . 'users';
	}

	/**
	 * Find a user in the database
	 *
	 * @access public
	 * @param  mixed $user The user ID or username to look for
	 * @return Tournament_Model_User The requested user
	 */
	public function find($user) {
		$query = $this->db->select()->from($this->table);
		if (is_numeric($user)) {
			$query = $query->where("{$this->table}.id = ?", $user);
		}
		else {
			$query = $query->where("{$this->table}.username = ?", $user);
		}

		$result = $this->db->fetchRow($query);
		if (empty($result) || 0 == count($result)) {
			return NULL;
		}

		$user = new Tournament_Model_User();
		$user->populate($result);
		return $user;
	}

	/**
	 * Authenticate a login/password pair in the database
	 *
	 * @access public
	 * @param  string $login The login
	 * @param  string $password The password
	 * @return Tournament_Model_User The requested user
	 */
	public function authenticate($login, $password) {
		$query = $this->db->select()->from($this->table)->
			where("{$this->table}.username = ?", $login);

		$result = $this->db->fetchRow($query);
		if (empty($result) || 0 == count($result)) {
			return NULL;
		}

		$providedHash = sha1(Zend_Registry::get('salt') . $password . $result['salt']);

		if ($result['password'] != $providedHash) {
			return NULL;
		}

		$user = new Tournament_Model_User();
		$user->populate($result);
		return $user;
	}

	/**
	 * Retrieve users in the database
	 *
	 * @access public
	 * @param  Zend_Db_Select $query Query filters applied
	 * @return array An array of Tournament_Model_User
	 */
	public function fetchAll($query = NULL) {
		if ($query === NULL) {
			$query = $this->db->select();
		}
		$query = $query->from($this->table);
		$resultSet = $this->db->fetchAll($query);

		$users = array();
		foreach ($resultSet as $row) {
			$user = new Tournament_Model_User();
			$user->populate($row);
			$users []= $user;
		}
		return $users;
	}

	/**
	 * Register a user into the database
	 *
	 * @access public
	 * @param  Tournament_Model_User $user The user to save
	 * @param  string $password The password to hash and save
	 * @return Tournament_Registration_Status The result of the registration attempt
	 */
	public function register(Tournament_Model_User $user, $password = NULL) {
		$query = $this->db->select()->from($this->table);
		$query = $query->where("{$this->table}.email = ?", $user->email);
		$validateEmail = $this->db->fetchRow($query);
		if (!empty($validateEmail)) {
			return new Tournament_Registration_Status(Tournament_Registration_Status::ERROR_DUPLICATE_EMAIL);
		}

		$query = $this->db->select()->from($this->table);
		$query = $query->where("{$this->table}.username = ?", $user->username);
		$validateUsername = $this->db->fetchRow($query);
		if (!empty($validateUsername)) {
			return new Tournament_Registration_Status(Tournament_Registration_Status::ERROR_DUPLICATE_USERNAME);
		}

		$userId = $this->save($user, $password);
		return new Tournament_Registration_Status(Tournament_Registration_Status::SUCCESS, $userId);
	}

	/**
	 * Save a user into the database
	 *
	 * @access public
	 * @param  Tournament_Model_User $user The user to save
	 * @param  string $password The password to hash and save
	 * @return int The user ID of the saved user
	 */
	public function save(Tournament_Model_User $user, $password = NULL) {
		$data = $user->export();

		if ($password !== NULL) {
			// create the password hash
			$uniqueSalt = uniqid(mt_rand(), true);
			$data['password'] = sha1(Zend_Registry::get('salt') . $password . $uniqueSalt);
			$data['salt'] = $uniqueSalt;
		}

		if (empty($data['id'])) {
			$result = $this->db->insert($this->table, $data);
			return $this->db->lastInsertId();
		} else {
			$result = $this->db->update($this->table, $data, array('id = ?' => $data['id']));
			return $data['id'];
		}
	}

}

/**
 * Details of the registration attempt
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Registration_Status {

	/**
	 * Constants representing possible status codes
	 * @var int
	 */
	const SUCCESS = 0;
	const SUCCESS_EMAIL = 1;
	const ERROR_DUPLICATE_EMAIL = -1;
	const ERROR_DUPLICATE_USERNAME = -2;

	/**
	 * The status of the registration
	 * @var int
	 */
	private $status;

	/**
	 * The user ID of the registered user
	 * @var int
	 */
	private $userId;

	/**
	 * Map of messages corresponding to a status
	 * @var array
	 */
	private static $messages = array(
		self::SUCCESS => 'The registration was successful.',
		self::SUCCESS_EMAIL => 'The registration is awaiting email verification.',
		self::ERROR_DUPLICATE_EMAIL => 'The email address has already registered.',
		self::ERROR_DUPLICATE_USERNAME => 'The username has already registered.',
	);

	/**
	 * Create a registration response object with a status
	 *
	 * @param int $status The status of the registration
	 * @param int $userId The ID of the registered user
	 */
	public function __construct($status, $userId = NULL) {
		$this->status = (int) $status;
		$this->userId = (int) $userId;
	}

	/**
	 * Determine if the registration was successful or not
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->status >= 0;
	}

	/**
	 * Get the status message
	 *
	 * @return string
	 */
	public function getMessage() {
		return self::$messages[$this->status];
	}

	/**
	 * Get the status ID
	 *
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Get the user ID
	 *
	 * @return int
	 */
	public function getUserId() {
		return $this->userId;
	}

}
