<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix."config/web_preprocess.php";
	
	if (permission_check('login')) {
		$uid = (preg_match("/^\d+$/", $_GET['uid'])) ? $_GET['uid'] : 0;
		$image = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
		$result = $image->show_profile_picture($uid);
		if (!isset($result['error']) and isset($result['image_data'])) {
			header('Content-type: '.$result['image_type']);
			echo $result['image_data'];
		}
	}
?>