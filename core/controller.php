<?php
	class Controller {
		protected $name;
		
		var $action;
		var $params;
		var $data;
		
		var $layout = 'default';
		var $viewVars = array();
		
		var $models;
		
		function beforeAction() {
		}
		
		function afterAction() {
		}
		
		/**
		* @brief Ejecuta el controlador.
		* @return Se retorna true en caso de que la ejecución haya sido exitosa,
		* false en otro caso.
		*/
		function execute() {
			// Si la acción no está definida entonces mostramos un error y terminamos.
			if(!method_exists($this, $this->action)) {
				error(preg_replace(array('/%CONTROLLER%/', '/%NAME%/', '/%ACTION%/'), array($this->name . 'Controller', $this->name, $this->action), Error::$missing_action));
				return false;
			}
			
			// Ejecutamos el callback beforeAction antes de llamar a la acción.
			$this->beforeAction();
			
			// Ejecutamos al acción.
			$this->{$this->action}();
			
			// Ejecutamos el callback afterAction después de llamar a la acción.
			$this->afterAction();
			
			// Importamos la clase View
			Core::Import('Core', 'View');
			$view = new View($this);
			
			// Mostramos la vista para la acción actual.
			if(($viewRendered = $view->render($this->action, $this->layout)) !== false)
				echo $viewRendered;
			
			return true;
		}
		
		/**
		* @brief Se encarga de establecer las variables que van a ser usadas en las vistas.
		* @param $key El nombre con el que se accedera a la variable dentro de la vista.
		* @param $value El valor de la variable definida.
		*/
		function set($key, $value) {
			$this->viewVars[$key] = $value;
		}
	}
?>