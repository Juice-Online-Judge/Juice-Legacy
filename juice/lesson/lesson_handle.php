<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (isset($_POST['unit']) and isset($_POST['level']) and isset($_POST['title']) and isset($_POST['goal']) and isset($_POST['content']) and isset($_POST['example']) and isset($_POST['practice']) and isset($_POST['implement'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_add_lesson']) and $_COOKIE['verify_code_add_lesson'] == $_POST['verify_code']) {
			if ($_POST['key'] == '') {
				$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
				$result = $lesson->add_lesson($_POST['unit'], $_POST['level'], $_POST['title'], $_POST['goal'], $_POST['content'], $_POST['example'], $_POST['practice'], $_POST['implement']);
			} else {
				$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
				$result = $lesson->update_lesson($_POST['key'], $_POST['level'], $_POST['title'], $_POST['goal'], $_POST['content'], $_POST['example'], $_POST['practice'], $_POST['implement']);
			}
		} else {
			$result['error'] = '新增頁面已失效';
			$result = json_encode($result);
		}
		return $result;
	}
?>