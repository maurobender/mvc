<?php
	class View {
		public $controller = null;	
		public $layout = 'default';
		
		protected $_helpers = array();
		protected $_layoutVars = array();
		protected $_viewVars = array();
		protected $_scripts = array();
		
		
		public function __construct(&$controller) {
			foreach($controller->viewVars as $var_name => $var_value) {
				$this->{$var_name} = $var_value;
			}
			
			$this->controller = $controller;
			
		}
		
		public function setVars(array $vars, $append = false) {
			if($append) {
				$this->_viewVars = array_merge($this->_viewVars, $var );
			} else {
				$this->_viewVars = $vars;
			}
		}
		
		public function addHelpers($helper) {
			if(is_array($helper)) {
				$this->_helpers = array_merge($this->_helpers, $helper);
			} else {
				array_push($this->_helpers, $helper);
			}
		}
		
		public function addScript($script) {
			array_push($this->_scripts, $script);
		}
		
		public function render($action, $layout = null) {
			// Si no nos pasan una layout usamos la que ya está definida por default.
			if($layout == null) {
				$layout = $this->layout;
			}
			
			// Creamos todos los helpers
			foreach($this->_helpers as $helper) {
				if(Core::Import('Helper', $helper)) {
					$helper_class = $helper . 'Helper';
					$this->{$helper} = new $helper_class($this);
				}
			}
			
			// Chequeamos que el archivo de la vista exista
			$view = $this->_getViewFile($action);
			if($view !== false) {
				$result = $this->_render($view, $this->_viewVars);
			} else {
				$result = Error::StandardError('MISSING_VIEW', array('action' => $action, 'controller' => $this->controller->name), true);
			}
			
			// Si hay una layout para usar, la randerizamos.
			if($layout != null) {
				$result = $this->_renderLayout($layout, $result);
			}
			
			return $result;
		}
		
		protected function _renderLayout($layout, $content) {
			$layoutVars = array_merge(
				array('content_for_layout' => $content),
				$this->_viewVars
			);
			
			// Chequeamos que el archivo de la layout exista.
			$layoutFile = $this->_getLayoutFile($layout);
			if($layoutFile !== false) {
				$result = $this->_render($layoutFile, $layoutVars);
			} else {
				$result = error(preg_replace(array('/%LAYOUT%/', '/%LAYOUT_FILE%/', '/%LAYOUTS_FOLDER%/'), array($layout, $layout . '.php', LAYOUTS_FOLDER), Error::$missing_layout), true);
			}
			
			
			return $result;
		}
		
		
		protected function _getViewFile($action) {
			$view = CORE_VIEWS_FOLDER . DS . Core::underscore($this->controller->name) . DS . $action . '.php';
			if(file_exists($view))
				return $view;
			
			$view = VIEWS_FOLDER . DS . Core::underscore($this->controller->name) . DS . $action . '.php';
			if(file_exists($view))
				return $view;
			
			return false;
		}
		
		protected function _getLayoutFile($layout) {
			$layout = CORE_LAYOUTS_FOLDER . DS . $layout . '.php';
			if(file_exists($layout))
				return $layout;
			
			$layout = LAYOUTS_FOLDER . DS . $layout . '.php';
			if(file_exists($layout))
				return $layout;
			
			return false;
		}
		
		
		protected function _render($_viewFile, $_viewArgs) {
			extract($_viewArgs, EXTR_SKIP);
			ob_start();
			
			@include($_viewFile);
			
			$result = ob_get_clean();
			return $result;
		}
	}
?>