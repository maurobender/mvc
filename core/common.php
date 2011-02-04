<?php
	function error($text, $type = 'ERROR', $return = false, $print_backtrace = false /*TODO: Implementar un depurado de backtrace.*/) {
		if($type == 'ERROR')
			$result = '<div class="mvc-error"><div class="mvc-error-title">Error</div><pre>' . $text . '</pre></div>';
		else
			$result = '<div class="mvc-warning"><div class="mvc-warning-title">Warning</div><pre>' . $text . '</pre></div>';
		
		if($return)
			return $result;
		else
			echo $result;
	}
	
	function debug($var) {
		echo '<div class="mvc-warning"><div class="mvc-warning-title">Debug</div><pre>' . print_r($var, true) . '</pre></div>';
	}
	
?>