<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!isset($_SESSION['uid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!($_SESSION['admin_group'] > 3)) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_lesson_refine']) and $_COOKIE['verify_code_lesson_refine'] == $_POST['verify_code']) {
		switch ($_POST['type']) {
			case 'practice':
				$data = array(
					'action' => $_POST['action'],
					'key' => $_POST['key']
				);
				foreach ($_POST as $key => $value) {
					if (stripos($key, 'practice') !== false) {
						$data[$key] = $value;
					}
				}
				break;
			case 'implement':
				$data = array(
					'action' => $_POST['action'],
					'key' => $_POST['key']
				);
				foreach ($_POST as $key => $value) {
					if (stripos($key, 'implement') !== false) {
						$data[$key] = $value;
					}
				}
				break;
			case 'lesson':
				$data = array(
					'action' => $_POST['action'],
					'level' => $_POST['level'],
					'title' => $_POST['title'],
					'goal' => $_POST['goal'],
					'content' => $_POST['content'],
					'example' => $_POST['example']
				);
				if ($_POST['action'] == 'add') {
					$data['unit'] = $_POST['unit'];
				} else {
					$data['key'] = $_POST['key'];
				}
				break;
			default :
				$_POST['type'] = 'lesson';
				break;
		}
		$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
		$result = $lesson->add_lesson($_POST['type'], $data);
	} else {
		$result['error'] = 'The page is invalid';
		$result = json_encode($result);
	}
	
	echo $result;
?>