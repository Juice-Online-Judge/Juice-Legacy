<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
		page_check('lesson_handle');
	
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
							case 'id' :
								$data[$tmp[2]]['content'] = $value;
								break;
							case 'answer':
								$data[$tmp[2]]['answer'] = $value;
							default :
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
							case 'id' :
								$data[$tmp[2]]['content'] = $value;
								break;
							default :
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
		$lesson = new lesson();
		if ($_POST['type'] == 'lesson' and $_POST['action'] == 'add') {
			$result = $lesson->add_lesson($_POST['type'], $data);
		} else {
			$result = $lesson->update_lesson($_POST['type'], $data);
		}
	} else {
		$result = 'The page is invalid';
	}
	
	if ($result !== true) {
		echo $result;
	} else {
		header("Location: ".$prefix."juice/lesson/lesson_list.php");
		exit();
	}
?>