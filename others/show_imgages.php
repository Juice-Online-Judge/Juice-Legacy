<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix."config/web_preprocess.php";
	
	$image = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$result = $image->show_image($_GET['key'], $_GET['image_key']);
	if (!isset($result['error']) and isset($result['image_data'])) {
		header('Content-type: '.$result['image_type']);
		echo $result['image_data'];
	}
?>