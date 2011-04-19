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
 * Represents a single user
 *
 * An empty $roleId represents a guest
 * 
 * @package	   Tournament_Model
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Model_User extends JY_Model_Bean {

	/**
	 * @var Mixed All user attributes
	 */
	protected $id, $email, $username, $role, $created, $updated, $lastLogin;
	// default role is guest
	protected $roleId = 0;

	/**
	 * Populate attributes of the user
	 *
	 * @access public
	 * @param  array $data An associative array of column => values
	 */
	public function populate($data) {
		$this->setId($data['id']);
		$this->setEmail($data['email']);
		$this->setUsername($data['username']);
		$this->setRoleId($data['role_id']);
		$this->setLastLogin($data['created']);
		$this->setCreated($data['created']);
		$this->setUpdated($data['updated']);
	}

	/**
	 * Convert this user object into an associative array to store into the database
	 *
	 * @access public
	 * @return array An associative array of column => values
	 */
	public function export() {
		$data = array(
			'id' => $this->getId(),
			'email' => $this->getEmail(),
			'username' => $this->getUsername(),
			'role_id' => $this->getRoleId(),
			'updated' => date('Y-m-d H:i:s'),
		);
		if (empty($data['id'])) {
			unset($data['id']);
			$data['created'] = $data['updated'];
		}
		return $data;
	}

	/**
	 * Determine if the user is allowed to access the resource or not
	 *
	 * @access public
	 * @param  string $resource The resource to check for access to
	 * @return boolean Whether or not the user can access the resource
	 */
	public function isAllowed($resource) {
		return Tournament_Acl::isAllowed($this, $resource);
	}

	/**
	 * Return whether the user is a guest or not
	 *
	 * @access public
	 * @return boolean Whether the user is a guest or not
	 */
	public function isGuest() {
		return $this->getRoleId() == Tournament_Constants_User_Role::GUEST;
	}

}
