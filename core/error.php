<?php
	class Error {
		protected static $_standardErrors = array(
			'MISSING_CONTROLLER' => "
Can't find the file for the controller %CONTROLLER%.
Please create the file %CONTROLLER_FILE% insde the controllers folder with
the next content:
&lt;?php
	class %CONTROLLER% extends Controller {
		var &#36;name = '%CONTROLLER_NAME%';
	}
?&gt;"
			, 'MISSING_MODEL' => "
Can't find the file for the model <b>%MODEL%</b>.
Please create the file <b>%MODEL_FILE%</b> inside the models folder with the next content:
&lt;?php
	class %MODEL% extends Model {
		var &#36;name = '%MODEL%';
		var &#36;name = 'model_table_name';
	}
?&gt;"	
			, 'MISSING_VIEW' => "
Can't find the view file for the action <b>%ACTION%</b>, please create a file named
<b>%VIEW_FILE%</b> inside the <b>%VIEW_FOLDER%</b> folder."
			, 'MISSING_ACTION' => "
Can't find the action <b>%ACTION%</b> inside the controller <b>%CONTROLLER%</b>.
Please define the action <b>%ACTION%</b> as next:
&lt;?php
	class %CONTROLLER% extends Controller {
		var &#36;name = '%CONTROLLER_NAME%';
		...
		
		function %ACTION% (... params ...) {
			.... action content ...
		}
		
		...
	}
?&gt;"	, 'MISSING_LAYOUT' => "
Can't find the layout %LAYOUT%, please create a file named %LAYOUT_FILE% inside
the %LAYOUTS_FOLDER% folder."
			, 'MISSING_DBSOURCE' => "
Can't find the file for the dbsource <b>%DBSOURCE%</b>.
Please create the file <b>%DBSOURCE_FILE%</b> inside the models folder."
		);
		
		static function StandardError($error_type, $error_params = array(), $return = false) {
			$result = '';
			switch($error_type) {
				case 'MISSING_CONTROLLER':
					$search_params = array(
						'/%CONTROLLER%/', '/%CONTROLLER_FILE%/', '/%CONTROLLER_NAME%/'
					);
					$replace_params = array(
						$error_params['controller'],
						'controllers/' . Core::underscore($error_params['controller']) . '.php',
						str_replace('Controller', '', $error_params['controller'])
					);
					
					$result =  preg_replace($search_params, $replace_params, self::$_standardErrors[$error_type]);
					break;
				case 'MISSING_ACTION':
					$search_params = array(
						'/%ACTION%/', '/%CONTROLLER%/', '/%CONTROLLER_FILE%/', '/%CONTROLLER_NAME%/'
					);
					$replace_params = array(
						$error_params['action'],
						$error_params['controller'],
						'controllers/' . Core::underscore($error_params['controller']) . '.php',
						str_replace('Controller', '', $error_params['controller'])
					);
					
					$result =  preg_replace($search_params, $replace_params, self::$_standardErrors[$error_type]);
					break;
				case 'MISSING_VIEW':
					$search_params = array(
						'/%ACTION%/', '/%VIEW_FILE%/', '/%VIEW_FOLDER%/'
					);
					$replace_params = array(
						$error_params['action'],
						$error_params['action'] . '.php',
						'views/' . Core::underscore($error_params['controller']) . DS .  $error_params['action'] .'.php'
					);
					
					$result =  preg_replace($search_params, $replace_params, self::$_standardErrors[$error_type]);
					break;
				case 'MISSING_MODEL':
					$search_params = array(
						'/%MODEL%/', '/%MODEL_FILE%/'
					);
					$replace_params = array(
						$error_params['model'],
						$error_params['model'] . '.php'
					);
					
					$result =  preg_replace($search_params, $replace_params, self::$_standardErrors[$error_type]);
					break;
					break;
				default:
					break;
			}
			
			return error($result, $return);
		}
	}
?>