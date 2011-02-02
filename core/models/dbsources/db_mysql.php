<?php
	class DbMysql extends DbSource {
		protected $_config;
		protected $_db = null;
		
		var $error;
		
		function __construct($config) {
			$this->_config = $config;
		}
		
		function connect() {
			$connected_ok = $this->_db = new mysqli(
				$this->_config['host'],
				$this->_config['user'],
				$this->_config['password'],
				$this->_config['database']
			);
			
			if(!$connected_ok)
				echo 'Error <br />';
			
			//TODO: Manejo de errores
			return true;
		}
		
		function read(&$model, $conditions) {
			$query = 'SELECT ';
			if(isset($conditions['fields'])) {
				foreach($conditions['fields'] as $field) {
					$query .= $field . ', ';
				}
				
				if(!empty($conditions['fields']))
					$query = substr($query, -2);
			} else {
				$query .= ' * ';
			}
			
			$query .= ' FROM ' . $model->__table . ' ' . $model->__name;
			
			if(isset($conditions['conditions'])) {
				$query .= ' WHERE ';
				foreach($conditions['conditions'] as $condition) {
					if(sizeof($condition) != 3) {
						error('The supplied condition for the query doesn\'t have a valid size.', false, true);
						continue;
					}
					
					$query .= $condition[0] . ' ' . $condition[1] . ' ' . $condition[2];
				}
			}
			
			if(isset($conditions['limit'])) {
				$query .= ' LIMIT ';
				
				if(isset($conditions['offset'])) {
					$query .= $conditions['offset'] . ', ';
				}
				
				$query .= $conditions['limit'];
			}
			
			if(isset($conditions['order'])) {
				$query .= ' ORDER BY ';
				
				if(is_array($conditions['order']))
					$query .= $conditions['order'][0] . ' ' . strtoupper($conditions['order'][1]);
				else
					$query .= $conditions['order'] . ' ASC';
			}
			
			//TODO: Manejo de joins.
			
			return $this->query($model, $query);
		}
		
		function query(&$model, $query) {
			$result = array();
			$q_result = $this->_db->query($query, MYSQLI_USE_RESULT);
			if($q_result === false) {
				error($this->_db->error);
				error($query);
			}
			
			while($q_row = $q_result->fetch_assoc()) {
				$result_row = array();
				
				//TODO: Cambio de nombres, manejo del Model.Campo -> $result[Model][campo].
				$result_row = $q_row;
				$result[] = $result_row;
			}
			
			return $result;
		}
		
		function describe(&$model) {
			$query = 'DESCRIBE ' . $model->__table;
			
			return $this->query($model, $query);
		}
		
		function connected() {
			return ($db != null); // && $db->is_connected() ???
		}
		
		function disconnect() {
			
		}
	}
?>