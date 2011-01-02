<?php
	function error($text, $return = false) {
		$result = '<pre class="mvc-error">' . $text . '</pre>';
		
		if($return)
			return $result;
		else
			echo $result;
	}
	
	function debug($var) {
		echo '<pre class="mvc-error">' . print_r($var, true) . '</pre>';
	}
	
	
	class Error {
		static public $missing_controller = <<<MISSING_CONTROLLER_ERROR
Can't find the file for the controller %CONTROLLER%.
Please create the file %CONTROLLER_FILE% insde the controllers folder with
the next content:
&lt;?php
	class %CONTROLLER% extends Controller {
		var &#36;name = '%NAME%';
	}
?&gt;
MISSING_CONTROLLER_ERROR;
		
		static public $missing_action = <<<MISSING_ACTION_ERROR
Can't find the action %ACTION% inside the controller %CONTROLLER%.
Please define the action %ACTION% as next:
&lt;?php
	class %CONTROLLER% extends Controller {
		var &#36;name = '%NAME%';
		...
		
		function %ACTION% (... params ...) {
			.... action content ...
		}
		
		...
	}
?&gt;
MISSING_ACTION_ERROR;
	
	static public $missing_view = <<<MISSING_VIEW_ERROR
Can't find the view file for the action %ACTION%, please create a file named
%VIEW_FILE% inside the %VIEW_FOLDER% folder.
MISSING_VIEW_ERROR;

	static public $missing_layout = <<<MISSING_LAYOUT_ERROR
Can't find the layout %LAYOUT%, please create a file named %LAYOUT_FILE% inside
the %LAYOUTS_FOLDER% folder.
MISSING_LAYOUT_ERROR;
	}
?>