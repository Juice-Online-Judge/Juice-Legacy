<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix."config/web_preprocess.php";
	
	page_check('api_ShowCourseImage');
	
	$image = new lesson();
	$result = $image->show_image($_GET['key'], $_GET['image_key']);
	if (!isset($result['error']) and isset($result['image_data'])) {
		header('Content-type: '.$result['image_type']);
		echo $result['image_data'];
	} else {
		error(404);
		exit();
	}
?>