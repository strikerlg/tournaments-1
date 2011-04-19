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
 * Main ACL class
 *
 * @package	   Tournament
 * @author     Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Acl {

	/**
	 * Cached ACL roles/resources
	 */
	private static $_acl;

	/**
	 * Static class
	 *
	 * @access private
	 */
	private function __construct() {}

	/**
	 * Is the user allowed to access this resource
	 *
	 * @access public
	 * @param  Tournament_Model_User $user The user to check
	 * @param  string $resource The resource to check for access to
	 * @return boolean Whether the user is allowed to access the resource
	 */
	public static function isAllowed(Tournament_Model_User $user, $resource) {
		if (self::$_acl === NULL) {
			$mapper = new Tournament_Acl_Mapper_Acl();
			self::$_acl = $mapper->fetchAll();
		}

		// admins can access everything
		if ($user->getRoleId() == Tournament_Constants_User_Role::ADMIN) {
			return TRUE;
		}

		// no assigned resources for this role
		if (empty(self::$_acl[$user->getRoleId()])) {
			return FALSE;
		}

		// this resource is assigned to this role
		if (in_array($resource, self::$_acl[$user->getRoleId()])) {
			return TRUE;
		}

		return FALSE;
	}

}
