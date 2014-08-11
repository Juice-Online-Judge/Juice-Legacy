<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	/*
	if (isset($_POST['unit']) and isset($_POST['level']) and isset($_POST['title']) and isset($_POST['goal']) and isset($_POST['content']) and isset($_POST['example']) and isset($_POST['practice']) and isset($_POST['implement'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_add_lesson']) and $_COOKIE['verify_code_add_lesson'] == $_POST['verify_code']) {
			$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $lesson->add_lesson($_POST['username'], $_POST['passward'], $remember);
			if ($message === true) {
				setcookie("verify_code_add_lesson", '', $current_time - 600, "/", WEB_DOMAIN_NAME);
				header("Location: ".$prefix."index.php");
				exit();
			}
		} else {
			$message = '登入頁面已失效，請重新登入';
		}
		setcookie("verify_code_add_lesson", verify_code(), $current_time + 1800, "/", WEB_DOMAIN_NAME);
	}*/
?>