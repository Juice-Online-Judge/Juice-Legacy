<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	$is_implement = false;
	foreach ($_POST as $key => $value) {
		if (strpos($key, "implement") !== false) {
			$is_implement = true;
			break;
		}
	}
	
	echo ($is_implement) ? 'OK' : 'NO';
?>