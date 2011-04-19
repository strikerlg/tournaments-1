<?php
/**
 * Autoload models when using a modules in MVC
 *
 * @author     Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @since      File available since Release 1.10.1
 */

/**
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class JY_Controller_Plugin_ModelLoader extends Zend_Controller_Plugin_Abstract {

	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$moduleName = $request->getModuleName();
		$loader = new Zend_Loader_Autoloader_Resource(array (
			'basePath' => APPLICATION_PATH . '/modules/' . $moduleName,
			'namespace' => ucfirst($moduleName),
		));
		$loader->addResourceType('model', 'models', 'Model');
	}

}

/* End of file ModelLoader.php
/* Location: library/JY/Controller/Plugin/ModelLoader.php */
