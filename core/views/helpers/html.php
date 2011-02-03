<?php
	class HtmlHelper extends Helper {
		protected $_commonTags = array(
			'stylesheet' => '<link rel="stylesheet" type="text/css" href="%1" />',
			'javascript' => '<script src="%1" type="text/javascript" language="Javascript"></script>'
		);
		
		public function css($name, array $options = array()) {
			$css_path = CSS_URL . '/' . $name . '.css';
			$result = str_replace('%1', $css_path, $this->_commonTags['stylesheet']);
			
			$options = array_merge(array('inline' => true), $options);
			
			if(isset($options['inline']) && $options['inline']) {
				return $result;
			} else {
				$this->_view->addScript($result);
			}
		}
		
		public function js($name, array $options = array()) {
			$jss_path = JS_URL . DS . $name . '.js';
			$result = str_replace('%1', $js_path, $this->_commonTags['javascript']);
			
			$options = array_merge(array('inline' => true), $options);
			
			if(in_array('inline', $options) && $options['inline']) {
				return $result;
			} else {
				$this->_view->addScript($result);
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