<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	$logout = new account();
	$logout->logout();
	
	header("Location: ".$prefix."index.php");
	exit();
?>