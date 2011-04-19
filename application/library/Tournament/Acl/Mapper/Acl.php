<?php
/**
 * Tournament ACL library
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Mapping of the access control list
 *
 * @package	   Tournament
 * @author     Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Acl_Mapper_Acl {

	/**
	 * @var array The ACL mapping
	 */
	protected $acl = array(
		Tournament_Constants_User_Role::GUEST => array(
			'default/index',
			'default/tournaments/index',
		),
		Tournament_Constants_User_Role::MEMBER => array(
			'default/index',
			'default/dashboard',
			'default/tournaments',
		)
	);

	/**
	 * Retrieve all of the role => resources pairs in the database
	 *
	 * @access public
	 * @return array An array of roleId => array(resources)
	 */
	public function fetchAll() {
		return $this->acl;
	}

}
