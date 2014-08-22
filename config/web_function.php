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
	
	function permission_check($type) {
		switch ($type) {
			case 'login':
				return (isset($_SESSION['uid'])) ? true : false;
			case 'admin_groups':
				if (!isset($_SESSION['uid']) or !isset($_SESSION['admin_group'])) {
					return false;
				} else {
					return ($_SESSION['admin_group'] > 0) ? true : false;
				}
			case 'admin_groups_lesson':
				if (!isset($_SESSION['uid']) or !isset($_SESSION['admin_group'])) {
					return false;
				} else {
					switch ($_SESSION['admin_group']) {
						case 10:
						case 100:
							return true;
						default :
							return false;
					}
				}
			case 'admin_groups_root':
				if (!isset($_SESSION['uid']) or !isset($_SESSION['admin_group'])) {
					return false;
				} else {
					switch ($_SESSION['admin_group']) {
						case 100:
							return true;
						default :
							return false;
					}
				}
			default :
				return false;
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
	
	function set_cookie($name, $value, $time) {
		setcookie($name, $value, $current_time + $time, '/', '', false, true);
	}
	
	function verify_code() {
		return mt_rand(1000000, 9999999);
	}
	
	function hash_key($type) {
		return hash($type, mt_rand());
	}
?>