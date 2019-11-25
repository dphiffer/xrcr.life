<?php
if (! function_exists('dbug')) {
	// A simple debugging function, for logging variables regardless of type.
	// (20190131/dphiffer)
	// Usage: dbug($object, $array, $string);
	// sudo tail -f /var/log/apache2/error.log | sed 's/\\n/\n/g'
	function dbug() {
		$args = func_get_args();
		$out = array();
		foreach ($args as $arg) {
			if (! is_scalar($arg)) {
				$arg = print_r($arg, true);
			}
			$out[] = $arg;
		}
		$out = implode("\n", $out);
		error_log("\n$out");
	}
}
