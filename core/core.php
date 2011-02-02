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
			define('WEBROOT_FOLDER', ROOT . DS . 'webroot');
			
			define('CORE_CONTROLLERS_FOLDER', CORE_FOLDER . DS . 'controllers');
			define('CORE_MODELS_FOLDER', CORE_FOLDER . DS . 'models');
			define('CORE_VIEWS_FOLDER', CORE_FOLDER . DS . 'views');
			
			define('LAYOUTS_FOLDER', VIEWS_FOLDER . DS . 'layouts');
			define('DBSOURCES_FOLDER', MODELS_FOLDER . DS . 'dbsources');
			
			define('CORE_LAYOUTS_FOLDER', CORE_VIEWS_FOLDER . DS . 'layouts');
			define('CORE_DBSOURCES_FOLDER', CORE_MODELS_FOLDER . DS . 'dbsources');
			
			define('IMG_FOLDER', WEBROOT_FOLDER . DS . 'img');
			define('JS_FOLDER', WEBROOT_FOLDER . DS . 'js');
			define('CSS_FOLDER', WEBROOT_FOLDER . DS . 'css');
			define('FILES_FOLDER', WEBROOT_FOLDER . DS . 'files');
			
			self::_loadMainClasses();
			self::_loadConfig();
		}
		
		static private function _loadMainClasses() {
			// Load the main classes
			include_once(CORE_FOLDER . DS . 'common.php');
 			include_once(CORE_FOLDER . DS . 'dispatcher.php');
 			include_once(CORE_CONTROLLERS_FOLDER . DS . 'controller.php');
 			include_once(CORE_MODELS_FOLDER . DS . 'db_sources_manager.php');
 			include_once(CORE_MODELS_FOLDER . DS . 'model.php');
 			include_once(CORE_DBSOURCES_FOLDER . DS . 'db_source.php');
 			include_once(CORE_VIEWS_FOLDER . DS . 'view.php');
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
					
					if(file_exists(CORE_CONTROLLERS_FOLDER . DS . $controller_file)) {
						include_once(CORE_CONTROLLERS_FOLDER . DS . $controller_file);
						$result = true;
					} elseif(file_exists(CONTROLLERS_FOLDER . DS . $controller_file)) {
						include_once(CONTROLLERS_FOLDER . DS . $controller_file);
						$result = true;
					} else {
						//TODO Imprimir error.
					}
					
					break;
				case 'Model':
					$model_class = $name;
					$model_file = self::underscore($name) . '.php';
					
					if(file_exists(CORE_MODELS_FOLDER . DS . $model_file)) {
						include_once(CORE_MODELS_FOLDER . DS . $model_file);
						$result = true;
					} elseif(file_exists(MODELS_FOLDER . DS . $model_file)) {
						include_once(MODELS_FOLDER . DS . $model_file);
						$result = true;
					} else {
						//TODO Imprimir error.
					}
					
					break;
				case 'Config':
					$config_file  = self::underscore($name) . '.php';
					if(file_exists(CONFIG_FOLDER . DS . $config_file)) {
						include_once(CONFIG_FOLDER . DS . $config_file);
						$result = true;
					} else {
						//TODO Imprmir error.
					}
					
					break;
				case 'DbSource':
					$dbs_file  = self::underscore($name) . '.php';
					
					if(file_exists(CORE_DBSOURCES_FOLDER . DS . $dbs_file)) {
						// Buscamos el datasource en los datasources definidos en el core.
						include_once(CORE_DBSOURCES_FOLDER . DS . $dbs_file);
						$result = true;
					} elseif (file_exists(DBSOURCES_FOLDER . DS . $dbs_file)) {
						// Buscamos el datasource en los datasources definidos por el usuario.
						include_once(DBSOURCES_FOLDER . DS . $dbs_file);
						$result = true;
					} else {
						//TODO Imprimir error.
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
	}
?>