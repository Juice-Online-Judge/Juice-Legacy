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
					'key' => $_POST['key']
				);
				foreach ($_POST as $key => $value) {
					if (stripos($key, 'practice') !== false) {
						// $tmp[0] : practice, $tmp[1] : type, $tmp[2] : id
						$tmp = explode('_', $key);
						switch ($tmp[1]) {
							case 'action':
								$data[$tmp[2]]['action'] = $value;
								break;
							case 'key':
								$data[$tmp[2]]['key'] = $value;
								break;
							default :
								$data[$tmp[2]]['content'] = $value;
								break;
						}
					}
				}
				break;
			case 'implement':
				$data = array(
					'key' => $_POST['key']
				);
				foreach ($_POST as $key => $value) {
					if (stripos($key, 'implement') !== false) {
						// $tmp[0] : practice, $tmp[1] : type, $tmp[2] : id
						$tmp = explode('_', $key);
						switch ($tmp[1]) {
							case 'action':
								$data[$tmp[2]]['action'] = $value;
								break;
							case 'key':
								$data[$tmp[2]]['key'] = $value;
								break;
							case 'timeLimit':
								$data[$tmp[2]]['time_limit'] = $value;
								break;
							case 'memoryLimit':
								$data[$tmp[2]]['memory_limit'] = $value;
								break;
							case 'fileLimit':
								$data[$tmp[2]]['file_limit'] = $value;
								break;
							case 'mode':
								$data[$tmp[2]]['mode'] = $value;
								break;
							case 'otherLimit':
								$data[$tmp[2]]['other_limit'] = $value;
								break;
							default :
								$data[$tmp[2]]['content'] = $value;
								break;
						}
					}
				}
				break;
			default :
				$_POST['type'] = 'lesson';
				$data = array(
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
		}
		$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
		if ($_POST['type'] == 'lesson' and $_POST['action'] == 'add') {
			$result = $lesson->add_lesson($_POST['type'], $data);
		} else {
			$result = $lesson->update_lesson($_POST['type'], $data);
		}
	} else {
		$result['error'] = 'The page is invalid';
		$result = json_encode($result);
	}
	
	$result = json_decode($result);
	if (isset($result->{'error'})) {
		echo $result->{'error'};
	} else {
		header("Location: ".$prefix."juice/lesson/lesson_list.php");
		exit();
	}
	//echo $result;
?>