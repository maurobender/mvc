<?php
	class Error {
		protected static $_standardErrors = array(
			'MISSING_CONTROLLER' => "
Can't find the file for the controller <b>%CONTROLLER%</b>.
Please create the file <b>%CONTROLLER_FILE%</b> insde the controllers folder with
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
Can't find the layout <b>%LAYOUT%</b>, please create a file named <b>%LAYOUT_FILE%</b> inside
the <b>'views/layouts'</b> folder."
			, 'MISSING_DBSOURCE' => "
Can't find the file for the dbsource <b>%DBSOURCE%</b>.
Please create the file <b>%DBSOURCE_FILE%</b> inside the models folder."
			, 'MISSING_CONFIG' => "
Can't find the config file <b>%CONFIG%</b>, please create that file inside the <b>config</b> folder."
		);
		
		static function StandardError($error_type, $error_params = array(), array $options = array()) {
			$result = '';
			$options = array_merge(array('return' => false, 'type' => 'ERROR'), $options);
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
				case 'MISSING_LAYOUT':
					$search_params = array('/%LAYOUT%/', '/%LAYOUT_FILE%/');
					$replace_params = array($error_params['layout'], $error_params['layout'] . '.php');
					
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
				case 'MISSING_DBSOURCE':
					$search_params = array(
						'/%DBSOURCE%/', '/%DBSOURCE_FILE%/'
					);
					$replace_params = array(
						$error_params['dbsource'],
						$error_params['dbsource'] . '.php'
					);
					
					$result =  preg_replace($search_params, $replace_params, self::$_standardErrors[$error_type]);
					break;
				case 'MISSING_CONFIG':
					$search_params = array(
						'/%CONFIG%/'
					);
					$replace_params = array(
						$error_params['config'] . '.php'
					);
					
					$result =  preg_replace($search_params, $replace_params, self::$_standardErrors[$error_type]);
					break;
				default:
					break;
			}
			
			return error($result, $options['type'], $options['return']);
		}
	}
?>