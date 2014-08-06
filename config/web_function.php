<?php	
	function error($msg) {
		header("Location: ".WEB_ERROR_PAGE."?message=".urlencode($msg));
		exit();
	}
	
	function classFileLoader($dirpath) {
		if (is_dir($dirpath)) {
			if ($dir = opendir($dirpath)) {
				$file_name = array();
				while (($file = readdir($dir)) !== false) {
					if (stripos($file, '.class.php') !== false and $file != 'db.class.php') {
						array_push($file_name, $file);
					}
				}
				closedir($dir);
				return $file_name;
			}
		}
	}
	
	function eol_replace($data) {
		switch (gettype($data)) {
			case 'string':
				return str_replace(PHP_EOL, '', $data);
			case 'array':
				$len = count($data);
				for ($i=0;$i<$len;$i++) {
					$data[$i] = eol_replace($data[$i]);
				}
				return $data;
			default :
				return $data;
		}
	}
	
	function verify_code() {
		return mt_rand(1000000, 9999999);
	}
	
	function hash_key($type) {
		return hash($type, mt_rand());
	}
?>