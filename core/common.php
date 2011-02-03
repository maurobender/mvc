<?php
	function error($text, $return = false, $print_backtrace = false /*TODO: Implementar un depurado de backtrace.*/) {
		$result = '<pre class="mvc-error">' . $text . '</pre>';
		
		if($return)
			return $result;
		else
			echo $result;
	}
	
	function debug($var) {
		echo '<pre class="mvc-error">' . print_r($var, true) . '</pre>';
	}
	
?>