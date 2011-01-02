<?php
	class Core {
		static public function Init() {
			define('DS', DIRECTORY_SEPARATOR);
			
			// Folders paths
			define('ROOT', dirname(dirname(__FILE__)));
			define('CORE_FOLDER', ROOT . DS . 'core');
			define('CONFIG_FOLDER', ROOT . DS . 'config');
			define('CONTROLLERS_FOLDER', ROOT . DS . 'controllers');
			define('VIEWS_FOLDER', ROOT . DS . 'views');
			define('MODELS_FOLDER', ROOT . DS . 'models');
			define('WEBROOT_FOLDER', ROOT . DS . 'webroot');
			
			define('LAYOUTS_FOLDER', VIEWS_FOLDER . DS . 'layouts');
			
			define('IMG_FOLDER', WEBROOT_FOLDER . DS . 'img');
			define('JS_FOLDER', WEBROOT_FOLDER . DS . 'js');
			define('CSS_FOLDER', WEBROOT_FOLDER . DS . 'css');
			define('FILES_FOLDER', WEBROOT_FOLDER . DS . 'files');
			
			// Load the main classes
			include_once(CORE_FOLDER . DS . 'common.php');
 			include_once(CORE_FOLDER . DS . 'dispatcher.php');
 			include_once(CORE_FOLDER . DS . 'controller.php');
 			include_once(CORE_FOLDER . DS . 'model.php');
		}
		
		static public function Import($from, $name) {
			$result = true;
			switch($from) {
				case 'Core':
					$class_file = self::Undescore($name) . '.php';
					if(!@include_once(CORE_FOLDER . DS . $class_file)) {
						$result = false;
					}
					
					break;
				case 'Controller':
					$controller_class = $name . 'Controller';
					$controller_file = self::Undescore($controller_class) . '.php';
					
					if(!@include_once(CONTROLLERS_FOLDER . DS . $controller_file)) {
						error(preg_replace(array('/%CONTROLLER%/', '/%NAME%/', '/%CONTROLLER_FILE%/'), array($controller_class, $name, $controller_file), Error::$missing_controller));
						$result = false;
					}
					
					break;
				default:
					error('Can\'t find the library ' . $from);
					$result = false;
					break;
			}
			
			return $result;
		}
		
		static public function Undescore($class) {
			$result = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class));
			return $result;
		}
		
		static public function Camelize($undescored) {
			$result = preg_replace('/ /', '', ucwords(preg_replace('/_/', ' ', $undescored)));
			return $result;
		}
	}
?>