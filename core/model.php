<?php
	class Model {
		// Estos valores solo tienen sentido para establecer los valores
		// iniciales de la clase, pues que después pueden ser sobrescritos con
		// los campos del modelo. Por lo tanto se recomienda usar las variables
		// $this->__name, $this->__table y $this->__db_source en su reemplazo.
		var $name = null;
		var $table = null;
		var $db_source = 'default';
		
		protected $_db = null;
		protected $_fields = array();
		
		var $__name = null;
		var $__table = null;
		vard $__db_source = null;
		
		function __construct($table = null, $db_source = null) {
			// Establecemos el nombre del modelo, que se carga desde la variables
			// $name, la cual debe ser establecida al declarar la clase. En caso de
			// no estar seteada dicha variable, se intenta cargar $this->__name
			// usando la función getClass(class) (la cual sólo funciona en PHP > 5).
			if($this->name == null) {
				// Esto solo funciona para PHP > 5
				if(function_exists('getClass'))
					$this->__name = getClass($this);
			} else {
				$this->__name = $this->name;
			}
			
			// Establecemos el nombre de la table a la que representa el modelo.
			if($table != null)
				$this->__table = $table;
			else
				$this->__table = $this->table;
			
			// Establecemos la fuente de datos que se va a usar.
			if($db_source != null)
				$this->__db_source = $db_source;
			else
				$this->__db_source = $this->db_source;
			
			// Cargamos el manejador de Fuentes de datos
			Core::import('Core', 'DbSourcesManager');
			
			// Obtenemos la fuente de datos que haya configurado el usuario.
			$this->_db = DbSourcesManager::getDbSource($this->db_source);
			$this->_db->connect();
			
			// Definimos las variables que representan a los campos del modelo.
			$fields = $this->_db->describe($this);
			foreach($fields as $field) {
				$this->{$field['Field']} = null;
				$this->_fields[] = $field['Field'];
			}
			
			//$this->db->disconnect();
		}
		
		function find($type, $conditions) {
			switch ($type) {
				case 'first':
					$conditions = array_merge( $conditions,
						array('limit' => 1)
					);
					
					break;
				case 'count':
					$conditions = array_merge( $conditions,
						array('fields' => 'COUNT(*)')
					);
					
					break;
				case 'all':
				default:
					break;
			}
			
			
			return $this->_db->read($this, $conditions);
		}
		
		function save() {}
		function delete() {}
		
		function load($id = null) {
			if($id == null)
				$id = $this->id;
			
			if($id == null)
				return false;
				
			$result = $this->find('first', array('conditions' => array(array('id', '=', $id))));
			
			if(empty($result))
				return false;
			
			foreach($result[0] as $field => $value) {
				$this->{$field} = $value;
			}
			
			return true;
		}
	}
?>