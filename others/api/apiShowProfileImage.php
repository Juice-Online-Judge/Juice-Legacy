<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix."config/web_preprocess.php";
	
	page_check('api_ShowProfileImage');
	
	$uid = (preg_match("/^\d+$/", $_GET['uid'])) ? $_GET['uid'] : 0;
	$image = new account();
	$result = $image->show_profile_picture($uid);
	if (!isset($result['error']) and isset($result['image_data'])) {
		header('Content-type: '.$result['image_type']);
		echo $result['image_data'];
	} else {
		error(404);
		exit();
	}
?>