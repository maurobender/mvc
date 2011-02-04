<?php
	class Core {
		static public function init() {
			define('DS', DIRECTORY_SEPARATOR);
			
			// Folders paths
			define('ROOT', dirname(dirname(__FILE__)));
			define('CORE_FOLDER', ROOT . DS . 'core');
			define('CONFIG_FOLDER', ROOT . DS . 'config');
			define('CONTROLLERS_FOLDER', ROOT . DS . 'controllers');
			define('VIEWS_FOLDER', ROOT . DS . 'views');
			define('MODELS_FOLDER', ROOT . DS . 'models');
			
			define('CORE_CONTROLLERS_FOLDER', CORE_FOLDER . DS . 'controllers');
			define('CORE_MODELS_FOLDER', CORE_FOLDER . DS . 'models');
			define('CORE_VIEWS_FOLDER', CORE_FOLDER . DS . 'views');
			
			define('LAYOUTS_FOLDER', VIEWS_FOLDER . DS . 'layouts');
			define('HELPERS_FOLDER', VIEWS_FOLDER . DS . 'helpers');
			define('DBSOURCES_FOLDER', MODELS_FOLDER . DS . 'dbsources');
			
			define('CORE_LAYOUTS_FOLDER', CORE_VIEWS_FOLDER . DS . 'layouts');
			define('CORE_HELPERS_FOLDER', CORE_VIEWS_FOLDER . DS . 'helpers');
			define('CORE_DBSOURCES_FOLDER', CORE_MODELS_FOLDER . DS . 'dbsources');
			
			define('WEBROOT_FOLDER', ROOT . DS . 'webroot');
			
			// Urls
			define('APP_BASE_URL', dirname($_SERVER['PHP_SELF']));
			define('APP_ABSOLUTE_URL', 'http://' . $_SERVER['SERVER_NAME'] . APP_BASE_URL);
			define('WEBROOT_URL', APP_ABSOLUTE_URL . '/webroot');
			define('IMG_URL', WEBROOT_URL . '/img');
			define('JS_URL', WEBROOT_URL . '/js');
			define('CSS_URL', WEBROOT_URL . '/css');
			define('FILES_URL', WEBROOT_URL . '/files');
			
			self::_loadMainClasses();
			self::_loadConfig();
		}
		
		static private function _loadMainClasses() {
			// Load the main classes
			include_once(CORE_FOLDER . DS . 'common.php');
 			include_once(CORE_FOLDER . DS . 'dispatcher.php');
 			include_once(CORE_FOLDER . DS . 'error.php');
 			include_once(CORE_CONTROLLERS_FOLDER . DS . 'controller.php');
 			include_once(CORE_MODELS_FOLDER . DS . 'db_sources_manager.php');
 			include_once(CORE_MODELS_FOLDER . DS . 'model.php');
 			include_once(CORE_DBSOURCES_FOLDER . DS . 'db_source.php');
 			include_once(CORE_VIEWS_FOLDER . DS . 'view.php');
 			include_once(CORE_HELPERS_FOLDER . DS . 'helper.php');
		}
		
		static private function _loadConfig() {
			// Load Config
			include_once(CONFIG_FOLDER . DS . 'core.php');
 			include_once(CONFIG_FOLDER . DS . 'database.php');
		}
		
		static public function import($from, $name) {
			$result = false;
			
			switch($from) {
				case 'Core':
					$class_file = self::underscore($name) . '.php';
					if(file_exists(CORE_FOLDER . DS . $class_file)) {
						include_once(CORE_FOLDER . DS . $class_file);
						$result = true;
					} else {
						//TODO Imprimir error.
					}
					
					break;
				case 'Controller':
					$controller_class = $name . 'Controller';
					$controller_file = self::underscore($controller_class) . '.php';
					
					if(file_exists(CONTROLLERS_FOLDER . DS . $controller_file)) {
						include_once(CONTROLLERS_FOLDER . DS . $controller_file);
						$result = true;
					} elseif(file_exists(CORE_CONTROLLERS_FOLDER . DS . $controller_file)) {
						include_once(CORE_CONTROLLERS_FOLDER . DS . $controller_file);
						$result = true;
					} else {
						Core::addError(Error::StandardError('MISSING_CONTROLLER', array('controller' => $name), array('return' => true)));
						Dispatcher::dispatchDefaultController('error');
						exit;
					}
					
					break;
				case 'Model':
					$model_class = $name;
					$model_file = self::underscore($name) . '.php';
					
					if(file_exists(MODELS_FOLDER . DS . $model_file)) {
						include_once(MODELS_FOLDER . DS . $model_file);
						$result = true;
					} elseif(file_exists(CORE_MODELS_FOLDER . DS . $model_file)) {
						include_once(CORE_MODELS_FOLDER . DS . $model_file);
						$result = true;
					} else {
						Error::StandardError('MISSING_MODEL', array('model' => $name), array('type' => 'WARNING'));
					}
					
					break;
				case 'Config':
					$config_file  = self::underscore($name) . '.php';
					if(file_exists(CONFIG_FOLDER . DS . $config_file)) {
						include_once(CONFIG_FOLDER . DS . $config_file);
						$result = true;
					} else {
						Error::StandardError('MISSING_CONFIG', array('config' => $name), array('type' => 'WARNING'));
					}
					
					break;
				case 'DbSource':
					$dbs_file  = self::underscore($name) . '.php';
					
					if(file_exists(DBSOURCES_FOLDER . DS . $dbs_file)) {
						// Buscamos el datasource en los datasources definidos en el core.
						include_once(DBSOURCES_FOLDER . DS . $dbs_file);
						$result = true;
					} elseif (file_exists(CORE_DBSOURCES_FOLDER . DS . $dbs_file)) {
						// Buscamos el datasource en los datasources definidos por el usuario.
						include_once(CORE_DBSOURCES_FOLDER . DS . $dbs_file);
						$result = true;
					} else {
						Error::StandardError('MISSING_DBSOURCE', array('dbsource' => $name), array('type' => 'WARNING'));
					}
					break;
				case 'Helper':
					$helper_file  = self::underscore($name) . '.php';
					
					if(file_exists(HELPERS_FOLDER . DS . $helper_file)) {
						// Buscamos el datasource en los datasources definidos en el core.
						include_once(HELPERS_FOLDER . DS . $helper_file);
						$result = true;
					} elseif (file_exists(CORE_HELPERS_FOLDER . DS . $helper_file)) {
						// Buscamos el datasource en los datasources definidos por el usuario.
						include_once(CORE_HELPERS_FOLDER . DS . $helper_file);
						$result = true;
					} else {
						Error::StandardError('MISSING_HELPER', array('helper' => $name), array('type' => 'WARNING'));
					}
					break;
				default:
					error('Can\'t find the library ' . $from);
					$result = false;
					break;
			}
			
			return $result;
		}
		
		static public function underscore($class) {
			$result = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class));
			return $result;
		}
		
		static public function camelize($undescored) {
			$result = preg_replace('/ /', '', ucwords(preg_replace('/_/', ' ', $undescored)));
			return $result;
		}
		
		static protected $_errors = array();
		
		static public function addError($error) {
			array_push(self::$_errors, $error);
			
		}
		
		static public function getErrors() {
			return self::$_errors;
		}
	}
?>