<?php
/**
 * Use custom layout for a module if it exists
 *
 * @author     Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @since      File available since Release 1.10.1
 */

/**
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class JY_Controller_Plugin_ModuleLayout extends Zend_Controller_Plugin_Abstract {

	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		$moduleName = $request->getModuleName();
		$layout = Zend_Layout::getMvcInstance();
		$layoutFile = $layout->getLayoutPath() . $moduleName . '.' . $layout->getViewSuffix();
		if (file_exists($layoutFile)) {
			$layout->setLayout($moduleName);
		}
	}

}

/* End of file ModuleLayout.php
/* Location: library/JY/Controller/Plugin/ModuleLayout.php */
