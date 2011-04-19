<?php
/**
 * Base class for model database mappers
 *
 * @package	   JY
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 * @since	   File available since Release 1.10.1
 */

/**
 * @package	   JY
 * @copyright  Copyright (c) 2010 Jim Yi
 */
abstract class JY_Model_DbMapper {
	
	/**
	 * @var object The actual DB adapter
	 *
	 */
	protected $db = NULL;

	/**
	 * Initialize the DB adapter
	 *
	 * @access public
	 */
	public function __construct() {
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
		$adapter = empty($config->resources->db->adapter) ? 'mysql' : $config->resources->db->adapter;
		if (strtolower($adapter) == 'mysql') {
			$this->db = Zend_Db::factory('Mysqli', array(
				'host'	=> $config->db->host,
				'dbname'	=> $config->db->dbname,
				'username'=> $config->db->username,
				'password'	=> $config->db->password)
			);
		}
	}

}

/* End of file DbMapper.php
/* Location: library/JY/Model/DbMapper.php */
