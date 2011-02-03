<?php
	class Controller {
		var $name = null;
		var $action;
		
		var $params = array();
		var $named  = array();
		var $data   = array();
		
		var $layout   = 'default';
		var $viewVars = array();
		
		var $db_source = 'default';
		var $models = array();
		
		public function __construct() {
			if(empty($this->name)) {
				if(function_exists('get_class'))
					$this->name = str_replace('Controller', '', get_class($this));
			}
			
			// Importamos los modelos que vamos a usar;
			foreach($this->models as $model) {
				if(Core::import('Model', $model))
					$this->{$model} = new $model();
			}
			
		}
		
		function beforeAction() {
		}
		
		function afterAction() {
		}
		
		/**
		* @brief Ejecuta el controlador.
		* @return Se retorna true en caso de que la ejecución haya sido exitosa,
		* false en otro caso.
		*/
		function execute($action = null, $params = array()) {
			if($action == null)
				$action = $this->action;
			
			$params = array_merge($this->params, $params);
			
			
			// Si la acción no está definida entonces mostramos un error y terminamos.
			if(!method_exists($this, $action)) {
				Error::StandardError('MISSING_ACTION', array('controller' => $this->name, 'action' => $action));
				return false;
			}
			
			// Ejecutamos el callback beforeAction antes de llamar a la acción.
			$this->beforeAction();
			
			// Ejecutamos al acción.
			call_user_func_array(array(&$this, $action), $params);
			//$this->{$this->action}();
			
			// Ejecutamos el callback afterAction después de llamar a la acción.
			$this->afterAction();
			
			// Creamos la vista
			$view = new View($this);
			$view->viewVars = $this->viewVars;
			
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