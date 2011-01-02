<?php
	class View {
		var $layout = 'default';
		var $viewFolder = null;
		var $viewVars = array();
		
		public function __construct(&$controller) {
			foreach($controller->viewVars as $var_name => $var_value) {
				$this->{$var_name} = $var_value;
			}
			
			$this->viewFolder = VIEWS_FOLDER . DS . Core::Camelize($controller->name);
		}
		
		public function render($action, $layout = null) {
			// Si no nos pasan una layout usamos la que ya está definida por default.
			if($layout == null) {
				$layout = $this->layout;
			}
			
			// Chequeamos que el archivo de la vista exista
			$view = $this->viewFolder . DS . $action . '.php';
			if(file_exists($view)) {
				$result = $this->_render($view, $this->viewVars);
			} else {
				$result = error(preg_replace(array('/%ACTION%/', '/%VIEW_FILE%/', '/%VIEW_FOLDER%/'), array($action, $action . '.php', $this->viewFolder), Error::$missing_view), true);
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
				$this->viewVars
			);
			
			// Chequeamos que el archivo de la layout exista.
			$layoutFile = LAYOUTS_FOLDER . DS . $layout . '.php';
			if(file_exists($layoutFile)) {
				$result = $this->_render($layoutFile, $layoutVars);
			} else {
				$result = error(preg_replace(array('/%LAYOUT%/', '/%LAYOUT_FILE%/', '/%LAYOUTS_FOLDER%/'), array($layout, $layout . '.php', LAYOUTS_FOLDER), Error::$missing_layout), true);
			}
			
			return $result;
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