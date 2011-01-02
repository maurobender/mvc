<?php
	class Model {
		protected $name;
		protected $table_name;
		
		protected $db;
		
		function __construct() {
			$db = new Database();
			
			$db->query("DESCRIBE $table_name");
		}
	}
?>