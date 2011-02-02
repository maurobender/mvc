<?php
	abstract class DbSource {
		var $name = null;
		
		abstract public function connect();
		abstract public function connected();
		abstract public function describe(&$model);
		abstract public function query(&$model, $query);
		abstract public function disconnect();
	}
?>
