<?php
	class Dispatcher {
		protected $router;
		
		protected $url;
		protected $controller;
		protected $action;
		
		protected $data;
		protected $params;
		protected $named;
		
		protected $defaul_controller;
		protected $defaul_action;
		
		public function __construct() {
			Core::import('Core', 'Router');
			
			$this->router = new Router();
			$this->default_controller = 'default';
			$this->default_action = 'index';
		}
		
		public function dispatch() {
			$url = $this->getUrl();
			
			if(!$this->__loadController($url))
				return;
			
			$this->controller->execute();
			//print_r($this->controller);
		}
		
		public function getUrl() {
			if(isset($_GET['url'])) {
				$url = $_GET['url'];
			} else {
				$url = $_GET['url'] = '';
			}
			
			$this->url = $url;
			$temp = preg_split('/\//', $url, null, PREG_SPLIT_NO_EMPTY);
			
			$result = array();
			
			if(sizeof($temp) > 0)
				$result['controller'] = $temp[0];
			else
				$result['controller'] = $this->default_controller;
			$this->controller = $result['controller'];
			
			if(sizeof($temp) > 1)
				$result['action'] = $temp[1];
			else
				$result['action'] = $this->default_action;
			$this->action = $result['action'];
			
			$result['params'] = array();
			$result['named'] = array();
			for($i = 2; $i < sizeof($temp); $i++) {
				preg_match('/(.*):(.*)/', $temp[$i], $named);
				
				if(sizeof($named) == 3) {
					$result['named'][$named[1]] = $named[2];
				} else {
					$result['params'][] = $temp[$i];
				}
			}
			$this->params = $result['params'];
			$this->named = $result['named'];
			
			return $result;
		}
		
		public static function dispatchDefaultController($action = 'index') {
			Core::import('Controller', 'Default');
			
			$controller = new DefaultController;
			$controller->action = $action;
			$controller->execute();
		}
		
		/**
		* Está función carga un controlador y lo incializa con los parametross
		* indicados
		* @param $url Un array que contiene el controlador a cargar.
		* @return @b true en caso de que se haya podido cargar correctamente
		* el controlador, o @b false en caso contrario.
		*/
		protected function __loadController($url) {
			$controller = Core::camelize($url['controller']);
			
			if(Core::import('Controller', $controller) === false)
				return false;
			
			$controller_class = $controller . 'Controller';
			
			$this->controller = new $controller_class;
			$this->controller->action = $this->action;
			$this->controller->params = $this->params;
			$this->controller->named = $this->named;
			$this->controller->data = $this->data;
			
			return true;
		}
	}
?>