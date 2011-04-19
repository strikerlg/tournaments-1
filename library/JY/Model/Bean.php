<?php
/**
 * Base class for models using the bean pattern
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
abstract class JY_Model_Bean {

	/**
	 * Automatic getter and setter functions
	 *
	 * @access public
	 * @param  string $name Function name
	 * @param  array $arguments Function arguments
	 * @return mixed
	 */
	public function __call($name, $arguments) {
		if (strpos($name, 'get') === 0) {
			$property = strtolower(substr($name,3,1)) . substr($name,4);
			return $this->$property;
		}
		elseif (strpos($name, 'set') === 0) {
			$property = strtolower(substr($name,3,1)) . substr($name,4);
			$this->$property = $arguments[0];
		}
		else {
			throw new Exception("Method $name does not exist");
		}
	}

	/**
	 * Call getter function if it exists for this property, otherwise get normally
	 *
	 * @access public
	 * @param  string $name Property name
	 * @return mixed
	 */
	public function __get($name) {
		$getter = 'get' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return call_user_func(array($this, $getter));
		}
		return $this->$name;
	}

	/**
	 * Call setter function if it exists for this property, otherwise set normally
	 *
	 * @access public
	 * @param  string $name Property name
	 * @param  mixed $value Value to set the property to
	 */
	public function __set($name, $value) {
		$setter = 'set' . ucfirst($name);
		if (method_exists($this, $setter)) {
			call_user_func(array($this, $setter), $value);
		}
		else {
			$this->$name = $value;
		}
	}
}

/* End of file Bean.php
/* Location: library/JY/Model/Bean.php */
