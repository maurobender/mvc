<?php
	include_once('core/core.php');
	
	Core::Init();
	
	$dispatcher = new Dispatcher();
	$dispatcher->dispatch();
?>