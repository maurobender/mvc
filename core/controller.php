<?php
	class Controller {
		protected $name = null;
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
				// TODO: Tratamos de cargarlo desde el nombre de la clase (Sólo funciona en PHP > 5).
			}
			
			// Importamos los modelos que vamos a usar;
			foreach($this->models as $model) {
				Core::import('Model', $model);
				
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
				error(preg_replace(array('/%CONTROLLER%/', '/%NAME%/', '/%ACTION%/'), array($this->name . 'Controller', $this->name, $action), Error::$missing_action));
				return false;
			}
			
			// Ejecutamos el callback beforeAction antes de llamar a la acción.
			$this->beforeAction();
			
			// Ejecutamos al acción.
			call_user_func_array(array(&$this, $action), $params);
			//$this->{$this->action}();
			
			// Ejecutamos el callback afterAction después de llamar a la acción.
			$this->afterAction();
			
			// Importamos la clase View
			Core::import('Core', 'View');
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