<?php	
	function error($type) {
		switch ($type) {
			case 401:
				header("Location: " . WEB_ROOT_DIR . "user/login.php");
				exit();
			case 403:
				header("Location: " . WEB_ROOT_DIR . "index.php");
				exit();
			case 404:
				header("Location: http://juice.cs.ccu.edu.tw/Juice/error/404.html");
				exit();
			default :
				header("Location: " . WEB_ROOT_DIR . "index.php");
				exit();
		}
	}
	
	function permission_check($type) {
		switch ($type) {
			case 'login':
				return (isset($_SESSION['uid'])) ? true : false;
			case 'admin_groups':
				if (!isset($_SESSION['uid']) or !isset($_SESSION['admin_group'])) {
					return false;
				}
				return ($_SESSION['admin_group'] > 0) ? true : false;
			case 'admin_groups_lesson':
				if (!isset($_SESSION['uid']) or !isset($_SESSION['admin_group'])) {
					return false;
				}
				switch ($_SESSION['admin_group']) {
					case 10:
					case 100:
						return true;
					default :
						return false;
				}
			case 'admin_groups_root':
				if (!isset($_SESSION['uid']) or !isset($_SESSION['admin_group'])) {
					return false;
				}
				switch ($_SESSION['admin_group']) {
					case 100:
						return true;
					default :
						return false;
				}
			default :
				return false;
		}
	}
	
	function page_check($page) {
		switch ($page) {
			case 'index':
			case 'user_member':
			case 'user_solve_status':
			case 'course':
			case 'course_list':
			case 'course_preprocess':
				if (!permission_check('login')) {
					error(401);
				}
				break;
				
			case 'user_register':
			case 'user_login':
				if (permission_check('login')) {
					error(403);
				}
				break;
			
			case 'about_refine':
				if (!permission_check('login')) {
					error(401);
				} else if (!permission_check('admin_groups')) {
					error(403);
				}
				break;
				
			case 'lesson_list':
			case 'lesson_refine':
			case 'lesson_handle':
			case 'lesson_image_list':
			case 'lesson_image_refine':
				if (!permission_check('login')) {
					error(401);
				} else if (!permission_check('admin_groups_lesson')) {
					error(403);
				}
				break;
				
			case 'ann_refine':
				if (!permission_check('login')) {
					error(401);
				} else if (!permission_check('admin_groups')) {
					error(403);
				}
				break;
				
			case 'api_ShowCourseImage':
			case 'api_ShowProfileImage':
				if (!permission_check('login')) {
					error(401);
				}
				break;
		}
	}
	
	function email_check($email) {
		$result = true;
		$blacklist = array(
			'trbvm.com', 'soisz.com', 'my10minutemail.com', '10minutemail.davidxia.com',
			'mailnesia.com', 'tempmailer.de', 'fakeinbox.com', 'sharklasers.com',
			'guerrillamail.biz', 'guerrillamail.com', 'guerrillamail.de', 'sharklasers.net',
			'guerrillamail.org', 'guerrillamailblock.com', 'spam4.me', 'juice.cs'
		);
		
		if (!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email)) {
			$result = false;
		} else if (strpos($email, $blacklist) !== false) {
			$result = false;
		}
		
		return $result;
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
	
	function set_cookie($name, $value, $time) {
		setcookie($name, $value, ($GLOBALS['current_time'] + $time), '/', '', false, true);
	}
	
	function del_cookie($name) {
		setcookie($name, '', 0, '/', '', false, true);
	}
	
	function verify_code() {
		return mt_rand(1000000, 9999999);
	}
	
	function hash_key($type) {
		return hash($type, mt_rand());
	}
?>