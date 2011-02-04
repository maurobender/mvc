<?php
	class DefaultController extends Controller {
		function index() {
			$this->set('title_for_layout', 'MVC Version 0.1');
		}
		
		function error() {
			$this->set('title_for_layout', 'Error...');
			$this->set('errors', Core::getErrors());
		}
	}
?>