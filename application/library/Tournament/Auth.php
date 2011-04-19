<?php
/**
 * Tournament auth library
 *
 * @package	   Tournament
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Handles authentication of users
 *
 * @package	   Tournament
 * @author     Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Auth {

	/**
	 * The logged in Tournament_Model_User
	 */
	private static $_user;

	/**
	 * Static class
	 *
	 * @access private
	 */
	private function __construct() {}

	/**
	 * Get the logged in user
	 *
	 * @access public
	 * @return Tournament_Model_User The logged in user
	 */
	public static function getInstance() {
		if (self::$_user === NULL) {
			$userId = self::getSession();
			self::loadUser($userId);
		}
		return self::$_user;
	}

	/**
	 * Login as a guest
	 *
	 * @access public
	 * @return Tournament_Model_User The guest user
	 */
	public static function loginAsGuest() {
		self::setSession('');
		self::$_user = new Tournament_Model_User();
		return self::$_user;
	}

	/**
	 * Perform a login with a login and password
	 *
	 * @access public
	 * @param  string $login The login
	 * @param  string $password The password
	 * @return Tournament_Model_User The logged in user
	 */
	public static function login($login, $password) {
		if (empty($login) || empty($password)) return self::loginAsGuest();

		$userMapper = new Tournament_Model_Mapper_User();
		self::$_user = $userMapper->authenticate($login, $password);
		if (self::$_user === NULL) {
			return self::loginAsGuest();
		}
		self::setSession(self::$_user->getId());
		return self::$_user;
	}

	/**
	 * Perform a login based on the user ID
	 *
	 * @access public
	 * @param  int $user The user ID
	 * @return Tournament_Model_User The logged in user
	 */
	public static function loadUser($user) {
		if (empty($user)) return self::loginAsGuest();

		$userMapper = new Tournament_Model_Mapper_User();
		self::$_user = $userMapper->find($user);
		if (self::$_user === NULL) {
			return self::loginAsGuest();
		}
		self::setSession(self::$_user->getId());
		return self::$_user;
	}

	/**
	 * Log the user out
	 *
	 * @access public
	 */
	public static function logout() {
		self::loginAsGuest();
	}

	/**
	 * Set the auth session variable
	 *
	 * @access protected
	 * @param  mixed Value to set the auth variable to
	 */
	protected static function setSession($value) {
		$_SESSION['auth_id'] = $value;
	}

	/**
	 * Get the auth session variable
	 *
	 * @access protected
	 * @return mixed
	 */
	protected static function getSession() {
		return isset($_SESSION['auth_id']) ? $_SESSION['auth_id'] : NULL;
	}

}
