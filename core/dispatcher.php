<?php
	class Dispatcher {
		protected $router;
		
		protected $url;
		protected $controller;
		protected $action;
		protected $data;
		protected $params;
		
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
				$result['params'][] = $temp[$i];
			}
			$this->params = $result['params'];
			
			return $result;
		}
		
		protected function __loadController($url) {
			$controller = Core::camelize($url['controller']);
			
			if(Core::Import('Controller', $controller) === false)
				return false;
			
			$controller_class = $controller . 'Controller';
			
			$this->controller = new $controller_class;
			$this->controller->action = $this->action;
			$this->controller->params = $this->params;
			$this->controller->data = $this->data;
			
			return true;
		}
	}
?>