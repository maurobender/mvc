<?php
	class HtmlHelper extends Helper {
		protected $_commonTags = array(
			'stylesheet' => '<link href="%1" rel="Stylesheet" type="text/css" />',
			'javascript' => '<script src="%1" type="text/javascript" language="Javascript"></script>'
		);
		
		public function css($name, array $options) {
			$css_path = CSS_FOLDER . DS . $name . '.css';
			$result = str_replace('%1', $css_path, $_commonTags);
			
			if(in_array('inline', $options) && $options['inline']) {
				$this->_view->addScript($result);
			} else {
				return $result;
			}
		}
		
		public function js($name, array $options) {
			$jss_path = JS_FOLDER . DS . $name . '.js';
			$result = str_replace('%1', $js_path, $_commonTags);
			
			if(in_array('inline', $options) && $options['inline']) {
				$this->_view->addScript($result);
			} else {
				return $result;
			}
		}
		
		public function url(array $url, array $params = array(), array $named = array(), $absolute = false) {
			$return_url = $absolute ? APP_ABSOLUTE_URL : APP_BASE_URL;
			
			if(isset($url['controller'])) {
				$return_url .= '/' . $url['controller'];
				
				if(isset($url['action'])) {
					$return_url .= '/' . $url['action'];
				} else {
					$return_url .= '/' . 'index';
				}
				
				foreach($params as $param) {
					$return_url .= '/' . $param;
				}
				
				foreach($named as $name => $value) {
					$return_url .= '/' . $name . ':' . $value;
				}
			}
			
			return $return_url;
		}
	}
?>