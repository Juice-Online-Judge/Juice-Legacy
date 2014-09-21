<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		$result = array(
			'error' => 'Permission denied.'
		);
	} else {
		if (!isset($_POST['key']) or !isset($_POST['is_implement'])) {
			$result = array(
				'error' => 'Wrong argument.'
			);
		} else {
			$get_data = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$result = $get_data->list_user_lesson_record($_POST['is_implement'], $_POST['key']);
		}
	}
	
	echo json_encode($result);
?>