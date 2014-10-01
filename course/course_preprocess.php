<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	foreach ($_POST as $key => $value) {
		if (strpos($key, "implement_a") !== false) {
			if (isset($_POST['implement_key']) {
			
			}
			break;
		} else if (strpos($key, "practice_a") !== false) {
			if (isset($_POST['practice_key']) {
			
			}
			break;
		}
	}
?>