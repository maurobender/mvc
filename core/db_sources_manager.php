<?php
	class DbSourcesManager {
		static function getDbSource($db_source_name) {
			$db_source_config = self::getDbSourceConfig($db_source_name);
			
			if($db_source_config === false)
				return false; // Error
			
			$db_source_class = 'Db' . Core::camelize($db_source_config['source']);
			if(!Core::import('DbSource', $db_source_class))
				return false; // Error
				
			$db_source = new $db_source_class($db_source_config);
			
			return $db_source;
		}
		
		static function getDbSourceConfig($db_source_name) {
			Core::import('Config', 'Database');
			
			$dbc = new DatabaseCofig();
			$db_configs = get_object_vars($dbc);
			
			if(in_array($db_source_name, array_keys($db_configs)))
				return $db_configs[$db_source_name];
			else 
				return false; // Error
		}
	}
?>