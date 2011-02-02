<?php
	include_once('core/core.php');
	
	Core::init();
	
	$dispatcher = new Dispatcher();
	$dispatcher->dispatch();
?>